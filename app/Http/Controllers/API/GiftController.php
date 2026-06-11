<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use Illuminate\Http\JsonResponse;

class GiftController extends Controller
{
    /**
     * Get all gifts.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $gifts = Gift::orderBy('created_at', 'desc')->get()->map(function ($gift) {
            return [
                'id' => $gift->id,
                'title' => $gift->title,
                'short_description' => $gift->short_description,
                'price' => (float) $gift->price,
                'image_url' => $gift->image_path ? asset($gift->image_path) : null,
                'created_at' => $gift->created_at,
                'updated_at' => $gift->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $gifts,
            'message' => 'Gifts retrieved successfully.',
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
}
