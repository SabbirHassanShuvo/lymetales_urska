<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'primaryImage', 'images'])
            ->orderBy('created_at', 'desc')
            ->get();

        $categories   = Category::parents()->orderBy('name')->get();
        $subcategories = Category::whereNotNull('parent_id')->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'              => 'required|string|max:255',
            'category_id'        => 'nullable|exists:categories,id',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'pages'              => 'nullable|integer|min:1',
            'age_range'          => 'nullable|string|max:255',
            'size'               => 'nullable|string|max:255',
            'characters'         => 'nullable|string|max:255',
            'cover_type'         => 'nullable|string|max:255',
            'print_type'         => 'nullable|string|max:255',
            'paper_type'         => 'nullable|string|max:255',
            'rating'             => 'nullable|numeric|between:0,5',
            'reviews_count'      => 'nullable|integer|min:0',
            'image'              => 'nullable|image|max:4096',
            'image_url'          => 'nullable|string|max:2048',
            'gallery_files.*'    => 'nullable|image|max:4096',
            'gallery_urls'       => 'nullable|string',
            'is_bestseller'      => 'nullable|boolean',
            'is_recommended'     => 'nullable|boolean',
            'status'             => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::create([
                'title'          => $request->title,
                'category_id'    => $request->filled('category_id') ? $request->category_id : null,
                'description'    => $request->description,
                'price'          => $request->price,
                'pages'          => $request->pages,
                'age_range'      => $request->age_range,
                'size'           => $request->size,
                'characters'     => $request->characters,
                'cover_type'     => $request->cover_type,
                'print_type'     => $request->print_type,
                'paper_type'     => $request->paper_type,
                'rating'         => $request->rating ?? 5.0,
                'reviews_count'  => $request->reviews_count ?? 0,
                'is_bestseller'  => $request->boolean('is_bestseller'),
                'is_recommended' => $request->boolean('is_recommended'),
                'status'         => $request->boolean('status'),
                'slug'           => Str::slug($request->title),
            ]);

            $sortOrder = 0;

            // Primary image — uploaded file takes priority over URL
            $primaryPath = null;
            if ($request->hasFile('image')) {
                $primaryPath = $this->saveUploadedFile($request->file('image'), 'img');
            } elseif ($request->filled('image_url')) {
                $primaryPath = $request->image_url;
            }

            if ($primaryPath) {
                $product->images()->create([
                    'image_path' => $primaryPath,
                    'is_main'    => true,
                    'sort_order' => $sortOrder++,
                ]);
            }

            // Gallery — URL list
            if ($request->filled('gallery_urls')) {
                foreach (array_map('trim', explode(',', $request->gallery_urls)) as $url) {
                    if ($url) {
                        $product->images()->create([
                            'image_path' => $url,
                            'is_main'    => false,
                            'sort_order' => $sortOrder++,
                        ]);
                    }
                }
            }

            // Gallery — uploaded files
            if ($request->hasFile('gallery_files')) {
                foreach ($request->file('gallery_files') as $file) {
                    $product->images()->create([
                        'image_path' => $this->saveUploadedFile($file, 'gal'),
                        'is_main'    => false,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Book created successfully.');
    }

    public function update(Request $request, string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        $request->validate([
            'title'              => 'required|string|max:255',
            'category_id'        => 'nullable|exists:categories,id',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'pages'              => 'nullable|integer|min:1',
            'age_range'          => 'nullable|string|max:255',
            'size'               => 'nullable|string|max:255',
            'characters'         => 'nullable|string|max:255',
            'cover_type'         => 'nullable|string|max:255',
            'print_type'         => 'nullable|string|max:255',
            'paper_type'         => 'nullable|string|max:255',
            'rating'             => 'nullable|numeric|between:0,5',
            'reviews_count'      => 'nullable|integer|min:0',
            'image'              => 'nullable|image|max:4096',
            'image_url'          => 'nullable|string|max:2048',
            'gallery_files.*'    => 'nullable|image|max:4096',
            'gallery_urls'       => 'nullable|string',
            'is_bestseller'      => 'nullable|boolean',
            'is_recommended'     => 'nullable|boolean',
            'status'             => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $product) {
            $product->update([
                'title'          => $request->title,
                'category_id'    => $request->filled('category_id') ? $request->category_id : null,
                'description'    => $request->description,
                'price'          => $request->price,
                'pages'          => $request->pages,
                'age_range'      => $request->age_range,
                'size'           => $request->size,
                'characters'     => $request->characters,
                'cover_type'     => $request->cover_type,
                'print_type'     => $request->print_type,
                'paper_type'     => $request->paper_type,
                'rating'         => $request->rating ?? $product->rating,
                'reviews_count'  => $request->reviews_count ?? $product->reviews_count,
                'is_bestseller'  => $request->boolean('is_bestseller'),
                'is_recommended' => $request->boolean('is_recommended'),
                'status'         => $request->boolean('status'),
                'slug'           => Str::slug($request->title),
            ]);

            // Delete images marked for removal
            if ($request->filled('deleted_image_ids')) {
                $ids = json_decode($request->deleted_image_ids, true);
                if (is_array($ids)) {
                    $toDelete = ProductImage::whereIn('id', $ids)
                        ->where('product_id', $product->id)
                        ->get();
                    foreach ($toDelete as $img) {
                        $this->deleteFile($img->image_path);
                        $img->delete();
                    }
                }
            }

            // Replace primary image if a new one is provided
            if ($request->hasFile('image') || $request->filled('image_url')) {
                $newPath = $request->hasFile('image')
                    ? $this->saveUploadedFile($request->file('image'), 'img')
                    : $request->image_url;

                $existing = $product->images()->where('is_main', true)->first();
                if ($existing) {
                    $this->deleteFile($existing->image_path);
                    $existing->update(['image_path' => $newPath]);
                } else {
                    $product->images()->create([
                        'image_path' => $newPath,
                        'is_main'    => true,
                        'sort_order' => 0,
                    ]);
                }
            }

            // Append new gallery images
            $maxOrder = $product->images()->max('sort_order') ?? 0;
            $sortOrder = $maxOrder + 1;

            if ($request->filled('gallery_urls')) {
                foreach (array_map('trim', explode(',', $request->gallery_urls)) as $url) {
                    if ($url) {
                        $product->images()->create([
                            'image_path' => $url,
                            'is_main'    => false,
                            'sort_order' => $sortOrder++,
                        ]);
                    }
                }
            }

            if ($request->hasFile('gallery_files')) {
                foreach ($request->file('gallery_files') as $file) {
                    $product->images()->create([
                        'image_path' => $this->saveUploadedFile($file, 'gal'),
                        'is_main'    => false,
                        'sort_order' => $sortOrder++,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(string $id)
    {
        $product = Product::with('images')->findOrFail($id);

        foreach ($product->images as $img) {
            $this->deleteFile($img->image_path);
        }

        $product->delete(); // cascade deletes product_images rows

        return redirect()->route('admin.products.index')->with('success', 'Book deleted successfully.');
    }

    public function toggleStatus(Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->status = !$product->status;
            $product->save();

            return response()->json([
                'success' => true,
                'status'  => $product->status,
                'message' => 'Product status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ── Private helpers ────────────────────────────────────────────────────

    private function saveUploadedFile($file, string $prefix): string
    {
        $filename = $prefix . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $dest = public_path('storage/products');

        // Ensure directory exists
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $file->move($dest, $filename);

        // Store with leading slash — consistent with existing data
        return '/storage/products/' . $filename;
    }

    private function deleteFile(string $path): void
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return; // external URL — nothing to delete
        }
        // Support both 'storage/products/...' and '/storage/products/...'
        $fullPath = public_path(ltrim($path, '/'));
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}
