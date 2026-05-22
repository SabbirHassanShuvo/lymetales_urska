# Requirements Document

## Introduction

This feature adds a complete guest (non-authenticated) e-commerce cart and checkout flow to the existing Laravel application. Guest users can browse products, add them to a session-based cart, proceed through a checkout form with shipping details, apply discount coupons, and pay via Cash on Delivery or Stripe. Upon successful order placement, a confirmation page is shown with a unique order number. No user account or login is required at any point in this flow.

## Glossary

- **Cart**: A session-stored collection of products and their quantities selected by a guest user.
- **Cart_Manager**: The system component responsible for reading and writing cart data to the session.
- **Checkout_Form**: The page where the guest provides contact, shipping, and payment information.
- **Coupon_Validator**: The system component that validates coupon codes against the database.
- **Order**: A persisted record of a completed purchase, stored in the `orders` database table.
- **Order_Generator**: The system component that creates Order records and generates unique order numbers.
- **Order_Number**: A unique human-readable identifier for an order, formatted as `LYM-XXXXXXXX` (e.g., `LYM-MOBXGZBF`) where each `X` is a random uppercase alphanumeric character (A–Z, 0–9).
- **Fast_Production**: An optional add-on that expedites order processing for an additional fixed fee configured in the application settings.
- **COD**: Cash on Delivery — a payment method where the customer pays upon receiving the order.
- **Stripe_Gateway**: The system component that processes online payments via the Stripe API.
- **Webhook_Handler**: The system component that receives and processes Stripe webhook events.
- **Confirmation_Page**: The page shown to the guest after a successful order placement.
- **Guest**: A site visitor who has not authenticated with a user account.
- **Shipping_Fee**: A fixed monetary cost configured in the application settings, added to the order total to cover delivery.
- **Discount**: A reduction in the order subtotal applied when a valid coupon code is used.

---

## Requirements

### Requirement 1: Session-Based Cart Management

**User Story:** As a guest user, I want to add products to a cart without logging in, so that I can shop freely and review my selections before purchasing.

#### Acceptance Criteria

1. WHEN a guest adds a product to the cart, THE Cart_Manager SHALL store the product ID, quantity, and unit price (locked at the time of addition) in the session.
2. WHEN a guest adds a product that already exists in the cart, THE Cart_Manager SHALL increment the existing quantity rather than creating a duplicate entry.
3. WHEN a guest updates the quantity of a cart item to zero or below, THE Cart_Manager SHALL remove that item from the cart.
4. WHEN a guest removes an item from the cart, THE Cart_Manager SHALL delete that item's entry from the session.
5. THE Cart_Manager SHALL persist cart contents for the duration of the session without requiring authentication.
6. WHEN a guest views the cart page, THE Cart_Manager SHALL display each item's product image, product name, unit price, and current quantity.
7. WHEN a guest adjusts item quantity using the increment or decrement controls, THE Cart_Manager SHALL update the session and recalculate the displayed subtotal (unit price × quantity for that line item) without a full page reload, and SHALL cap the quantity at 99 per item.
8. WHEN a guest attempts to add a product that is out of stock or unavailable, THE Cart_Manager SHALL display an error message and SHALL NOT add the product to the cart.
9. WHEN the session expires, THE Cart_Manager SHALL clear all cart contents, and the guest SHALL see an empty cart on their next visit.

---

### Requirement 2: Cart Order Summary

**User Story:** As a guest user, I want to see a live order summary on the cart page, so that I know the cost breakdown before proceeding to checkout.

#### Acceptance Criteria

1. WHILE the cart contains at least one item, THE Cart_Manager SHALL display a subtotal calculated as the sum of (unit price × quantity) for all cart items.
2. WHILE the cart contains at least one item, THE Cart_Manager SHALL display the configured Shipping_Fee as a separate line item in the order summary.
3. WHEN a guest selects the Fast Production option, THE Cart_Manager SHALL immediately add the configured Fast_Production fee to the order total and display it as a separate line item, without a full page reload.
4. WHEN a guest deselects the Fast Production option, THE Cart_Manager SHALL immediately remove the Fast_Production fee from the order total, without a full page reload.
5. WHILE the cart contains at least one item, THE Cart_Manager SHALL display the total as the sum of subtotal, Shipping_Fee, and any applicable Fast_Production fee.
6. WHILE the cart is empty, THE Cart_Manager SHALL display a message indicating the cart is empty and hide the order summary.
7. IF the Shipping_Fee is not configured in the application settings, THEN THE Cart_Manager SHALL display the shipping fee as €0.00 and SHALL NOT add any shipping cost to the total.

---

### Requirement 3: Checkout Contact and Shipping Information

**User Story:** As a guest user, I want to enter my contact and shipping details on the checkout page, so that my order can be delivered to the correct address.

#### Acceptance Criteria

1. THE Checkout_Form SHALL collect the guest's email address in the Contact Information section.
2. THE Checkout_Form SHALL collect the guest's full name (max 100 characters), street address (max 255 characters), city, postal code (max 20 characters), country (max 100 characters), and phone number (digits, spaces, hyphens, and a leading plus sign only, max 20 characters) in the Shipping Address section.
3. WHEN a guest submits the checkout form with any of the following required fields missing or empty — email, full name, address, city, postal code, country, or phone — THE Checkout_Form SHALL display a validation error message directly below the missing field and prevent order submission.
4. WHEN a guest submits the checkout form with an email that does not match the pattern `local-part@domain.tld` (where local-part is at least 1 character, domain contains at least one dot, and tld is at least 2 characters), THE Checkout_Form SHALL display a validation error directly below the email field and prevent order submission.
5. THE Checkout_Form SHALL present the city field as a dropdown populated with available city options.
6. IF the city dropdown has no available options, THEN THE Checkout_Form SHALL display a message indicating that no cities are available and SHALL prevent order submission.
7. WHEN a guest submits valid contact and shipping information but a payment error occurs, THE Checkout_Form SHALL retain the entered values for email, full name, address, city, postal code, country, and phone, so the guest does not need to re-enter them.

---

### Requirement 4: Checkout Order Summary Sidebar

**User Story:** As a guest user, I want to see my order summary on the checkout page, so that I can confirm the items and costs before paying.

#### Acceptance Criteria

1. WHEN a guest navigates to the checkout page, THE Checkout_Form SHALL display each cart item's product image, product name, quantity, and line total (unit price × quantity) in the order summary sidebar.
2. WHEN a guest navigates to the checkout page, THE Checkout_Form SHALL display the subtotal (sum of all line totals), the Shipping_Fee, and the order total (subtotal + Shipping_Fee) in the checkout order summary sidebar.
3. WHEN a discount has been applied, THE Checkout_Form SHALL display the discount amount as a separate line item and recalculate the order total as (subtotal − discount + Shipping_Fee).
4. IF the cart is empty when a guest navigates to the checkout page, THEN THE Checkout_Form SHALL display a message indicating the cart is empty and redirect the guest to the cart page.

---

### Requirement 5: Coupon Code Validation and Discount Application

**User Story:** As a guest user, I want to apply a discount coupon at checkout, so that I can reduce the total cost of my order.

#### Acceptance Criteria

