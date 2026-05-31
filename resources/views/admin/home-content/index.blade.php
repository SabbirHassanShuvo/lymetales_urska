@extends('layouts.admin', ['title' => 'Home Content'])

@section('content')
<div x-data="{ activeTab: 'hero' }" class="w-full">
    <!-- Tabs -->
    <div class="flex space-x-4 border-b border-gray-200 mb-6">
        <button @click="activeTab = 'hero'" :class="{ 'border-indigo-600 text-indigo-600': activeTab === 'hero', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'hero' }" class="py-2 px-4 border-b-2 font-medium text-sm transition-colors">
            Hero Sections
        </button>
        <button @click="activeTab = 'gift'" :class="{ 'border-indigo-600 text-indigo-600': activeTab === 'gift', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'gift' }" class="py-2 px-4 border-b-2 font-medium text-sm transition-colors">
            Gift Cards
        </button>
        <button @click="activeTab = 'faq'" :class="{ 'border-indigo-600 text-indigo-600': activeTab === 'faq', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'faq' }" class="py-2 px-4 border-b-2 font-medium text-sm transition-colors">
            FAQs
        </button>
    </div>

    <!-- Hero Sections Tab -->
    <div x-show="activeTab === 'hero'">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4">Add New Hero Section</h2>
            <form action="{{ route('admin.home-content.hero.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                        <input type="text" name="subtitle" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button One Text</label>
                        <input type="text" name="button_one_text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button One Link</label>
                        <input type="text" name="button_one_link" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button Two Text</label>
                        <input type="text" name="button_two_text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Button Two Link</label>
                        <input type="text" name="button_two_link" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                        <input type="file" name="image" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" onchange="previewImage(event, 'hero-preview')">
                        <img id="hero-preview" src="#" alt="Image Preview" style="display: none; height: 100px; margin-top: 10px; border-radius: 8px; object-fit: cover;">
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Save Hero Section</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-4">Existing Hero Sections</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($heroSections as $hero)
                    <div class="border rounded-lg p-4 relative">
                        <form action="{{ route('admin.home-content.hero.destroy', $hero) }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white p-1 rounded hover:bg-red-600" onclick="return confirm('Are you sure?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>
                        @if($hero->image_path)
                            <img src="{{ asset($hero->image_path) }}" alt="Hero Image" class="w-full h-32 object-cover rounded mb-4">
                        @else
                            <div class="w-full h-32 bg-gray-200 rounded mb-4 flex items-center justify-center text-gray-500">No Image</div>
                        @endif
                        <h3 class="font-bold">{{ $hero->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $hero->subtitle }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Gift Cards Tab -->
    <div x-show="activeTab === 'gift'" style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4">Add New Gift Card</h2>
            <form action="{{ route('admin.home-content.gift.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subtitle</label>
                        <input type="text" name="subtitle" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link</label>
                        <input type="text" name="link" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600">
                    </div>
                    <div>
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
            <h2 class="text-lg font-semibold mb-4">Existing Gift Cards</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($giftCards as $gift)
                    <div class="border rounded-lg p-4 relative">
                        <form action="{{ route('admin.home-content.gift.destroy', $gift) }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white p-1 rounded hover:bg-red-600" onclick="return confirm('Are you sure?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>
                        @if($gift->image_path)
                            <img src="{{ asset($gift->image_path) }}" alt="Gift Image" class="w-full h-32 object-cover rounded mb-4">
                        @else
                            <div class="w-full h-32 bg-gray-200 rounded mb-4 flex items-center justify-center text-gray-500">No Image</div>
                        @endif
                        <h3 class="font-bold">{{ $gift->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $gift->subtitle }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- FAQs Tab -->
    <div x-show="activeTab === 'faq'" style="display: none;">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4">Add New FAQ</h2>
            <form action="{{ route('admin.home-content.faq.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Question</label>
                        <input type="text" name="question" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Answer</label>
                        <textarea name="answer" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600" required></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Save FAQ</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-4">Existing FAQs</h2>
            <div class="space-y-4">
                @foreach($faqs as $faq)
                    <div class="border rounded-lg p-4 relative">
                        <form action="{{ route('admin.home-content.faq.destroy', $faq) }}" method="POST" class="absolute top-4 right-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        <h3 class="font-bold text-gray-800">{{ $faq->question }}</h3>
                        <p class="text-sm text-gray-600 mt-2">{{ $faq->answer }}</p>
                    </div>
                @endforeach
            </div>
        </div>
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
</script>
@endsection
