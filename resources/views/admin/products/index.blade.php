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
                <p class="text-2xl font-bold text-gray-800">{{ $products->count() }}</p>
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
                <p class="text-2xl font-bold text-gray-800">{{ $products->where('is_bestseller', true)->count() }}</p>
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
                <p class="text-2xl font-bold text-gray-800">{{ $products->where('is_recommended', true)->count() }}</p>
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
                <p class="text-2xl font-bold text-gray-800">{{ $products->where('status', true)->count() }}</p>
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
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase border-b border-gray-100">
                        <th class="px-6 py-4">Book Details</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Specifications</th>
                        <th class="px-6 py-4">Badges</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700" id="productTableBody">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition-colors product-row" data-title="{{ strtolower($product->title) }}" data-category="{{ strtolower($product->category ? $product->category->name : '') }}">
                            <!-- Title & Main Image -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-14 h-16 bg-gray-100 rounded-lg overflow-hidden border border-gray-100 flex-shrink-0">
                                        <img src="{{ $product->image ?: 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=120' }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-sm hover:text-indigo-600 transition-colors">
                                            {{ $product->title }}
                                        </h4>
                                        <div class="flex items-center space-x-1.5 mt-1 text-xs text-amber-500 font-semibold">
                                            <span>★ {{ number_format($product->rating ?: 5.0, 1) }}</span>
                                            <span class="text-gray-400 font-normal">({{ $product->reviews_count ?: 0 }} reviews)</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <!-- Category -->
                            <td class="px-6 py-4">
                                @if($product->category)
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-100/50">
                                        {{ $product->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic text-xs">Uncategorized</span>
                                @endif
                            </td>
                            <!-- Price -->
                            <td class="px-6 py-4 font-bold text-gray-900 text-sm">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <!-- Specifications -->
                            <td class="px-6 py-4 text-xs space-y-1 text-gray-500">
                                <div><span class="font-semibold text-gray-700">Ages:</span> {{ $product->age_range ?: 'N/A' }}</div>
                                <div><span class="font-semibold text-gray-700">Pages:</span> {{ $product->pages ?: 'N/A' }} pg</div>
                                <div><span class="font-semibold text-gray-700">Cover:</span> {{ $product->cover_type ?: 'N/A' }}</div>
                            </td>
                            <!-- Badges -->
                            <td class="px-6 py-4 space-y-1">
                                @if($product->is_bestseller)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-extrabold bg-amber-50 text-amber-800 uppercase tracking-wider border border-amber-200">
                                        Best Seller
                                    </span>
                                @endif
                                @if($product->is_recommended)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-2xs font-extrabold bg-rose-50 text-rose-800 uppercase tracking-wider border border-rose-200 block w-max">
                                        Recommended
                                    </span>
                                @endif
                                @if(!$product->is_bestseller && !$product->is_recommended)
                                    <span class="text-gray-400 italic text-xs">None</span>
                                @endif
                            </td>
                            <!-- Status -->
                            <td class="px-6 py-4">
                                <button onclick="toggleProductStatus({{ $product->id }}, {{ $product->status ? 'true' : 'false' }})" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold transition-colors {{ $product->status ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                    {{ $product->status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <!-- Actions -->
                            <td class="px-6 py-4 text-right space-x-2">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <button onclick="previewProduct({{ json_encode($product) }})" class="px-2.5 py-1.5 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 border border-gray-150 rounded-lg text-xs font-bold transition-all" title="Customer Preview">
                                        Preview
                                    </button>
                                    <button onclick="editProduct({{ json_encode($product) }})" class="px-2.5 py-1.5 bg-gray-50 hover:bg-indigo-55 hover:text-indigo-600 border border-gray-150 rounded-lg text-xs font-bold transition-all" title="Edit Book">
                                        Edit
                                    </button>
                                    <button onclick="confirmDelete('{{ route('admin.products.destroy', $product->id) }}', 'This book and its files will be permanently deleted.')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete Book">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-gray-400">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3 text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-700">No Books Found</h4>
                                <p class="text-xs text-gray-500 mt-1">Get started by creating your very first personalised book product.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Fields -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Book Title *</label>
                                <input type="text" name="title" id="prodTitle" required placeholder="e.g. My First Easter Egg Hunt" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                                <select id="prodParentCategory" onchange="handleCategoryChange()" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                        @if($cat->subcategories->count() == 0)
                                            <option value="{{ $cat->id }}" data-has-sub="false">{{ $cat->name }}</option>
                                        @else
                                            <option value="{{ $cat->id }}" data-has-sub="true">{{ $cat->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <input type="hidden" name="category_id" id="prodCategoryId">
                            </div>
                            <!-- Subcategory (shown only when parent has sub) -->
                            <div id="subCategoryContainer" class="hidden">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Subcategory *</label>
                                <select id="prodSubCategory" onchange="handleSubCategoryChange()" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-semibold">
                                    <option value="">-- Select Subcategory --</option>
                                    @foreach($subcategories as $sub)
                                        <option value="{{ $sub->id }}" data-parent="{{ $sub->parent_id }}">{{ $sub->name }}</option>
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
                            </div>

                            <!-- Gallery Upload -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Gallery Thumbnails</label>
                                <input type="file" name="gallery_files[]" id="prodGalleryFiles" multiple accept="image/*" onchange="previewGalleryImages(event)" class="text-xs text-gray-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 w-full border border-gray-200 p-1.5 rounded-xl bg-gray-50">
                                <div id="galleryPreviewContainer" class="flex flex-wrap gap-2 mt-3"></div>
                                <div id="existingGalleryContainer" class="flex flex-wrap gap-2 mt-2 hidden"></div>
                                <input type="hidden" name="deleted_gallery_images" id="deletedGalleryImages" value="[]">
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

                    <!-- Status Flags -->
                    <div class="flex items-center space-x-6 pt-2 border-t border-gray-50">
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
                        <!-- Mini Gallery -->
                        <div id="prevGallery" class="flex space-x-3 overflow-x-auto pb-2">
                            <!-- JS injected thumbnails -->
                        </div>
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

                <!-- Mock Customer Reviews Section -->
                <div class="mt-12 pt-8 border-t border-gray-150/80">
                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight mb-6">Customer Reviews</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex text-amber-400 text-xs">★★★★★</div>
                                <span class="text-2xs text-gray-400 font-semibold">Verified Buyer</span>
                            </div>
                            <h5 class="font-bold text-gray-800 text-sm">"Absolutely magical!"</h5>
                            <p class="text-xs text-gray-500 leading-relaxed">"The illustration is stunning and the personalisation matches perfectly. High-quality cover."</p>
                            <p class="text-[10px] text-gray-400 font-medium">Reviewed by Sarah M.</p>
                        </div>
                        <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex text-amber-400 text-xs">★★★★★</div>
                                <span class="text-2xs text-gray-400 font-semibold">Verified Buyer</span>
                            </div>
                            <h5 class="font-bold text-gray-800 text-sm">"Best gift ever"</h5>
                            <p class="text-xs text-gray-500 leading-relaxed">"My son loves seeing his name in the story. We read this book every single night now."</p>
                            <p class="text-[10px] text-gray-400 font-medium">Reviewed by Thomas R.</p>
                        </div>
                    </div>
                </div>
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
        document.getElementById('prodCategoryId').value = "";
        document.getElementById('prodParentCategory').value = "";
        document.getElementById('prodSubCategory').value = "";
        document.getElementById('subCategoryContainer').classList.add('hidden');
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
        document.getElementById('prodImageUrl').value = "";
        document.getElementById('prodGalleryUrls').value = "";
        document.getElementById('prodImageFile').value = "";
        document.getElementById('prodGalleryFiles').value = "";

        // Clear image previews
        document.getElementById('mainImagePreview').classList.add('hidden');
        document.getElementById('mainImagePreviewImg').src = "";
        document.getElementById('galleryPreviewContainer').innerHTML = "";
        selectedGalleryFiles = [];
        document.getElementById('existingGalleryContainer').innerHTML = "";
        document.getElementById('existingGalleryContainer').classList.add('hidden');
        document.getElementById('deletedGalleryImages').value = "[]";
        
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
        
        // Category Logic update for Edit
        const parentSelect = document.getElementById('prodParentCategory');
        const subSelect = document.getElementById('prodSubCategory');
        const catIdInput = document.getElementById('prodCategoryId');
        const subContainer = document.getElementById('subCategoryContainer');
        
        // Reset category state
        parentSelect.value = "";
        subSelect.value = "";
        catIdInput.value = product.category_id || "";
        subContainer.classList.add('hidden');

        if (product.category_id) {
            // Find if it's a parent or subcategory
            let isSub = false;
            let parentId = null;
            Array.from(subSelect.options).forEach(opt => {
                if (opt.value == product.category_id) {
                    isSub = true;
                    parentId = opt.getAttribute('data-parent');
                }
            });

            if (isSub) {
                parentSelect.value = parentId;
                handleCategoryChange(); // Trigger the filter
                subSelect.value = product.category_id;
            } else {
                parentSelect.value = product.category_id;
                handleCategoryChange();
            }
        }

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
        
        // Handle images (if it's a URL, populate URL input, otherwise empty it)
        if (product.image && (product.image.startsWith('http://') || product.image.startsWith('https://'))) {
            document.getElementById('prodImageUrl').value = product.image;
        } else {
            document.getElementById('prodImageUrl').value = "";
        }

        // Show replace gallery checkbox option since we're editing
        document.getElementById('replaceGalleryGroup').classList.remove('hidden');
        document.getElementById('replaceGalleryGroup').classList.add('flex');

        // Populate gallery url inputs if any
        if (product.gallery && Array.isArray(product.gallery)) {
            const urls = product.gallery.filter(g => g.startsWith('http://') || g.startsWith('https://'));
            document.getElementById('prodGalleryUrls').value = urls.join(', ');
            
            // Render existing non-url gallery items to delete
            const existingContainer = document.getElementById('existingGalleryContainer');
            existingContainer.innerHTML = '';
            existingContainer.classList.remove('hidden');
            
            product.gallery.forEach(imgUrl => {
                if (!imgUrl.startsWith('http://') && !imgUrl.startsWith('https://')) {
                    const div = document.createElement('div');
                    div.className = 'relative w-16 h-20 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 flex-shrink-0 group';
                    div.innerHTML = `
                        <img src="${imgUrl}" class="w-full h-full object-cover">
                        <button type="button" onclick="markExistingGalleryDeleted('${imgUrl}', this)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity" title="Remove">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    `;
                    existingContainer.appendChild(div);
                }
            });
        } else {
            document.getElementById('prodGalleryUrls').value = "";
            document.getElementById('existingGalleryContainer').innerHTML = '';
            document.getElementById('existingGalleryContainer').classList.add('hidden');
        }

        document.getElementById('deletedGalleryImages').value = "[]";

        document.getElementById('prodIsBestseller').checked = product.is_bestseller === true || product.is_bestseller === 1;
        document.getElementById('prodIsRecommended').checked = product.is_recommended === true || product.is_recommended === 1;
        document.getElementById('prodStatus').checked = product.status === true || product.status === 1;

        toggleModal('productModal');
    }

    function previewProduct(product) {
        const fallBackImg = "https://images.unsplash.com/photo-1543002588-bfa74002ed7e?auto=format&fit=crop&q=80&w=400";
        
        // Ingest texts
        document.getElementById('prevTitle').textContent = product.title;
        document.getElementById('prevImg').src = product.image || fallBackImg;
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

        // Build Gallery Thumbnails
        const prevGallery = document.getElementById('prevGallery');
        prevGallery.innerHTML = "";
        
        // Add main cover as first thumbnail
        const mainThumb = document.createElement('div');
        mainThumb.className = "w-16 h-20 bg-gray-50 rounded-lg overflow-hidden border border-indigo-400 cursor-pointer flex-shrink-0";
        mainThumb.innerHTML = `<img src="${product.image || fallBackImg}" class="w-full h-full object-cover">`;
        mainThumb.onclick = () => {
            document.querySelectorAll('#prevGallery > div').forEach(d => d.classList.remove('border-indigo-400'));
            mainThumb.classList.add('border-indigo-400');
            document.getElementById('prevImg').src = product.image || fallBackImg;
        };
        prevGallery.appendChild(mainThumb);

        // Add actual gallery images
        if (product.gallery && Array.isArray(product.gallery)) {
            product.gallery.forEach(imgUrl => {
                if (imgUrl) {
                    const thumb = document.createElement('div');
                    thumb.className = "w-16 h-20 bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:border-indigo-300 cursor-pointer flex-shrink-0 transition-all";
                    thumb.innerHTML = `<img src="${imgUrl}" class="w-full h-full object-cover">`;
                    thumb.onclick = () => {
                        document.querySelectorAll('#prevGallery > div').forEach(d => d.classList.remove('border-indigo-400'));
                        thumb.classList.add('border-indigo-400');
                        document.getElementById('prevImg').src = imgUrl;
                    };
                    prevGallery.appendChild(thumb);
                }
            });
        }

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

    // --- Category Logic ---
    function handleCategoryChange() {
        const parentSelect = document.getElementById('prodParentCategory');
        const selectedOption = parentSelect.options[parentSelect.selectedIndex];
        const hasSub = selectedOption ? selectedOption.getAttribute('data-has-sub') === 'true' : false;
        const parentId = parentSelect.value;
        const subContainer = document.getElementById('subCategoryContainer');
        const subSelect = document.getElementById('prodSubCategory');
        const catIdInput = document.getElementById('prodCategoryId');

        if (!parentId) {
            subContainer.classList.add('hidden');
            subSelect.required = false;
            catIdInput.value = '';
            return;
        }

        if (hasSub) {
            subContainer.classList.remove('hidden');
            subSelect.required = true;
            // Show only subcategories of selected parent
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
            catIdInput.value = ''; // Wait for subcategory selection
        } else {
            subContainer.classList.add('hidden');
            subSelect.required = false;
            subSelect.value = '';
            catIdInput.value = parentId; // Use parent as category_id
        }
    }

    function handleSubCategoryChange() {
        const subSelect = document.getElementById('prodSubCategory');
        const catIdInput = document.getElementById('prodCategoryId');
        if (subSelect.value) {
            catIdInput.value = subSelect.value;
        } else {
            catIdInput.value = "";
        }
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

    // --- Multi-Image Gallery Preview & Delete ---
    let selectedGalleryFiles = [];

    function previewGalleryImages(event) {
        const files = Array.from(event.target.files);
        files.forEach(file => selectedGalleryFiles.push(file));
        renderGalleryPreview();
        // Reset the input so user can add more files later
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
        // Rebuild the FileList for the input using DataTransfer
        const dt = new DataTransfer();
        selectedGalleryFiles.forEach(f => dt.items.add(f));
        document.getElementById('prodGalleryFiles').files = dt.files;
    }

    function markExistingGalleryDeleted(url, element) {
        let deleted = JSON.parse(document.getElementById('deletedGalleryImages').value || "[]");
        deleted.push(url);
        document.getElementById('deletedGalleryImages').value = JSON.stringify(deleted);
        element.closest('div').remove();
    }

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
@endsection
