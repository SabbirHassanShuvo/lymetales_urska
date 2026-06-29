@extends('layouts.admin')

@section('content')
@php
    $periods = [
        [
            'key' => 'today',      
            'label' => 'Today',      
            'color' => 'indigo',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ],
        [
            'key' => 'this_week',  
            'label' => 'This Week',  
            'color' => 'violet',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
        ],
        [
            'key' => 'this_month', 
            'label' => 'This Month', 
            'color' => 'emerald',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>'
        ],
        [
            'key' => 'this_year',  
            'label' => 'This Year',  
            'color' => 'amber',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>'
        ],
        [
            'key' => 'all',        
            'label' => 'All Time',   
            'color' => 'rose',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ],
    ];
    $maxRevenue = max(array_values($revenueByPeriod)) ?: 1;
    $colorMap = [
        'indigo'  => ['bar' => '#6366f1', 'light' => '#eef2ff', 'text' => '#4338ca', 'glow' => 'rgba(99,102,241,0.08)'],
        'violet'  => ['bar' => '#8b5cf6', 'light' => '#f5f3ff', 'text' => '#7c3aed', 'glow' => 'rgba(139,92,246,0.08)'],
        'emerald' => ['bar' => '#10b981', 'light' => '#ecfdf5', 'text' => '#059669', 'glow' => 'rgba(16,185,129,0.08)'],
        'amber'   => ['bar' => '#f59e0b', 'light' => '#fffbeb', 'text' => '#d97706', 'glow' => 'rgba(245,158,11,0.08)'],
        'rose'    => ['bar' => '#f43f5e', 'light' => '#fff1f2', 'text' => '#e11d48', 'glow' => 'rgba(244,63,94,0.08)'],
    ];
