<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shipping_charge' => 'required|numeric|min:0',
            'fast_production_fee' => 'required|numeric|min:0',
            'global_discount_type' => 'required|in:fixed,percentage',
            'global_discount_value' => 'required|numeric|min:0',
        ]);

        $keys = ['shipping_charge', 'fast_production_fee', 'global_discount_type', 'global_discount_value'];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $request->input($key)]
            );
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
