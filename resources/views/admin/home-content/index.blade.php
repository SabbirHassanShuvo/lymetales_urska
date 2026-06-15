@extends('layouts.admin', ['title' => 'Home Content'])

@section('content')
<div x-data="{ 
    activeTab: localStorage.getItem('home_content_active_tab') || 'hero',
    socialLinks: {{ json_encode($socialLinks) }}
}" x-init="$watch('activeTab', value => localStorage.setItem('home_content_active_tab', value))" class="w-full">
    <!-- Navigation Tabs -->
    <div class="flex flex-wrap gap-2 border-b border-gray-200 mb-8 pb-px">
        <button @click="activeTab = 'hero'" :class="{ 'border-indigo-600 text-indigo-600 font-semibold bg-indigo-50/50': activeTab === 'hero', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'hero' }" class="py-2.5 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
            Hero Sections
        </button>
        <button @click="activeTab = 'feature'" :class="{ 'border-indigo-600 text-indigo-600 font-semibold bg-indigo-50/50': activeTab === 'feature', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'feature' }" class="py-2.5 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
            Highlight Features
        </button>
        <button @click="activeTab = 'gift'" :class="{ 'border-indigo-600 text-indigo-600 font-semibold bg-indigo-50/50': activeTab === 'gift', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'gift' }" class="py-2.5 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
            Gift Cards
        </button>
        <button @click="activeTab = 'promo'" :class="{ 'border-indigo-600 text-indigo-600 font-semibold bg-indigo-50/50': activeTab === 'promo', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'promo' }" class="py-2.5 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
            Promo Section
        </button>
        <button @click="activeTab = 'gift-giver'" :class="{ 'border-indigo-600 text-indigo-600 font-semibold bg-indigo-50/50': activeTab === 'gift-giver', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'gift-giver' }" class="py-2.5 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
            Legendary Gift-Giver
        </button>
        <button @click="activeTab = 'faq'" :class="{ 'border-indigo-600 text-indigo-600 font-semibold bg-indigo-50/50': activeTab === 'faq', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'faq' }" class="py-2.5 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
            FAQs
        </button>
        <button @click="activeTab = 'newsletter'" :class="{ 'border-indigo-600 text-indigo-600 font-semibold bg-indigo-50/50': activeTab === 'newsletter', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'newsletter' }" class="py-2.5 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
            Newsletter & Subs
        </button>
        <button @click="activeTab = 'footer'" :class="{ 'border-indigo-600 text-indigo-600 font-semibold bg-indigo-50/50': activeTab === 'footer', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'footer' }" class="py-2.5 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
            Footer Columns
        </button>
        <button @click="activeTab = 'footer-brand'" :class="{ 'border-indigo-600 text-indigo-600 font-semibold bg-indigo-50/50': activeTab === 'footer-brand', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'footer-brand' }" class="py-2.5 px-4 border-b-2 font-medium text-sm transition-all rounded-t-lg">
            Footer Brand &amp; Socials
        </button>
    </div>

    <!-- 1. Hero Sections Tab -->
    <div x-show="activeTab === 'hero'">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Add New Hero Section</h2>
            <form action="{{ route('admin.home-content.hero.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="e.g. Summer Stories">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button One Text</label>
                        <input type="text" name="button_one_text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" placeholder="e.g. PERSONALISE NOW">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button Two Text</label>
                        <input type="text" name="button_two_text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" placeholder="e.g. MORE BOOKS">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                        <input type="file" name="image" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" onchange="previewImage(event, 'hero-preview')">
                        <img id="hero-preview" src="#" alt="Image Preview" style="display: none; height: 120px; margin-top: 10px; border-radius: 8px; object-fit: cover;">
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Save Hero Section</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Existing Hero Sections</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($heroSections as $hero)
                    <div class="border rounded-xl p-4 relative bg-gray-50 flex flex-col justify-between">
                        <form action="{{ route('admin.home-content.hero.destroy', $hero) }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white p-1.5 rounded-lg hover:bg-red-600 shadow" onclick="confirmDelete(event, this.closest('form'), 'This hero section will be permanently deleted.')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        <div>
                            @if($hero->image_path)
                                <img src="{{ filter_var($hero->image_path, FILTER_VALIDATE_URL) ? $hero->image_path : asset($hero->image_path) }}" alt="Hero Image" class="w-full h-36 object-cover rounded-lg mb-4">
                            @else
                                <div class="w-full h-36 bg-gray-200 rounded-lg mb-4 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                            <h3 class="font-bold text-gray-800">{{ $hero->title }}</h3>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded">Btn 1: {{ $hero->button_one_text ?: 'N/A' }}</span>
                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">Btn 2: {{ $hero->button_two_text ?: 'N/A' }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm md:col-span-3 text-center py-6">No hero sections found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 2. Highlight Features Tab -->
    <div x-show="activeTab === 'feature'" style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Add New Feature</h2>
            <form action="{{ route('admin.home-content.feature.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="e.g. More than just a story">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="Describe this feature..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Save Feature</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Existing Features (Image 2)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($features as $feat)
                    <div class="border rounded-xl p-4 relative bg-gray-50 flex flex-col justify-between">
                        <form action="{{ route('admin.home-content.feature.destroy', $feat) }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1" onclick="confirmDelete(event, this.closest('form'), 'This highlight feature will be permanently deleted.')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">{{ $feat->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $feat->description }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm md:col-span-2 text-center py-6">No highlight features found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 3. Gift Cards Tab -->
    <div x-show="activeTab === 'gift'" style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Add New Gift Card</h2>
            <form action="{{ route('admin.home-content.gift.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="e.g. Gift Voucher">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                        <input type="text" name="subtitle" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" placeholder="e.g. A magical present">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                        <input type="file" name="image" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" onchange="previewImage(event, 'gift-preview')">
                        <img id="gift-preview" src="#" alt="Image Preview" style="display: none; height: 100px; margin-top: 10px; border-radius: 8px; object-fit: cover;">
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Save Gift Card</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Existing Gift Cards (Requirement 3: Links Removed)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($giftCards as $gift)
                    <div class="border rounded-xl p-4 relative bg-gray-50 flex flex-col justify-between">
                        <form action="{{ route('admin.home-content.gift.destroy', $gift) }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white p-1 rounded-lg hover:bg-red-600" onclick="confirmDelete(event, this.closest('form'), 'This gift card will be permanently deleted.')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>
                        <div>
                            @if($gift->image_path)
                                <img src="{{ filter_var($gift->image_path, FILTER_VALIDATE_URL) ? $gift->image_path : asset($gift->image_path) }}" alt="Gift Image" class="w-full h-32 object-cover rounded-lg mb-4">
                            @else
                                <div class="w-full h-32 bg-gray-200 rounded-lg mb-4 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                            <h3 class="font-bold text-gray-800">{{ $gift->title }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $gift->subtitle }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm md:col-span-4 text-center py-6">No gift cards found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 4. Promo Section Tab -->
    <div x-show="activeTab === 'promo'" style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Customize Middle Promo Section (Image 3)</h2>
            <form action="{{ route('admin.home-content.promo.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" value="{{ $promo->title }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="e.g. Thoughtfully made for the people you love most">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="Section descriptions...">{{ $promo->description }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                        <input type="text" name="button_text" value="{{ $promo->button_text }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="e.g. Shop the Story">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                        <input type="file" name="image" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" onchange="previewImage(event, 'promo-preview')">
                        @if($promo->image_path)
                            <img id="promo-preview" src="{{ filter_var($promo->image_path, FILTER_VALIDATE_URL) ? $promo->image_path : asset($promo->image_path) }}" alt="Image Preview" class="h-28 mt-2.5 rounded-lg object-cover">
                        @else
                            <img id="promo-preview" src="#" alt="Image Preview" style="display: none; height: 110px; margin-top: 10px; border-radius: 8px; object-fit: cover;">
                        @endif
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Update Promo Section</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. Legendary Gift-Giver Tab -->
    <div x-show="activeTab === 'gift-giver'" style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-6 text-gray-800">Customize Legendary Gift-Giver Steps (Image 4)</h2>
            <form action="{{ route('admin.home-content.gift-giver.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Main Header Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-gray-100 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Section Subtitle</label>
                        <input type="text" name="subtitle" value="{{ $giftGiver->subtitle ?: 'BECOME A' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Section Main Title</label>
                        <input type="text" name="title" value="{{ $giftGiver->title ?: 'Legendary gift-giver' }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required>
                    </div>
                </div>

                <!-- Steps -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex items-center space-x-3 mb-4">
                            <span class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">1</span>
                            <h3 class="font-semibold text-gray-800">Step 1</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Text Description</label>
                                <input type="text" name="step_1_text" value="{{ $giftGiver->step_1_text }}" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Step 1 Image</label>
                                <input type="file" name="step_1_image" class="w-full text-xs" onchange="previewImage(event, 'step1-preview')">
                                @if($giftGiver->step_1_image)
                                    <img id="step1-preview" src="{{ filter_var($giftGiver->step_1_image, FILTER_VALIDATE_URL) ? $giftGiver->step_1_image : asset($giftGiver->step_1_image) }}" alt="Step 1 Preview" class="w-20 h-20 rounded-full mx-auto object-cover mt-2 border border-indigo-200">
                                @else
                                    <img id="step1-preview" src="#" alt="Step 1 Preview" style="display: none; width: 80px; height: 80px; border-radius: 9999px; margin-top: 10px; object-fit: cover; margin-left: auto; margin-right: auto;">
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex items-center space-x-3 mb-4">
                            <span class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">2</span>
                            <h3 class="font-semibold text-gray-800">Step 2</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Text Description</label>
                                <input type="text" name="step_2_text" value="{{ $giftGiver->step_2_text }}" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Step 2 Image</label>
                                <input type="file" name="step_2_image" class="w-full text-xs" onchange="previewImage(event, 'step2-preview')">
                                @if($giftGiver->step_2_image)
                                    <img id="step2-preview" src="{{ filter_var($giftGiver->step_2_image, FILTER_VALIDATE_URL) ? $giftGiver->step_2_image : asset($giftGiver->step_2_image) }}" alt="Step 2 Preview" class="w-20 h-20 rounded-full mx-auto object-cover mt-2 border border-indigo-200">
                                @else
                                    <img id="step2-preview" src="#" alt="Step 2 Preview" style="display: none; width: 80px; height: 80px; border-radius: 9999px; margin-top: 10px; object-fit: cover; margin-left: auto; margin-right: auto;">
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <div class="flex items-center space-x-3 mb-4">
                            <span class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">3</span>
                            <h3 class="font-semibold text-gray-800">Step 3</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Text Description</label>
                                <input type="text" name="step_3_text" value="{{ $giftGiver->step_3_text }}" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Step 3 Image</label>
                                <input type="file" name="step_3_image" class="w-full text-xs" onchange="previewImage(event, 'step3-preview')">
                                @if($giftGiver->step_3_image)
                                    <img id="step3-preview" src="{{ filter_var($giftGiver->step_3_image, FILTER_VALIDATE_URL) ? $giftGiver->step_3_image : asset($giftGiver->step_3_image) }}" alt="Step 3 Preview" class="w-20 h-20 rounded-full mx-auto object-cover mt-2 border border-indigo-200">
                                @else
                                    <img id="step3-preview" src="#" alt="Step 3 Preview" style="display: none; width: 80px; height: 80px; border-radius: 9999px; margin-top: 10px; object-fit: cover; margin-left: auto; margin-right: auto;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Update Gift-Giver Steps</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 6. FAQs Tab -->
    <div x-show="activeTab === 'faq'" style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Add New FAQ</h2>
            <form action="{{ route('admin.home-content.faq.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Question</label>
                        <input type="text" name="question" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="e.g. What details do I need?">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Answer</label>
                        <textarea name="answer" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="Type the answer..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Save FAQ</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Existing FAQs</h2>
            <div class="space-y-4">
                @forelse($faqs as $faq)
                    <div class="border rounded-xl p-4 relative bg-gray-50">
                        <form action="{{ route('admin.home-content.faq.destroy', $faq) }}" method="POST" class="absolute top-4 right-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="confirmDelete(event, this.closest('form'), 'This FAQ will be permanently deleted.')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        <h3 class="font-bold text-gray-800 pr-8">{{ $faq->question }}</h3>
                        <p class="text-sm text-gray-600 mt-2">{{ $faq->answer }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-6">No FAQs found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 7. Newsletter & Subscribers Tab -->
    <div x-show="activeTab === 'newsletter'" style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Customize Newsletter Text (Requirement 7)</h2>
            <form action="{{ route('admin.home-content.newsletter.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Newsletter Title</label>
                        <input type="text" name="newsletter_title" value="{{ $newsletterTitle }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Newsletter Description</label>
                        <input type="text" name="newsletter_description" value="{{ $newsletterDescription }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Update Newsletter Texts</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Subscribed Emails List</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscribed At</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($subscribers as $sub)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-950">{{ $sub->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sub->created_at->format('M d, Y h:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.home-content.subscriber.destroy', $sub) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="confirmDelete(event, this.closest('form'), 'Remove this email address from the subscribers list.')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">No subscribers found yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 8. Footer Columns Tab -->
    <div x-show="activeTab === 'footer'" style="display: none;">
        <!-- Columns builder -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 lg:col-span-1">
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Add Footer Column</h2>
                <form action="{{ route('admin.home-content.footer-section.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Column Title</label>
                        <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="e.g. Shop">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">Create Section Column</button>
                </form>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 lg:col-span-2">
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Add Link to Column</h2>
                <form action="{{ route('admin.home-content.footer-item.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Column</label>
                            <select name="footer_section_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required>
                                <option value="">Select a column...</option>
                                @foreach($footerSections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Link Label</label>
                            <input type="text" name="label" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="e.g. Our Books">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">URL Link Path</label>
                            <input type="text" name="url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required placeholder="e.g. /books">
                        </div>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Add Link</button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-6 text-gray-800">Configured Footer Columns & Links</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($footerSections as $sec)
                    <div class="border rounded-xl p-5 bg-gray-50 relative flex flex-col justify-between">
                        <form action="{{ route('admin.home-content.footer-section.destroy', $sec) }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white p-1 rounded-lg hover:bg-red-600" onclick="confirmDelete(event, this.closest('form'), 'This will permanently delete this entire footer column and all its links.')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 border-b pb-2 mb-4 pr-6">{{ $sec->title }}</h3>
                            <ul class="space-y-2.5">
                                @forelse($sec->items as $item)
                                    <li class="flex items-center justify-between text-sm bg-white p-2 rounded-lg border border-gray-100">
                                        <div>
                                            <span class="font-medium text-gray-700">{{ $item->label }}</span>
                                            <span class="text-xs text-gray-400 block">{{ $item->url }}</span>
                                        </div>
                                        <form action="{{ route('admin.home-content.footer-item.destroy', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-0.5" onclick="confirmDelete(event, this.closest('form'), 'Remove this link from the footer column.')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    </li>
                                @empty
                                    <li class="text-xs text-gray-400 italic">No links in this column yet</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm md:col-span-3 text-center py-6">No footer columns configured yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Footer Brand Info & Social Links Tab --}}
    <div x-show="activeTab === 'footer-brand'">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-2 text-gray-800">Footer Brand, Logo &amp; Social Media</h2>
            <p class="text-sm text-gray-500 mb-6">Controls the logo, tagline, copyright text, and social media links shown in the footer.</p>

            <form action="{{ route('admin.home-content.footer-brand-socials.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-6">

                    {{-- Logo Upload --}}
                    <div class="border border-dashed border-gray-300 rounded-xl p-5 bg-gray-50">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Footer Logo</label>
                        <div class="flex items-start gap-6">
                            {{-- Current Logo Preview --}}
                            <div class="flex-shrink-0">
                                @if($footerLogoPath)
                                    <img id="logo-preview"
                                         src="{{ filter_var($footerLogoPath, FILTER_VALIDATE_URL) ? $footerLogoPath : asset($footerLogoPath) }}"
                                         alt="Current Footer Logo"
                                         class="h-16 max-w-[180px] object-contain rounded-lg border border-gray-200 bg-white p-2">
                                @else
                                    <img id="logo-preview" src="#" alt="Logo Preview"
                                         class="h-16 max-w-[180px] object-contain rounded-lg border border-gray-200 bg-white p-2"
                                         style="display:none;">
                                    <div id="logo-placeholder" class="h-16 w-40 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs">No logo yet</div>
                                @endif
                            </div>
                            {{-- Upload Input --}}
                            <div class="flex-1">
                                <input type="file" name="footer_logo" accept="image/*"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 text-sm"
                                       onchange="previewFooterLogo(event)">
                                <p class="text-xs text-gray-400 mt-2">Recommended: PNG with transparent background. Max 2MB.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Brand Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand Description / Tagline</label>
                        <textarea name="footer_brand_description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 resize-none" placeholder="e.g. Crafting personalized stories...">{{ $footerBrandDescription }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Shown below the logo in the footer left column.</p>
                    </div>

                    {{-- Copyright Text --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Copyright Text</label>
                        <input type="text" name="footer_copyright" value="{{ $footerCopyright }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600"
                               placeholder="e.g. © 2026 Lymetales HQ, Inc. All Rights Reserved.">
                        <p class="text-xs text-gray-400 mt-1">Displayed in the footer bottom bar.</p>
                    </div>

                    {{-- Social Links --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="block text-sm font-semibold text-gray-700">Social Media Links</label>
                            <button type="button" @click="socialLinks.push({ label: '', url: '' })" class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-lg border border-indigo-100 hover:bg-indigo-100 transition text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Link
                            </button>
                        </div>
                        
                        <input type="hidden" name="social_media_links" :value="JSON.stringify(socialLinks)">
                        
                        <div class="space-y-3">
                            <template x-for="(link, index) in socialLinks" :key="index">
                                <div class="flex items-start gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Platform / Label</label>
                                            <input type="text" x-model="link.label" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600" placeholder="e.g. Instagram, TikTok, Facebook" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">URL or Username</label>
                                            <input type="text" x-model="link.url" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600" placeholder="e.g. https://instagram.com/user or just username" required>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 pt-5">
                                        <button type="button" @click="socialLinks.splice(index, 1)" class="bg-red-50 text-red-600 p-2 rounded-lg border border-red-100 hover:bg-red-100 hover:text-red-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="socialLinks.length === 0">
                                <p class="text-gray-400 text-xs italic py-2 text-center bg-gray-50 rounded-xl border border-gray-200 border-dashed">No social links added yet.</p>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Save All Footer Settings</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function previewImage(event, previewId) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById(previewId);
            output.src = reader.result;
            output.style.display = 'block';
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    function previewFooterLogo(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logo-preview');
            const placeholder = document.getElementById('logo-placeholder');
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    function confirmDelete(event, form, messageText = 'Are you sure you want to delete this?') {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: messageText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5', // Indigo color matching theme
            cancelButtonColor: '#ef4444',
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
                form.submit();
            }
        });
    }
</script>
@endsection
