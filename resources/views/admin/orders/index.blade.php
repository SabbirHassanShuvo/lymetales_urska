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
                <p class="text-2xl font-bold text-gray-800">{{ $orders->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Pending</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $orders->where('order_status', 'pending')->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Delivered</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $orders->where('order_status', 'delivered')->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Total Revenue</h3>
                <p class="text-2xl font-bold text-gray-800">&euro;{{ number_format($orders->sum('total'), 2) }}</p>
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
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg z-50 overflow-hidden">
                    <button type="button" @click="exportOrdersToExcel(); open = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors flex items-center font-semibold">
                        Excel Spreadsheet
                    </button>
                    <button type="button" @click="exportOrdersToPDF(); open = false" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors flex items-center font-semibold">
                        PDF Document
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="ordersTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase border-b border-gray-100">
                        <th class="px-5 py-4">Order #</th>
                        <th class="px-5 py-4">Customer</th>
                        <th class="px-5 py-4">Payment</th>
                        <th class="px-5 py-4">Order Status</th>
                        <th class="px-5 py-4">Payment Status</th>
                        <th class="px-5 py-4">Total</th>
                        <th class="px-5 py-4">Date</th>
                        <th class="px-5 py-4 text-center">Receipt</th>
                        <th class="px-5 py-4 text-center">Preview</th>
                        <th class="px-5 py-4 text-center">Delete</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors order-row"
                            data-name="{{ strtolower($order->order_number . ' ' . $order->full_name . ' ' . $order->email) }}">
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

                            {{-- Receipt button --}}
                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('admin.orders.receipt', $order) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 text-xs font-semibold rounded-lg transition-all"
                                   title="Download Receipt">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Receipt
                                </a>
                            </td>

                            {{-- Preview button --}}
                            <td class="px-5 py-4 text-center">
                                @php
                                    $previewImage = null;
                                    $orderItems = is_array($order->items) ? $order->items : json_decode($order->items, true);
                                    foreach ($orderItems ?? [] as $oi) {
                                        if (!empty($oi['personalisation']['preview_image'])) {
                                            $previewImage = $oi['personalisation']['preview_image'];
                                            break;
                                        }
                                    }

                                    $canPreview = $order->payment_status === 'paid';
                                @endphp
                                @if($previewImage)
                                    @if($canPreview)
                                        <button onclick="openPreviewModal('{{ $previewImage }}', '{{ $order->order_number }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Preview
                                        </button>
                                    @else
                                        <button disabled title="Payment must be completed (Paid status) to preview image"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-400 text-xs font-semibold rounded-lg cursor-not-allowed">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Preview
                                        </button>
                                    @endif
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
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
        </div>
        <div id="tablePagination" class="px-5 py-4 border-t border-gray-100"></div>
    </div>
</div>

<!-- Image Preview Modal — full viewport, image fills the space -->
<div id="imagePreviewModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-85" onclick="closePreviewModal()"></div>

    <!-- Panel -->
    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 py-6">

        <!-- Top bar: order number + close -->
        <div class="w-full max-w-2xl flex items-center justify-between mb-3">
            <span id="pvOrderNumber" class="font-mono text-sm font-semibold text-white/80"></span>
            <button onclick="closePreviewModal()" class="text-white/70 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Image — max height fills screen, natural aspect ratio preserved -->
        <div class="w-full max-w-2xl flex-1 flex items-center justify-center">
            <img id="pvImage" src="" alt="Book Preview"
                 class="max-w-full rounded-xl shadow-2xl border border-white/10"
                 style="max-height: calc(100vh - 160px); object-fit: contain;">
        </div>

        <!-- Download buttons -->
        <div class="w-full max-w-2xl mt-4 flex gap-3">
            <button onclick="downloadPreviewAsPdf()"
                class="flex-1 flex items-center justify-center gap-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition-all shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
                Download as PDF
            </button>
            <a id="pvDownloadImageBtn" href="#" download="preview.png"
                class="flex-1 flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl transition-all shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download Image
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';

    // ── Image Preview Modal ────────────────────────────────────────────────

    let _pvCurrentSrc   = '';
    let _pvCurrentOrder = '';

    function openPreviewModal(imagePath, orderNumber) {
        const src = imagePath.startsWith('http')
            ? imagePath
            : window.location.origin + '/' + imagePath.replace(/^\//, '');

        _pvCurrentSrc   = src;
        _pvCurrentOrder = orderNumber;

        document.getElementById('pvImage').src = src;
        document.getElementById('pvOrderNumber').textContent = orderNumber;
        document.getElementById('pvDownloadImageBtn').href = src;
        document.getElementById('pvDownloadImageBtn').download = `personalisation-${orderNumber}.png`;
        document.getElementById('imagePreviewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePreviewModal() {
        document.getElementById('imagePreviewModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close on Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closePreviewModal();
    });

    function downloadPreviewAsPdf() {
        const img = document.getElementById('pvImage');

        const generate = () => {
            const { jsPDF } = window.jspdf;

            const natW = img.naturalWidth  || img.width  || 600;
            const natH = img.naturalHeight || img.height || 800;

            // Use image's own aspect ratio for PDF page size (in mm)
            const pageW = 210;
            const pageH = Math.round((natH / natW) * pageW);

            const pdf = new jsPDF({ orientation: pageH > pageW ? 'portrait' : 'landscape', unit: 'mm', format: [pageW, pageH] });
            pdf.addImage(_pvCurrentSrc, 'JPEG', 0, 0, pageW, pageH);
            pdf.save(`preview-${_pvCurrentOrder}.pdf`);
        };

        if (img.complete && img.naturalWidth > 0) {
            generate();
        } else {
            img.onload = generate;
        }
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

    // ── Table pagination & search ───────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        new TableHelper('#ordersTable', '#searchInput', '#tablePagination', 10);
    });

    document.querySelectorAll('select[data-type]').forEach(select => {
        select.dataset.previous = select.value;
    });

    // ── Table Data Exports ─────────────────────────────────────────────────
    function getOrdersData() {
        const table = document.getElementById('ordersTable');
        if (!table) return [];
        
        const headers = ["Order #", "Customer Name", "Customer Email", "Payment Method", "Order Status", "Payment Status", "Total", "Date"];
        const rows = Array.from(table.querySelectorAll('tbody tr')).filter(row => !row.classList.contains('no-results-row'));
        const data = [headers];
        
        rows.forEach(row => {
            if (row.style.display === 'none') return;

            const cells = row.cells;
            if (cells.length < 7) return;

            const orderNumber = cells[0].innerText.trim();
            const name = cells[1].querySelector('div:nth-child(1)')?.innerText.trim() || '';
            const email = cells[1].querySelector('div:nth-child(2)')?.innerText.trim() || '';
            const paymentMethod = cells[2].innerText.trim();
            
            const orderStatusSelect = cells[3].querySelector('select');
            const orderStatus = orderStatusSelect ? orderStatusSelect.value : cells[3].innerText.trim();
            
            const paymentStatusSelect = cells[4].querySelector('select');
            let paymentStatus = '';
            if (paymentStatusSelect) {
                paymentStatus = paymentStatusSelect.value;
            } else {
                paymentStatus = cells[4].innerText.trim();
            }
            
            const total = cells[5].innerText.trim();
            const date = cells[6].innerText.replace('\n', ' ').trim();
            
            data.push([orderNumber, name, email, paymentMethod, orderStatus, paymentStatus, total, date]);
        });
        return data;
    }

    function exportOrdersToExcel() {
        const data = getOrdersData();
        if (data.length <= 1) {
            Swal.fire('Info', 'No data to export.', 'info');
            return;
        }
        const worksheet = XLSX.utils.aoa_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Orders");
        XLSX.writeFile(workbook, `orders_report_${new Date().toISOString().slice(0,10)}.xlsx`);
    }

    function exportOrdersToPDF() {
        const data = getOrdersData();
        if (data.length <= 1) {
            Swal.fire('Info', 'No data to export.', 'info');
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
</script>
@endpush
@endsection
