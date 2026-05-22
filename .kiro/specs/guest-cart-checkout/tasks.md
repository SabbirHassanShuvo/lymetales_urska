# Implementation Plan: Guest Cart & Checkout

## Overview

Implement a complete guest e-commerce cart and checkout flow for the existing Laravel 12 application. The feature covers session-based cart management, coupon validation, COD and Stripe payment, order persistence, and a confirmation page — all without requiring user authentication.

## Tasks

- [x] 1. Database migration and Order model
  - [x] 1.1 Create the orders table migration
    - Create `database/migrations/2026_XX_XX_000001_create_orders_table.php`
    - Define all columns: `order_number` (unique indexed), `status`, contact/shipping fields, `items` (JSON), financial decimals (`subtotal`, `shipping_fee`, `fast_production_fee`, `discount`, `coupon_code`, `total`), `payment_method`, `stripe_payment_intent_id` (nullable), timestamps
    - Add a regular index on `stripe_payment_intent_id` for fast webhook lookups
    - _Requirements: 7.3, 8.4, 9.4_

  - [x] 1.2 Create the Order Eloquent model
    - Create `app/Models/Order.php` extending `Model`
    - Set `$fillable` for all columns
    - Add `$casts`: `items` → `array`, all decimal columns → `decimal:2`
    - _Requirements: 7.1, 8.2_

- [x] 2. Configuration and exception classes
  - [ ] 2.1 Create `config/shop.php`
    - Define keys: `currency`, `currency_symbol`, `shipping_fee`, `fast_production_fee`, `cities` (array of Dutch cities), `order_number_prefix`, `order_number_length`, `order_number_retries`, `cart_session_key`, `coupon_session_key`
    - All fee/currency values read from `.env` with sensible defaults
    - _Requirements: 2.2, 2.3, 3.5, 9.2_

  - [x] 2.2 Create exception classes
    - Create `app/Exceptions/CartException.php` (extends `\RuntimeException`)
    - Create `app/Exceptions/CouponException.php` (extends `\RuntimeException`)
    - Create `app/Exceptions/OrderException.php` (extends `\RuntimeException`)
    - Create `app/Exceptions/StripeException.php` (extends `\RuntimeException`)
    - _Requirements: 1.8, 5.7–5.10, 7.6, 8.7_

- [x] 3. Install Stripe SDK and update environment config
  - [x] 3.1 Install `stripe/stripe-php` via Composer
    - Run `composer require stripe/stripe-php:^13.0`
    - _Requirements: 8.1_

  - [x] 3.2 Update `config/services.php` with Stripe keys
    - Add/update the `stripe` array: `key` → `env('STRIPE_KEY')`, `secret` → `env('STRIPE_SECRET')`, `webhook_secret` → `env('STRIPE_WEBHOOK_SECRET')`
    - _Requirements: 8.1, 8.10_

  - [x] 3.3 Update `.env.example` with new environment variables
    - Add `SHOP_CURRENCY`, `SHOP_CURRENCY_SYMBOL`, `SHOP_SHIPPING_FEE`, `SHOP_FAST_PRODUCTION_FEE`, `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`
    - _Requirements: 2.2, 8.1_

- [x] 4. CartManager service
  - [x] 4.1 Implement `CartManager`
    - Create `app/Services/CartManager.php`
    - Implement `add(int $productId, int $quantity = 1): void` — reads product from DB, locks unit price, increments if already in cart, caps at 99, throws `CartException` if product inactive/not found
    - Implement `update(int $productId, int $quantity): void` — updates quantity, removes item if ≤ 0, caps at 99
    - Implement `remove(int $productId): void`
    - Implement `items(): array`, `count(): int`, `subtotal(): float`, `isEmpty(): bool`, `clear(): void`
    - Store cart under `config('shop.cart_session_key')`; all monetary values rounded to 2 decimal places
    - _Requirements: 1.1–1.9, 2.1, 2.5_

  - [ ]* 4.2 Write property test for CartManager quantity bounds (Property 2)
    - **Property 2: Cart Quantity Bounds**
    - For any sequence of `add()` / `update()` calls, every item's quantity satisfies `1 ≤ quantity ≤ 99`
    - **Validates: Requirements 1.7**

  - [ ]* 4.3 Write property test for CartManager subtotal accuracy (Property 3)
    - **Property 3: Cart Subtotal Accuracy**
    - After any combination of `add()`, `update()`, `remove()`, `subtotal()` equals `Σ (unit_price × quantity)` rounded to 2 decimal places
    - **Validates: Requirements 2.1**

  - [ ]* 4.4 Write unit tests for CartManager
    - Test `add()` increments existing item quantity
    - Test `update()` removes item when quantity ≤ 0
    - Test `add()` throws `CartException` for inactive product
    - Test `clear()` empties the session
    - Test `count()` returns sum of all quantities
    - _Requirements: 1.1–1.9_

