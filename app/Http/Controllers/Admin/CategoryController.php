<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $parentCategories = Category::parents()->with('subcategories')->orderBy('name')->get();
        $specialCategories = Category::special()->orderBy('name')->get();

        return view('admin.categories.index', compact('parentCategories', 'specialCategories'));
    }

    public function store(Request $request)
    {
        $parent_id = $request->input('parent_id') ?: null;

        // ── Bulk subcategory creation (names[] array) ──────────────────────
        if ($parent_id) {
            $request->validate([
                'parent_id'   => 'required|exists:categories,id',
                'names'       => 'required|array|min:1',
                'names.*'     => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_special'  => 'boolean',
                'status'      => 'boolean',
            ]);

            $count = 0;
            foreach ($request->names as $name) {
                $name = trim($name);
                if ($name === '') continue;

                Category::create([
                    'parent_id'   => $parent_id,
                    'name'        => $name,
                    'slug'        => Str::slug($name),
                    'description' => $request->description,
                    'is_special'  => $request->boolean('is_special'),
                    'status'      => $request->boolean('status'),
                ]);
                $count++;
            }

            return redirect()
                ->route('admin.categories.index', ['tab' => 'sub'])
                ->with('success', "$count subcategori(es) created successfully.");
        }

        // ── Single parent category creation ───────────────────────────────
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_special'  => 'boolean',
            'status'      => 'boolean',
        ]);

        Category::create([
            'parent_id'   => null,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'is_special'  => $request->boolean('is_special'),
            'status'      => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Parent category created successfully.');
    }

    public function update(Request $request, string $id)
    {
        $category   = Category::findOrFail($id);
        $activeTab  = $request->input('active_tab', 'parent');

        $request->validate([
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|exists:categories,id|not_in:' . $category->id,
            'description' => 'nullable|string',
            'is_special'  => 'boolean',
            'status'      => 'boolean',
        ]);

        $category->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'description' => $request->description,
            'parent_id'   => $request->input('parent_id') ?: null,
            'is_special'  => $request->boolean('is_special'),
            'status'      => $request->boolean('status'),
        ]);

        $params = $activeTab === 'sub' ? ['tab' => 'sub'] : [];

        return redirect()
            ->route('admin.categories.index', $params)
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $category      = Category::findOrFail($id);
        $isSubcategory = ! is_null($category->parent_id);
        $category->delete();

        $params = $isSubcategory ? ['tab' => 'sub'] : [];

        return redirect()
            ->route('admin.categories.index', $params)
            ->with('success', 'Category deleted successfully.');
    }

    public function toggleStatus(Request $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->status = !$category->status;
            $category->save();

            return response()->json([
                'success' => true,
                'status' => $category->status,
                'message' => 'Category status updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }
}