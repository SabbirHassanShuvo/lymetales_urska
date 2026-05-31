@extends('layouts.admin')
@section('content')

@php
    $combinedBody = '';
    $sections = $content['sections'] ?? [];
    foreach ($sections as $sec) {
        if (!empty($sec['title'])) {
            $combinedBody .= '<h2>' . e($sec['title']) . '</h2>';
        }
        $combinedBody .= $sec['body'] ?? '';
    }
@endphp

<style>
    .label { display:block; font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#6b7280; margin-bottom:0.35rem; }
    .input { width:100%; padding:0.55rem 0.85rem; border:1.5px solid #e5e7eb; border-radius:0.6rem; font-size:0.875rem; color:#1f2937; background:#fff; transition:border-color 0.15s, box-shadow 0.15s; outline:none; box-sizing:border-box; }
    .input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .card { background:#fff; border:1.5px solid #f1f2f4; border-radius:1.1rem; padding:1.5rem; margin-bottom:1.25rem; }
    .card-title { font-size:0.875rem; font-weight:700; color:#374151; display:flex; align-items:center; gap:0.5rem; margin-bottom:1.1rem; }
    .dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.85rem; }
    @media(max-width:768px){ .grid-3{ grid-template-columns:1fr; } }
    .btn-add { font-size:0.75rem; font-weight:600; background:#eef2ff; color:#4f46e5; border:none; padding:0.4rem 0.85rem; border-radius:0.5rem; cursor:pointer; transition:background 0.15s; }
    .btn-add:hover { background:#e0e7ff; }
</style>

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.5rem">
    <div>
        <div style="margin-bottom:0.35rem">
            <span style="background:#dbeafe;color:#1d4ed8;font-size:0.7rem;font-weight:700;padding:0.25rem 0.65rem;border-radius:999px;text-transform:uppercase;letter-spacing:0.07em">Legal</span>
        </div>
        <h2 style="font-size:1.5rem;font-weight:800;color:#111827;margin:0">Edit Terms of Service</h2>
        <p style="font-size:0.85rem;color:#9ca3af;margin-top:0.25rem">Manage all sections of the Terms and Conditions page</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.85rem;font-weight:600;color:#6b7280;background:#f3f4f6;padding:0.5rem 1rem;border-radius:0.65rem;text-decoration:none" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
        <svg style="width:1rem;height:1rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Pages
    </a>
</div>

<form action="{{ route('admin.pages.update', $page) }}" method="POST">
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
    <div class="card-title"><span class="dot" style="background:#3b82f6"></span> Page Header</div>
    <div class="grid-3">
        <div><label class="label">Badge</label><input type="text" name="header_badge" value="{{ $content['header']['badge'] ?? '' }}" class="input"></div>
        <div><label class="label">Title</label><input type="text" name="header_title" value="{{ $content['header']['title'] ?? '' }}" class="input"></div>
        <div><label class="label">Last Updated</label><input type="text" name="last_updated" value="{{ $content['header']['last_updated'] ?? '' }}" class="input" placeholder="e.g. April 2025"></div>
    </div>
</div>

{{-- Terms Content --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#6366f1"></span> Terms Content</div>
    <div>
        <label class="label">Page Body Content</label>
        <textarea id="terms_body" name="sections_body" rows="15" class="input" style="resize:vertical">{!! $combinedBody !!}</textarea>
    </div>
</div>

{{-- Submit --}}
<div style="display:flex;justify-content:flex-end;padding-bottom:2rem">
    <button type="submit" style="background:#4f46e5;color:#fff;padding:0.7rem 2.2rem;border-radius:0.75rem;font-weight:700;font-size:0.9rem;border:none;cursor:pointer;transition:background 0.15s" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
        Save All Changes
    </button>
</div>
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let termsEditor;
    ClassicEditor.create(document.querySelector('#terms_body')).then(editor => {
        termsEditor = editor;
    }).catch(err => console.error(err));

    document.querySelector('form').addEventListener('submit', function () {
        if (termsEditor) {
            document.querySelector('#terms_body').value = termsEditor.getData();
        }
    });
</script>
<style>
    .ck-editor__editable_inline { min-height: 400px; font-size: 0.9rem; }
</style>
@endsection