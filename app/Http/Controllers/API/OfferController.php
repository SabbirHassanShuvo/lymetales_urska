<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;

class OfferController extends Controller
{
    /**
     * Get all active offers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $offers = Offer::where('is_active', true)->orderBy('created_at', 'desc')->get()->map(function ($offer) {
            return [
                'id' => $offer->id,
                'title' => $offer->title,
                'min_quantity' => $offer->min_quantity,
                'discount_percentage' => (float) $offer->discount_percentage,
                'created_at' => $offer->created_at,
                'updated_at' => $offer->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $offers,
            'message' => 'Active offers retrieved successfully.',
        ], 200);
    }
}
