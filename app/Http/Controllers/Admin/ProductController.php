<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'subcategory', 'primaryImage', 'images', 'specialSections', 'categoryImages.category', 'categoryImages.subcategory'])
            ->orderBy('created_at', 'desc')
            ->get();

        $categories   = Category::with('subcategories')->orderBy('name')->get();
        $subcategories = Subcategory::orderBy('name')->get();

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
            'category_images'                   => 'nullable|array',
            'category_images.*.category_id'    => 'nullable|exists:categories,id',
            'category_images.*.subcategory_id' => 'nullable|exists:subcategories,id',
            'category_images.*.image'          => 'nullable|image|max:4096',
            'category_images.*.option_type'    => 'nullable|in:box,drop,color',
            'category_images.*.option_value'   => 'nullable|string|max:20',
            'is_bestseller'      => 'nullable|boolean',
            'is_recommended'     => 'nullable|boolean',
            'status'             => 'nullable|boolean',
            'subcategory_id'     => 'nullable|exists:subcategories,id',
            'special_sections'             => 'nullable|array',
            'special_sections.*.title'     => 'nullable|string|max:255',
            'special_sections.*.subtitle'  => 'nullable|string|max:255',
            'special_sections.*.description' => 'nullable|string',
            'special_sections.*.image'     => 'nullable|image|max:4096',
            'name_text'        => 'nullable|string|max:255',
            'name_font_family' => 'nullable|string|max:100',
            'name_top'         => 'nullable|string|max:20',
            'name_color'       => 'nullable|string|max:20',
            'name_font_size'   => 'nullable|string|max:20',
            'name_right'       => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::create([
                'title'          => $request->title,
                'category_id'    => $request->filled('category_id') ? $request->category_id : null,
                'subcategory_id' => $request->filled('subcategory_id') ? $request->subcategory_id : null,
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
                'name_text'        => $request->name_text,
                'name_font_family' => $request->name_font_family ?: 'PetitCochon',
                'name_top'         => $request->name_top ?: '2%',
                'name_color'       => $request->name_color ?: '#e591ae',
                'name_font_size'   => $request->name_font_size ?: '88px',
                'name_right'       => $request->name_right ?: '50%',
            ]);

            // Save special sections
            if ($request->has('special_sections') && is_array($request->special_sections)) {
                $sort = 0;
                foreach ($request->special_sections as $index => $section) {
                    if (empty($section['title']) && empty($section['subtitle']) && empty($section['description']) && !$request->hasFile("special_sections.$index.image")) {
                        continue;
                    }

                    $imagePath = null;
                    if ($request->hasFile("special_sections.$index.image")) {
                        $imagePath = $this->saveUploadedFile($request->file("special_sections.$index.image"), 'special');
                    }

                    $product->specialSections()->create([
                        'title' => $section['title'] ?? null,
                        'subtitle' => $section['subtitle'] ?? null,
                        'description' => $section['description'] ?? null,
                        'image' => $imagePath,
                        'sort_order' => $sort++,
                    ]);
                }
            }

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

            // Save category images
            if ($request->has('category_images') && is_array($request->category_images)) {
                $sort = 0;
                foreach ($request->category_images as $index => $catImg) {
                    if (empty($catImg['category_id']) || !$request->hasFile("category_images.$index.image")) {
                        continue;
                    }
                    $imagePath = $this->saveUploadedFile($request->file("category_images.$index.image"), 'cat');
                    $product->categoryImages()->create([
                        'category_id'    => $catImg['category_id'],
                        'subcategory_id' => !empty($catImg['subcategory_id']) ? $catImg['subcategory_id'] : null,
                        'image_path'     => $imagePath,
                        'sort_order'     => $sort++,
                        'option_type'    => $catImg['option_type'] ?? 'box',
                        'option_value'   => ($catImg['option_type'] ?? 'box') === 'color' ? ($catImg['option_value'] ?? null) : null,
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
            'category_images'                   => 'nullable|array',
            'category_images.*.id'             => 'nullable|exists:product_category_images,id',
            'category_images.*.category_id'    => 'nullable|exists:categories,id',
            'category_images.*.subcategory_id' => 'nullable|exists:subcategories,id',
            'category_images.*.image'          => 'nullable|image|max:4096',
            'category_images.*.existing_image' => 'nullable|string',
            'category_images.*.option_type'    => 'nullable|in:box,drop,color',
            'category_images.*.option_value'   => 'nullable|string|max:20',
            'is_bestseller'      => 'nullable|boolean',
            'is_recommended'     => 'nullable|boolean',
            'status'             => 'nullable|boolean',
            'subcategory_id'     => 'nullable|exists:subcategories,id',
            'special_sections'             => 'nullable|array',
            'special_sections.*.id'        => 'nullable|exists:product_special_sections,id',
            'special_sections.*.title'     => 'nullable|string|max:255',
            'special_sections.*.subtitle'  => 'nullable|string|max:255',
            'special_sections.*.description' => 'nullable|string',
            'special_sections.*.image'     => 'nullable|image|max:4096',
            'special_sections.*.existing_image' => 'nullable|string',
            'name_text'        => 'nullable|string|max:255',
            'name_font_family' => 'nullable|string|max:100',
            'name_top'         => 'nullable|string|max:20',
            'name_color'       => 'nullable|string|max:20',
            'name_font_size'   => 'nullable|string|max:20',
            'name_right'       => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, $product) {
            $product->update([
                'title'          => $request->title,
                'category_id'    => $request->filled('category_id') ? $request->category_id : null,
                'subcategory_id' => $request->filled('subcategory_id') ? $request->subcategory_id : null,
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
                'name_text'        => $request->name_text,
                'name_font_family' => $request->name_font_family ?: $product->name_font_family ?: 'PetitCochon',
                'name_top'         => $request->name_top ?: $product->name_top ?: '2%',
                'name_color'       => $request->name_color ?: $product->name_color ?: '#e591ae',
                'name_font_size'   => $request->name_font_size ?: $product->name_font_size ?: '88px',
                'name_right'       => $request->name_right ?: $product->name_right ?: '50%',
            ]);

            // Save special sections
            if ($request->has('special_sections') && is_array($request->special_sections)) {
                $submittedIds = [];
                $sort = 0;

                foreach ($request->special_sections as $index => $section) {
                    $id = $section['id'] ?? null;

                    if (empty($section['title']) && empty($section['subtitle']) && empty($section['description']) && !$request->hasFile("special_sections.$index.image") && empty($section['existing_image'])) {
                        continue;
                    }

                    $imagePath = $section['existing_image'] ?? null;
                    if ($request->hasFile("special_sections.$index.image")) {
                        $imagePath = $this->saveUploadedFile($request->file("special_sections.$index.image"), 'special');
                    }

                    if ($id) {
                        $specialSection = $product->specialSections()->find($id);
                        if ($specialSection) {
                            $specialSection->update([
                                'title' => $section['title'] ?? null,
                                'subtitle' => $section['subtitle'] ?? null,
                                'description' => $section['description'] ?? null,
                                'image' => $imagePath,
                                'sort_order' => $sort++,
                            ]);
                            $submittedIds[] = $specialSection->id;
                        }
                    } else {
                        $newSection = $product->specialSections()->create([
                            'title' => $section['title'] ?? null,
                            'subtitle' => $section['subtitle'] ?? null,
                            'description' => $section['description'] ?? null,
                            'image' => $imagePath,
                            'sort_order' => $sort++,
                        ]);
                        $submittedIds[] = $newSection->id;
                    }
                }

                // Delete removed sections
                $toDelete = $product->specialSections()->whereNotIn('id', $submittedIds)->get();
                foreach ($toDelete as $delSec) {
                    if ($delSec->image) {
                        $this->deleteFile($delSec->image);
                    }
                    $delSec->delete();
                }
            } else {
                $toDelete = $product->specialSections;
                foreach ($toDelete as $delSec) {
                    if ($delSec->image) {
                        $this->deleteFile($delSec->image);
                    }
                    $delSec->delete();
                }
            }

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

            // Save category images
            if ($request->has('category_images') && is_array($request->category_images)) {
                $submittedCatImgIds = [];
                $sort = 0;

                foreach ($request->category_images as $index => $catImg) {
                    $id = $catImg['id'] ?? null;

                    if (empty($catImg['category_id']) && !$request->hasFile("category_images.$index.image") && empty($catImg['existing_image'])) {
                        continue;
                    }

                    $imagePath = $catImg['existing_image'] ?? null;
                    if ($request->hasFile("category_images.$index.image")) {
                        $imagePath = $this->saveUploadedFile($request->file("category_images.$index.image"), 'cat');
                    }

                    if ($id) {
                        $existingCatImg = $product->categoryImages()->find($id);
                        if ($existingCatImg) {
                            $existingCatImg->update([
                                'category_id'    => !empty($catImg['category_id']) ? $catImg['category_id'] : null,
                                'subcategory_id' => !empty($catImg['subcategory_id']) ? $catImg['subcategory_id'] : null,
                                'image_path'     => $imagePath,
                                'sort_order'     => $sort++,
                                'option_type'    => $catImg['option_type'] ?? 'box',
                                'option_value'   => ($catImg['option_type'] ?? 'box') === 'color' ? ($catImg['option_value'] ?? null) : null,
                            ]);
                            $submittedCatImgIds[] = $existingCatImg->id;
                        }
                    } else {
                        if ($catImg['category_id'] && $imagePath) {
                            $newCatImg = $product->categoryImages()->create([
                                'category_id'    => $catImg['category_id'],
                                'subcategory_id' => !empty($catImg['subcategory_id']) ? $catImg['subcategory_id'] : null,
                                'image_path'     => $imagePath,
                                'sort_order'     => $sort++,
                                'option_type'    => $catImg['option_type'] ?? 'box',
                                'option_value'   => ($catImg['option_type'] ?? 'box') === 'color' ? ($catImg['option_value'] ?? null) : null,
                            ]);
                            $submittedCatImgIds[] = $newCatImg->id;
                        }
                    }
                }

                // Delete removed category images
                $toDeleteCatImgs = $product->categoryImages()->whereNotIn('id', $submittedCatImgIds)->get();
                foreach ($toDeleteCatImgs as $delImg) {
                    if ($delImg->image_path) {
                        $this->deleteFile($delImg->image_path);
                    }
                    $delImg->delete();
                }
            } else {
                $toDeleteCatImgs = $product->categoryImages;
                foreach ($toDeleteCatImgs as $delImg) {
                    if ($delImg->image_path) {
                        $this->deleteFile($delImg->image_path);
                    }
                    $delImg->delete();
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

        foreach ($product->specialSections as $sec) {
            if ($sec->image) {
                $this->deleteFile($sec->image);
            }
        }

        foreach ($product->categoryImages as $catImg) {
            if ($catImg->image_path) {
                $this->deleteFile($catImg->image_path);
            }
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
        $filename = $prefix . '_' . uniqid('', true) . '_' . \Illuminate\Support\Str::random(5) . '.' . $file->getClientOriginalExtension();
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