1. WHEN a guest enters a coupon code and clicks Apply, THE Coupon_Validator SHALL look up the code in the `coupons` table using a case-insensitive comparison.
2. WHEN the coupon code exists, has `status = true`, has an `expiry_date` that is null or in the future, and has a `used_count` less than `usage_limit` (or `usage_limit` is null), THE Coupon_Validator SHALL return the coupon type and value.
3. WHEN the coupon type is `percent`, THE Coupon_Validator SHALL calculate the discount as `round((value / 100) × subtotal, 2)`.
4. WHEN the coupon type is `fixed`, THE Coupon_Validator SHALL apply the coupon value as a flat monetary discount rounded to two decimal places, capped at the order subtotal so the discount never exceeds the subtotal.
5. WHEN the coupon type is `free_shipping`, THE Coupon_Validator SHALL set the Shipping_Fee to €0.00 for the order and display "Free Shipping" as the discount line item.
6. WHEN a valid coupon is applied, THE Checkout_Form SHALL display the discount amount as a separate line item and update the order total as (subtotal − discount + Shipping_Fee) in the summary sidebar.
7. IF the coupon code does not exist in the `coupons` table, THEN THE Coupon_Validator SHALL return the error message "Coupon code not found."
8. IF the coupon has `status = false`, THEN THE Coupon_Validator SHALL return the error message "This coupon is inactive."
9. IF the coupon's `expiry_date` is in the past, THEN THE Coupon_Validator SHALL return the error message "This coupon has expired."
10. IF the coupon's `used_count` is greater than or equal to its `usage_limit`, THEN THE Coupon_Validator SHALL return the error message "This coupon has reached its usage limit."
11. WHEN an invalid coupon code is submitted, THE Checkout_Form SHALL display the specific error message returned by the Coupon_Validator without clearing the other form fields.
12. WHEN a guest attempts to apply a second coupon code while a coupon is already applied, THE Checkout_Form SHALL reject the second code with the error message "A coupon is already applied. Remove it before applying a new one." and SHALL leave the existing coupon and discount unchanged.

---

### Requirement 6: Payment Method Selection

**User Story:** As a guest user, I want to choose between Cash on Delivery and Stripe payment, so that I can pay in the way that suits me.

#### Acceptance Criteria

1. THE Checkout_Form SHALL present exactly two payment options: Cash on Delivery (COD) and Stripe (online payment), with no option pre-selected by default.
2. WHEN a guest selects COD, THE Checkout_Form SHALL display a note stating "Payment will be collected upon delivery." and SHALL hide any Stripe payment input fields.
3. WHEN a guest selects Stripe, THE Checkout_Form SHALL display the Stripe payment input fields (card number, expiry, CVC) and SHALL hide the COD delivery note.
4. WHEN a guest clicks "Proceed to Pay" without selecting a payment method, THE Checkout_Form SHALL display the validation error "Please select a payment method." directly below the payment section and prevent order submission.

---

### Requirement 7: Cash on Delivery Order Placement

**User Story:** As a guest user, I want to place an order with Cash on Delivery, so that I can complete my purchase without providing card details.

#### Acceptance Criteria

1. WHEN a guest submits the checkout form with all required fields (email, full name, address, city, postal code, country, phone) filled, COD selected, and the cart containing at least one item, THE Order_Generator SHALL create an Order record with status `pending`.
2. WHEN creating a COD order, THE Order_Generator SHALL generate a unique Order_Number in the format `LYM-XXXXXXXX` (8 uppercase alphanumeric characters A–Z, 0–9), retrying up to 5 times if a collision is detected.
3. THE Order_Generator SHALL store the guest's email, full name, shipping address, city, postal code, country, phone, cart items (as JSON), subtotal, Shipping_Fee, Fast_Production fee (if applicable), discount amount and coupon code (if applicable), and total in the Order record.
4. WHEN the COD order is successfully created, THE Order_Generator SHALL clear the cart from the session.
5. WHEN the COD order is successfully created, THE Order_Generator SHALL redirect the guest to the Confirmation_Page with the Order_Number in the URL.
6. IF the order creation fails due to a database error, THEN THE Order_Generator SHALL return the guest to the checkout page with the error message "Order could not be placed. Please try again." and preserve all entered form data including the COD payment method selection.
7. WHEN a guest submits the checkout form with any required field missing or empty, THE Checkout_Form SHALL display validation errors and SHALL NOT create an Order record.
8. IF the cart is empty when a guest submits the checkout form, THEN THE Order_Generator SHALL redirect the guest to the cart page without creating an Order record.

---

### Requirement 8: Stripe Online Payment

**User Story:** As a guest user, I want to pay online via Stripe, so that I can complete my purchase immediately with a credit or debit card.

#### Acceptance Criteria

