<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use App\Models\Product;
use App\Models\Category;
use App\Services\CartManager;
use Illuminate\Http\JsonResponse;

class GiftController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private CartManager $cart) {}

    /**
     * Get dynamic cart-based or default gifts.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // 1. Get products in the cart
        $cartItems = $this->cart->items();
        $cartProductIds = [];
        foreach ($cartItems as $item) {
            if (($item['type'] ?? 'product') === 'product') {
                $cartProductIds[] = $item['product_id'];
            }
        }

        $upsellProducts = collect();

        // 2. Fetch specific upsells configured for books in the cart
        if (!empty($cartProductIds)) {
            $upsellProducts = Product::whereIn('id', function ($query) use ($cartProductIds) {
                $query->select('upsell_product_id')
                      ->from('product_upsells')
                      ->whereIn('product_id', $cartProductIds);
            })
            ->where('status', true)
            ->with('primaryImage')
            ->get();
        }

        // 3. Fallback: query catalog products from the "Gifts" category
        if ($upsellProducts->isEmpty()) {
            $giftCategory = Category::where('slug', 'gifts')
                ->orWhere('name', 'Gifts')
                ->orWhere('slug', 'gift')
                ->orWhere('name', 'Gift')
                ->first();

            if ($giftCategory) {
                $upsellProducts = Product::where('category_id', $giftCategory->id)
                    ->where('status', true)
                    ->with('primaryImage')
                    ->get();
            }
        }

        // 4. Fallback 2: query any recommended catalog products or catalog products of type 'gift'
        if ($upsellProducts->isEmpty()) {
            $upsellProducts = Product::where('status', true)
                ->where(function ($q) {
                    $q->where('is_recommended', true)
                      ->orWhere('type', 'gift');
                })
                ->with('primaryImage')
                ->get();
        }

        // 5. Map catalog products to frontend output structure
        if ($upsellProducts->isNotEmpty()) {
            $data = $upsellProducts->map(function ($product) {
                return [
                    'id'                => $product->id,
                    'title'             => $product->title,
                    'short_description' => $product->description,
                    'price'             => (float) $product->price,
                    'image_url'         => $product->imageUrl ? (str_starts_with($product->imageUrl, 'http') ? $product->imageUrl : asset($product->imageUrl)) : null,
                    'created_at'        => $product->created_at,
                    'updated_at'        => $product->updated_at,
                ];
            });
        } else {
            // 6. Fallback 3: query legacy gifts from the legacy gifts table
            $data = Gift::orderBy('created_at', 'desc')->get()->map(function ($gift) {
                return [
                    'id'                => $gift->id,
                    'title'             => $gift->title,
                    'short_description' => $gift->short_description,
                    'price'             => (float) $gift->price,
                    'image_url'         => $gift->image_path ? (str_starts_with($gift->image_path, 'http') ? $gift->image_path : asset($gift->image_path)) : null,
                    'created_at'        => $gift->created_at,
                    'updated_at'        => $gift->updated_at,
                ];
            });
        }

        return response()->json([
            'success' => true,
            'data'    => $data,
            'message' => 'Gifts retrieved successfully.',
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
}

