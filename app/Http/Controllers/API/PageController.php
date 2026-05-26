<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Get page data by slug.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $page
        ]);
    }
}
