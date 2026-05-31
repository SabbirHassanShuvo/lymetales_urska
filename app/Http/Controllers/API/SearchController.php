<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * GET /api/shop/search
     *
     * Smart global search for books, characters, or themes.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q', $request->input('search', '')));

        // Smart Natural Language Parsing (Heuristic Heuristics)
        $ageRange = null;
        $isBestseller = false;
        $isRecommended = false;
        $cleanQuery = $query;

        if (!empty($query)) {
            // 1. Detect and extract age range (e.g., "3-5", "4-8", "2-6", "3 to 5")
            if (preg_match('/3\s*(?:-|to)\s*5/i', $query)) {
                $ageRange = '3-5 Years';
            } elseif (preg_match('/4\s*(?:-|to)\s*8/i', $query)) {
                $ageRange = '4-8 Years';
            } elseif (preg_match('/2\s*(?:-|to)\s*6/i', $query)) {
                $ageRange = '2-6 Years';
            }

            // Remove detected age range terms to avoid polluted textual searches
            $cleanQuery = preg_replace('/(?:3\s*(?:-|to)\s*5(?:\s*years)?|4\s*(?:-|to)\s*8(?:\s*years)?|2\s*(?:-|to)\s*6(?:\s*years)?)/i', '', $cleanQuery);

            // 2. Detect Bestseller intent
            if (preg_match('/\b(?:best\s*seller|bestseller|popular)\b/i', $cleanQuery)) {
                $isBestseller = true;
                $cleanQuery = preg_replace('/\b(?:best\s*seller|bestseller|popular)\b/i', '', $cleanQuery);
            }

            // 3. Detect Recommended intent
            if (preg_match('/\b(?:recommended|recommend|featured)\b/i', $cleanQuery)) {
                $isRecommended = true;
                $cleanQuery = preg_replace('/\b(?:recommended|recommend|featured)\b/i', '', $cleanQuery);
            }

            // Clean multiple spaces and trim
            $cleanQuery = trim(preg_replace('/\s+/', ' ', $cleanQuery));
        }

        // --- 1. SEARCH BOOKS (PRODUCTS) ---
        $booksQuery = Product::with(['category', 'subcategory', 'primaryImage'])
            ->where('status', true);

        if (!empty($cleanQuery)) {
            $booksQuery->where(function ($q) use ($cleanQuery) {
                $q->where('title', 'like', "%{$cleanQuery}%")
                  ->orWhere('description', 'like', "%{$cleanQuery}%")
                  ->orWhere('characters', 'like', "%{$cleanQuery}%");
            });
        }

        // Apply smart extracted filters
        if ($ageRange) {
            $booksQuery->where('age_range', $ageRange);
        }
        if ($isBestseller) {
            $booksQuery->where('is_bestseller', true);
        }
        if ($isRecommended) {
            $booksQuery->where('is_recommended', true);
        }

        $books = $booksQuery->latest()->limit(12)->get();

        // --- 2. SEARCH THEMES (CATEGORIES / SUBCATEGORIES) ---
        $themes = [];
        
        $categoriesQuery = Category::where('status', true);
        $subcategoriesQuery = Subcategory::where('status', true);

        if (!empty($cleanQuery)) {
            $categoriesQuery->where(function ($q) use ($cleanQuery) {
                $q->where('name', 'like', "%{$cleanQuery}%")
                  ->orWhere('description', 'like', "%{$cleanQuery}%");
            });

            $subcategoriesQuery->where(function ($q) use ($cleanQuery) {
                $q->where('name', 'like', "%{$cleanQuery}%")
                  ->orWhere('description', 'like', "%{$cleanQuery}%");
            });

            $categories = $categoriesQuery->limit(5)->get();
            $subcategories = $subcategoriesQuery->limit(5)->get();
        } else {
            // If empty search, return active themes by default as featured tags
            $categories = $categoriesQuery->limit(4)->get();
            $subcategories = $subcategoriesQuery->limit(6)->get();
        }

        foreach ($categories as $cat) {
            $themes[] = [
                'id'          => $cat->id,
                'name'        => $cat->name,
                'slug'        => $cat->slug,
                'description' => $cat->description,
                'type'        => 'category'
            ];
        }

        foreach ($subcategories as $sub) {
            $themes[] = [
                'id'          => $sub->id,
                'name'        => $sub->name,
                'slug'        => $sub->slug,
                'description' => $sub->description,
                'type'        => 'subcategory'
            ];
        }

        // --- 3. SEARCH CHARACTERS ---
        $charactersQuery = Product::with(['category', 'subcategory', 'primaryImage'])
            ->where('status', true);

        if (!empty($cleanQuery)) {
            $charactersQuery->where('characters', 'like', "%{$cleanQuery}%");
            $characterProducts = $charactersQuery->latest()->limit(6)->get();
        } else {
            // Default character list (exclude products with "None" characters)
            $characterProducts = $charactersQuery->whereNotNull('characters')
                ->where('characters', '!=', 'None')
                ->latest()
                ->limit(6)
                ->get();
        }

        // Format outputs
        $formattedBooks = $books->map(fn ($p) => $this->formatProduct($p))->values();
        
        $formattedCharacters = $characterProducts->map(fn ($p) => [
            'id'         => $p->id,
            'title'      => $p->title,
            'slug'       => $p->slug,
            'image'      => $p->imageUrl,
            'characters' => $p->characters,
        ])->values();

        $popularSearches = [
            "Easter", "Birthday", "Christmas", "Bedtime", "Adventure", "Dinosaurs", "Princess", "Space"
        ];

        return response()->json([
            'success' => true,
            'data'    => [
                'books'            => $formattedBooks,
                'themes'           => $themes,
                'characters'       => $formattedCharacters,
                'popular_searches' => $popularSearches,
            ],
            'meta'    => [
                'query'                => $query,
                'parsed_age_range'     => $ageRange,
                'parsed_bestseller'    => $isBestseller,
                'parsed_recommended'   => $isRecommended,
                'clean_search_keyword' => $cleanQuery,
            ],
            'message' => 'Search results retrieved successfully.'
        ]);
    }

    /**
     * Map a Product model instance to consistent array format
     */
    private function formatProduct(Product $p): array
    {
        return [
            'id'             => $p->id,
            'title'          => $p->title,
            'slug'           => $p->slug,
            'price'          => (float) $p->price,
            'image'          => $p->imageUrl,
            'rating'         => (float) $p->rating,
            'reviews_count'  => $p->reviews_count,
            'is_bestseller'  => $p->is_bestseller,
            'is_recommended' => $p->is_recommended,
            'age_range'      => $p->age_range,
            'category'       => $p->category ? [
                'id'   => $p->category->id,
                'name' => $p->category->name,
                'slug' => $p->category->slug,
            ] : null,
            'subcategory'    => $p->subcategory ? [
                'id'   => $p->subcategory->id,
                'name' => $p->subcategory->name,
                'slug' => $p->subcategory->slug,
            ] : null,
        ];
    }
}
