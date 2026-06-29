@extends('layouts.admin', ['title' => 'Gifts Management'])

@section('content')
<div class="space-y-8">
    <!-- Header Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <!-- SVG Gift Icon -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Total Gifts</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $gifts->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Average Gift Price</h3>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($gifts->avg('price') ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Section Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gifts Management</h2>
            <p class="text-sm text-gray-500">Add, edit, update and delete products listed in the Gifts section.</p>
        </div>
        <div class="flex items-center space-x-3">
            {{-- Language Filter --}}
            <div class="flex items-center space-x-2 bg-white px-3 py-2 border border-gray-200 rounded-xl">
                <label class="text-xs font-semibold text-gray-500">Language:</label>
                <select onchange="window.location.href='?lang=' + this.value" class="text-xs font-bold text-gray-700 bg-gray-50 border border-gray-200 rounded-lg py-1.5 px-2.5 outline-none cursor-pointer focus:ring-1 focus:ring-indigo-500">
                    <option value="SL" {{ (isset($lang) && $lang === 'SL') ? 'selected' : '' }}>SL (Slovenian)</option>
                    <option value="HR" {{ (isset($lang) && $lang === 'HR') ? 'selected' : '' }}>HR (Croatian)</option>
                    <option value="EN" {{ (isset($lang) && $lang === 'EN') ? 'selected' : '' }}>EN (English)</option>
                </select>
            </div>

            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search gifts..." class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-52 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <button onclick="toggleModal('giftModal')" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Gift
            </button>
        </div>
    </div>

    <!-- Gifts Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table id="giftsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase border-b border-gray-100">
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Language</th>
                        <th class="px-6 py-4">Short Description</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($gifts as $gift)
                        <tr class="hover:bg-gray-50/50 transition-colors gift-row" data-title="{{ strtolower($gift->title) }}">
                            <td class="px-6 py-4">
                                @if($gift->image_path)
                                    <img src="{{ asset($gift->image_path) }}" alt="{{ $gift->title }}" class="w-14 h-14 object-cover rounded-xl border border-gray-100">
                                @else
                                    <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 border border-gray-50 text-xs">
                                        No Image
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ $gift->title }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $gift->language_type ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate text-gray-500">
                                {{ $gift->short_description ?? '—' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800 text-base">
                                ${{ number_format($gift->price, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="editGift({{ json_encode($gift) }})" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="confirmDelete('{{ route('admin.gifts.destroy', $gift->id) }}')" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="text-gray-300 mb-3">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-700">No Gifts Found</h4>
                                <p class="text-gray-500 text-sm mt-1">Get started by adding your very first gift item.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div id="tablePagination" class="px-6 py-4 border-t border-gray-100"></div>
    </div>
</div>

<!-- Add/Edit Gift Modal -->
<div id="giftModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="toggleModal('giftModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="{{ route('admin.gifts.store') }}" method="POST" id="giftForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Add New Gift</h3>
                        <button type="button" onclick="toggleModal('giftModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                        <input type="text" name="title" id="giftTitle" required placeholder="e.g. Birthday Magic Basket"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Price ($) *</label>
                        <input type="number" step="0.01" name="price" id="giftPrice" required placeholder="0.00"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Short Description</label>
                        <textarea name="short_description" id="giftDescription" placeholder="Provide a brief description of the gift" rows="3"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Language</label>
                        <select name="language_type" id="giftLanguage" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                            <option value="SL" {{ (isset($lang) && $lang === 'SL') ? 'selected' : '' }}>SL (Slovenian)</option>
                            <option value="HR" {{ (isset($lang) && $lang === 'HR') ? 'selected' : '' }}>HR (Croatian)</option>
                            <option value="EN" {{ (isset($lang) && $lang === 'EN') ? 'selected' : '' }}>EN (English)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gift Image</label>
                        <input type="file" name="image" id="giftImage" accept="image/*" onchange="previewImage(event)"
                            class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                        
                        <!-- Image Preview Container -->
                        <div id="imagePreviewContainer" class="hidden mt-4">
                            <p class="text-xs text-gray-500 mb-1.5 font-medium">Image Preview:</p>
                            <img id="imagePreview" src="" alt="Selected Image" class="w-32 h-32 object-cover rounded-xl border border-gray-200 shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="toggleModal('giftModal')"
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
            if (modalId === 'giftModal') resetGiftForm();
        }
    }

    function resetGiftForm() {
        document.getElementById('giftForm').action = "{{ route('admin.gifts.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('modalTitle').textContent = 'Add New Gift';
        document.getElementById('giftTitle').value = '';
        document.getElementById('giftPrice').value = '';
        document.getElementById('giftDescription').value = '';
        document.getElementById('giftLanguage').value = 'SL';
        document.getElementById('giftImage').value = '';
        document.getElementById('imagePreviewContainer').classList.add('hidden');
        document.getElementById('imagePreview').src = '';
    }

    function previewImage(event) {
        const input = event.target;
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function editGift(gift) {
        document.getElementById('giftForm').action = '/admin/gifts/' + gift.id;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('modalTitle').textContent = 'Edit Gift Details';

        document.getElementById('giftTitle').value       = gift.title;
        document.getElementById('giftPrice').value       = gift.price;
        document.getElementById('giftDescription').value = gift.short_description || '';
        document.getElementById('giftLanguage').value    = gift.language_type || 'SL';

        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg       = document.getElementById('imagePreview');

        if (gift.image_path) {
            previewImg.src = '/' + gift.image_path;
            previewContainer.classList.remove('hidden');
        } else {
            previewImg.src = '';
            previewContainer.classList.add('hidden');
        }

        toggleModal('giftModal');
    }

    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This gift item will be permanently deleted.',
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

    document.addEventListener('DOMContentLoaded', () => {
        new TableHelper('#giftsTable', '#searchInput', '#tablePagination', 10);
    });
</script>
@endsection
