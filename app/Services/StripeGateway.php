<?php

namespace App\Services;

use App\Exceptions\StripeException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeGateway
{
    /**
     * Create a Stripe PaymentIntent and return the client_secret.
     *
     * @param  int     $amountCents  Amount in smallest currency unit (e.g. cents)
     * @param  string  $currency     ISO currency code (e.g. 'eur')
     * @return string  client_secret
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
     * @param  string  $payload    Raw request body
     * @param  string  $sigHeader  Value of Stripe-Signature header
     * @return \Stripe\Event
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
