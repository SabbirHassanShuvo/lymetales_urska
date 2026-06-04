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

        $query = Product::with(['category:id,name,slug', 'subcategory:id,category_id,name,slug', 'primaryImage', 'galleryImages'])
            ->where('status', true);

        // Category / Subcategory filter
        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        } elseif ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
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
     * GET /api/shop/products/{id}
     */
    public function show(string $id)
    {
        $product = Product::with([
            'category:id,name,slug', 
            'subcategory:id,category_id,name,slug', 
            'primaryImage', 
            'galleryImages', 
            'images',
            'specialSections',
            'categoryImages.category:id,name,slug',
            'categoryImages.subcategory:id,name,slug',
            'approvedReviews'
        ])
            ->where('id', $id)
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
            'image'          => $this->resolveImageUrl($p->imageUrl),
            'rating'         => (float) $p->rating,
            'reviews_count'  => $p->reviews_count,
            'is_bestseller'  => (bool) $p->is_bestseller,
            'is_recommended' => (bool) $p->is_recommended,
            'status'         => (bool) $p->status,
            'age_range'      => $p->age_range,
            'category'       => $p->category ? [
                'id'        => $p->category->id,
                'name'      => $p->category->name,
                'slug'      => $p->category->slug,
            ] : null,
            'subcategory'    => $p->subcategory ? [
                'id'        => $p->subcategory->id,
                'name'      => $p->subcategory->name,
                'slug'      => $p->subcategory->slug,
            ] : null,
        ];

        if ($detailed) {
            return [
                'general_info'    => $base,
                'product_details' => [
                    'description' => $p->description,
                    'pages'       => $p->pages,
                    'size'        => $p->size,
                    'characters'  => $p->characters,
                    'cover_type'  => $p->cover_type,
                    'print_type'  => $p->print_type,
                    'paper_type'  => $p->paper_type,
                ],
                'rating_and_reviews' => [
                    'rating'        => (float) ($p->rating ?? 5.0),
                    'rating_out_of' => 5.0,
                    'rating_display'=> number_format((float) ($p->rating ?? 5.0), 1) . ' / 5.0',
                    'stars'         => (int) round((float) ($p->rating ?? 5.0)),
                    'reviews_count' => (int) ($p->reviews_count ?? 0),
                    'reviews_text'  => 'Based on ' . number_format((int) ($p->reviews_count ?? 0)) . ' reviews',
                    'reviews'       => $p->approvedReviews ? $p->approvedReviews->map(fn ($r) => [
                        'id'               => $r->id,
                        'reviewer_name'    => $r->reviewer_name,
                        'title'            => $r->title,
                        'reviewer_location'=> $r->reviewer_location,
                        'rating'           => (float) $r->rating,
                        'stars'            => (int) round((float) $r->rating),
                        'comment'          => $r->comment,
                        'created_at'       => $r->created_at->toDateString(),
                        'time_ago'         => $r->created_at->diffForHumans(),
                    ])->values() : [],
                ],
                'name_overlay'    => [
                    'text'        => $p->name_text,
                    'font_family' => $p->name_font_family,
                    'font_size'   => $p->name_font_size,
                    'color'       => $p->name_color,
                    'position'    => [
                        'top'   => $p->name_top,
                        'right' => $p->name_right,
                    ],
                ],
                'book_category_images' => $p->categoryImages
                    ? $p->categoryImages
                        ->groupBy(fn ($img) => $img->category_id)
                        ->map(fn ($items) => [
                            'id'            => $items->first()->category?->id,
                            'name'          => $items->first()->category?->name,
                            'slug'          => $items->first()->category?->slug,
                            'subcategories' => $items->map(fn ($img) => [
                                'id'           => $img->subcategory?->id,
                                'name'         => $img->subcategory?->name,
                                'slug'         => $img->subcategory?->slug,
                                'image'        => $this->resolveImageUrl($img->image_path),
                                'sort_order'   => $img->sort_order,
                                'option_type'  => $img->option_type ?? 'box',
                                'option_value' => $img->option_value,
                            ])->values(),
                        ])
                        ->values()
                    : [],
                'special_sections' => $p->specialSections ? $p->specialSections->map(fn ($sec) => [
                    'id'          => $sec->id,
                    'title'       => $sec->title,
                    'subtitle'    => $sec->subtitle,
                    'description' => $sec->description,
                    'image'       => $this->resolveImageUrl($sec->image),
                ])->values() : [],
                'gallery_thumbnails' => $p->galleryImages ? $p->galleryImages->map(fn ($img) => [
                    'id'         => $img->id,
                    'url'        => $this->resolveImageUrl($img->image_path),
                    'is_primary' => $img->is_main,
                    'sort_order' => $img->sort_order,
                ])->values() : [],
            ];
        }

        return $base;
    }

    /**
     * Resolve image URL handling external links, relative paths, and faker text gracefully.
     */
    private function resolveImageUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Check if it's already a valid external URL
        if (filter_var($path, FILTER_VALIDATE_URL) || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // If it looks like faker sentence data (contains space and lacks file extension)
        if (!preg_match('/\.(jpg|jpeg|png|webp|gif|svg)$/i', $path) && str_contains($path, ' ')) {
            // Provide a placeholder image for broken faker data to maintain professional look
            return 'https://placehold.co/600x400/eeeeee/333333?text=' . urlencode($path);
        }

        // Normalise path and use Laravel's asset helper
        $normalised = ltrim($path, '/');
        
        return asset($normalised);
    }
}
