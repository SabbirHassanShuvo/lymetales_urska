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
                <h3 class="text-sm font-semibold text-gray-500">Total Offers</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $offers->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Active Offers</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $offers->where('is_active', true)->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Inactive Offers</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $offers->where('is_active', false)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Section Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Offers & Quantity Discounts</h2>
            <p class="text-sm text-gray-500">Manage campaign quantity-based offers and store-wide cart discount thresholds.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search offers..." class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-52 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button onclick="toggleModal('offerModal')" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Offer
            </button>
        </div>
    </div>

    <!-- Offers Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase border-b border-gray-100">
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Min Quantity Threshold</th>
                        <th class="px-6 py-4">Discount Rate</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($offers as $offer)
                        <tr class="hover:bg-gray-50/50 transition-colors offer-row" data-title="{{ strtolower($offer->title) }} {{ strtolower($offer->short_description) }}">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $offer->title }}</div>
                                @if($offer->short_description)
                                    <div class="text-xs text-gray-500 mt-1">{{ $offer->short_description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                Buy strictly more than <span class="font-bold text-indigo-700 bg-indigo-50 px-2 py-1 rounded-md">{{ $offer->min_quantity }}</span> items
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800 text-base">
                                {{ number_format($offer->discount_percentage, 0) }}% Off
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <button onclick="toggleOfferStatus({{ $offer->id }}, {{ $offer->is_active ? 'true' : 'false' }})" class="inline-flex items-center text-xs font-bold transition-colors w-fit px-2 py-1 rounded-lg {{ $offer->is_active ? 'text-green-700 bg-green-50 hover:bg-green-100' : 'text-gray-400 bg-gray-100 hover:bg-gray-200' }}">
                                        <span class="w-1.5 h-1.5 {{ $offer->is_active ? 'bg-green-500' : 'bg-gray-300' }} rounded-full mr-1.5"></span>
                                        {{ $offer->is_active ? 'Enabled' : 'Disabled' }}
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="editOffer({{ json_encode($offer) }})" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="confirmDelete('{{ route('admin.offers.destroy', $offer->id) }}')" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="text-gray-300 mb-3">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm-2 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-700">No Offers Found</h4>
                                <p class="text-gray-500 text-sm mt-1">Get started by creating your very first quantity-based offer.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Offer Modal -->
<div id="offerModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="toggleModal('offerModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="{{ route('admin.offers.store') }}" method="POST" id="offerForm">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Create Quantity Offer</h3>
                        <button type="button" onclick="toggleModal('offerModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Offer Title *</label>
                        <input type="text" name="title" id="offTitle" required placeholder="e.g. Buy more than 2 books and get 20% discount"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Short Description</label>
                        <textarea name="short_description" id="offShortDesc" placeholder="e.g. Get 20% off on your entire cart when you purchase 3 or more products." rows="2"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Min Quantity Threshold *</label>
                            <input type="number" name="min_quantity" id="offMinQty" required min="1" placeholder="2"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-semibold">
                            <span class="text-[10px] text-gray-400">Triggers for values strictly greater than this</span>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Discount Percentage (%) *</label>
                            <input type="number" step="0.01" name="discount_percentage" id="offDiscount" required min="0" max="100" placeholder="20.00"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm font-semibold">
                        </div>
                    </div>

                    <label class="flex items-center space-x-3 cursor-pointer pt-1">
                        <input type="checkbox" name="is_active" id="offStatus" value="1" checked
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                </div>

                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="toggleModal('offerModal')"
                        class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                        Submit
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
            if (modalId === 'offerModal') resetOfferForm();
        }
    }

    function resetOfferForm() {
        document.getElementById('offerForm').action = "{{ route('admin.offers.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('modalTitle').textContent = "Create Quantity Offer";
        document.getElementById('offTitle').value = '';
        document.getElementById('offShortDesc').value = '';
        document.getElementById('offMinQty').value = '2';
        document.getElementById('offDiscount').value = '20.00';
        document.getElementById('offStatus').checked = true;
    }

    function editOffer(offer) {
        document.getElementById('offerForm').action = '/admin/offers/' + offer.id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('modalTitle').textContent = "Edit Quantity Offer";

        document.getElementById('offTitle').value     = offer.title;
        document.getElementById('offShortDesc').value = offer.short_description || '';
        document.getElementById('offMinQty').value    = offer.min_quantity;
        document.getElementById('offDiscount').value  = offer.discount_percentage;
        document.getElementById('offStatus').checked  = offer.is_active == 1;

        toggleModal('offerModal');
    }

    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This offer will be permanently deleted.',
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

    function toggleOfferStatus(offerId, currentStatus) {
        const actionText = currentStatus ? "disable" : "enable";
        const url = `/admin/offers/${offerId}/status`;

        Swal.fire({
            title: `Want to ${actionText} offer?`,
            text: `This will ${actionText} the active status of the offer.`,
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

    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.offer-row');
        rows.forEach(row => {
            const title = row.getAttribute('data-title');
            if (title.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
