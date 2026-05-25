@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Total Orders</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $orders->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Pending</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $orders->where('order_status', 'pending')->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Delivered</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $orders->where('order_status', 'delivered')->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
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
        <div class="relative">
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search orders..."
                class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase border-b border-gray-100">
                        <th class="px-5 py-4">Order #</th>
                        <th class="px-5 py-4">Customer</th>
                        <th class="px-5 py-4">Payment</th>
                        <th class="px-5 py-4">Order Status</th>
                        <th class="px-5 py-4">Payment Status</th>
                        <th class="px-5 py-4">Total</th>
                        <th class="px-5 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors order-row"
                            data-search="{{ strtolower($order->order_number . ' ' . $order->full_name . ' ' . $order->email) }}">
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
                                @if($order->payment_method === 'stripe')
                                    {{-- Stripe: read-only, webhook-controlled --}}
                                    @php
                                        $psColors = [
                                            'pending' => 'bg-amber-50 text-amber-700',
                                            'paid'    => 'bg-green-50 text-green-700',
                                            'failed'  => 'bg-red-50 text-red-700',
                                        ];
                                        $psColor = $psColors[$order->payment_status] ?? 'bg-gray-50 text-gray-600';
                                    @endphp
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-xs font-bold px-2.5 py-1.5 rounded-lg {{ $psColor }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                        <span title="Stripe payment status is webhook-controlled" class="text-gray-400 cursor-help">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </span>
                                    </div>
                                @else
                                    {{-- COD: editable --}}
                                    <select
                                        class="payment-status-select text-xs font-semibold border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer transition-colors"
                                        data-order-id="{{ $order->id }}"
                                        data-type="payment-status"
                                        onchange="updateStatus(this, 'payment-status', {{ $order->id }})">
                                        @foreach(['pending', 'paid', 'failed'] as $status)
                                            <option value="{{ $status }}" {{ $order->payment_status === $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <div class="text-gray-300 mb-3">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-700">No Orders Yet</h4>
                                <p class="text-gray-500 text-sm mt-1">Orders will appear here once customers start placing them.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';

    /**
     * Called when a status dropdown changes.
     * @param {HTMLSelectElement} select
     * @param {'order-status'|'payment-status'} type
     * @param {number} orderId
     */
    function updateStatus(select, type, orderId) {
        const newValue = select.value;
        const previousValue = select.dataset.previous ?? select.querySelector('option[selected]')?.value ?? newValue;

        // Store previous value for rollback on error
        select.dataset.previous = previousValue;

        const url = `/admin/orders/${orderId}/${type}`;
        const bodyKey = type === 'order-status' ? 'order_status' : 'payment_status';

        select.disabled = true;

        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ [bodyKey]: newValue }),
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok || !data.success) {
                throw new Error(data.message ?? 'Update failed.');
            }
            return data;
        })
        .then(data => {
            select.dataset.previous = newValue;
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: `Status changed to "${newValue}".`,
                timer: 1500,
                showConfirmButton: false,
                customClass: { popup: 'rounded-2xl shadow-xl' },
            });
        })
        .catch(err => {
            // Roll back the dropdown to the previous value
            select.value = previousValue;
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: err.message,
                customClass: { popup: 'rounded-2xl shadow-xl' },
            });
        })
        .finally(() => {
            select.disabled = false;
        });
    }

    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('.order-row').forEach(row => {
            const searchData = row.getAttribute('data-search') ?? '';
            row.style.display = searchData.includes(query) ? '' : 'none';
        });
    }

    // Initialise previous-value tracking on all selects
    document.querySelectorAll('select[data-type]').forEach(select => {
        select.dataset.previous = select.value;
    });
</script>
@endpush
@endsection
