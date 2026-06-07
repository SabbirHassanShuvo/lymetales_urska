<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $parentCategories  = Category::with(['subcategories.children'])->orderBy('name')->get();
        $specialCategories = Category::special()->orderBy('name')->get();
        // All level-1 subcategories for the "Add Sub-subcategory" parent selector
        $allSubcategories  = Subcategory::whereNull('parent_id')->with('category')->orderBy('name')->get();

        return view('admin.categories.index', compact('parentCategories', 'specialCategories', 'allSubcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_special'  => 'nullable|in:0,1', 
            'status'      => 'nullable|in:0,1',
        ]);

        Category::create([
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

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_special'  => 'nullable|in:0,1',
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
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $category      = Category::findOrFail($id);
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
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