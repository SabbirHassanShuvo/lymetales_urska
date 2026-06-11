<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CartException;
use App\Http\Controllers\Controller;
use App\Services\CartManager;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartManager $cart) {}

    /**
     * GET /api/shop/cart
     */
    public function index(): JsonResponse
    {
        $symbol      = config('shop.currency_symbol', '€');
        $shippingFee = (float) Setting::getVal('shipping_charge', config('shop.shipping_fee', 5.95));
        $fastFee     = (float) Setting::getVal('fast_production_fee', config('shop.fast_production_fee', 9.95));
        $subtotal    = $this->cart->subtotal();

        // Global discount
        $discountType  = Setting::getVal('global_discount_type', 'fixed');
        $discountValue = (float) Setting::getVal('global_discount_value', 0);
        $globalDiscount = 0;
        if ($discountValue > 0) {
            if ($discountType === 'percentage') {
                $globalDiscount = $subtotal * ($discountValue / 100);
            } else {
                $globalDiscount = $discountValue;
            }
        }
        if ($globalDiscount > $subtotal) {
            $globalDiscount = $subtotal;
        }

        // Coupon discount
        $coupon         = session(config('shop.coupon_session_key'));
        $couponDiscount = 0.0;
        $freeShip       = false;
        if ($coupon) {
            $freeShip = (bool) ($coupon['free_shipping'] ?? false);
            if ($freeShip) {
                $couponDiscount = $shippingFee; // shipping becomes free = deduct shipping cost
            } elseif ($coupon['type'] === 'percent') {
                $couponValue    = (float) \App\Models\Coupon::where('code', $coupon['code'])->value('value');
                $couponDiscount = round(($couponValue / 100) * $subtotal, 2);
            } else {
                $couponDiscount = (float) ($coupon['discount'] ?? 0);
            }
        }

        $totalDiscount = $globalDiscount + $couponDiscount;
        if ($totalDiscount > $subtotal + $shippingFee) {
            $totalDiscount = $subtotal + $shippingFee;
        }

        $isFastProd    = session('shop.cart_fast_production', false);
        $appliedFastFee = $isFastProd ? $fastFee : 0.0;
        $total          = $subtotal - $totalDiscount + $shippingFee + $appliedFastFee;

        return response()->json([
            'items'               => $this->cart->items(),
            'count'               => $this->cart->count(),
            'subtotal'            => $symbol . number_format($subtotal, 2),
            'global_discount'     => $globalDiscount > 0 ? '-' . $symbol . number_format($globalDiscount, 2) : null,
            'coupon_discount'     => $couponDiscount > 0 ? '-' . $symbol . number_format($couponDiscount, 2) : null,
            'coupon'              => $coupon ? ['code' => $coupon['code'], 'type' => $coupon['type'], 'free_shipping' => $freeShip] : null,
            'shipping_fee'        => $freeShip ? 'Free' : $symbol . number_format($shippingFee, 2),
            'fast_production_fee' => $symbol . number_format($fastFee, 2),
            'is_fast_production'  => $isFastProd,
            'total'               => $symbol . number_format($total, 2),
            'is_empty'            => $this->cart->isEmpty(),
        ]);
    }

    /**
     * POST /api/shop/cart/add
     * Body: { "product_id": 1, "quantity": 1, "personalisation": { "child_name": "Emma", ... } }
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id'      => ['required', 'integer', 'min:1'],
            'quantity'        => ['sometimes', 'integer', 'min:1', 'max:99'],
            'personalisation' => ['sometimes', 'nullable', 'array'],
        ]);

        try {
            $this->cart->add(
                (int) $request->input('product_id'),
                (int) $request->input('quantity', 1),
                $request->input('personalisation')
            );
        } catch (CartException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success'    => true,
            'message'    => 'Added to cart.',
            'cart_count' => $this->cart->count(),
        ]);
    }

    /**
     * PATCH /api/shop/cart/update
     * Body: { "product_id": 1, "quantity": 3 }
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id'      => ['required', 'integer', 'min:1'],
            'quantity'        => ['required', 'integer'],
            'personalisation' => ['sometimes', 'nullable', 'array'],
        ]);

        $this->cart->update(
            (int) $request->input('product_id'),
            (int) $request->input('quantity'),
            $request->input('personalisation')
        );

        $symbol      = config('shop.currency_symbol', '€');
        $shippingFee = (float) Setting::getVal('shipping_charge', config('shop.shipping_fee', 5.95));
        $subtotal    = $this->cart->subtotal();

        $discountType = Setting::getVal('global_discount_type', 'fixed');
        $discountValue = (float) Setting::getVal('global_discount_value', 0);
        $discountAmount = 0;

        if ($discountValue > 0) {
            if ($discountType === 'percentage') {
                $discountAmount = $subtotal * ($discountValue / 100);
            } else {
                $discountAmount = $discountValue;
            }
        }

        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }

        $fastFee    = (float) Setting::getVal('fast_production_fee', config('shop.fast_production_fee', 9.95));
        $isFastProd = session('shop.cart_fast_production', false);
        $appliedFastFee = $isFastProd ? $fastFee : 0.0;

        $total = $subtotal - $discountAmount + $shippingFee + $appliedFastFee;

        $items     = $this->cart->items();
        $productId = (int) $request->input('product_id');
        $itemTotal = $symbol . '0.00';
        foreach ($items as $item) {
            if ($item['product_id'] === $productId) {
                $itemTotal = $symbol . number_format($item['line_total'], 2);
                break;
            }
        }

        return response()->json([
            'success'         => true,
            'item_total'      => $itemTotal,
            'subtotal'        => $symbol . number_format($subtotal, 2),
            'global_discount' => '-' . $symbol . number_format($discountAmount, 2),
            'total'           => $symbol . number_format($total, 2),
            'cart_count'      => $this->cart->count(),
        ]);
    }

    /**
     * DELETE /api/shop/cart/remove
     * Body: { "product_id": 1 }
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id'      => ['required', 'integer', 'min:1'],
            'personalisation' => ['sometimes', 'nullable', 'array'],
        ]);

        $this->cart->remove(
            (int) $request->input('product_id'),
            $request->input('personalisation')
        );

        $symbol      = config('shop.currency_symbol', '€');
        $shippingFee = (float) Setting::getVal('shipping_charge', config('shop.shipping_fee', 5.95));
        $subtotal    = $this->cart->subtotal();

        $discountType = Setting::getVal('global_discount_type', 'fixed');
        $discountValue = (float) Setting::getVal('global_discount_value', 0);
        $discountAmount = 0;

        if ($discountValue > 0) {
            if ($discountType === 'percentage') {
                $discountAmount = $subtotal * ($discountValue / 100);
            } else {
                $discountAmount = $discountValue;
            }
        }

        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }

        $fastFee    = (float) Setting::getVal('fast_production_fee', config('shop.fast_production_fee', 9.95));
        $isFastProd = session('shop.cart_fast_production', false);
        $appliedFastFee = $isFastProd ? $fastFee : 0.0;

        $total = $subtotal - $discountAmount + $shippingFee + $appliedFastFee;

        return response()->json([
            'success'         => true,
            'subtotal'        => $symbol . number_format($subtotal, 2),
            'global_discount' => '-' . $symbol . number_format($discountAmount, 2),
            'total'           => $symbol . number_format($total, 2),
            'cart_count'      => $this->cart->count(),
        ]);
    }

    /**
     * POST /api/shop/cart/fast-production
     * Body: { "fast_production": true }
     */
    public function toggleFastProduction(Request $request): JsonResponse
    {
        $request->validate([
            'fast_production' => ['required', 'boolean'],
        ]);

        session()->put('shop.cart_fast_production', $request->boolean('fast_production'));

        return $this->index();
    }
}
