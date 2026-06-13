@extends('layouts.admin')

@section('content')
<div class="space-y-8">

    {{-- ── Summary Cards ──────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-teal-50 text-teal-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Categories</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $categories->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-cyan-50 text-cyan-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Subcategories</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $categories->sum(fn($c) => $c->subcategories->count()) }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all duration-200">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500">Active Categories</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $categories->where('status', true)->count() }}</p>
            </div>
        </div>
    </div>

    {{-- ── Tab Container ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Tab Header --}}
        <div class="px-8 py-4 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex space-x-1">
                <button onclick="switchTab('cat')" id="tabBtn-cat" class="tab-btn px-4 py-2 text-sm font-bold rounded-lg transition-all focus:outline-none">
                    Categories
                </button>
                <button onclick="switchTab('sub')" id="tabBtn-sub" class="tab-btn px-4 py-2 text-sm font-medium rounded-lg transition-all focus:outline-none">
                    Subcategories
                </button>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-sm w-56 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button onclick="openCatModal()" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Category
                </button>
                <button onclick="openSubModal()" class="inline-flex items-center px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Subcategory
                </button>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- ── Tab 1: Categories ────────────────────────────────────────── --}}
        <div id="tabContent-cat" class="tab-content p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-xs font-semibold text-gray-500 bg-gray-50/50">
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Slug</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4">Subcategories</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50/55 transition-colors category-row" data-name="{{ strtolower($cat->name) }}">
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    {{ $cat->name }}
                                    @if($cat->is_special)
                                        <span class="ml-1 text-amber-500 font-bold" title="Special Category">★</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-gray-500">{{ $cat->slug }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">{{ $cat->description ?: '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 bg-cyan-50 text-cyan-700 text-xs font-bold rounded-full">{{ $cat->subcategories->count() }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <button onclick="toggleCatStatus({{ $cat->id }}, {{ $cat->status ? 'true' : 'false' }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $cat->status ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                        {{ $cat->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button onclick="openEditCatModal({{ json_encode($cat) }})" class="text-teal-600 hover:text-teal-900 font-semibold text-xs">Edit</button>
                                    <button onclick="confirmDelete('{{ route('admin.site-categories.destroy', $cat->id) }}')" class="text-red-500 hover:text-red-800 font-semibold text-xs">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 text-sm">No categories found.</td></tr>
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
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @php $hasAny = false; @endphp
                        @foreach($categories as $cat)
                            @foreach($cat->subcategories as $sub)
                                @php $hasAny = true; @endphp
                                <tr class="hover:bg-gray-50/55 transition-colors category-row" data-name="{{ strtolower($sub->name) }} {{ strtolower($cat->name) }}">
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $sub->name }}</td>
                                    <td class="px-6 py-4"><span class="px-2 py-1 bg-teal-50 text-teal-700 text-xs font-bold rounded-lg">{{ $cat->name }}</span></td>
                                    <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">{{ $sub->description ?: '—' }}</td>
                                    <td class="px-6 py-4">
                                        <button onclick="toggleSubStatus({{ $sub->id }}, {{ $sub->status ? 'true' : 'false' }})"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $sub->status ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                            {{ $sub->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button onclick="openEditSubModal({{ json_encode(array_merge($sub->toArray(), ['site_category_id' => $cat->id])) }})" class="text-teal-600 hover:text-teal-900 font-semibold text-xs">Edit</button>
                                        <button onclick="confirmDelete('{{ route('admin.site-subcategories.destroy', $sub->id) }}')" class="text-red-500 hover:text-red-800 font-semibold text-xs">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        @unless($hasAny)
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">No subcategories found.</td></tr>
                        @endunless
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- Modal 1: Create / Edit Category                                      --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="catModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('catModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="{{ route('admin.site-categories.store') }}" method="POST" id="catForm">
                @csrf
                <input type="hidden" name="_method" id="catFormMethod" value="POST">
                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800" id="catModalTitle">Create Category</h3>
                        <button type="button" onclick="closeModal('catModal')" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="catName" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="catDesc" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm"></textarea>
                    </div>
                    <div class="flex items-center space-x-6">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_special" id="catIsSpecial" value="1" class="w-4 h-4 text-amber-500 border-gray-300 rounded">
                            <span class="text-sm font-semibold text-gray-700">★ Special Category</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="status" id="catStatus" value="1" checked class="w-4 h-4 text-teal-600 border-gray-300 rounded">
                            <span class="text-sm font-semibold text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="closeModal('catModal')" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-xl shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════ --}}
{{-- Modal 2: Create / Edit Subcategory                                   --}}
{{-- ════════════════════════════════════════════════════════════════════ --}}
<div id="subModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500/75" onclick="closeModal('subModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-lg">
            <form action="{{ route('admin.site-subcategories.store') }}" method="POST" id="subForm">
                @csrf
                <input type="hidden" name="_method" id="subFormMethod" value="POST">
                <div class="px-8 pt-8 pb-6 space-y-5">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800" id="subModalTitle">Add Subcategory</h3>
                            <p class="text-xs text-gray-400 mt-0.5" id="subModalSubtitle">Add one or more subcategories at once</p>
                        </div>
                        <button type="button" onclick="closeModal('subModal')" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                        <select name="site_category_id" id="subCategoryId" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Names field (create mode: multiple; edit mode: single) --}}
                    <div id="subNamesWrapper">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Names <span class="text-red-500">*</span></label>
                        <div id="subNameRows" class="space-y-2">
                            <div class="flex items-center gap-2 sub-row">
                                <input type="text" name="names[]" required placeholder="Subcategory name"
                                    class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm">
                                <span class="w-9 h-9"></span>
                            </div>
                        </div>
                        <button type="button" id="addRowBtn" onclick="addSubRow()" class="mt-3 inline-flex items-center text-sm font-semibold text-cyan-600 hover:text-cyan-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Another
                        </button>
                    </div>

                    {{-- Single name field (edit mode) --}}
                    <div id="subSingleNameWrapper" class="hidden">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="subSingleName" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                        <textarea name="description" id="subDesc" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm"></textarea>
                    </div>

                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="status" id="subStatus" value="1" checked class="w-4 h-4 text-cyan-600 border-gray-300 rounded">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                </div>
                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" onclick="closeModal('subModal')" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold rounded-xl shadow-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete form --}}
