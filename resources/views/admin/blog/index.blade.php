@extends('layouts.admin')
@section('content')

<style>
    .pill { display:inline-flex; align-items:center; gap:0.3rem; font-size:0.68rem; font-weight:600; padding:0.2rem 0.55rem; border-radius:999px; }
    .tbl-th { padding:0.8rem 1rem; font-size:0.68rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; color:#9ca3af; background:#f9fafb; }
    .tbl-td { padding:0.8rem 1rem; vertical-align: middle; font-size:0.83rem; }
    .tbl-row { border-bottom:1px solid #f3f4f6; transition:background 0.12s; }
    .tbl-row:hover { background-color: #fafbff; }
    .tbl-row:last-child { border-bottom:none; }
    .act-btn { font-size:0.72rem; font-weight:600; padding:0.3rem 0.65rem; border-radius:0.45rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; border:none; cursor:pointer; transition:all 0.15s; }
    .cover-wrap { width:46px; height:46px; border-radius:0.6rem; overflow:hidden; flex-shrink:0; background:#f3f4f6; display:flex; align-items:center; justify-content:center; }
    .cover-img { width:46px; height:46px; object-fit:cover; }
    .label { display:block; font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#6b7280; margin-bottom:0.35rem; }
    .input { width:100%; padding:0.55rem 0.85rem; border:1.5px solid #e5e7eb; border-radius:0.6rem; font-size:0.875rem; color:#1f2937; background:#fff; transition:border-color 0.15s, box-shadow 0.15s; outline:none; box-sizing:border-box; }
    .input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
</style>

{{-- Page Header --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.5rem">
    <div>
        <h2 style="font-size:1.4rem;font-weight:800;color:#111827;margin:0">Blog Posts</h2>
        <p style="font-size:0.82rem;color:#9ca3af;margin-top:0.2rem">Manage articles published on the Our Blog section</p>
    </div>
    <a href="{{ route('admin.blog.create') }}" style="display:inline-flex;align-items:center;gap:0.4rem;background:#4f46e5;color:#fff;font-size:0.82rem;font-weight:700;padding:0.55rem 1.1rem;border-radius:0.7rem;text-decoration:none;transition:background 0.15s;white-space:nowrap" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
        <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        New Post
    </a>
</div>

{{-- Blog Page Header Settings Form --}}
<div style="background:#fff;border:1.5px solid #f1f2f4;border-radius:1rem;padding:1.25rem 1.5rem;margin-bottom:1.5rem">
    <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.1rem">
        <span style="width:7px;height:7px;border-radius:50%;background:#6366f1;display:inline-block;flex-shrink:0"></span>
        <h3 style="font-size:0.82rem;font-weight:700;color:#374151;margin:0">Blog Page Header Settings</h3>
        <span style="font-size:0.68rem;color:#9ca3af;background:#f3f4f6;padding:0.15rem 0.5rem;border-radius:999px;margin-left:auto">Displays on the public "Our Blog" page</span>
    </div>
    <form action="{{ route('admin.blog.update-header') }}" method="POST">
        @csrf
        @if(session('header_success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;padding:0.65rem 1rem;border-radius:0.6rem;font-size:0.8rem;font-weight:600;margin-bottom:0.85rem">
                ✓ {{ session('header_success') }}
            </div>
        @endif
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:0.85rem;margin-bottom:0.85rem">
            <div>
                <label class="label">Header Badge</label>
                <input type="text" name="blog_header_badge" class="input" value="{{ \App\Models\Setting::getVal('blog_header_badge', 'THE JOURNAL') }}">
            </div>
            <div>
                <label class="label">Header Title</label>
                <input type="text" name="blog_header_title" class="input" value="{{ \App\Models\Setting::getVal('blog_header_title', 'Stories, ideas, and quiet inspiration') }}">
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr auto;gap:0.85rem;align-items:flex-end">
            <div>
                <label class="label">Header Subtitle</label>
                <input type="text" name="blog_header_subtitle" class="input" value="{{ \App\Models\Setting::getVal('blog_header_subtitle', 'Thoughts on storytelling, parenting, and the small rituals that make childhood feel like magic.') }}">
            </div>
            <div>
                <button type="submit" style="background:#4f46e5;color:#fff;padding:0.55rem 1.1rem;font-weight:700;font-size:0.8rem;border:none;border-radius:0.6rem;cursor:pointer;white-space:nowrap;transition:background 0.15s" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
                    Save Header
                </button>
            </div>
        </div>
    </form>
</div>

{{-- All Posts Table --}}
<div style="background:#fff;border:1.5px solid #f1f2f4;border-radius:1rem;overflow:hidden">
    {{-- Table Toolbar --}}
    <div style="padding:0.9rem 1.25rem;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem">
        <div style="display:flex;align-items:center;gap:0.5rem">
            <span style="font-size:0.82rem;font-weight:700;color:#374151">All Articles</span>
            <span style="font-size:0.7rem;color:#fff;background:#6366f1;padding:0.15rem 0.55rem;border-radius:999px;font-weight:700">{{ $posts->count() }}</span>
        </div>
        <div style="position:relative">
            <input type="text" id="searchInput" placeholder="Search posts..." style="padding:0.42rem 0.85rem 0.42rem 2rem;border:1.5px solid #e5e7eb;border-radius:0.55rem;font-size:0.78rem;width:14rem;outline:none;background:#f9fafb;color:#374151" onfocus="this.style.borderColor='#4f46e5';this.style.background='#fff'" onblur="this.style.borderColor='#e5e7eb';this.style.background='#f9fafb'">
            <svg style="position:absolute;left:0.65rem;top:50%;transform:translateY(-50%);width:0.8rem;height:0.8rem;color:#9ca3af;pointer-events:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    <div style="overflow-x:auto">
        <table id="postsTable" style="width:100%;border-collapse:collapse;text-align:left">
            <thead>
                <tr>
                    <th class="tbl-th" style="width:70px">Cover</th>
                    <th class="tbl-th">Title</th>
                    <th class="tbl-th" style="width:120px">Category</th>
                    <th class="tbl-th" style="width:100px">Read Time</th>
                    <th class="tbl-th" style="width:90px">Status</th>
                    <th class="tbl-th" style="width:100px">Published</th>
                    <th class="tbl-th" style="width:140px;text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                    <tr class="tbl-row" data-name="{{ strtolower($post->title . ' ' . $post->category) }}">
                        <td class="tbl-td">
                            <div class="cover-wrap">
                                @if($post->cover_image)
                                    <img src="{{ asset($post->cover_image) }}" class="cover-img" alt="Cover">
                                @else
                                    <span style="font-size:1.1rem">📝</span>
                                @endif
                            </div>
                        </td>
                        <td class="tbl-td" style="min-width:220px">
                            <div style="font-weight:700;color:#111827;font-size:0.85rem;line-height:1.3">{{ $post->title }}</div>
                            <div style="margin-top:0.2rem;display:flex;gap:0.35rem;align-items:center;flex-wrap:wrap">
                                @if($post->is_featured)
                                    <span class="pill" style="background:#fef3c7;color:#b45309">⭐ Featured</span>
                                @endif
                                <span style="font-size:0.7rem;color:#9ca3af">ID #{{ $post->id }}</span>
                            </div>
                        </td>
                        <td class="tbl-td">
                            <span style="font-size:0.72rem;background:#eef2ff;color:#4f46e5;font-weight:600;padding:0.2rem 0.55rem;border-radius:0.4rem">
                                {{ $post->category }}
                            </span>
                        </td>
                        <td class="tbl-td" style="color:#6b7280;font-size:0.78rem;white-space:nowrap">
                            <svg style="width:0.75rem;height:0.75rem;display:inline;vertical-align:middle;margin-right:0.2rem;color:#9ca3af" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $post->reading_time ?: '—' }}
                        </td>
                        <td class="tbl-td">
                            @if($post->is_active)
                                <span class="pill" style="background:#f0fdf4;color:#16a34a">
                                    <span style="width:5px;height:5px;border-radius:50%;background:#22c55e;flex-shrink:0"></span>
                                    Live
                                </span>
                            @else
                                <span class="pill" style="background:#fef2f2;color:#dc2626">
                                    <span style="width:5px;height:5px;border-radius:50%;background:#ef4444;flex-shrink:0"></span>
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="tbl-td" style="color:#9ca3af;white-space:nowrap">
                            @if($post->published_at)
                                <div style="font-size:0.75rem;color:#374151;font-weight:600">{{ $post->published_at->format('M d, Y') }}</div>
                                <div style="font-size:0.68rem;color:#d1d5db">{{ $post->published_at->format('H:i') }}</div>
                            @else
                                <span style="color:#d1d5db">—</span>
                            @endif
                        </td>
                        <td class="tbl-td" style="text-align:right">
                            <div style="display:inline-flex;gap:0.35rem">
                                <a href="{{ route('admin.blog.edit', $post) }}" class="act-btn" style="background:#eef2ff;color:#4f46e5" onmouseover="this.style.background='#e0e7ff'" onmouseout="this.style.background='#eef2ff'">
                                    <svg style="width:0.72rem;height:0.72rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit
                                </a>
                                <button type="button" class="act-btn" style="background:#fef2f2;color:#dc2626" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'" onclick="confirmDelete('{{ $post->id }}', '{{ addslashes($post->title) }}')">
                                    <svg style="width:0.72rem;height:0.72rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                                <form id="delete-form-{{ $post->id }}" action="{{ route('admin.blog.destroy', $post) }}" method="POST" style="display:none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:3.5rem;text-align:center;color:#9ca3af;font-size:0.875rem">
                            <div style="font-size:2.5rem;margin-bottom:0.75rem">📝</div>
                            <div style="font-weight:700;color:#374151;margin-bottom:0.25rem">No articles yet</div>
                            <div style="font-size:0.8rem">Click "New Post" above to publish your first article.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="tablePagination" class="px-5 py-3 border-t border-gray-100"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        new TableHelper('#postsTable', '#searchInput', '#tablePagination', 10);
    });

    function confirmDelete(postId, postTitle) {
        Swal.fire({
            title: 'Delete blog post?',
            text: `"${postTitle}" will be permanently deleted.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'rounded-2xl shadow-xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${postId}`).submit();
            }
        });
    }
</script>
@endsection
