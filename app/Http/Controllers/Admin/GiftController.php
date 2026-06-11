<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gifts = Gift::orderBy('created_at', 'desc')->get();
        return view('admin.gifts.index', compact('gifts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'short_description', 'price']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Ensure uploads directory exists
            $uploadPath = public_path('uploads/gifts');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $image->move($uploadPath, $imageName);
            $data['image_path'] = 'uploads/gifts/' . $imageName;
        }

        Gift::create($data);

        return redirect()->route('admin.gifts.index')->with('success', 'Gift created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gift = Gift::findOrFail($id);

        $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->only(['title', 'short_description', 'price']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($gift->image_path && File::exists(public_path($gift->image_path))) {
                File::delete(public_path($gift->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            $uploadPath = public_path('uploads/gifts');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $image->move($uploadPath, $imageName);
            $data['image_path'] = 'uploads/gifts/' . $imageName;
        }

        $gift->update($data);

        return redirect()->route('admin.gifts.index')->with('success', 'Gift updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gift = Gift::findOrFail($id);

        if ($gift->image_path && File::exists(public_path($gift->image_path))) {
            File::delete(public_path($gift->image_path));
        }

        $gift->delete();

        return redirect()->route('admin.gifts.index')->with('success', 'Gift deleted successfully.');
    }
}
