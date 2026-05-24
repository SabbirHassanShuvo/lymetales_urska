# Payment / Order Status Fix — Bugfix Design

## Overview

The `orders` table currently uses a single `status` column that conflates payment status with order
fulfillment status. This causes Stripe orders to be marked `paid` at creation time — before the
Stripe webhook fires — and prevents admins from managing the two concerns independently.

The fix introduces two dedicated columns (`order_status`, `payment_status`), corrects initialization
in `OrderGenerator::create()`, updates `WebhookController::handle()` to write only to
`payment_status`, updates the `Order` model, and adds admin API endpoints for independent status
management. The legacy `status` column is **retained but deprecated** (kept `null`-able, no longer
written to by application code) so that existing data is not destroyed and a future cleanup
migration can drop it safely.

---

## Glossary

- **Bug_Condition (C)**: The set of inputs that trigger the defect — specifically, any call to
  `OrderGenerator::create()` with `payment_method = 'stripe'`, which currently produces
  `status = 'paid'` before payment is confirmed.
- **Property (P)**: The desired post-condition — both `order_status` and `payment_status` MUST be
  `'pending'` immediately after order creation, regardless of payment method.
- **Preservation**: All behaviors unrelated to the two new status columns must remain byte-for-byte
  identical: coupon incrementing, financial calculations, `stripe_payment_intent_id` storage,
  webhook signature validation, and existing admin CRUD operations.
- **`OrderGenerator`**: `app/Services/OrderGenerator.php` — creates and persists an `Order` from
  validated checkout data.
- **`WebhookController`**: `app/Http/Controllers/API/WebhookController.php` — receives Stripe
  webhook events and updates order payment state.
- **`order_status`**: Fulfillment lifecycle of the order (`pending` → `processing` → `shipped` →
  `delivered` → `cancelled`). Managed by admins.
- **`payment_status`**: Payment lifecycle (`pending` → `paid` | `failed`). Managed by Stripe
  webhooks (Stripe orders) or by admins (COD orders).
- **COD**: Cash on Delivery — payment collected outside Stripe.

---

## Bug Details

### Bug Condition

The bug manifests when `OrderGenerator::create()` is called with `payment_method = 'stripe'`. The
function sets `status = 'paid'` immediately, before the Stripe webhook has confirmed payment. There
is also no `payment_status` column, so the webhook later overwrites the same `status` column,
making it impossible to distinguish fulfillment state from payment state.

**Formal Specification:**

```
FUNCTION isBugCondition(checkoutData)
  INPUT:  checkoutData — validated array from CheckoutRequest
  OUTPUT: boolean

  RETURN checkoutData['payment_method'] == 'stripe'
         AND Order is created with status = 'paid'
         AND no webhook confirmation has been received yet
END FUNCTION
```

### Examples

- **Stripe order created**: `payment_method = 'stripe'` → current code sets `status = 'paid'`
  immediately. Expected: `order_status = 'pending'`, `payment_status = 'pending'`.
- **COD order created**: `payment_method = 'cod'` → current code sets `status = 'pending'`.
  Expected: `order_status = 'pending'`, `payment_status = 'pending'` (no change in observable
  outcome, but now stored in the correct columns).
- **Webhook `checkout.session.completed`**: current code updates `status = 'paid'`. Expected:
  updates `payment_status = 'paid'`, leaves `order_status` untouched.
- **Webhook `payment_intent.payment_failed`**: current code updates `status = 'failed'`. Expected:
  updates `payment_status = 'failed'`, leaves `order_status` untouched.
- **Admin marks COD order paid**: no endpoint exists today. Expected: `PATCH
  /api/admin/orders/{id}/payment-status` sets `payment_status = 'paid'`.
- **Admin tries to set payment_status on a Stripe order**: Expected: 422 Unprocessable Entity —
  Stripe payment status is webhook-controlled.

---

## Expected Behavior

### Preservation Requirements

**Unchanged Behaviors:**

- `stripe_payment_intent_id` MUST continue to be stored on the order at creation time (for webhook
  lookup) and updated when `checkout.session.completed` fires.
- Coupon `used_count` MUST continue to be incremented inside the same DB transaction as order
  creation.
- All financial calculations (subtotal, shipping fee, fast production fee, discount, total) MUST
  remain unchanged.
- Webhook signature verification MUST continue to return `400` on invalid signatures.
- All existing admin CRUD routes (products, categories, coupons, settings) MUST be unaffected.
- Admin updating `order_status` for a Stripe order MUST be allowed (fulfillment is
  admin-controlled regardless of payment method).

**Scope:**

