<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->input('lang', 'SL');
        $parentCategories  = Category::with(['subcategories.children'])->where('language_type', $lang)->orderBy('name')->get();
        $specialCategories = Category::special()->where('language_type', $lang)->orderBy('name')->get();
        // All level-1 subcategories for the "Add Sub-subcategory" parent selector
        $categoryIds = Category::where('language_type', $lang)->pluck('id');
        $allSubcategories  = Subcategory::whereNull('parent_id')->whereIn('category_id', $categoryIds)->with('category')->orderBy('name')->get();

        return view('admin.categories.index', compact('parentCategories', 'specialCategories', 'allSubcategories', 'lang'));
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
            'language_type' => $request->input('language_type', 'SL'),
        ]);

        return redirect()
            ->route('admin.categories.index', ['lang' => $request->input('language_type', 'SL')])
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
            'language_type' => $request->input('language_type', $category->language_type),
        ]);

        return redirect()
            ->route('admin.categories.index', ['lang' => $request->input('language_type', $category->language_type)])
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $lang = $category->language_type ?? 'SL';
        $category->delete();

        return redirect()
            ->route('admin.categories.index', ['lang' => $lang])
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