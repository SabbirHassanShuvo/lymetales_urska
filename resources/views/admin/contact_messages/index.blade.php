@extends('layouts.admin')
@section('content')

<style>
    .page-badge { display:inline-block; font-size:0.7rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; padding:0.25rem 0.65rem; border-radius:999px; }
    .status-pill { display:inline-flex; align-items:center; gap:0.35rem; font-size:0.72rem; font-weight:600; padding:0.2rem 0.65rem; border-radius:999px; }
    .status-dot { width:6px; height:6px; border-radius:50%; display:inline-block; }
    .action-link { font-size:0.82rem; font-weight:600; padding:0.3rem 0.75rem; border-radius:0.5rem; text-decoration:none; transition:background 0.15s, color 0.15s; }
    .tbl-th { padding:0.85rem 1.25rem; font-size:0.7rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; color:#9ca3af; }
    .tbl-td { padding:1rem 1.25rem; font-size:0.875rem; }
    .tbl-row { border-bottom:1.5px solid #f9fafb; transition:background 0.12s; }
    .tbl-row:hover { background:#fafbff; }
    .tbl-row:last-child { border-bottom:none; }
</style>

{{-- Header --}}
<div class="mb-7 flex items-center justify-between flex-wrap gap-3">
    <div>
        <h2 style="font-size:1.5rem;font-weight:800;color:#111827;margin:0">Contact Messages</h2>
        <p style="font-size:0.85rem;color:#9ca3af;margin-top:0.25rem">Manage inquiries from the contact us page</p>
    </div>
</div>

{{-- Table Card --}}
<div style="background:#fff;border:1.5px solid #f1f2f4;border-radius:1.1rem;overflow:hidden">
    <div style="padding:1rem 1.25rem;border-bottom:1.5px solid #f9fafb;display:flex;align-items:center;justify-content:between;flex-wrap:wrap;gap:0.75rem">
        <span style="font-size:0.875rem;font-weight:700;color:#374151">All Messages</span>
        <div style="display:flex;align-items:center;gap:0.75rem">
            <div style="position:relative">
                <input type="text" id="searchInput" placeholder="Search messages..." style="padding:0.4rem 0.75rem 0.4rem 2rem;border:1.5px solid #e5e7eb;border-radius:0.5rem;font-size:0.8rem;width:12rem;outline:none;background:#fff" onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#e5e7eb'">
                <svg style="position:absolute;left:0.6rem;top:0.55rem;width:0.85rem;height:0.85rem;color:#9ca3af" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
    </div>
    <div style="overflow-x:auto">
        <table id="messagesTable" style="width:100%;border-collapse:collapse;text-align:left">
            <thead style="background:#f9fafb;border-bottom:1.5px solid #f1f2f4">
                <tr>
                    <th class="tbl-th">Date</th>
                    <th class="tbl-th">Name</th>
                    <th class="tbl-th">Email</th>
                    <th class="tbl-th">Order #</th>
                    <th class="tbl-th">Status</th>
                    <th class="tbl-th" style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                <tr class="tbl-row" style="{{ !$message->is_read ? 'background:#fafbff' : '' }}" data-name="{{ strtolower($message->first_name . ' ' . $message->last_name) }}" data-email="{{ strtolower($message->email) }}">
                    <td class="tbl-td" style="color:#6b7280;white-space:nowrap">
                        {{ $message->created_at->format('M d, Y') }}<br>
                        <span style="font-size:0.75rem;color:#c3c8d4">{{ $message->created_at->format('h:i A') }}</span>
                    </td>
                    <td class="tbl-td">
                        <div style="font-weight:600;color:#111827">{{ $message->first_name }} {{ $message->last_name }}</div>
                    </td>
                    <td class="tbl-td" style="color:#6b7280">{{ $message->email }}</td>
                    <td class="tbl-td">
                        @if($message->order_number)
                            <span style="font-family:monospace;font-size:0.8rem;background:#f3f4f6;padding:0.2rem 0.5rem;border-radius:0.4rem;color:#374151">{{ $message->order_number }}</span>
                        @else
                            <span style="color:#d1d5db">—</span>
                        @endif
                    </td>
                    <td class="tbl-td">
                        @if(!$message->is_read)
                            <span class="status-pill" style="background:#eff6ff;color:#1d4ed8">
                                <span class="status-dot" style="background:#3b82f6"></span> Unread
                            </span>
                        @else
                            <span class="status-pill" style="background:#f3f4f6;color:#6b7280">
                                <span class="status-dot" style="background:#9ca3af"></span> Read
                            </span>
                        @endif
                    </td>
                    <td class="tbl-td" style="text-align:right">
                        <div style="display:inline-flex;align-items:center;gap:0.4rem">
                            <a href="{{ route('admin.contact-messages.show', $message) }}" class="action-link" style="background:#eef2ff;color:#4f46e5">View</a>
                            <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this message?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-link" style="background:#fff5f5;color:#ef4444;border:none;cursor:pointer">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:3.5rem;text-align:center">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:0.75rem">
                            <div style="width:3rem;height:3rem;background:#f3f4f6;border-radius:0.75rem;display:flex;align-items:center;justify-content:center;font-size:1.4rem">✉️</div>
                            <p style="color:#9ca3af;font-size:0.9rem;font-weight:500;margin:0">No messages yet</p>
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
        new TableHelper('#messagesTable', '#searchInput', '#tablePagination', 10);
    });
</script>
@endsection