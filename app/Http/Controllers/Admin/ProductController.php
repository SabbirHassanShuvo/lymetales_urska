<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
            $file = $request->file('image');
            $filename = 'img_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/products'), $filename);
            $validated['image'] = '/storage/products/' . $filename;
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
                $filename = 'gal_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/products'), $filename);
                $gallery[] = '/storage/products/' . $filename;
            }
        }

        $validated['gallery'] = $gallery;

        // Determine correct category_id based on frontend changes
        if ($request->filled('category_id')) {
            $validated['category_id'] = $request->category_id;
        }

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
            // Delete old file if it was a local upload
            if ($product->image && str_starts_with($product->image, '/storage/products/')) {
                $oldPath = public_path(ltrim($product->image, '/'));
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $file = $request->file('image');
            $filename = 'img_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/products'), $filename);
            $validated['image'] = '/storage/products/' . $filename;
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->image_url;
        } else {
            // Keep existing image
            $validated['image'] = $product->image;
        }

        // Handle Gallery images — load existing gallery first
        $gallery = $product->gallery ?? [];

        // Remove deleted gallery images
        if ($request->filled('deleted_gallery_images')) {
            $deletedImages = json_decode($request->deleted_gallery_images, true);
            if (is_array($deletedImages)) {
                foreach ($deletedImages as $delImg) {
                    // Remove from gallery array
                    $key = array_search($delImg, $gallery);
                    if ($key !== false) {
                        unset($gallery[$key]);
                        // Delete local file
                        if (str_starts_with($delImg, '/storage/products/')) {
                            $filePath = public_path(ltrim($delImg, '/'));
                            if (file_exists($filePath)) {
                                @unlink($filePath);
                            }
                        } elseif (str_starts_with($delImg, '/storage/')) {
                            Storage::disk('public')->delete(str_replace('/storage/', '', $delImg));
                        }
                    }
                }
                $gallery = array_values($gallery); // Re-index array
            }
        }

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
                    $filename = 'gal_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('storage/products'), $filename);
                    $gallery[] = '/storage/products/' . $filename;
                }
            }
        }

        $validated['gallery'] = $gallery;

        // Determine correct category_id based on frontend changes
        if ($request->filled('category_id')) {
            $validated['category_id'] = $request->category_id;
        }

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
        if ($product->image) {
            if (str_starts_with($product->image, '/storage/products/')) {
                $filePath = public_path(ltrim($product->image, '/'));
                if (file_exists($filePath)) @unlink($filePath);
            } elseif (str_starts_with($product->image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image));
            }
        }

        // Delete gallery files if local
        if ($product->gallery && is_array($product->gallery)) {
            foreach ($product->gallery as $img) {
                if (!$img) continue;
                if (str_starts_with($img, '/storage/products/')) {
                    $filePath = public_path(ltrim($img, '/'));
                    if (file_exists($filePath)) @unlink($filePath);
                } elseif (str_starts_with($img, '/storage/')) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $img));
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Book deleted successfully.');
    }

    /**
     * Toggle the status of the specified product.
     */
    public function toggleStatus(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->status = !$product->status;
            $product->save();

            return response()->json([
                'success' => true,
                'status' => $product->status,
                'message' => 'Product status updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }
}
