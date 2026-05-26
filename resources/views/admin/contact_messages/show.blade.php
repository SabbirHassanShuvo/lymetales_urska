@extends('layouts.admin')
@section('content')

<style>
    .detail-label { font-size:0.72rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; color:#9ca3af; display:block; margin-bottom:0.2rem; }
    .detail-value { font-size:0.925rem; font-weight:600; color:#111827; }
    .card { background:#fff; border:1.5px solid #f1f2f4; border-radius:1.1rem; }
    .section-head { font-size:0.78rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; color:#9ca3af; margin-bottom:1rem; }
</style>

{{-- Header --}}
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <h2 style="font-size:1.5rem;font-weight:800;color:#111827;margin:0">Message Details</h2>
        <p style="font-size:0.85rem;color:#9ca3af;margin-top:0.25rem">From {{ $contactMessage->first_name }} {{ $contactMessage->last_name }}</p>
    </div>
    <a href="{{ route('admin.contact-messages.index') }}" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.85rem;font-weight:600;color:#6b7280;background:#f3f4f6;padding:0.5rem 1rem;border-radius:0.65rem;text-decoration:none;transition:background 0.15s" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
        <svg style="width:1rem;height:1rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Messages
    </a>
</div>

<div class="card" style="overflow:hidden">

    {{-- Sender & Context --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;border-bottom:1.5px solid #f9fafb">
        <div style="padding:1.5rem 1.75rem;border-right:1.5px solid #f9fafb">
            <p class="section-head">Sender Details</p>
            <div style="display:flex;flex-direction:column;gap:1rem">
                <div>
                    <span class="detail-label">Name</span>
                    <span class="detail-value">{{ $contactMessage->first_name }} {{ $contactMessage->last_name }}</span>
                </div>
                <div>
                    <span class="detail-label">Email Address</span>
                    <a href="mailto:{{ $contactMessage->email }}" style="font-size:0.925rem;font-weight:600;color:#4f46e5;text-decoration:none">{{ $contactMessage->email }}</a>
                </div>
            </div>
        </div>
        <div style="padding:1.5rem 1.75rem">
            <p class="section-head">Request Context</p>
            <div style="display:flex;flex-direction:column;gap:1rem">
                <div>
                    <span class="detail-label">Order Number</span>
                    @if($contactMessage->order_number)
                        <span style="font-family:monospace;font-size:0.875rem;font-weight:600;background:#f3f4f6;padding:0.2rem 0.6rem;border-radius:0.4rem;color:#374151">{{ $contactMessage->order_number }}</span>
                    @else
                        <span class="detail-value" style="color:#d1d5db">Not provided</span>
                    @endif
                </div>
                <div>
                    <span class="detail-label">Submitted At</span>
                    <span class="detail-value">{{ $contactMessage->created_at->format('M j, Y \a\t g:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Message Body --}}
    <div style="padding:1.5rem 1.75rem">
        <p class="section-head">Message</p>
        <div style="background:#f9fafb;border:1.5px solid #f1f2f4;border-radius:0.75rem;padding:1.25rem 1.5rem;font-size:0.9rem;color:#374151;white-space:pre-wrap;line-height:1.75;min-height:6rem">{{ $contactMessage->message }}</div>
    </div>

    {{-- Footer Actions --}}
    <div style="padding:1rem 1.75rem;border-top:1.5px solid #f9fafb;display:flex;justify-content:flex-end">
        <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" onsubmit="return confirm('Delete this message? This cannot be undone.');">
            @csrf @method('DELETE')
            <button type="submit" style="background:#fff5f5;color:#dc2626;border:1.5px solid #fecaca;padding:0.55rem 1.5rem;border-radius:0.65rem;font-weight:600;font-size:0.85rem;cursor:pointer;transition:background 0.15s" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fff5f5'">
                Delete Message
            </button>
        </form>
    </div>
</div>

@endsection