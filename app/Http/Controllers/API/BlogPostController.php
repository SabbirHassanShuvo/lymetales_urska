<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\FooterSection;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class BlogPostController extends Controller
{
    /**
     * Get blog posts list along with the manageable blog page header content.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Fetch manageable header content from settings
        $header = [
            'badge' => Setting::getVal('blog_header_badge', 'THE JOURNAL'),
            'title' => Setting::getVal('blog_header_title', 'Stories, ideas, and quiet inspiration'),
            'subtitle' => Setting::getVal('blog_header_subtitle', 'Thoughts on storytelling, parenting, and the small rituals that make childhood feel like magic.'),
        ];

        // Fetch active posts
        $posts = BlogPost::where('is_active', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'category' => $post->category,
                    'excerpt' => $post->excerpt,
                    'content' => $post->content,
                    'cover_image_url' => $post->cover_image ? (filter_var($post->cover_image, FILTER_VALIDATE_URL) ? $post->cover_image : asset($post->cover_image)) : null,
                    'reading_time' => $post->reading_time,
                    'is_featured' => $post->is_featured,
                    'published_at' => $post->published_at,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'header' => $header,
            'data' => $posts,
            'message' => 'Blog posts and header retrieved successfully.'
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get a specific blog post by slug.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse
    {
        $post = BlogPost::where('slug', $slug)->where('is_active', true)->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found.'
            ], 404);
        }

        $formattedPost = [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'category' => $post->category,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'cover_image_url' => $post->cover_image ? (filter_var($post->cover_image, FILTER_VALIDATE_URL) ? $post->cover_image : asset($post->cover_image)) : null,
            'reading_time' => $post->reading_time,
            'is_featured' => $post->is_featured,
            'published_at' => $post->published_at,
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
        ];

        return response()->json([
            'success' => true,
            'data' => $formattedPost,
            'message' => 'Article retrieved successfully.'
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get the WhatsApp URL.
     *
     * @return JsonResponse
     */
    public function whatsapp(): JsonResponse
    {
        $whatsappUrl = Setting::getVal('whatsapp_url', '');

        return response()->json([
            'success' => true,
            'whatsapp_url' => $whatsappUrl,
            'message' => 'WhatsApp URL retrieved successfully.'
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get all footer content.
     *
     * @return JsonResponse
     */
    public function footer(): JsonResponse
    {
        $formatImage = function ($path) {
            if (!$path) return null;
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }
            return asset($path);
        };

        // 1. Footer sections / columns
        $sections = FooterSection::with('items')->orderBy('sort_order')->get()->map(function ($section) {
            return [
                'title' => $section->title,
                'items' => $section->items->map(function ($item) {
                    return [
                        'label' => $item->label,
                        'url'   => $item->url,
                    ];
                })->values()->toArray(),
            ];
        })->values()->toArray();

        // 2. Footer Brand Info
        $footerLogoRaw = Setting::getVal('footer_logo_path', '');
        $footer_brand = [
            'description' => Setting::getVal('footer_brand_description', 'Crafting personalized stories that celebrate the magic of childhood and the bonds of family.'),
            'logo_url'    => $footerLogoRaw ? $formatImage($footerLogoRaw) : null,
            'copyright'   => Setting::getVal('footer_copyright', '© ' . date('Y') . ' Lymetales HQ, Inc. All Rights Reserved.'),
        ];

        // 3. Social links (both as keyed object AND as list with icons)
        $socialLinksJson = Setting::getVal('social_media_links', null);
        $socialLinksArr = [];
        if ($socialLinksJson) {
            $socialLinksArr = json_decode($socialLinksJson, true) ?? [];
        } else {
            $inst = Setting::getVal('social_instagram', '');
            if ($inst) $socialLinksArr[] = ['label' => 'Instagram', 'url' => $inst];
            $tktk = Setting::getVal('social_tiktok', '');
            if ($tktk) $socialLinksArr[] = ['label' => 'TikTok', 'url' => $tktk];
            $fb = Setting::getVal('social_facebook', '');
            if ($fb) $socialLinksArr[] = ['label' => 'Facebook', 'url' => $fb];
            $tw = Setting::getVal('social_twitter', '');
            if ($tw) $socialLinksArr[] = ['label' => 'X', 'url' => $tw];
        }

        // Keyed social_links object (instagram, tiktok, facebook, twitter keys)
        $social_links = [];
        foreach ($socialLinksArr as $link) {
            $cleanLabel = strtolower(trim($link['label'] ?? ''));
            if (strpos($cleanLabel, 'instagram') !== false) {
                $social_links['instagram'] = $link['url'] ?? '';
            } elseif (strpos($cleanLabel, 'tiktok') !== false) {
                $social_links['tiktok'] = $link['url'] ?? '';
            } elseif (strpos($cleanLabel, 'facebook') !== false) {
                $social_links['facebook'] = $link['url'] ?? '';
            } elseif (strpos($cleanLabel, 'twitter') !== false || $cleanLabel === 'x') {
                $social_links['twitter'] = $link['url'] ?? '';
            } elseif (strpos($cleanLabel, 'youtube') !== false) {
                $social_links['youtube'] = $link['url'] ?? '';
            } elseif (strpos($cleanLabel, 'linkedin') !== false) {
                $social_links['linkedin'] = $link['url'] ?? '';
            }
        }

        // List version with icons
        $social_links_list = array_map(function ($link) {
            $label = $link['label'] ?? '';
            $url   = $link['url'] ?? '';
            $cleanLabel = strtolower(trim($label));

            $icon = 'FaShareAlt';
            if (strpos($cleanLabel, 'instagram') !== false) $icon = 'FaInstagram';
            elseif (strpos($cleanLabel, 'tiktok') !== false) $icon = 'FaTiktok';
            elseif (strpos($cleanLabel, 'facebook') !== false) $icon = 'FaFacebook';
            elseif (strpos($cleanLabel, 'youtube') !== false) $icon = 'FaYoutube';
            elseif (strpos($cleanLabel, 'twitter') !== false || $cleanLabel === 'x') $icon = 'FaTwitter';
            elseif (strpos($cleanLabel, 'linkedin') !== false) $icon = 'FaLinkedin';
            elseif (strpos($cleanLabel, 'pinterest') !== false) $icon = 'FaPinterest';

            return ['label' => $label, 'url' => $url, 'icon' => $icon];
        }, $socialLinksArr);

        return response()->json([
            'success' => true,
            'data' => [
                'footer'            => $sections,
                'footer_brand'      => $footer_brand,
                'social_links'      => $social_links,
                'social_links_list' => array_values($social_links_list),
            ],
            'message' => 'Footer content retrieved successfully.'
        ], 200, [], JSON_UNESCAPED_SLASHES);
    }
}
