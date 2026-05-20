<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|string|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->boolean('status');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', __('admin.coupon_created_success'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|string|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->boolean('status');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', __('admin.coupon_updated_success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', __('admin.coupon_deleted_success'));
    }
}
