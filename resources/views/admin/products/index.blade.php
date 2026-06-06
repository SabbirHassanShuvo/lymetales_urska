@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 font-medium">Total Books</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalCount }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.371 1.24.588 1.81l-3.97 2.883a1 1 0 00-.364 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.971-2.883a1 1 0 00-1.18 0l-3.97 2.883c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.364-1.118L2.98 10.12c-.783-.57-.38-1.81.588-1.81h4.907a1 1 0 00.95-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 font-medium">Bestsellers</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $bestsellersCount }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 font-medium">Recommended</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $recommendedCount }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 font-medium">Active Books</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $activeCount }}</p>
            </div>
        </div>
    </div>

    <!-- Section Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Books & Personalised Products</h2>
            <p class="text-sm text-gray-500">Create, edit, update, preview and manage personalised book offerings.</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search books..." class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button onclick="openCreateProductModal()" class="inline-flex items-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Book
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="px-5 py-3.5">Book</th>
                        <th class="px-5 py-3.5">Domain</th>
                        <th class="px-5 py-3.5">Category</th>
                        <th class="px-5 py-3.5">Price</th>
                        <th class="px-5 py-3.5">Specs</th>
                        <th class="px-5 py-3.5">Badges</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm" id="productTableBody">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/60 transition-colors product-row group"
                            data-title="{{ strtolower($product->title) }}"
                            data-category="{{ strtolower($product->category ? $product->category->name : '') }}">

                            {{-- Book --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-13 rounded-lg overflow-hidden border border-gray-100 flex-shrink-0 bg-gray-50" style="height:3.25rem">
                                        <img src="{{ $product->imageUrl ?: 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=80' }}"
                                             alt="{{ $product->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-800 text-sm truncate max-w-[180px]">{{ $product->title }}</p>
                                        <div class="flex items-center gap-1 mt-0.5">
                                            <span class="text-amber-400 text-xs">★</span>
                                            <span class="text-xs text-gray-500">{{ number_format($product->rating ?: 5.0, 1) }}</span>
                                            <span class="text-gray-300 text-xs">·</span>
                                            <span class="text-xs text-gray-400">{{ $product->reviews_count ?: 0 }} reviews</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Domain --}}
                            <td class="px-5 py-3.5">
                                @if($product->domain)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold
                                        {{ $product->domain === 'domain1' ? 'bg-violet-50 text-violet-700 border border-violet-100' : 'bg-sky-50 text-sky-700 border border-sky-100' }}">
                                        {{ $product->domain === 'domain1' ? 'Domain 1' : 'Domain 2' }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Category --}}
                            <td class="px-5 py-3.5">
                                @if($product->categoryImages && $product->categoryImages->count() > 0)
                                    <div class="flex flex-col gap-1">
                                        @foreach($product->categoryImages->unique('category_id')->take(2) as $catImg)
                                            @if($catImg->category)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-md border border-indigo-100/60 w-fit">
                                                    {{ $catImg->category->name }}
                                                    @if($catImg->subcategory)
                                                        <span class="text-indigo-300">›</span>
                                                        <span class="text-indigo-500">{{ $catImg->subcategory->name }}</span>
                                                    @endif
                                                </span>
                                            @endif
                                        @endforeach
                                        @if($product->categoryImages->unique('category_id')->count() > 2)
                                            <span class="text-xs text-gray-400">+{{ $product->categoryImages->unique('category_id')->count() - 2 }} more</span>
                                        @endif
                                    </div>
                                @elseif($product->category)
                                    <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-md border border-indigo-100/60">
                                        {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs italic">None</span>
                                @endif
                            </td>

                            {{-- Price --}}
                            <td class="px-5 py-3.5">
                                <span class="text-sm font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            </td>

                            {{-- Specs --}}
                            <td class="px-5 py-3.5">
                                <div class="space-y-0.5 text-xs text-gray-500">
                                    <div><span class="font-semibold text-gray-600">Ages:</span> {{ $product->age_range ?: '—' }}</div>
                                    <div><span class="font-semibold text-gray-600">Pages:</span> {{ $product->pages ?: '—' }}</div>
                                    <div><span class="font-semibold text-gray-600">Cover:</span> {{ $product->cover_type ? \Illuminate\Support\Str::limit($product->cover_type, 16) : '—' }}</div>
                                </div>
                            </td>

                            {{-- Badges --}}
                            <td class="px-5 py-3.5">
                                <div class="flex flex-col gap-1">
                                    @if($product->is_bestseller)
                                        <span class="inline-flex w-fit items-center px-2 py-0.5 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            ★ Bestseller
                                        </span>
                                    @endif
                                    @if($product->is_recommended)
                                        <span class="inline-flex w-fit items-center px-2 py-0.5 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            ❤ Recommended
                                        </span>
                                    @endif
                                    @if(!$product->is_bestseller && !$product->is_recommended)
                                        <span class="text-gray-300 text-xs">—</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3.5">
                                <button onclick="toggleProductStatus({{ $product->id }}, {{ $product->status ? 'true' : 'false' }})"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold transition-colors
                                    {{ $product->status
                                        ? 'bg-green-50 text-green-700 hover:bg-green-100 border border-green-200'
                                        : 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->status ? 'bg-green-500' : 'bg-red-400' }}"></span>
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button onclick="previewProduct({{ json_encode($product->load('images')) }})"
                                        class="px-2.5 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 hover:text-indigo-600 transition-all"
                                        title="Preview">
                                        Preview
                                    </button>
                                    <button onclick="editProduct({{ json_encode($product->load('images')) }})"
                                        class="px-2.5 py-1.5 bg-white hover:bg-indigo-50 border border-gray-200 rounded-lg text-xs font-semibold text-gray-600 hover:text-indigo-600 transition-all"
                                        title="Edit">
                                        Edit
                                    </button>
                                    <button onclick="confirmDelete('{{ route('admin.products.destroy', $product->id) }}', 'This book and its files will be permanently deleted.')"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all border border-transparent hover:border-red-100"
                                        title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-16 text-gray-400">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <p class="font-bold text-gray-700">No Books Found</p>
                                <p class="text-xs text-gray-400 mt-1">Get started by adding your first book.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-500">
                Showing <span class="font-semibold text-gray-700">{{ $products->firstItem() }}</span>–<span class="font-semibold text-gray-700">{{ $products->lastItem() }}</span>
                of <span class="font-semibold text-gray-700">{{ $products->total() }}</span> books
            </p>
            <div class="flex items-center gap-1">
                {{-- Previous --}}
                @if($products->onFirstPage())
                    <span class="px-3 py-1.5 text-xs font-semibold text-gray-300 bg-gray-50 border border-gray-100 rounded-lg cursor-not-allowed">← Prev</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1.5 text-xs font-semibold text-gray-600 bg-white hover:bg-indigo-50 hover:text-indigo-700 border border-gray-200 rounded-lg transition-all">← Prev</a>
                @endif

                {{-- Page numbers --}}
                @foreach($products->getUrlRange(max(1, $products->currentPage()-2), min($products->lastPage(), $products->currentPage()+2)) as $page => $url)
                    @if($page == $products->currentPage())
                        <span class="px-3 py-1.5 text-xs font-bold text-white bg-indigo-600 border border-indigo-600 rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 text-xs font-semibold text-gray-600 bg-white hover:bg-indigo-50 hover:text-indigo-700 border border-gray-200 rounded-lg transition-all">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1.5 text-xs font-semibold text-gray-600 bg-white hover:bg-indigo-50 hover:text-indigo-700 border border-gray-200 rounded-lg transition-all">Next →</a>
                @else
                    <span class="px-3 py-1.5 text-xs font-semibold text-gray-300 bg-gray-50 border border-gray-100 rounded-lg cursor-not-allowed">Next →</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Add/Edit Product Modal -->
<div id="productModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleModal('productModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Large Modal Panel -->
        <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-gray-100">
            <form action="{{ route('admin.products.store') }}" method="POST" id="productForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="bg-white px-8 pt-8 pb-6 space-y-6">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                        <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Create New Book</h3>
                        <button type="button" onclick="toggleModal('productModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
                        <p class="text-sm font-semibold text-red-700 mb-1">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-600 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Fields -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Book Title *</label>
                                <input type="text" name="title" id="prodTitle" required placeholder="e.g. My First Easter Egg Hunt" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Domain</label>
                                <select name="domain" id="prodDomain" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                    <option value="">— No specific domain —</option>
                                    <option value="domain1">Domain 1</option>
                                    <option value="domain2">Domain 2</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Price ($) *</label>
                                    <input type="number" step="0.01" name="price" id="prodPrice" required placeholder="29.99" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Total Pages</label>
                                    <input type="number" name="pages" id="prodPages" placeholder="24" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Age Range</label>
                                    <input type="text" name="age_range" id="prodAgeRange" placeholder="e.g. 3-5 Years" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Book Size</label>
                                    <input type="text" name="size" id="prodSize" placeholder="e.g. 21cm X 29.7cm" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Characters</label>
                                    <input type="text" name="characters" id="prodCharacters" placeholder="e.g. 1 Customizable" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cover Type</label>
                                    <select name="cover_type" id="prodCoverType" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                        <option value="Premium Hardcover">Premium Hardcover</option>
                                        <option value="Premium Softcover">Premium Softcover</option>
                                        <option value="Hardcover & Softcover">Both Options</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Right Fields -->
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Print Quality</label>
                                    <input type="text" name="print_type" id="prodPrintType" placeholder="e.g. Archival-quality ink" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Paper Quality</label>
                                    <input type="text" name="paper_type" id="prodPaperType" placeholder="e.g. Thick matte pages" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Initial Rating</label>
                                    <input type="number" step="0.1" max="5" name="rating" id="prodRating" placeholder="5.0" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Reviews Count</label>
                                    <input type="number" name="reviews_count" id="prodReviewsCount" placeholder="0" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                </div>
                            </div>

                            <!-- Main Image Upload -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Main Cover Image</label>
                                <input type="file" name="image" id="prodImageFile" accept="image/*" onchange="previewMainImage(event)" class="text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 w-full border border-gray-200 p-1.5 rounded-xl bg-gray-50">
                                <!-- Main image preview -->
                                <div id="mainImagePreview" class="hidden mt-2 relative w-20 h-24 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                                    <img id="mainImagePreviewImg" src="" class="w-full h-full object-cover">
                                    <button type="button" onclick="clearMainImage()" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center" title="Remove">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <input type="text" name="image_url" id="prodImageUrl" placeholder="Or enter Image URL (e.g. https://images.unsplash.com/...)" class="w-full px-4 py-2 mt-2 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500 text-xs">
                                
                                <!-- Name Overlay Configuration Button -->
                                <button type="button" onclick="openNameOverlayModal()" class="mt-3 w-full py-2 px-4 border border-indigo-200 rounded-xl shadow-sm text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Configure Name Overlay
                                </button>

                                <!-- Hidden Inputs for Name Overlay -->
                                <input type="hidden" name="name_text" id="prodNameText">
                                <input type="hidden" name="name_font_family" id="prodNameFontFamily" value="PetitCochon">
                                <input type="hidden" name="name_top" id="prodNameTop" value="2%">
                                <input type="hidden" name="name_color" id="prodNameColor" value="#e591ae">
                                <input type="hidden" name="name_font_size" id="prodNameFontSize" value="88px">
                                <input type="hidden" name="name_right" id="prodNameRight" value="50%">
                            </div>

                            <!-- Gallery Upload -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Gallery Thumbnails</label>
                                <input type="file" name="gallery_files[]" id="prodGalleryFiles" multiple accept="image/*" onchange="previewGalleryImages(event)" class="text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 w-full border border-gray-200 p-1.5 rounded-xl bg-gray-50">
                                <div id="galleryPreviewContainer" class="flex flex-wrap gap-2 mt-3"></div>
                                <div id="existingGalleryContainer" class="flex flex-wrap gap-2 mt-2 hidden"></div>
                                <input type="hidden" name="deleted_image_ids" id="deletedImageIds" value="[]">
                                <input type="text" name="gallery_urls" id="prodGalleryUrls" placeholder="Or enter comma-separated Image URLs..." class="w-full px-4 py-2 mt-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-indigo-500 text-xs">
                                <!-- Replace Gallery checkbox for editing -->
                                <label id="replaceGalleryGroup" class="hidden items-center space-x-2 mt-2 cursor-pointer">
                                    <input type="checkbox" name="replace_gallery" value="1" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                                    <span class="text-2xs text-gray-500 font-semibold uppercase">Replace existing gallery instead of appending</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Book Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Book Description</label>
                        <textarea name="description" id="prodDesc" rows="3" placeholder="Write a gorgeous description for this personalized story..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"></textarea>
                    </div>

                    <!-- Dynamic "Book Category Images" Sections -->
                    <div class="pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-md font-bold text-gray-800">Book Category Images</h4>
                            <button type="button" onclick="addCategoryImageSection()" class="px-3 py-1.5 bg-pink-50 text-pink-700 text-sm font-semibold rounded-lg hover:bg-pink-100 transition-all flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Image
                            </button>
                        </div>
                        <div id="categoryImagesContainer" class="space-y-6">
                            <!-- Dynamic sections appended here via JS -->
                        </div>
                    </div>

                    <!-- Dynamic "What makes this special" Sections -->
                    <div class="pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-md font-bold text-gray-800">"What makes this special" Sections</h4>
                            <button type="button" onclick="addSpecialSection()" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-lg hover:bg-indigo-100 transition-all flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Section
                            </button>
                        </div>
                        <div id="specialSectionsContainer" class="space-y-6">
                            <!-- Dynamic sections appended here via JS -->
                        </div>
                    </div>

                    <!-- Status Flags -->
                    <div class="flex items-center space-x-6 pt-6 border-t border-gray-50">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_bestseller" id="prodIsBestseller" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                            <span class="text-sm font-bold text-gray-700">★ Best Seller Badge</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_recommended" id="prodIsRecommended" value="1" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                            <span class="text-sm font-bold text-gray-700">❤ Recommended Gift Badge</span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="status" id="prodStatus" value="1" checked class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                            <span class="text-sm font-bold text-gray-700">Active / Listed</span>
                        </label>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('productModal')" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                        Save Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Interactive Customer Preview Modal -->
<div id="previewModal" class="fixed inset-0 z-50 overflow-y-auto hidden animate-fade-in" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity" aria-hidden="true" onclick="toggleModal('previewModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- High-fidelity Detail page mockup panel -->
        <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100 relative">
            
            <!-- Close btn -->
            <button onclick="toggleModal('previewModal')" class="absolute top-6 right-6 z-10 w-10 h-10 bg-white hover:bg-gray-100 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 transition-all focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <div class="p-8 sm:p-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    
                    <!-- Left: Book Cover & Gallery Mock -->
                    <div class="space-y-6">
                        <div class="w-full aspect-[4/5] bg-gray-50 rounded-2xl overflow-hidden border border-gray-100/50 shadow-inner flex items-center justify-center">
                            <img id="prevImg" src="" alt="Book Cover" class="w-full h-full object-cover transition-all duration-300">
                        </div>
                        <!-- Mini Gallery Removed -->
                    </div>

                    <!-- Right: Product details -->
                    <div class="flex flex-col justify-between">
                        <div class="space-y-6">
                            <!-- Badges -->
                            <div class="flex flex-wrap gap-2" id="prevBadges">
                                <!-- JS injected badges -->
                            </div>

                            <!-- Title -->
                            <h2 id="prevTitle" class="text-3xl font-extrabold text-gray-900 tracking-tight leading-tight">My First Easter Egg Hunt</h2>
                            
                            <!-- Rating -->
                            <div class="flex items-center space-x-3">
                                <div class="flex text-amber-400" id="prevStars">
                                    <!-- Injected stars -->
                                </div>
                                <span class="text-sm font-semibold text-gray-800" id="prevRatingScore">4.9 / 5.0</span>
                                <span class="text-xs text-gray-400 font-medium cursor-pointer hover:underline" id="prevReviewsLink">Based on 2,847 reviews</span>
                            </div>

                            <!-- Price & Personalise CTA -->
                            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Customer Price</p>
                                    <p id="prevPrice" class="text-3xl font-black text-gray-900 mt-1">$29.99</p>
                                </div>
                                <button type="button" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm shadow-md shadow-indigo-150 transition-all hover:scale-105">
                                    Personalise Book
                                </button>
                            </div>

                            <!-- Core Specs Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50/50 border border-gray-100/80 rounded-xl flex items-center space-x-3">
                                    <span class="text-xl">📖</span>
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Pages</p>
                                        <p id="prevSpecPages" class="text-sm font-bold text-gray-800">24 Pages</p>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-50/50 border border-gray-100/80 rounded-xl flex items-center space-x-3">
                                    <span class="text-xl">👶</span>
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Ages</p>
                                        <p id="prevSpecAges" class="text-sm font-bold text-gray-800">0-6 Years</p>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-50/50 border border-gray-100/80 rounded-xl flex items-center space-x-3">
                                    <span class="text-xl">📏</span>
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Size</p>
                                        <p id="prevSpecSize" class="text-sm font-bold text-gray-800">26 x 26 cm</p>
                                    </div>
                                </div>
                                <div class="p-4 bg-gray-50/50 border border-gray-100/80 rounded-xl flex items-center space-x-3">
                                    <span class="text-xl">👥</span>
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Characters</p>
                                        <p id="prevSpecCharacters" class="text-sm font-bold text-gray-800">1 Customizable</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Book Description</h4>
                                <p id="prevDescription" class="text-sm text-gray-600 leading-relaxed">A magical personalised Easter adventure where your child hunts for colorful eggs in the enchanted forest...</p>
                            </div>
                        </div>

                        <!-- Crafted Specifications (Collapsible / Table) -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Premium Build Details</h4>
                            <div class="grid grid-cols-3 gap-4 text-2xs uppercase tracking-wider text-gray-500 font-semibold">
                                <div class="space-y-1">
                                    <span class="text-gray-400">Cover</span>
                                    <p id="prevSpecCover" class="text-xs font-bold text-gray-800 normal-case">Hardcover</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-gray-400">Print Quality</span>
                                    <p id="prevSpecPrint" class="text-xs font-bold text-gray-800 normal-case">Archival ink</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-gray-400">Paper Type</span>
                                    <p id="prevSpecPaper" class="text-xs font-bold text-gray-800 normal-case">Thick matte</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mock Customer Reviews Section (Removed Fake Reviews) -->
                <div class="mt-12 pt-8 border-t border-gray-150/80">
                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight mb-6">Customer Reviews</h3>
                    <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 text-center">
                        <p class="text-xs text-gray-500 font-medium">Customer reviews for this product will appear on the storefront. Live review preview is not available in admin.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Name Overlay Configuration Modal -->
<div id="nameOverlayModal" class="fixed inset-0 z-[60] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" aria-hidden="true" onclick="closeNameOverlayModal()"></div>

        <!-- Modal Panel -->
        <div class="relative inline-block w-full max-w-5xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-extrabold text-gray-900" id="modal-title">Configure Name Overlay</h3>
                <button type="button" onclick="closeNameOverlayModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Preview Side (Fixed 650x650 area relative to its container) -->
                <div class="flex flex-col items-center">
                    <p class="text-sm font-semibold text-gray-700 mb-3 w-full text-center">Live Preview (650x650 Base)</p>
                    <div class="relative bg-gray-100 border border-gray-200 overflow-hidden rounded-xl flex items-center justify-center shadow-inner" style="width: 100%; max-width: 650px; aspect-ratio: 1/1;" id="overlayPreviewBox">
                        <img id="overlayPreviewImage" src="" class="w-full h-full object-cover" alt="Main Cover Preview">
                        <div id="overlayPreviewText" class="absolute whitespace-nowrap drop-shadow-md" style="font-family: 'PetitCochon', cursive; top: 2%; right: 50%; color: #e591ae; font-size: 88px; transform: translateX(50%);">Your Name</div>
                    </div>
                </div>

                <!-- Controls Side -->
                <div class="flex flex-col space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Name Text</label>
                        <input type="text" id="configNameText" placeholder="e.g. Emma" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" oninput="updateOverlayPreview()">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Font Family</label>
                        <select id="configNameFont" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" onchange="updateOverlayPreview()">
                            <option value="PetitCochon">PetitCochon</option>
                            <option value="Arial, sans-serif">Arial</option>
                            <option value="'Courier New', Courier, monospace">Courier New</option>
                            <option value="'Times New Roman', Times, serif">Times New Roman</option>
                            <option value="'Comic Sans MS', cursive, sans-serif">Comic Sans MS</option>
                            <option value="Impact, fantasy">Impact</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Text Color</label>
                            <div class="flex items-center space-x-3">
                                <input type="color" id="configNameColor" value="#e591ae" class="h-10 w-16 p-1 bg-white border border-gray-200 rounded-lg cursor-pointer" oninput="updateOverlayPreview()">
                                <span class="text-xs text-gray-500 font-mono" id="colorHexDisplay">#e591ae</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Font Size (px)</label>
                            <div class="flex items-center space-x-3">
                                <input type="range" id="configNameFontSize" min="10" max="250" value="88" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600" oninput="updateOverlayPreview()">
                                <span class="text-xs text-gray-700 font-bold w-12 text-right" id="fontSizeDisplay">88px</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Top Position (%)</label>
                        <div class="flex items-center space-x-3">
                            <input type="range" id="configNameTop" min="0" max="100" value="2" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600" oninput="updateOverlayPreview()">
                            <span class="text-xs text-gray-700 font-bold w-12 text-right" id="topDisplay">2%</span>
                        </div>
                        <p class="text-2xs text-gray-400 mt-1">Move text down.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Right Position (%)</label>
                        <div class="flex items-center space-x-3">
                            <input type="range" id="configNameRight" min="0" max="100" value="50" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-indigo-600" oninput="updateOverlayPreview()">
                            <span class="text-xs text-gray-700 font-bold w-12 text-right" id="rightDisplay">50%</span>
                        </div>
                        <p class="text-2xs text-gray-400 mt-1">Move text left.</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-100 flex items-center justify-end space-x-3 rounded-b-2xl">
                <button type="button" onclick="closeNameOverlayModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="saveNameOverlayConfig()" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md shadow-indigo-200 transition-all">
                    Apply Overlay Config
                </button>
            </div>
        </div>
    </div>
</div>


<!-- SweetAlert delete form helper -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    const categoriesData = @json($categories);
    const subcategoriesData = @json($subcategories);
    let specialSectionIndex = 0;
    let categoryImageIndex = 0;

    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
            if (modalId === 'productModal') {
                resetProductForm();
            }
        }
    }

    function resetProductForm() {
        document.getElementById('productForm').action = "{{ route('admin.products.store') }}";
        document.getElementById('formMethod').value = "POST";
        document.getElementById('modalTitle').textContent = "Create New Book";
        
        document.getElementById('prodTitle').value = "";
        if (document.getElementById('prodDomain')) document.getElementById('prodDomain').value = "";
        if (document.getElementById('prodParentCategory')) document.getElementById('prodParentCategory').value = "";
        if (document.getElementById('prodSubCategory')) document.getElementById('prodSubCategory').value = "";
        if (document.getElementById('subCategoryContainer')) document.getElementById('subCategoryContainer').classList.add('hidden');
        document.getElementById('prodPrice').value = "";
        document.getElementById('prodPages').value = "";
        document.getElementById('prodAgeRange').value = "";
        document.getElementById('prodSize').value = "";
        document.getElementById('prodCharacters').value = "";
        document.getElementById('prodCoverType').value = "Premium Hardcover";
        document.getElementById('prodPrintType').value = "";
        document.getElementById('prodPaperType').value = "";
        document.getElementById('prodRating').value = "";
        document.getElementById('prodReviewsCount').value = "";
        document.getElementById('prodDesc').value = "";
        
        // Name Overlay Reset
        document.getElementById('prodNameText').value = "";
        document.getElementById('prodNameFontFamily').value = "PetitCochon";
        document.getElementById('prodNameTop').value = "2%";
        document.getElementById('prodNameColor').value = "#e591ae";
        document.getElementById('prodNameFontSize').value = "88px";
        document.getElementById('prodNameRight').value = "50%";

        document.getElementById('prodImageUrl').value = "";
        document.getElementById('prodGalleryUrls').value = "";
        document.getElementById('prodImageFile').value = "";
        document.getElementById('prodGalleryFiles').value = "";
        
        document.getElementById('specialSectionsContainer').innerHTML = "";
        specialSectionIndex = 0;
        addSpecialSection(); // Add one blank section by default

        document.getElementById('categoryImagesContainer').innerHTML = "";
        categoryImageIndex = 0;
        addCategoryImageSection(); // Add one blank category image section by default

        // Clear image previews
        document.getElementById('mainImagePreview').classList.add('hidden');
        document.getElementById('mainImagePreviewImg').src = "";
        document.getElementById('galleryPreviewContainer').innerHTML = "";
        selectedGalleryFiles = [];
        document.getElementById('existingGalleryContainer').innerHTML = "";
        document.getElementById('existingGalleryContainer').classList.add('hidden');
        document.getElementById('deletedImageIds').value = "[]";
        
        document.getElementById('prodIsBestseller').checked = false;
        document.getElementById('prodIsRecommended').checked = false;
        document.getElementById('prodStatus').checked = true;
        document.getElementById('replaceGalleryGroup').classList.add('hidden');
    }

    function openCreateProductModal() {
        resetProductForm();
        toggleModal('productModal');
    }

    function editProduct(product) {
        document.getElementById('productForm').action = "/admin/products/" + product.id;
        document.getElementById('formMethod').value = "PUT";
        document.getElementById('modalTitle').textContent = "Edit Book";

        document.getElementById('prodTitle').value = product.title;
        if (document.getElementById('prodDomain')) document.getElementById('prodDomain').value = product.domain || "";
        
        document.getElementById('prodPrice').value = product.price;
        document.getElementById('prodPages').value = product.pages || "";
        document.getElementById('prodAgeRange').value = product.age_range || "";
        document.getElementById('prodSize').value = product.size || "";
        document.getElementById('prodCharacters').value = product.characters || "";
        document.getElementById('prodCoverType').value = product.cover_type || "Premium Hardcover";
        document.getElementById('prodPrintType').value = product.print_type || "";
        document.getElementById('prodPaperType').value = product.paper_type || "";
        document.getElementById('prodRating').value = product.rating || "";
        document.getElementById('prodReviewsCount').value = product.reviews_count || "";
        document.getElementById('prodDesc').value = product.description || "";
        
        // Name Overlay Populate
        document.getElementById('prodNameText').value = product.name_text || "";
        document.getElementById('prodNameFontFamily').value = product.name_font_family || "PetitCochon";
        document.getElementById('prodNameTop').value = product.name_top || "2%";
        document.getElementById('prodNameColor').value = product.name_color || "#e591ae";
        document.getElementById('prodNameFontSize').value = product.name_font_size || "88px";
        document.getElementById('prodNameRight').value = product.name_right || "50%";
        
        document.getElementById('specialSectionsContainer').innerHTML = "";
        specialSectionIndex = 0;
        
        if (product.special_sections && product.special_sections.length > 0) {
            product.special_sections.forEach(sec => addSpecialSection(sec));
        } else {
            addSpecialSection();
        }
        
        document.getElementById('categoryImagesContainer').innerHTML = "";
        categoryImageIndex = 0;
        
        if (product.category_images && product.category_images.length > 0) {
            product.category_images.forEach(catImg => addCategoryImageSection(catImg));
        } else {
            addCategoryImageSection();
        }

        // Handle images — populate from product.images relation
        const primaryImg = product.images ? product.images.find(i => i.is_main) : null;

        // Primary image — show preview and populate URL field
        const mainPreviewDiv = document.getElementById('mainImagePreview');
        const mainPreviewImg = document.getElementById('mainImagePreviewImg');
        document.getElementById('prodImageFile').value = "";

        if (primaryImg && primaryImg.image_path) {
            const imgUrl = primaryImg.image_path.startsWith('http') ? primaryImg.image_path : window.location.origin + '/' + primaryImg.image_path.replace(/^\//, '');

            mainPreviewImg.src = imgUrl;
            mainPreviewDiv.classList.remove('hidden');

            if (primaryImg.image_path.startsWith('http://') || primaryImg.image_path.startsWith('https://')) {
                document.getElementById('prodImageUrl').value = primaryImg.image_path;
            } else {
                document.getElementById('prodImageUrl').value = "";
            }
        } else {
            mainPreviewImg.src = "";
            mainPreviewDiv.classList.add('hidden');
            document.getElementById('prodImageUrl').value = "";
        }

        // Show replace gallery checkbox option since we're editing
        document.getElementById('replaceGalleryGroup').classList.remove('hidden');
        document.getElementById('replaceGalleryGroup').classList.add('flex');

        // Render existing gallery images with delete buttons
        const galleryImgs = product.images ? product.images.filter(i => !i.is_main) : [];
        const existingContainer = document.getElementById('existingGalleryContainer');
        existingContainer.innerHTML = '';
        document.getElementById('deletedImageIds').value = "[]";
        selectedGalleryFiles = [];
        document.getElementById('galleryPreviewContainer').innerHTML = '';

        if (galleryImgs.length > 0) {
            existingContainer.classList.remove('hidden');
            galleryImgs.forEach(img => {
                const imgUrl = img.image_path.startsWith('http') ? img.image_path : window.location.origin + '/' + img.image_path.replace(/^\//, '');
                const div = document.createElement('div');
                div.className = 'relative w-16 h-20 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0 group';
                div.dataset.imageId = img.id;
                div.innerHTML = `
                    <img src="${imgUrl}" class="w-full h-full object-cover">
                    <button type="button" onclick="markImageDeleted(${img.id}, this.closest('div'))" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity" title="Remove">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                existingContainer.appendChild(div);
            });
        } else {
            existingContainer.classList.add('hidden');
        }

        document.getElementById('prodGalleryUrls').value = "";

        document.getElementById('prodIsBestseller').checked = product.is_bestseller === true || product.is_bestseller === 1;
        document.getElementById('prodIsRecommended').checked = product.is_recommended === true || product.is_recommended === 1;
        document.getElementById('prodStatus').checked = product.status === true || product.status === 1;

        toggleModal('productModal');
    }

    function previewProduct(product) {
        const fallBackImg = "https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=400";

        // Resolve images from product.images relation
        const primaryImg = product.images ? product.images.find(i => i.is_main) : null;
        const galleryImgs = product.images ? product.images.filter(i => !i.is_main) : [];
        // Use url accessor if available, otherwise build from image_path
        const resolveUrl = (img) => img.url
            ? img.url
            : (img.image_path.startsWith('http') ? img.image_path : window.location.origin + '/' + img.image_path.replace(/^\//, ''));
        const primaryUrl = primaryImg ? resolveUrl(primaryImg) : null;
        
        // Ingest texts
        document.getElementById('prevTitle').textContent = product.title;
        document.getElementById('prevImg').src = primaryUrl || fallBackImg;
        document.getElementById('prevPrice').textContent = "$" + parseFloat(product.price).toFixed(2);
        document.getElementById('prevDescription').textContent = product.description || "No description provided for this book yet.";
        document.getElementById('prevRatingScore').textContent = parseFloat(product.rating || 5.0).toFixed(1) + " / 5.0";
        document.getElementById('prevReviewsLink').textContent = `Based on ${(product.reviews_count || 0).toLocaleString()} reviews`;

        // Specs
        document.getElementById('prevSpecPages').textContent = (product.pages || "N/A") + " Pages";
        document.getElementById('prevSpecAges').textContent = product.age_range || "N/A";
        document.getElementById('prevSpecSize').textContent = product.size || "N/A";
        document.getElementById('prevSpecCharacters').textContent = product.characters || "N/A";

        // Crafted Specs
        document.getElementById('prevSpecCover').textContent = product.cover_type || "N/A";
        document.getElementById('prevSpecPrint').textContent = product.print_type || "N/A";
        document.getElementById('prevSpecPaper').textContent = product.paper_type || "N/A";

        // Generate Rating Stars
        const rating = Math.round(product.rating || 5.0);
        let starsHtml = "";
        for(let i=1; i<=5; i++) {
            starsHtml += i <= rating ? "★" : "☆";
        }
        document.getElementById('prevStars').textContent = starsHtml;

        // Generate Badges
        let badgesHtml = "";
        if (product.is_bestseller) {
            badgesHtml += `<span class="px-3.5 py-1.5 bg-amber-500 text-white text-xs font-black rounded-lg uppercase tracking-wider shadow-sm">★ Best Seller</span>`;
        }
        if (product.is_recommended) {
            badgesHtml += `<span class="px-3.5 py-1.5 bg-rose-500 text-white text-xs font-black rounded-lg uppercase tracking-wider shadow-sm">❤ Recommended Gift</span>`;
        }
        document.getElementById('prevBadges').innerHTML = badgesHtml;

        // Gallery Thumbnails Removed

        toggleModal('previewModal');
    }

    function confirmDelete(deleteUrl, messageText) {
        Swal.fire({
            title: 'Are you sure?',
            text: messageText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5', // indigo-600
            cancelButtonColor: '#ef4444', // red-500
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            background: '#ffffff',
            borderRadius: '1rem',
            customClass: {
                popup: 'rounded-2xl border border-gray-100 shadow-xl',
                confirmButton: 'px-5 py-2.5 rounded-xl text-white font-semibold transition-all hover:scale-105',
                cancelButton: 'px-5 py-2.5 rounded-xl text-white font-semibold transition-all hover:scale-105'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm');
                form.action = deleteUrl;
                form.submit();
            }
        });
    }

    // --- Search Filter ---
    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.product-row');
        rows.forEach(row => {
            const title = row.getAttribute('data-title');
            const category = row.getAttribute('data-category');
            if (title.includes(query) || category.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }


    // --- Main Image Preview ---
    function previewMainImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('mainImagePreviewImg').src = e.target.result;
            document.getElementById('mainImagePreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function clearMainImage() {
        document.getElementById('prodImageFile').value = '';
        document.getElementById('mainImagePreviewImg').src = '';
        document.getElementById('mainImagePreview').classList.add('hidden');
    }

    // --- Special Section Builder ---
    function addSpecialSection(data = null) {
        const container = document.getElementById('specialSectionsContainer');
        const idx = specialSectionIndex++;
        
        let existingIdHtml = '';
        let existingImageHtml = '';
        let previewHtml = '';
        
        if (data && data.id) {
            existingIdHtml = `<input type="hidden" name="special_sections[${idx}][id]" value="${data.id}">`;
        }
        if (data && data.image) {
            existingImageHtml = `<input type="hidden" name="special_sections[${idx}][existing_image]" value="${data.image}">`;
            const imgUrl = data.image.startsWith('http') ? data.image : window.location.origin + '/' + data.image.replace(/^\//, '');
            previewHtml = `
                <div id="specialImagePreview_${idx}" class="mt-3 relative w-full h-40 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                    <img id="specialImagePreviewImg_${idx}" src="${imgUrl}" class="w-full h-full object-cover">
                    <button type="button" onclick="clearDynamicSpecialImage(${idx})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center" title="Remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
        } else {
            previewHtml = `
                <div id="specialImagePreview_${idx}" class="hidden mt-3 relative w-full h-40 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                    <img id="specialImagePreviewImg_${idx}" src="" class="w-full h-full object-cover">
                    <button type="button" onclick="clearDynamicSpecialImage(${idx})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center" title="Remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
        }

        const sectionHtml = `
            <div id="special_section_row_${idx}" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-100 rounded-2xl bg-white relative group">
                <button type="button" onclick="removeSpecialSection(${idx})" class="absolute -top-3 -right-3 w-8 h-8 bg-red-50 hover:bg-red-100 text-red-500 rounded-full flex items-center justify-center shadow-sm opacity-0 group-hover:opacity-100 transition-all border border-red-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                ${existingIdHtml}
                ${existingImageHtml}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Subtitle</label>
                        <input type="text" name="special_sections[${idx}][subtitle]" value="${data ? (data.subtitle || '') : ''}" placeholder="e.g. WATCH THEIR FACE LIGHT UP" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                        <input type="text" name="special_sections[${idx}][title]" value="${data ? (data.title || '') : ''}" placeholder="e.g. Watch their face light up" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="special_sections[${idx}][description]" rows="4" placeholder="There's nothing quite like seeing a child discover..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">${data ? (data.description || '') : ''}</textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Section Image</label>
                    <input type="file" name="special_sections[${idx}][image]" id="prodSpecialImageFile_${idx}" accept="image/*" onchange="previewDynamicSpecialImage(event, ${idx})" class="text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 w-full border border-gray-200 p-1.5 rounded-xl bg-gray-50">
                    ${previewHtml}
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', sectionHtml);
    }

    function removeSpecialSection(idx) {
        document.getElementById(`special_section_row_${idx}`).remove();
    }

    function previewDynamicSpecialImage(event, idx) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(`specialImagePreviewImg_${idx}`).src = e.target.result;
            document.getElementById(`specialImagePreview_${idx}`).classList.remove('hidden');
            // Remove existing image hidden input if they upload a new one
            const existingInput = document.querySelector(`input[name="special_sections[${idx}][existing_image]"]`);
            if(existingInput) existingInput.remove();
        };
        reader.readAsDataURL(file);
    }

    function clearDynamicSpecialImage(idx) {
        document.getElementById(`prodSpecialImageFile_${idx}`).value = '';
        document.getElementById(`specialImagePreviewImg_${idx}`).src = '';
        document.getElementById(`specialImagePreview_${idx}`).classList.add('hidden');
        // Remove existing image hidden input
        const existingInput = document.querySelector(`input[name="special_sections[${idx}][existing_image]"]`);
        if(existingInput) existingInput.remove();
    }

    // --- Multi-Image Gallery Preview & Delete ---
    let selectedGalleryFiles = [];

    function previewGalleryImages(event) {
        const files = Array.from(event.target.files);
        files.forEach(file => selectedGalleryFiles.push(file));
        renderGalleryPreview();
        // Reset input so same files can be picked again
        event.target.value = '';
    }

    function renderGalleryPreview() {
        const container = document.getElementById('galleryPreviewContainer');
        container.innerHTML = '';
        selectedGalleryFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative w-16 h-20 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0 group';
                div.setAttribute('data-index', index);
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <button type="button" onclick="removeGalleryImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity" title="Remove">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeGalleryImage(index) {
        selectedGalleryFiles.splice(index, 1);
        renderGalleryPreview();
    }

    function markImageDeleted(imageId, element) {
        let deleted = JSON.parse(document.getElementById('deletedImageIds').value || "[]");
        deleted.push(imageId);
        document.getElementById('deletedImageIds').value = JSON.stringify(deleted);
        element.remove();
    }

    // --- Category Images Builder ---
    function addCategoryImageSection(data = null) {
        const container = document.getElementById('categoryImagesContainer');
        const idx = categoryImageIndex++;
        
        let existingIdHtml = '';
        let existingImageHtml = '';
        let previewHtml = '';
        
        if (data && data.id) {
            existingIdHtml = `<input type="hidden" name="category_images[${idx}][id]" value="${data.id}">`;
        }
        if (data && data.image_path) {
            existingImageHtml = `<input type="hidden" name="category_images[${idx}][existing_image]" value="${data.image_path}">`;
            const imgUrl = data.image_path.startsWith('http') ? data.image_path : window.location.origin + '/' + data.image_path.replace(/^\//, '');
            previewHtml = `
                <div id="categoryImagePreview_${idx}" class="mt-3 relative w-full h-40 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                    <img id="categoryImagePreviewImg_${idx}" src="${imgUrl}" class="w-full h-full object-cover">
                    <button type="button" onclick="clearDynamicCategoryImage(${idx})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center" title="Remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
        } else {
            previewHtml = `
                <div id="categoryImagePreview_${idx}" class="hidden mt-3 relative w-full h-40 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                    <img id="categoryImagePreviewImg_${idx}" src="" class="w-full h-full object-cover">
                    <button type="button" onclick="clearDynamicCategoryImage(${idx})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center" title="Remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
        }

        // Build Category Options
        let categoryOptions = '<option value="">-- Select Category --</option>';
        categoriesData.forEach(cat => {
            const isSelected = data && data.category_id == cat.id ? 'selected' : '';
            categoryOptions += `<option value="${cat.id}" data-has-sub="${cat.subcategories && cat.subcategories.length > 0 ? 'true' : 'false'}" ${isSelected}>${cat.name}</option>`;
        });

        const sectionHtml = `
            <div id="category_image_row_${idx}" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-pink-100 rounded-2xl bg-pink-50/30 relative group">
                <button type="button" onclick="removeCategoryImageSection(${idx})" class="absolute -top-3 -right-3 w-8 h-8 bg-red-50 hover:bg-red-100 text-red-500 rounded-full flex items-center justify-center shadow-sm opacity-0 group-hover:opacity-100 transition-all border border-red-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                ${existingIdHtml}
                ${existingImageHtml}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                        <select name="category_images[${idx}][category_id]" id="catImgCategory_${idx}" onchange="handleCategoryRowChange(${idx})" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold" required>
                            ${categoryOptions}
                        </select>
                    </div>
                    <div id="catImgSubContainer_${idx}" class="hidden">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Subcategory *</label>
                        <select name="category_images[${idx}][subcategory_id]" id="catImgSubcategory_${idx}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                            <option value="">-- Select Subcategory --</option>
                            ${subcategoriesData.map(sub => `<option value="${sub.id}" data-parent="${sub.category_id}">${sub.name}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Option Type</label>
                        <select name="category_images[${idx}][option_type]" id="catImgOptionType_${idx}" onchange="handleOptionTypeChange(${idx})" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-semibold">
                            <option value="box"   ${(data && data.option_type === 'box')   || !data ? 'selected' : ''}>Box</option>
                            <option value="drop"  ${data && data.option_type === 'drop'  ? 'selected' : ''}>Drop</option>
                            <option value="color" ${data && data.option_type === 'color' ? 'selected' : ''}>Color</option>
                        </select>
                    </div>
                    <div id="catImgColorContainer_${idx}" class="${data && data.option_type === 'color' ? '' : 'hidden'}">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" id="catImgColorPicker_${idx}" value="${data && data.option_value ? data.option_value : '#e591ae'}"
                                oninput="syncColorValue(${idx})"
                                class="w-12 h-12 rounded-xl border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="category_images[${idx}][option_value]" id="catImgColorValue_${idx}"
                                value="${data && data.option_value ? data.option_value : '#e591ae'}"
                                oninput="syncColorPicker(${idx})"
                                placeholder="#e591ae"
                                class="flex-1 px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-mono text-sm">
                            <div id="catImgColorPreview_${idx}" class="w-10 h-10 rounded-xl border border-gray-200 shadow-inner" style="background:${data && data.option_value ? data.option_value : '#e591ae'}"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Section Image</label>
                    <input type="file" name="category_images[${idx}][image]" id="catImgFile_${idx}" accept="image/*" onchange="previewDynamicCategoryImage(event, ${idx})" class="text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 w-full border border-gray-200 p-1.5 rounded-xl bg-white">
                    ${previewHtml}
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', sectionHtml);

        // Pre-fill subcategory logic if editing
        if (data && data.category_id) {
            handleCategoryRowChange(idx);
            if (data.subcategory_id) {
                document.getElementById(`catImgSubcategory_${idx}`).value = data.subcategory_id;
            }
        }
    }

    function removeCategoryImageSection(idx) {
        document.getElementById(`category_image_row_${idx}`).remove();
    }

    function handleOptionTypeChange(idx) {
        const type = document.getElementById(`catImgOptionType_${idx}`).value;
        const colorContainer = document.getElementById(`catImgColorContainer_${idx}`);
        if (type === 'color') {
            colorContainer.classList.remove('hidden');
        } else {
            colorContainer.classList.add('hidden');
            // clear option_value when not color
            const valInput = document.getElementById(`catImgColorValue_${idx}`);
            if (valInput) valInput.value = '';
        }
    }

    function syncColorValue(idx) {
        const hex = document.getElementById(`catImgColorPicker_${idx}`).value;
        document.getElementById(`catImgColorValue_${idx}`).value = hex;
        document.getElementById(`catImgColorPreview_${idx}`).style.background = hex;
    }

    function syncColorPicker(idx) {
        const val = document.getElementById(`catImgColorValue_${idx}`).value;
        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            document.getElementById(`catImgColorPicker_${idx}`).value = val;
            document.getElementById(`catImgColorPreview_${idx}`).style.background = val;
        }
    }

    function handleCategoryRowChange(idx) {
        const parentSelect = document.getElementById(`catImgCategory_${idx}`);
        const selectedOption = parentSelect.options[parentSelect.selectedIndex];
        const hasSub = selectedOption ? selectedOption.getAttribute('data-has-sub') === 'true' : false;
        const parentId = parentSelect.value;
        const subContainer = document.getElementById(`catImgSubContainer_${idx}`);
        const subSelect = document.getElementById(`catImgSubcategory_${idx}`);

        if (!parentId) {
            subContainer.classList.add('hidden');
            subSelect.required = false;
            subSelect.value = '';
            return;
        }

        if (hasSub) {
            subContainer.classList.remove('hidden');
            subSelect.required = true;
            Array.from(subSelect.options).forEach(opt => {
                if (opt.value === '') {
                    opt.style.display = '';
                } else if (opt.getAttribute('data-parent') == parentId) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                }
            });
            subSelect.value = '';
        } else {
            subContainer.classList.add('hidden');
            subSelect.required = false;
            subSelect.value = '';
        }
    }

    function previewDynamicCategoryImage(event, idx) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(`categoryImagePreviewImg_${idx}`).src = e.target.result;
            document.getElementById(`categoryImagePreview_${idx}`).classList.remove('hidden');
            const existingInput = document.querySelector(`input[name="category_images[${idx}][existing_image]"]`);
            if(existingInput) existingInput.remove();
        };
        reader.readAsDataURL(file);
    }

    function clearDynamicCategoryImage(idx) {
        document.getElementById(`catImgFile_${idx}`).value = '';
        document.getElementById(`categoryImagePreviewImg_${idx}`).src = '';
        document.getElementById(`categoryImagePreview_${idx}`).classList.add('hidden');
        const existingInput = document.querySelector(`input[name="category_images[${idx}][existing_image]"]`);
        if(existingInput) existingInput.remove();
    }

    // --- Form Submit via fetch ---
    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('[type="submit"]');
        const originalText = submitBtn.textContent;

        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        // Build FormData from existing form fields
        const formData = new FormData(form);

        // Remove any broken gallery_files entries from FormData (may be empty)
        formData.delete('gallery_files[]');

        // Append each file from our tracked array
        selectedGalleryFiles.forEach(file => {
            formData.append('gallery_files[]', file);
        });

        // Determine method override
        const method = document.getElementById('formMethod').value || 'POST';
        if (method === 'PUT' || method === 'PATCH') {
            formData.set('_method', method);
        }

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => {
            // follow redirect (fetch auto-follows, response.url is final URL)
            window.location.href = response.url;
        })
        .catch(err => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
        });
    });

    // --- Status Toggle SweetAlert ---
    function toggleProductStatus(productId, currentStatus) {
        const actionText = currentStatus ? "deactivate" : "activate";
        const url = `/admin/products/${productId}/status`;

        Swal.fire({
            title: `Want to ${actionText}?`,
            text: `This will ${actionText} the book listing.`,
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

    // --- Name Overlay Configuration Modal Logic ---
    function openNameOverlayModal() {
        // Sync values from hidden inputs to modal inputs
        document.getElementById('configNameText').value = document.getElementById('prodNameText').value || '';
        document.getElementById('configNameFont').value = document.getElementById('prodNameFontFamily').value || 'PetitCochon';
        
        let colorVal = document.getElementById('prodNameColor').value || '#e591ae';
        document.getElementById('configNameColor').value = colorVal;
        
        let fontSizeVal = (document.getElementById('prodNameFontSize').value || '88px').replace('px', '');
        document.getElementById('configNameFontSize').value = fontSizeVal;
        
        let topVal = (document.getElementById('prodNameTop').value || '2%').replace('%', '');
        document.getElementById('configNameTop').value = topVal;
        
        let rightVal = (document.getElementById('prodNameRight').value || '50%').replace('%', '');
        document.getElementById('configNameRight').value = rightVal;

        // Sync main image preview to overlay preview
        let mainImgSrc = document.getElementById('mainImagePreviewImg').src;
        let overlayImg = document.getElementById('overlayPreviewImage');
        if(mainImgSrc && !mainImgSrc.endsWith('/')) {
            overlayImg.src = mainImgSrc;
        } else {
            overlayImg.src = 'https://via.placeholder.com/650x650.png?text=Upload+Main+Cover+Image';
        }

        updateOverlayPreview();
        
        const modal = document.getElementById('nameOverlayModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.querySelector('.transform').classList.add('scale-100', 'opacity-100');
            modal.querySelector('.transform').classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeNameOverlayModal() {
        const modal = document.getElementById('nameOverlayModal');
        modal.querySelector('.transform').classList.remove('scale-100', 'opacity-100');
        modal.querySelector('.transform').classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function updateOverlayPreview() {
        let text = document.getElementById('configNameText').value || 'Your Name';
        let font = document.getElementById('configNameFont').value;
        let color = document.getElementById('configNameColor').value;
        let fontSize = document.getElementById('configNameFontSize').value + 'px';
        let top = document.getElementById('configNameTop').value + '%';
        let right = document.getElementById('configNameRight').value + '%';

        // Update displays
        document.getElementById('colorHexDisplay').textContent = color;
        document.getElementById('fontSizeDisplay').textContent = fontSize;
        document.getElementById('topDisplay').textContent = top;
        document.getElementById('rightDisplay').textContent = right;

        // Apply to preview text
        let previewText = document.getElementById('overlayPreviewText');
        previewText.textContent = text;
        previewText.style.fontFamily = font;
        previewText.style.color = color;
        previewText.style.fontSize = fontSize;
        previewText.style.top = top;
        previewText.style.right = right;
    }

    function saveNameOverlayConfig() {
        // Save back to hidden inputs
        document.getElementById('prodNameText').value = document.getElementById('configNameText').value;
        document.getElementById('prodNameFontFamily').value = document.getElementById('configNameFont').value;
        document.getElementById('prodNameColor').value = document.getElementById('configNameColor').value;
        document.getElementById('prodNameFontSize').value = document.getElementById('configNameFontSize').value + 'px';
        document.getElementById('prodNameTop').value = document.getElementById('configNameTop').value + '%';
        document.getElementById('prodNameRight').value = document.getElementById('configNameRight').value + '%';
        
        closeNameOverlayModal();
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fade-in {
        animation: fadeIn 0.25s ease-out forwards;
    }
</style>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('productModal').classList.remove('hidden');
    });
</script>
@endif

@endsection
