<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteCategory;
use App\Models\SiteSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteCategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->input('lang', 'SL');
        $categories = SiteCategory::where('language_type', $lang)->orderBy('name')->get();

        return view('admin.site-categories.index', compact('categories', 'lang'));
    }

    // ── Category CRUD ────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_special'  => 'boolean',
            'status'      => 'nullable|in:0,1',
        ]);

        SiteCategory::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_special'  => $request->boolean('is_special'),
            'status'      => $request->boolean('status'),
            'language_type' => $request->input('language_type', 'SL'),
        ]);

        return redirect()
            ->route('admin.site-categories.index', ['lang' => $request->input('language_type', 'SL')])
            ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, string $id)
    {
        $category = SiteCategory::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_special'  => 'boolean',
            'status'      => 'nullable|in:0,1',
        ]);

        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_special'  => $request->boolean('is_special'),
            'status'      => $request->boolean('status'),
            'language_type' => $request->input('language_type', $category->language_type),
        ]);

        return redirect()
            ->route('admin.site-categories.index', ['lang' => $request->input('language_type', $category->language_type)])
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $category = SiteCategory::findOrFail($id);
        $lang = $category->language_type ?? 'SL';
        $category->delete();

        return redirect()
            ->route('admin.site-categories.index', ['lang' => $lang])
            ->with('success', 'Category deleted successfully.');
    }

    public function toggleStatus(Request $request, string $id)
    {
        try {
            $category         = SiteCategory::findOrFail($id);
            $category->status = !$category->status;
            $category->save();

            return response()->json([
                'success' => true,
                'status'  => $category->status,
                'message' => 'Category status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Subcategory CRUD removed
}
