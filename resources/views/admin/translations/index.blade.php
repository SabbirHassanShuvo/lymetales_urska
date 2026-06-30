@extends('layouts.admin')

@section('title', 'CMS Manage')

@section('content')
<div class="min-h-screen bg-slate-50/50">
    <form action="{{ route('admin.translations.update') }}" method="POST" id="cms-form">
        @csrf
        <input type="hidden" name="lang" value="{{ $currentLang }}">
        <input type="hidden" name="group" value="{{ $currentGroup }}">

        {{-- Top Sticky Header (CMS Vibe) --}}
        <div class="sticky top-0 z-30 bg-white border-b border-slate-200 shadow-sm px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-2 bg-indigo-50 rounded-xl">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">CMS Content Manager</h1>
                    <p class="text-sm text-slate-500 font-medium mt-0.5">Edit texts, labels, and structured content across your site.</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                {{-- Language Switcher Pills --}}
                <div class="flex bg-slate-100 p-1 rounded-lg border border-slate-200">
                    @foreach ($langs as $l)
                        <a href="{{ route('admin.translations.index', ['lang' => $l, 'group' => $currentGroup]) }}"
                           class="px-4 py-1.5 rounded-md text-sm font-semibold transition-all duration-200
                                  {{ $currentLang === $l
                                      ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-slate-900/5'
                                      : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                            {{ $l }}
                        </a>
                    @endforeach
                </div>
                <div class="w-px h-8 bg-slate-200"></div>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Publish Changes
                </button>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-8 flex gap-8">
            
            {{-- Left: Content Groups (Sidebar) --}}
            <div class="w-64 flex-shrink-0">
                <div class="sticky top-28 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50/80 border-b border-slate-100">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Content Sections</h3>
                    </div>
                    <nav class="p-3 space-y-1">
                        @foreach ($groups as $gKey => $gLabel)
                            <a href="{{ route('admin.translations.index', ['lang' => $currentLang, 'group' => $gKey]) }}"
                               class="group flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                                      {{ $currentGroup === $gKey
                                          ? 'bg-indigo-50 text-indigo-700'
                                          : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 {{ $currentGroup === $gKey ? 'text-indigo-500' : 'text-slate-400 group-hover:text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $gLabel }}
                                </div>
                                @if ($currentGroup === $gKey)
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                @endif
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            {{-- Right: Content Editor Area --}}
            <div class="flex-1 min-w-0">
                @if (session('success'))
                    <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-5 py-4 text-sm font-medium shadow-sm">
                        <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($translations->isEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 border-dashed flex flex-col items-center justify-center py-24 text-center px-8">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-700">No Content Blocks Found</h3>
                        <p class="text-sm text-slate-500 mt-1 max-w-md">There are no text entries for <strong>{{ $groups[$currentGroup] ?? $currentGroup }}</strong> in <strong>{{ $currentLang }}</strong>.</p>
                    </div>
                @else
                    <div class="mb-6 flex items-baseline justify-between">
                        <h2 class="text-xl font-bold text-slate-800">Editing: {{ $groups[$currentGroup] ?? ucfirst($currentGroup) }}</h2>
                        <span class="text-sm font-medium text-slate-500">{{ $translations->count() }} Content Blocks</span>
                    </div>

                    <div class="space-y-6">
                        @foreach ($translations as $t)
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden transition-all hover:shadow-md">
                                {{-- Card Header --}}
                                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                            @if ($t->input_type === 'json')
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                            @elseif ($t->input_type === 'textarea')
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                            @else
                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-800">{{ $t->display_name ?? $t->key }}</h3>
                                            <p class="text-xs font-mono text-slate-400 mt-0.5">{{ $t->key }}</p>
                                        </div>
                                    </div>
                                    @if ($t->input_type === 'json')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                            Structured Data
                                        </span>
                                    @endif
                                </div>

                                {{-- Card Body (Input) --}}
                                <div class="p-6">
                                    @if ($t->input_type === 'json')
                                        <textarea
                                            id="field_{{ $loop->index }}"
                                            name="translations[{{ $t->key }}]"
                                            rows="8"
                                            class="w-full text-[13px] font-mono bg-slate-900 text-emerald-400 rounded-xl px-5 py-4 border border-slate-700 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none resize-y transition-all shadow-inner leading-relaxed"
                                            placeholder="Valid JSON only..."
                                        >{{ $t->value }}</textarea>
                                        <div class="mt-3 flex items-center gap-2 text-xs text-amber-600 bg-amber-50 p-2.5 rounded-lg border border-amber-200/50">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            Ensure this remains valid JSON formatting. Invalid JSON structures will be rejected upon save.
                                        </div>
                                    @elseif ($t->input_type === 'textarea')
                                        <textarea
                                            id="field_{{ $loop->index }}"
                                            name="translations[{{ $t->key }}]"
                                            rows="4"
                                            class="w-full text-[15px] bg-slate-50/50 hover:bg-white rounded-xl px-5 py-3.5 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 outline-none resize-y transition-all shadow-sm leading-relaxed text-slate-700 placeholder-slate-400"
                                            placeholder="Enter text content..."
                                        >{{ $t->value }}</textarea>
                                    @else
                                        <input
                                            type="text"
                                            id="field_{{ $loop->index }}"
                                            name="translations[{{ $t->key }}]"
                                            value="{{ $t->value }}"
                                            class="w-full text-[15px] bg-slate-50/50 hover:bg-white rounded-xl px-5 py-3.5 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-400 outline-none transition-all shadow-sm text-slate-700 font-medium placeholder-slate-400"
                                            placeholder="Enter text..."
                                        >
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection
