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
    .grid-5 { display:grid; grid-template-columns:repeat(5,1fr); gap:0.6rem; }
    @media(max-width:900px){ .grid-3,.grid-5{ grid-template-columns:1fr 1fr; } }
    @media(max-width:600px){ .grid-2,.grid-3,.grid-5{ grid-template-columns:1fr; } }
    .btn-add { font-size:0.75rem; font-weight:600; background:#eef2ff; color:#4f46e5; border:none; padding:0.4rem 0.85rem; border-radius:0.5rem; cursor:pointer; transition:background 0.15s; }
    .btn-add:hover { background:#e0e7ff; }
    .contact-card { border:1.5px solid #f1f2f4; border-radius:0.85rem; padding:1rem; background:#fafbff; position:relative; }
    .preview-card { background:#fff; border:1.5px solid #f1f2f4; border-radius:0.65rem; padding:0.75rem 1rem; display:flex; align-items:center; gap:0.75rem; margin-top:0.75rem; }
    .preview-icon { width:2rem; height:2rem; border-radius:50%; background:#eef2ff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .info-box { background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:0.65rem; padding:0.85rem 1rem; display:flex; align-items:flex-start; gap:0.6rem; }
</style>

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.5rem">
    <div>
        <div style="margin-bottom:0.35rem">
            <span style="background:#dcfce7;color:#16a34a;font-size:0.7rem;font-weight:700;padding:0.25rem 0.65rem;border-radius:999px;text-transform:uppercase;letter-spacing:0.07em">Contact</span>
        </div>
        <h2 style="font-size:1.5rem;font-weight:800;color:#111827;margin:0">Edit Contact Us Page</h2>
        <p style="font-size:0.85rem;color:#9ca3af;margin-top:0.25rem">Manage the left-side contact info and form settings</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.85rem;font-weight:600;color:#6b7280;background:#f3f4f6;padding:0.5rem 1rem;border-radius:0.65rem;text-decoration:none" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
        <svg style="width:1rem;height:1rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Pages
    </a>
</div>

<form action="{{ route('admin.pages.update', $page) }}" method="POST" x-data="{ contacts: {{ json_encode($content['contact_info'] ?? []) }} }">
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
        <span style="font-size:0.85rem;color:#4b5563">Page is active (visible on frontend)</span>
    </label>
</div>

{{-- Page Header --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#22c55e"></span> Page Header</div>
    <div class="grid-3">
        <div><label class="label">Badge</label><input type="text" name="header_badge" value="{{ $content['header']['badge'] ?? '' }}" class="input" placeholder="WE'D LOVE TO HEAR FROM YOU"></div>
        <div><label class="label">Title</label><input type="text" name="header_title" value="{{ $content['header']['title'] ?? '' }}" class="input" placeholder="Contact With Us"></div>
        <div><label class="label">Subtitle</label><input type="text" name="header_subtitle" value="{{ $content['header']['subtitle'] ?? '' }}" class="input"></div>
    </div>
</div>

{{-- Contact Info Cards --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
        <div>
            <div class="card-title" style="margin-bottom:0.15rem"><span class="dot" style="background:#6366f1"></span> Left-Side Contact Info Cards</div>
            <p style="font-size:0.75rem;color:#9ca3af;margin:0 0 0 1rem">Appear as cards on the left side (Email, Live Chat, etc.)</p>
        </div>
        <button type="button" class="btn-add" @click="contacts.push({type:'', label:'', value:'', note:'', icon:''})">+ Add Card</button>
    </div>

    <template x-for="(c, i) in contacts" :key="i">
        <div>
            <input type="hidden" :name="'ci_type['+i+']'" :value="c.type">
            <input type="hidden" :name="'ci_label['+i+']'" :value="c.label">
            <input type="hidden" :name="'ci_value['+i+']'" :value="c.value">
            <input type="hidden" :name="'ci_note['+i+']'" :value="c.note">
            <input type="hidden" :name="'ci_icon['+i+']'" :value="c.icon">
        </div>
    </template>

    <div style="display:flex;flex-direction:column;gap:0.75rem">
        <template x-for="(contact, i) in contacts" :key="i">
            <div class="contact-card">
                <button type="button" @click="contacts.splice(i,1)" style="position:absolute;top:0.65rem;right:0.65rem;background:#fff5f5;color:#f87171;border:none;cursor:pointer;border-radius:0.4rem;padding:0.25rem 0.4rem;transition:background 0.15s" title="Remove">
                    <svg style="width:0.85rem;height:0.85rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="grid-5" style="padding-right:2rem">
                    <div>
                        <label class="label">Type</label>
                        <select x-model="contact.type" class="input">
                            <option value="">Select type</option>
                            <option value="email">Email</option>
                            <option value="chat">Live Chat</option>
                            <option value="production">Production</option>
                            <option value="phone">Phone</option>
                            <option value="address">Address</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Label</label>
                        <input type="text" x-model="contact.label" class="input" placeholder="EMAIL">
                    </div>
                    <div>
                        <label class="label">Value</label>
                        <input type="text" x-model="contact.value" class="input" placeholder="hello@example.com">
                    </div>
                    <div>
                        <label class="label">Note</label>
                        <input type="text" x-model="contact.note" class="input" placeholder="We reply within 24h">
                    </div>
                    <div>
                        <label class="label">Icon</label>
                        <select x-model="contact.icon" class="input">
                            <option value="email">email</option>
                            <option value="chat">chat</option>
                            <option value="clock">clock</option>
                            <option value="phone">phone</option>
                            <option value="location">location</option>
                        </select>
                    </div>
                </div>
                {{-- Live preview --}}
                <div class="preview-card">
                    <div class="preview-icon">
                        <svg style="width:0.9rem;height:0.9rem;color:#6366f1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p style="font-size:0.68rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin:0" x-text="contact.label || 'LABEL'"></p>
                        <p style="font-size:0.85rem;font-weight:600;color:#111827;margin:0.1rem 0 0" x-text="contact.value || 'value'"></p>
                        <p style="font-size:0.75rem;color:#9ca3af;margin:0" x-text="contact.note || 'note'"></p>
                    </div>
                </div>
            </div>
        </template>
        <p x-show="contacts.length === 0" style="text-align:center;color:#9ca3af;font-size:0.85rem;padding:1.5rem 0">No contact info cards yet. Click "Add Card" to start.</p>
    </div>
</div>

{{-- Form Settings --}}
<div class="card">
    <div class="card-title"><span class="dot" style="background:#a855f7"></span> Right-Side Form Settings</div>
    <div class="grid-2" style="margin-bottom:1rem">
        <div><label class="label">Form Title</label><input type="text" name="form_title" value="{{ $content['form']['title'] ?? 'Send us a message' }}" class="input"></div>
        <div><label class="label">Form Subtitle</label><input type="text" name="form_subtitle" value="{{ $content['form']['subtitle'] ?? '' }}" class="input"></div>
        <div><label class="label">Submit Button Text</label><input type="text" name="form_submit_text" value="{{ $content['form']['submit_button_text'] ?? 'Send message' }}" class="input"></div>
        <div><label class="label">Privacy Note</label><input type="text" name="form_privacy_note" value="{{ $content['form']['privacy_note'] ?? '' }}" class="input"></div>
    </div>
    <div class="info-box">
        <svg style="width:1rem;height:1rem;color:#3b82f6;flex-shrink:0;margin-top:0.1rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p style="font-size:0.78rem;color:#1d4ed8;margin:0">
            The contact form fields are handled by the API via <code style="background:#dbeafe;padding:0.1rem 0.35rem;border-radius:0.3rem">POST /api/shop/contact</code>.
            View submissions in the <a href="{{ route('admin.contact-messages.index') }}" style="font-weight:700;text-decoration:underline;color:#1d4ed8">Messages</a> section.
        </p>
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
@endsection