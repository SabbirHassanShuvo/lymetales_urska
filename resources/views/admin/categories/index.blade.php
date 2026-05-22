@extends('layouts.admin')

@section('content')
<div class="space-y-8">

    {{-- ── Summary Cards ─────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">{{ __('admin.parent_categories') ?? 'Parent Categories' }}</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $parentCategories->count() }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-pink-50 text-pink-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">{{ __('admin.subcategories') ?? 'Subcategories' }}</h3>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $parentCategories->sum(fn($p) => $p->subcategories->count()) }}
                </p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.18 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.98 10.12c-.783-.57-.38-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">{{ __('admin.special_categories') ?? 'Special Categories' }}</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $specialCategories->count() }}</p>
            </div>
        </div>
    </div>

    {{-- ── Tab Container ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Tab Header --}}
        <div class="px-8 py-4 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex space-x-1">
                <button onclick="switchTab('parent')" id="tabBtn-parent"
                    class="tab-btn px-4 py-2 text-sm font-bold rounded-lg transition-all focus:outline-none">
                    {{ __('admin.parent_categories') ?? 'Parent Categories' }}
                </button>
                <button onclick="switchTab('sub')" id="tabBtn-sub"
                    class="tab-btn px-4 py-2 text-sm font-medium rounded-lg transition-all focus:outline-none">
                    {{ __('admin.subcategories') ?? 'Subcategories' }}
                </button>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search categories..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm w-56 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="openParentModal()"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Parent
                    </button>
                    <button onclick="openSubCreateModal()"
                        class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Subcategories
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Tab 1: Parent Categories ─────────────────────────────────── --}}
        <div id="tabContent-parent" class="tab-content p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs font-semibold text-gray-500 bg-gray-50/50">
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Slug</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4">Special</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($parentCategories as $parent)
                            <tr class="hover:bg-gray-50/55 transition-colors category-row" data-name="{{ strtolower($parent->name) }}">
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $parent->name }}</td>
                                <td class="px-6 py-4 text-xs font-mono text-gray-500">{{ $parent->slug }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                                    {{ $parent->description ?: 'No description provided.' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($parent->is_special)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800">★ Special</span>
                                    @else
                                        <span class="text-xs text-gray-400">Regular</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="toggleCategoryStatus({{ $parent->id }}, {{ $parent->status ? 'true' : 'false' }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $parent->status ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                        {{ $parent->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button onclick="openEditModal({{ json_encode($parent) }}, 'parent')"
                                        class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs">Edit</button>
                                    <button onclick="confirmDelete('{{ route('admin.categories.destroy', $parent->id) }}')"
                                        class="text-red-500 hover:text-red-800 font-semibold text-xs">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">No parent categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Tab 2: Subcategories ─────────────────────────────────────── --}}
        <div id="tabContent-sub" class="tab-content p-6 hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs font-semibold text-gray-500 bg-gray-50/50">
                            <th class="px-6 py-4">Subcategory Name</th>
                            <th class="px-6 py-4">Parent</th>
                            <th class="px-6 py-4">Slug</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4">Special</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @php $hasSub = false; @endphp
                        @foreach($parentCategories as $parent)
                            @foreach($parent->subcategories as $sub)
                                @php $hasSub = true; @endphp
                                <tr class="hover:bg-gray-50/55 transition-colors category-row" data-name="{{ strtolower($sub->name) }} {{ strtolower($parent->name) }}">
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $sub->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg">{{ $parent->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-mono text-gray-500">{{ $sub->slug }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                                        {{ $sub->description ?: 'No description provided.' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($sub->is_special)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800">★ Special</span>
                                        @else
                                            <span class="text-xs text-gray-400">Regular</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <button onclick="toggleCategoryStatus({{ $sub->id }}, {{ $sub->status ? 'true' : 'false' }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $sub->status ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                            {{ $sub->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button onclick="openEditModal({{ json_encode($sub) }}, 'sub')"
                                            class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs">Edit</button>
                                        <button onclick="confirmDelete('{{ route('admin.categories.destroy', $sub->id) }}')"
                                            class="text-red-500 hover:text-red-800 font-semibold text-xs">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        @unless($hasSub)
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400 text-sm">No subcategories found.</td>
                            </tr>
                        @endunless
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- Modal 1: Create / Edit Parent Category  (single form)                  --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="parentModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="closeModal('parentModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="{{ route('admin.categories.store') }}" method="POST" id="parentForm">
                @csrf
                <input type="hidden" name="_method" id="parentFormMethod" value="POST">
                <input type="hidden" name="active_tab" value="parent">

                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800" id="parentModalTitle">Create Parent Category</h3>
                        <button type="button" onclick="closeModal('parentModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name *</label>
                        <input type="text" name="name" id="parentName" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="parentDesc" rows="3"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm"></textarea>
                    </div>

                    <div class="flex items-center space-x-6 pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_special" id="parentIsSpecial" value="1"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-700">★ Special</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="status" id="parentStatus" value="1" checked
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="closeModal('parentModal')"
                        class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- Modal 2: Create Subcategories (multi-row, dynamic)                     --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="subCreateModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="closeModal('subCreateModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Add Subcategories</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Add one or more subcategories at once</p>
                        </div>
                        <button type="button" onclick="closeModal('subCreateModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Parent selector --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Parent Category *</label>
                        <select name="parent_id" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all text-sm">
                            <option value="">-- Select Parent Category --</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dynamic name rows --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Subcategory Names *</label>
                        <div id="subNameRows" class="space-y-2">
                            {{-- First row (not removable) --}}
                            <div class="flex items-center gap-2 sub-row">
                                <input type="text" name="names[]" required placeholder="e.g. Coloring Books"
                                    class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all text-sm">
                                <span class="w-9 h-9 flex items-center justify-center text-gray-300 text-sm select-none">
                                    <!-- spacer for alignment -->
                                </span>
                            </div>
                        </div>

                        <button type="button" onclick="addSubRow()"
                            class="mt-3 inline-flex items-center text-sm font-semibold text-pink-600 hover:text-pink-800 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Another Subcategory
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(applied to all)</span></label>
                        <textarea name="description" rows="2"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all text-sm"></textarea>
                    </div>

                    <div class="flex items-center space-x-6 pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_special" value="1"
                                class="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-700">★ Special</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="status" value="1" checked
                                class="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="closeModal('subCreateModal')"
                        class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                        Save All
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- Modal 3: Edit Subcategory (single)                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="subEditModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" onclick="closeModal('subEditModal')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="" method="POST" id="subEditForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="active_tab" value="sub">

                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800">Edit Subcategory</h3>
                        <button type="button" onclick="closeModal('subEditModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Parent Category *</label>
                        <select name="parent_id" id="subEditParentId" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all text-sm">
                            <option value="">-- Select Parent Category --</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Subcategory Name *</label>
                        <input type="text" name="name" id="subEditName" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="subEditDesc" rows="3"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all text-sm"></textarea>
                    </div>

                    <div class="flex items-center space-x-6 pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_special" id="subEditIsSpecial" value="1"
                                class="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-700">★ Special</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="status" id="subEditStatus" value="1"
                                class="w-4 h-4 text-pink-600 border-gray-300 rounded focus:ring-pink-500 cursor-pointer">
                            <span class="text-sm font-semibold text-gray-700">Active</span>
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="closeModal('subEditModal')"
                        class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Hidden delete form --}}
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<style>
    .tab-btn {
        color: #6b7280;
    }
    .tab-btn.active {
        background-color: #eef2ff;
        color: #4f46e5;
        font-weight: 700;
    }
</style>

<script>
    // ── Tab switching ─────────────────────────────────────────────────────────
    function switchTab(tab) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

        document.getElementById('tabBtn-' + tab).classList.add('active');
        document.getElementById('tabContent-' + tab).classList.remove('hidden');
    }

    // Restore active tab from URL param on page load
    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        switchTab(params.get('tab') === 'sub' ? 'sub' : 'parent');
    });

    // ── Modal helpers ─────────────────────────────────────────────────────────
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    // ── Parent category modal ─────────────────────────────────────────────────
    function openParentModal() {
        const form = document.getElementById('parentForm');
        form.action = "{{ route('admin.categories.store') }}";
        document.getElementById('parentFormMethod').value = 'POST';
        document.getElementById('parentModalTitle').textContent = 'Create Parent Category';
        document.getElementById('parentName').value = '';
        document.getElementById('parentDesc').value = '';
        document.getElementById('parentIsSpecial').checked = false;
        document.getElementById('parentStatus').checked = true;
        openModal('parentModal');
    }

    // ── Subcategory multi-create modal ────────────────────────────────────────
    function openSubCreateModal() {
        // Reset to one empty row
        const container = document.getElementById('subNameRows');
        container.innerHTML = `
            <div class="flex items-center gap-2 sub-row">
                <input type="text" name="names[]" required placeholder="e.g. Coloring Books"
                    class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all text-sm">
                <span class="w-9"></span>
            </div>`;
        openModal('subCreateModal');
    }

    function addSubRow() {
        const container = document.getElementById('subNameRows');
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 sub-row';
        row.innerHTML = `
            <input type="text" name="names[]" required placeholder="Subcategory name"
                class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 transition-all text-sm">
            <button type="button" onclick="removeSubRow(this)"
                class="w-9 h-9 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>`;
        container.appendChild(row);
        row.querySelector('input').focus();
    }

    function removeSubRow(btn) {
        const rows = document.querySelectorAll('#subNameRows .sub-row');
        if (rows.length > 1) {
            btn.closest('.sub-row').remove();
        }
    }

    // ── Edit modal ────────────────────────────────────────────────────────────
    function openEditModal(category, type) {
        if (type === 'parent') {
            const form = document.getElementById('parentForm');
            form.action = '/admin/categories/' + category.id;
            document.getElementById('parentFormMethod').value = 'PUT';
            document.getElementById('parentModalTitle').textContent = 'Edit Parent Category';
            document.getElementById('parentName').value = category.name;
            document.getElementById('parentDesc').value = category.description || '';
            document.getElementById('parentIsSpecial').checked = !!category.is_special;
            document.getElementById('parentStatus').checked = !!category.status;
            openModal('parentModal');
        } else {
            const form = document.getElementById('subEditForm');
            form.action = '/admin/categories/' + category.id;
            document.getElementById('subEditParentId').value = category.parent_id || '';
            document.getElementById('subEditName').value = category.name;
            document.getElementById('subEditDesc').value = category.description || '';
            document.getElementById('subEditIsSpecial').checked = !!category.is_special;
            document.getElementById('subEditStatus').checked = !!category.status;
            openModal('subEditModal');
        }
    }

    // ── Delete confirmation ───────────────────────────────────────────────────
    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
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

    // ── Status Toggle ────────────────────────────────────────────────────────
    function toggleCategoryStatus(categoryId, currentStatus) {
        const actionText = currentStatus ? "deactivate" : "activate";
        const url = `/admin/categories/${categoryId}/status`;

        Swal.fire({
            title: `Want to ${actionText}?`,
            text: `This will ${actionText} the category.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Yes, do it!',
            background: '#ffffff',
            borderRadius: '1rem',
            customClass: {
                popup: 'rounded-2xl border border-gray-100 shadow-xl',
                confirmButton: 'px-5 py-2.5 rounded-xl text-white font-semibold transition-all hover:scale-105',
                cancelButton: 'px-5 py-2.5 rounded-xl text-white font-semibold transition-all hover:scale-105'
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
                    if(data.success) {
                        Swal.fire({
                            title: 'Updated!',
                            text: data.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: { popup: 'rounded-2xl shadow-xl' }
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'An unexpected error occurred.', 'error');
                });
            }
        });
    }

    // ── Search Filter ────────────────────────────────────────────────────────
    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        // Since we have two tables, filter both
        const rows = document.querySelectorAll('.category-row');
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection