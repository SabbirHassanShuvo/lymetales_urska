@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Revenue Reports</h2>
            <p class="text-sm text-gray-500 mt-1">Detailed breakdown of all revenue-generating orders.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
            Back to Dashboard
        </a>
    </div>

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Revenue</p>
            <p class="text-3xl font-bold text-green-600 mt-2">&euro;{{ number_format($totalRevenue, 2) }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Average Order Value</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">&euro;{{ number_format($averageOrderValue, 2) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Stripe Revenue</p>
            <p class="text-3xl font-bold text-indigo-600 mt-2">&euro;{{ number_format($stripeRevenue, 2) }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">COD Revenue</p>
            <p class="text-3xl font-bold text-amber-600 mt-2">&euro;{{ number_format($codRevenue, 2) }}</p>
        </div>
    </div>

    <!-- Revenue Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800">Paid Orders Detail</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-indigo-600">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $order->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-800">{{ $order->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if(strtolower($order->payment_method) === 'stripe')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 text-xs font-bold uppercase">
                                    Stripe
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold uppercase">
                                    COD
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-800 text-right">
                            &euro;{{ number_format($order->total, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No paid orders found yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
