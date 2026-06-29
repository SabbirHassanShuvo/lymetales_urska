@extends('layouts.admin')
@section('content')

<style>
    .label { display:block; font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#6b7280; margin-bottom:0.35rem; }
    .input { width:100%; padding:0.55rem 0.85rem; border:1.5px solid #e5e7eb; border-radius:0.6rem; font-size:0.875rem; color:#1f2937; background:#fff; transition:border-color 0.15s, box-shadow 0.15s; outline:none; box-sizing:border-box; }
    .input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .card { background:#fff; border:1.5px solid #f1f2f4; border-radius:1.1rem; padding:1.5rem; margin-bottom:1.25rem; }
    .card-title { font-size:0.875rem; font-weight:700; color:#374151; display:flex; align-items:center; gap:0.5rem; margin-bottom:1.1rem; }
    .dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.85rem; }
    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:0.85rem; }
    @media(max-width:768px){ .grid-3,.grid-2{ grid-template-columns:1fr; } }
    .btn-add { font-size:0.75rem; font-weight:600; background:#eef2ff; color:#4f46e5; border:none; padding:0.4rem 0.85rem; border-radius:0.5rem; cursor:pointer; transition:background 0.15s; }
    .btn-add:hover { background:#e0e7ff; }
    .cat-block { border:2px solid #f1f2f4; border-radius:0.9rem; padding:1.1rem; }
    .q-block { background:#f9fafb; border:1.5px solid #f1f2f4; border-radius:0.65rem; padding:0.85rem; }
</style>

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.5rem">
    <div>
        <div style="margin-bottom:0.35rem">
            <span style="background:#fef3c7;color:#d97706;font-size:0.7rem;font-weight:700;padding:0.25rem 0.65rem;border-radius:999px;text-transform:uppercase;letter-spacing:0.07em">FAQ</span>
        </div>
        <h2 style="font-size:1.5rem;font-weight:800;color:#111827;margin:0">Edit FAQ Page</h2>
        <p style="font-size:0.85rem;color:#9ca3af;margin-top:0.25rem">Manage FAQ categories and questions</p>
    </div>
    <div style="display:flex;align-items:center;gap:1rem">
        <div style="display:flex;align-items:center;gap:0.5rem;background:#fff;padding:0.35rem 0.75rem;border-radius:0.5rem;border:1.5px solid #e5e7eb;">
            <label style="font-size:0.75rem;font-weight:600;color:#4b5563;">Language:</label>
            <select onchange="window.location.href='?lang=' + this.value" style="font-size:0.75rem;padding:0.2rem 0.5rem;border:1px solid #d1d5db;border-radius:0.35rem;background:#f9fafb;outline:none;cursor:pointer;">
                <option value="SL" {{ ($page->language_type ?? 'SL') == 'SL' ? 'selected' : '' }}>SL (Slovenian)</option>
                <option value="HR" {{ ($page->language_type ?? 'SL') == 'HR' ? 'selected' : '' }}>HR (Croatian)</option>
                <option value="EN" {{ ($page->language_type ?? 'SL') == 'EN' ? 'selected' : '' }}>EN (English)</option>
            </select>
        </div>
        <a href="{{ route('admin.pages.index') }}" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.85rem;font-weight:600;color:#6b7280;background:#f3f4f6;padding:0.5rem 1rem;border-radius:0.65rem;text-decoration:none" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
            <svg style="width:1rem;height:1rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Pages
        </a>
    </div>
</div>

<form action="{{ route('admin.pages.update', $page) }}" method="POST" x-data="faqEditor({{ json_encode($content) }})">
@csrf @method('PUT')

{{-- Meta --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#9ca3af"></span> Page Meta & SEO</div>
    <div class="grid-3" style="margin-bottom:0.75rem">
        <div><label class="label">Page Title</label><input type="text" name="title" value="{{ $page->title }}" class="input"></div>
        <div><label class="label">Meta Title</label><input type="text" name="meta_title" value="{{ $page->meta_title }}" class="input"></div>
        <div><label class="label">Meta Description</label><input type="text" name="meta_description" value="{{ $page->meta_description }}" class="input"></div>
    </div>
    <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ $page->is_active ? 'checked' : '' }} style="width:1rem;height:1rem;accent-color:#6366f1">
        <span style="font-size:0.85rem;color:#4b5563">Page is active</span>
    </label>
</div>

{{-- Header --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#f59e0b"></span> Page Header</div>
    <div class="grid-3">
        <div><label class="label">Badge</label><input type="text" name="header_badge" value="{{ $content['header']['badge'] ?? '' }}" class="input"></div>
        <div><label class="label">Title</label><input type="text" name="header_title" value="{{ $content['header']['title'] ?? '' }}" class="input"></div>
        <div><label class="label">Subtitle</label><input type="text" name="header_subtitle" value="{{ $content['header']['subtitle'] ?? '' }}" class="input"></div>
    </div>
</div>

{{-- Categories & Q&A --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.1rem">
        <div class="card-title" style="margin-bottom:0"><span class="dot" style="background:#6366f1"></span> FAQ Categories & Questions</div>
        <button type="button" class="btn-add" @click="addCategory()">+ Add Category</button>
    </div>

    <template x-for="(cat, ci) in categories" :key="ci">
        <div>
            <input type="hidden" :name="'cat_name['+ci+']'" :value="cat.name">
            <template x-for="(q, qi) in cat.questions" :key="qi">
                <div>
                    <input type="hidden" :name="'cat_index['+globalQIndex(ci,qi)+']'" :value="ci">
                    <input type="hidden" :name="'question['+globalQIndex(ci,qi)+']'" :value="q.question">
                    <input type="hidden" :name="'answer['+globalQIndex(ci,qi)+']'" :value="q.answer">
                </div>
            </template>
        </div>
    </template>

    <div style="display:flex;flex-direction:column;gap:1rem">
        <template x-for="(cat, ci) in categories" :key="ci">
            <div class="cat-block">
                {{-- Category Header --}}
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.85rem">
                    <div style="flex:1">
                        <label class="label">Category Name</label>
                        <input type="text" x-model="cat.name" class="input" placeholder="e.g. Personalisation">
                    </div>
                    <button type="button" @click="categories.splice(ci,1)" style="background:#fff5f5;color:#f87171;border:none;cursor:pointer;border-radius:0.5rem;padding:0.4rem 0.6rem;margin-top:1.35rem;transition:background 0.15s" title="Delete category">
                        <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>

                {{-- Questions --}}
                <div style="display:flex;flex-direction:column;gap:0.5rem;padding-left:0.75rem">
                    <template x-for="(q, qi) in cat.questions" :key="qi">
                        <div class="q-block">
                            <div style="display:flex;align-items:flex-start;gap:0.75rem">
                                <div style="flex:1;display:flex;flex-direction:column;gap:0.5rem">
                                    <div><label class="label">Question</label><input type="text" x-model="q.question" class="input" placeholder="How does...?"></div>
                                    <div>
                                        <label class="label">Answer</label>
                                        <div x-init="
                                            ClassicEditor.create($refs.editor).then(editor => {
                                                editor.setData(q.answer || '');
                                                editor.model.document.on('change:data', () => {
                                                    q.answer = editor.getData();
                                                });
                                            }).catch(err => console.error(err));
                                        ">
                                            <textarea x-ref="editor" x-model="q.answer" rows="2" class="input" style="resize:vertical" placeholder="Answer..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" @click="cat.questions.splice(qi,1)" style="background:none;color:#f87171;border:none;cursor:pointer;padding:0.25rem;border-radius:0.35rem;margin-top:1.4rem;flex-shrink:0" title="Remove question">
                                    <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="cat.questions.push({question:'', answer:''})" style="font-size:0.75rem;color:#6366f1;background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:0.3rem;padding:0.35rem 0;font-weight:600">
                        <svg style="width:0.8rem;height:0.8rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Add Question to this Category
                    </button>
                </div>
            </div>
        </template>
        <p x-show="categories.length === 0" style="text-align:center;color:#9ca3af;font-size:0.85rem;padding:1.5rem 0">No categories yet. Click "Add Category" to start.</p>
    </div>
</div>

{{-- CTA --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#22c55e"></span> Bottom CTA (Still have questions?)</div>
    <div class="grid-2">
        <div><label class="label">Title</label><input type="text" name="cta_title" value="{{ $content['cta']['title'] ?? '' }}" class="input"></div>
        <div><label class="label">Subtitle</label><input type="text" name="cta_subtitle" value="{{ $content['cta']['subtitle'] ?? '' }}" class="input"></div>
        <div><label class="label">Button Text</label><input type="text" name="cta_button_text" value="{{ $content['cta']['button_text'] ?? '' }}" class="input"></div>
        <div><label class="label">Button URL</label><input type="text" name="cta_button_url" value="{{ $content['cta']['button_url'] ?? '' }}" class="input"></div>
    </div>
</div>

{{-- Submit --}}
<div style="display:flex;justify-content:flex-end;padding-bottom:2rem">
    <button type="submit" style="background:#4f46e5;color:#fff;padding:0.7rem 2.2rem;border-radius:0.75rem;font-weight:700;font-size:0.9rem;border:none;cursor:pointer;transition:background 0.15s" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
        Save All Changes
    </button>
</div>
</form>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable_inline { min-height: 150px; font-size: 0.9rem; }
</style>
<script>
function faqEditor(content) {
    return {
        categories: content.categories || [],
        addCategory() { this.categories.push({ name: '', questions: [] }); },
        globalQIndex(ci, qi) {
            let idx = 0;
            for (let i = 0; i < ci; i++) idx += (this.categories[i].questions || []).length;
            return idx + qi;
        }
    }
}
</script>
@endsection