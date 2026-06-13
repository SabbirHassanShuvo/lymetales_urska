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

        $query = Product::with([
            'category:id,name,slug',
            'subcategory:id,category_id,name,slug',
            'siteCategory:id,name,slug',
            'siteSubcategory:id,site_category_id,name',
            'primaryImage',
            'galleryImages'
        ])
        ->where('status', true);

        // Category / Subcategory filter
        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        } elseif ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Site Category / Site Subcategory filter
        if ($request->filled('site_subcategory_id')) {
            $query->where('site_subcategory_id', $request->site_subcategory_id);
        } elseif ($request->filled('site_category_id')) {
            $siteCategoryId = $request->site_category_id;
            $subcategoryIds = \App\Models\SiteSubcategory::where('site_category_id', $siteCategoryId)->pluck('id');
            $query->where(function ($q) use ($siteCategoryId, $subcategoryIds) {
                $q->where('site_category_id', $siteCategoryId)
                  ->orWhereIn('site_subcategory_id', $subcategoryIds);
            });
        }

        if ($request->boolean('is_bestseller'))  $query->where('is_bestseller', true);
        if ($request->boolean('is_recommended')) $query->where('is_recommended', true);
        if ($request->filled('min_price'))       $query->where('price', '>=', (float) $request->min_price);
        if ($request->filled('max_price'))       $query->where('price', '<=', (float) $request->max_price);
        if ($request->filled('age_range'))       $query->where('age_range', $request->age_range);
        if ($request->filled('domain'))          $query->where('domain', $request->domain);

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
            'subcategory:id,category_id,parent_id,name',
            'subcategory.parent:id,name',
            'siteCategory:id,name,slug',
            'siteSubcategory:id,site_category_id,name',
            'primaryImage',
            'galleryImages',
            'images',
            'specialSections',
            'categoryImages.category:id,name,slug',
            'categoryImages.subcategory:id,name,parent_id',
            'categoryImages.subcategory.parent:id,name',
            'approvedReviews',
            'customizationSteps.options.subSteps.subOptions',
        ])->where('id', $id)->where('status', true)->firstOrFail();

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
            'image'          => ($detailed && $p->primaryImage)
                                ? $this->resolveImageUrl($p->primaryImage->image_path)
                                : $this->resolveImageUrl($p->imageUrl),
            'rating'         => (float) $p->rating,
            'reviews_count'  => $p->reviews_count,
            'is_bestseller'  => (bool) $p->is_bestseller,
            'is_recommended' => (bool) $p->is_recommended,
            'status'         => (bool) $p->status,
            'age_range'      => $p->age_range,
            'domain'         => $p->domain,
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
            'site_category'  => $p->siteCategory ? [
                'id'        => $p->siteCategory->id,
                'name'      => $p->siteCategory->name,
                'slug'      => $p->siteCategory->slug,
            ] : null,
            'site_subcategory' => $p->siteSubcategory ? [
                'id'        => $p->siteSubcategory->id,
                'name'      => $p->siteSubcategory->name,
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
                    'rating'         => (float) ($p->rating ?? 5.0),
                    'rating_out_of'  => 5.0,
                    'rating_display' => number_format((float) ($p->rating ?? 5.0), 1) . ' / 5.0',
                    'stars'          => (int) round((float) ($p->rating ?? 5.0)),
                    'reviews_count'  => (int) ($p->reviews_count ?? 0),
                    'reviews_text'   => 'Based on ' . number_format((int) ($p->reviews_count ?? 0)) . ' reviews',
                    'rating_breakdown' => (function () use ($p) {
                        $reviews = $p->approvedReviews ?? collect();
                        $total   = $reviews->count();
                        $breakdown = [];
                        for ($star = 5; $star >= 1; $star--) {
                            $count = $reviews->where('rating', $star)->count();
                            $breakdown[] = [
                                'star'       => $star,
                                'count'      => $count,
                                'percentage' => $total > 0 ? (int) round(($count / $total) * 100) : 0,
                            ];
                        }
                        return $breakdown;
                    })(),
                    'reviews' => $p->approvedReviews ? $p->approvedReviews->map(fn ($r) => [
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
                // 'book_category_images' => $p->categoryImages
                //     ? $p->categoryImages
                //         ->groupBy(fn ($img) => $img->category_id)
                //         ->map(fn ($items) => [
                //             'id'            => $items->first()->category?->id,
                //             'name'          => $items->first()->category?->name,
                //             'slug'          => $items->first()->category?->slug,
                //             'subcategories' => $items->map(fn ($img) => [
                //                 'id'              => $img->subcategory?->id,
                //                 'name'            => $img->subcategory?->name,
                //                 'slug'            => $img->subcategory?->slug,
                //                 'parent_id'       => $img->subcategory?->parent_id,
                //                 'parent_name'     => $img->subcategory?->parent?->name,
                //                 'image'           => $this->resolveImageUrl($img->image_path),
                //                 'sort_order'      => $img->sort_order,
                //                 'option_type'     => $img->option_type ?? 'box',
                //                 'option_value'    => $img->option_value,
                //             ])->values(),
                //         ])
                //         ->values()
                //     : [],
                'special_sections' => $p->specialSections ? $p->specialSections->map(fn ($sec) => [
                    'id'          => $sec->id,
                    'title'       => $sec->title,
                    'subtitle'    => $sec->subtitle,
                    'description' => $sec->description,
                    'image'       => $this->resolveImageUrl($sec->image),
                ])->values() : [],
                'gallery_thumbnails' => (function () use ($p) {
                    $thumbnails = collect();

                    // Gather only gallery images (excluding primary/cover image)
                    $allImages = collect();
                    if ($p->galleryImages) {
                        foreach ($p->galleryImages as $img) {
                            $allImages->push($img);
                        }
                    }

                    // Find the featured/selected image if any is set
                    $featuredImage = null;
                    if ($p->featured_image_id) {
                        $featuredImage = $allImages->firstWhere('id', (int) $p->featured_image_id);
                    }

                    if ($featuredImage) {
                        // Push the featured image first
                        $thumbnails->push([
                            'id'         => $featuredImage->id,
                            'url'        => $this->resolveImageUrl($featuredImage->image_path),
                            'is_primary' => (bool) $featuredImage->is_main,
                            'sort_order' => $featuredImage->sort_order,
                        ]);

                        // Then push all other images in their original sequence
                        foreach ($allImages as $img) {
                            if ($img->id !== $featuredImage->id) {
                                $thumbnails->push([
                                    'id'         => $img->id,
                                    'url'        => $this->resolveImageUrl($img->image_path),
                                    'is_primary' => (bool) $img->is_main,
                                    'sort_order' => $img->sort_order,
                                ]);
                            }
                        }
                    } else {
                        // If no featured image is set, fall back to standard order (primary first)
                        foreach ($allImages as $img) {
                            $thumbnails->push([
                                'id'         => $img->id,
                                'url'        => $this->resolveImageUrl($img->image_path),
                                'is_primary' => (bool) $img->is_main,
                                'sort_order' => $img->sort_order,
                            ]);
                        }
                    }

                    return $thumbnails->values();
                })(),
                'customization' => $p->customizationSteps
                    ? $p->customizationSteps->map(fn ($step) => [
                        'step_id'    => $step->id,
                        'step_name'  => $step->name,
                        'type'       => $step->type ?? 'dropdown',
                        'color_value'=> $step->color_value,
                        'sort_order' => $step->sort_order,
                        'options'    => $step->options->map(fn ($opt) => [
                            'id'          => $opt->id,
                            'name'        => $opt->name,
                            'type'        => $opt->type ?? 'dropdown',
                            'color_value' => $opt->color_value,
                            'image'       => $this->resolveImageUrl($opt->image_path),
                            'is_default'  => $opt->is_default,
                            'sub_steps'   => $opt->subSteps->map(fn ($ss) => [
                                'step_id'    => $ss->id,
                                'step_name'  => $ss->name,
                                'type'       => $ss->type ?? 'dropdown',
                                'color_value'=> $ss->color_value,
                                'sort_order' => $ss->sort_order,
                                'options'    => $ss->subOptions->map(fn ($so) => [
                                    'id'          => $so->id,
                                    'name'        => $so->name,
                                    'type'        => $so->type ?? 'dropdown',
                                    'color_value' => $so->color_value,
                                    'image'       => $this->resolveImageUrl($so->image_path),
                                    'is_default'  => $so->is_default,
                                ])->values(),
                            ])->values(),
                        ])->values(),
                    ])->values()
                    : [],
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

        // Normalise path — ensure storage/ prefix for stored uploads
        $normalised = ltrim($path, '/');

        // If the path doesn't already start with 'storage/' or 'public/',
        // it's a relative upload path (e.g. "products/xxx.png") → prepend storage/
        if (!str_starts_with($normalised, 'storage/') && !str_starts_with($normalised, 'public/')) {
            $normalised = 'storage/' . $normalised;
        }

        return asset($normalised);
    }
}
