# Implementation Plan

## Overview

This plan fixes the premature `paid` status bug in Stripe orders and introduces separate `order_status` and `payment_status` columns. The workflow follows the exploratory bugfix methodology: write tests against unfixed code first, then implement the fix, then verify.

## Task Dependency Graph

```json
{
  "waves": [
    { "wave": 1, "tasks": ["1", "2"] },
    { "wave": 2, "tasks": ["3"] },
    { "wave": 3, "tasks": ["4"] }
  ]
}
```

Tasks 1 and 2 are independent and can be written in parallel. Task 3 depends on the understanding gained from tasks 1 and 2. Task 4 depends on all of task 3.

## Tasks

- [x] 1. Write bug condition exploration test
  - **Property 1: Bug Condition** - Stripe Order Created With `paid` Status
  - **CRITICAL**: This test MUST FAIL on unfixed code — failure confirms the bug exists
  - **DO NOT attempt to fix the test or the code when it fails**
  - **NOTE**: This test encodes the expected behavior — it will validate the fix when it passes after implementation
  - **GOAL**: Surface counterexamples that demonstrate the premature `paid` assignment bug
  - **Scoped PBT Approach**: Scope the property to the concrete failing case — `payment_method = 'stripe'` with any valid cart and coupon combination
  - Create a feature test that calls `OrderGenerator::create()` with `payment_method = 'stripe'` and asserts `order->order_status == 'pending'` AND `order->payment_status == 'pending'`
  - Also assert that `order->status` is NOT `'paid'` (the legacy column should not be set to paid)
  - Run test on UNFIXED code
  - **EXPECTED OUTCOME**: Test FAILS with something like `order->status = 'paid'` and `order_status` / `payment_status` columns do not exist — this proves the bug exists
  - Document counterexamples found (e.g., `OrderGenerator::create(['payment_method' => 'stripe', ...])` returns order with `status = 'paid'` before any webhook fires)
  - Mark task complete when test is written, run, and failure is documented
  - _Requirements: 1.1, 2.1_

- [x] 2. Write preservation property tests (BEFORE implementing fix)
  - **Property 2: Preservation** - Non-Status Fields Unchanged by Fix
  - **IMPORTANT**: Follow observation-first methodology
  - Observe: `OrderGenerator::create()` with `payment_method = 'cod'` and random cart items produces correct subtotal, shipping_fee, fast_production_fee, discount, total, coupon_code, stripe_payment_intent_id, and items snapshot on unfixed code
  - Observe: `WebhookController::handle()` with an invalid Stripe signature returns HTTP 400 on unfixed code
  - Write property-based test: for all COD orders with random cart items and optional coupons, the financial fields (subtotal, shipping_fee, fast_production_fee, discount, total) must match the expected calculation — `subtotal = sum(line_total)`, `total = subtotal - discount + shipping_fee + fast_production_fee`
  - Write property-based test: for all orders with a coupon applied, `coupon->used_count` is incremented exactly once inside the same DB transaction
  - Write unit test: invalid Stripe webhook signature returns 400 (baseline preservation)
  - Write unit test: `stripe_payment_intent_id` is stored on the order at creation time for Stripe orders
  - Run all tests on UNFIXED code
  - **EXPECTED OUTCOME**: Tests PASS (confirms baseline behavior to preserve)
  - Mark task complete when tests are written, run, and passing on unfixed code
  - _Requirements: 3.1, 3.3, 3.4, 3.5_

- [x] 3. Fix for premature `paid` status and missing order/payment status separation

  - [x] 3.1 Add migration for `order_status` and `payment_status` columns
    - Create `database/migrations/2026_06_10_000001_add_order_payment_status_to_orders_table.php`
    - Add `order_status` string(20) column with default `'pending'` after `status` — allowed values: `pending`, `processing`, `shipped`, `delivered`, `cancelled`
    - Add `payment_status` string(20) column with default `'pending'` after `order_status` — allowed values: `pending`, `paid`, `failed`
    - Make existing `status` column nullable (deprecation, not removal) so existing rows are not broken
    - `down()` must drop `order_status` and `payment_status`, and restore `status` to non-nullable
    - Run `php artisan migrate` to apply
    - _Bug_Condition: isBugCondition(checkoutData) where checkoutData['payment_method'] == 'stripe' AND order is created with status = 'paid' before webhook fires_
    - _Expected_Behavior: order_status = 'pending' AND payment_status = 'pending' immediately after creation for any payment method_
    - _Preservation: existing rows keep their old status value; no data migration needed_
    - _Requirements: 2.1, 2.2_

  - [x] 3.2 Update `Order` model
    - File: `app/Models/Order.php`
    - Add `order_status` and `payment_status` to `$fillable`
    - Remove `status` from `$fillable` (deprecated — no new code should write to it)
    - _Requirements: 2.1, 2.2, 2.3, 2.4_

  - [x] 3.3 Fix `OrderGenerator::create()` — remove premature `paid` assignment
    - File: `app/Services/OrderGenerator.php`
    - Remove the line: `'status' => $checkoutData['payment_method'] === 'stripe' ? 'paid' : 'pending'`
    - Replace with: `'order_status' => 'pending'` and `'payment_status' => 'pending'`
    - Do NOT write to `status` at all
    - _Bug_Condition: isBugCondition(checkoutData) where checkoutData['payment_method'] == 'stripe'_
    - _Expected_Behavior: order_status = 'pending' AND payment_status = 'pending' for both Stripe and COD orders_
    - _Preservation: coupon used_count increment, all financial calculations, stripe_payment_intent_id storage, and items snapshot remain unchanged_
    - _Requirements: 2.1, 2.2, 3.1, 3.3, 3.4_

  - [x] 3.4 Fix `WebhookController::handle()` — write to `payment_status` only
    - File: `app/Http/Controllers/API/WebhookController.php`
    - `checkout.session.completed` branch: change `'status' => 'paid'` to `'payment_status' => 'paid'`; change guard from `where('status', '!=', 'paid')` to `where('payment_status', '!=', 'paid')`
    - `payment_intent.succeeded` branch: same substitution as above
    - `payment_intent.payment_failed` branch: change `'status' => 'failed'` to `'payment_status' => 'failed'`; change guard from `where('status', '!=', 'failed')` to `where('payment_status', '!=', 'failed')`
    - Do NOT write to `status` in any branch
    - _Bug_Condition: webhook updating status column instead of payment_status, conflating payment state with fulfillment state_
    - _Expected_Behavior: payment_status updated by webhook; order_status left untouched_
    - _Preservation: stripe_payment_intent_id still stored on checkout.session.completed; signature validation still returns 400 on invalid signature_
    - _Requirements: 2.3, 2.4, 3.2, 3.5_

  - [x] 3.5 Create `OrderController` for admin status management
    - File: `app/Http/Controllers/Admin/OrderController.php`
    - Implement `updateOrderStatus(Request $request, Order $order): JsonResponse`
      - Validates `order_status` against allowed values: `pending`, `processing`, `shipped`, `delivered`, `cancelled`
      - Updates `order->order_status` for any order (COD or Stripe)
      - Returns `{ success: true, order_status: '...' }`
    - Implement `updatePaymentStatus(Request $request, Order $order): JsonResponse`
      - Returns 422 with message if `order->payment_method === 'stripe'` (Stripe payment status is webhook-controlled)
      - Validates `payment_status` against allowed values: `pending`, `paid`, `failed`
      - Updates `order->payment_status` for COD orders only
      - Returns `{ success: true, payment_status: '...' }`
    - _Requirements: 2.5, 2.6, 2.7, 3.6_

  - [x] 3.6 Register admin routes
    - File: `routes/api.php`
    - Add a new `admin` prefix group with `auth:sanctum` (or `auth` if using session-based web guard) and `admin` middleware
    - Register `PATCH /admin/orders/{order}/order-status` → `OrderController@updateOrderStatus`
    - Register `PATCH /admin/orders/{order}/payment-status` → `OrderController@updatePaymentStatus`
    - _Requirements: 2.5, 2.6, 2.7_

  - [x] 3.7 Verify bug condition exploration test now passes
    - **Property 1: Expected Behavior** - Stripe Order Created With `pending` Statuses
    - **IMPORTANT**: Re-run the SAME test from task 1 — do NOT write a new test
    - The test from task 1 encodes the expected behavior: `order_status = 'pending'` AND `payment_status = 'pending'` for Stripe orders
    - When this test passes, it confirms the premature `paid` assignment is fixed
    - Run bug condition exploration test from step 1
    - **EXPECTED OUTCOME**: Test PASSES (confirms bug is fixed)
    - _Requirements: 2.1, 2.2_

  - [x] 3.8 Verify preservation tests still pass
    - **Property 2: Preservation** - Non-Status Fields Unchanged by Fix
    - **IMPORTANT**: Re-run the SAME tests from task 2 — do NOT write new tests
    - Run all preservation property tests from step 2
    - **EXPECTED OUTCOME**: All tests PASS (confirms no regressions in financial calculations, coupon handling, stripe_payment_intent_id storage, and webhook signature validation)
    - Confirm all tests still pass after fix (no regressions)

- [ ] 4. Checkpoint — Ensure all tests pass
  - Run the full test suite: `php artisan test`
  - Verify Property 1 (bug condition) passes — Stripe orders now start with `order_status = 'pending'` and `payment_status = 'pending'`
  - Verify Property 2 (preservation) passes — financial calculations, coupon increments, and webhook signature rejection are unchanged
  - Verify webhook tests pass — `checkout.session.completed` sets `payment_status = 'paid'` without touching `order_status`; `payment_intent.payment_failed` sets `payment_status = 'failed'` without touching `order_status`
  - Verify admin endpoint tests pass — `updateOrderStatus` works for both COD and Stripe orders; `updatePaymentStatus` works for COD and returns 422 for Stripe
  - Ensure all tests pass; ask the user if questions arise
