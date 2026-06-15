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

        $content = is_array($page->content) ? $page->content : json_decode($page->content, true);
        
        // Recursively format image URLs (uploads/ -> asset URL)
        $formatUrls = function (&$array) use (&$formatUrls) {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    $formatUrls($value);
                } elseif (is_string($value) && str_starts_with($value, 'uploads/')) {
                    $value = asset($value);
                }
            }
        };

        // Unwrap {html: '...'} paragraph wrapper objects into plain strings
        $unwrapParagraphs = function (&$array) use (&$unwrapParagraphs, $slug) {
            foreach ($array as $key => &$value) {
                if (is_array($value) && isset($value['html']) && count($value) === 1) {
                    // This is a paragraph wrapper — flatten it
                    $unwrapped = $value['html'];
                    if ($slug === 'our-story') {
                        $value = trim(html_entity_decode(strip_tags($unwrapped), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                    } else {
                        $value = $unwrapped;
                    }
                } elseif (is_array($value)) {
                    $unwrapParagraphs($value);
                } elseif (is_string($value) && $slug === 'our-story') {
                    // Also strip HTML from any other CKEditor fields (like badges/titles) on our-story
                    $value = trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                }
            }
        };

        if (is_array($content)) {
            $unwrapParagraphs($content);
            $formatUrls($content);
            $page->content = $content;
        }

        return response()->json([
            'success' => true,
            'data' => $page
        ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
