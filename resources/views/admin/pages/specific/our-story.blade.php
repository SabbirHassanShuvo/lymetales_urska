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

<form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data" x-data="ourStoryForm()">
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
        <div class="col-2">
            <label class="field-label">Hero Image</label>
            <input type="file" name="hero_image_file" class="field-input" accept="image/*" onchange="previewImage(event, 'hero-preview')">
            <img id="hero-preview" src="#" alt="Hero Preview" style="display: none; height: 100px; margin-top: 10px; border-radius: 8px; object-fit: cover;">
            @if(!empty($content['hero']['image_url']))
                <div class="mt-2 flex items-center gap-2">
                    <img src="{{ asset($content['hero']['image_url']) }}" style="height: 40px; border-radius: 4px;" alt="Hero">
                    <span class="text-xs text-gray-500">Current Image</span>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ── Mission Section ────────────────────────── --}}
<div class="card" x-data="{ missionParas: {{ json_encode(array_map(fn($p) => ['html' => $p], $content['mission']['paragraphs'] ?? (isset($content['mission']['paragraph_1']) ? array_values(array_filter([$content['mission']['paragraph_1'], $content['mission']['paragraph_2'] ?? ''])) : []))) }} }">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
        <div class="card-title" style="margin-bottom:0">
            <span class="dot" style="background:#22c55e"></span> Mission Section
        </div>
        <button type="button" class="btn-add" @click="missionParas.push({html:''})">+ Add Paragraph</button>
    </div>
    <div style="display:flex;flex-direction:column;gap:0.75rem">
        <div><label class="field-label">Title</label><input type="text" name="mission_title" value="{{ $content['mission']['title'] ?? '' }}" class="field-input"></div>
        <template x-for="(para, i) in missionParas" :key="i">
            <div style="display:flex;align-items:flex-start;gap:0.5rem">
                <div style="flex:1">
                    <label class="field-label" x-text="'Paragraph ' + (i+1)"></label>
                    <textarea :name="'mission_paragraphs['+i+']'" x-init="initCKEditor($el, para, 'html')" class="field-input"></textarea>
                </div>
                <button type="button" class="btn-remove" @click="missionParas.splice(i,1)" style="margin-top:1.5rem" title="Remove Paragraph">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>
</div>

