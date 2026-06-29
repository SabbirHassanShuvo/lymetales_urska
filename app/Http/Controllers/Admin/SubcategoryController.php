<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'parent_category_id' => 'required|exists:categories,id',
            'parent_id'          => 'nullable|exists:subcategories,id',
            'names'              => 'required|array|min:1',
            'names.*'            => 'required|string|max:255',
            'description'        => 'nullable|string',
            'status'             => 'nullable|in:0,1',
        ]);

        $count = 0;
        foreach ($request->names as $name) {
            $name = trim($name);
            if ($name === '') continue;

            Subcategory::create([
                'category_id' => $request->parent_category_id,
                'parent_id'   => $request->filled('parent_id') ? $request->parent_id : null,
                'name'        => $name,
                'description' => $request->description,
                'status'      => $request->boolean('status'),
            ]);
            $count++;
        }

        $category = Category::find($request->parent_category_id);
        $lang = $category ? ($category->language_type ?? 'SL') : 'SL';

        return redirect()
            ->route('admin.categories.index', ['tab' => 'sub', 'lang' => $lang])
            ->with('success', "$count subcategori(es) created successfully.");
    }

    public function update(Request $request, string $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $request->validate([
            'name'               => 'required|string|max:255',
            'parent_category_id' => 'required|exists:categories,id',
            'parent_id'          => 'nullable|exists:subcategories,id',
            'description'        => 'nullable|string',
            'status'             => 'nullable|in:0,1',
        ]);

        // Prevent setting parent_id to self
        $parentId = $request->filled('parent_id') && $request->parent_id != $id
            ? $request->parent_id
            : null;

        $subcategory->update([
            'name'        => $request->name,
            'description' => $request->description,
            'category_id' => $request->parent_category_id,
            'parent_id'   => $parentId,
            'status'      => $request->boolean('status'),
        ]);

        $category = Category::find($request->parent_category_id);
        $lang = $category ? ($category->language_type ?? 'SL') : 'SL';

        return redirect()
            ->route('admin.categories.index', ['tab' => 'sub', 'lang' => $lang])
            ->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(string $id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $category = Category::find($subcategory->category_id);
        $lang = $category ? ($category->language_type ?? 'SL') : 'SL';
        $subcategory->delete();

        return redirect()
            ->route('admin.categories.index', ['tab' => 'sub', 'lang' => $lang])
            ->with('success', 'Subcategory deleted successfully.');
    }

    public function toggleStatus(Request $request, string $id)
    {
        try {
            $subcategory = Subcategory::findOrFail($id);
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
                'message' => 'Error updating status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