All inputs that do NOT involve the `status` column write path are completely unaffected. This
includes cart operations, coupon validation, the confirmation endpoint, and all non-order admin
operations.

---

## Hypothesized Root Cause

1. **Single-column design**: The original migration created one `status` column intended to cover
   both payment and fulfillment state. This was a schema design shortcut that became a bug when
   Stripe's async webhook model was introduced.

2. **Premature `paid` assignment in `OrderGenerator`**: `OrderGenerator::create()` contains
   `'status' => $checkoutData['payment_method'] === 'stripe' ? 'paid' : 'pending'`. This
   optimistically marks Stripe orders as paid before the webhook fires, which is incorrect.

3. **Webhook writes to the wrong semantic column**: `WebhookController::handle()` updates `status`
   when it should update only `payment_status`, leaving `order_status` untouched.

4. **No admin endpoint for independent status management**: There is no route or controller method
   to update `order_status` or `payment_status` separately, so admins have no way to manage
   fulfillment state without touching payment state.

---

## Correctness Properties

Property 1: Bug Condition — Order Creation Always Starts Pending

_For any_ call to `OrderGenerator::create()` where `isBugCondition(checkoutData)` is true (i.e.,
`payment_method = 'stripe'`), the fixed function SHALL create an order with `order_status =
'pending'` AND `payment_status = 'pending'`, never setting `payment_status = 'paid'` at creation
time.

**Validates: Requirements 2.1, 2.2**

Property 2: Preservation — Non-Status Fields Unchanged by Fix

_For any_ call to `OrderGenerator::create()` where `isBugCondition(checkoutData)` is false (i.e.,
`payment_method = 'cod'`), the fixed function SHALL produce an order whose financial fields
(subtotal, shipping_fee, fast_production_fee, discount, total), contact fields, items snapshot,
coupon_code, and stripe_payment_intent_id are identical to what the original function would have
produced, preserving all non-status behavior.

**Validates: Requirements 3.1, 3.3, 3.4**

Property 3: Preservation — Webhook Updates Only payment_status

_For any_ Stripe webhook event (`checkout.session.completed`, `payment_intent.succeeded`,
`payment_intent.payment_failed`), the fixed `WebhookController::handle()` SHALL update
`payment_status` and SHALL NOT modify `order_status`, preserving fulfillment state across all
webhook calls.

**Validates: Requirements 2.3, 2.4, 3.2**

---

## Fix Implementation

### 1. New Migration — Add `order_status` and `payment_status` Columns

**File**: `database/migrations/2026_06_10_000001_add_order_payment_status_to_orders_table.php`

**Changes**:
- Add `order_status` string column (20), default `'pending'`, after `status`.
  Allowed values: `pending`, `processing`, `shipped`, `delivered`, `cancelled`.
- Add `payment_status` string column (20), default `'pending'`, after `order_status`.
  Allowed values: `pending`, `paid`, `failed`.
- Make the existing `status` column **nullable** (deprecation, not removal) so existing rows are
  not broken. No data migration is needed — existing rows keep their old `status` value; new rows
  will leave `status` null.
- `down()` drops `order_status` and `payment_status`, and restores `status` to non-nullable.

```php
Schema::table('orders', function (Blueprint $table) {
    $table->string('order_status', 20)->default('pending')->after('status');
    $table->string('payment_status', 20)->default('pending')->after('order_status');
    $table->string('status', 20)->nullable()->change(); // deprecate, not drop
});
```

### 2. Update `Order` Model

**File**: `app/Models/Order.php`

**Changes**:
- Add `order_status` and `payment_status` to `$fillable`.
- Remove `status` from `$fillable` (it is deprecated; no new code should write to it).
- No cast changes needed (both new columns are plain strings).

```php
protected $fillable = [
    'order_number',
    'order_status',    // replaces 'status'
    'payment_status',  // new
    'email',
    // ... rest unchanged
];
```

### 3. Fix `OrderGenerator::create()`

**File**: `app/Services/OrderGenerator.php`

**Function**: `create()`

**Specific Changes**:
- Remove the conditional `'status' => ... ? 'paid' : 'pending'` line entirely.
- Add `'order_status' => 'pending'` and `'payment_status' => 'pending'` for both COD and Stripe.
- Do not write to `status` at all.

```php
// Before (buggy):
'status' => $checkoutData['payment_method'] === 'stripe' ? 'paid' : 'pending',

// After (fixed):
'order_status'  => 'pending',
'payment_status' => 'pending',
```

### 4. Fix `WebhookController::handle()`

**File**: `app/Http/Controllers/API/WebhookController.php`

**Function**: `handle()`

