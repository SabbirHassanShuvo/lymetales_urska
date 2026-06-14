<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\GiftCard;
use App\Models\HeroSection;
use App\Models\HomeFeature;
use App\Models\HomePromo;
use App\Models\GiftGiver;
use App\Models\FooterSection;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class HomeContentControllerNew extends Controller
{
    /**
     * Get all home content (Hero, Features, Latest Products, Gift Cards, Promo,
     * Gift Giver steps, Reviews, FAQs, Newsletter, Footer).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Helper to format image URLs
        $formatImage = function ($path) {
            if (!$path) return null;
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }
            return asset($path);
        };

        // 1. Hero sections (Requirement 1: only title, image, 2 button texts)
        $heroSections = HeroSection::all()->map(function ($hero) use ($formatImage) {
            return [
                'id'              => $hero->id,
                'title'           => $hero->title,
                'image_url'       => $formatImage($hero->image_path),
                'button_one_text' => $hero->button_one_text,
                'button_two_text' => $hero->button_two_text,
                'created_at'      => $hero->created_at,
                'updated_at'      => $hero->updated_at,
            ];
        });

        // 2. Highlight features (Requirement 2: only title and description)
        $features = HomeFeature::all()->map(function ($feat) {
            return [
                'id'          => $feat->id,
                'title'       => $feat->title,
                'description' => $feat->description,
            ];
        });

        // 2b. Latest 6 published products (after features section)
        $latestProducts = Product::where('status', true)
            ->with(['images', 'galleryImages', 'primaryImage'])
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id'            => $product->id,
                    'title'         => $product->title,
                    'slug'          => $product->slug,
                    'price'         => (float) $product->price,
                    'rating'        => (float) $product->rating,
                    'reviews_count' => (int) $product->reviews_count,
                    'is_bestseller' => (bool) $product->is_bestseller,
                    'image_url'     => $product->image_url,
                ];
            });

        // 3. Gift cards (Requirement 3: remove link!)
        $giftCards = GiftCard::all()->map(function ($gift) use ($formatImage) {
            return [
                'id'        => $gift->id,
                'title'     => $gift->title,
                'subtitle'  => $gift->subtitle,
                'image_url' => $formatImage($gift->image_path),
                'created_at'=> $gift->created_at,
                'updated_at'=> $gift->updated_at,
            ];
        });

        // 4. Middle promo section (Requirement 4)
        $promoModel = HomePromo::first();
        $promoSection = $promoModel ? [
            'title'       => $promoModel->title,
            'description' => $promoModel->description,
            'button_text' => $promoModel->button_text,
            'image_url'   => $formatImage($promoModel->image_path),
        ] : null;

        // 5. Legendary Gift-Giver section (Requirement 5)
        $giverModel = GiftGiver::first();
        $giftGiverSection = $giverModel ? [
            'subtitle'    => $giverModel->subtitle,
            'title'       => $giverModel->title,
            'step_1_image'=> $formatImage($giverModel->step_1_image),
            'step_1_text' => $giverModel->step_1_text,
            'step_2_image'=> $formatImage($giverModel->step_2_image),
            'step_2_text' => $giverModel->step_2_text,
            'step_3_image'=> $formatImage($giverModel->step_3_image),
            'step_3_text' => $giverModel->step_3_text,
        ] : null;

        // 5b. Reviews — approved, sorted by rating desc
        // Format a single review record
        $formatReview = function ($review) {
            return [
                'id'                => $review->id,
                'reviewer_name'     => $review->reviewer_name,
                'reviewer_location' => $review->reviewer_location,
                'rating'            => (float) $review->rating,
                'title'             => $review->title,
                'comment'           => $review->comment,
                'created_at'        => $review->created_at,
            ];
        };

        // All approved reviews ordered by rating desc
        $allApprovedReviews = ProductReview::where('is_approved', true)
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->get();

        $reviews = [
            'top_3' => $allApprovedReviews->take(3)->map($formatReview)->values(),
            'all'   => $allApprovedReviews->map($formatReview)->values(),
        ];

        // 6. FAQs (Requirement 6)
        $faqs = Faq::all()->map(function ($faq) {
            return [
                'id'       => $faq->id,
                'question' => $faq->question,
                'answer'   => $faq->answer,
            ];
        });

        // 7. Newsletter Section Texts (Requirement 7)
        $newsletterSection = [
            'title'       => Setting::getVal('newsletter_title', 'Get 10% off your first order'),
            'description' => Setting::getVal('newsletter_description', 'Join our community and create magical moments for the children you love.'),
        ];

        // 8. Footer Section Links (Requirement 8)
        $footer = FooterSection::with('items')->orderBy('sort_order')->get()->map(function ($section) {
            return [
                'title' => $section->title,
                'items' => $section->items->map(function ($item) {
                    return [
                        'label' => $item->label,
                        'url'   => $item->url,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // 9. Footer Brand Info & Social Links
        $footerLogoRaw = Setting::getVal('footer_logo_path', '');
        $footerBrand = [
            'description' => Setting::getVal('footer_brand_description', 'Crafting personalized stories that celebrate the magic of childhood and the bonds of family.'),
            'logo_url'    => $footerLogoRaw ? $formatImage($footerLogoRaw) : null,
            'copyright'   => Setting::getVal('footer_copyright', '© ' . date('Y') . ' Lymetales HQ, Inc. All Rights Reserved.'),
        ];

        $socialLinks = [
            'instagram' => Setting::getVal('social_instagram', ''),
            'tiktok'    => Setting::getVal('social_tiktok', ''),
            'facebook'  => Setting::getVal('social_facebook', ''),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'hero_sections'      => $heroSections,
                'features'           => $features,
                'latest_products'    => $latestProducts,      // ← NEW: after features
                'gift_cards'         => $giftCards,
                'promo_section'      => $promoSection,
                'gift_giver_section' => $giftGiverSection,
                'reviews'            => $reviews,             // ← NEW: after gift_giver_section
                'faqs'               => $faqs,
                'newsletter_section' => $newsletterSection,
                'footer'             => $footer,
                'footer_brand'       => $footerBrand,
                'social_links'       => $socialLinks,
            ],
            'message' => 'Home content retrieved successfully.',
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
}
