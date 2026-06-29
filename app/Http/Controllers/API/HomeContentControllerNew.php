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
                'language_type'   => $hero->language_type,
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
                'language_type' => $feat->language_type,
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
                    'language_type' => $product->language_type,
                    'price'         => (float) $product->price,
                    'compare_at_price' => $product->compare_at_price ? (float) $product->compare_at_price : null,
                    'rating'        => (float) $product->rating,
                    'reviews_count' => (int) $product->reviews_count,
                    'is_bestseller' => (bool) $product->is_bestseller,
                    'image'     => $product->image_url,
                ];
            });

        // 3. Gift cards (Requirement 3: remove link!)
        $giftCards = GiftCard::all()->map(function ($gift) use ($formatImage) {
            return [
                'id'        => $gift->id,
                'title'     => $gift->title,
                'subtitle'  => $gift->subtitle,
                'image_url' => $formatImage($gift->image_path),
                'language_type' => $gift->language_type,
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
            'language_type' => $promoModel->language_type,
        ] : null;

        // 5. Legendary Gift-Giver section (Requirement 5)
        $giverModel = GiftGiver::first();
        $giftGiverSection = $giverModel ? [
            'subtitle'    => $giverModel->subtitle,
            'title'       => $giverModel->title,
            'steps'       => [
                [
                    'image' => $formatImage($giverModel->step_1_image),
                    'text'  => $giverModel->step_1_text,
                ],
                [
                    'image' => $formatImage($giverModel->step_2_image),
                    'text'  => $giverModel->step_2_text,
                ],
                [
                    'image' => $formatImage($giverModel->step_3_image),
                    'text'  => $giverModel->step_3_text,
                ],
            ]
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
                'language_type' => $faq->language_type,
            ];
        });

        // 7. Newsletter Section Texts (Requirement 7)
        $lang = request()->input('lang', 'SL');
        $suffix = $lang === 'SL' ? '' : '_' . $lang;

        $newsletterSection = [
            'title'       => Setting::getVal('newsletter_title' . $suffix, Setting::getVal('newsletter_title', 'Get 10% off your first order')),
            'description' => Setting::getVal('newsletter_description' . $suffix, Setting::getVal('newsletter_description', 'Join our community and create magical moments for the children you love.')),
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
            'description' => Setting::getVal('footer_brand_description' . $suffix, Setting::getVal('footer_brand_description', 'Crafting personalized stories that celebrate the magic of childhood and the bonds of family.')),
            'logo_url'    => $footerLogoRaw ? $formatImage($footerLogoRaw) : null,
            'copyright'   => Setting::getVal('footer_copyright' . $suffix, Setting::getVal('footer_copyright', '© ' . date('Y') . ' Lymetales HQ, Inc. All Rights Reserved.')),
        ];

        $socialLinksJson = Setting::getVal('social_media_links' . $suffix, Setting::getVal('social_media_links', null));
        $socialLinks = [];
        if ($socialLinksJson) {
            $socialLinks = json_decode($socialLinksJson, true);
        } else {
            $inst = Setting::getVal('social_instagram', '');
            if ($inst) $socialLinks[] = ['label' => 'Instagram', 'url' => $inst];
            $tktk = Setting::getVal('social_tiktok', '');
            if ($tktk) $socialLinks[] = ['label' => 'TikTok', 'url' => $tktk];
            $fb = Setting::getVal('social_facebook', '');
            if ($fb) $socialLinks[] = ['label' => 'Facebook', 'url' => $fb];
        }

        // Map icons dynamically
        $socialLinksList = array_map(function ($link) {
            $label = $link['label'] ?? '';
            $url = $link['url'] ?? '';
            
            $cleanLabel = strtolower(trim($label));
            $icon = 'FaShareAlt';
            if (strpos($cleanLabel, 'instagram') !== false) {
                $icon = 'FaInstagram';
            } elseif (strpos($cleanLabel, 'tiktok') !== false) {
                $icon = 'FaTiktok';
            } elseif (strpos($cleanLabel, 'facebook') !== false) {
                $icon = 'FaFacebook';
            } elseif (strpos($cleanLabel, 'youtube') !== false) {
                $icon = 'FaYoutube';
            } elseif (strpos($cleanLabel, 'twitter') !== false || $cleanLabel === 'x') {
                $icon = 'FaTwitter';
            } elseif (strpos($cleanLabel, 'linkedin') !== false) {
                $icon = 'FaLinkedin';
            } elseif (strpos($cleanLabel, 'pinterest') !== false) {
                $icon = 'FaPinterest';
            }

            return [
                'label' => $label,
                'url'   => $url,
                'icon'  => $icon,
            ];
        }, $socialLinks);

        // Rebuild legacy key-value object to prevent React crash
        $socialLinksObj = [
            'instagram' => '',
            'tiktok' => '',
            'facebook' => '',
        ];
        
        foreach ($socialLinksList as $l) {
            $cl = strtolower($l['label']);
            if (strpos($cl, 'instagram') !== false) {
                $socialLinksObj['instagram'] = $l['url'];
            } elseif (strpos($cl, 'tiktok') !== false) {
                $socialLinksObj['tiktok'] = $l['url'];
            } elseif (strpos($cl, 'facebook') !== false) {
                $socialLinksObj['facebook'] = $l['url'];
            }
        }

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
                'social_links'       => $socialLinksObj,
                'social_links_list'  => $socialLinksList,
            ],
            'message' => 'Home content retrieved successfully.',
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get just the FAQs data for a dedicated API endpoint.
     *
     * @return JsonResponse
     */
    public function faqs(): JsonResponse
    {
        $faqs = Faq::all()->map(function ($faq) {
            return [
                'id'       => $faq->id,
                'question' => $faq->question,
                'answer'   => $faq->answer,
                'language_type' => $faq->language_type,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $faqs,
            'message' => 'FAQs retrieved successfully.',
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
}
