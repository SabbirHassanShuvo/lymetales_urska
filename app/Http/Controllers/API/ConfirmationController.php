<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\SiteTranslation;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class ConfirmationController extends Controller
{
    public function __construct(private CartManager $cart) {}

    /**
     * GET /api/shop/confirmation/{orderNumber}
     */
    public function show(string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        // If payment status is pending for Stripe order, try to verify with Stripe directly
        if ($order->payment_method === 'stripe' && $order->payment_status !== 'paid') {
            $sessionId = $order->stripe_payment_intent_id;
            if ($sessionId && str_starts_with($sessionId, 'cs_')) {
                try {
                    Stripe::setApiKey(config('services.stripe.secret'));
                    $session = StripeSession::retrieve($sessionId);
                    if ($session && $session->payment_status === 'paid') {
                        $order->update([
                            'payment_status'           => 'paid',
                            'stripe_payment_intent_id' => $session->payment_intent,
                        ]);
                        $order->refresh();
                    }
                } catch (\Exception $e) {
                    Log::error('Stripe session retrieval failed in confirmation: ' . $e->getMessage());
                }
            }
        }

        // If payment status is pending for PayPal order, try to capture using PayPal Order ID
        if ($order->payment_method === 'paypal' && $order->payment_status !== 'paid') {
            $paypalOrderId = $order->paypal_order_id;
            if ($paypalOrderId) {
                try {
                    $provider = new \Srmklive\PayPal\Services\PayPal;
                    $provider->setApiCredentials(config('paypal'));
                    $provider->getAccessToken();

                    $response = $provider->capturePaymentOrder($paypalOrderId);

                    if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                        $order->update([
                            'payment_status' => 'paid',
                        ]);
                        $order->refresh();
                    }
                } catch (\Exception $e) {
                    Log::error('PayPal order capture failed in confirmation: ' . $e->getMessage());
                }
            }
        }

        // If order is paid or is a COD order, clear the cart in the user's session
        if ($order->payment_status === 'paid' || $order->payment_method === 'cod') {
            $this->cart->clear();
            session()->forget(config('shop.coupon_session_key'));
        }

        return response()->json([
            'success'        => true,
            'order_number'   => $order->order_number,
            'status'         => $order->payment_status, // Return payment status for backward compatibility
            'payment_status' => $order->payment_status,
            'order_status'   => $order->order_status,
            'email'          => $order->email,
            'full_name'      => $order->full_name,
            'total'          => config('shop.currency_symbol', '€') . number_format($order->total, 2),
            'items'          => $order->items,
            'created_at'     => $order->created_at->toDateTimeString(),
        ]);
    }
}
