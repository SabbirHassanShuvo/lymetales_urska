<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CouponException;
use App\Exceptions\OrderException;
use App\Exceptions\StripeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartManager;
use App\Services\CouponValidator;
use App\Services\OrderGenerator;
use App\Services\StripeGateway;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CartManager     $cart,
        private CouponValidator $couponValidator,
        private OrderGenerator  $orderGenerator,
        private StripeGateway   $stripe,
    ) {}

    /**
     * GET /api/shop/checkout
     */
    public function index(): JsonResponse
    {
        if ($this->cart->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $symbol      = config('shop.currency_symbol', '€');
        $shippingFee = (float) config('shop.shipping_fee', 5.95);
        $fastFee     = (float) config('shop.fast_production_fee', 9.95);
        $subtotal    = $this->cart->subtotal();
        $coupon      = session(config('shop.coupon_session_key'));
        $discount    = $coupon ? (float) ($coupon['discount'] ?? 0) : 0.0;
        $freeShip    = $coupon && ($coupon['free_shipping'] ?? false);
        $shipping    = $freeShip ? 0.0 : $shippingFee;
        $total       = $subtotal - $discount + $shipping;

        return response()->json([
            'items'               => $this->cart->items(),
            'subtotal'            => $symbol . number_format($subtotal, 2),
            'shipping_fee'        => $freeShip ? 'Free' : $symbol . number_format($shippingFee, 2),
            'fast_production_fee' => $symbol . number_format($fastFee, 2),
            'discount'            => $discount > 0 ? $symbol . number_format($discount, 2) : null,
            'coupon'              => $coupon,
            'total'               => $symbol . number_format($total, 2),
            'cities'              => config('shop.cities', []),
        ]);
    }

    /**
     * POST /api/shop/checkout
     * Place a COD order.
     */
    public function store(CheckoutRequest $request): JsonResponse
    {
        if ($this->cart->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $coupon = session(config('shop.coupon_session_key'));

        try {
            $order = $this->orderGenerator->create(
                $request->validated(),
                $this->cart->items(),
                $coupon
            );
        } catch (OrderException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        $this->cart->clear();
        session()->forget(config('shop.coupon_session_key'));

        return response()->json([
            'success'      => true,
            'order_number' => $order->order_number,
            'redirect'     => url('/api/shop/confirmation/' . $order->order_number),
        ]);
    }

    /**
     * POST /api/shop/coupon/apply
     * Body: { "code": "SUMMER20" }
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $couponKey = config('shop.coupon_session_key');

        if (session()->has($couponKey)) {
            return response()->json([
                'success' => false,
                'message' => 'A coupon is already applied. Remove it before applying a new one.',
            ], 422);
        }

        try {
            $result = $this->couponValidator->validate(
                $request->input('code'),
                $this->cart->subtotal()
            );
        } catch (CouponException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        session()->put($couponKey, [
            'code'         => $result->code,
            'type'         => $result->type,
            'discount'     => $result->discount,
            'free_shipping' => $result->freeShipping,
        ]);

        $symbol = config('shop.currency_symbol', '€');

        return response()->json([
            'success'      => true,
            'discount'     => $symbol . number_format($result->discount, 2),
            'free_shipping' => $result->freeShipping,
            'message'      => 'Coupon applied successfully.',
        ]);
    }

    /**
     * DELETE /api/shop/coupon/remove
     */
    public function removeCoupon(): JsonResponse
    {
        session()->forget(config('shop.coupon_session_key'));

        return response()->json(['success' => true]);
    }

    /**
     * POST /api/shop/payment/intent
     * Body: { "fast_production": true }
     */
    public function createPaymentIntent(Request $request): JsonResponse
    {
        $shippingFee = (float) config('shop.shipping_fee', 5.95);
        $fastFee     = (float) config('shop.fast_production_fee', 9.95);
        $subtotal    = $this->cart->subtotal();
        $coupon      = session(config('shop.coupon_session_key'));
        $discount    = $coupon ? (float) ($coupon['discount'] ?? 0) : 0.0;
        $freeShip    = $coupon && ($coupon['free_shipping'] ?? false);
        $shipping    = $freeShip ? 0.0 : $shippingFee;
        $fastProd    = $request->boolean('fast_production') ? $fastFee : 0.0;
        $total       = $subtotal - $discount + $shipping + $fastProd;
        $amountCents = (int) round($total * 100);
        $currency    = config('shop.currency', 'eur');

        try {
            $clientSecret = $this->stripe->createPaymentIntent($amountCents, $currency);
        } catch (StripeException $e) {
            return response()->json([
                'error' => 'Payment service unavailable. Please try again.',
            ], 500);
        }

        return response()->json(['client_secret' => $clientSecret]);
    }
}
