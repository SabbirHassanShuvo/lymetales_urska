@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm-2 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Total Coupons</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $coupons->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Active & Valid</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $coupons->filter(fn($c) => $c->isValid())->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Expired / Inactive</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $coupons->filter(fn($c) => !$c->isValid())->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Section Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('admin.coupons_management') ?? 'Coupon Codes & Discounts' }}</h2>
            <p class="text-sm text-gray-500">Manage campaign coupon codes, discount rates, and active usage statistics.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search coupons..." class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-52 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button onclick="toggleModal('couponModal')" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('admin.add_new_coupon') ?? 'Add Coupon' }}
            </button>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="couponsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase border-b border-gray-100">
                        <th class="px-6 py-4">Code</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Discount Value</th>
                        <th class="px-6 py-4">Usage Limit</th>
                        <th class="px-6 py-4">Expiry Date</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50/50 transition-colors coupon-row" data-code="{{ strtolower($coupon->code) }}" data-type="{{ strtolower($coupon->type) }}">
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                <span
                                    onclick="copyCoupon('{{ $coupon->code }}')"
                                    title="Click to copy"
                                    class="group inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-700 font-mono px-3 py-1.5 rounded-lg cursor-pointer hover:bg-indigo-100 select-none transition-colors">
                                    {{ $coupon->code }}
                                    <svg class="w-3.5 h-3.5 opacity-40 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </span>
                                @if($coupon->description)
                                    <p class="text-xs text-gray-500 font-normal mt-2">{{ $coupon->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($coupon->type === 'percent')
                                    <span class="bg-purple-100 text-purple-700 text-xs px-2.5 py-1 rounded-md font-bold uppercase">Percentage</span>
                                @elseif($coupon->type === 'free_shipping')
                                    <span class="bg-teal-100 text-teal-700 text-xs px-2.5 py-1 rounded-md font-bold uppercase flex items-center gap-1 w-fit">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l2 7a2 2 0 002 2h6a2 2 0 002-2l2-7"></path></svg>
                                        Free Shipping
                                    </span>
                                @else
                                    <span class="bg-blue-100 text-blue-700 text-xs px-2.5 py-1 rounded-md font-bold uppercase">Fixed Amount</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800 text-base">
                                @if($coupon->type === 'free_shipping')
                                    <span class="text-teal-600 text-sm font-semibold">—</span>
                                @elseif($coupon->type === 'percent')
                                    {{ number_format($coupon->value, 0) }}%
                                @else
                                    ${{ number_format($coupon->value, 2) }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1.5">
                                    <div class="flex items-center justify-between text-xs font-semibold">
                                        <span>{{ $coupon->used_count }} {{ __('admin.used') ?? 'Used' }}</span>
                                        @if($coupon->usage_limit)
                                            <span class="text-gray-400">/ {{ $coupon->usage_limit }} Limit</span>
                                        @else
                                            <span class="text-gray-400">∞</span>
                                        @endif
                                    </div>
                                    @if($coupon->usage_limit)
                                        @php
                                            $percent  = min(100, ($coupon->used_count / $coupon->usage_limit) * 100);
                                            $barColor = $percent >= 100 ? 'bg-red-500' : ($percent >= 80 ? 'bg-amber-500' : 'bg-indigo-500');
                                        @endphp
                                        <div class="w-28 bg-gray-100 h-2 rounded-full overflow-hidden">
                                            <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $percent }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if(is_null($coupon->expiry_date))
                                    <span class="text-gray-400 font-semibold italic">{{ __('admin.no_expiry') ?? 'Never Expires' }}</span>
                                @elseif($coupon->isExpired())
                                    <span class="text-red-600 font-semibold flex items-center text-xs">
                                        <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1.5"></span>
                                        {{ $coupon->expiry_date->format('M d, Y') }} (Expired)
                                    </span>
                                @else
                                    <span class="text-gray-700 font-medium text-xs">{{ $coupon->expiry_date->format('M d, Y') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <button onclick="toggleCouponStatus({{ $coupon->id }}, {{ $coupon->status ? 'true' : 'false' }})" class="inline-flex items-center text-xs font-bold transition-colors w-fit px-2 py-1 rounded-lg {{ $coupon->status ? 'text-green-700 bg-green-50 hover:bg-green-100' : 'text-gray-400 bg-gray-100 hover:bg-gray-200' }}">
                                        <span class="w-1.5 h-1.5 {{ $coupon->status ? 'bg-green-500' : 'bg-gray-300' }} rounded-full mr-1.5"></span>
                                        {{ $coupon->status ? 'Enabled' : 'Disabled' }}
                                    </button>
                                    @if(!$coupon->isValid())
                                        <span class="text-[10px] text-red-500 font-semibold uppercase">
                                            @if(!$coupon->status) (Disabled)
                                            @elseif($coupon->isExpired()) (Expired)
                                            @elseif(!is_null($coupon->usage_limit) && $coupon->used_count >= $coupon->usage_limit) (Limit Reached)
                                            @else (Inactive)
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-[10px] text-green-600 font-medium uppercase">(Valid)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="editCoupon({{ json_encode($coupon) }})" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="confirmDelete('{{ route('admin.coupons.destroy', $coupon->id) }}')" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <div class="text-gray-300 mb-3">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm-2 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-700">{{ __('admin.no_coupons_yet') ?? 'No Coupons Found' }}</h4>
                                <p class="text-gray-500 text-sm mt-1">Get started by creating your very first promotion coupon code.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
        </div>
        <div id="tablePagination" class="px-6 py-4 border-t border-gray-100"></div>
    </div>
</div>

<!-- Add/Edit Coupon Modal -->
<div id="couponModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="toggleModal('couponModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800" id="modalTitle">{{ __('admin.create_coupon') ?? 'Create Coupon Code' }}</h3>
                        <button type="button" onclick="toggleModal('couponModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.coupon_code') ?? 'Coupon Code' }} *</label>
                        <div class="flex space-x-2">
                            <input type="text" name="code" id="coupCode" required placeholder="SUMMER50"
                                class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-mono uppercase text-sm">
                            <button type="button" onclick="generateCouponCode()"
                                class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm border border-gray-200 transition-all flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3 3L22 4"/>
                                </svg>
                                Generate
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin.discount_type') ?? 'Discount Type' }} *</label>
                            <select name="type" id="coupType" required onchange="handleCouponTypeChange()"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                                <option value="fixed">Fixed Amount ($)</option>
                                <option value="percent">Percentage (%)</option>
                                <option value="free_shipping">🚚 Free Shipping</option>
                            </select>
                        </div>
                        <div id="coupValueGroup">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Discount Value *</label>
                            <input type="number" step="0.01" name="value" id="coupValue" placeholder="50.00"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Usage Limit <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <input type="number" name="usage_limit" id="coupLimit" placeholder="Unlimited"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Expiry Date <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <input type="date" name="expiry_date" id="coupExpiry"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="coupDesc" placeholder="e.g. 50% discount on summer sales" rows="3"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm"></textarea>
                    </div>

                    <label class="flex items-center space-x-3 cursor-pointer pt-1">
                        <input type="checkbox" name="status" id="coupStatus" value="1" checked
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                        <span class="text-sm font-semibold text-gray-700">{{ __('admin.active') ?? 'Active' }}</span>
                    </label>
                </div>

                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="toggleModal('couponModal')"
                        class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl transition-all">
                        {{ __('admin.cancel') ?? 'Cancel' }}
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                        {{ __('admin.submit') ?? 'Submit' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden delete form -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
            if (modalId === 'couponModal') resetCouponForm();
        }
    }

    function resetCouponForm() {
        document.getElementById('couponForm').action = "{{ route('admin.coupons.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('modalTitle').textContent = "{{ __('admin.create_coupon') ?? 'Create Coupon Code' }}";
        document.getElementById('coupCode').value = '';
        document.getElementById('coupType').value = 'fixed';
        document.getElementById('coupValue').value = '';
        document.getElementById('coupValue').required = true;
        document.getElementById('coupValueGroup').classList.remove('opacity-50');
        document.getElementById('coupLimit').value = '';
        document.getElementById('coupExpiry').value = '';
        document.getElementById('coupDesc').value = '';
        document.getElementById('coupStatus').checked = true;
    }

    function handleCouponTypeChange() {
        const type = document.getElementById('coupType').value;
        const valueGroup = document.getElementById('coupValueGroup');
        const valueInput = document.getElementById('coupValue');
        if (type === 'free_shipping') {
            valueInput.required = false;
            valueInput.value = '0';
            valueGroup.classList.add('opacity-50');
            valueInput.setAttribute('disabled', true);
        } else {
            valueInput.required = true;
            valueInput.value = '';
            valueGroup.classList.remove('opacity-50');
            valueInput.removeAttribute('disabled');
        }
    }

    function generateCouponCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = 'URSKA-';
        for (let i = 0; i < 6; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('coupCode').value = code;
    }

    function editCoupon(coupon) {
        document.getElementById('couponForm').action = '/admin/coupons/' + coupon.id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('modalTitle').textContent = "{{ __('admin.edit_coupon') ?? 'Edit Coupon Code' }}";

        document.getElementById('coupCode').value    = coupon.code;
        document.getElementById('coupType').value    = coupon.type;
        document.getElementById('coupLimit').value   = coupon.usage_limit || '';
        document.getElementById('coupDesc').value    = coupon.description || '';
        document.getElementById('coupStatus').checked = coupon.status == 1;

        if (coupon.expiry_date) {
            document.getElementById('coupExpiry').value = coupon.expiry_date.split('T')[0];
        } else {
            document.getElementById('coupExpiry').value = '';
        }

        // Handle free_shipping type
        handleCouponTypeChange();
        if (coupon.type !== 'free_shipping') {
            document.getElementById('coupValue').value = coupon.value;
        }

        toggleModal('couponModal');
    }

    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This coupon will be permanently deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-2xl border border-gray-100 shadow-xl',
                confirmButton: 'rounded-xl font-semibold',
                cancelButton: 'rounded-xl font-semibold',
            }
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = deleteUrl;
                form.submit();
            }
        });
    }

    function toggleCouponStatus(couponId, currentStatus) {
        const actionText = currentStatus ? "disable" : "enable";
        const url = `/admin/coupons/${couponId}/status`;

        Swal.fire({
            title: `Want to ${actionText} coupon?`,
            text: `This will ${actionText} the coupon code.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Yes, do it!',
            background: '#ffffff',
            customClass: {
                popup: 'rounded-2xl border border-gray-100 shadow-xl',
                confirmButton: 'px-5 py-2.5 rounded-xl text-white font-semibold',
                cancelButton: 'px-5 py-2.5 rounded-xl text-white font-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Updated!',
                            text: data.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: { popup: 'rounded-2xl shadow-xl' }
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'An unexpected error occurred.', 'error'));
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        new TableHelper('#couponsTable', '#searchInput', '#tablePagination', 10);
    });

    function copyCoupon(code) {
        // Try modern clipboard API first, fall back to execCommand for HTTP
        const showSuccess = () => {
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: code + ' copied to clipboard.',
                timer: 1500,
                showConfirmButton: false,
                customClass: { popup: 'rounded-2xl shadow-xl' }
            });
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(code).then(showSuccess).catch(() => fallbackCopy(code, showSuccess));
        } else {
            fallbackCopy(code, showSuccess);
        }
    }

    function fallbackCopy(text, onSuccess) {
        const el = document.createElement('textarea');
        el.value = text;
        el.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0';
        document.body.appendChild(el);
        el.focus();
        el.select();
        try {
            document.execCommand('copy');
            onSuccess();
        } catch (e) {
            alert('Copy failed. Please copy manually: ' + text);
        }
        document.body.removeChild(el);
    }
</script>
@endsection