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
            <div class="h-16 flex items-center px-6 border-b border-gray-50">
                @php
                    $adminLogoPath = \App\Models\Setting::getVal('admin_logo_path', '');
                    $adminSiteName = \App\Models\Setting::getVal('admin_site_name', 'LYMETALES');
                @endphp
                @if($adminLogoPath && file_exists(public_path($adminLogoPath)))
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset($adminLogoPath) }}" alt="Admin Logo" style="max-height:38px;max-width:160px;object-fit:contain">
                    </a>
                @else
                    <span class="text-xl font-bold text-gray-800 tracking-tight">
                        <span class="text-indigo-600">{{ strtoupper($adminSiteName) }}</span>
                    </span>
                @endif
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
                <a href="{{ route('admin.reports.revenue') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.reports.revenue') ? 'bg-green-50 text-green-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                    Revenue Reports
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
                        <a href="{{ route('admin.blog.index') }}"
                            class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.blog.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }} transition-all">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 flex-shrink-0"></span>
                            Our Blog
                        </a>
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

                {{-- Subscribers --}}
                <a href="{{ route('admin.subscribers.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.subscribers.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                    <span class="flex-1">Subscribers</span>
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
                <a href="{{ route('admin.gdpr.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('admin.gdpr.*') ? 'bg-rose-50 text-rose-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    GDPR Tools
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
                    
                    <a href="{{ route('admin.profile.index') }}" style="display:flex;align-items:center;gap:0.5rem;text-decoration:none;padding:0.35rem 0.65rem;border-radius:0.65rem;transition:background 0.15s{{ request()->routeIs('admin.profile.*') ? ';background:#eef2ff' : '' }}" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='{{ request()->routeIs('admin.profile.*') ? '#eef2ff' : 'transparent' }}'">
                        <span style="font-size:0.82rem;font-weight:600;color:#4b5563">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                        @php $avatar = auth()->user()->avatar; @endphp
                        @if($avatar && file_exists(public_path($avatar)))
                            <img src="{{ asset($avatar) }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid #e0e7ff" alt="Avatar">
                        @else
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.72rem;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                            </div>
                        @endif
                    </a>
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

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 shadow-sm flex items-start space-x-3">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-red-800 text-sm">Please correct the following errors:</h4>
                            <ul class="list-disc list-inside text-xs mt-1.5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    <script>
    class TableHelper {
        constructor(tableSelector, searchInputSelector, paginationContainerSelector, pageSize = 10, rowSelector = 'tbody tr') {
            this.table = document.querySelector(tableSelector);
            if (!this.table) return;
            this.tbody = this.table.querySelector('tbody');
            this.rowSelector = rowSelector;
            this.searchInput = document.querySelector(searchInputSelector);
            this.paginationContainer = document.querySelector(paginationContainerSelector);
            this.pageSize = pageSize;
            this.currentPage = 1;
            
            // Extract all rows initially
            this.allRows = Array.from(this.tbody.querySelectorAll(this.rowSelector)).filter(row => !row.classList.contains('no-results-row'));
            this.filteredRows = [...this.allRows];

            this.init();
        }

        init() {
            if (this.searchInput) {
                this.searchInput.addEventListener('input', () => {
                    this.currentPage = 1;
                    this.filter();
                });
                this.filter();
            } else {
                this.render();
            }
        }

        filter() {
            const query = this.searchInput.value.toLowerCase().trim();
            this.filteredRows = this.allRows.filter(row => {
                let searchableText = '';
                if (row.dataset.name) searchableText += ' ' + row.dataset.name.toLowerCase();
                if (row.dataset.title) searchableText += ' ' + row.dataset.title.toLowerCase();
                if (row.dataset.code) searchableText += ' ' + row.dataset.code.toLowerCase();
                if (row.dataset.email) searchableText += ' ' + row.dataset.email.toLowerCase();
                if (row.dataset.category) searchableText += ' ' + row.dataset.category.toLowerCase();
                
                if (searchableText === '') {
                    searchableText = row.innerText.toLowerCase();
                }
                
                return searchableText.includes(query);
            });

            // Show/hide no results row
            let noResultsRow = this.tbody.querySelector('.no-results-row');
            if (this.filteredRows.length === 0) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.className = 'no-results-row';
                    const cols = this.table.querySelectorAll('thead th').length || 10;
                    noResultsRow.innerHTML = `<td colspan="${cols}" class="text-center py-8 text-gray-500 text-sm">No matching results found.</td>`;
                    this.tbody.appendChild(noResultsRow);
                } else {
                    noResultsRow.style.display = '';
                }
            } else if (noResultsRow) {
                noResultsRow.style.display = 'none';
            }

            this.render();
        }

        render() {
            const totalRows = this.filteredRows.length;
            const totalPages = Math.ceil(totalRows / this.pageSize) || 1;

            if (this.currentPage > totalPages) {
                this.currentPage = totalPages;
            }

            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + this.pageSize;

            // Hide all rows
            this.allRows.forEach(row => row.style.display = 'none');

            // Show current page rows
            this.filteredRows.slice(start, end).forEach(row => row.style.display = '');

            // Render pagination controls
            if (this.paginationContainer) {
                this.renderPagination(totalPages);
            }
        }

        renderPagination(totalPages) {
            this.paginationContainer.innerHTML = '';
            if (totalPages <= 1) {
                this.paginationContainer.classList.add('hidden');
                return;
            }
            this.paginationContainer.classList.remove('hidden');

            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-center justify-between border-t border-gray-100 bg-white px-4 py-3 sm:px-6 mt-4 w-full';

            const flex1 = document.createElement('div');
            flex1.className = 'flex flex-1 justify-between sm:hidden';

            const mobPrev = document.createElement('button');
            mobPrev.type = 'button';
            mobPrev.className = 'relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50';
            mobPrev.innerText = 'Previous';
            mobPrev.disabled = this.currentPage === 1;
            mobPrev.onclick = () => { if (this.currentPage > 1) { this.currentPage--; this.render(); } };

            const mobNext = document.createElement('button');
            mobNext.type = 'button';
            mobNext.className = 'relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50';
            mobNext.innerText = 'Next';
            mobNext.disabled = this.currentPage === totalPages;
            mobNext.onclick = () => { if (this.currentPage < totalPages) { this.currentPage++; this.render(); } };

            flex1.appendChild(mobPrev);
            flex1.appendChild(mobNext);

            const flex2 = document.createElement('div');
            flex2.className = 'hidden sm:flex sm:flex-1 sm:items-center sm:justify-between gap-4';

            const info = document.createElement('div');
            const startIdx = this.filteredRows.length === 0 ? 0 : (this.currentPage - 1) * this.pageSize + 1;
            const endIdx = Math.min(this.currentPage * this.pageSize, this.filteredRows.length);
            info.innerHTML = `<p class="text-xs text-gray-500">Showing <span class="font-semibold text-gray-700">${startIdx}</span> to <span class="font-semibold text-gray-700">${endIdx}</span> of <span class="font-semibold text-gray-700">${this.filteredRows.length}</span> results</p>`;

            const nav = document.createElement('nav');
            nav.className = 'isolate inline-flex -space-x-px rounded-md shadow-sm bg-white';

            const prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = 'relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50';
            prevBtn.innerHTML = `<svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.83 10l3.94 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>`;
            prevBtn.disabled = this.currentPage === 1;
            prevBtn.onclick = () => { if (this.currentPage > 1) { this.currentPage--; this.render(); } };
            nav.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.type = 'button';
                if (i === this.currentPage) {
                    pageBtn.className = 'relative z-10 inline-flex items-center bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600';
                } else {
                    pageBtn.className = 'relative inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0';
                }
                pageBtn.innerText = i;
                pageBtn.onclick = () => { this.currentPage = i; this.render(); };
                nav.appendChild(pageBtn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = 'relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-50';
            nextBtn.innerHTML = `<svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.17 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>`;
            nextBtn.disabled = this.currentPage === totalPages;
            nextBtn.onclick = () => { if (this.currentPage < totalPages) { this.currentPage++; this.render(); } };
            nav.appendChild(nextBtn);

            flex2.appendChild(info);
            flex2.appendChild(nav);

            wrapper.appendChild(flex1);
            wrapper.appendChild(flex2);
            this.paginationContainer.appendChild(wrapper);
        }
    }
    </script>
    @stack('scripts')
    @stack('modals')
</body>

</html>