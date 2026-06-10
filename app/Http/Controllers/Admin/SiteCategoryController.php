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
            'status'      => 'nullable|in:0,1',
        ]);

        SiteCategory::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
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
            'status'      => 'nullable|in:0,1',
        ]);

        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'status'      => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.site-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        SiteCategory::findOrFail($id)->delete();

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
        $request->validate([
            'site_category_id' => 'required|exists:site_categories,id',
            'names'            => 'required|array|min:1',
            'names.*'          => 'required|string|max:255',
            'description'      => 'nullable|string',
            'status'           => 'nullable|in:0,1',
        ]);

        $count = 0;
        foreach ($request->names as $name) {
            $name = trim($name);
            if ($name === '') continue;

            SiteSubcategory::create([
                'site_category_id' => $request->site_category_id,
                'name'             => $name,
                'description'      => $request->description,
                'status'           => $request->boolean('status'),
            ]);
            $count++;
        }

        return redirect()
            ->route('admin.site-categories.index', ['tab' => 'sub'])
            ->with('success', "$count subcategory(ies) created successfully.");
    }

    public function updateSubcategory(Request $request, string $id)
    {
        $subcategory = SiteSubcategory::findOrFail($id);

        $request->validate([
            'site_category_id' => 'required|exists:site_categories,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'status'           => 'nullable|in:0,1',
        ]);

        $subcategory->update([
            'site_category_id' => $request->site_category_id,
            'name'             => $request->name,
            'description'      => $request->description,
            'status'           => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.site-categories.index', ['tab' => 'sub'])
            ->with('success', 'Subcategory updated successfully.');
    }

    public function destroySubcategory(string $id)
    {
        SiteSubcategory::findOrFail($id)->delete();

        return redirect()
            ->route('admin.site-categories.index', ['tab' => 'sub'])
            ->with('success', 'Subcategory deleted successfully.');
    }

    public function toggleSubcategoryStatus(Request $request, string $id)
    {
        try {
            $subcategory         = SiteSubcategory::findOrFail($id);
            $subcategory->status = !$subcategory->status;
            $subcategory->save();

            return response()->json([
                'success' => true,
                'status'  => $subcategory->status,
                'message' => 'Subcategory status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