{{-- ── Quality Section ────────────────────────── --}}
<div class="card" x-data="{ qualityItems: {{ json_encode(array_map(function($item) {
    $paras = $item['paragraphs'] ?? array_values(array_filter([$item['paragraph_1'] ?? null, $item['paragraph_2'] ?? null]));
    $item['paragraphs'] = array_map(fn($p) => ['html' => $p], $paras);
    $item['_preview'] = '';
    return $item;
}, $content['quality_section']['items'] ?? (isset($content['quality_section']['left']) ? [$content['quality_section']['left'], $content['quality_section']['right']] : []))) }} }">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
        <div class="card-title" style="margin-bottom:0">
            <span class="dot" style="background:#eab308"></span> Quality Section
        </div>
        <button type="button" class="btn-add" @click="qualityItems.push({badge:'', title:'', paragraphs:[], image_url:'', _preview:''})">+ Add Block</button>
    </div>
    <div class="grid-2">
        <template x-for="(item, i) in qualityItems" :key="i">
            <div style="border:1.5px solid #f1f2f4;border-radius:0.75rem;padding:1rem;position:relative">
                <button type="button" class="btn-remove" @click="qualityItems.splice(i,1)" style="position:absolute;top:0.5rem;right:0.5rem">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <p style="font-size:0.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.75rem" x-text="'Block ' + (i+1)"></p>
                <div style="display:flex;flex-direction:column;gap:0.6rem">
                    <div><label class="field-label">Badge</label><textarea :name="'quality_badge['+i+']'" x-init="initCKEditor($el, item, 'badge')" class="field-input"></textarea></div>
                    <div><label class="field-label">Title</label><textarea :name="'quality_title['+i+']'" x-init="initCKEditor($el, item, 'title')" class="field-input"></textarea></div>
                    
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem">
                            <label class="field-label" style="margin-bottom:0">Paragraphs</label>
                            <button type="button" class="btn-add" @click="if(!item.paragraphs) item.paragraphs = []; item.paragraphs.push({html:''})" style="font-size:0.65rem;padding:0.2rem 0.5rem">+ Add Paragraph</button>
                        </div>
                        <template x-for="(p, pi) in (item.paragraphs || [])" :key="pi">
                            <div style="display:flex;align-items:flex-start;gap:0.5rem;margin-bottom:0.5rem">
                                <div style="flex:1">
                                    <textarea :name="'quality_p['+i+']['+pi+']'" x-init="initCKEditor($el, p, 'html')" class="field-input"></textarea>
                                </div>
                                <button type="button" class="btn-remove" @click="item.paragraphs.splice(pi,1)" title="Remove Paragraph">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <div>
                        <label class="field-label">Image</label>
                        <input type="file" :name="'quality_image_file['+i+']'" class="field-input" accept="image/*"
                            @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = e => { item._preview = e.target.result; };
                                    reader.readAsDataURL(file);
                                }
                            ">
                        <input type="hidden" :name="'old_quality_image['+i+']'" :value="item.image_url">
                        {{-- Live preview for newly selected file --}}
                        <template x-if="item._preview">
                            <div class="mt-2 flex items-center gap-2">
                                <img :src="item._preview" style="height:60px;border-radius:4px;object-fit:cover;" alt="Preview">
                                <span class="text-xs" style="color:#6366f1;font-weight:600">New Image (not saved yet)</span>
                            </div>
                        </template>
                        {{-- Current saved image (only show when no new preview) --}}
                        <template x-if="item.image_url && !item._preview">
                            <div class="mt-2 flex items-center gap-2">
                                <img :src="'/' + item.image_url" style="height:40px;border-radius:4px;" alt="Image">
                                <button type="button" class="btn-remove" @click="item.image_url = ''" title="Remove image" style="font-size:0.7rem;padding:0.2rem 0.5rem;background:#fef2f2;border-radius:0.35rem">✕ Cancel</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
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
<div class="card" x-data="galleryManager({{ json_encode($content['gallery']['images'] ?? []) }})">
    <div class="card-title"><span class="dot" style="background:#14b8a6"></span> Gallery Section</div>
    <div style="margin-bottom:1rem">
        <label class="field-label">Gallery Title</label>
        <input type="text" name="gallery_title" value="{{ $content['gallery']['title'] ?? '' }}" class="field-input">
    </div>
    <div>
        <label class="field-label">Add New Images <span style="font-weight:400;text-transform:none;letter-spacing:0">(Select multiple)</span></label>
        {{-- Visible trigger input without name --}}
        <input type="file" multiple class="field-input" accept="image/*" @change="addNewFiles($event)">
        {{-- Hidden input that actually submits the files --}}
        <input type="file" id="gallery-file-input" name="gallery_image_files[]" multiple style="display:none">
    </div>
    {{-- Hidden inputs for kept existing images --}}
    <template x-for="img in existingImages" :key="img">
        <input type="hidden" name="old_gallery_images[]" :value="img">
    </template>
    {{-- Image grid --}}
    <div class="mt-3" style="display:flex;flex-wrap:wrap;gap:0.75rem" x-show="existingImages.length > 0 || newPreviews.length > 0">
        {{-- Existing images with cancel --}}
        <template x-for="(img, idx) in existingImages" :key="img">
            <div style="position:relative;display:inline-block">
                <img :src="'/' + img" style="height:80px;width:80px;object-fit:cover;border-radius:8px;border:1.5px solid #e5e7eb;" alt="Gallery">
                <button type="button" @click="removeExisting(idx)" title="Remove"
                    style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;background:#ef4444;color:#fff;border:none;border-radius:50%;cursor:pointer;font-size:0.7rem;display:flex;align-items:center;justify-content:center;">✕</button>
            </div>
        </template>
        {{-- New image previews with cancel --}}
        <template x-for="(prev, idx) in newPreviews" :key="idx">
            <div style="position:relative;display:inline-block">
                <img :src="prev.url" style="height:80px;width:80px;object-fit:cover;border-radius:8px;border:2px dashed #6366f1;" alt="New">
                <span style="position:absolute;bottom:0;left:0;right:0;background:rgba(99,102,241,0.8);color:#fff;font-size:0.55rem;font-weight:700;text-align:center;border-radius:0 0 7px 7px;padding:1px 0">NEW</span>
                <button type="button" @click="removeNew(idx)" title="Cancel"
                    style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;background:#ef4444;color:#fff;border:none;border-radius:50%;cursor:pointer;font-size:0.7rem;display:flex;align-items:center;justify-content:center;">✕</button>
            </div>
        </template>
    </div>
    <p x-show="existingImages.length === 0 && newPreviews.length === 0" style="color:#9ca3af;font-size:0.85rem;margin-top:0.75rem">No gallery images yet.</p>
