@extends('layouts.admin')
@section('content')

<style>
    .field-label { display:block; font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#6b7280; margin-bottom:0.35rem; }
    .field-input { width:100%; padding:0.55rem 0.85rem; border:1.5px solid #e5e7eb; border-radius:0.6rem; font-size:0.875rem; color:#1f2937; background:#fff; transition:border-color 0.15s, box-shadow 0.15s; outline:none; }
    .field-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .field-error { font-size:0.75rem; color:#ef4444; margin-top:0.3rem; }
    .card { background:#fff; border:1.5px solid #f1f2f4; border-radius:1.1rem; padding:1.5rem; margin-bottom:1.25rem; }
    .card-title { font-size:0.875rem; font-weight:700; color:#374151; display:flex; align-items:center; gap:0.5rem; margin-bottom:1.1rem; }
    .dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    @media(max-width:768px){ .grid-2{ grid-template-columns:1fr; } }
</style>

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.5rem">
    <div>
        <h2 style="font-size:1.5rem;font-weight:800;color:#111827;margin:0">Add New Page</h2>
        <p style="font-size:0.85rem;color:#9ca3af;margin-top:0.25rem">Create a new dynamic page (e.g. Terms, Privacy Policy)</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.85rem;font-weight:600;color:#6b7280;background:#f3f4f6;padding:0.5rem 1rem;border-radius:0.65rem;text-decoration:none;transition:background 0.15s" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
        <svg style="width:1rem;height:1rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Pages
    </a>
</div>

<form action="{{ route('admin.pages.store') }}" method="POST">
@csrf

{{-- Title & Slug --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#6366f1"></span> Page Identity</div>
    <div class="grid-2">
        <div>
            <label class="field-label" for="title">Page Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="e.g. Privacy Policy" class="field-input">
            @error('title')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="field-label" for="slug">Slug / URL Path</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="e.g. privacy-policy" class="field-input">
            @error('slug')<p class="field-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- SEO --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#22c55e"></span> SEO Settings</div>
    <div class="grid-2">
        <div>
            <label class="field-label" for="meta_title">Meta Title</label>
            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" placeholder="Leave blank to use Page Title" class="field-input">
            @error('meta_title')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="field-label" for="meta_description">Meta Description</label>
            <textarea name="meta_description" id="meta_description" rows="2" class="field-input" style="resize:vertical">{{ old('meta_description') }}</textarea>
            @error('meta_description')<p class="field-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- Status --}}
<div class="card" style="padding:1.1rem 1.5rem">
    <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width:1rem;height:1rem;accent-color:#6366f1">
        <div>
            <span style="font-size:0.875rem;font-weight:600;color:#374151">Page is Active</span>
            <span style="font-size:0.8rem;color:#9ca3af;margin-left:0.35rem">— visible on the frontend</span>
        </div>
    </label>
</div>

{{-- Content --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#f59e0b"></span> Page Content</div>
    <p style="font-size:0.78rem;color:#9ca3af;margin-bottom:0.75rem;margin-top:-0.5rem">
        Use JSON to structure complex page data, or plain text for simple pages.
        Example: <code style="background:#f3f4f6;padding:0.1rem 0.35rem;border-radius:0.3rem;font-size:0.75rem">{"sections": [{"type": "hero", "title": "..."}]}</code>
    </p>
    <textarea name="content" id="content" rows="16" placeholder='{\n  "title": "...",\n  "sections": []\n}' class="field-input" style="font-family:monospace;font-size:0.82rem;resize:vertical">{{ old('content') }}</textarea>
    @error('content')<p class="field-error">{{ $message }}</p>@enderror
</div>

{{-- Submit --}}
<div style="display:flex;justify-content:flex-end;padding-bottom:2rem">
    <button type="submit" style="background:#4f46e5;color:#fff;padding:0.7rem 2.2rem;border-radius:0.75rem;font-weight:700;font-size:0.9rem;border:none;cursor:pointer;transition:background 0.15s" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
        Create Page
    </button>
</div>
</form>
@endsection