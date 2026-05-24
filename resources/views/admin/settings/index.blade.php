@extends('layouts.admin', ['title' => __('Settings')])

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <div class="space-y-6">
            <!-- Shipping Charge -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Charge</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500">€</span>
                    <input type="number" step="0.01" name="shipping_charge" 
                        class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600"
                        value="{{ old('shipping_charge', $settings['shipping_charge'] ?? '5.95') }}" required>
                </div>
            </div>

            <!-- Fast Production Fee -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fast Production Fee</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500">€</span>
                    <input type="number" step="0.01" name="fast_production_fee" 
                        class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600"
                        value="{{ old('fast_production_fee', $settings['fast_production_fee'] ?? '9.95') }}" required>
                </div>
            </div>

            <hr class="border-gray-100">

            <!-- Global Discount Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Global Discount Type</label>
                <select name="global_discount_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600" required>
                    <option value="fixed" {{ (old('global_discount_type', $settings['global_discount_type'] ?? 'fixed') == 'fixed') ? 'selected' : '' }}>Fixed Amount</option>
                    <option value="percentage" {{ (old('global_discount_type', $settings['global_discount_type'] ?? 'fixed') == 'percentage') ? 'selected' : '' }}>Percentage (%)</option>
                </select>
            </div>

            <!-- Global Discount Value -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Global Discount Value</label>
                <input type="number" step="0.01" name="global_discount_value" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600"
                    value="{{ old('global_discount_value', $settings['global_discount_value'] ?? '0') }}" required>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all font-medium">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