- [x] 5. CouponValidator service and CouponResult value object
  - [x] 5.1 Implement `CouponResult` value object
    - Create `app/Services/CouponResult.php`
    - Readonly properties: `code` (string), `type` (string), `discount` (float), `freeShipping` (bool)
    - _Requirements: 5.2–5.5_

  - [x] 5.2 Implement `CouponValidator`
    - Create `app/Services/CouponValidator.php`
    - Implement `validate(string $code, float $subtotal): CouponResult`
    - Ordered checks: case-insensitive lookup → status → expiry (`Coupon::isExpired()`) → usage limit
    - Throw `CouponException` with exact messages from requirements for each failure case
    - Calculate discount: `percent` → `round((value/100) × subtotal, 2)`; `fixed` → `min(round(value, 2), subtotal)`; `free_shipping` → discount = 0, freeShipping = true
    - _Requirements: 5.1–5.11_

  - [ ]* 5.3 Write property test for coupon discount cap (Property 4)
    - **Property 4: Coupon Discount Cap**
    - For any fixed coupon value F and subtotal S: `discount = min(round(F, 2), S)`, so post-discount subtotal is always ≥ 0
    - **Validates: Requirements 5.4**

  - [ ]* 5.4 Write unit tests for CouponValidator
    - Test each error branch: not found, inactive, expired, usage limit reached
    - Test percent discount calculation with known values
    - Test fixed discount capped at subtotal
    - Test free_shipping sets freeShipping = true and discount = 0
    - _Requirements: 5.1–5.11_

- [ ] 6. OrderGenerator service
  - [ ] 6.1 Implement `OrderGenerator`
    - Create `app/Services/OrderGenerator.php`
    - Implement private `generateOrderNumber(): string` — draws from `[A-Z0-9]` charset, retries up to `config('shop.order_number_retries')` times, throws `OrderException` on exhaustion
    - Implement `create(array $checkoutData, array $cartItems, ?array $coupon): Order`
      - Calls `generateOrderNumber()`
      - Calculates totals (subtotal, shipping_fee, fast_production_fee, discount, total)
      - Wraps `Order::create()` and `Coupon::increment('used_count')` in a DB transaction
      - Throws `OrderException` on DB failure
    - _Requirements: 7.1–7.6, 8.2–8.4, 9.1–9.4, 11.1–11.2_

  - [ ]* 6.2 Write property test for order number format (Property 7)
    - **Property 7: Order Number Format**
    - Every generated order number matches `^LYM-[A-Z0-9]{8}$`
    - **Validates: Requirements 7.2, 8.3**

  - [ ]* 6.3 Write unit tests for OrderGenerator
    - Test order number uniqueness retry logic (mock `Order::where()->exists()` to return true N times then false)
    - Test `OrderException` thrown after max retries
    - Test coupon `used_count` incremented inside transaction
    - Test DB rollback on insert failure
    - _Requirements: 9.1–9.4, 11.1_

- [ ] 7. StripeGateway service
  - [ ] 7.1 Implement `StripeGateway`
    - Create `app/Services/StripeGateway.php`
    - Implement `createPaymentIntent(int $amountCents, string $currency): string`
      - Sets `Stripe::setApiKey(config('services.stripe.secret'))`
      - Creates `PaymentIntent` with `automatic_payment_methods` enabled
      - Returns `client_secret`; wraps Stripe exceptions in `StripeException`
    - Implement `constructWebhookEvent(string $payload, string $sigHeader): \Stripe\Event`
      - Calls `Webhook::constructEvent()` with `config('services.stripe.webhook_secret')`
      - Lets `SignatureVerificationException` propagate to caller
    - _Requirements: 8.1, 8.10, 8.11_

  - [ ]* 7.2 Write unit tests for StripeGateway
    - Test `constructWebhookEvent()` with valid signature returns event
    - Test `constructWebhookEvent()` with invalid signature throws `SignatureVerificationException`
    - _Requirements: 8.10, 8.11_

- [ ] 8. Register services in AppServiceProvider
  - [ ] 8.1 Bind service classes in `AppServiceProvider`
    - Open `app/Providers/AppServiceProvider.php`
    - Bind `CartManager`, `CouponValidator`, `OrderGenerator`, `StripeGateway` into the service container (singleton for `CartManager` so the same instance is shared per request)
    - _Requirements: 1.1, 5.1, 7.1, 8.1_

