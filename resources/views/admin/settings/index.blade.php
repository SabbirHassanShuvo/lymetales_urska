@extends('layouts.admin', ['title' => __('Settings')])

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'general' }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">System Settings</h2>
            <p class="text-sm text-gray-500 mt-1">Configure branding, store settings, payment integrations, and SMTP credentials.</p>
        </div>
    </div>

    <!-- Tab Buttons -->
    <div class="flex flex-wrap border-b border-gray-100 bg-white p-2.5 rounded-xl shadow-sm border border-gray-100/50 gap-1 select-none">
        <button type="button" @click="activeTab = 'branding'" :class="activeTab === 'branding' ? 'bg-violet-50 text-violet-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg transition-all focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
            Admin Branding
        </button>
        <button type="button" @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg transition-all focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            General & Shipping
        </button>
        <button type="button" @click="activeTab = 'payments'" :class="activeTab === 'payments' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg transition-all focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Payment Gateways
        </button>
        <button type="button" @click="activeTab = 'smtp'" :class="activeTab === 'smtp' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg transition-all focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Mail Server (SMTP)
        </button>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="px-5 py-4 bg-green-50 text-green-700 border border-green-100 rounded-xl text-sm font-semibold animate-fade-in flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        @csrf

        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- Tab 0: Admin Branding                               --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'branding'" x-transition class="space-y-8">

            <div>
                <h3 class="text-lg font-bold text-gray-800 border-b border-gray-50 pb-3">Admin Panel Branding</h3>
                <p class="text-xs text-gray-400 mt-2">Customize the logo and site name shown in the admin sidebar.</p>
            </div>

            {{-- Logo Upload --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Admin Panel Logo</label>
                    @php $adminLogoPath = $settings['admin_logo_path'] ?? ''; @endphp
                    <div class="mb-3 flex items-center gap-4">
                        <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden" id="logoPreviewWrap">
                            @if($adminLogoPath && file_exists(public_path($adminLogoPath)))
                                <img id="adminLogoPreview" src="{{ asset($adminLogoPath) }}" class="w-full h-full object-contain p-2" alt="Admin Logo">
                            @else
                                <img id="adminLogoPreview" src="#" class="w-full h-full object-contain p-2" alt="Admin Logo" style="display:none">
                                <span id="adminLogoPlaceholder" class="text-2xl font-bold text-indigo-600">LT</span>
                            @endif
                        </div>
                        <div>
                            <label for="admin_logo_file" class="inline-flex items-center gap-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-semibold px-4 py-2 rounded-xl cursor-pointer transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload Logo
                            </label>
                            <input type="file" id="admin_logo_file" name="admin_logo_file" accept="image/*" class="hidden" onchange="previewAdminLogo(this)">
                            <p class="text-xs text-gray-400 mt-2">PNG, SVG, or WebP. Recommended: 200×60px</p>
                            @if($adminLogoPath)
                                <label class="flex items-center gap-1.5 mt-2 cursor-pointer">
                                    <input type="checkbox" name="remove_admin_logo" value="1" class="accent-red-500">
                                    <span class="text-xs text-red-500 font-medium">Remove current logo</span>
                                </label>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Panel Site Name</label>
                        <input type="text" name="admin_site_name"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-violet-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-100 transition-all font-semibold text-sm"
                            value="{{ old('admin_site_name', $settings['admin_site_name'] ?? 'LYMETALES') }}"
                            placeholder="e.g. LYMETALES">
                        <p class="text-xs text-gray-400 mt-1">Shown in the sidebar header when no logo is uploaded.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Panel Tagline</label>
                        <input type="text" name="admin_tagline"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-violet-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-100 transition-all font-semibold text-sm"
                            value="{{ old('admin_tagline', $settings['admin_tagline'] ?? 'Admin Portal') }}"
                            placeholder="e.g. Admin Portal">
                        <p class="text-xs text-gray-400 mt-1">Shown on the login page below the site name.</p>
                    </div>
                </div>
            </div>

            {{-- Login Page Customization --}}
            <div class="pt-4 border-t border-gray-50">
                <h3 class="text-base font-bold text-gray-700 mb-4">Login Page Background</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Background Style</label>
                        <select name="admin_login_bg"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-violet-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-100 transition-all font-semibold text-sm cursor-pointer">
                            @foreach(['gradient-indigo' => 'Indigo / Violet Gradient (Default)', 'gradient-slate' => 'Slate / Gray', 'gradient-emerald' => 'Emerald / Teal', 'solid-white' => 'Plain White'] as $val => $label)
                                <option value="{{ $val }}" {{ (old('admin_login_bg', $settings['admin_login_bg'] ?? 'gradient-indigo') === $val) ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Accent Color (hex)</label>
                        <div class="flex gap-3 items-center">
                            <input type="color" name="admin_accent_color"
                                class="w-12 h-11 rounded-xl border border-gray-200 cursor-pointer"
                                value="{{ old('admin_accent_color', $settings['admin_accent_color'] ?? '#4f46e5') }}">
                            <input type="text" name="admin_accent_color_text"
                                class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 focus:border-violet-400 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-100 transition-all font-semibold text-sm font-mono"
                                value="{{ old('admin_accent_color_text', $settings['admin_accent_color'] ?? '#4f46e5') }}"
                                placeholder="#4f46e5">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- Tab 1: General & Shipping                           --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'general'" x-transition class="space-y-6">
            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-50 pb-3">General Settings</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Shipping Charge (€)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-3 text-gray-400 font-bold">€</span>
                        <input type="number" step="0.01" name="shipping_charge" 
                            class="w-full pl-8 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all font-semibold text-sm"
                            value="{{ old('shipping_charge', $settings['shipping_charge'] ?? '5.95') }}" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fast Production Fee (€)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-3 text-gray-400 font-bold">€</span>
                        <input type="number" step="0.01" name="fast_production_fee" 
                            class="w-full pl-8 pr-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all font-semibold text-sm"
                            value="{{ old('fast_production_fee', $settings['fast_production_fee'] ?? '9.95') }}" required>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Global Discount Type</label>
                    <select name="global_discount_type" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all font-semibold text-sm cursor-pointer" required>
                        <option value="fixed" {{ (old('global_discount_type', $settings['global_discount_type'] ?? 'fixed') == 'fixed') ? 'selected' : '' }}>Fixed Amount (€)</option>
                        <option value="percentage" {{ (old('global_discount_type', $settings['global_discount_type'] ?? 'fixed') == 'percentage') ? 'selected' : '' }}>Percentage (%)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Global Discount Value</label>
                    <input type="number" step="0.01" name="global_discount_value" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all font-semibold text-sm"
                        value="{{ old('global_discount_value', $settings['global_discount_value'] ?? '0') }}" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">WhatsApp URL</label>
                    <input type="url" name="whatsapp_url" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all font-semibold text-sm"
                        value="{{ old('whatsapp_url', $settings['whatsapp_url'] ?? '') }}" placeholder="https://wa.me/...">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">VAT Rate (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="shop_vat_rate" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all font-semibold text-sm"
                        value="{{ old('shop_vat_rate', $settings['shop_vat_rate'] ?? '22.00') }}" required>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- Tab 2: Payment Gateways                             --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'payments'" x-transition class="space-y-8">
            <!-- Stripe Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-50 pb-3">
                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold uppercase tracking-wider">Stripe</span>
                    <h3 class="text-lg font-bold text-gray-800">Stripe Configuration</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stripe Publishable Key</label>
                        <input type="text" name="stripe_key" placeholder="pk_test_..."
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-mono"
                            value="{{ old('stripe_key', $settings['stripe_key'] ?? '') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stripe Secret Key</label>
                        <input type="password" name="stripe_secret" placeholder="sk_test_..."
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-mono"
                            value="{{ old('stripe_secret', $settings['stripe_secret'] ?? '') }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stripe Webhook Secret</label>
                    <input type="password" name="stripe_webhook_secret" placeholder="whsec_..."
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-mono"
                        value="{{ old('stripe_webhook_secret', $settings['stripe_webhook_secret'] ?? '') }}">
                </div>
            </div>

            <!-- PayPal Section -->
            <div class="space-y-6 pt-4">
                <div class="flex items-center gap-3 border-b border-gray-50 pb-3">
                    <span class="px-2.5 py-1 bg-sky-50 text-sky-700 rounded-lg text-xs font-bold uppercase tracking-wider">PayPal</span>
                    <h3 class="text-lg font-bold text-gray-800">PayPal Configuration</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">PayPal Mode</label>
                        <select name="paypal_mode" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all font-semibold text-sm cursor-pointer">
                            <option value="sandbox" {{ (old('paypal_mode', $settings['paypal_mode'] ?? 'sandbox') == 'sandbox') ? 'selected' : '' }}>Sandbox (Testing)</option>
                            <option value="live" {{ (old('paypal_mode', $settings['paypal_mode'] ?? 'sandbox') == 'live') ? 'selected' : '' }}>Live (Production)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-sky-50/10 p-5 rounded-2xl border border-sky-100/30">
                    <div class="md:col-span-2"><span class="text-xs font-bold text-sky-700 uppercase tracking-widest block mb-2">Sandbox Credentials</span></div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sandbox Client ID</label>
                        <input type="text" name="paypal_sandbox_client_id" placeholder="Client ID"
                            class="w-full px-4 py-3 bg-white border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-mono"
                            value="{{ old('paypal_sandbox_client_id', $settings['paypal_sandbox_client_id'] ?? '') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sandbox Client Secret</label>
                        <input type="password" name="paypal_sandbox_client_secret" placeholder="Secret Key"
                            class="w-full px-4 py-3 bg-white border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-mono"
                            value="{{ old('paypal_sandbox_client_secret', $settings['paypal_sandbox_client_secret'] ?? '') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-indigo-50/10 p-5 rounded-2xl border border-indigo-100/30">
                    <div class="md:col-span-2"><span class="text-xs font-bold text-indigo-700 uppercase tracking-widest block mb-2">Live Credentials</span></div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Live Client ID</label>
                        <input type="text" name="paypal_live_client_id" placeholder="Client ID"
                            class="w-full px-4 py-3 bg-white border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-mono"
                            value="{{ old('paypal_live_client_id', $settings['paypal_live_client_id'] ?? '') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Live Client Secret</label>
                        <input type="password" name="paypal_live_client_secret" placeholder="Secret Key"
                            class="w-full px-4 py-3 bg-white border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-mono"
                            value="{{ old('paypal_live_client_secret', $settings['paypal_live_client_secret'] ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- Tab 3: SMTP                                         --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'smtp'" x-transition class="space-y-6">
            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-50 pb-3">SMTP Mail Configuration</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mail Mailer</label>
                    <select name="mail_mailer" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all font-semibold text-sm cursor-pointer">
                        <option value="smtp" {{ (old('mail_mailer', $settings['mail_mailer'] ?? 'smtp') == 'smtp') ? 'selected' : '' }}>SMTP</option>
                        <option value="log" {{ (old('mail_mailer', $settings['mail_mailer'] ?? 'smtp') == 'log') ? 'selected' : '' }}>Log (Testing)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">SMTP Host</label>
                    <input type="text" name="mail_host" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-semibold"
                        value="{{ old('mail_host', $settings['mail_host'] ?? '') }}" placeholder="e.g. smtp.mailtrap.io">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">SMTP Port</label>
                    <input type="number" name="mail_port" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-semibold"
                        value="{{ old('mail_port', $settings['mail_port'] ?? '') }}" placeholder="e.g. 2525">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">SMTP Username</label>
                    <input type="text" name="mail_username" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-semibold"
                        value="{{ old('mail_username', $settings['mail_username'] ?? '') }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">SMTP Password</label>
                    <input type="password" name="mail_password" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-semibold"
                        value="{{ old('mail_password', $settings['mail_password'] ?? '') }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">SMTP Encryption</label>
                    <select name="mail_encryption" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all font-semibold text-sm cursor-pointer">
                        <option value="" {{ (old('mail_encryption', $settings['mail_encryption'] ?? '') == '') ? 'selected' : '' }}>None</option>
                        <option value="tls" {{ (old('mail_encryption', $settings['mail_encryption'] ?? '') == 'tls') ? 'selected' : '' }}>TLS (Recommended)</option>
                        <option value="ssl" {{ (old('mail_encryption', $settings['mail_encryption'] ?? '') == 'ssl') ? 'selected' : '' }}>SSL</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">From Email Address</label>
                    <input type="email" name="mail_from_address" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-semibold"
                        value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}" placeholder="hello@example.com">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">From Name</label>
                    <input type="text" name="mail_from_name" 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 focus:border-indigo-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-semibold"
                        value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}" placeholder="Lyme Tales">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Save Settings
            </button>
        </div>
    </form>
</div>

<script>
    function previewAdminLogo(input) {
        const img = document.getElementById('adminLogoPreview');
        const placeholder = document.getElementById('adminLogoPlaceholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                img.src = e.target.result;
                img.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
