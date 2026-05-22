<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CartException;
use App\Http\Controllers\Controller;
use App\Services\CartManager;
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
        $shippingFee = (float) config('shop.shipping_fee', 5.95);
        $fastFee     = (float) config('shop.fast_production_fee', 9.95);
        $subtotal    = $this->cart->subtotal();

        return response()->json([
            'items'               => $this->cart->items(),
            'count'               => $this->cart->count(),
            'subtotal'            => $symbol . number_format($subtotal, 2),
            'shipping_fee'        => $symbol . number_format($shippingFee, 2),
            'fast_production_fee' => $symbol . number_format($fastFee, 2),
            'total'               => $symbol . number_format($subtotal + $shippingFee, 2),
            'is_empty'            => $this->cart->isEmpty(),
        ]);
    }

    /**
     * POST /api/shop/cart/add
     * Body: { "product_id": 1, "quantity": 1 }
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'min:1'],
            'quantity'   => ['sometimes', 'integer', 'min:1', 'max:99'],
        ]);

        try {
            $this->cart->add(
                (int) $request->input('product_id'),
                (int) $request->input('quantity', 1)
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
            'product_id' => ['required', 'integer', 'min:1'],
            'quantity'   => ['required', 'integer'],
        ]);

        $this->cart->update(
            (int) $request->input('product_id'),
            (int) $request->input('quantity')
        );

        $symbol      = config('shop.currency_symbol', '€');
        $shippingFee = (float) config('shop.shipping_fee', 5.95);
        $subtotal    = $this->cart->subtotal();

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
            'success'    => true,
            'item_total' => $itemTotal,
            'subtotal'   => $symbol . number_format($subtotal, 2),
            'total'      => $symbol . number_format($subtotal + $shippingFee, 2),
            'cart_count' => $this->cart->count(),
        ]);
    }

    /**
     * DELETE /api/shop/cart/remove
     * Body: { "product_id": 1 }
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'min:1'],
        ]);

        $this->cart->remove((int) $request->input('product_id'));

        $symbol      = config('shop.currency_symbol', '€');
        $shippingFee = (float) config('shop.shipping_fee', 5.95);
        $subtotal    = $this->cart->subtotal();

        return response()->json([
            'success'    => true,
            'subtotal'   => $symbol . number_format($subtotal, 2),
            'total'      => $symbol . number_format($subtotal + $shippingFee, 2),
            'cart_count' => $this->cart->count(),
        ]);
    }
}
