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

    // ─────────────────────────────────────────────────────────────────────────
    // Helper: calculate order totals
    // ─────────────────────────────────────────────────────────────────────────

    private function calculateTotals(bool $fastProduction = false): array
    {
        $shippingFee = (float) config('shop.shipping_fee', 5.95);
        $fastFee     = (float) config('shop.fast_production_fee', 9.95);
        $subtotal    = $this->cart->subtotal();

        // Calculate subtotal of products (excluding gifts) for offer discount calculation
        $productsSubtotal = 0.0;
        $totalProductQuantity = 0;
        foreach ($this->cart->items() as $item) {
            if (($item['type'] ?? 'product') === 'product') {
                $productsSubtotal += $item['line_total'];
                $totalProductQuantity += $item['quantity'];
            }
        }

        // Coupon discount
        $coupon      = session(config('shop.coupon_session_key'));
        $discount    = $coupon ? (float) ($coupon['discount'] ?? 0) : 0.0;

        // Offer discount
        $offerDiscount = 0.0;
        $appliedOffer = null;
        $activeOffer = \App\Models\Offer::where('is_active', true)
            ->where('min_quantity', '<=', $totalProductQuantity)
            ->orderBy('min_quantity', 'desc')
            ->first();
        if ($activeOffer) {
            $offerDiscount = round($productsSubtotal * ($activeOffer->discount_percentage / 100), 2);
            $appliedOffer = $activeOffer;
        }

        $freeShip    = $coupon && ($coupon['free_shipping'] ?? false);
        $shipping    = $freeShip ? 0.0 : $shippingFee;
        $fastProdFee = $fastProduction ? $fastFee : 0.0;

        $totalDiscount = $discount + $offerDiscount;
        $total       = round($subtotal - $totalDiscount + $shipping + $fastProdFee, 2);
        if ($total < 0) {
            $total = 0.0;
        }

        return compact(
            'subtotal', 'shippingFee', 'fastFee',
            'discount', 'freeShip', 'shipping',
            'fastProdFee', 'total', 'coupon',
            'offerDiscount', 'appliedOffer'
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /api/shop/checkout — summary for checkout page
    // ─────────────────────────────────────────────────────────────────────────

    public function index(): JsonResponse
    {
        if ($this->cart->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 422);
        }

        $isFastProd = session('shop.cart_fast_production', false);
        $t          = $this->calculateTotals($isFastProd);

        $responseItems = array_map(function($item) {
            unset($item['personalisation']);
            return $item;
        }, $this->cart->items());

        return response()->json([
            'items'               => $responseItems,
            'subtotal'            => number_format($t['subtotal'], 2, '.', ''),
            'shipping_fee'        => number_format($t['shipping'] + $t['fastProdFee'], 2, '.', ''),
            'fast_production_fee' => number_format($t['fastFee'], 2, '.', ''),
            'discount'            => $t['discount'] > 0 ? number_format($t['discount'], 2, '.', '') : null,
            'coupon'              => $t['coupon'],
            'total'               => number_format($t['total'], 2, '.', ''),
            'cities'              => config('shop.cities', []),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /api/shop/checkout
    //
    // payment_method = cod    → creates order, returns success
    // payment_method = stripe → creates order + Stripe Checkout link
    // ─────────────────────────────────────────────────────────────────────────

    public function store(CheckoutRequest $request): JsonResponse
    {
        if ($this->cart->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 422);
        }

        $coupon   = session(config('shop.coupon_session_key'));
        $fastProd = $request->boolean('fast_production');
        $t        = $this->calculateTotals($fastProd);

        // ── COD ──────────────────────────────────────────────────────────────
        if ($request->input('payment_method') === 'cod') {
            try {
                $order = $this->orderGenerator->create(
                    $request->validated(),
                    $this->cart->items(),
                    $coupon
                );
            } catch (OrderException $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            session()->put('last_order_number', $order->order_number);
            $this->cart->clear();
            session()->forget(config('shop.coupon_session_key'));
            session()->save();

            return response()->json([
                'success'        => true,
                'payment_method' => 'cod',
                'order_number'   => $order->order_number,
                'message'        => 'Order placed successfully.',
            ]);
        }

        // ── Stripe ───────────────────────────────────────────────────────────
        $currency = config('shop.currency', 'eur');

        // 1. Create pending order (stripe_payment_intent_id filled later by webhook)
        $checkoutData = array_merge($request->validated(), [
            'payment_method'           => 'stripe',
            'fast_production'          => $fastProd,
            'stripe_payment_intent_id' => null,
        ]);

        try {
            $order = $this->orderGenerator->create($checkoutData, $this->cart->items(), $coupon);
        } catch (OrderException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        // 2. Build Stripe line_items
        $lineItems = [];

        foreach ($this->cart->items() as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => $currency,
                    'unit_amount'  => (int) round($item['unit_price'] * 100),
                    'product_data' => ['name' => $item['title']],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        if ($t['shipping'] > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => $currency,
                    'unit_amount'  => (int) round($t['shipping'] * 100),
                    'product_data' => ['name' => 'Shipping'],
                ],
                'quantity' => 1,
            ];
        }

        if ($t['fastProdFee'] > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => $currency,
                    'unit_amount'  => (int) round($t['fastProdFee'] * 100),
                    'product_data' => ['name' => 'Fast Production'],
                ],
                'quantity' => 1,
            ];
        }

        // 3. Redirect URLs (Next.js pages)
        $frontendUrl = rtrim(env('FRONTEND_URL', 'http://localhost:3000'), '/');
        $successUrl  = $frontendUrl . '/shop/order-confirmed?session_id={CHECKOUT_SESSION_ID}&order=' . $order->order_number;
        $cancelUrl   = $frontendUrl . '/shop/order-failed?order=' . $order->order_number;

        // 4. Create Stripe Checkout Session
        try {
            $result = $this->stripe->createCheckoutSession(
                $lineItems,
                $currency,
                $successUrl,
                $cancelUrl,
                ['order_number' => $order->order_number]
            );

            // Save the session ID to the order
            $order->update([
                'stripe_payment_intent_id' => $result['session_id'],
            ]);

            // Save the last order number to the session
            session()->put('last_order_number', $order->order_number);
            session()->save();
        } catch (StripeException $e) {
            $order->delete(); // rollback
            return response()->json([
                'success' => false,
                'error'   => 'Payment service unavailable: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success'        => true,
            'payment_method' => 'stripe',
            'order_number'   => $order->order_number,
            'checkout_url'   => $result['checkout_url'],
            'session_id'     => $result['session_id'],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /api/shop/coupon/apply
    // ─────────────────────────────────────────────────────────────────────────

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
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
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

    // ─────────────────────────────────────────────────────────────────────────
    // POST /api/shop/coupon/remove
    // ─────────────────────────────────────────────────────────────────────────

    public function removeCoupon(): JsonResponse
    {
        session()->forget(config('shop.coupon_session_key'));

        return response()->json(['success' => true, 'message' => 'Coupon removed.']);
    }
}
