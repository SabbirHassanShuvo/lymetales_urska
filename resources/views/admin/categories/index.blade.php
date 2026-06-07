@extends('layouts.admin')

@section('content')
<div class="space-y-8">

    {{-- ── Summary Cards ──────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Categories</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $parentCategories->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-pink-50 text-pink-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Subcategories</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $parentCategories->sum(fn($p) => $p->subcategories->count()) }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-violet-50 text-violet-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Sub-subcategories</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $parentCategories->sum(fn($p) => $p->subcategories->sum(fn($s) => $s->children->count())) }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.18 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.98 10.12c-.783-.57-.38-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.519-4.674z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Special Categories</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $specialCategories->count() }}</p>
            </div>
        </div>
    </div>

    {{-- ── Tab Container ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Tab Header --}}
        <div class="px-8 py-4 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex space-x-1">
                <button onclick="switchTab('parent')" id="tabBtn-parent" class="tab-btn px-4 py-2 text-sm font-bold rounded-lg transition-all focus:outline-none">
                    Categories
                </button>
                <button onclick="switchTab('sub')" id="tabBtn-sub" class="tab-btn px-4 py-2 text-sm font-medium rounded-lg transition-all focus:outline-none">
                    Subcategories
                </button>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm w-56 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button onclick="openParentModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Category
                </button>
                <button onclick="openSubCreateModal()" class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Subcategory
                </button>
            </div>
        </div>

        {{-- ── Tab 1: Categories ────────────────────────────────────────── --}}
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
                                <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">{{ $parent->description ?: '—' }}</td>
                                <td class="px-6 py-4">
                                    @if($parent->is_special)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800">★ Special</span>
                                    @else
                                        <span class="text-xs text-gray-400">Regular</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="toggleCategoryStatus({{ $parent->id }}, {{ $parent->status ? 'true' : 'false' }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $parent->status ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                        {{ $parent->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button onclick="openEditModal({{ json_encode($parent) }}, 'parent')" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs">Edit</button>
                                    <button onclick="confirmDelete('{{ route('admin.categories.destroy', $parent->id) }}')" class="text-red-500 hover:text-red-800 font-semibold text-xs">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">No categories found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Tab 2: Subcategories (tree view) ───────────────────────── --}}
        <div id="tabContent-sub" class="tab-content p-6 hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs font-semibold text-gray-500 bg-gray-50/50">
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Parent Sub</th>
                            <th class="px-6 py-4">Level</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @php $hasAny = false; @endphp
                        @foreach($parentCategories as $cat)
                            @foreach($cat->subcategories as $sub)
                                @php $hasAny = true; @endphp
                                {{-- Level-1 row --}}
                                <tr class="hover:bg-gray-50/55 transition-colors category-row" data-name="{{ strtolower($sub->name) }} {{ strtolower($cat->name) }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            @if($sub->children->count() > 0)
                                                <button onclick="toggleChildren('children-{{ $sub->id }}')" class="text-gray-400 hover:text-indigo-600 transition-colors flex-shrink-0" title="Toggle children">
                                                    <svg id="arrow-{{ $sub->id }}" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </button>
                                            @else
                                                <span class="w-4 h-4 flex-shrink-0"></span>
                                            @endif
                                            <span class="font-semibold text-gray-800">{{ $sub->name }}</span>
                                            @if($sub->children->count() > 0)
                                                <span class="text-[10px] bg-violet-100 text-violet-700 px-1.5 py-0.5 rounded-full font-bold">{{ $sub->children->count() }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg">{{ $cat->name }}</span></td>
                                    <td class="px-6 py-4 text-gray-400 text-xs">—</td>
                                    <td class="px-6 py-4"><span class="px-2 py-0.5 bg-pink-50 text-pink-700 text-xs font-bold rounded-full">L1</span></td>
                                    <td class="px-6 py-4">
                                        <button onclick="toggleSubcategoryStatus({{ $sub->id }}, {{ $sub->status ? 'true' : 'false' }})"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $sub->status ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                            {{ $sub->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button onclick="openEditModal({{ json_encode(array_merge($sub->toArray(), ['parent_category_id' => $cat->id])) }}, 'sub')" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs">Edit</button>
                                        <button onclick="confirmDelete('{{ route('admin.subcategories.destroy', $sub->id) }}')" class="text-red-500 hover:text-red-800 font-semibold text-xs">Delete</button>
                                    </td>
                                </tr>

                                {{-- Level-2 children rows (collapsed by default) --}}
                                @foreach($sub->children as $child)
                                    @php $hasAny = true; @endphp
                                    <tr class="children-{{ $sub->id }} hidden hover:bg-violet-50/30 transition-colors category-row" data-name="{{ strtolower($child->name) }} {{ strtolower($cat->name) }} {{ strtolower($sub->name) }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2 pl-10">
                                                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                <span class="font-medium text-gray-700">{{ $child->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4"><span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg">{{ $cat->name }}</span></td>
                                        <td class="px-6 py-4"><span class="px-2 py-1 bg-pink-50 text-pink-700 text-xs font-semibold rounded-lg">{{ $sub->name }}</span></td>
                                        <td class="px-6 py-4"><span class="px-2 py-0.5 bg-violet-50 text-violet-700 text-xs font-bold rounded-full">L2</span></td>
                                        <td class="px-6 py-4">
                                            <button onclick="toggleSubcategoryStatus({{ $child->id }}, {{ $child->status ? 'true' : 'false' }})"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $child->status ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                                {{ $child->status ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <button onclick="openEditModal({{ json_encode(array_merge($child->toArray(), ['parent_category_id' => $cat->id])) }}, 'sub')" class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs">Edit</button>
                                            <button onclick="confirmDelete('{{ route('admin.subcategories.destroy', $child->id) }}')" class="text-red-500 hover:text-red-800 font-semibold text-xs">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                        @unless($hasAny)
                            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">No subcategories found.</td></tr>
                        @endunless
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- Modal 1: Create / Edit Parent Category                               --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="parentModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('parentModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="{{ route('admin.categories.store') }}" method="POST" id="parentForm">
                @csrf
                <input type="hidden" name="_method" id="parentFormMethod" value="POST">
                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800" id="parentModalTitle">Create Category</h3>
                        <button type="button" onclick="closeModal('parentModal')" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name *</label>
                        <input type="text" name="name" id="parentName" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="parentDesc" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"></textarea>
                    </div>
                    <div class="flex items-center space-x-6 pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_special" id="parentIsSpecial" value="1" class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                            <span class="text-sm font-semibold text-gray-700">★ Special</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="status" id="parentStatus" value="1" checked class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                            <span class="text-sm font-semibold text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="closeModal('parentModal')" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- Modal 2: Create Subcategory (supports L1 and L2)                    --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="subCreateModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('subCreateModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="{{ route('admin.subcategories.store') }}" method="POST">
                @csrf
                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Add Subcategory</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Add one or more subcategories at once</p>
                        </div>
                        <button type="button" onclick="closeModal('subCreateModal')" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    {{-- Step 1: Parent Category --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Parent Category <span class="text-red-500">*</span>
                        </label>
                        <select name="parent_category_id" id="createParentCategoryId" required onchange="loadLevel1Subs(this.value)"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
                            <option value="">-- Select Category --</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Step 2: Parent Subcategory (optional — makes this L2) --}}
                    <div id="createParentSubWrapper">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Parent Subcategory
                            <span class="text-xs text-gray-400 font-normal ml-1">(leave blank for Level-1)</span>
                        </label>
                        <select name="parent_id" id="createParentSubId"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 text-sm">
                            <option value="">-- None (create as Level-1) --</option>
                        </select>
                        <p id="createParentSubHint" class="text-xs text-gray-400 mt-1">Select a category first to load its subcategories.</p>
                    </div>

                    {{-- Names --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Names <span class="text-red-500">*</span></label>
                        <div id="subNameRows" class="space-y-2">
                            <div class="flex items-center gap-2 sub-row">
                                <input type="text" name="names[]" required placeholder="e.g. Black"
                                    class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
                                <span class="w-9 h-9"></span>
                            </div>
                        </div>
                        <button type="button" onclick="addSubRow()" class="mt-3 inline-flex items-center text-sm font-semibold text-pink-600 hover:text-pink-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Another
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                        <textarea name="description" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm"></textarea>
                    </div>

                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="status" value="1" checked class="w-4 h-4 text-pink-600 border-gray-300 rounded">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                </div>
                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="closeModal('subCreateModal')" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold rounded-xl shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- Modal 3: Edit Subcategory                                            --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="subEditModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('subEditModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="" method="POST" id="subEditForm">
                @csrf
                @method('PUT')
                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800">Edit Subcategory</h3>
                        <button type="button" onclick="closeModal('subEditModal')" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Parent Category <span class="text-red-500">*</span></label>
                        <select name="parent_category_id" id="subEditParentCategoryId" required onchange="loadLevel1SubsForEdit(this.value)"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
                            <option value="">-- Select Category --</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="editParentSubWrapper">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Parent Subcategory
                            <span class="text-xs text-gray-400 font-normal ml-1">(blank = Level-1)</span>
                        </label>
                        <select name="parent_id" id="subEditParentSubId"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 text-sm">
                            <option value="">-- None (Level-1) --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="subEditName" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="subEditDesc" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm"></textarea>
                    </div>

                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="status" id="subEditStatus" value="1" class="w-4 h-4 text-pink-600 border-gray-300 rounded">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                </div>
                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="closeModal('subEditModal')" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold rounded-xl shadow-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete form --}}
<form id="deleteForm" method="POST" class="hidden">@csrf @method('DELETE')</form>

{{-- Subcategories data for JS --}}
<script>
const categorySubs = @json(
    $parentCategories->mapWithKeys(fn($cat) => [
        $cat->id => $cat->subcategories->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()
    ])
);
</script>

<style>
.tab-btn { color: #6b7280; }
.tab-btn.active { background-color: #eef2ff; color: #4f46e5; font-weight: 700; }
</style>

<script>
// ── Tab switching ──────────────────────────────────────────────────────────
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('tabBtn-' + tab).classList.add('active');
    document.getElementById('tabContent-' + tab).classList.remove('hidden');
}
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    switchTab(params.get('tab') === 'sub' ? 'sub' : 'parent');
});

// ── Modal helpers ──────────────────────────────────────────────────────────
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }

// ── Toggle children rows ──────────────────────────────────────────────────
function toggleChildren(cls) {
    const subId = cls.replace('children-', '');
    const rows  = document.querySelectorAll('.' + cls);
    const arrow = document.getElementById('arrow-' + subId);
    const allHidden = [...rows].every(r => r.classList.contains('hidden'));
    rows.forEach(r => r.classList.toggle('hidden', !allHidden));
    if (arrow) arrow.style.transform = allHidden ? 'rotate(90deg)' : '';
}

// ── Load L1 subs for create modal ─────────────────────────────────────────
function loadLevel1Subs(catId) {
    const sel  = document.getElementById('createParentSubId');
    const hint = document.getElementById('createParentSubHint');
    sel.innerHTML = '<option value="">-- None (create as Level-1) --</option>';
    if (!catId || !categorySubs[catId]) {
        hint.textContent = 'Select a category first to load its subcategories.';
        return;
    }
    const subs = categorySubs[catId];
    if (subs.length === 0) {
        hint.textContent = 'No Level-1 subcategories yet — this will be created as Level-1.';
        return;
    }
    hint.textContent = 'Optional: select a parent to create as Level-2.';
    subs.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.name;
        sel.appendChild(opt);
    });
}

// ── Load L1 subs for edit modal ───────────────────────────────────────────
function loadLevel1SubsForEdit(catId, currentParentId = null) {
    const sel = document.getElementById('subEditParentSubId');
    sel.innerHTML = '<option value="">-- None (Level-1) --</option>';
    if (!catId || !categorySubs[catId]) return;
    categorySubs[catId].forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.name;
        if (currentParentId && String(s.id) === String(currentParentId)) opt.selected = true;
        sel.appendChild(opt);
    });
}

// ── Create modal ───────────────────────────────────────────────────────────
function openSubCreateModal() {
    document.getElementById('createParentCategoryId').value = '';
    document.getElementById('createParentSubId').innerHTML = '<option value="">-- None (create as Level-1) --</option>';
    document.getElementById('createParentSubHint').textContent = 'Select a category first to load its subcategories.';
    document.getElementById('subNameRows').innerHTML = `
        <div class="flex items-center gap-2 sub-row">
            <input type="text" name="names[]" required placeholder="e.g. Black"
                class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
            <span class="w-9 h-9"></span>
        </div>`;
    openModal('subCreateModal');
}

function addSubRow() {
    const container = document.getElementById('subNameRows');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2 sub-row';
    div.innerHTML = `
        <input type="text" name="names[]" placeholder="Subcategory name"
            class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
        <button type="button" onclick="this.closest('.sub-row').remove()"
            class="w-9 h-9 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    container.appendChild(div);
}

// ── Edit modals ────────────────────────────────────────────────────────────
function openEditModal(data, type) {
    if (type === 'parent') {
        document.getElementById('parentModalTitle').textContent = 'Edit Category';
        document.getElementById('parentForm').action = `/admin/categories/${data.id}`;
        document.getElementById('parentFormMethod').value = 'PUT';
        document.getElementById('parentName').value = data.name;
        document.getElementById('parentDesc').value = data.description || '';
        document.getElementById('parentIsSpecial').checked = !!data.is_special;
        document.getElementById('parentStatus').checked = !!data.status;
        openModal('parentModal');
    } else {
        const form = document.getElementById('subEditForm');
        form.action = `/admin/subcategories/${data.id}`;
        document.getElementById('subEditName').value = data.name;
        document.getElementById('subEditDesc').value = data.description || '';
        document.getElementById('subEditStatus').checked = !!data.status;

        const catSel = document.getElementById('subEditParentCategoryId');
        catSel.value = data.parent_category_id || data.category_id || '';
        loadLevel1SubsForEdit(catSel.value, data.parent_id);

        openModal('subEditModal');
    }
}

// ── Parent category modal ──────────────────────────────────────────────────
function openParentModal() {
    document.getElementById('parentForm').action = "{{ route('admin.categories.store') }}";
    document.getElementById('parentFormMethod').value = 'POST';
    document.getElementById('parentModalTitle').textContent = 'Create Category';
    document.getElementById('parentName').value = '';
    document.getElementById('parentDesc').value = '';
    document.getElementById('parentIsSpecial').checked = false;
    document.getElementById('parentStatus').checked = true;
    openModal('parentModal');
}

// ── Status toggles ─────────────────────────────────────────────────────────
function toggleCategoryStatus(id, current) {
    fetch(`/admin/categories/${id}/status`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
}

function toggleSubcategoryStatus(id, current) {
    fetch(`/admin/subcategories/${id}/status`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}', 'Accept': 'application/json' }
    }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
}

// ── Delete ─────────────────────────────────────────────────────────────────
function confirmDelete(action) {
    Swal.fire({ title: 'Are you sure?', text: 'This cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Delete' })
        .then(result => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = action;
                form.submit();
            }
        });
}

// ── Table search ───────────────────────────────────────────────────────────
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.category-row').forEach(row => {
        row.style.display = row.dataset.name.includes(q) ? '' : 'none';
    });
}
</script>
@endsection