@endphp
<div class="space-y-6">

    {{-- ── Header ── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Sales Reports &amp; Analytics</h2>
            <p class="text-sm text-gray-500 mt-1">Monitor revenue, product sales, and coupon usages over time.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-xl transition-all shadow-sm self-start">
            &larr; Dashboard
        </a>
    </div>

    {{-- ── Advanced Grid Revenue Overview ── --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-800 tracking-tight">Revenue Overview</h3>
                <p class="text-xs text-gray-400">Paid orders gross revenue comparison</p>
            </div>
            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-xl">
                All-time: &euro;{{ number_format($revenueByPeriod['all'], 2) }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5" id="revenueBars">
            @foreach($periods as $period)
            @php
                $val = $revenueByPeriod[$period['key']];
                $pct = $maxRevenue > 0 ? round(($val / $maxRevenue) * 100, 1) : 0;
                $c   = $colorMap[$period['color']];
            @endphp
            <a href="{{ route('admin.reports.revenue', ['date_preset' => $period['key']]) }}" 
               class="group relative bg-white border border-gray-100 rounded-3xl p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden cursor-pointer">
                
                {{-- Decorative Hover Bar --}}
                <div class="absolute inset-x-0 top-0 h-1 transition-all duration-300 group-hover:h-1.5" style="background: {{ $c['bar'] }};"></div>
                
                <div class="flex items-center justify-between mb-5">
                    <div class="p-2.5 rounded-2xl transition-all duration-300" style="background: {{ $c['light'] }}; color: {{ $c['text'] }};">
                        {!! $period['icon'] !!}
                    </div>
                    @if($pct > 0)
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                        style="background: {{ $c['light'] }}; color: {{ $c['text'] }}">
                        {{ $pct }}%
                    </span>
                    @endif
                </div>

                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-gray-400 group-hover:text-gray-600 transition-colors uppercase tracking-wider">{{ $period['label'] }}</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-xl font-black text-gray-800 tabular-nums rev-bar-value" data-value="{{ $val }}" data-formatted="€{{ number_format($val, 2) }}">€0.00</span>
                    </div>
                </div>

                {{-- Tiny Modern Indicator Bar --}}
                <div class="mt-4 w-full bg-gray-50 h-1.5 rounded-full overflow-hidden">
                    <div class="h-full rounded-full rev-bar-fill transition-all duration-[1200ms] ease-out"
                        style="width: 0%; background: linear-gradient(90deg, {{ $c['bar'] }}cc, {{ $c['bar'] }});"
                        data-target="{{ $pct }}">
                    </div>
                </div>

                {{-- Interactive Ambient Glow Shadow --}}
                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-all duration-500 pointer-events-none rounded-3xl" style="box-shadow: 0 10px 30px {{ $c['glow'] }};"></div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── Stats Cards ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">
        @php
        $cards = [
            ['label' => 'Gross Revenue', 'value' => '€'.number_format($grossRevenue,2),  'sub' => 'Sum of totals',          'color' => '#6366f1'],
            ['label' => 'Net Revenue',   'value' => '€'.number_format($netRevenue,2),     'sub' => 'Excl. VAT',              'color' => '#10b981'],
            ['label' => 'VAT ('.$vatRate.'%)', 'value' => '€'.number_format($totalVat,2), 'sub' => 'Tax collected',          'color' => '#8b5cf6'],
            ['label' => 'Shipping',      'value' => '€'.number_format($totalShipping,2),  'sub' => 'Shipping + Fast prod.',  'color' => '#3b82f6'],
            ['label' => 'Orders',        'value' => $orderCount,                           'sub' => 'Paid orders',            'color' => '#6366f1'],
            ['label' => 'AOV (Net)',     'value' => '€'.number_format($averageOrderValue,2),'sub' => 'Net / orders',          'color' => '#f59e0b'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition-all">
            <p class="text-[10px] font-bold uppercase tracking-wider" style="color: {{ $card['color'] }}">{{ $card['label'] }}</p>
            <p class="text-2xl font-black text-gray-800 mt-2 stat-count" data-target="{{ preg_replace('/[^0-9.]/', '', $card['value']) }}" data-prefix="{{ str_starts_with($card['value'], '€') ? '€' : '' }}">{{ $card['value'] }}</p>
            <span class="text-[10px] text-gray-400 mt-2 block">{{ $card['sub'] }}</span>
        </div>
        @endforeach
    </div>

    {{-- ── Tabbed Tables ── --}}
    <div x-data="{ activeTab: 'products' }" class="space-y-0">

        {{-- Tab bar with date preset filter + export --}}
        <div class="bg-white rounded-t-2xl border border-gray-100 shadow-sm px-4 py-3 flex flex-col md:flex-row md:items-center justify-between gap-3">

            {{-- Left: Tabs --}}
            <div class="flex flex-wrap gap-1 select-none">
                <button type="button" @click="activeTab = 'products'"
                    :class="activeTab === 'products' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-lg transition-all focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Products
                </button>
                <button type="button" @click="activeTab = 'orders'"
                    :class="activeTab === 'orders' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-lg transition-all focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Orders
                </button>
                <button type="button" @click="activeTab = 'coupons'"
                    :class="activeTab === 'coupons' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-lg transition-all focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    Coupons
                </button>
            </div>

            {{-- Right: Date preset filter + export --}}
            <form id="filterForm" action="{{ route('admin.reports.revenue') }}" method="GET"
                x-data="{ preset: '{{ $preset }}' }"
                class="flex flex-wrap items-end gap-2">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Date Range</label>
                    <select name="date_preset" x-model="preset"
                        @change="if(preset !== 'custom') { $el.form.submit() }"
                        class="px-3 py-2 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white cursor-pointer shadow-sm">
                        <option value="today"      {{ $preset === 'today'      ? 'selected' : '' }}>Today</option>
                        <option value="this_week"  {{ $preset === 'this_week'  ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ $preset === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="this_year"  {{ $preset === 'this_year'  ? 'selected' : '' }}>This Year</option>
                        <option value="all"        {{ $preset === 'all'        ? 'selected' : '' }}>All Time</option>
                        <option value="custom"     {{ $preset === 'custom'     ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
                <div x-show="preset === 'custom'" x-transition.duration.200ms class="flex items-end gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">From</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}"
                            class="px-3 py-2 border border-gray-200 rounded-xl text-xs text-gray-700 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">To</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                            class="px-3 py-2 border border-gray-200 rounded-xl text-xs text-gray-700 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-sm">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">Apply</button>
                </div>
                {{-- Export (all/filtered) --}}
                <div class="relative ml-1" x-data="{ eopen: false }">
                    <button type="button" @click="eopen = !eopen"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-xl transition-all shadow-sm">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export
                    </button>
                    <div x-show="eopen" @click.away="eopen = false" x-transition
                        class="absolute right-0 mt-2 w-44 bg-white border border-gray-100 rounded-xl shadow-lg z-50 overflow-hidden top-full">
                        <div class="px-4 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50">Active Tab — All</div>
                        <button type="button" @click="exportActiveTab('excel', 'all'); eopen = false"
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors flex items-center gap-2 font-semibold">
                            <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Excel
                        </button>
                        <button type="button" @click="exportActiveTab('csv', 'all'); eopen = false"
                            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors flex items-center gap-2 font-semibold">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            CSV
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ── 1. Products ── --}}
        <div x-show="activeTab === 'products'" x-transition.duration.200ms
            class="bg-white rounded-b-2xl border border-t-0 border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto max-h-[520px] overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white shadow-[0_1px_0_0_rgba(243,244,246,1)]">
                        <tr class="border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="px-5 py-4 w-10">
                                <input type="checkbox" id="prodSelectAll" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            </th>
                            <th class="px-5 py-4 w-20">Rank</th>
                            <th class="px-5 py-4">Product Name</th>
                            <th class="px-5 py-4 text-center">Qty Sold</th>
                            <th class="px-5 py-4 text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($productStats as $index => $stat)
                        <tr class="hover:bg-gray-50/50 transition-colors prod-row" data-index="{{ $index }}">
                            <td class="px-5 py-3.5">
                                <input type="checkbox" class="prod-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" value="{{ $index }}">
                            </td>
                            <td class="px-5 py-3.5 text-sm font-bold text-gray-400">#{{ $index + 1 }}</td>
                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-800">{{ $stat['title'] }}</td>
                            <td class="px-5 py-3.5 text-sm text-gray-600 text-center font-bold">{{ $stat['quantity'] }}</td>
                            <td class="px-5 py-3.5 text-sm font-extrabold text-indigo-600 text-right">&euro;{{ number_format($stat['revenue'], 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">No product sales data for this range.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── 2. Orders ── --}}
        <div x-show="activeTab === 'orders'" x-transition.duration.200ms
            class="bg-white rounded-b-2xl border border-t-0 border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-3.5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between gap-3">
                <h3 class="text-sm font-bold text-gray-700">Orders Pricing Breakdown</h3>
                <div class="relative">
                    <input type="text" id="revenueSearch" placeholder="Search orders..."
                        class="pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm w-56 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="px-5 py-4 w-10">
                                <input type="checkbox" id="revSelectAll" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            </th>
                            <th class="px-5 py-4">Order #</th>
                            <th class="px-5 py-4">Date</th>
                            <th class="px-5 py-4">Customer</th>
                            <th class="px-5 py-4 text-right">Item Price</th>
                            <th class="px-5 py-4 text-right">Shipping</th>
                            <th class="px-5 py-4 text-right">Discount</th>
                            <th class="px-5 py-4 text-right">Total</th>
                            <th class="px-5 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors rev-order-row"
                            data-order-id="{{ $order->id }}"
                            data-name="{{ strtolower($order->full_name . ' ' . $order->email . ' ' . $order->order_number) }}">
                            <td class="px-5 py-3.5">
                                <input type="checkbox" class="rev-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" value="{{ $order->id }}">
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg">{{ $order->order_number }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500 whitespace-nowrap">
                                {{ $order->created_at->format('M d, Y') }}<br>
                                <span class="text-gray-400">{{ $order->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-gray-900 text-sm">{{ $order->full_name }}</div>
                                <div class="text-xs text-gray-400">{{ $order->email }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-right font-medium text-gray-700">&euro;{{ number_format($order->subtotal, 2) }}</td>
                            <td class="px-5 py-3.5 text-right font-medium text-gray-700">&euro;{{ number_format($order->shipping_fee + $order->fast_production_fee, 2) }}</td>
                            <td class="px-5 py-3.5 text-right font-semibold text-rose-600">&euro;{{ number_format($order->discount, 2) }}</td>
                            <td class="px-5 py-3.5 text-right font-extrabold text-gray-900">&euro;{{ number_format($order->total, 2) }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @php $ps = strtolower($order->payment_status); @endphp
                                @if($ps === 'paid') <span class="inline-flex px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-wider">Paid</span>
                                @elseif($ps === 'failed') <span class="inline-flex px-2.5 py-1 rounded-md bg-red-50 text-red-700 text-[10px] font-black uppercase tracking-wider">Failed</span>
                                @else <span class="inline-flex px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 text-[10px] font-black uppercase tracking-wider">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="px-6 py-12 text-center text-gray-400 text-sm">No paid orders found for this range.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator && $orders->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-sm text-gray-500">Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }} orders</div>
                <div class="flex items-center gap-1">
                    @if($orders->onFirstPage()) <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-50 rounded-lg border border-gray-100 cursor-not-allowed">&laquo;</span>
                    @else <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white hover:bg-indigo-50 hover:text-indigo-600 rounded-lg border border-gray-200 transition-all">&laquo;</a>
                    @endif
                    @foreach($orders->getUrlRange(max(1,$orders->currentPage()-2), min($orders->lastPage(),$orders->currentPage()+2)) as $page => $url)
                        @if($page === $orders->currentPage()) <span class="px-3 py-1.5 text-sm font-bold text-white bg-indigo-600 rounded-lg">{{ $page }}</span>
                        @else <a href="{{ $url }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white hover:bg-indigo-50 hover:text-indigo-600 rounded-lg border border-gray-200 transition-all">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($orders->hasMorePages()) <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white hover:bg-indigo-50 hover:text-indigo-600 rounded-lg border border-gray-200 transition-all">&raquo;</a>
                    @else <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-50 rounded-lg border border-gray-100 cursor-not-allowed">&raquo;</span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- ── 3. Coupons ── --}}
        <div x-show="activeTab === 'coupons'" x-transition.duration.200ms
            class="bg-white rounded-b-2xl border border-t-0 border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto max-h-[520px] overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white shadow-[0_1px_0_0_rgba(243,244,246,1)]">
                        <tr class="border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="px-5 py-4 w-10">
                                <input type="checkbox" id="couponSelectAll" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            </th>
                            <th class="px-5 py-4">Coupon Code</th>
                            <th class="px-5 py-4">Type</th>
                            <th class="px-5 py-4 text-right">Value</th>
                            <th class="px-5 py-4 text-center">Times Used</th>
                            <th class="px-5 py-4 text-right">Total Discount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($couponStats as $cStat)
                        <tr class="hover:bg-gray-50/50 transition-colors coupon-row">
                            <td class="px-5 py-3.5">
                                <input type="checkbox" class="coupon-checkbox w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                    value="{{ $cStat->code }}">
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded font-black text-xs uppercase tracking-wider border border-amber-100">{{ $cStat->code }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-sm text-gray-600 capitalize font-medium">{{ $cStat->type ?? 'N/A' }}</td>
                            <td class="px-5 py-3.5 text-sm text-right font-medium text-gray-700">
                                @if($cStat->type === 'percentage') {{ number_format($cStat->value, 0) }}%
                                @elseif($cStat->type === 'fixed') &euro;{{ number_format($cStat->value, 2) }}
                                @else - @endif
                            </td>
                            <td class="px-5 py-3.5 text-sm text-gray-600 text-center font-bold">{{ $cStat->times_used }}</td>
                            <td class="px-5 py-3.5 text-sm font-extrabold text-red-600 text-right">&euro;{{ number_format($cStat->total_discount_given ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">No coupon usage statistics available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Floating Selection Bar ── --}}
    <div id="selBar" class="hidden fixed bottom-5 left-1/2 -translate-x-1/2 z-40 flex items-center gap-3 px-4 py-2 rounded-2xl shadow-2xl"
        style="background:rgba(17,24,39,0.78);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.12);">
        <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="text-xs font-semibold text-white/90 whitespace-nowrap" id="selBarCount">0 selected</span>
        <div class="w-px h-4 bg-white/20"></div>
        <button onclick="doExport('excel','selected')" class="flex items-center gap-1.5 text-xs font-semibold text-white/80 hover:text-white border border-white/20 hover:border-white/40 hover:bg-white/10 px-2.5 py-1 rounded-lg transition-all">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Excel
        </button>
        <button onclick="doExport('csv','selected')" class="flex items-center gap-1.5 text-xs font-semibold text-white/80 hover:text-white border border-white/20 hover:border-white/40 hover:bg-white/10 px-2.5 py-1 rounded-lg transition-all">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> CSV
        </button>
        <div class="w-px h-4 bg-white/20"></div>
        <button onclick="clearAllSelections()" class="text-xs text-white/40 hover:text-white/80 transition-colors px-1">&times;</button>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
// ── Revenue bar animation ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Animate bars after slight delay
    setTimeout(() => {
        document.querySelectorAll('.rev-bar-fill').forEach(bar => {
            bar.style.width = bar.dataset.target + '%';
        });
        // Animate value counters
        document.querySelectorAll('.rev-bar-value').forEach(el => {
            const target = parseFloat(el.dataset.value) || 0;
            const formatted = el.dataset.formatted;
            let start = 0;
            const duration = 1200;
            const step = 16;
            const steps = duration / step;
            const inc = target / steps;
            let current = 0;
            const timer = setInterval(() => {
                current += inc;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                    el.textContent = formatted;
                    return;
                }
                el.textContent = '€' + current.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }, step);
        });
    }, 200);

    // ── Checkbox wiring ──────────────────────────────────────────────────────
    wireCheckboxGroup('prodSelectAll',   '.prod-checkbox');
    wireCheckboxGroup('revSelectAll',    '.rev-checkbox');
    wireCheckboxGroup('couponSelectAll', '.coupon-checkbox');

    // Search
    const search = document.getElementById('revenueSearch');
    if (search) {
        search.addEventListener('input', () => {
            const q = search.value.toLowerCase();
            document.querySelectorAll('.rev-order-row').forEach(r => {
                r.style.display = (r.dataset.name || '').includes(q) ? '' : 'none';
            });
        });
    }
});

function wireCheckboxGroup(selectAllId, itemSelector) {
    const sa = document.getElementById(selectAllId);
    if (!sa) return;
    sa.addEventListener('change', () => {
        document.querySelectorAll(itemSelector).forEach(cb => cb.checked = sa.checked);
        updateSelBar();
    });
    document.querySelectorAll(itemSelector).forEach(cb => {
        cb.addEventListener('change', () => {
            const all = document.querySelectorAll(itemSelector);
            const checked = document.querySelectorAll(itemSelector + ':checked');
            sa.checked = all.length === checked.length;
            updateSelBar();
        });
    });
}

// ── Selection bar ───────────────────────────────────────────────────────────
function updateSelBar() {
    const n = document.querySelectorAll('.prod-checkbox:checked, .rev-checkbox:checked, .coupon-checkbox:checked').length;
    const bar = document.getElementById('selBar');
    document.getElementById('selBarCount').textContent = n + (n === 1 ? ' selected' : ' selected');
    bar.classList.toggle('hidden', n === 0);
}

function clearAllSelections() {
    document.querySelectorAll('.prod-checkbox, .rev-checkbox, .coupon-checkbox').forEach(cb => cb.checked = false);
    ['prodSelectAll','revSelectAll','couponSelectAll'].forEach(id => {
        const el = document.getElementById(id); if (el) el.checked = false;
    });
    updateSelBar();
}

// ── Detect active tab (Alpine) ───────────────────────────────────────────────
function getActiveTab() {
    // Read Alpine's active tab from the tab buttons
    const active = document.querySelector('[x-data]');
    if (!active) return 'orders';
    // Fallback: check which panel is visible
    if (!document.querySelector('[x-show="activeTab === \'products\'"]')?.hidden) return 'products';
    if (!document.querySelector('[x-show="activeTab === \'coupons\'"]')?.hidden) return 'coupons';
    return 'orders';
}

// ── Export dispatcher ────────────────────────────────────────────────────────
function exportActiveTab(fmt, mode) { doExport(fmt, mode); }

function doExport(fmt, mode) {
    // Determine which rows to export based on active tab and mode
    let rows = [], headers = [], filename = 'report';

    // Try to detect active tab by visible panel
    const prodPanel = document.querySelector('[data-tab="products"]') ||
                      [...document.querySelectorAll('[x-show]')].find(el => !el.hidden && el.querySelector('.prod-row'));
    const couponPanel = [...document.querySelectorAll('[x-show]')].find(el => !el.hidden && el.querySelector('.coupon-row'));

    const hasProdChecked   = document.querySelectorAll('.prod-checkbox:checked').length > 0;
    const hasRevChecked    = document.querySelectorAll('.rev-checkbox:checked').length > 0;
    const hasCouponChecked = document.querySelectorAll('.coupon-checkbox:checked').length > 0;

    // Decide data source
    if (mode === 'selected') {
        if (hasProdChecked) {
            headers = ['Rank', 'Product Name', 'Qty Sold', 'Revenue'];
            filename = 'products_report';
            document.querySelectorAll('.prod-checkbox:checked').forEach(cb => {
                const tr = cb.closest('tr');
                const cells = tr.cells;
                rows.push([cells[1].innerText.trim(), cells[2].innerText.trim(), cells[3].innerText.trim(), cells[4].innerText.trim()]);
            });
        } else if (hasRevChecked) {
            headers = ['Order #','Date','Customer','Email','Item Price','Shipping','Discount','Total','Status'];
            filename = 'orders_report';
            document.querySelectorAll('.rev-checkbox:checked').forEach(cb => {
                const tr = cb.closest('tr');
                const cells = tr.cells;
                rows.push([cells[1].innerText.trim(), cells[2].innerText.replace('\n',' ').trim(),
                    cells[3].querySelector('.font-semibold')?.innerText.trim()||'',
                    cells[3].querySelector('.text-xs')?.innerText.trim()||'',
                    cells[4].innerText.trim(), cells[5].innerText.trim(), cells[6].innerText.trim(),
                    cells[7].innerText.trim(), cells[8].innerText.trim()]);
            });
        } else if (hasCouponChecked) {
            headers = ['Coupon Code','Type','Value','Times Used','Total Discount'];
            filename = 'coupons_report';
            document.querySelectorAll('.coupon-checkbox:checked').forEach(cb => {
                const tr = cb.closest('tr');
                const cells = tr.cells;
                rows.push([cells[1].innerText.trim(), cells[2].innerText.trim(), cells[3].innerText.trim(), cells[4].innerText.trim(), cells[5].innerText.trim()]);
            });
        } else {
            Swal.fire('Info','No rows selected across any table.','info'); return;
        }
    } else {
        // all: export whichever table has checked items, or all products by default
        // Try products first
        const prodRows = document.querySelectorAll('.prod-row');
        const revRows  = document.querySelectorAll('.rev-order-row');
        const couponRows = document.querySelectorAll('.coupon-row');

        if (prodRows.length > 0) {
            headers = ['Rank', 'Product Name', 'Qty Sold', 'Revenue'];
            filename = 'products_report';
            prodRows.forEach(tr => {
                const cells = tr.cells;
                rows.push([cells[1].innerText.trim(), cells[2].innerText.trim(), cells[3].innerText.trim(), cells[4].innerText.trim()]);
            });
        }
        if (revRows.length > 0 && rows.length === 0) {
            headers = ['Order #','Date','Customer','Email','Item Price','Shipping','Discount','Total','Status'];
            filename = 'orders_report';
            revRows.forEach(tr => {
                const cells = tr.cells;
                rows.push([cells[1].innerText.trim(), cells[2].innerText.replace('\n',' ').trim(),
                    cells[3].querySelector('.font-semibold')?.innerText.trim()||'',
                    cells[3].querySelector('.text-xs')?.innerText.trim()||'',
                    cells[4].innerText.trim(), cells[5].innerText.trim(), cells[6].innerText.trim(),
                    cells[7].innerText.trim(), cells[8].innerText.trim()]);
            });
        }
        // Actually – for "all" mode, export the currently visible tab
        // Re-detect by checking which tab panel is visible
        if (document.querySelector('#revSelectAll') && getVisibleSelector('.rev-order-row')) {
            headers = ['Order #','Date','Customer','Email','Item Price','Shipping','Discount','Total','Status'];
            filename = 'orders_report'; rows = [];
            document.querySelectorAll('.rev-order-row').forEach(tr => {
                if (tr.style.display === 'none') return;
                const cells = tr.cells;
                rows.push([cells[1].innerText.trim(), cells[2].innerText.replace('\n',' ').trim(),
                    cells[3].querySelector('.font-semibold')?.innerText.trim()||'',
                    cells[3].querySelector('.text-xs')?.innerText.trim()||'',
                    cells[4].innerText.trim(), cells[5].innerText.trim(), cells[6].innerText.trim(),
                    cells[7].innerText.trim(), cells[8].innerText.trim()]);
            });
        }
    }

    if (!rows.length) { Swal.fire('Info','No data to export.','info'); return; }

    const data = [headers, ...rows];
    const dateStr = new Date().toISOString().slice(0,10);

    if (fmt === 'excel') {
        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Report');
        XLSX.writeFile(wb, `${filename}_${dateStr}.xlsx`);
    } else {
        const csv = "\uFEFF" + data.map(r => r.map(v => {
            const t = String(v);
            return (t.includes(',') || t.includes('"') || t.includes('\n')) ? '"' + t.replace(/"/g,'""') + '"' : t;
        }).join(',')).join('\n');
        const link = document.createElement('a');
        link.href = URL.createObjectURL(new Blob([csv], {type:'text/csv;charset=utf-8;'}));
        link.download = `${filename}_${dateStr}.csv`;
        link.style.display = 'none';
        document.body.appendChild(link); link.click(); document.body.removeChild(link);
    }
}

function getVisibleSelector(sel) {
    const el = document.querySelector(sel);
    return el && el.offsetParent !== null;
}
</script>
@endpush
