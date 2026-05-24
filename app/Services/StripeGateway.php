<?php

namespace App\Services;

use App\Exceptions\StripeException;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeGateway
{
    /**
     * Create a Stripe Checkout Session.
     *
     * Returns the hosted Stripe payment page URL.
     * After payment, Stripe redirects to success_url or cancel_url.
     *
     * @param  array   $lineItems    Array of Stripe line_items
     * @param  string  $currency     ISO currency code (e.g. 'eur')
     * @param  string  $successUrl   URL to redirect after successful payment (must include {CHECKOUT_SESSION_ID})
     * @param  string  $cancelUrl    URL to redirect if user cancels
     * @param  array   $metadata     Extra metadata stored on the session (e.g. order_number)
     * @return array   ['session_id' => string, 'checkout_url' => string]
     *
     * @throws StripeException
     */
    public function createCheckoutSession(
        array  $lineItems,
        string $currency,
        string $successUrl,
        string $cancelUrl,
        array  $metadata = []
    ): array {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items'           => $lineItems,
                'mode'                 => 'payment',
                'success_url'          => $successUrl,
                'cancel_url'           => $cancelUrl,
                'metadata'             => $metadata,
            ]);

            return [
                'session_id'   => $session->id,
                'checkout_url' => $session->url,
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new StripeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Create a Stripe PaymentIntent and return the client_secret.
     * (Kept for backward compatibility / direct integration use)
     *
     * @throws StripeException
     */
    public function createPaymentIntent(int $amountCents, string $currency): string
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $intent = PaymentIntent::create([
                'amount'                    => $amountCents,
                'currency'                  => $currency,
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return $intent->client_secret;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            throw new StripeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Verify Stripe webhook signature and return the Event object.
     *
     * @throws \Stripe\Exception\SignatureVerificationException
     */
    public function constructWebhookEvent(string $payload, string $sigHeader): \Stripe\Event
    {
        return Webhook::constructEvent(
            $payload,
            $sigHeader,
            config('services.stripe.webhook_secret')
        );
    }
}
