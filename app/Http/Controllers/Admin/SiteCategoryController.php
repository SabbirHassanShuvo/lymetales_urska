<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteCategory;
use App\Models\SiteSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteCategoryController extends Controller
{
    public function index()
    {
        $categories = SiteCategory::with('subcategories')->orderBy('name')->get();

        return view('admin.site-categories.index', compact('categories'));
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
        ]);

        return redirect()
            ->route('admin.site-categories.index')
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
        ]);

        return redirect()
            ->route('admin.site-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $category = SiteCategory::findOrFail($id);
        $category->delete();

        return redirect()
            ->route('admin.site-categories.index')
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

    // ── Subcategory CRUD ─────────────────────────────────────────────────────

    public function storeSubcategory(Request $request)
    {
        return redirect()
            ->route('admin.site-categories.index', ['tab' => 'sub'])
            ->with('error', 'Subcategories are fixed and cannot be modified.');
    }

    public function updateSubcategory(Request $request, string $id)
    {
        return redirect()
            ->route('admin.site-categories.index', ['tab' => 'sub'])
            ->with('error', 'Subcategories are fixed and cannot be modified.');
    }

    public function destroySubcategory(string $id)
    {
        return redirect()
            ->route('admin.site-categories.index', ['tab' => 'sub'])
            ->with('error', 'Subcategories are fixed and cannot be modified.');
    }

    public function toggleSubcategoryStatus(Request $request, string $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'Subcategories are fixed and status cannot be modified.',
        ], 403);
    }
}
