<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * GET /api/shop/products
     */
    public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 12), 48);

        $query = Product::with(['category:id,name,slug,parent_id', 'primaryImage', 'galleryImages'])
            ->where('status', true);

        // Category / Subcategory filter
        if ($request->filled('subcategory')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->subcategory));
        } elseif ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category)
                  ->orWhereHas('parent', fn ($q2) => $q2->where('slug', $request->category));
            });
        }

        if ($request->boolean('is_bestseller'))  $query->where('is_bestseller', true);
        if ($request->boolean('is_recommended')) $query->where('is_recommended', true);
        if ($request->filled('min_price'))       $query->where('price', '>=', (float) $request->min_price);
        if ($request->filled('max_price'))       $query->where('price', '<=', (float) $request->max_price);
        if ($request->filled('age_range'))       $query->where('age_range', $request->age_range);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        match ($request->input('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating'     => $query->orderBy('rating', 'desc'),
            default      => $query->latest(),
        };

        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $products->map(fn ($p) => $this->formatProduct($p)),
            'meta'    => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
                'from'         => $products->firstItem(),
                'to'           => $products->lastItem(),
            ],
            'links' => [
                'first' => $products->url(1),
                'last'  => $products->url($products->lastPage()),
                'prev'  => $products->previousPageUrl(),
                'next'  => $products->nextPageUrl(),
            ],
        ]);
    }

    /**
     * GET /api/shop/products/{slug}
     */
    public function show(string $slug)
    {
        $product = Product::with(['category:id,name,slug,parent_id', 'primaryImage', 'galleryImages'])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $this->formatProduct($product, detailed: true),
        ]);
    }

    // ── Private helpers ────────────────────────────────────────────────────

    private function formatProduct(Product $p, bool $detailed = false): array
    {
        $base = [
            'id'             => $p->id,
            'title'          => $p->title,
            'slug'           => $p->slug,
            'price'          => (float) $p->price,
            'image'          => $p->imageUrl,   // primary image URL via accessor
            'rating'         => (float) $p->rating,
            'reviews_count'  => $p->reviews_count,
            'is_bestseller'  => $p->is_bestseller,
            'is_recommended' => $p->is_recommended,
            'age_range'      => $p->age_range,
            'category'       => $p->category ? [
                'id'        => $p->category->id,
                'name'      => $p->category->name,
                'slug'      => $p->category->slug,
                'parent_id' => $p->category->parent_id,
            ] : null,
        ];

        if ($detailed) {
            $base += [
                'description' => $p->description,
                'pages'       => $p->pages,
                'size'        => $p->size,
                'characters'  => $p->characters,
                'cover_type'  => $p->cover_type,
                'print_type'  => $p->print_type,
                'paper_type'  => $p->paper_type,
                'images'      => $p->images->map(fn ($img) => [
                    'id'         => $img->id,
                    'url'        => $img->url,
                    'is_primary' => $img->is_main,
                    'sort_order' => $img->sort_order,
                ])->values(),
            ];
        }

        return $base;
    }
}
