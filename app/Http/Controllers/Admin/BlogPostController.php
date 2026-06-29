<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    private function handleImageUpload(Request $request, $fileKey, $oldImageUrl = null)
    {
        if ($request->hasFile($fileKey)) {
            // Delete old image if exists
            if ($oldImageUrl && file_exists(public_path($oldImageUrl))) {
                @unlink(public_path($oldImageUrl));
            }

            $file = $request->file($fileKey);
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/blog'), $filename);
            return 'uploads/blog/' . $filename;
        }
        return $oldImageUrl;
    }

    public function index(Request $request)
    {
        $lang = $request->input('lang', 'SL');
        $posts = BlogPost::where('language_type', $lang)->orderBy('published_at', 'desc')->get();
        return view('admin.blog.index', compact('posts', 'lang'));
    }

    public function create(Request $request)
    {
        $lang = $request->input('lang', 'SL');
        return view('admin.blog.create', compact('lang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'category' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'reading_time' => 'nullable|string|max:255',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'published_at' => 'nullable|date',
        ]);

        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title'));

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $coverImage = $this->handleImageUpload($request, 'cover_image_file');

        BlogPost::create([
            'title' => $request->input('title'),
            'slug' => $slug,
            'category' => $request->input('category'),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'reading_time' => $request->input('reading_time') ?: '5 min read',
            'cover_image' => $coverImage,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
            'published_at' => $request->input('published_at') ?: now(),
            'language_type' => $request->input('language_type', 'SL'),
        ]);

        return redirect()->route('admin.blog.index', ['lang' => $request->input('language_type', 'SL')])->with('success', 'Blog post created successfully.');
    }

    public function edit(Request $request, BlogPost $blogPost)
    {
        $lang = $request->query('lang');
        if ($lang && $lang !== $blogPost->language_type) {
            $translatedPost = BlogPost::where('slug', $blogPost->slug)->where('language_type', $lang)->first();
            if ($translatedPost) {
                return redirect()->route('admin.blog.edit', ['blog_post' => $translatedPost->id]);
            } else {
                $newPost = $blogPost->replicate();
                $newPost->language_type = $lang;
                $newPost->save();
                return redirect()->route('admin.blog.edit', ['blog_post' => $newPost->id]);
            }
        }

        $lang = $blogPost->language_type;
        return view('admin.blog.edit', compact('blogPost', 'lang'));
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug,' . $blogPost->id,
            'category' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'reading_time' => 'nullable|string|max:255',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
            'published_at' => 'nullable|date',
        ]);

        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title'));

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (BlogPost::where('slug', $slug)->where('id', '!=', $blogPost->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $coverImage = $this->handleImageUpload($request, 'cover_image_file', $blogPost->cover_image);

        // If featured is checked, we can optionally unfeature other posts (or keep it simple)
        if ($request->has('is_featured')) {
            BlogPost::where('id', '!=', $blogPost->id)
                ->where('language_type', $blogPost->language_type)
                ->update(['is_featured' => false]);
        }

        $blogPost->update([
            'title' => $request->input('title'),
            'slug' => $slug,
            'category' => $request->input('category'),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'reading_time' => $request->input('reading_time') ?: '5 min read',
            'cover_image' => $coverImage,
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
            'published_at' => $request->input('published_at') ?: $blogPost->published_at,
        ]);

        return redirect()->route('admin.blog.index', ['lang' => $blogPost->language_type])->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $lang = $blogPost->language_type;
        
        if ($blogPost->cover_image && file_exists(public_path($blogPost->cover_image))) {
            @unlink(public_path($blogPost->cover_image));
        }

        $blogPost->delete();

        return redirect()->route('admin.blog.index', ['lang' => $lang])->with('success', 'Blog post deleted successfully.');
    }

    // ─── Guest Views ─────────────────────────────────────────────────────────
    public function publicIndex(Request $request)
    {
        $lang = $request->input('lang', session('language', 'SL'));
        
        $posts = BlogPost::where('is_active', true)
            ->where('language_type', $lang)
            ->orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->get();

        $featured = $posts->where('is_featured', true)->first();
        if (!$featured) {
            $featured = $posts->first();
        }

        $gridPosts = $posts->filter(fn($p) => $featured ? $p->id !== $featured->id : true);

        return view('blog.index', compact('posts', 'featured', 'gridPosts'));
    }

    public function publicShow($slug)
    {
        $post = BlogPost::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('blog.show', compact('post'));
    }

    public function updateHeader(Request $request)
    {
        $lang = $request->input('lang', 'SL');

        $request->validate([
            'blog_header_badge' => 'nullable|string|max:255',
            'blog_header_title' => 'nullable|string|max:255',
            'blog_header_subtitle' => 'nullable|string',
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'blog_header_badge_' . $lang],
            ['value' => $request->input('blog_header_badge')]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'blog_header_title_' . $lang],
            ['value' => $request->input('blog_header_title')]
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'blog_header_subtitle_' . $lang],
            ['value' => $request->input('blog_header_subtitle')]
        );

        return redirect()->route('admin.blog.index', ['lang' => $lang])->with('header_success', 'Blog header settings updated successfully.');
    }
}
