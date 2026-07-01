<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SiteTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteTranslationController extends Controller
{
    /**
     * Return all translations for the requested language as a nested JSON object.
     * Endpoint: GET /api/shop/translations?lang=SL
     */
    public function index(Request $request): JsonResponse
    {
        $lang = strtoupper($request->input('language_type', $request->input('lang', 'SL')));

        $data = SiteTranslation::getAllForLanguage($lang);

        return response()->json([
            'success'       => true,
            'language_type' => $lang,
            'data'          => $data,
            'message'       => 'Translations retrieved successfully.',
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Return global content data (global, cookie, search, books, coupon)
     * Endpoint: GET /api/shop/global-content?lang=SL
     */
    public function globalContent(Request $request): JsonResponse
    {
        $lang = strtoupper($request->input('language_type', $request->input('lang', 'SL')));
        $data = SiteTranslation::getAllForLanguage($lang);

        // Filter the requested groups (if you only want specific groups)
        $groups = ['global', 'cookie', 'search', 'books', 'coupon'];
        
        $filteredData = [];
        foreach ($groups as $group) {
            if (isset($data[$group])) {
                $filteredData[$group] = $data[$group];
            }
        }

        // Manually map the order keys to an 'orders' group since they are stored as order_confirmed and order_failed
        $filteredData['orders'] = [
            'confirmed' => $data['order_confirmed'] ?? [],
            'failed'    => $data['order_failed'] ?? [],
        ];

        return response()->json([
            'success'       => true,
            'language_type' => $lang,
            'data'          => $filteredData,
            'message'       => 'Global content retrieved successfully.',
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
