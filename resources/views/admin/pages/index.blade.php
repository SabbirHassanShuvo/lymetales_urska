@extends('layouts.admin')
@section('content')

<style>
    .quick-card {
        background:#fff;
        border:1.5px solid #f1f2f4;
        border-radius:1rem;
        padding:1.1rem;
        text-decoration:none;
        display:block;
        transition:box-shadow 0.18s, border-color 0.18s, transform 0.15s;
        position:relative;
        overflow:hidden;
    }
    .quick-card:hover { box-shadow:0 6px 24px rgba(80,80,180,0.09); border-color:#e0e7ff; transform:translateY(-2px); }
    .quick-card-icon { font-size:1.6rem; margin-bottom:0.6rem; }
    .quick-card-label { font-size:0.85rem; font-weight:700; color:#1f2937; margin-bottom:0.15rem; }
    .quick-card-desc { font-size:0.72rem; color:#9ca3af; }
    .quick-card-footer { display:flex; align-items:center; justify-content:space-between; margin-top:0.85rem; }
    .quick-card-empty { background:#f9fafb; border:1.5px dashed #e5e7eb; border-radius:1rem; padding:1.1rem; }
    .pill { display:inline-flex; align-items:center; gap:0.3rem; font-size:0.7rem; font-weight:600; padding:0.2rem 0.6rem; border-radius:999px; }
    .tbl-th { padding:0.75rem 1.25rem; font-size:0.7rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; color:#9ca3af; }
    .tbl-td { padding:0.9rem 1.25rem; }
    .tbl-row { border-bottom:1.5px solid #f9fafb; transition:background 0.12s; }
    .tbl-row:hover .row-actions { opacity:1; }
    .tbl-row:last-child { border-bottom:none; }
    .row-actions { opacity:0; transition:opacity 0.15s; display:inline-flex; align-items:center; gap:0.4rem; }
    .act-btn { font-size:0.75rem; font-weight:600; padding:0.3rem 0.7rem; border-radius:0.45rem; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; border:none; cursor:pointer; transition:background 0.13s; }
    .page-grid { display:grid; grid-template-columns:repeat(5, 1fr); gap:0.85rem; margin-bottom:1.5rem; }
    @media(max-width:1100px){ .page-grid{ grid-template-columns:repeat(3,1fr); } }
    @media(max-width:700px){ .page-grid{ grid-template-columns:repeat(2,1fr); } }
</style>

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.5rem">
    <div>
        <h2 style="font-size:1.5rem;font-weight:800;color:#111827;margin:0">Content Pages</h2>
        <p style="font-size:0.85rem;color:#9ca3af;margin-top:0.25rem">Manage all frontend pages from one place</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" style="display:inline-flex;align-items:center;gap:0.4rem;background:#4f46e5;color:#fff;font-size:0.85rem;font-weight:700;padding:0.6rem 1.25rem;border-radius:0.75rem;text-decoration:none;transition:background 0.15s" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
        <svg style="width:1rem;height:1rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        Add New Page
    </a>
</div>

{{-- Quick Access Cards --}}
@php
    $knownPages = [
        'our-story'            => ['label'=>'Our Story',        'icon'=>'📖', 'desc'=>'Hero, mission & CTA'],
        'privacy-policy'       => ['label'=>'Privacy Policy',   'icon'=>'🔒', 'desc'=>'Legal policy sections'],
        'terms-and-conditions' => ['label'=>'Terms of Service', 'icon'=>'📋', 'desc'=>'Terms & conditions'],
        'faq'                  => ['label'=>'FAQ',              'icon'=>'❓', 'desc'=>'Categories & Q&A pairs'],
        'contact-us'           => ['label'=>'Contact Us',       'icon'=>'✉️', 'desc'=>'Info cards & form text'],
    ];
@endphp

<div class="page-grid">
    @foreach($knownPages as $slug => $meta)
        @php $pg = $pages->where('slug', $slug)->first() @endphp
        @if($pg)
            <a href="{{ route('admin.pages.edit', $pg) }}" class="quick-card">
                <div class="quick-card-icon">{{ $meta['icon'] }}</div>
                <p class="quick-card-label">{{ $meta['label'] }}</p>
                <p class="quick-card-desc">{{ $meta['desc'] }}</p>
                <div class="quick-card-footer">
                    <span class="pill" style="{{ $pg->is_active ? 'background:#f0fdf4;color:#16a34a' : 'background:#f3f4f6;color:#9ca3af' }}">
                        <span style="width:5px;height:5px;border-radius:50%;background:{{ $pg->is_active ? '#22c55e' : '#9ca3af' }};display:inline-block"></span>
                        {{ $pg->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <svg style="width:0.85rem;height:0.85rem;color:#c3c8d4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        @else
            <div class="quick-card-empty">
                <div style="font-size:1.6rem;opacity:0.25;margin-bottom:0.6rem">{{ $meta['icon'] }}</div>
                <p style="font-size:0.85rem;font-weight:700;color:#d1d5db;margin-bottom:0.15rem">{{ $meta['label'] }}</p>
                <p style="font-size:0.72rem;color:#d1d5db;margin-bottom:0.75rem">Not seeded yet</p>
                <span style="font-size:0.68rem;background:#fef3c7;color:#d97706;font-weight:700;padding:0.2rem 0.55rem;border-radius:999px">Run seeder</span>
            </div>
        @endif
    @endforeach
</div>

{{-- All Pages Table --}}
<div style="background:#fff;border:1.5px solid #f1f2f4;border-radius:1.1rem;overflow:hidden">
    <div style="padding:1rem 1.25rem;border-bottom:1.5px solid #f9fafb;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem">
        <span style="font-size:0.875rem;font-weight:700;color:#374151">All Pages</span>
        <div style="display:flex;align-items:center;gap:0.75rem">
            <div style="position:relative">
                <input type="text" id="searchInput" placeholder="Search pages..." style="padding:0.4rem 0.75rem 0.4rem 2rem;border:1.5px solid #e5e7eb;border-radius:0.5rem;font-size:0.8rem;width:12rem;outline:none;background:#fff" onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#e5e7eb'">
                <svg style="position:absolute;left:0.6rem;top:0.55rem;width:0.85rem;height:0.85rem;color:#9ca3af" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <span style="font-size:0.78rem;color:#9ca3af">{{ $pages->count() }} total</span>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table id="pagesTable" style="width:100%;border-collapse:collapse;text-align:left">
            <thead style="background:#f9fafb;border-bottom:1.5px solid #f1f2f4">
                <tr>
                    <th class="tbl-th">Page</th>
                    <th class="tbl-th">API Endpoint</th>
                    <th class="tbl-th">Status</th>
                    <th class="tbl-th">Last Updated</th>
                    <th class="tbl-th" style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr class="tbl-row">
                    <td class="tbl-td">
                        <div style="display:flex;align-items:center;gap:0.75rem">
                            <div style="width:2.1rem;height:2.1rem;border-radius:0.55rem;background:#eef2ff;display:flex;align-items:center;justify-content:center;font-size:0.95rem;flex-shrink:0">
                                {{ ['our-story'=>'📖','privacy-policy'=>'🔒','terms-and-conditions'=>'📋','faq'=>'❓','contact-us'=>'✉️'][$page->slug] ?? '📄' }}
                            </div>
                            <div>
                                <p style="font-weight:700;color:#111827;font-size:0.875rem;margin:0">{{ $page->title }}</p>
                                <p style="font-size:0.72rem;color:#9ca3af;margin:0">/{{ $page->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="tbl-td">
                        <code style="font-size:0.75rem;background:#f3f4f6;color:#6b7280;padding:0.2rem 0.5rem;border-radius:0.4rem">/api/shop/pages/{{ $page->slug }}</code>
                    </td>
                    <td class="tbl-td">
                        @if($page->is_active)
                            <span class="pill" style="background:#f0fdf4;color:#16a34a"><span style="width:5px;height:5px;border-radius:50%;background:#22c55e;display:inline-block"></span> Active</span>
                        @else
                            <span class="pill" style="background:#f3f4f6;color:#9ca3af"><span style="width:5px;height:5px;border-radius:50%;background:#9ca3af;display:inline-block"></span> Inactive</span>
                        @endif
                    </td>
                    <td class="tbl-td" style="font-size:0.78rem;color:#9ca3af">{{ $page->updated_at->diffForHumans() }}</td>
                    <td class="tbl-td" style="text-align:right">
                        <div class="row-actions">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="act-btn" style="background:#eef2ff;color:#4f46e5">
                                <svg style="width:0.8rem;height:0.8rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete \'{{ $page->title }}\'?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn" style="background:#fff5f5;color:#ef4444">
                                    <svg style="width:0.8rem;height:0.8rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:4rem;text-align:center">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem">
                            <div style="width:3rem;height:3rem;background:#f3f4f6;border-radius:0.75rem;display:flex;align-items:center;justify-content:center;font-size:1.4rem">📄</div>
                            <p style="font-weight:700;color:#9ca3af;margin:0">No pages found</p>
                            <p style="font-size:0.78rem;color:#c3c8d4;margin:0">Run migrations and seeder to get started</p>
                            <code style="font-size:0.75rem;background:#f3f4f6;color:#6b7280;padding:0.4rem 0.85rem;border-radius:0.5rem;margin-top:0.25rem">php artisan migrate && php artisan db:seed --class=PagesSeeder</code>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="tablePagination" class="px-6 py-4 border-t border-gray-100"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        new TableHelper('#pagesTable', '#searchInput', '#tablePagination', 10);
    });
</script>
@endsection