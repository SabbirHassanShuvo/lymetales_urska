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
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class HomeContentController extends Controller
{
    /**
     * Get all home content (Hero, Features, Gift Cards, Promo, Gift Giver steps, FAQs, Newsletter, Footer).
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
                'id' => $hero->id,
                'title' => $hero->title,
                'image_url' => $formatImage($hero->image_path),
                'button_one_text' => $hero->button_one_text,
                'button_two_text' => $hero->button_two_text,
                'created_at' => $hero->created_at,
                'updated_at' => $hero->updated_at,
            ];
        });

        // 2. Highlight features (Requirement 2: only title and description)
        $features = HomeFeature::all()->map(function ($feat) {
            return [
                'id' => $feat->id,
                'title' => $feat->title,
                'description' => $feat->description,
            ];
        });

        // 3. Gift cards (Requirement 3: remove link!)
        $giftCards = GiftCard::all()->map(function ($gift) use ($formatImage) {
            return [
                'id' => $gift->id,
                'title' => $gift->title,
                'subtitle' => $gift->subtitle,
                'image_url' => $formatImage($gift->image_path),
                'created_at' => $gift->created_at,
                'updated_at' => $gift->updated_at,
            ];
        });

        // 4. Middle promo section (Requirement 4)
        $promoModel = HomePromo::first();
        $promoSection = $promoModel ? [
            'title' => $promoModel->title,
            'description' => $promoModel->description,
            'button_text' => $promoModel->button_text,
            'image_url' => $formatImage($promoModel->image_path),
        ] : null;

        // 5. Legendary Gift-Giver section (Requirement 5)
        $giverModel = GiftGiver::first();
        $giftGiverSection = $giverModel ? [
            'subtitle' => $giverModel->subtitle,
            'title' => $giverModel->title,
            'steps' => [
                [
                    'image' => $formatImage($giverModel->step_1_image),
                    'text' => $giverModel->step_1_text,
                ],
                [
                    'image' => $formatImage($giverModel->step_2_image),
                    'text' => $giverModel->step_2_text,
                ],
                [
                    'image' => $formatImage($giverModel->step_3_image),
                    'text' => $giverModel->step_3_text,
                ],
            ]
        ] : null;

        // 6. FAQs (Requirement 6)
        $faqs = Faq::all()->map(function ($faq) {
            return [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
            ];
        });

        // 7. Newsletter Section Texts (Requirement 7)
        $newsletterSection = [
            'title' => Setting::getVal('newsletter_title', 'Get 10% off your first order'),
            'description' => Setting::getVal('newsletter_description', 'Join our community and create magical moments for the children you love.'),
        ];

        // 8. Footer Section Links (Requirement 8)
        $footer = FooterSection::with('items')->orderBy('sort_order')->get()->map(function ($section) {
            return [
                'title' => $section->title,
                'items' => $section->items->map(function ($item) {
                    return [
                        'label' => $item->label,
                        'url' => $item->url,
                    ];
                })->toArray(),
            ];
        })->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'hero_sections' => $heroSections,
                'features' => $features,
                'gift_cards' => $giftCards,
                'promo_section' => $promoSection,
                'gift_giver_section' => $giftGiverSection,
                'faqs' => $faqs,
                'newsletter_section' => $newsletterSection,
                'footer' => $footer,
            ],
            'message' => 'Home content retrieved successfully.',
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
}