</div>

{{-- ── CTA ─────────────────────────────────────── --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#f97316"></span> Bottom CTA Section</div>
    <div class="grid-2">
        <div><label class="field-label">Title</label><input type="text" name="cta_title" value="{{ $content['cta']['title'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Description</label><input type="text" name="cta_description" value="{{ $content['cta']['description'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Button Text</label><input type="text" name="cta_button_text" value="{{ $content['cta']['button_text'] ?? '' }}" class="field-input"></div>
        <div><label class="field-label">Button URL</label><input type="text" name="cta_button_url" value="{{ $content['cta']['button_url'] ?? '' }}" class="field-input"></div>
        <div class="col-2">
            <label class="field-label">CTA Image</label>
            <input type="file" name="cta_image_file" class="field-input" accept="image/*" onchange="previewImage(event, 'cta-preview')">
            <img id="cta-preview" src="#" alt="CTA Preview" style="display: none; height: 100px; margin-top: 10px; border-radius: 8px; object-fit: cover;">
            @if(!empty($content['cta']['image_url']))
                <div class="mt-2 flex items-center gap-2">
                    <img src="{{ asset($content['cta']['image_url']) }}" style="height: 40px; border-radius: 4px;" alt="CTA">
                    <span class="text-xs text-gray-500">Current Image</span>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Save Bar --}}
<div style="display:flex;justify-content:flex-end;padding-bottom:2rem">
    <button type="submit" id="save-all-btn"
        style="background:#4f46e5;color:#fff;padding:0.7rem 2.2rem;border-radius:0.75rem;font-weight:700;font-size:0.9rem;border:none;cursor:pointer;transition:background 0.15s" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
        Save All Changes
    </button>
</div>
</form>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
    /* Fix CKEditor z-index and styles */
    .ck-editor__editable_inline { min-height: 150px; font-size: 0.9rem; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for elements with .ck-editor class
    document.querySelectorAll('.ck-editor').forEach(el => {
        ClassicEditor.create(el, {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ]
        }).catch(error => { console.error(error); });
    });
});

window.initCKEditor = function(el, item, key) {
    // Wait for Alpine to finish rendering before creating editor
    setTimeout(() => {
        let editorInstance;
        ClassicEditor.create(el, {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ]
        }).then(editor => {
            editorInstance = editor;
            // item[key] must be an object property (not a primitive) for reactivity
            editor.setData(item[key] || '');
            editor.model.document.on('change:data', () => {
                item[key] = editor.getData();
            });
        }).catch(error => { console.error(error); });
    }, 50);
};

function galleryManager(existingImgs) {
    return {
        existingImages: existingImgs || [],
        newPreviews: [],
        _allFiles: [],

        addNewFiles(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                const url = URL.createObjectURL(file);
                this.newPreviews.push({ url, file });
                this._allFiles.push(file);
            });
            // Sync the named file input so server receives the files
            this._syncNamedInput();
            // Reset the trigger input
            event.target.value = '';
        },

        removeExisting(idx) {
            this.existingImages.splice(idx, 1);
        },

        removeNew(idx) {
            URL.revokeObjectURL(this.newPreviews[idx].url);
            this.newPreviews.splice(idx, 1);
            this._allFiles.splice(idx, 1);
            this._syncNamedInput();
        },

        _syncNamedInput() {
            // Push the current _allFiles into the named hidden input via DataTransfer
            const dt = new DataTransfer();
            this._allFiles.forEach(f => dt.items.add(f));
            const input = document.getElementById('gallery-file-input');
            if (input) input.files = dt.files;
        }
    };
}

function ourStoryForm() { 
    return {
        // Alpine data...
    }; 
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('form').addEventListener('submit', function(e) {
        // Find the gallery manager Alpine component and sync its files before submission
        const galleryEl = document.querySelector('[x-data*="galleryManager"]');
        if (galleryEl && galleryEl._x_dataStack) {
            const gm = galleryEl._x_dataStack[0];
            if (gm && typeof gm.submitFiles === 'function') {
                gm.submitFiles();
            }
        }
    });
});

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

function previewMultipleImages(event, containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = ''; // Clear previous previews
    
    Array.from(event.target.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.height = '100px';
            img.style.borderRadius = '8px';
            img.style.objectFit = 'cover';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endsection