<form id="deleteForm" method="POST" class="hidden">@csrf @method('DELETE')</form>

<script>
// ── Tab switching ────────────────────────────────────────────────────────────
const activeTab = '{{ request('tab') === 'sub' ? 'sub' : 'cat' }}';

function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('bg-white', 'shadow-sm', 'text-gray-800', 'font-bold');
        el.classList.add('text-gray-500');
    });
    document.getElementById('tabContent-' + tab).classList.remove('hidden');
    const btn = document.getElementById('tabBtn-' + tab);
    btn.classList.add('bg-white', 'shadow-sm', 'text-gray-800', 'font-bold');
    btn.classList.remove('text-gray-500');
}

document.addEventListener('DOMContentLoaded', () => switchTab(activeTab));

// ── Search ───────────────────────────────────────────────────────────────────
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.category-row').forEach(row => {
        row.style.display = row.dataset.name.includes(q) ? '' : 'none';
    });
}

// ── Modal helpers ────────────────────────────────────────────────────────────
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

// ── Category modal ───────────────────────────────────────────────────────────
function openCatModal() {
    document.getElementById('catForm').action = "{{ route('admin.site-categories.store') }}";
    document.getElementById('catFormMethod').value = 'POST';
    document.getElementById('catModalTitle').textContent = 'Create Category';
    document.getElementById('catName').value = '';
    document.getElementById('catDesc').value = '';
    document.getElementById('catIsSpecial').checked = false;
    document.getElementById('catStatus').checked = true;
    document.getElementById('catModal').classList.remove('hidden');
}

