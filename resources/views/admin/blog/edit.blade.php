@extends('layouts.admin')
@section('content')

<style>
    .label { display:block; font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#6b7280; margin-bottom:0.35rem; }
    .input { width:100%; padding:0.55rem 0.85rem; border:1.5px solid #e5e7eb; border-radius:0.6rem; font-size:0.875rem; color:#1f2937; background:#fff; transition:border-color 0.15s, box-shadow 0.15s; outline:none; box-sizing:border-box; }
    .input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .card { background:#fff; border:1.5px solid #f1f2f4; border-radius:1.1rem; padding:1.5rem; margin-bottom:1.25rem; }
    .card-title { font-size:0.875rem; font-weight:700; color:#374151; display:flex; align-items:center; gap:0.5rem; margin-bottom:1.1rem; }
    .dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:0.85rem; }
    .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.85rem; }
    @media(max-width:768px){ .grid-2, .grid-3 { grid-template-columns:1fr; } }
    .image-preview { width:120px; height:120px; object-fit:cover; border-radius:0.75rem; border:1.5px solid #e5e7eb; margin-top:0.5rem; display:block; }
</style>

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.5rem">
    <div>
        <div style="margin-bottom:0.35rem">
            <span style="background:#e0e7ff;color:#4f46e5;font-size:0.7rem;font-weight:700;padding:0.25rem 0.65rem;border-radius:999px;text-transform:uppercase;letter-spacing:0.07em">Blog</span>
        </div>
        <h2 style="font-size:1.5rem;font-weight:800;color:#111827;margin:0">Edit Blog Post</h2>
        <p style="font-size:0.85rem;color:#9ca3af;margin-top:0.25rem">Update article details and publishing parameters</p>
    </div>
    <div style="display:flex;align-items:center;gap:1rem">
        <div style="display:flex;align-items:center;gap:0.5rem;background:#fff;padding:0.35rem 0.75rem;border-radius:0.5rem;border:1.5px solid #e5e7eb;">
            <label style="font-size:0.75rem;font-weight:600;color:#4b5563;">Language:</label>
            <select onchange="window.location.href='?lang=' + this.value" style="font-size:0.75rem;padding:0.2rem 0.5rem;border:1px solid #d1d5db;border-radius:0.35rem;background:#f9fafb;outline:none;cursor:pointer;">
                <option value="SL" {{ $lang == 'SL' ? 'selected' : '' }}>SL (Slovenian)</option>
                <option value="HR" {{ $lang == 'HR' ? 'selected' : '' }}>HR (Croatian)</option>
                <option value="EN" {{ $lang == 'EN' ? 'selected' : '' }}>EN (English)</option>
            </select>
        </div>
        <a href="{{ route('admin.blog.index') }}?lang={{ $lang }}" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.85rem;font-weight:600;color:#6b7280;background:#f3f4f6;padding:0.5rem 1rem;border-radius:0.65rem;text-decoration:none" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
            <svg style="width:1rem;height:1rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Articles
        </a>
    </div>
</div>

@if ($errors->any())
    <div style="background:#fef2f2; border:1.5px solid #fecaca; border-radius:0.75rem; padding:1rem; margin-bottom:1.25rem; color:#b91c1c; font-size:0.85rem">
        <strong style="display:block;margin-bottom:0.35rem">Please fix the following errors:</strong>
        <ul style="list-style:disc; margin-left:1.25rem">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.blog.update', $blogPost) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

{{-- Basic Info --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#6366f1"></span> General Info</div>
    <div class="grid-3" style="margin-bottom:0.75rem">
        <div>
            <label class="label">Post Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $blogPost->title) }}" class="input" required>
        </div>
        <div>
            <label class="label">Slug (URL identifier)</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $blogPost->slug) }}" class="input">
        </div>
        <div>
            <label class="label">Language</label>
            <input type="text" value="{{ $lang }}" class="input" readonly style="background:#f3f4f6; color:#6b7280; font-weight:bold;">
            <input type="hidden" name="language_type" value="{{ $lang }}">
        </div>
    </div>
    
    <div class="grid-3">
        <div>
            <label class="label">Category</label>
            <select name="category" class="input" required>
                <option value="">Select Category</option>
                @foreach(['Storytelling', 'Parenting', 'Behind the Scenes', 'Gift Guide'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $blogPost->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Reading Time</label>
            <input type="text" name="reading_time" value="{{ old('reading_time', $blogPost->reading_time) }}" class="input">
        </div>
        <div>
            <label class="label">Publish Date</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $blogPost->published_at ? $blogPost->published_at->format('Y-m-d\TH:i') : '') }}" class="input">
        </div>
    </div>
</div>

{{-- Media and Layout --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#10b981"></span> Cover Image & Status</div>
    <div class="grid-2" style="margin-bottom:1rem">
        <div>
            <label class="label">Cover Image File</label>
            <input type="file" id="cover_image_file" name="cover_image_file" class="input" accept="image/*" onchange="previewImage(this)">
            @if($blogPost->cover_image)
                <img id="preview" src="{{ asset($blogPost->cover_image) }}" class="image-preview" alt="Preview Image">
            @else
                <img id="preview" class="image-preview" style="display:none" alt="Preview Image">
            @endif
        </div>
        <div style="display:flex;flex-direction:column;justify-content:center;gap:0.75rem">
            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $blogPost->is_featured) ? 'checked' : '' }} style="width:1.15rem;height:1.15rem;accent-color:#6366f1">
                <span style="font-size:0.875rem;color:#4b5563;font-weight:600">⭐ Mark as Featured Post</span>
            </label>
            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $blogPost->is_active) ? 'checked' : '' }} style="width:1.15rem;height:1.15rem;accent-color:#6366f1">
                <span style="font-size:0.875rem;color:#4b5563;font-weight:600">🟢 Post is Active (Visible on Blog)</span>
            </label>
        </div>
    </div>
</div>

{{-- Content Only --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#f59e0b"></span> Article Body</div>
    <div>
        <label class="label">Full Content</label>
        <textarea id="editor" name="content" rows="12" class="input" style="resize:vertical">{{ old('content', $blogPost->content) }}</textarea>
    </div>
</div>

{{-- Submit --}}
<div style="display:flex;justify-content:flex-end;padding-bottom:2rem">
    <button type="submit" style="background:#4f46e5;color:#fff;padding:0.7rem 2.2rem;border-radius:0.75rem;font-weight:700;font-size:0.9rem;border:none;cursor:pointer;transition:background 0.15s" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
        Save Changes
    </button>
</div>
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    let blogEditor;
    ClassicEditor.create(document.querySelector('#editor'), {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo']
    }).then(editor => {
        blogEditor = editor;
    }).catch(err => console.error(err));

    document.querySelector('form').addEventListener('submit', function () {
        if (blogEditor) {
            document.querySelector('#editor').value = blogEditor.getData();
        }
    });

    // Auto slugify
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    titleInput.addEventListener('input', function() {
        if (!slugInput.dataset.edited) {
            slugInput.value = titleInput.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
    slugInput.addEventListener('input', function() {
        slugInput.dataset.edited = true;
    });

    // Image preview
    function previewImage(input) {
        const preview = document.getElementById('preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<style>
    .ck-editor__editable_inline { min-height: 350px; font-size: 0.9rem; }
</style>
@endsection
