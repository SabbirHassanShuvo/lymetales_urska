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
                <input type="text" id="searchInput" placeholder="Search books..." class="pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
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
            <table id="productsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="px-5 py-3.5">Book</th>
                        <th class="px-5 py-3.5">Domain</th>
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
                                        {{ $product->domain === 'https://lymetales.com/' ? 'bg-violet-50 text-violet-700 border border-violet-100' : 'bg-sky-50 text-sky-700 border border-sky-100' }}">
                                        {{ $product->domain === 'https://lymetales.com/' ? 'LymeTales' : 'BeeBook' }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Category --}}
                            <!-- <td class="px-5 py-3.5">
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
                            </td> -->

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

        <div id="tablePagination" class="px-5 py-4 border-t border-gray-100"></div>
    </div>
</div>

<!-- Add/Edit Product Modal -->
<div id="productModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleModal('productModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Large Modal Panel -->
        <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full border border-gray-100">
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
                                <select name="domain" id="prodDomain" onchange="loadDomainCategories(this.value)" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                    <option value="">— No specific domain —</option>
                                    <option value="https://lymetales.com/">LymeTales</option>
                                    <option value="https://beebook.si/">BeeBook</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                                <select name="type" id="prodType" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                    <option value="">— No specific type —</option>
                                    <option value="newborn">novorojenček</option>
                                    <option value="kids">otroci</option>
                                    <option value="adult">odrasli</option>
                                </select>
                            </div>

                            <div id="prodCategoryWrapper">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                                <select name="site_category_id" id="prodCategory" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                    <option value="">— No category —</option>
                                    @foreach($siteCategories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
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
                                <input type="hidden" name="featured_image_id" id="featuredImageId" value="">
                                <p id="featuredHint" class="text-xs text-indigo-600 font-semibold mt-1 hidden"></p>
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

                    {{--
                    ╔══════════════════════════════════════════════════════════╗
                    ║  Book Category Images — DISABLED (replaced by           ║
                    ║  Customization Steps below)                             ║
                    ╚══════════════════════════════════════════════════════════╝
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
                        </div>
                    </div>
                    --}}

                    {{-- ── Book Pictures ────────────────────────────────────────── --}}
                    <div class="pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-md font-bold text-gray-800">Book Pictures (Pages)</h4>
                            <button type="button" onclick="toggleModal('bookPicturesModal')" class="px-4 py-2 bg-sky-600 text-white text-sm font-semibold rounded-lg hover:bg-sky-700 transition-all flex items-center shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Manage Book Pictures
                            </button>
                        </div>
                    </div>

                    {{-- ── Customization Steps ───────────────────────────────── --}}
                    <div class="pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h4 class="text-sm font-bold text-gray-800">Customization Steps</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Gender → Boy / Girl → Hair Color → Red / Black</p>
                            </div>
                            <button type="button" onclick="addCustomizationStep()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add Step
                            </button>
                        </div>
                        <div id="customizationStepsContainer" class="space-y-5"></div>
                    </div>
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

<!-- Book Pictures Modal (Outside Form) -->
<div id="bookPicturesModal" class="fixed inset-0 z-[60] overflow-y-auto hidden animate-fade-in" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity" aria-hidden="true" onclick="toggleModal('bookPicturesModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-gray-100 relative">
            <!-- Close btn -->
            <button type="button" onclick="toggleModal('bookPicturesModal')" class="absolute top-6 right-6 z-10 w-10 h-10 bg-white hover:bg-gray-100 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 transition-all focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="px-8 py-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900">Manage Book Pictures</h3>
                <p class="text-sm text-gray-500 mt-1">Add internal pages of the book. These will be saved when you save the product.</p>
            </div>
            <div class="p-8 max-h-[60vh] overflow-y-auto">
                <div class="flex justify-end mb-4">
                    <button type="button" onclick="addBookPictureSection()" class="px-3 py-1.5 bg-sky-50 text-sky-700 text-sm font-semibold rounded-lg hover:bg-sky-100 transition-all flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Picture
                    </button>
                </div>
                <div id="bookPicturesContainer" class="space-y-4">
                    <!-- Dynamic book picture fields appended here -->
                </div>
            </div>
            <div class="bg-gray-50 px-8 py-4 flex justify-end">
                <button type="button" onclick="toggleModal('bookPicturesModal')" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all">
                    Done
                </button>
            </div>
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
                        <select id="configNameFont" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-semibold" onchange="updateOverlayPreview()">
                            <option value="PetitCochon">PetitCochon</option>
                            <option value="Arial, sans-serif">Arial</option>
                            <option value="'Courier New', Courier, monospace">Courier New</option>
                            <option value="'Times New Roman', Times, serif">Times New Roman</option>
                            <option value="'Comic Sans MS', cursive, sans-serif">Comic Sans MS</option>
                            <option value="Impact, fantasy">Impact</option>
                            <option value="Pacifico">Pacifico</option>
                            <option value="Dancing Script">Dancing Script</option>
                            <option value="Satisfy">Satisfy</option>
                            <option value="Lobster">Lobster</option>
                            <option value="Caveat">Caveat</option>
                            <option value="Permanent Marker">Permanent Marker</option>
                            <option value="Architects Daughter">Architects Daughter</option>
                            <option value="Indie Flower">Indie Flower</option>
                            <option value="Shadows Into Light">Shadows Into Light</option>
                            <option value="Patrick Hand">Patrick Hand</option>
                            <option value="Kalam">Kalam</option>
                            <option value="Amatic SC">Amatic SC</option>
                            <option value="Courgette">Courgette</option>
                            <option value="Great Vibes">Great Vibes</option>
                            <option value="Sacramento">Sacramento</option>
                            <option value="Yellowtail">Yellowtail</option>
                            <option value="Righteous">Righteous</option>
                            <option value="Fredoka One">Fredoka One</option>
                            <option value="Boogaloo">Boogaloo</option>
                            <option value="Chewy">Chewy</option>
                            <option value="Josefin Sans">Josefin Sans</option>
                            <option value="Raleway">Raleway</option>
                            <option value="Cinzel">Cinzel</option>
                            <option value="Playfair Display">Playfair Display</option>
                            <option value="Abril Fatface">Abril Fatface</option>
                            <option value="Alfa Slab One">Alfa Slab One</option>
                            <option value="Bangers">Bangers</option>
                            <option value="Black Han Sans">Black Han Sans</option>
                            <option value="Bree Serif">Bree Serif</option>
                            <option value="Cabin Sketch">Cabin Sketch</option>
                            <option value="Changa One">Changa One</option>
                            <option value="Cinzel Decorative">Cinzel Decorative</option>
                            <option value="Comfortaa">Comfortaa</option>
                            <option value="Cookie">Cookie</option>
                            <option value="Covered By Your Grace">Covered By Your Grace</option>
                            <option value="Crafty Girls">Crafty Girls</option>
                            <option value="Creepster">Creepster</option>
                            <option value="Damion">Damion</option>
                            <option value="Delius">Delius</option>
                            <option value="Diplomata SC">Diplomata SC</option>
                            <option value="Dynalight">Dynalight</option>
                            <option value="Fascinate">Fascinate</option>
                            <option value="Finger Paint">Finger Paint</option>
                            <option value="Fondamento">Fondamento</option>
                            <option value="Fruktur">Fruktur</option>
                            <option value="Galada">Galada</option>
                            <option value="Galdeano">Galdeano</option>
                            <option value="Geostar">Geostar</option>
                            <option value="Give You Glory">Give You Glory</option>
                            <option value="Gloria Hallelujah">Gloria Hallelujah</option>
                            <option value="Handlee">Handlee</option>
                            <option value="Henny Penny">Henny Penny</option>
                            <option value="Homemade Apple">Homemade Apple</option>
                            <option value="Hurricane">Hurricane</option>
                            <option value="Just Another Hand">Just Another Hand</option>
                            <option value="Kaushan Script">Kaushan Script</option>
                            <option value="Kranky">Kranky</option>
                            <option value="Kristi">Kristi</option>
                            <option value="La Belle Aurore">La Belle Aurore</option>
                            <option value="Lacquer">Lacquer</option>
                            <option value="League Script">League Script</option>
                            <option value="Leckerli One">Leckerli One</option>
                            <option value="Lilita One">Lilita One</option>
                            <option value="Limelight">Limelight</option>
                            <option value="Loved by the King">Loved by the King</option>
                            <option value="Luckiest Guy">Luckiest Guy</option>
                            <option value="Mystery Quest">Mystery Quest</option>
                            <option value="Niconne">Niconne</option>
                            <option value="Nothing You Could Do">Nothing You Could Do</option>
                            <option value="Offside">Offside</option>
                            <option value="Oregano">Oregano</option>
                            <option value="Oswald">Oswald</option>
                            <option value="Philosopher">Philosopher</option>
                            <option value="Pinyon Script">Pinyon Script</option>
                            <option value="Plaster">Plaster</option>
                            <option value="Press Start 2P">Press Start 2P</option>
                            <option value="Princess Sofia">Princess Sofia</option>
                            <option value="Puritan">Puritan</option>
                            <option value="Qwigley">Qwigley</option>
                            <option value="Rancho">Rancho</option>
                            <option value="Reenie Beanie">Reenie Beanie</option>
                            <option value="Rock Salt">Rock Salt</option>
                            <option value="Rouge Script">Rouge Script</option>
                            <option value="Ruge Boogie">Ruge Boogie</option>
                            <option value="Ruluko">Ruluko</option>
                            <option value="Ruslan Display">Ruslan Display</option>
                            <option value="Sail">Sail</option>
                            <option value="Schoolbell">Schoolbell</option>
                            <option value="Sevillana">Sevillana</option>
                            <option value="Shrikhand">Shrikhand</option>
                            <option value="Sigmar One">Sigmar One</option>
                            <option value="Special Elite">Special Elite</option>
                            <option value="Stalemate">Stalemate</option>
                            <option value="Stint Ultra Expanded">Stint Ultra Expanded</option>
                            <option value="Sunshiney">Sunshiney</option>
                            <option value="Supermercado One">Supermercado One</option>
                            <option value="Syne Mono">Syne Mono</option>
                            <option value="Taprom">Taprom</option>
                            <option value="Tinos">Tinos</option>
                            <option value="Trade Winds">Trade Winds</option>
                            <option value="Uncial Antiqua">Uncial Antiqua</option>
                            <option value="Vampiro One">Vampiro One</option>
                            <option value="Vast Shadow">Vast Shadow</option>
                            <option value="Vibur">Vibur</option>
                            <option value="Voltaire">Voltaire</option>
                            <option value="Walter Turncoat">Walter Turncoat</option>
                            <option value="Warnes">Warnes</option>
                            <option value="Wellfleet">Wellfleet</option>
                            <option value="Wendy One">Wendy One</option>
                            <option value="Wire One">Wire One</option>
                            <option value="Zeyada">Zeyada</option>
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
    const categoriesData    = @json($categories);
    const subcategoriesData = @json($subcategories);  // L1 subcategories with children[]
    const siteCategoriesData = @json($siteCategories); // site_categories with subcategories[]

    /**
     * Build <option> HTML for subcategory select.
     * L1 shown normally, L2 children indented under their parent.
     * If catId is given, filter to that category only.
     */
    function buildSubcategoryOptions(catId = null, selectedId = null) {
        let html = '';
        subcategoriesData.forEach(l1 => {
            if (catId && l1.category_id != catId) return;
            const l1Sel = selectedId && String(l1.id) === String(selectedId) ? 'selected' : '';
            html += `<option value="${l1.id}" data-parent="${l1.category_id}" data-level="1" ${l1Sel}>${l1.name}</option>`;
            if (l1.children && l1.children.length > 0) {
                l1.children.forEach(l2 => {
                    if (catId && l2.category_id != catId) return;
                    const l2Sel = selectedId && String(l2.id) === String(selectedId) ? 'selected' : '';
                    html += `<option value="${l2.id}" data-parent="${l2.category_id}" data-l1parent="${l2.parent_id}" data-level="2" ${l2Sel}>&nbsp;&nbsp;&nbsp;↳ ${l2.name}</option>`;
                });
            }
        });
        return html;
    }
    let specialSectionIndex = 0;
    let categoryImageIndex = 0;
    let bookPictureIndex = 0;

    // ── Gallery Featured Image ────────────────────────────────────────────────

    /**
     * Mark an existing gallery image as featured.
     * The selected image's ID is stored in the hidden input and will be
     * sent to the server so it appears first in the API response.
     */
    function setFeaturedGalleryImage(imageId, clickedDiv) {
        // Clear all existing featured styles
        document.querySelectorAll('#existingGalleryContainer > div, #galleryPreviewContainer > div').forEach(d => {
            d.classList.remove('border-indigo-500');
            d.classList.add('border-gray-200');
            const badge = d.querySelector('.featured-badge');
            if (badge) badge.remove();
        });

        const current = document.getElementById('featuredImageId').value;
        if (String(current) === String(imageId)) {
            // Clicking the already-featured image deselects it
            document.getElementById('featuredImageId').value = '';
            return;
        }

        // Set new featured
        document.getElementById('featuredImageId').value = imageId;
        clickedDiv.classList.remove('border-gray-200');
        clickedDiv.classList.add('border-indigo-500');
        const badge = document.createElement('span');
        badge.className = 'featured-badge absolute bottom-0 left-0 right-0 bg-indigo-500 text-white text-[9px] font-bold text-center py-0.5';
        badge.textContent = 'Featured';
        clickedDiv.appendChild(badge);
        document.getElementById('featuredHint').classList.remove('hidden');
    }

    // ── Domain → Category → Subcategory helpers ──────────────────────────────

    /**
     * When domain changes, filter category options to show only matching ones.
     * (Currently all categories are shown regardless of domain — extend if needed)
     */
    function loadDomainCategories(domain) {
        // Category is shown regardless of domain; just reset subcategory
        document.getElementById('prodCategory').value = '';
        document.getElementById('prodSubcategoryWrapper').classList.add('hidden');
        document.getElementById('prodSubcategory').innerHTML = '<option value="">— No subcategory —</option>';
    }

    /**
     * When a category is selected, load its L1 subcategories.
     * If none exist, hide the subcategory field.
     */
    function loadProductSubcategories(catId, selectedSubId = null) {
        const wrapper = document.getElementById('prodSubcategoryWrapper');
        const sel     = document.getElementById('prodSubcategory');
        sel.innerHTML = '<option value="">— No subcategory —</option>';

        if (!catId) {
            wrapper.classList.add('hidden');
            return;
        }

        const cat = siteCategoriesData.find(c => String(c.id) === String(catId));
        const subs = cat ? (cat.subcategories || []) : [];

        if (subs.length === 0) {
            wrapper.classList.add('hidden');
            return;
        }

        subs.forEach(sub => {
            const sel_attr = selectedSubId && String(sub.id) === String(selectedSubId) ? 'selected' : '';
            sel.innerHTML += `<option value="${sub.id}" ${sel_attr}>${sub.name}</option>`;
        });

        wrapper.classList.remove('hidden');
    }

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
        // Reset product category/subcategory
        if (document.getElementById('prodCategory')) document.getElementById('prodCategory').value = '';
        if (document.getElementById('prodSubcategoryWrapper')) {
            document.getElementById('prodSubcategoryWrapper').classList.add('hidden');
            document.getElementById('prodSubcategory').innerHTML = '<option value="">— No subcategory —</option>';
        }
        // Reset featured image
        document.getElementById('featuredImageId').value = '';
        document.getElementById('featuredHint').classList.add('hidden');
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

        const catImgCont = document.getElementById('categoryImagesContainer');
        if (catImgCont) {
            catImgCont.innerHTML = "";
            categoryImageIndex = 0;
            addCategoryImageSection();
        }

        const bookPicCont = document.getElementById('bookPicturesContainer');
        if (bookPicCont) {
            bookPicCont.innerHTML = "";
            bookPictureIndex = 0;
            addBookPictureSection();
        }

        // Reset customization steps
        resetCustomizationSteps();

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

        // Reset and pre-fill customization steps from server
        resetCustomizationSteps();
        prefillCustomizationSteps(product.id);

        document.getElementById('prodTitle').value = product.title;
        if (document.getElementById('prodDomain')) document.getElementById('prodDomain').value = product.domain || "";
        if (document.getElementById('prodType')) document.getElementById('prodType').value = product.type || "";

        // Populate Site Category
        if (document.getElementById('prodCategory')) {
            document.getElementById('prodCategory').value = product.site_category_id || '';
        }

        // Populate featured image
        document.getElementById('featuredImageId').value = product.featured_image_id || '';
        document.getElementById('featuredHint').classList.remove('hidden');

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
        document.getElementById('specialSectionsContainer').innerHTML = "";
        specialSectionIndex = 0;
        if (product.special_sections && product.special_sections.length > 0) {
            product.special_sections.forEach(section => {
                addSpecialSection(section);
            });
        } else {
            addSpecialSection();
        }

        document.getElementById('bookPicturesContainer').innerHTML = "";
        bookPictureIndex = 0;
        if (product.book_images && product.book_images.length > 0) {
            product.book_images.forEach(img => {
                addBookPictureSection(img);
            });
        } else {
            addBookPictureSection();
        }

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
        
        const catImgContEdit = document.getElementById('categoryImagesContainer');
        if (catImgContEdit) {
            catImgContEdit.innerHTML = "";
            categoryImageIndex = 0;
            if (product.category_images && product.category_images.length > 0) {
                product.category_images.forEach(catImg => addCategoryImageSection(catImg));
            } else {
                addCategoryImageSection();
            }
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
                div.className = 'relative w-16 h-20 bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-200 flex-shrink-0 group cursor-pointer';
                div.dataset.imageId = img.id;
                const isFeatured = product.featured_image_id && String(img.id) === String(product.featured_image_id);
                if (isFeatured) div.classList.replace('border-gray-200', 'border-indigo-500');
                div.innerHTML = `
                    <img src="${imgUrl}" class="w-full h-full object-cover">
                    ${isFeatured ? '<span class="absolute bottom-0 left-0 right-0 bg-indigo-500 text-white text-[9px] font-bold text-center py-0.5">Featured</span>' : ''}
                    <button type="button" onclick="markImageDeleted(${img.id}, this.closest('div'))" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10" title="Remove">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                div.addEventListener('click', function(e) {
                    if (e.target.closest('button')) return;
                    setFeaturedGalleryImage(img.id, div);
                });
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

    // ── Book Pictures Logic ────────────────────────────────────────────────

    function addBookPictureSection(data = null) {
        const container = document.getElementById('bookPicturesContainer');
        if (!container) return;
        const idx = bookPictureIndex++;
        
        let existingIdHtml = '';
        let existingImageHtml = '';
        let previewHtml = '';

        if (data && data.id) {
            existingIdHtml = `<input type="hidden" name="book_images[${idx}][id]" value="${data.id}" form="productForm">`;
        }

        if (data && data.image_path) {
            existingImageHtml = `<input type="hidden" name="book_images[${idx}][existing_image]" value="${data.image_path}" form="productForm">`;
            let src = data.image_path.startsWith('http') ? data.image_path : '/' + data.image_path.replace(/^\//, '');
            previewHtml = `
                <div id="bookImagePreview_${idx}" class="mt-3 relative w-32 h-32 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                    <img id="bookImagePreviewImg_${idx}" src="${src}" class="w-full h-full object-cover">
                    <button type="button" onclick="clearBookImage(${idx})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center" title="Remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
        } else {
            previewHtml = `
                <div id="bookImagePreview_${idx}" class="hidden mt-3 relative w-32 h-32 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                    <img id="bookImagePreviewImg_${idx}" src="" class="w-full h-full object-cover">
                    <button type="button" onclick="clearBookImage(${idx})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center" title="Remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            `;
        }

        const sectionHtml = `
            <div id="book_image_row_${idx}" class="flex items-start gap-4 p-4 border border-sky-100 rounded-2xl bg-sky-50/30 relative group">
                <div class="flex-1">
                    ${existingIdHtml}
                    ${existingImageHtml}
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Page Image</label>
                    <input type="file" name="book_images[${idx}][image]" id="bookImgFile_${idx}" accept="image/*" onchange="previewBookImage(event, ${idx})" form="productForm" class="text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 w-full border border-gray-200 p-1.5 rounded-xl bg-white">
                    ${previewHtml}
                </div>
                <div class="flex flex-col gap-2 pt-8">
                    <button type="button" onclick="addBookPictureSection()" class="w-8 h-8 bg-sky-100 hover:bg-sky-200 text-sky-600 rounded-full flex items-center justify-center transition-colors" title="Add another">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                    <button type="button" onclick="removeBookPictureSection(${idx})" class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-500 rounded-full flex items-center justify-center shadow-sm opacity-0 group-hover:opacity-100 transition-all border border-red-200" title="Remove">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', sectionHtml);
    }

    function previewBookImage(event, idx) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(`bookImagePreviewImg_${idx}`).src = e.target.result;
            document.getElementById(`bookImagePreview_${idx}`).classList.remove('hidden');
            const existingInput = document.querySelector(`input[name="book_images[${idx}][existing_image]"]`);
            if(existingInput) existingInput.remove();
        };
        reader.readAsDataURL(file);
    }

    function clearBookImage(idx) {
        document.getElementById(`bookImgFile_${idx}`).value = '';
        document.getElementById(`bookImagePreviewImg_${idx}`).src = '';
        document.getElementById(`bookImagePreview_${idx}`).classList.add('hidden');
        const existingInput = document.querySelector(`input[name="book_images[${idx}][existing_image]"]`);
        if(existingInput) existingInput.remove();
    }

    function removeBookPictureSection(idx) {
        document.getElementById(`book_image_row_${idx}`).remove();
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
                div.className = 'relative w-16 h-20 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0 group cursor-pointer';
                div.setAttribute('data-index', index);
                
                const isFeatured = document.getElementById('featuredImageId').value === 'new_' + index;
                if (isFeatured) div.classList.replace('border-gray-200', 'border-indigo-500');
                
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    ${isFeatured ? '<span class="featured-badge absolute bottom-0 left-0 right-0 bg-indigo-500 text-white text-[9px] font-bold text-center py-0.5">Featured</span>' : ''}
                    <button type="button" onclick="removeGalleryImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10" title="Remove">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                
                div.addEventListener('click', function(evt) {
                    if (evt.target.closest('button')) return;
                    setFeaturedGalleryImage('new_' + index, div);
                });
                
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
        if (!container) return; // section is disabled/commented out
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
                            ${buildSubcategoryOptions(null, data && data.subcategory_id ? data.subcategory_id : null)}
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
            handleCategoryRowChange(idx, data.subcategory_id || null);
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

    function handleCategoryRowChange(idx, preserveValue = null) {
        const parentSelect = document.getElementById(`catImgCategory_${idx}`);
        const selectedOption = parentSelect.options[parentSelect.selectedIndex];
        const hasSub = selectedOption ? selectedOption.getAttribute('data-has-sub') === 'true' : false;
        const parentId = parentSelect.value;
        const subContainer = document.getElementById(`catImgSubContainer_${idx}`);
        const subSelect = document.getElementById(`catImgSubcategory_${idx}`);

        if (!parentId) {
            subContainer.classList.add('hidden');
            subSelect.required = false;
            subSelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
            return;
        }

        if (hasSub) {
            // Rebuild options filtered to this category (L1 + L2 children grouped)
            subSelect.innerHTML = '<option value="">-- Select Subcategory --</option>'
                + buildSubcategoryOptions(parentId, preserveValue);
            subContainer.classList.remove('hidden');
            subSelect.required = true;
        } else {
            subContainer.classList.add('hidden');
            subSelect.required = false;
            subSelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
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

    function loadGoogleFont(fontName) {
        if (!fontName) return;
        // Skip default local fonts
        const skipFonts = ['PetitCochon', 'Arial', 'Courier New', 'Times New Roman', 'Comic Sans MS', 'Impact', 'sans-serif', 'serif', 'monospace', 'fantasy', 'cursive'];
        if (skipFonts.some(f => fontName.includes(f))) {
            return;
        }
        
        const fontId = 'gfont-' + fontName.toLowerCase().replace(/[^a-z0-9]/g, '-');
        if (!document.getElementById(fontId)) {
            const link = document.createElement('link');
            link.id = fontId;
            link.rel = 'stylesheet';
            link.href = `https://fonts.googleapis.com/css2?family=${encodeURIComponent(fontName)}&display=swap`;
            document.head.appendChild(link);
        }
    }

    function updateOverlayPreview() {
        let text = document.getElementById('configNameText').value || 'Your Name';
        let font = document.getElementById('configNameFont').value;
        
        // Load font dynamically if it's a Google Font
        loadGoogleFont(font);

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

    // ═══════════════════════════════════════════════════════════════════
    // CUSTOMIZATION STEPS BUILDER — option names from category selects
    // ═══════════════════════════════════════════════════════════════════

    function buildCatOpts(selectedName) {
        let o = '<option value="">— Select —</option>';
        if (typeof categoriesData === 'undefined') return o;
        categoriesData.forEach(c => {
            const s = c.name === selectedName ? 'selected' : '';
            o += `<option value="${c.name}" ${s}>${c.name}</option>`;
            (c.subcategories || []).forEach(s1 => {
                const ss1 = s1.name === selectedName ? 'selected' : '';
                o += `<option value="${s1.name}" ${ss1}>\u00a0\u00a0\u21b3 ${s1.name}</option>`;
                (s1.children || []).forEach(s2 => {
                    const ss2 = s2.name === selectedName ? 'selected' : '';
                    o += `<option value="${s2.name}" ${ss2}>\u00a0\u00a0\u00a0\u00a0\u21b3 ${s2.name}</option>`;
                });
            });
        });
        return o;
    }

    let customStepIdx = 0;

    function buildTypeSelect(nameAttr, selectedType, colorInputId) {
        const types = ['dropdown', 'box', 'color'];
        let opts = types.map(t => `<option value="${t}" ${selectedType === t ? 'selected' : ''}>${t.charAt(0).toUpperCase() + t.slice(1)}</option>`).join('');
        return `<select name="${nameAttr}" onchange="toggleColorInput(this,'${colorInputId}')"
            class="px-2.5 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-violet-400 cursor-pointer">${opts}</select>`;
    }

    function toggleColorInput(selectEl, colorInputId) {
        const wrap = document.getElementById(colorInputId);
        if (!wrap) return;
        wrap.classList.toggle('hidden', selectEl.value !== 'color');
    }

    function addCustomizationStep(data = null) {
        const container = document.getElementById('customizationStepsContainer');
        const si = customStepIdx++;
        const stepName  = data ? data.name : '';
        const stepType  = data ? (data.type || 'dropdown') : 'dropdown';
        const stepColor = data ? (data.color_value || '') : '';

        const div = document.createElement('div');
        div.id = `custStep_${si}`;
        div.className = 'border border-violet-200 rounded-2xl bg-gradient-to-b from-violet-50/60 to-white p-5 space-y-4 relative shadow-sm';
        div.innerHTML = `
            <button type="button" onclick="this.closest('[id^=custStep_]').remove()"
                class="absolute -top-3 -right-3 w-7 h-7 bg-white hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-full flex items-center justify-center shadow border border-gray-200 hover:border-red-200 transition-all z-10">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="flex items-center gap-3 flex-wrap">
                <span class="inline-flex items-center gap-1 text-xs font-bold text-violet-700 bg-violet-100 px-2.5 py-1 rounded-full whitespace-nowrap">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Step
                </span>
                <input type="text" name="customization_steps[${si}][name]" value="${stepName}"
                    placeholder="Step name — e.g. Gender"
                    class="flex-1 min-w-0 px-4 py-2.5 bg-white border border-violet-200 rounded-xl text-sm font-semibold placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-violet-400 transition-all">
                ${buildTypeSelect('customization_steps['+si+'][type]', stepType, 'stepColorWrap_'+si)}
                <div id="stepColorWrap_${si}" class="${stepType === 'color' ? '' : 'hidden'} flex items-center gap-1.5">
                    <input type="color" name="customization_steps[${si}][color_value]" value="${stepColor || '#000000'}"
                        class="w-8 h-8 rounded border border-gray-200 cursor-pointer p-0.5">
                    <input type="text" id="stepColorText_${si}"
                        value="${stepColor}"
                        placeholder="#000000"
                        oninput="syncCustColorPicker(this,'customization_steps[${si}][color_value]')"
                        class="w-20 px-2 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-mono focus:outline-none focus:ring-2 focus:ring-violet-400">
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex items-center justify-between px-1">
                    <span class="text-xs font-semibold text-gray-500">Options <span class="text-gray-300 font-normal">(e.g. Boy / Girl)</span></span>
                    <button type="button" onclick="addCustomizationOption(${si})"
                        class="inline-flex items-center gap-1 text-xs font-bold text-violet-600 hover:text-violet-800 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Option
                    </button>
                </div>
                <div id="custOptions_${si}" class="space-y-3"></div>
            </div>
        `;
        container.appendChild(div);

        if (data && data.options) {
            data.options.forEach(opt => addCustomizationOption(si, opt));
        }
    }

    let customOptIdx = 0;

    function addCustomizationOption(si, data = null) {
        const container = document.getElementById(`custOptions_${si}`);
        if (!container) return;
        const oi        = customOptIdx++;
        const optName   = data ? (data.name || '') : '';
        const optType   = data ? (data.type || 'dropdown') : 'dropdown';
        const optColor  = data ? (data.color_value || '') : '';
        const isDef     = data ? data.is_default : false;
        const existImg  = data ? (data.image_url  || '') : '';
        const existPath = data ? (data.image_path || '') : '';
        const selOpts   = buildCatOpts(optName);

        const div = document.createElement('div');
        div.id = `custOpt_${si}_${oi}`;
        div.className = 'rounded-xl border border-pink-100 bg-white shadow-sm overflow-hidden';
        div.innerHTML = `
            <div class="flex items-center gap-2 px-3 py-2.5 bg-pink-50/50 border-b border-pink-100 flex-wrap">
                <span class="text-[10px] font-bold text-pink-600 bg-pink-100 px-2 py-0.5 rounded-full whitespace-nowrap">Option</span>
                <select name="customization_steps[${si}][options][${oi}][name]"
                    class="flex-1 min-w-0 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-pink-400 cursor-pointer">${selOpts}</select>
                ${buildTypeSelect('customization_steps['+si+'][options]['+oi+'][type]', optType, 'optColorWrap_'+si+'_'+oi)}
                <div id="optColorWrap_${si}_${oi}" class="${optType === 'color' ? '' : 'hidden'} flex items-center gap-1.5 flex-shrink-0">
                    <input type="color" name="customization_steps[${si}][options][${oi}][color_value]" value="${optColor || '#000000'}"
                        class="w-7 h-7 rounded border border-gray-200 cursor-pointer p-0.5">
                    <input type="text" value="${optColor}" placeholder="#000000"
                        oninput="syncCustColorPicker(this,'customization_steps[${si}][options][${oi}][color_value]')"
                        class="w-20 px-2 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-mono focus:outline-none focus:ring-2 focus:ring-pink-400">
                </div>
                <label class="flex items-center gap-1.5 cursor-pointer flex-shrink-0 ml-1">
                    <input type="checkbox" name="customization_steps[${si}][options][${oi}][is_default]" value="1" ${isDef ? 'checked' : ''}
                        class="w-3.5 h-3.5 text-pink-600 border-gray-300 rounded focus:ring-pink-400">
                    <span class="text-xs font-semibold text-gray-600 whitespace-nowrap">Default</span>
                </label>
                <button type="button" onclick="this.closest('[id^=custOpt_]').remove()"
                    class="ml-1 w-6 h-6 flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-full transition-all flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-3 space-y-3">
                <div class="flex items-start gap-3">
                    <label class="flex-shrink-0 cursor-pointer">
                        <input type="file" name="customization_steps[${si}][options][${oi}][image]" accept="image/*"
                            class="sr-only" onchange="handleCustOptImage(event,'opt_${si}_${oi}')">
                        <div id="custOptBtn_opt_${si}_${oi}" class="w-24 h-24 rounded-xl border-2 border-dashed border-pink-200 bg-pink-50/40 flex flex-col items-center justify-center gap-1 hover:border-pink-400 hover:bg-pink-50 transition-all ${existImg ? 'hidden' : ''}">
                            <svg class="w-7 h-7 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[10px] font-semibold text-pink-400">Image</span>
                        </div>
                        <div id="custOptPreview_opt_${si}_${oi}" class="relative w-24 h-24 rounded-xl overflow-hidden border border-gray-200 shadow-sm ${existImg ? '' : 'hidden'}">
                            <img id="custOptImg_opt_${si}_${oi}" src="${existImg}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 flex items-center justify-center transition-all">
                                <span class="text-white text-[9px] font-bold">Change</span>
                            </div>
                        </div>
                    </label>
                    <input type="hidden" name="customization_steps[${si}][options][${oi}][existing_image]" id="custOptExist_opt_${si}_${oi}" value="${existPath}">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-500 mb-1">Option Image</p>
                        <p class="text-[10px] text-gray-400 leading-relaxed">Upload the overlay image for this option. Click the box to choose a file.</p>
                    </div>
                </div>
                <div class="border-t border-gray-50 pt-2 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-gray-500">Sub-steps <span class="text-gray-300 font-normal">(e.g. Hair Color, Eye Color)</span></span>
                        <button type="button" onclick="addCustomizationSubstep(${si},${oi})"
                            class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add Sub-step
                        </button>
                    </div>
                    <div id="custSubsteps_${si}_${oi}" class="space-y-2"></div>
                </div>
            </div>
        `;
        container.appendChild(div);

        if (data && data.sub_steps) {
            data.sub_steps.forEach(ss => addCustomizationSubstep(si, oi, ss));
        }
    }

    let customSsIdx = 0;

    function addCustomizationSubstep(si, oi, data = null) {
        const container = document.getElementById(`custSubsteps_${si}_${oi}`);
        if (!container) return;
        const ssi     = customSsIdx++;
        const ssName  = data ? (data.name || '') : '';
        const ssType  = data ? (data.type || 'dropdown') : 'dropdown';
        const ssOpts  = buildCatOpts(ssName);

        // Sub-step shows only a type selector (no color picker/input here).
        // When type === 'color', sub-options will render a color picker instead of a name select.
        const types = ['dropdown', 'box', 'color'];
        const ssTypeOpts = types.map(t => `<option value="${t}" ${ssType === t ? 'selected' : ''}>${t.charAt(0).toUpperCase() + t.slice(1)}</option>`).join('');
        const ssTypeSelect = `<select id="ssType_${si}_${oi}_${ssi}" name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][type]"
            onchange="onSubstepTypeChange(${si},${oi},${ssi})"
            class="px-2.5 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-violet-400 cursor-pointer">${ssTypeOpts}</select>`;

        const div = document.createElement('div');
        div.id = `custSS_${si}_${oi}_${ssi}`;
        div.className = 'rounded-xl border border-indigo-100 bg-indigo-50/30 overflow-hidden';
        div.innerHTML = `
            <div class="flex items-center gap-2 px-3 py-2 border-b border-indigo-100/60 bg-indigo-50/60 flex-wrap">
                <svg class="w-3.5 h-3.5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full whitespace-nowrap">Sub-step</span>
                <select name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][name]"
                    class="flex-1 min-w-0 px-3 py-1.5 bg-white border border-indigo-200 rounded-lg text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400 cursor-pointer">${ssOpts}</select>
                ${ssTypeSelect}
                <button type="button" onclick="this.closest('[id^=custSS_]').remove()"
                    class="ml-auto w-6 h-6 flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-full transition-all flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-3 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-gray-500">Sub-options <span class="text-gray-300 font-normal">(e.g. Red, Black)</span></span>
                    <button type="button" onclick="addCustomizationSuboption(${si},${oi},${ssi})"
                        class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600 hover:text-amber-800 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add
                    </button>
                </div>
                <div id="custSubOpts_${si}_${oi}_${ssi}" class="grid grid-cols-2 sm:grid-cols-3 gap-2"></div>
            </div>
        `;
        container.appendChild(div);

        if (data && data.sub_options) {
            data.sub_options.forEach(so => addCustomizationSuboption(si, oi, ssi, so));
        }
    }

    // Called when sub-step type changes — re-render all existing sub-options to match new type
    function onSubstepTypeChange(si, oi, ssi) {
        const container = document.getElementById(`custSubOpts_${si}_${oi}_${ssi}`);
        if (!container) return;
        const existingCards = container.querySelectorAll(':scope > div');
        const existing = [];
        existingCards.forEach(card => {
            const nameSelect = card.querySelector('select[name*="[name]"]');
            const nameHidden = card.querySelector('input[id^="soNameHidden_"]');
            const defEl      = card.querySelector('input[type="checkbox"]');
            const imgEl      = card.querySelector('img');
            const existEl    = card.querySelector('input[name*="[existing_image]"]');
            const colorHid   = card.querySelector('input[id^="soColorHidden_"]');
            existing.push({
                name:        nameSelect ? nameSelect.value : (nameHidden ? nameHidden.value : ''),
                is_default:  defEl   ? defEl.checked : false,
                image_url:   (imgEl && imgEl.src && !imgEl.src.endsWith('/')) ? imgEl.src : '',
                image_path:  existEl ? existEl.value : '',
                color_value: colorHid ? colorHid.value : '',
            });
        });
        container.innerHTML = '';
        existing.forEach(d => addCustomizationSuboption(si, oi, ssi, d));
    }

    let customSoIdx = 0;

    function addCustomizationSuboption(si, oi, ssi, data = null) {
        const container = document.getElementById(`custSubOpts_${si}_${oi}_${ssi}`);
        if (!container) return;
        const soi      = customSoIdx++;
        const soColor  = data ? (data.color_value || '#000000') : '#000000';
        const isDef    = data ? data.is_default : false;
        const existImg  = data ? (data.image_url  || '') : '';
        const existPath = data ? (data.image_path || '') : '';
        const soKey    = `${si}_${oi}_${ssi}_${soi}`;

        // Determine sub-step type to decide how to render this sub-option
        const ssTypeEl = document.getElementById(`ssType_${si}_${oi}_${ssi}`);
        const ssType   = ssTypeEl ? ssTypeEl.value : 'dropdown';
        const isColor  = ssType === 'color';

        // Name: color type → color picker; other types → category select
        const soName   = data ? (data.name || (isColor ? soColor : '')) : '';
        const soOpts   = buildCatOpts(soName);

        const div = document.createElement('div');
        div.className = 'rounded-xl border border-amber-100 bg-white overflow-hidden shadow-sm';

        if (isColor) {
            // Color type: image upload + color picker only, NO select option
            div.innerHTML = `
                <label class="block cursor-pointer">
                    <input type="file"
                        name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][image]"
                        accept="image/*" class="sr-only"
                        onchange="handleCustSOImage(event,'${soKey}')">
                    <div id="custSOBtn_${soKey}" class="h-20 border-b-2 border-dashed border-amber-200 bg-amber-50/40 flex flex-col items-center justify-center gap-1 hover:border-amber-400 hover:bg-amber-50 transition-all ${existImg ? 'hidden' : ''}">
                        <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-[9px] font-semibold text-amber-400">Upload</span>
                    </div>
                    <div id="custSOPrev_${soKey}" class="relative h-20 overflow-hidden ${existImg ? '' : 'hidden'}">
                        <img id="custSOImg_${soKey}" src="${existImg}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/30 opacity-0 hover:opacity-100 flex items-center justify-center transition-all">
                            <span class="text-white text-[9px] font-bold">Change</span>
                        </div>
                    </div>
                </label>
                <input type="hidden" name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][existing_image]" id="custSOExist_${soKey}" value="${existPath}">
                <input type="hidden" name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][type]" value="color">
                <input type="hidden" name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][name]" id="soNameHidden_${soKey}" value="${soColor}">
                <div class="flex items-center gap-2 px-2 py-2">
                    <input type="color"
                        id="soColorPicker_${soKey}"
                        value="${soColor}"
                        oninput="syncSOColor('${soKey}')"
                        class="w-8 h-8 rounded border border-gray-200 cursor-pointer p-0.5 flex-shrink-0">
                    <input type="hidden"
                        name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][color_value]"
                        id="soColorHidden_${soKey}" value="${soColor}">
                </div>
                <div class="flex items-center justify-between px-2 pb-2">
                    <label class="flex items-center gap-1 cursor-pointer">
                        <input type="checkbox"
                            name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][is_default]"
                            value="1" ${isDef ? 'checked' : ''}
                            class="w-3 h-3 text-amber-500 border-gray-300 rounded focus:ring-amber-400">
                        <span class="text-[10px] font-semibold text-gray-500">Default</span>
                    </label>
                    <button type="button" onclick="this.closest('.rounded-xl').remove()"
                        class="w-5 h-5 flex items-center justify-center text-gray-300 hover:text-red-500 rounded transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            `;
        } else {
            // Non-color type: image upload + category select
            div.innerHTML = `
                <label class="block cursor-pointer">
                    <input type="file"
                        name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][image]"
                        accept="image/*" class="sr-only"
                        onchange="handleCustSOImage(event,'${soKey}')">
                    <div id="custSOBtn_${soKey}" class="h-20 border-b-2 border-dashed border-amber-200 bg-amber-50/40 flex flex-col items-center justify-center gap-1 hover:border-amber-400 hover:bg-amber-50 transition-all ${existImg ? 'hidden' : ''}">
                        <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-[9px] font-semibold text-amber-400">Upload</span>
                    </div>
                    <div id="custSOPrev_${soKey}" class="relative h-20 overflow-hidden ${existImg ? '' : 'hidden'}">
                        <img id="custSOImg_${soKey}" src="${existImg}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/30 opacity-0 hover:opacity-100 flex items-center justify-center transition-all">
                            <span class="text-white text-[9px] font-bold">Change</span>
                        </div>
                    </div>
                </label>
                <input type="hidden" name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][existing_image]" id="custSOExist_${soKey}" value="${existPath}">
                <input type="hidden" name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][type]" value="${ssType}">
                <div class="px-2 pt-1">
                    <select name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][name]"
                        class="w-full px-2 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer">${soOpts}</select>
                </div>
                <div class="flex items-center justify-between px-2 py-1.5">
                    <label class="flex items-center gap-1 cursor-pointer">
                        <input type="checkbox"
                            name="customization_steps[${si}][options][${oi}][sub_steps][${ssi}][sub_options][${soi}][is_default]"
                            value="1" ${isDef ? 'checked' : ''}
                            class="w-3 h-3 text-amber-500 border-gray-300 rounded focus:ring-amber-400">
                        <span class="text-[10px] font-semibold text-gray-500">Default</span>
                    </label>
                    <button type="button" onclick="this.closest('.rounded-xl').remove()"
                        class="w-5 h-5 flex items-center justify-center text-gray-300 hover:text-red-500 rounded transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            `;
        }
        container.appendChild(div);
    }

    function syncSOColor(soKey) {
        const picker  = document.getElementById(`soColorPicker_${soKey}`);
        if (!picker) return;
        const hex     = picker.value;
        const colorEl = document.getElementById(`soColorHidden_${soKey}`);
        const nameEl  = document.getElementById(`soNameHidden_${soKey}`);
        if (colorEl) colorEl.value = hex;
        if (nameEl)  nameEl.value  = hex;
    }

    function syncSOColorFromText(soKey) {
        // text input removed — no-op kept for safety
    }

    function syncCustColorPicker(textInput, colorInputName) {
        const val = textInput.value.trim();
        // Find the color input by name within the same parent container
        const form = textInput.closest('form') || document;
        const colorInput = form.querySelector(`input[type="color"][name="${CSS.escape(colorInputName)}"]`);
        if (colorInput && /^#[0-9a-fA-F]{6}$/.test(val)) {
            colorInput.value = val;
        }
    }

    function handleCustOptImage(event, key) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById(`custOptImg_${key}`).src = e.target.result;
            document.getElementById(`custOptPreview_${key}`).classList.remove('hidden');
            document.getElementById(`custOptBtn_${key}`).classList.add('hidden');
            const exist = document.getElementById(`custOptExist_${key}`);
            if (exist) exist.value = '';
        };
        reader.readAsDataURL(file);
    }

    function handleCustSOImage(event, key) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById(`custSOImg_${key}`).src = e.target.result;
            document.getElementById(`custSOPrev_${key}`).classList.remove('hidden');
            document.getElementById(`custSOBtn_${key}`).classList.add('hidden');
            const exist = document.getElementById(`custSOExist_${key}`);
            if (exist) exist.value = '';
        };
        reader.readAsDataURL(file);
    }

    function previewCustImage(event, key) {
        // legacy fallback
        handleCustOptImage(event, key);
    }

    function resetCustomizationSteps() {
        const c = document.getElementById('customizationStepsContainer');
        if (c) c.innerHTML = '';
        customStepIdx = 0;
        customOptIdx  = 0;
        customSsIdx   = 0;
        customSoIdx   = 0;
    }

    async function prefillCustomizationSteps(productId) {
        try {
            const res  = await fetch(`/admin/products/${productId}/customization`);
            const json = await res.json();
            if (json.success && json.data && json.data.length > 0) {
                resetCustomizationSteps();
                json.data.forEach(step => addCustomizationStep(step));
            }
        } catch (e) {
            console.error('Failed to load customization data', e);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        new TableHelper('#productsTable', '#searchInput', '#tablePagination', 10);
    });
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