function openEditCatModal(data) {
    document.getElementById('catForm').action = `/admin/site-categories/${data.id}`;
    document.getElementById('catFormMethod').value = 'PUT';
    document.getElementById('catModalTitle').textContent = 'Edit Category';
    document.getElementById('catName').value = data.name;
    document.getElementById('catDesc').value = data.description || '';
    document.getElementById('catIsSpecial').checked = !!data.is_special;
    document.getElementById('catStatus').checked = !!data.status;
    document.getElementById('catModal').classList.remove('hidden');
}

// ── Subcategory modal ────────────────────────────────────────────────────────
function openSubModal() {
    // Create mode: show multi-name rows, hide single name
    document.getElementById('subForm').action = "{{ route('admin.site-subcategories.store') }}";
    document.getElementById('subFormMethod').value = 'POST';
    document.getElementById('subModalTitle').textContent = 'Add Subcategory';
    document.getElementById('subModalSubtitle').classList.remove('hidden');
    document.getElementById('subCategoryId').value = '';
    document.getElementById('subNamesWrapper').classList.remove('hidden');
    document.getElementById('addRowBtn').classList.remove('hidden');
    document.getElementById('subSingleNameWrapper').classList.add('hidden');
    document.getElementById('subSingleName').removeAttribute('required');
    document.getElementById('subNameRows').innerHTML = `
        <div class="flex items-center gap-2 sub-row">
            <input type="text" name="names[]" required placeholder="Subcategory name"
                class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm">
            <span class="w-9 h-9"></span>
        </div>`;
    document.getElementById('subDesc').value = '';
    document.getElementById('subStatus').checked = true;
    document.getElementById('subModal').classList.remove('hidden');
}

function openEditSubModal(data) {
    document.getElementById('subForm').action = `/admin/site-subcategories/${data.id}`;
    document.getElementById('subFormMethod').value = 'PUT';
    document.getElementById('subModalTitle').textContent = 'Edit Subcategory';
    document.getElementById('subModalSubtitle').classList.add('hidden');
    document.getElementById('subCategoryId').value = data.site_category_id;
    // Hide multi-name rows, show single name
    document.getElementById('subNamesWrapper').classList.add('hidden');
    document.getElementById('addRowBtn').classList.add('hidden');
    document.getElementById('subSingleNameWrapper').classList.remove('hidden');
    document.getElementById('subSingleName').value = data.name;
    document.getElementById('subSingleName').setAttribute('required', 'required');
    document.getElementById('subDesc').value = data.description || '';
    document.getElementById('subStatus').checked = !!data.status;
    document.getElementById('subModal').classList.remove('hidden');
}

function addSubRow() {
    const container = document.getElementById('subNameRows');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2 sub-row';
    div.innerHTML = `
        <input type="text" name="names[]" placeholder="Subcategory name"
            class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm">
        <button type="button" onclick="this.parentElement.remove()" class="w-9 h-9 flex items-center justify-center text-red-400 hover:text-red-600 rounded-lg hover:bg-red-50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
    container.appendChild(div);
}

// ── Status toggles ───────────────────────────────────────────────────────────
function toggleCatStatus(id, current) {
    fetch(`/admin/site-categories/${id}/status`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); });
}

function toggleSubStatus(id, current) {
    fetch(`/admin/site-subcategories/${id}/status`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
    })
    .then(r => r.json())
    .then(data => { if (data.success) location.reload(); });
}

// ── Delete ───────────────────────────────────────────────────────────────────
function confirmDelete(action) {
    if (!confirm('Are you sure you want to delete this? This action cannot be undone.')) return;
    const form = document.getElementById('deleteForm');
    form.action = action;
    form.submit();
}
</script>
@endsection
