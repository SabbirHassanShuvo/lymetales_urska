# Bugfix Requirements Document

## Introduction

The `orders` table currently uses a single `status` column that conflates both order fulfillment status and payment status. This causes two problems:

1. **Premature `paid` status on Stripe orders**: `OrderGenerator` sets `status = 'paid'` at order creation time for Stripe payments — before the Stripe webhook fires to confirm actual payment. This means every Stripe order appears paid even if the customer never completes checkout.
2. **No separation of concerns**: There is no way to independently track whether an order has been fulfilled (order status) versus whether it has been paid (payment status). Admin cannot update order fulfillment status without also affecting payment status, and vice versa.

The fix introduces two separate columns — `order_status` and `payment_status` — and corrects the initialization logic and webhook handling to use them appropriately.

---

## Bug Analysis

### Current Behavior (Defect)

1.1 WHEN a user places a Stripe order THEN the system sets `status = 'paid'` immediately at order creation, before the Stripe webhook confirms payment

1.2 WHEN the Stripe `checkout.session.completed` webhook fires THEN the system updates the single `status` column, which conflates payment confirmation with order fulfillment status

1.3 WHEN the Stripe `payment_intent.payment_failed` webhook fires THEN the system updates the single `status` column to `'failed'`, with no distinction between a failed payment and a failed order

1.4 WHEN an admin needs to update order fulfillment status (e.g., mark as shipped) THEN the system provides no separate field — any status change overwrites the combined payment/order status

1.5 WHEN an admin needs to mark a COD order as paid THEN the system provides no dedicated payment status field to update independently of order status

### Expected Behavior (Correct)

2.1 WHEN a user places a Stripe order THEN the system SHALL set `order_status = 'pending'` and `payment_status = 'pending'` at order creation

2.2 WHEN a user places a COD order THEN the system SHALL set `order_status = 'pending'` and `payment_status = 'pending'` at order creation

2.3 WHEN the Stripe `checkout.session.completed` webhook fires THEN the system SHALL set `payment_status = 'paid'` while leaving `order_status` unchanged

2.4 WHEN the Stripe `payment_intent.payment_failed` webhook fires THEN the system SHALL set `payment_status = 'failed'` while leaving `order_status` unchanged

2.5 WHEN an admin updates the order fulfillment status for any order (COD or Stripe) THEN the system SHALL update `order_status` independently without affecting `payment_status`

2.6 WHEN an admin updates the payment status for a COD order THEN the system SHALL update `payment_status` independently (e.g., to `'paid'` when cash is collected)

2.7 WHEN an admin attempts to update the payment status for a Stripe order THEN the system SHALL reject the request, as Stripe order payment status is controlled exclusively by webhooks

### Unchanged Behavior (Regression Prevention)

3.1 WHEN a Stripe order is created and the webhook has not yet fired THEN the system SHALL CONTINUE TO store the `stripe_payment_intent_id` on the order for webhook lookup

3.2 WHEN the `checkout.session.completed` webhook fires for a Stripe order THEN the system SHALL CONTINUE TO store the `stripe_payment_intent_id` from the session onto the order

3.3 WHEN a coupon is applied at checkout THEN the system SHALL CONTINUE TO increment the coupon's `used_count` and store the `coupon_code` on the order

3.4 WHEN order financials are calculated (subtotal, shipping fee, fast production fee, discount, total) THEN the system SHALL CONTINUE TO compute and persist them correctly

3.5 WHEN the Stripe webhook signature is invalid THEN the system SHALL CONTINUE TO return a 400 response and reject the event

3.6 WHEN an admin updates `order_status` for a Stripe order THEN the system SHALL CONTINUE TO allow that update (order fulfillment is admin-controlled regardless of payment method)