1. WHEN a guest submits a valid checkout form with Stripe selected, THE Stripe_Gateway SHALL create a Stripe PaymentIntent for the order total amount in the configured currency.
2. WHEN the Stripe payment is confirmed successfully on the client side, THE Order_Generator SHALL create an Order record with status `paid`.
3. WHEN creating a Stripe order, THE Order_Generator SHALL generate a unique Order_Number in the format `LYM-XXXXXXXX` (8 uppercase alphanumeric characters A–Z, 0–9), verified to be globally unique in the `orders` table.
4. WHEN creating a Stripe order, THE Order_Generator SHALL store the Stripe PaymentIntent ID in the Order record.
5. WHEN the Stripe payment is confirmed, THE Order_Generator SHALL clear the cart from the session.
6. WHEN the cart is cleared after Stripe payment, THE Order_Generator SHALL redirect the guest to the Confirmation_Page with the Order_Number in the URL.
7. IF the Stripe payment fails or is declined, THEN THE Stripe_Gateway SHALL return the guest to the checkout page with the Stripe error message and preserve all entered form data except card fields (card number, expiry, CVC).
8. WHEN a Stripe `payment_intent.succeeded` webhook event is received and the corresponding Order record status is not already `paid`, THE Webhook_Handler SHALL update that Order record status to `paid`.
9. WHEN a Stripe `payment_intent.payment_failed` webhook event is received and the corresponding Order record status is not already `failed`, THE Webhook_Handler SHALL update that Order record status to `failed`.
10. THE Webhook_Handler SHALL verify the Stripe webhook signature using the configured webhook secret before processing any event.
11. IF the webhook signature verification fails, THEN THE Webhook_Handler SHALL return an HTTP 400 response and discard the event without modifying any Order record.

---

### Requirement 9: Order Number Uniqueness

**User Story:** As the system, I want every order to have a unique identifier, so that orders can be reliably tracked and referenced.

#### Acceptance Criteria

1. THE Order_Generator SHALL verify that a generated Order_Number does not already exist in the `orders` table before persisting the Order record.
2. IF a generated Order_Number already exists, THEN THE Order_Generator SHALL regenerate a new Order_Number and repeat the uniqueness check, up to a maximum of 10 attempts.
3. IF the Order_Generator fails to produce a unique Order_Number after 10 attempts, THEN it SHALL abort order creation, log an error, and return the guest to the checkout page with the message "Order could not be placed. Please try again."
4. THE Order_Generator SHALL store the Order_Number as a unique indexed column in the `orders` table.

---

### Requirement 10: Order Confirmation Page

**User Story:** As a guest user, I want to see a confirmation page after placing my order, so that I know my order was received and understand what happens next.

#### Acceptance Criteria

1. WHEN a guest is redirected to the Confirmation_Page, THE Confirmation_Page SHALL display the message "Thank you, your order is confirmed!".
2. WHEN the Confirmation_Page loads with a valid Order_Number, THE Confirmation_Page SHALL display the Order_Number with a "Confirmed" status badge.
3. WHEN the Confirmation_Page loads, THE Confirmation_Page SHALL display three status steps: "Confirmation" (with sub-label "Sent to your inbox"), "Personalizing" (with sub-label "Within 24 hours"), and "Estimated delivery" (with a placeholder delivery date label).
4. WHEN the Confirmation_Page loads, THE Confirmation_Page SHALL display a "Back to home" button that navigates to the home page.
5. WHEN the Confirmation_Page loads, THE Confirmation_Page SHALL display a "Shop more" button that navigates to the products listing page.
6. IF a guest navigates directly to the Confirmation_Page with an Order_Number that does not exist in the `orders` table, THEN THE Confirmation_Page SHALL redirect the guest to the home page.

---

### Requirement 11: Coupon Usage Tracking

**User Story:** As an admin, I want coupon usage to be tracked accurately, so that usage limits are enforced correctly.

#### Acceptance Criteria

1. WHEN an order is successfully placed with a coupon code applied, THE Order_Generator SHALL atomically increment the `used_count` column of the corresponding Coupon record by 1 using a database-level increment to prevent race conditions.
2. WHEN an order is successfully placed with a coupon code applied, THE Order_Generator SHALL store the applied coupon code and discount amount in the Order record.
3. WHEN the `used_count` of a coupon equals its `usage_limit`, THE Coupon_Validator SHALL reject subsequent applications of that coupon code with the error message "This coupon has reached its usage limit."
