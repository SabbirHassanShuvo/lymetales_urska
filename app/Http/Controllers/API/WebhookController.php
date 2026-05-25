<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\StripeGateway;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    public function __construct(private StripeGateway $stripe) {}

    /**
     * POST /api/stripe/webhook
     *
     * Handles:
     *   - checkout.session.completed  → order status = 'paid'
     *   - payment_intent.succeeded    → order status = 'paid'  (fallback)
     *   - payment_intent.payment_failed → order status = 'failed'
     */
    public function handle(Request $request): Response
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature', '');

        try {
            $event = $this->stripe->constructWebhookEvent($payload, $sigHeader);
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature.', 400);
        }

        switch ($event->type) {

            // Primary: Stripe Checkout Session completed
            case 'checkout.session.completed':
                $session     = $event->data->object;
                $orderNumber = $session->metadata->order_number ?? null;

                if ($orderNumber) {
                    Order::where('order_number', $orderNumber)
                        ->where('payment_status', '!=', 'paid')
                        ->update([
                            'payment_status'           => 'paid',
                            'stripe_payment_intent_id' => $session->payment_intent,
                        ]);
                }
                break;

            // Fallback: PaymentIntent succeeded (direct integration)
            case 'payment_intent.succeeded':
                $intentId = $event->data->object->id ?? null;
                if ($intentId) {
                    Order::where('stripe_payment_intent_id', $intentId)
                        ->where('payment_status', '!=', 'paid')
                        ->update(['payment_status' => 'paid']);
                }
                break;

            // Payment failed
            case 'payment_intent.payment_failed':
                $intentId = $event->data->object->id ?? null;
                if ($intentId) {
                    Order::where('stripe_payment_intent_id', $intentId)
                        ->where('payment_status', '!=', 'failed')
                        ->update(['payment_status' => 'failed']);
                }
                break;
        }

        return response('OK', 200);
    }
}