**Specific Changes**:
- `checkout.session.completed`: change `'status' => 'paid'` to `'payment_status' => 'paid'`;
  remove the `where('status', '!=', 'paid')` guard and replace with
  `where('payment_status', '!=', 'paid')`.
- `payment_intent.succeeded`: same substitution.
- `payment_intent.payment_failed`: change `'status' => 'failed'` to `'payment_status' =>
  'failed'`; update the guard to `where('payment_status', '!=', 'failed')`.
- Do not write to `status` in any branch.

```php
// checkout.session.completed — after fix:
Order::where('order_number', $orderNumber)
    ->where('payment_status', '!=', 'paid')
    ->update([
        'payment_status'           => 'paid',
        'stripe_payment_intent_id' => $session->payment_intent,
    ]);

// payment_intent.payment_failed — after fix:
Order::where('stripe_payment_intent_id', $intentId)
    ->where('payment_status', '!=', 'failed')
    ->update(['payment_status' => 'failed']);
```

### 5. New Admin Controller — `OrderController`

**File**: `app/Http/Controllers/Admin/OrderController.php`

**Methods**:

```php
/**
 * PATCH /admin/orders/{order}/order-status
 * Updates order_status for any order (COD or Stripe).
 * Allowed values: pending, processing, shipped, delivered, cancelled
 */
public function updateOrderStatus(Request $request, Order $order): JsonResponse
{
    $validated = $request->validate([
        'order_status' => ['required', 'string', Rule::in([
            'pending', 'processing', 'shipped', 'delivered', 'cancelled'
        ])],
    ]);

    $order->update(['order_status' => $validated['order_status']]);

    return response()->json(['success' => true, 'order_status' => $order->order_status]);
}

/**
 * PATCH /admin/orders/{order}/payment-status
 * Updates payment_status for COD orders only.
 * Stripe order payment status is controlled exclusively by webhooks.
 */
public function updatePaymentStatus(Request $request, Order $order): JsonResponse
{
    if ($order->payment_method === 'stripe') {
        return response()->json([
            'success' => false,
            'message' => 'Payment status for Stripe orders is managed by webhooks.',
        ], 422);
    }

    $validated = $request->validate([
        'payment_status' => ['required', 'string', Rule::in(['pending', 'paid', 'failed'])],
    ]);

    $order->update(['payment_status' => $validated['payment_status']]);

    return response()->json(['success' => true, 'payment_status' => $order->payment_status]);
}
```

### 6. New Admin API Routes

**File**: `routes/api.php`

Add inside a new `admin` prefix group with `auth` + `admin` middleware:

```php
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::patch('orders/{order}/order-status',
        [\App\Http\Controllers\Admin\OrderController::class, 'updateOrderStatus'])
        ->name('admin.orders.order-status');

    Route::patch('orders/{order}/payment-status',
        [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])
        ->name('admin.orders.payment-status');
});
```

> **Note**: If the admin panel uses session-based auth (web guard) rather than Sanctum tokens,
> replace `auth:sanctum` with `auth` and ensure the API session middleware is active (it already
> is via `StartSession` in `bootstrap/app.php`).

---

## Testing Strategy

### Validation Approach

Testing follows a two-phase approach: first run exploratory tests against the **unfixed** code to
confirm the bug and root cause, then run fix-checking and preservation tests against the **fixed**
code.

### Exploratory Bug Condition Checking

**Goal**: Surface counterexamples that demonstrate the bug on unfixed code. Confirm the root cause
before implementing the fix.

**Test Plan**: Call `OrderGenerator::create()` with `payment_method = 'stripe'` and assert that
the resulting order does NOT have `status = 'paid'`. These tests will fail on unfixed code,
confirming the root cause.

**Test Cases**:

1. **Stripe Order Status Test**: Create a Stripe order and assert `order->status != 'paid'`
   immediately after creation (will fail on unfixed code — confirms bug).
2. **Webhook Column Test**: Fire a `checkout.session.completed` event and assert that `status`
   column is NOT updated (will fail on unfixed code — confirms webhook writes wrong column).
3. **COD Order Status Test**: Create a COD order and assert `order->status == 'pending'` (passes
   on unfixed code — establishes baseline).

**Expected Counterexamples**:
- `OrderGenerator::create()` with `payment_method = 'stripe'` returns an order with
  `status = 'paid'` — confirms premature paid assignment.
- `WebhookController::handle()` with `checkout.session.completed` updates `status` instead of
  `payment_status` — confirms wrong column write.

### Fix Checking

**Goal**: Verify that for all inputs where the bug condition holds, the fixed function produces the
expected behavior.

**Pseudocode:**

```
FOR ALL checkoutData WHERE isBugCondition(checkoutData) DO
  order := OrderGenerator_fixed.create(checkoutData, cartItems, coupon)
  ASSERT order.order_status  == 'pending'
  ASSERT order.payment_status == 'pending'
END FOR
```

**Test Cases**:

1. **Stripe order → both statuses pending**: `payment_method = 'stripe'` must yield
   `order_status = 'pending'` and `payment_status = 'pending'`.
2. **Webhook sets payment_status only**: `checkout.session.completed` must set
   `payment_status = 'paid'` and leave `order_status = 'pending'`.
3. **Failed webhook sets payment_status only**: `payment_intent.payment_failed` must set
   `payment_status = 'failed'` and leave `order_status = 'pending'`.
4. **Admin updates order_status**: `PATCH /admin/orders/{id}/order-status` with
   `order_status = 'shipped'` must update only `order_status`.
5. **Admin updates COD payment_status**: `PATCH /admin/orders/{id}/payment-status` with
   `payment_status = 'paid'` on a COD order must update only `payment_status`.
6. **Admin blocked from updating Stripe payment_status**: same endpoint on a Stripe order must
   return 422.

### Preservation Checking

**Goal**: Verify that for all inputs where the bug condition does NOT hold, the fixed function
produces the same result as the original function.

**Pseudocode:**

```
FOR ALL checkoutData WHERE NOT isBugCondition(checkoutData) DO
  order_original := OrderGenerator_original.create(checkoutData, cartItems, coupon)
  order_fixed    := OrderGenerator_fixed.create(checkoutData, cartItems, coupon)
  ASSERT order_original.subtotal            == order_fixed.subtotal
  ASSERT order_original.total               == order_fixed.total
  ASSERT order_original.coupon_code         == order_fixed.coupon_code
  ASSERT order_original.stripe_payment_intent_id == order_fixed.stripe_payment_intent_id
  ASSERT order_original.items               == order_fixed.items
END FOR
```

**Testing Approach**: Property-based testing is recommended for preservation checking because:
- It generates many random cart configurations and coupon combinations automatically.
- It catches edge cases (zero discount, free shipping coupon, fast production fee) that manual
  tests might miss.
- It provides strong guarantees that financial calculations are unchanged across all non-buggy
  inputs.

**Test Cases**:

1. **COD financial preservation**: For random cart items and optional coupons, COD order
   financials must match original calculation exactly.
2. **Stripe financial preservation**: For random cart items, Stripe order financials must match
   original calculation exactly (only status columns differ).
3. **Coupon increment preservation**: When a coupon is applied, `used_count` must be incremented
   exactly once, same as before.
4. **Webhook signature rejection preservation**: Invalid Stripe signature must still return 400.
5. **stripe_payment_intent_id preservation**: Stripe orders must still store the intent ID at
   creation and on webhook update.

### Unit Tests

- `OrderGenerator::create()` with `payment_method = 'stripe'` sets `order_status = 'pending'`
  and `payment_status = 'pending'`.
- `OrderGenerator::create()` with `payment_method = 'cod'` sets `order_status = 'pending'` and
  `payment_status = 'pending'`.
- `WebhookController::handle()` with `checkout.session.completed` updates `payment_status = 'paid'`
  and does not change `order_status`.
- `WebhookController::handle()` with `payment_intent.payment_failed` updates
  `payment_status = 'failed'` and does not change `order_status`.
- `OrderController::updateOrderStatus()` updates `order_status` for both COD and Stripe orders.
- `OrderController::updatePaymentStatus()` updates `payment_status` for COD orders.
- `OrderController::updatePaymentStatus()` returns 422 for Stripe orders.

### Property-Based Tests

- Generate random valid cart item arrays and verify that `OrderGenerator::create()` always
  produces `order_status = 'pending'` and `payment_status = 'pending'` for any payment method.
- Generate random financial inputs (prices, quantities, coupon discounts, shipping flags) and
  verify that the fixed `OrderGenerator` produces identical totals to the original for COD orders.
- Generate random `order_status` values and verify that `updateOrderStatus()` always accepts valid
  values and rejects invalid ones.

### Integration Tests

- Full COD checkout flow: place order → verify `order_status = 'pending'`, `payment_status =
  'pending'` → admin updates `order_status` to `'shipped'` → verify `payment_status` unchanged.
- Full Stripe checkout flow: place order → verify both statuses `'pending'` → fire
  `checkout.session.completed` webhook → verify `payment_status = 'paid'`, `order_status` still
  `'pending'`.
- Admin payment status guard: attempt to update `payment_status` on a Stripe order via API →
  verify 422 response.