- [ ] 9. CheckoutRequest form request
  - [ ] 9.1 Create `CheckoutRequest`
    - Create `app/Http/Requests/CheckoutRequest.php`
    - Define `authorize()` → `true`
    - Define `rules()`: `email` (required, email:rfc,dns), `full_name` (required, max:100), `address` (required, max:255), `city` (required, in: config cities), `postal_code` (required, max:20), `country` (required, max:100), `phone` (required, regex:`/^[+\d\s\-]{1,20}$/`), `payment_method` (required, in:cod,stripe), `fast_production` (sometimes, boolean), `stripe_payment_intent_id` (requiredIf payment_method=stripe, nullable, string)
    - _Requirements: 3.1–3.7, 6.1–6.4_

- [ ] 10. Controllers
  - [ ] 10.1 Create `CartController`
    - Create `app/Http/Controllers/Shop/CartController.php`
    - Constructor injects `CartManager`
    - `index()`: returns `shop.cart` view with cart items, subtotal, shipping fee, fast production fee from config
    - `add(Request $request)`: validates `product_id` and `quantity`, calls `CartManager::add()`, returns JSON `{success, message, cart_count}`; catches `CartException` → JSON 422
    - `update(Request $request)`: validates inputs, calls `CartManager::update()`, returns JSON `{success, item_total, subtotal, total, cart_count}` with formatted currency values
    - `remove(Request $request)`: validates `product_id`, calls `CartManager::remove()`, returns JSON `{success, subtotal, total, cart_count}`
    - _Requirements: 1.1–1.9, 2.1–2.7_

  - [ ] 10.2 Create `CheckoutController`
    - Create `app/Http/Controllers/Shop/CheckoutController.php`
    - Constructor injects `CartManager`, `CouponValidator`, `OrderGenerator`, `StripeGateway`
    - `index()`: redirects to `shop.cart` if cart empty; passes cart items, totals, applied coupon (from session), cities to `shop.checkout` view
    - `store(CheckoutRequest $request)`: redirects to cart if empty; calls `OrderGenerator::create()` for COD; clears cart; redirects to confirmation; catches `OrderException` → redirect back with error flash preserving old input
    - `applyCoupon(Request $request)`: rejects if coupon already in session (error: "A coupon is already applied. Remove it before applying a new one."); calls `CouponValidator::validate()`; stores result in `shop_coupon` session; returns JSON `{success, discount, free_shipping, message}`; catches `CouponException` → JSON 422
    - `removeCoupon(Request $request)`: forgets `shop_coupon` session key; returns JSON `{success: true}`
    - `createPaymentIntent(Request $request)`: calculates total in cents; calls `StripeGateway::createPaymentIntent()`; returns JSON `{client_secret}`; catches `StripeException` → JSON 500 with generic message
    - _Requirements: 3.1–3.7, 4.1–4.4, 5.1–5.12, 6.1–6.4, 7.1–7.8, 8.1–8.7_

  - [ ] 10.3 Create `ConfirmationController`
    - Create `app/Http/Controllers/Shop/ConfirmationController.php`
    - `show(string $orderNumber)`: looks up `Order::where('order_number', $orderNumber)->first()`; redirects to `/` if not found; passes order to `shop.confirmation` view
    - _Requirements: 10.1–10.6_

  - [ ] 10.4 Create `WebhookController`
    - Create `app/Http/Controllers/Shop/WebhookController.php`
    - Constructor injects `StripeGateway`
    - `handle(Request $request)`: calls `StripeGateway::constructWebhookEvent()`; catches `SignatureVerificationException` → return HTTP 400; handles `payment_intent.succeeded` (update order status to `paid` if not already `paid`) and `payment_intent.payment_failed` (update to `failed` if not already `failed`) by looking up order via `stripe_payment_intent_id`; returns HTTP 200
    - _Requirements: 8.8–8.11_

- [ ] 11. Checkpoint — core backend complete
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 12. Routes and middleware
  - [ ] 12.1 Update `routes/web.php` with all guest shop routes
    - Add `Route::prefix('shop')->name('shop.')->group(...)` block with: `GET /cart`, `POST /cart/add`, `PATCH /cart/update`, `DELETE /cart/remove`, `GET /checkout`, `POST /checkout`, `POST /coupon/apply`, `DELETE /coupon/remove`, `POST /payment/intent`, `GET /confirmation/{orderNumber}`
    - Add `Route::post('/stripe/webhook', ...)` outside the shop prefix group
    - Import all four Shop controller classes
    - _Requirements: 1.1–1.4, 3.1, 5.1, 7.1, 8.1, 10.1_

  - [ ] 12.2 Exclude webhook route from CSRF in `bootstrap/app.php`
    - In `bootstrap/app.php`, configure the `VerifyCsrfToken` middleware (or the equivalent Laravel 12 approach) to exclude `stripe/webhook` from CSRF verification
    - _Requirements: 8.10_

