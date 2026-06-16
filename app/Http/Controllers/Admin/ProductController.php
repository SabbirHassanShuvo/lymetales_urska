<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\SiteCategory;
use App\Models\ProductCustomizationStep;
use App\Models\ProductCustomizationOption;
use App\Models\ProductCustomizationSubstep;
use App\Models\ProductCustomizationSuboption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'subcategory', 'siteCategory', 'siteSubcategory', 'primaryImage', 'images', 'bookImages', 'specialSections', 'categoryImages.category', 'categoryImages.subcategory', 'customizationSteps.options.subSteps.subOptions'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Totals for header cards (all products, not just current page)
        $totalCount       = Product::count();
        $bestsellersCount = Product::where('is_bestseller', true)->count();
        $recommendedCount = Product::where('is_recommended', true)->count();
        $activeCount      = Product::where('status', true)->count();

        $categories    = Category::with(['subcategories.children'])->orderBy('name')->get();
        $subcategories = Subcategory::with('children')->whereNull('parent_id')->orderBy('name')->get();
        $siteCategories = SiteCategory::with('subcategories')->where('status', true)->orderBy('name')->get();

        return view('admin.products.index', compact(
            'products', 'categories', 'subcategories', 'siteCategories',
            'totalCount', 'bestsellersCount', 'recommendedCount', 'activeCount'
        ));
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
            'image'              => 'nullable|image',
            'image_url'          => 'nullable|string',
            'gallery_files.*'    => 'nullable|image',
            'gallery_urls'       => 'nullable|string',
            'category_images'                   => 'nullable|array',
            'category_images.*.category_id'    => 'nullable|exists:categories,id',
            'category_images.*.subcategory_id' => 'nullable|exists:subcategories,id',
            'category_images.*.image'          => 'nullable|image',
            'category_images.*.option_type'    => 'nullable|in:box,drop,color',
            'category_images.*.option_value'   => 'nullable|string',
            'is_bestseller'      => 'nullable|boolean',
            'is_recommended'     => 'nullable|boolean',
            'status'             => 'nullable|boolean',
            'type'               => 'nullable|string|max:255',
            'subcategory_id'     => 'nullable|exists:subcategories,id',
            'domain'             => 'nullable|in:domain1,domain2',
            'featured_image_id'  => 'nullable|string',
            'site_category_id'    => 'nullable|exists:site_categories,id',
            'site_subcategory_id' => 'nullable|exists:site_subcategories,id',
            'special_sections.*.subtitle'  => 'nullable|string|max:255',
            'special_sections.*.description' => 'nullable|string',
            'special_sections.*.image'     => 'nullable|image',
            'name_text'        => 'nullable|string|max:255',
            'name_font_family' => 'nullable|string|max:100',
            'name_top'         => 'nullable|string|max:20',
            'name_color'       => 'nullable|string|max:20',
            'name_font_size'   => 'nullable|string|max:20',
            'name_right'       => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            $product = Product::create([
                'title'               => $request->title,
                'category_id'         => $request->filled('category_id') ? $request->category_id : null,
                'subcategory_id'      => $request->filled('subcategory_id') ? $request->subcategory_id : null,
                'site_category_id'    => $request->filled('site_category_id') ? $request->site_category_id : null,
                'site_subcategory_id' => $request->filled('site_subcategory_id') ? $request->site_subcategory_id : null,
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
                'type'           => $request->type,
                'slug'           => Str::slug($request->title),
                'domain'         => $request->filled('domain') ? $request->domain : null,
                'featured_image_id' => is_numeric($request->featured_image_id) ? $request->featured_image_id : null,
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

            // Save book images
            if ($request->has('book_images') && is_array($request->book_images)) {
                $sort = 0;
                foreach ($request->book_images as $index => $imgData) {
                    if (!$request->hasFile("book_images.$index.image")) {
                        continue;
                    }
                    $imagePath = $this->saveUploadedFile($request->file("book_images.$index.image"), 'book');
                    $product->bookImages()->create([
                        'image_path' => $imagePath,
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
                foreach ($request->file('gallery_files') as $index => $file) {
                    $img = $product->images()->create([
                        'image_path' => $this->saveUploadedFile($file, 'gal'),
                        'is_main'    => false,
                        'sort_order' => $sortOrder++,
                    ]);
                    
                    if ($request->featured_image_id === 'new_' . $index) {
                        $product->update(['featured_image_id' => $img->id]);
                    }
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

            // ── Save customization steps ───────────────────────────────────
            if ($request->has('customization_steps') && is_array($request->customization_steps)) {
                foreach ($request->customization_steps as $si => $stepData) {
                    if (empty($stepData['name'])) continue;

                    $stepType = in_array($stepData['type'] ?? '', ['dropdown','box','color']) ? $stepData['type'] : 'dropdown';
                    $step = $product->customizationSteps()->create([
                        'name'        => $stepData['name'],
                        'type'        => $stepType,
                        'color_value' => $stepType === 'color' ? ($stepData['color_value'] ?? null) : null,
                        'sort_order'  => $si,
                    ]);

                    foreach (($stepData['options'] ?? []) as $oi => $optData) {
                        if (empty($optData['name'])) continue;

                        $imagePath = null;
                        if ($request->hasFile("customization_steps.{$si}.options.{$oi}.image")) {
                            $imagePath = $this->saveUploadedFile(
                                $request->file("customization_steps.{$si}.options.{$oi}.image"), 'copt'
                            );
                        } elseif (!empty($optData['image_url'])) {
                            $imagePath = $optData['image_url'];
                        }

                        $optType = in_array($optData['type'] ?? '', ['dropdown','box','color']) ? $optData['type'] : 'dropdown';
                        $option = $step->options()->create([
                            'name'        => $optData['name'],
                            'type'        => $optType,
                            'color_value' => $optType === 'color' ? ($optData['color_value'] ?? null) : null,
                            'image_path'  => $imagePath,
                            'is_default'  => !empty($optData['is_default']),
                            'sort_order'  => $oi,
                        ]);

                        foreach (($optData['sub_steps'] ?? []) as $ssi => $ssData) {
                            if (empty($ssData['name'])) continue;

                            $ssType = in_array($ssData['type'] ?? '', ['dropdown','box','color']) ? $ssData['type'] : 'dropdown';
                            $subStep = $option->subSteps()->create([
                                'name'        => $ssData['name'],
                                'type'        => $ssType,
                                'color_value' => $ssType === 'color' ? ($ssData['color_value'] ?? null) : null,
                                'sort_order'  => $ssi,
                            ]);

                            foreach (($ssData['sub_options'] ?? []) as $soi => $soData) {
                                // Allow empty name for colour-type sub-options (name = hex value)
                                // Only skip if truly nothing useful was submitted
                                if (empty($soData['name']) && empty($soData['color_value'])) continue;

                                $soImagePath = null;
                                if ($request->hasFile("customization_steps.{$si}.options.{$oi}.sub_steps.{$ssi}.sub_options.{$soi}.image")) {
                                    $soImagePath = $this->saveUploadedFile(
                                        $request->file("customization_steps.{$si}.options.{$oi}.sub_steps.{$ssi}.sub_options.{$soi}.image"), 'csub'
                                    );
                                } elseif (!empty($soData['image_url'])) {
                                    $soImagePath = $soData['image_url'];
                                }

                                $soType = in_array($soData['type'] ?? '', ['dropdown','box','color']) ? $soData['type'] : 'dropdown';
                                // For colour-type sub-options the name is stored as the hex value
                                $soName = !empty($soData['name']) ? $soData['name'] : ($soData['color_value'] ?? null);
                                $subStep->subOptions()->create([
                                    'name'        => $soName,
                                    'type'        => $soType,
                                    'color_value' => $soType === 'color' ? ($soData['color_value'] ?? null) : null,
                                    'image_path'  => $soImagePath,
                                    'is_default'  => !empty($soData['is_default']),
                                    'sort_order'  => $soi,
                                ]);
                            }
                        }
                    }
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Book created successfully.');
    }

    public function update(Request $request, string $id)
    {
        $product = Product::with('images', 'bookImages')->findOrFail($id);

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
            'image'              => 'nullable|image',
            'image_url'          => 'nullable|string',
            'gallery_files.*'    => 'nullable|image',
            'gallery_urls'       => 'nullable|string',
            'category_images'                   => 'nullable|array',
            'category_images.*.id'             => 'nullable|exists:product_category_images,id',
            'category_images.*.category_id'    => 'nullable|exists:categories,id',
            'category_images.*.subcategory_id' => 'nullable|exists:subcategories,id',
            'category_images.*.image'          => 'nullable|image',
            'category_images.*.existing_image' => 'nullable|string',
            'category_images.*.option_type'    => 'nullable|in:box,drop,color',
            'category_images.*.option_value'   => 'nullable|string',
            'is_bestseller'      => 'nullable|boolean',
            'is_recommended'     => 'nullable|boolean',
            'status'             => 'nullable|boolean',
            'type'               => 'nullable|string|max:255',
            'subcategory_id'     => 'nullable|exists:subcategories,id',
            'domain'             => 'nullable|in:domain1,domain2',
            'featured_image_id'  => 'nullable|string',
            'site_category_id'    => 'nullable|exists:site_categories,id',
            'site_subcategory_id' => 'nullable|exists:site_subcategories,id',
            'special_sections'             => 'nullable|array',
            'special_sections.*.id'        => 'nullable|exists:product_special_sections,id',
            'special_sections.*.title'     => 'nullable|string|max:255',
            'special_sections.*.subtitle'  => 'nullable|string|max:255',
            'special_sections.*.description' => 'nullable|string',
            'special_sections.*.image'     => 'nullable|image',
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
                'title'               => $request->title,
                'category_id'         => $request->filled('category_id') ? $request->category_id : null,
                'subcategory_id'      => $request->filled('subcategory_id') ? $request->subcategory_id : null,
                'site_category_id'    => $request->filled('site_category_id') ? $request->site_category_id : null,
                'site_subcategory_id' => $request->filled('site_subcategory_id') ? $request->site_subcategory_id : null,
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
                'type'           => $request->type,
                'slug'           => Str::slug($request->title),
                'domain'         => $request->filled('domain') ? $request->domain : null,
                'featured_image_id' => is_numeric($request->featured_image_id) ? $request->featured_image_id : null,
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

            // Save book images
            if ($request->has('book_images') && is_array($request->book_images)) {
                $submittedImgIds = [];
                $sort = 0;

                foreach ($request->book_images as $index => $imgData) {
                    $id = $imgData['id'] ?? null;

                    if (!$request->hasFile("book_images.$index.image") && empty($imgData['existing_image'])) {
                        continue;
                    }

                    $imagePath = $imgData['existing_image'] ?? null;
                    if ($request->hasFile("book_images.$index.image")) {
                        $imagePath = $this->saveUploadedFile($request->file("book_images.$index.image"), 'book');
                    }

                    if ($id) {
                        $existingBookImg = $product->bookImages()->find($id);
                        if ($existingBookImg) {
                            $existingBookImg->update([
                                'image_path' => $imagePath,
                                'sort_order' => $sort++,
                            ]);
                            $submittedImgIds[] = $existingBookImg->id;
                        }
                    } else {
                        if ($imagePath) {
                            $newBookImg = $product->bookImages()->create([
                                'image_path' => $imagePath,
                                'sort_order' => $sort++,
                            ]);
                            $submittedImgIds[] = $newBookImg->id;
                        }
                    }
                }

                // Delete removed book images
                $toDeleteBookImgs = $product->bookImages()->whereNotIn('id', $submittedImgIds)->get();
                foreach ($toDeleteBookImgs as $delImg) {
                    if ($delImg->image_path) {
                        $this->deleteFile($delImg->image_path);
                    }
                    $delImg->delete();
                }
            } else {
                $toDeleteBookImgs = $product->bookImages;
                foreach ($toDeleteBookImgs as $delImg) {
                    if ($delImg->image_path) {
                        $this->deleteFile($delImg->image_path);
                    }
                    $delImg->delete();
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
                foreach ($request->file('gallery_files') as $index => $file) {
                    $img = $product->images()->create([
                        'image_path' => $this->saveUploadedFile($file, 'gal'),
                        'is_main'    => false,
                        'sort_order' => $sortOrder++,
                    ]);
                    
                    if ($request->featured_image_id === 'new_' . $index) {
                        $product->update(['featured_image_id' => $img->id]);
                    }
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

            // ── Update customization steps ─────────────────────────────────
            if ($request->has('customization_steps')) {
                // Collect all existing image paths before deleting so we can
                // preserve any that are reused via existing_image in the request.
                $oldImagePaths = [];
                foreach ($product->customizationSteps()->with('options.subSteps.subOptions')->get() as $oldStep) {
                    foreach ($oldStep->options as $oldOpt) {
                        if ($oldOpt->image_path) $oldImagePaths[] = $oldOpt->image_path;
                        foreach ($oldOpt->subSteps as $oldSS) {
                            foreach ($oldSS->subOptions as $oldSO) {
                                if ($oldSO->image_path) $oldImagePaths[] = $oldSO->image_path;
                            }
                        }
                    }
                    $oldStep->delete(); // cascades options → substeps → suboptions
                }

                // Track which old paths are being reused so they are NOT deleted
                $reusedImagePaths = [];

                if (is_array($request->customization_steps)) {
                    foreach ($request->customization_steps as $si => $stepData) {
                        if (empty($stepData['name'])) continue;

                        $stepType = in_array($stepData['type'] ?? '', ['dropdown','box','color']) ? $stepData['type'] : 'dropdown';
                        $step = $product->customizationSteps()->create([
                            'name'        => $stepData['name'],
                            'type'        => $stepType,
                            'color_value' => $stepType === 'color' ? ($stepData['color_value'] ?? null) : null,
                            'sort_order'  => $si,
                        ]);

                        foreach (($stepData['options'] ?? []) as $oi => $optData) {
                            if (empty($optData['name'])) continue;

                            $imagePath = $optData['existing_image'] ?? null;
                            if (!empty($imagePath)) $reusedImagePaths[] = $imagePath;

                            if ($request->hasFile("customization_steps.{$si}.options.{$oi}.image")) {
                                $imagePath = $this->saveUploadedFile(
                                    $request->file("customization_steps.{$si}.options.{$oi}.image"), 'copt'
                                );
                            } elseif (!empty($optData['image_url'])) {
                                $imagePath = $optData['image_url'];
                            }

                            $optType = in_array($optData['type'] ?? '', ['dropdown','box','color']) ? $optData['type'] : 'dropdown';
                            $option = $step->options()->create([
                                'name'        => $optData['name'],
                                'type'        => $optType,
                                'color_value' => $optType === 'color' ? ($optData['color_value'] ?? null) : null,
                                'image_path'  => $imagePath,
                                'is_default'  => !empty($optData['is_default']),
                                'sort_order'  => $oi,
                            ]);

                            foreach (($optData['sub_steps'] ?? []) as $ssi => $ssData) {
                                if (empty($ssData['name'])) continue;

                                $ssType = in_array($ssData['type'] ?? '', ['dropdown','box','color']) ? $ssData['type'] : 'dropdown';
                                $subStep = $option->subSteps()->create([
                                    'name'        => $ssData['name'],
                                    'type'        => $ssType,
                                    'color_value' => $ssType === 'color' ? ($ssData['color_value'] ?? null) : null,
                                    'sort_order'  => $ssi,
                                ]);

                                foreach (($ssData['sub_options'] ?? []) as $soi => $soData) {
                                    // Allow empty name for colour-type sub-options (name = hex value)
                                    // Only skip if truly nothing useful was submitted
                                    if (empty($soData['name']) && empty($soData['color_value'])) continue;

                                    $soImagePath = $soData['existing_image'] ?? null;
                                    if (!empty($soImagePath)) $reusedImagePaths[] = $soImagePath;

                                    if ($request->hasFile("customization_steps.{$si}.options.{$oi}.sub_steps.{$ssi}.sub_options.{$soi}.image")) {
                                        $soImagePath = $this->saveUploadedFile(
                                            $request->file("customization_steps.{$si}.options.{$oi}.sub_steps.{$ssi}.sub_options.{$soi}.image"), 'csub'
                                        );
                                    } elseif (!empty($soData['image_url'])) {
                                        $soImagePath = $soData['image_url'];
                                    }

                                    $soType = in_array($soData['type'] ?? '', ['dropdown','box','color']) ? $soData['type'] : 'dropdown';
                                    // For colour-type sub-options the name is stored as the hex value
                                    $soName = !empty($soData['name']) ? $soData['name'] : ($soData['color_value'] ?? null);
                                    $subStep->subOptions()->create([
                                        'name'        => $soName,
                                        'type'        => $soType,
                                        'color_value' => $soType === 'color' ? ($soData['color_value'] ?? null) : null,
                                        'image_path'  => $soImagePath,
                                        'is_default'  => !empty($soData['is_default']),
                                        'sort_order'  => $soi,
                                    ]);
                                }
                            }
                        }
                    }
                }

                // Delete only image files that were NOT reused in the new structure
                foreach ($oldImagePaths as $oldPath) {
                    if (!in_array($oldPath, $reusedImagePaths)) {
                        $this->deleteFile($oldPath);
                    }
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Book updated successfully.');
    }

    /**
     * GET /admin/products/{product}/customization
     * Returns existing customization JSON for edit modal pre-fill
     */
    public function getCustomization(string $id)
    {
        $product = Product::with('customizationSteps.options.subSteps.subOptions')->findOrFail($id);

        $data = $product->customizationSteps->map(fn ($step) => [
            'id'          => $step->id,
            'name'        => $step->name,
            'type'        => $step->type ?? 'dropdown',
            'color_value' => $step->color_value,
            'sort_order'  => $step->sort_order,
            'options'     => $step->options->map(fn ($opt) => [
                'id'          => $opt->id,
                'name'        => $opt->name,
                'type'        => $opt->type ?? 'dropdown',
                'color_value' => $opt->color_value,
                'image_path'  => $opt->image_path,
                'image_url'   => $opt->image_path ? (
                    str_starts_with($opt->image_path, 'http') ? $opt->image_path : asset(ltrim($opt->image_path, '/'))
                ) : null,
                'is_default'  => $opt->is_default,
                'sort_order'  => $opt->sort_order,
                'sub_steps'   => $opt->subSteps->map(fn ($ss) => [
                    'id'          => $ss->id,
                    'name'        => $ss->name,
                    'type'        => $ss->type ?? 'dropdown',
                    'color_value' => $ss->color_value,
                    'sort_order'  => $ss->sort_order,
                    'sub_options' => $ss->subOptions->map(fn ($so) => [
                        'id'          => $so->id,
                        'name'        => $so->name,
                        'type'        => $so->type ?? 'dropdown',
                        'color_value' => $so->color_value,
                        'image_path'  => $so->image_path,
                        'image_url'   => $so->image_path ? (
                            str_starts_with($so->image_path, 'http') ? $so->image_path : asset(ltrim($so->image_path, '/'))
                        ) : null,
                        'is_default'  => $so->is_default,
                        'sort_order'  => $so->sort_order,
                    ])->values(),
                ])->values(),
            ])->values(),
        ])->values();

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function destroy(string $id)    {
        $product = Product::with('images')->findOrFail($id);

        foreach ($product->images as $img) {
            $this->deleteFile($img->image_path);
        }

        foreach ($product->specialSections as $sec) {
            if ($sec->image) {
                $this->deleteFile($sec->image);
            }
        }

        foreach ($product->bookImages as $bookImg) {
            if ($bookImg->image_path) {
                $this->deleteFile($bookImg->image_path);
            }
        }

        foreach ($product->categoryImages as $catImg) {
            if ($catImg->image_path) {
                $this->deleteFile($catImg->image_path);
            }
        }

        // Customization image cleanup
        foreach ($product->customizationSteps()->with('options.subSteps.subOptions')->get() as $step) {
            foreach ($step->options as $opt) {
                if ($opt->image_path) $this->deleteFile($opt->image_path);
                foreach ($opt->subSteps as $ss) {
                    foreach ($ss->subOptions as $so) {
                        if ($so->image_path) $this->deleteFile($so->image_path);
                    }
                }
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
