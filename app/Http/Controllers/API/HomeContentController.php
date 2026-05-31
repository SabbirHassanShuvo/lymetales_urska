<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\GiftCard;
use App\Models\HeroSection;
use Illuminate\Http\JsonResponse;

class HomeContentController extends Controller
{
    /**
     * Get all home content (Hero sections, Gift Cards, FAQs).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $heroSections = HeroSection::all()->map(function ($hero) {
            return [
                'id' => $hero->id,
                'title' => $hero->title,
                'subtitle' => $hero->subtitle,
                'button_one_text' => $hero->button_one_text,
                'button_one_link' => $hero->button_one_link,
                'button_two_text' => $hero->button_two_text,
                'button_two_link' => $hero->button_two_link,
                'image_url' => $hero->image_path ? asset($hero->image_path) : null,
                'created_at' => $hero->created_at,
                'updated_at' => $hero->updated_at,
            ];
        });

        $giftCards = GiftCard::all()->map(function ($gift) {
            return [
                'id' => $gift->id,
                'title' => $gift->title,
                'subtitle' => $gift->subtitle,
                'link' => $gift->link,
                'image_url' => $gift->image_path ? asset($gift->image_path) : null,
                'created_at' => $gift->created_at,
                'updated_at' => $gift->updated_at,
            ];
        });

        $faqs = Faq::all();

        return response()->json([
            'success' => true,
            'data' => [
                'hero_sections' => $heroSections,
                'gift_cards' => $giftCards,
                'faqs' => $faqs,
            ],
            'message' => 'Home content retrieved successfully.',
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
}
