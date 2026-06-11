<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('admin.dashboard') }} - UR Consultant</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-100 flex-shrink-0 relative">
            <div class="h-16 flex items-center px-8 border-b border-gray-50">
                <span class="text-xl font-bold text-gray-800 tracking-tight"><span
                        class="text-indigo-600">LYMETALES</span></span>
            </div>
            <nav class="mt-8 px-4 space-y-2 mb-24">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a11 11 0 001 1h3m10-11l2 2m-2-2v10a11 11 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    {{ __('admin.dashboard') }}
                </a>
                <!-- <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.users.index') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    {{ __('admin.user_request') }}
                </a> -->
                <a href="{{ route('admin.products.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.products.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    {{ 'Books' }}
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    {{ __('admin.categories') }}
                </a>
                <a href="{{ route('admin.site-categories.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.site-categories.*') || request()->routeIs('admin.site-subcategories.*') ? 'bg-teal-50 text-teal-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z">
                        </path>
                    </svg>
                    Categories
                </a>
                <a href="{{ route('admin.coupons.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.coupons.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm-2 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    {{ __('admin.coupons') }}
                </a>
                <a href="{{ route('admin.offers.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.offers.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    Offers
                </a>
                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    Orders
                </a>
                {{-- ── Content Management ─────────────────────────── --}}
                @php
                    $pagesOpen = request()->routeIs('admin.pages.*');
                    $allPages = \App\Models\Page::orderBy('id')->get();
                    $unreadCount = \App\Models\ContactMessage::where('is_read', false)->count();
                @endphp

                {{-- Section heading --}}
                <p class="px-4 pt-4 pb-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Content</p>

                {{-- Pages group toggle --}}
                <div x-data="{ open: {{ $pagesOpen ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open"
                        class="w-full flex items-center px-4 py-3 {{ $pagesOpen ? 'text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="flex-1 text-left">Pages</span>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    {{-- Sub-links: one per known page slug --}}
                    <div x-show="open" x-transition class="ml-4 pl-3 border-l border-gray-100 mt-1 space-y-1">
                        @foreach([
                            'our-story'            => ['label' => 'Our Story',          'color' => 'bg-indigo-400'],
                            'privacy-policy'       => ['label' => 'Privacy Policy',      'color' => 'bg-blue-400'],
                            'terms-and-conditions' => ['label' => 'Terms of Service',    'color' => 'bg-blue-400'],
                            'faq'                  => ['label' => 'FAQ',                 'color' => 'bg-amber-400'],
                            'contact-us'           => ['label' => 'Contact With Us',     'color' => 'bg-green-400'],
                        ] as $slug => $meta)
                            @php $pg = $allPages->where('slug', $slug)->first() @endphp
                            @if($pg)
                                <a href="{{ route('admin.pages.edit', $pg) }}"
                                    class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->is('*pages/'.$pg->id.'/edit') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }} transition-all">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $meta['color'] }} flex-shrink-0"></span>
                                    {{ $meta['label'] }}
                                </a>
                            @else
                                <span class="flex items-center gap-2 px-3 py-2 text-sm text-gray-300 rounded-lg cursor-not-allowed">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-200 flex-shrink-0"></span>
                                    {{ $meta['label'] }}
                                    <span class="text-[10px] bg-gray-100 text-gray-400 px-1.5 rounded ml-auto">Seed</span>
                                </span>
                            @endif
                        @endforeach
                        <a href="{{ route('admin.pages.index') }}"
                            class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg text-gray-400 hover:bg-gray-50 hover:text-gray-700 transition-all">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 flex-shrink-0"></span>
                            All Pages
                        </a>
                    </div>
                </div>

                {{-- Home Content --}}
                <a href="{{ route('admin.home-content.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.home-content.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="flex-1">Home Content</span>
                </a>

                {{-- Gifts --}}
                <a href="{{ route('admin.gifts.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.gifts.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="flex-1">Gifts</span>
                </a>

                {{-- Messages --}}
                <a href="{{ route('admin.contact-messages.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.contact-messages.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span class="flex-1">Messages</span>
                    @if($unreadCount > 0)
                        <span class="ml-auto bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.settings.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                    Settings
                </a>
            </nav>

            <div class="absolute bottom-8 w-64 px-4">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-3 text-gray-500 hover:bg-red-50 hover:text-red-700 rounded-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        {{ __('admin.logout') }}
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="h-16 flex items-center justify-between px-8 bg-white border-b border-gray-100 flex-shrink-0">
                <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? __('admin.dashboard') }}</h1>
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <!-- <div class="flex items-center bg-gray-50 rounded-lg p-1 mr-4 border border-gray-100">
                        <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 text-xs font-bold rounded-md transition-all {{ App::getLocale() === 'en' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">EN</a>
                        <a href="{{ route('lang.switch', 'nl') }}" class="px-3 py-1 text-xs font-bold rounded-md transition-all {{ App::getLocale() === 'nl' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">NL</a>
                    </div> -->
                    
                    <span class="text-sm text-gray-500">{{ auth()->user()->first_name }}
                        {{ auth()->user()->last_name }}</span>
                    <div
                        class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>

</html>