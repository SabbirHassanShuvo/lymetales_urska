<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'parent_id'   => 'required|exists:categories,id',
            'names'       => 'required|array|min:1',
            'names.*'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:0,1',
        ]);

        $count = 0;
        foreach ($request->names as $name) {
            $name = trim($name);
            if ($name === '') continue;

            Subcategory::create([
                'category_id' => $request->parent_id,
                'name'        => $name,
                'description' => $request->description,
                'status'      => $request->boolean('status'),
            ]);
            $count++;
        }

        return redirect()
            ->route('admin.categories.index', ['tab' => 'sub'])
            ->with('success', "$count subcategori(es) created successfully.");
    }

    public function update(Request $request, string $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'parent_id'   => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:0,1',
        ]);

        $subcategory->update([
            'name'        => $request->name,
            'description' => $request->description,
            'category_id' => $request->parent_id,
            'status'      => $request->boolean('status'),
        ]);

        return redirect()
            ->route('admin.categories.index', ['tab' => 'sub'])
            ->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(string $id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->delete();

        return redirect()
            ->route('admin.categories.index', ['tab' => 'sub'])
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
                'status' => $subcategory->status,
                'message' => 'Subcategory status updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }
}
