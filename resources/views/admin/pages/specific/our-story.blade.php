@extends('layouts.admin')
@section('content')

{{-- Shared styles for this page --}}
<style>
    .field-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 0.35rem;
    }
    .field-input {
        width: 100%;
        padding: 0.55rem 0.85rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 0.6rem;
        font-size: 0.875rem;
        color: #1f2937;
        background: #fff;
        transition: border-color 0.15s, box-shadow 0.15s;
        outline: none;
    }
    .field-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
    }
    .card {
        background: #fff;
        border: 1.5px solid #f1f2f4;
        border-radius: 1.1rem;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .card-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.1rem;
    }
    .dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .btn-add {
        font-size: 0.75rem;
        font-weight: 600;
        background: #eef2ff;
        color: #4f46e5;
        border: none;
        padding: 0.4rem 0.85rem;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background 0.15s;
    }
    .btn-add:hover { background: #e0e7ff; }
    .btn-remove {
        color: #f87171;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.2rem;
        border-radius: 0.4rem;
        transition: color 0.15s, background 0.15s;
        flex-shrink: 0;
    }
    .btn-remove:hover { color: #dc2626; background: #fef2f2; }
    .step-badge {
        width: 2.2rem; height: 2.2rem;
        border-radius: 50%;
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 700;
        font-size: 0.85rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
    @media(max-width:768px){ .grid-2,.grid-3{ grid-template-columns:1fr; } }
    .col-2 { grid-column: span 2; }
    @media(max-width:768px){ .col-2{ grid-column: span 1; } }
</style>

{{-- Page Header --}}
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <div class="flex items-center gap-2 mb-1">
            <span class="bg-indigo-50 text-indigo-600 text-xs font-bold px-2.5 py-1 rounded-full border border-indigo-100">Our Story</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Edit Our Story Page</h2>
        <p class="text-sm text-gray-400 mt-0.5">Manage all sections of the Our Story page</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 px-3.5 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Pages
    </a>
</div>

<form action="{{ route('admin.pages.update', $page) }}" method="POST" x-data="ourStoryForm()">
@csrf @method('PUT')

{{-- ── Page Meta ──────────────────────────────── --}}
<div class="card">
    <div class="card-title">
        <span class="dot" style="background:#9ca3af"></span> Page Meta & SEO
    </div>
    <div class="grid-3" style="margin-bottom:0.75rem">
        <div>
            <label class="field-label">Page Title</label>
            <input type="text" name="title" value="{{ $page->title }}" class="field-input">
        </div>
        <div>
            <label class="field-label">Meta Title</label>
            <input type="text" name="meta_title" value="{{ $page->meta_title }}" class="field-input">
        </div>
        <div>
            <label class="field-label">Meta Description</label>
            <input type="text" name="meta_description" value="{{ $page->meta_description }}" class="field-input">
        </div>
    </div>
    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;margin-top:0.25rem">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ $page->is_active ? 'checked' : '' }} style="width:1rem;height:1rem;accent-color:#6366f1">
        <span style="font-size:0.85rem;color:#4b5563">Page is active (visible on frontend)</span>
    </label>
</div>

{{-- ── Hero Section ───────────────────────────── --}}
<div class="card">
    <div class="card-title">
        <span class="dot" style="background:#6366f1"></span> Hero Section
    </div>
    <div class="grid-2">
        <div><label class="field-label">Badge Text</label><input type="text" name="hero_badge" value="{{ $content['hero']['badge'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Hero Title</label><input type="text" name="hero_title" value="{{ $content['hero']['title'] ?? '' }}" class="field-input"></div>
        <div class="col-2"><label class="field-label">Subtitle</label><input type="text" name="hero_subtitle" value="{{ $content['hero']['subtitle'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Button Text</label><input type="text" name="hero_button_text" value="{{ $content['hero']['button_text'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Button URL</label><input type="text" name="hero_button_url" value="{{ $content['hero']['button_url'] ?? '' }}" class="field-input"></div>
        <div class="col-2"><label class="field-label">Hero Image URL</label><input type="text" name="hero_image_url" value="{{ $content['hero']['image_url'] ?? '' }}" class="field-input" placeholder="https://..."></div>
    </div>
</div>

{{-- ── Mission Section ────────────────────────── --}}
<div class="card">
    <div class="card-title">
        <span class="dot" style="background:#22c55e"></span> Mission Section
    </div>
    <div style="display:flex;flex-direction:column;gap:0.75rem">
        <div><label class="field-label">Title</label><input type="text" name="mission_title" value="{{ $content['mission']['title'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Paragraph 1</label><textarea name="mission_paragraph_1" rows="3" class="field-input" style="resize:vertical">{{ $content['mission']['paragraph_1'] ?? '' }}</textarea></div>
        <div><label class="field-label">Paragraph 2</label><textarea name="mission_paragraph_2" rows="3" class="field-input" style="resize:vertical">{{ $content['mission']['paragraph_2'] ?? '' }}</textarea></div>
    </div>
</div>

{{-- ── Quality Section ────────────────────────── --}}
<div class="card">
    <div class="card-title">
        <span class="dot" style="background:#eab308"></span> Quality Section
    </div>
    <div class="grid-2">
        <div style="border:1.5px solid #f1f2f4;border-radius:0.75rem;padding:1rem">
            <p style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.75rem">Left Block</p>
            <div style="display:flex;flex-direction:column;gap:0.6rem">
                <div><label class="field-label">Badge</label><input type="text" name="quality_left_badge" value="{{ $content['quality_section']['left']['badge'] ?? '' }}" class="field-input"></div>
                <div><label class="field-label">Title</label><input type="text" name="quality_left_title" value="{{ $content['quality_section']['left']['title'] ?? '' }}" class="field-input"></div>
                <div><label class="field-label">Paragraph 1</label><textarea name="quality_left_p1" rows="2" class="field-input" style="resize:vertical">{{ $content['quality_section']['left']['paragraph_1'] ?? '' }}</textarea></div>
                <div><label class="field-label">Paragraph 2</label><textarea name="quality_left_p2" rows="2" class="field-input" style="resize:vertical">{{ $content['quality_section']['left']['paragraph_2'] ?? '' }}</textarea></div>
                <div><label class="field-label">Image URL</label><input type="text" name="quality_left_image" value="{{ $content['quality_section']['left']['image_url'] ?? '' }}" class="field-input" placeholder="https://..."></div>
            </div>
        </div>
        <div style="border:1.5px solid #f1f2f4;border-radius:0.75rem;padding:1rem">
            <p style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.75rem">Right Block</p>
            <div style="display:flex;flex-direction:column;gap:0.6rem">
                <div><label class="field-label">Badge</label><input type="text" name="quality_right_badge" value="{{ $content['quality_section']['right']['badge'] ?? '' }}" class="field-input"></div>
                <div><label class="field-label">Title</label><input type="text" name="quality_right_title" value="{{ $content['quality_section']['right']['title'] ?? '' }}" class="field-input"></div>
                <div><label class="field-label">Paragraph 1</label><textarea name="quality_right_p1" rows="2" class="field-input" style="resize:vertical">{{ $content['quality_section']['right']['paragraph_1'] ?? '' }}</textarea></div>
                <div><label class="field-label">Paragraph 2</label><textarea name="quality_right_p2" rows="2" class="field-input" style="resize:vertical">{{ $content['quality_section']['right']['paragraph_2'] ?? '' }}</textarea></div>
                <div><label class="field-label">Image URL</label><input type="text" name="quality_right_image" value="{{ $content['quality_section']['right']['image_url'] ?? '' }}" class="field-input" placeholder="https://..."></div>
            </div>
        </div>
    </div>
</div>

{{-- ── Steps ──────────────────────────────────── --}}
<div class="card" x-data="{ steps: {{ json_encode($content['steps']['items'] ?? []) }} }">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
        <div class="card-title" style="margin-bottom:0">
            <span class="dot" style="background:#a855f7"></span> Creating Magic Steps
        </div>
        <button type="button" class="btn-add" @click="steps.push({step: steps.length+1, title:'', description:''})">+ Add Step</button>
    </div>
    <div style="margin-bottom:0.75rem">
        <label class="field-label">Section Title</label>
        <input type="text" name="steps_title" value="{{ $content['steps']['title'] ?? '' }}" class="field-input">
    </div>
    <div style="display:flex;flex-direction:column;gap:0.6rem">
        <template x-for="(step, i) in steps" :key="i">
            <div style="display:flex;align-items:flex-start;gap:0.75rem;border:1.5px solid #f1f2f4;border-radius:0.75rem;padding:1rem">
                <div class="step-badge" x-text="i+1"></div>
                <div style="flex:1;display:grid;grid-template-columns:1fr 1fr;gap:0.6rem">
                    <input type="hidden" :name="'steps_step['+i+']'" :value="i+1">
                    <div><label class="field-label">Step Title</label><input type="text" :name="'steps_item_title['+i+']'" x-model="step.title" class="field-input"></div>
                    <div><label class="field-label">Description</label><input type="text" :name="'steps_item_desc['+i+']'" x-model="step.description" class="field-input"></div>
                </div>
                <button type="button" class="btn-remove" @click="steps.splice(i,1)" title="Remove step">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
        <p x-show="steps.length === 0" style="text-align:center;color:#9ca3af;font-size:0.85rem;padding:1.5rem 0">No steps yet. Click "Add Step" to start.</p>
    </div>
</div>

{{-- ── The Difference ─────────────────────────── --}}
<div class="card" x-data="{ items: {{ json_encode($content['difference']['items'] ?? []) }} }">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
        <div class="card-title" style="margin-bottom:0">
            <span class="dot" style="background:#ec4899"></span> The Lymetales Difference
        </div>
        <button type="button" class="btn-add" @click="items.push({title:'', description:''})">+ Add Item</button>
    </div>
    <div style="margin-bottom:0.75rem">
        <label class="field-label">Section Title</label>
        <input type="text" name="difference_title" value="{{ $content['difference']['title'] ?? '' }}" class="field-input">
    </div>
    <div class="grid-2">
        <template x-for="(item, i) in items" :key="i">
            <div style="border:1.5px solid #f1f2f4;border-radius:0.75rem;padding:1rem;position:relative">
                <button type="button" class="btn-remove" @click="items.splice(i,1)" style="position:absolute;top:0.5rem;right:0.5rem">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div style="padding-right:1.5rem;display:flex;flex-direction:column;gap:0.5rem">
                    <div><label class="field-label">Title</label><input type="text" :name="'diff_title['+i+']'" x-model="item.title" class="field-input"></div>
                    <div><label class="field-label">Description</label><textarea :name="'diff_desc['+i+']'" x-model="item.description" rows="2" class="field-input" style="resize:vertical"></textarea></div>
                </div>
            </div>
        </template>
    </div>
</div>

{{-- ── Stats ──────────────────────────────────── --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#f59e0b"></span> Stats Section</div>
    <div class="grid-3">
        <div><label class="field-label">Big Number (e.g. 1M+)</label><input type="text" name="stats_number" value="{{ $content['stats']['number'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Label</label><input type="text" name="stats_label" value="{{ $content['stats']['label'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Quote</label><input type="text" name="stats_quote" value="{{ $content['stats']['quote'] ?? '' }}" class="field-input"></div>
    </div>
</div>

{{-- ── Gallery ────────────────────────────────── --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#14b8a6"></span> Gallery Section</div>
    <div class="grid-2">
        <div><label class="field-label">Gallery Title</label><input type="text" name="gallery_title" value="{{ $content['gallery']['title'] ?? '' }}" class="field-input"></div>
        <div>
            <label class="field-label">Image URLs <span style="font-weight:400;text-transform:none;letter-spacing:0">(one per line)</span></label>
            <textarea name="gallery_images" rows="4" class="field-input" style="font-family:monospace;font-size:0.8rem;resize:vertical" placeholder="https://image1.jpg&#10;https://image2.jpg">{{ implode("\n", $content['gallery']['images'] ?? []) }}</textarea>
        </div>
    </div>
</div>

{{-- ── CTA ─────────────────────────────────────── --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#f97316"></span> Bottom CTA Section</div>
    <div class="grid-2">
        <div><label class="field-label">Title</label><input type="text" name="cta_title" value="{{ $content['cta']['title'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Description</label><input type="text" name="cta_description" value="{{ $content['cta']['description'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Button Text</label><input type="text" name="cta_button_text" value="{{ $content['cta']['button_text'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Button URL</label><input type="text" name="cta_button_url" value="{{ $content['cta']['button_url'] ?? '' }}" class="field-input"></div>
        <div class="col-2"><label class="field-label">CTA Image URL</label><input type="text" name="cta_image_url" value="{{ $content['cta']['image_url'] ?? '' }}" class="field-input"></div>
    </div>
</div>

{{-- Save Bar --}}
<div style="display:flex;justify-content:flex-end;padding-bottom:2rem">
    <button type="submit" style="background:#4f46e5;color:#fff;padding:0.7rem 2.2rem;border-radius:0.75rem;font-weight:700;font-size:0.9rem;border:none;cursor:pointer;transition:background 0.15s" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
        Save All Changes
    </button>
</div>
</form>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function ourStoryForm() { return {}; }
</script>
@endsection