- [ ] 13. Blade views
  - [ ] 13.1 Create `resources/views/shop/cart.blade.php`
    - Cart items table: product image, name, unit price, quantity controls (+/- buttons + number input), line total, remove button
    - Fast Production toggle checkbox — recalculates total client-side using fee values passed from controller
    - Order summary sidebar: subtotal, shipping fee, fast production fee (conditional), total
    - "Proceed to Checkout" button (disabled when cart empty)
    - Empty cart message when no items
    - JavaScript: `+`/`-` buttons call `PATCH /shop/cart/update` via `fetch()` with `X-CSRF-TOKEN`; remove button calls `DELETE /shop/cart/remove`; updates DOM values (item total, subtotal, total, cart count badge) without page reload
    - _Requirements: 1.6, 1.7, 2.1–2.7_

  - [ ] 13.2 Create `resources/views/shop/checkout.blade.php`
    - Left column — checkout form with `@csrf`:
      - Contact section: email field with `@error` display
      - Shipping section: full name, address, city dropdown (from `config('shop.cities')`), postal code, country, phone — all with `@error` and `old()` preservation
      - Payment section: two radio cards (COD / Stripe); COD shows delivery note; Stripe shows Stripe Elements card input; `@error('payment_method')` below section
      - Submit button "Proceed to Pay"
    - Right column — order summary sidebar:
      - Cart items list (image, name, qty × price, line total)
      - Subtotal, shipping fee, fast production fee (conditional), discount line (conditional), total
      - Coupon input + Apply button (AJAX via `fetch()`); applied coupon badge with Remove link; error display
    - JavaScript: coupon apply/remove calls AJAX endpoints and updates sidebar totals; Stripe Elements integration for card input; on Stripe payment confirmation, submits form with `stripe_payment_intent_id`
    - _Requirements: 3.1–3.7, 4.1–4.4, 5.1–5.12, 6.1–6.4_

  - [ ] 13.3 Create `resources/views/shop/confirmation.blade.php`
    - Heading: "Thank you, your order is confirmed!"
    - Order number display with green "Confirmed" status badge
    - Three-step progress indicator: "Confirmation" (Sent to your inbox), "Personalizing" (Within 24 hours), "Estimated delivery" (placeholder date label)
    - "Back to home" button → home route
    - "Shop more" button → `route('shop.cart')`
    - _Requirements: 10.1–10.5_

- [ ] 14. Run migration
  - [ ] 14.1 Execute the orders table migration
    - Run `php artisan migrate` to apply the new `create_orders_table` migration
    - Verify the `orders` table exists with all expected columns and indexes
    - _Requirements: 9.4_

- [ ] 15. Final checkpoint — Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional and can be skipped for faster MVP
- Each task references specific requirements for traceability
- Checkpoints ensure incremental validation
- Property tests validate universal correctness properties (Properties 2, 3, 4, 7 from the design)
- Unit tests validate specific examples and edge cases
- The `CartManager` should be bound as a singleton so the same instance is reused within a single request cycle
- Laravel 12 uses `bootstrap/app.php` for middleware configuration rather than `app/Http/Middleware/VerifyCsrfToken.php` — use the `withMiddleware()` callback to add the CSRF exception

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "2.1", "2.2"] },
    { "id": 1, "tasks": ["1.2", "3.1", "3.2", "3.3"] },
    { "id": 2, "tasks": ["4.1", "5.1"] },
    { "id": 3, "tasks": ["4.2", "4.3", "4.4", "5.2"] },
    { "id": 4, "tasks": ["5.3", "5.4", "6.1"] },
    { "id": 5, "tasks": ["6.2", "6.3", "7.1"] },
    { "id": 6, "tasks": ["7.2", "8.1"] },
    { "id": 7, "tasks": ["9.1"] },
    { "id": 8, "tasks": ["10.1", "10.2", "10.3", "10.4"] },
    { "id": 9, "tasks": ["12.1", "12.2"] },
    { "id": 10, "tasks": ["13.1", "13.2", "13.3"] },
    { "id": 11, "tasks": ["14.1"] }
  ]
}
```
