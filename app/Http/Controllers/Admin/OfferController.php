<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offers = Offer::orderBy('created_at', 'desc')->get();
        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'short_description'   => 'nullable|string|max:500',
            'min_quantity'        => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'is_active'           => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            Offer::where('is_active', true)->update(['is_active' => false]);
        }

        Offer::create($validated);

        return redirect()->route('admin.offers.index')->with('success', 'Offer created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $offer = Offer::findOrFail($id);

        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'short_description'   => 'nullable|string|max:500',
            'min_quantity'        => 'required|integer|min:1',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'is_active'           => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($validated['is_active']) {
            Offer::where('id', '!=', $offer->id)->where('is_active', true)->update(['is_active' => false]);
        }

        $offer->update($validated);

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted successfully.');
    }

    /**
     * Toggle the status of the specified offer.
     */
    public function toggleStatus(Request $request, string $id)
    {
        try {
            $offer = Offer::findOrFail($id);
            $offer->is_active = !$offer->is_active;
            if ($offer->is_active) {
                Offer::where('id', '!=', $offer->id)->where('is_active', true)->update(['is_active' => false]);
            }
            $offer->save();

            return response()->json([
                'success' => true,
                'status'  => $offer->is_active,
                'message' => 'Offer status updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }
}
