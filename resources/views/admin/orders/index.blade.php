@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Total Orders</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Pending</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $pendingOrders }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Delivered</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $deliveredOrders }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Total Revenue</h3>
                <p class="text-2xl font-bold text-gray-800">&euro;{{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Section Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Orders Management</h2>
            <p class="text-sm text-gray-500">Manage order fulfillment and payment statuses for all customer orders.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search orders..."
                    class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <!-- Export Options -->
            <div class="relative animate-fade-in" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-44 bg-white border border-gray-100 rounded-xl shadow-lg z-50 overflow-hidden">
                    <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50">All / Filtered</div>
                    <button type="button" @click="exportOrdersToExcel('all'); open = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors flex items-center gap-2 font-semibold">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Excel
                    </button>
                    <button type="button" @click="exportOrdersToCSV('all'); open = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors flex items-center gap-2 font-semibold">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm mt-6 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Order Status</label>
                <select name="order_status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50/50 text-gray-700 font-medium">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('order_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('order_status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('order_status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('order_status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('order_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Payment Status</label>
                <select name="payment_status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50/50 text-gray-700 font-medium">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50/50 text-gray-700 font-medium">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50/50 text-gray-700 font-medium">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm shadow-sm transition-all flex items-center justify-center gap-1.5 hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
                @if(request()->anyFilled(['order_status', 'payment_status', 'date_from', 'date_to']))
                    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold rounded-xl text-sm transition-all flex items-center justify-center">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="ordersTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase border-b border-gray-100">
                        <th class="px-5 py-4 w-10">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </th>
                        <th class="px-5 py-4">Order #</th>
                        <th class="px-5 py-4">Customer</th>
                        <th class="px-5 py-4">Payment</th>
                        <th class="px-5 py-4">Order Status</th>
                        <th class="px-5 py-4">Payment Status</th>
                        <th class="px-5 py-4">Total</th>
                        <th class="px-5 py-4">Date</th>
                        <th class="px-5 py-4">Source</th>
                        <th class="px-5 py-4 text-center">Preview</th>
                        <th class="px-5 py-4 text-center">Delete</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors order-row"
                            data-order-id="{{ $order->id }}"
                            data-name="{{ strtolower($order->order_number . ' ' . $order->full_name . ' ' . $order->email) }}">
                            <td class="px-5 py-4">
                                <input type="checkbox" class="order-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" value="{{ $order->id }}">
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg">
                                    {{ $order->order_number }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-gray-900">{{ $order->full_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->email }}</div>
                            </td>
                            <td class="px-5 py-4">
                                @if($order->payment_method === 'stripe')
                                    <span class="inline-flex items-center gap-1.5 bg-violet-50 text-violet-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.591-7.305z"/></svg>
                                        Stripe
                                    </span>
                                @elseif($order->payment_method === 'paypal')
                                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M20.007 11.624c-.139 2.01-1.42 3.427-3.844 3.427h-2.146c-.506 0-.931.36-.995.858L12.01 23.63a.583.583 0 0 1-.577.509h-3.48a.43.43 0 0 1-.418-.517l2.873-18.064a.874.874 0 0 1 .865-.736h6.059c1.986 0 3.324.498 4.015 1.488.625.894.757 2.052.709 3.314zm-4.32-6.52H9.627a.437.437 0 0 0-.433.368L6.476 22.585a.291.291 0 0 0 .288.337h3.48a.583.583 0 0 0 .577-.509l1.012-6.355a.874.874 0 0 1 .866-.736h2.146c2.81 0 5.083-1.636 5.617-5.11.233-1.52.062-2.73-.664-3.6-.665-.794-1.92-1.31-3.791-1.31z"/></svg>
                                        PayPal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-teal-50 text-teal-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        COD
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <select
                                    class="order-status-select text-xs font-semibold border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer transition-colors"
                                    data-order-id="{{ $order->id }}"
                                    data-type="order-status"
                                    onchange="updateStatus(this, 'order-status', {{ $order->id }})">
                                    @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                        <option value="{{ $status }}" {{ $order->order_status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-5 py-4">
                                @if($order->payment_method === 'stripe' || $order->payment_method === 'paypal')
                                    @php
                                        $psColors = ['pending' => 'bg-amber-50 text-amber-700', 'paid' => 'bg-green-50 text-green-700', 'failed' => 'bg-red-50 text-red-700'];
                                        $psColor = $psColors[$order->payment_status] ?? 'bg-gray-50 text-gray-600';
                                        $tooltipText = $order->payment_method === 'stripe' 
                                            ? 'Stripe payment status is webhook-controlled' 
                                            : 'PayPal payment status is API-controlled';
                                    @endphp
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-bold px-2.5 py-1.5 rounded-lg {{ $psColor }}">{{ ucfirst($order->payment_status) }}</span>
                                        <span title="{{ $tooltipText }}" class="text-gray-400 cursor-help">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        </span>
                                    </div>
                                @else
                                    <select
                                        class="payment-status-select text-xs font-semibold border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer transition-colors"
                                        data-order-id="{{ $order->id }}"
                                        data-type="payment-status"
                                        onchange="updateStatus(this, 'payment-status', {{ $order->id }})">
                                        @foreach(['pending', 'paid', 'failed'] as $status)
                                            <option value="{{ $status }}" {{ $order->payment_status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-bold text-gray-800">
                                &euro;{{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500 whitespace-nowrap">
                                {{ $order->created_at->format('M d, Y') }}<br>
                                <span class="text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                            </td>
                            
                            <td class="px-5 py-4 text-xs text-gray-500 whitespace-nowrap">
                                <span class="px-2 py-1 bg-gray-100 rounded-md">{{ $order->source ?? 'Organic' }}</span>
                            </td>

                            {{-- Preview button --}}
                            <td class="px-5 py-4 text-center">
                                <button onclick="openPreviewModal({{ $order->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Preview
                                </button>
                            </td>

                            {{-- Delete button --}}
                            <td class="px-5 py-4 text-center">
                                <button onclick="deleteOrder({{ $order->id }}, '{{ $order->order_number }}', this)"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-12">
                                <div class="text-gray-300 mb-3">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <h4 class="font-bold text-gray-700">No Orders Yet</h4>
                                <p class="text-gray-500 text-sm mt-1">Orders will appear here once customers start placing them.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Server-side Pagination -->
        @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-sm text-gray-500">
                Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }} orders
            </div>
            <div class="flex items-center gap-1">
                {{-- Previous --}}
                @if($orders->onFirstPage())
                    <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-50 rounded-lg border border-gray-100 cursor-not-allowed select-none">&laquo;</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white hover:bg-indigo-50 hover:text-indigo-600 rounded-lg border border-gray-200 transition-all">&laquo;</a>
                @endif

                {{-- Page numbers --}}
                @foreach($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                    @if($page === $orders->currentPage())
                        <span class="px-3 py-1.5 text-sm font-bold text-white bg-indigo-600 rounded-lg border border-indigo-600 select-none">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white hover:bg-indigo-50 hover:text-indigo-600 rounded-lg border border-gray-200 transition-all">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white hover:bg-indigo-50 hover:text-indigo-600 rounded-lg border border-gray-200 transition-all">&raquo;</a>
                @else
                    <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-50 rounded-lg border border-gray-100 cursor-not-allowed select-none">&raquo;</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Floating Selection Action Bar --}}
    <div id="selectionBar" class="hidden fixed bottom-5 left-1/2 -translate-x-1/2 z-40 backdrop-blur-md bg-white/10 border border-white/20 text-white px-4 py-2 rounded-2xl shadow-2xl flex items-center gap-3 animate-fade-in" style="background: rgba(17,24,39,0.75); backdrop-filter: blur(12px);">
        <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-xs font-semibold text-white/90 whitespace-nowrap" id="selectionCount">0 selected</span>
        <div class="w-px h-4 bg-white/20"></div>
        <button onclick="exportOrdersToExcel('selected')" class="flex items-center gap-1.5 text-xs font-semibold text-white/80 hover:text-white border border-white/20 hover:border-white/40 hover:bg-white/10 px-2.5 py-1 rounded-lg transition-all">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Excel
        </button>
        <button onclick="exportOrdersToCSV('selected')" class="flex items-center gap-1.5 text-xs font-semibold text-white/80 hover:text-white border border-white/20 hover:border-white/40 hover:bg-white/10 px-2.5 py-1 rounded-lg transition-all">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            CSV
        </button>
        <div class="w-px h-4 bg-white/20"></div>
        <button onclick="clearSelection()" class="text-xs text-white/40 hover:text-white/80 transition-colors px-1">&times;</button>
    </div>
</div>

@endsection

@push('modals')
<!-- Order Preview Modal -->
<div id="imagePreviewModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black bg-opacity-40" onclick="closePreviewModal()"></div>
    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 py-6">
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-2xl overflow-y-auto" style="max-height: calc(100vh - 80px);">
            
            <!-- Top bar -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800" id="pvOrderNumber">Order #</h2>
                <div class="flex items-center gap-4">
                    <span id="pvOrderStatus" class="px-3 py-1 bg-green-100 text-green-700 font-semibold rounded-md text-sm uppercase tracking-wide">Status</span>
                    <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Body grid -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8 text-sm text-gray-700 border-b border-gray-100">
                <div>
                    <h3 class="font-bold text-gray-900 mb-4 text-base">Billing Details</h3>
                    <div id="pvBillingDetails" class="space-y-3"></div>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-4 text-base">Shipping Details</h3>
                    <div id="pvShippingDetails" class="space-y-3"></div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="p-6">
                <table class="w-full text-left text-sm text-gray-700 pb-4">
                    <thead>
                        <tr class="font-bold text-gray-900 border-b border-gray-100">
                            <th class="pb-3 w-2/3">Product</th>
                            <th class="pb-3 text-center">Quantity</th>
                            <th class="pb-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody id="pvItemsList" class="divide-y divide-gray-50">
                        <!-- Items will be injected here -->
                    </tbody>
                </table>

                <!-- Financial Summary -->
                <div class="border-t border-gray-100 pt-4 flex justify-end">
                    <div class="w-full md:w-80 space-y-2.5 text-sm text-gray-600" id="pvOrderSummary">
                        <!-- Summary details (Subtotal, Shipping, Coupon, Total) will be injected here -->
                    </div>
                </div>
            </div>

            <!-- Personalisation Details -->
            <div id="pvPersonalisationSection" class="hidden px-6 pb-6">
                <div class="bg-indigo-50/60 border border-indigo-100 rounded-xl p-4">
                    <h4 class="text-xs font-bold text-indigo-700 uppercase tracking-wider mb-3">Personalisation Details</h4>
                    <div id="pvPersonalisationList" class="space-y-2"></div>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 flex flex-col md:flex-row items-center justify-between bg-gray-50 border-t border-gray-100 gap-4">
                <button onclick="closePreviewModal()" class="px-5 py-2.5 bg-white border border-indigo-200 text-indigo-600 font-semibold rounded-lg shadow-sm hover:bg-indigo-50 transition-colors">
                    Close
                </button>
                <div class="flex items-center gap-3">
                    <button onclick="generateInvoice()" id="pvGenerateInvoiceBtn" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-lg shadow-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Generate Invoice
                    </button>
                    <button id="pvEditBtn" onclick="openEditFromPreview()" class="px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg shadow-sm hover:bg-indigo-700 transition-colors">
                        Edit
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Edit Order Modal -->
<div id="editOrderModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black bg-opacity-60" onclick="closeEditModal()"></div>
    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 py-6">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900">Edit Customer Details</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="editOrderForm" class="p-6 space-y-4" onsubmit="submitEditOrder(event)">
                <input type="hidden" id="editOrderId" name="order_id">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Order Status</label>
                        <select id="editOrderStatus" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Payment Status</label>
                        <select id="editPaymentStatus" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="editFullName" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" id="editEmail" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Phone</label>
                        <input type="text" id="editPhone" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Postal Code</label>
                        <input type="text" id="editPostalCode" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Address</label>
                    <input type="text" id="editAddress" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">City</label>
                        <input type="text" id="editCity" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Country</label>
                        <input type="text" id="editCountry" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="pt-4 flex justify-end gap-2 border-t">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editItemModal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black bg-opacity-60" onclick="closeEditItemModal()"></div>
    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 py-6">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900">Edit Product Details</h3>
                <button onclick="closeEditItemModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form id="editItemForm" class="p-6 space-y-4" onsubmit="submitEditItem(event)">
                <input type="hidden" id="editItemOrderId">
                <input type="hidden" id="editItemIndex">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Quantity</label>
                    <input type="number" id="editItemQuantity" min="1" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" required>
                </div>

                <div id="editItemPersonalisationContainer" class="space-y-4">
                    <!-- Dynamic fields will be injected here -->
                </div>

                <div class="pt-4 flex justify-end gap-2 border-t mt-6">
                    <button type="button" onclick="closeEditItemModal()" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-semibold transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';

    // ── Image Preview Modal ────────────────────────────────────────────────

    let _pvCurrentOrderId = null;
    let _pvCurrentOrderData = null;

    async function openPreviewModal(orderId) {
        _pvCurrentOrderId = orderId;
        _pvCurrentOrderData = null;
        
        // Show loading state (optional, can just clear previous content)
        document.getElementById('pvOrderNumber').textContent = 'Loading...';
        document.getElementById('pvBillingDetails').innerHTML = '';
        document.getElementById('pvShippingDetails').innerHTML = '';
        document.getElementById('pvItemsList').innerHTML = '';
        document.getElementById('pvPersonalisationList').innerHTML = '';
        document.getElementById('pvPersonalisationSection').classList.add('hidden');
        
        document.getElementById('imagePreviewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        try {
            const res = await fetch(`/admin/orders/${orderId}/preview`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            _pvCurrentOrderData = data;
            
            // Populate Header
            document.getElementById('pvOrderNumber').textContent = 'Order #' + data.order.order_number;
            
            const statusEl = document.getElementById('pvOrderStatus');
            statusEl.textContent = (data.order.order_status || '').toUpperCase();
            statusEl.className = "px-3 py-1 font-semibold rounded-md text-sm uppercase tracking-wide border";
            
            const status = (data.order.order_status || '').toLowerCase();
            if (status === 'pending') {
                statusEl.classList.add('bg-amber-50', 'text-amber-700', 'border-amber-100');
            } else if (status === 'processing') {
                statusEl.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-100');
            } else if (status === 'shipped') {
                statusEl.classList.add('bg-indigo-50', 'text-indigo-700', 'border-indigo-100');
            } else if (status === 'delivered') {
                statusEl.classList.add('bg-green-50', 'text-green-700', 'border-green-100');
            } else if (status === 'cancelled') {
                statusEl.classList.add('bg-rose-50', 'text-rose-700', 'border-rose-100');
            } else {
                statusEl.classList.add('bg-gray-50', 'text-gray-700', 'border-gray-100');
            }
            
            // Populate Billing
            document.getElementById('pvBillingDetails').innerHTML = `
                <p class="mb-1">${data.billing.full_name}</p>
                <p class="mb-1 text-gray-500">${data.billing.address}</p>
                <p class="mb-4 text-gray-500">${data.billing.postal_code} ${data.billing.city}</p>
                
                <p class="mb-1 font-semibold text-gray-800">Email</p>
                <p class="mb-4 text-indigo-600 underline"><a href="mailto:${data.billing.email}">${data.billing.email}</a></p>
                
                <p class="mb-1 font-semibold text-gray-800">Phone</p>
                <p class="mb-4 text-indigo-600 underline">${data.billing.phone || '-'}</p>
                
                <p class="mb-1 font-semibold text-gray-800">Payment via</p>
                <p class="text-gray-500">${(data.order.payment_method || '').toUpperCase()} (${data.order.payment_status})</p>
            `;
            
            // Populate Shipping
            document.getElementById('pvShippingDetails').innerHTML = `
                <p class="mb-1 text-indigo-600 underline">${data.shipping.full_name}</p>
                <p class="mb-1 text-indigo-600 underline">${data.shipping.address}</p>
                <p class="mb-4 text-indigo-600 underline">${data.shipping.postal_code} ${data.shipping.city}</p>
                
                <p class="mb-1 font-semibold text-gray-800">Shipping method</p>
                <p class="text-gray-500">${data.shipping.method}</p>
            `;
            
            // Populate Items
            let itemsHtml = '';
            const skipKeys = ['preview_image', 'pdf_url', 'created_at', 'updated_at', 'id', 'product_id', 'quantity', 'fields'];
            const formatLabel = (key) => String(key).split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
            const isHexColor = (val) => typeof val === 'string' && /^#[0-9A-F]{6}$/i.test(val);

            data.items.forEach((item, index) => {
                
                // Construct personalisation HTML for this specific product (product-wise)
                let personalisationHtml = '';
                if (item.personalisation && typeof item.personalisation === 'object') {
                    // Extract top-level attributes
                    const topLevelEntries = Object.entries(item.personalisation).filter(([k, v]) =>
                        !skipKeys.includes(k.toLowerCase()) && v !== null && typeof v !== 'object'
                    );

                    // Extract fields sub-attributes (e.g. character_gender, skin_tone, hair_style, eye_colour)
                    let fieldsEntries = [];
                    if (item.personalisation.fields && typeof item.personalisation.fields === 'object') {
                        fieldsEntries = Object.entries(item.personalisation.fields).filter(([k, v]) =>
                            v !== null && typeof v !== 'object'
                        );
                    }

                    const allEntries = [...topLevelEntries, ...fieldsEntries];

                    if (allEntries.length > 0) {
                        personalisationHtml = `
                            <div class="mt-3 bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 max-w-2xl">
                                <h4 class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider mb-2.5">Personalisation Details</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    ${allEntries.map(([k, v]) => {
                                        let valueHtml = v;
                                        if (isHexColor(v)) {
                                            valueHtml = `
                                                <div class="flex items-center gap-1.5">
                                                    <span class="w-3.5 h-3.5 rounded-full border border-gray-300 flex-shrink-0 shadow-sm" style="background-color: ${v};"></span>
                                                    <span class="font-mono text-xs">${v}</span>
                                                </div>
                                            `;
                                        }
                                        return `
                                            <div class="bg-white rounded-lg px-3 py-2 border border-indigo-50 shadow-sm flex flex-col justify-center">
                                                <p class="text-[10px] text-gray-400 font-semibold">${formatLabel(k)}</p>
                                                <p class="text-xs text-gray-800 font-semibold mt-0.5">${valueHtml}</p>
                                            </div>
                                        `;
                                    }).join('')}
                                </div>
                            </div>
                        `;
                    }
                }

                itemsHtml += `
                    <tr>
                        <td class="py-4 align-top">
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-gray-900">${item.title}</p>
                                <button onclick='openEditItemModal(${index})' class="text-indigo-600 hover:text-indigo-800 transition-colors p-1 bg-indigo-50 hover:bg-indigo-100 rounded-md" title="Edit Item Details">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                            </div>
                            ${personalisationHtml}
                        </td>
                        <td class="py-4 align-top text-center">${item.quantity}</td>
                        <td class="py-4 align-top text-right whitespace-nowrap">&euro;${item.line_total}</td>
                    </tr>
                `;
            });
            document.getElementById('pvItemsList').innerHTML = itemsHtml;

            // Populate Summary
            let summaryHtml = `
                <div class="flex justify-between items-center text-gray-500">
                    <span>Subtotal</span>
                    <span class="font-semibold text-gray-800">&euro;${data.order.subtotal || '0.00'}</span>
                </div>
                <div class="flex justify-between items-center text-gray-500">
                    <span>Shipping Fee</span>
                    <span class="font-semibold text-gray-800">&euro;${data.order.shipping_fee || '0.00'}</span>
                </div>
            `;

            if (data.order.fast_production_fee && parseFloat(data.order.fast_production_fee) > 0) {
                summaryHtml += `
                    <div class="flex justify-between items-center text-gray-500">
                        <span>Fast Production Fee</span>
                        <span class="font-semibold text-gray-800">&euro;${data.order.fast_production_fee}</span>
                    </div>
                `;
            }

            if (data.order.discount && parseFloat(data.order.discount) > 0) {
                const couponLabel = data.order.coupon_code ? `Discount (${data.order.coupon_code.toUpperCase()})` : 'Discount';
                summaryHtml += `
                    <div class="flex justify-between items-center text-rose-600">
                        <span>${couponLabel}</span>
                        <span class="font-semibold">-&euro;${data.order.discount}</span>
                    </div>
                `;
            }

            summaryHtml += `
                <div class="flex justify-between items-center border-t border-gray-100 pt-2.5 text-base font-bold text-gray-900 mt-2">
                    <span>Total</span>
                    <span class="text-indigo-600">&euro;${data.order.total || '0.00'}</span>
                </div>
            `;

            document.getElementById('pvOrderSummary').innerHTML = summaryHtml;

            // Ensure old bottom personalisation section remains hidden
            const pvSection = document.getElementById('pvPersonalisationSection');
            if (pvSection) {
                pvSection.classList.add('hidden');
            }
            
            // Attach data to Edit button so it can open the Edit Modal
            document.getElementById('pvEditBtn').setAttribute('data-order', JSON.stringify({
                id: data.order.id,
                full_name: data.billing.full_name,
                email: data.billing.email,
                phone: data.billing.phone,
                address: data.billing.address,
                city: data.billing.city,
                postal_code: data.billing.postal_code,
                country: data.billing.country,
                order_status: data.order.order_status,
                payment_status: data.order.payment_status
            }));

        } catch (e) {
            console.error('Error fetching preview data', e);
            document.getElementById('pvOrderNumber').textContent = 'Error loading order';
        }
    }

    function openEditFromPreview() {
        closePreviewModal();
        const dataStr = document.getElementById('pvEditBtn').getAttribute('data-order');
        if (dataStr) {
            const data = JSON.parse(dataStr);
            openEditModal(data.id, null, data);
        }
    }

    function generateInvoice() {
        if (!_pvCurrentOrderId) return;
        Swal.fire({
            title: 'Generate Invoice?',
            text: 'Are you sure you want to generate an invoice for this order via Cebelca?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, generate',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'rounded-2xl shadow-xl' }
        }).then(result => {
            if (result.isConfirmed) {
                // To be implemented on backend
                Swal.fire({
                    icon: 'info',
                    title: 'Pending',
                    text: 'Cebelca API integration is pending API credentials.',
                    customClass: { popup: 'rounded-2xl shadow-xl' }
                });
            }
        });
    }

    function closePreviewModal() {
        document.getElementById('imagePreviewModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close on Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closePreviewModal();
            if (typeof closeEditModal === 'function') closeEditModal();
        }
    });

    // ── Edit Order Modal ───────────────────────────────────────────────────
    
    function openEditModal(orderId, btn, passedData = null) {
        let data = {};
        if (passedData) {
            data = passedData;
        } else if (btn) {
            data = JSON.parse(btn.getAttribute('data-order-details') || '{}');
        }
        
        document.getElementById('editOrderId').value = orderId;
        
        if (data.order_status) document.getElementById('editOrderStatus').value = data.order_status;
        if (data.payment_status) document.getElementById('editPaymentStatus').value = data.payment_status;
        
        document.getElementById('editFullName').value = data.full_name || '';
        document.getElementById('editEmail').value = data.email || '';
        document.getElementById('editPhone').value = data.phone || '';
        document.getElementById('editPostalCode').value = data.postal_code || '';
        document.getElementById('editAddress').value = data.address || '';
        document.getElementById('editCity').value = data.city || '';
        document.getElementById('editCountry').value = data.country || '';
        
        document.getElementById('editOrderModal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editOrderModal').classList.add('hidden');
    }
    
    function submitEditOrder(e) {
        e.preventDefault();
        const orderId = document.getElementById('editOrderId').value;
        const payload = {
            order_status: document.getElementById('editOrderStatus').value,
            payment_status: document.getElementById('editPaymentStatus').value,
            full_name: document.getElementById('editFullName').value,
            email: document.getElementById('editEmail').value,
            phone: document.getElementById('editPhone').value,
            postal_code: document.getElementById('editPostalCode').value,
            address: document.getElementById('editAddress').value,
            city: document.getElementById('editCity').value,
            country: document.getElementById('editCountry').value,
        };
        
        fetch(`/admin/orders/${orderId}/update-details`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload),
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok || !data.success) throw new Error(data.message || 'Failed to update order details.');
            
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Order details have been successfully updated.',
                timer: 1500,
                showConfirmButton: false,
                customClass: { popup: 'rounded-2xl shadow-xl' }
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(err => {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, customClass: { popup: 'rounded-2xl shadow-xl' } });
        });
    }

    // ── Edit Item Modal ───────────────────────────────────────────────────

    function openEditItemModal(itemIndex) {
        if (!_pvCurrentOrderData || !_pvCurrentOrderData.items[itemIndex]) return;
        const item = _pvCurrentOrderData.items[itemIndex];
        const orderId = _pvCurrentOrderData.order.id;

        document.getElementById('editItemOrderId').value = orderId;
        document.getElementById('editItemIndex').value = itemIndex;
        document.getElementById('editItemQuantity').value = item.quantity || 1;

        const container = document.getElementById('editItemPersonalisationContainer');
        container.innerHTML = '';

        if (item.personalisation) {
            const formatLabel = (key) => String(key).split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
            
            // Loop through top level fields
            for (const [key, val] of Object.entries(item.personalisation)) {
                if (['preview_image', 'pdf_url', 'created_at', 'updated_at', 'id', 'product_id', 'quantity', 'fields'].includes(key.toLowerCase())) continue;
                
                if (val !== null && typeof val !== 'object') {
                    container.innerHTML += `
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">${formatLabel(key)}</label>
                            <input type="text" name="personalisation[${key}]" value="${val}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                    `;
                }
            }

            // Loop through nested fields
            if (item.personalisation.fields && typeof item.personalisation.fields === 'object') {
                for (const [key, val] of Object.entries(item.personalisation.fields)) {
                    if (val !== null && typeof val !== 'object') {
                        container.innerHTML += `
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">${formatLabel(key)}</label>
                                <input type="text" name="personalisation[fields][${key}]" value="${val}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            </div>
                        `;
                    }
                }
            }
        }

        document.getElementById('editItemModal').classList.remove('hidden');
    }

    function closeEditItemModal() {
        document.getElementById('editItemModal').classList.add('hidden');
    }

    function submitEditItem(e) {
        e.preventDefault();
        const orderId = document.getElementById('editItemOrderId').value;
        const container = document.getElementById('editItemPersonalisationContainer');
        
        const payload = {
            item_index: document.getElementById('editItemIndex').value,
            quantity: document.getElementById('editItemQuantity').value,
            personalisation: {}
        };

        const fields = {};
        container.querySelectorAll('input[name]').forEach(input => {
            const name = input.name;
            const value = input.value;
            
            if (name.startsWith('personalisation[fields][')) {
                const match = name.match(/personalisation\[fields\]\[(.*?)\]/);
                if (match) {
                    fields[match[1]] = value;
                }
            } else if (name.startsWith('personalisation[')) {
                const match = name.match(/personalisation\[(.*?)\]/);
                if (match) {
                    payload.personalisation[match[1]] = value;
                }
            }
        });

        if (Object.keys(fields).length > 0) {
            payload.personalisation['fields'] = fields;
        }

        fetch(`/admin/orders/${orderId}/update-items`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload),
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok || !data.success) throw new Error(data.message || 'Failed to update item details.');
            
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Item details have been successfully updated.',
                timer: 1500,
                showConfirmButton: false,
                customClass: { popup: 'rounded-2xl shadow-xl' }
            }).then(() => {
                closeEditItemModal();
                openPreviewModal(orderId); // Refresh modal
            });
        })
        .catch(err => {
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, customClass: { popup: 'rounded-2xl shadow-xl' } });
        });
    }

    // ── Delete Order ───────────────────────────────────────────────────────

    function deleteOrder(orderId, orderNumber, btn) {
        Swal.fire({
            title: 'Delete order?',
            text: `Order ${orderNumber} will be permanently deleted.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444',
            customClass: { popup: 'rounded-2xl shadow-xl' },
        }).then(result => {
            if (!result.isConfirmed) return;

            btn.disabled = true;

            fetch(`/admin/orders/${orderId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.message ?? 'Delete failed.');
                // Remove table row
                btn.closest('tr').remove();
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: `Order ${orderNumber} has been deleted.`,
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-2xl shadow-xl' },
                });
            })
            .catch(err => {
                btn.disabled = false;
                Swal.fire({ icon: 'error', title: 'Error', text: err.message, customClass: { popup: 'rounded-2xl shadow-xl' } });
            });
        });
    }

    // ── Status Update ──────────────────────────────────────────────────────

    function updateStatus(select, type, orderId) {
        const newValue      = select.value;
        const previousValue = select.dataset.previous ?? newValue;
        select.dataset.previous = previousValue;

        const url     = `/admin/orders/${orderId}/${type}`;
        const bodyKey = type === 'order-status' ? 'order_status' : 'payment_status';

        // If setting to "shipped", ask for tracking info first
        if (type === 'order-status' && newValue === 'shipped') {
            select.disabled = true;
            Swal.fire({
                title: '🚚 Shipping Details',
                html: `
                    <div style="text-align:left;margin-top:8px;">
                        <label style="display:block;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Tracking Number</label>
                        <input id="swal-tracking-number" type="text" placeholder="e.g. CJ123456789SI"
                            class="swal2-input" style="margin:0 0 14px;width:100%;font-family:monospace;font-size:14px;">
                        <label style="display:block;font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Tracking Link <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
                        <input id="swal-tracking-link" type="url" placeholder="https://www.posta.si/track/..."
                            class="swal2-input" style="margin:0;width:100%;font-size:13px;">
                    </div>
                `,
                confirmButtonText: 'Mark as Shipped & Send Email',
                cancelButtonText: 'Cancel',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                customClass: { popup: 'rounded-2xl shadow-xl', confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' },
                preConfirm: () => {
                    const tn = document.getElementById('swal-tracking-number').value.trim();
                    const tl = document.getElementById('swal-tracking-link').value.trim();
                    return { tracking_number: tn, tracking_link: tl };
                }
            }).then(result => {
                if (!result.isConfirmed) {
                    select.value = previousValue;
                    select.disabled = false;
                    return;
                }
                const body = { order_status: 'shipped' };
                if (result.value.tracking_number) body.tracking_number = result.value.tracking_number;
                if (result.value.tracking_link) body.tracking_link = result.value.tracking_link;

                fetch(url, {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body),
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok || !data.success) throw new Error(data.message ?? 'Update failed.');
                    select.dataset.previous = 'shipped';
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Shipped! 🚚',
                        text: 'Status updated and shipping email sent to the customer.',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-2xl shadow-xl' }
                    }).then(() => window.location.reload());
                })
                .catch(err => {
                    select.value = previousValue;
                    Swal.fire({ icon: 'error', title: 'Error', text: err.message, customClass: { popup: 'rounded-2xl shadow-xl' } });
                })
                .finally(() => { select.disabled = false; });
            });
            return;
        }

        select.disabled = true;

        fetch(url, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ [bodyKey]: newValue }),
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok || !data.success) throw new Error(data.message ?? 'Update failed.');
            select.dataset.previous = newValue;
            Swal.fire({ 
                icon: 'success', 
                title: 'Updated!', 
                text: `Status changed to "${newValue}".`, 
                timer: 1500, 
                showConfirmButton: false, 
                customClass: { popup: 'rounded-2xl shadow-xl' } 
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(err => {
            select.value = previousValue;
            Swal.fire({ icon: 'error', title: 'Error', text: err.message, customClass: { popup: 'rounded-2xl shadow-xl' } });
        })
        .finally(() => { select.disabled = false; });
    }

    // ── Checkbox Selection & Selection Bar ────────────────────────────────

    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
    }

    function updateSelectionBar() {
        const ids = getSelectedIds();
        const bar = document.getElementById('selectionBar');
        const countEl = document.getElementById('selectionCount');
        if (ids.length > 0) {
            bar.classList.remove('hidden');
            countEl.textContent = ids.length + (ids.length === 1 ? ' order selected' : ' orders selected');
        } else {
            bar.classList.add('hidden');
        }
    }

    function clearSelection() {
        document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        updateSelectionBar();
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Select-all checkbox
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.order-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectionBar();
            });
        }

        // Individual checkboxes
        document.querySelectorAll('.order-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.order-checkbox').length === document.querySelectorAll('.order-checkbox:checked').length;
                document.getElementById('selectAll').checked = allChecked;
                updateSelectionBar();
            });
        });

        // Search filter (client-side on current page)
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.order-row').forEach(row => {
                    const name = (row.dataset.name || '').toLowerCase();
                    row.style.display = name.includes(q) ? '' : 'none';
                });
            });
        }
    });

    document.querySelectorAll('select[data-type]').forEach(select => {
        select.dataset.previous = select.value;
    });

    // ── Export Helper ──────────────────────────────────────────────────────
    const EXPORT_SELECTED_URL = '{{ route("admin.orders.export-selected") }}';
    const CSRF_TOKEN_EXPORT = '{{ csrf_token() }}';

    // Get data for export — either from server (for 'selected'/'all') or from table DOM
    async function fetchExportData(mode) {
        const headers = ["Order #", "Customer Name", "Customer Email", "Payment Method", "Order Status", "Payment Status", "Total", "Date"];
        let ids = [];
        if (mode === 'selected') {
            ids = getSelectedIds();
            if (ids.length === 0) {
                Swal.fire('Info', 'No orders selected. Please check at least one order.', 'info');
                return null;
            }
        }

        // Fetch from server
        const body = new URLSearchParams();
        body.append('_token', CSRF_TOKEN_EXPORT);
        body.append('format', 'json');
        if (ids.length > 0) {
            ids.forEach(id => body.append('ids[]', id));
        } else {
            // Pass current filter params
            const params = new URLSearchParams(window.location.search);
            for (const [k, v] of params.entries()) {
                if (['order_status','payment_status','date_from','date_to'].includes(k)) {
                    body.append(k, v);
                }
            }
        }

        const res = await fetch(EXPORT_SELECTED_URL, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: body
        });
        const json = await res.json();
        if (!json.data) return null;

        const rows = [headers];
        json.data.forEach(o => {
            rows.push([o.order_number, o.full_name, o.email, o.payment_method, o.order_status, o.payment_status, o.total, o.date]);
        });
        return rows;
    }

    async function exportOrdersToExcel(mode) {
        const data = await fetchExportData(mode);
        if (!data || data.length <= 1) {
            if (data) Swal.fire('Info', 'No data to export.', 'info');
            return;
        }
        const worksheet = XLSX.utils.aoa_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Orders");
        XLSX.writeFile(workbook, `orders_report_${new Date().toISOString().slice(0,10)}.xlsx`);
    }

    async function exportOrdersToCSV(mode) {
        const data = await fetchExportData(mode);
        if (!data || data.length <= 1) {
            if (data) Swal.fire('Info', 'No data to export.', 'info');
            return;
        }
        const csvContent = "\uFEFF" + data.map(e => e.map(val => {
            let text = String(val);
            if (text.includes(',') || text.includes('"') || text.includes('\n')) {
                text = '"' + text.replace(/"/g, '""') + '"';
            }
            return text;
        }).join(",")).join("\n");
        
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", `orders_report_${new Date().toISOString().slice(0,10)}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    async function exportOrdersToWord(mode) {
        const data = await fetchExportData(mode);
        if (!data || data.length <= 1) {
            if (data) Swal.fire('Info', 'No data to export.', 'info');
            return;
        }
        const title = "Orders Report";
        let html = `
        <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
        <head>
            <title>${title}</title>
            <style>
                table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 11px; }
                th { background-color: #4F46E5; color: white; padding: 8px; text-align: left; }
                td { border: 1px solid #E5E7EB; padding: 8px; }
                h2 { font-family: Arial, sans-serif; color: #1F2937; }
            </style>
        </head>
        <body>
            <h2>${title}</h2>
            <p>Generated on: ${new Date().toLocaleString()}</p>
            <table>
                <thead>
                    <tr>
                        ${data[0].map(h => `<th>${h}</th>`).join('')}
                    </tr>
                </thead>
                <tbody>
                    ${data.slice(1).map(row => `
                        <tr>
                            ${row.map(cell => `<td>${cell}</td>`).join('')}
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </body>
        </html>`;

        const blob = new Blob([html], { type: 'application/msword' });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", `orders_report_${new Date().toISOString().slice(0,10)}.doc`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    async function exportOrdersToPDF(mode) {
        const data = await fetchExportData(mode);
        if (!data || data.length <= 1) {
            if (data) Swal.fire('Info', 'No data to export.', 'info');
            return;
        }
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'pt', 'a4');
        
        doc.setFontSize(16);
        doc.text("Orders Report", 40, 40);
        doc.setFontSize(10);
        doc.text(`Generated on: ${new Date().toLocaleString()}`, 40, 55);
        
        doc.autoTable({
            head: [data[0]],
            body: data.slice(1),
            startY: 70,
            styles: { fontSize: 8 },
            headStyles: { fillColor: [79, 70, 229] }
        });
        
        doc.save(`orders_report_${new Date().toISOString().slice(0,10)}.pdf`);
    }

    // ── eSpremnica Export (via server POST) ─────────────────────────────────
    function exportESpremnicaSelected(mode) {
        let ids = [];
        if (mode === 'selected') {
            ids = getSelectedIds();
            if (ids.length === 0) {
                Swal.fire('Info', 'No orders selected. Please check at least one order.', 'info');
                return;
            }
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = EXPORT_SELECTED_URL;
        form.style.display = 'none';

        // CSRF
        const csrf = document.createElement('input');
        csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = CSRF_TOKEN_EXPORT;
        form.appendChild(csrf);

        // Format
        const fmt = document.createElement('input');
        fmt.type = 'hidden'; fmt.name = 'format'; fmt.value = 'espremnica';
        form.appendChild(fmt);

        // IDs
        ids.forEach(id => {
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = id;
            form.appendChild(inp);
        });

        // Pass current filters if exporting all
        if (ids.length === 0) {
            const params = new URLSearchParams(window.location.search);
            for (const [k, v] of params.entries()) {
                if (['order_status','payment_status','date_from','date_to'].includes(k)) {
                    const inp = document.createElement('input');
                    inp.type = 'hidden'; inp.name = k; inp.value = v;
                    form.appendChild(inp);
                }
            }
        }

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
</script>
@endpush
