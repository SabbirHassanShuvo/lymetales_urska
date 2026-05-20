<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();
        // Load categories/subcategories for form selects
        $categories = Category::parents()->orderBy('name')->get();
        $subcategories = Category::whereNotNull('parent_id')->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'pages' => 'nullable|integer|min:1',
            'age_range' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'characters' => 'nullable|string|max:255',
            'cover_type' => 'nullable|string|max:255',
            'print_type' => 'nullable|string|max:255',
            'paper_type' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|between:0,5',
            'reviews_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:4096',
            'image_url' => 'nullable|string|url',
            'gallery_files.*' => 'nullable|image|max:4096',
            'gallery_urls' => 'nullable|string',
            'is_bestseller' => 'boolean',
            'is_recommended' => 'boolean',
            'status' => 'boolean',
        ]);

        $validated['is_bestseller'] = $request->boolean('is_bestseller');
        $validated['is_recommended'] = $request->boolean('is_recommended');
        $validated['status'] = $request->boolean('status');
        $validated['slug'] = Str::slug($validated['title']);

        // Handle Main Image upload or URL
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->image_url;
        }

        // Handle Gallery images
        $gallery = [];

        // Add URL gallery images first if any
        if ($request->filled('gallery_urls')) {
            $urls = array_map('trim', explode(',', $request->gallery_urls));
            foreach ($urls as $url) {
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $gallery[] = $url;
                }
            }
        }

        // Add uploaded gallery files
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('products', 'public');
                $gallery[] = '/storage/' . $path;
            }
        }

        $validated['gallery'] = $gallery;

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Book created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'pages' => 'nullable|integer|min:1',
            'age_range' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'characters' => 'nullable|string|max:255',
            'cover_type' => 'nullable|string|max:255',
            'print_type' => 'nullable|string|max:255',
            'paper_type' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|between:0,5',
            'reviews_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:4096',
            'image_url' => 'nullable|string|url',
            'gallery_files.*' => 'nullable|image|max:4096',
            'gallery_urls' => 'nullable|string',
            'is_bestseller' => 'boolean',
            'is_recommended' => 'boolean',
            'status' => 'boolean',
        ]);

        $validated['is_bestseller'] = $request->boolean('is_bestseller');
        $validated['is_recommended'] = $request->boolean('is_recommended');
        $validated['status'] = $request->boolean('status');
        $validated['slug'] = Str::slug($validated['title']);

        // Handle Main Image upload or URL
        if ($request->hasFile('image')) {
            // Delete old local file if exists
            if ($product->image && str_starts_with($product->image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->image_url;
        }

        // Handle Gallery images
        $gallery = $product->gallery ?? [];

        // Check if user wants to reset/replace gallery
        if ($request->hasFile('gallery_files') || $request->filled('gallery_urls')) {
            // If they provided new files or URLs, we replace or append
            if ($request->boolean('replace_gallery')) {
                // Delete old local files if exists
                foreach ($gallery as $img) {
                    if ($img && str_starts_with($img, '/storage/')) {
                        Storage::disk('public')->delete(str_replace('/storage/', '', $img));
                    }
                }
                $gallery = [];
            }

            if ($request->filled('gallery_urls')) {
                $urls = array_map('trim', explode(',', $request->gallery_urls));
                foreach ($urls as $url) {
                    if (filter_var($url, FILTER_VALIDATE_URL)) {
                        $gallery[] = $url;
                    }
                }
            }

            if ($request->hasFile('gallery_files')) {
                foreach ($request->file('gallery_files') as $file) {
                    $path = $file->store('products', 'public');
                    $gallery[] = '/storage/' . $path;
                }
            }
        }

        $validated['gallery'] = $gallery;

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete main image file if local
        if ($product->image && str_starts_with($product->image, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
        }

        // Delete gallery files if local
        if ($product->gallery && is_array($product->gallery)) {
            foreach ($product->gallery as $img) {
                if ($img && str_starts_with($img, '/storage/')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $img));
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Book deleted successfully.');
    }
}
