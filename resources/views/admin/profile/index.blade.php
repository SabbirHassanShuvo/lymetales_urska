@extends('layouts.admin', ['title' => 'My Profile'])
@section('content')

<style>
    .label { display:block; font-size:0.7rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#6b7280; margin-bottom:0.4rem; }
    .input { width:100%; padding:0.6rem 0.9rem; border:1.5px solid #e5e7eb; border-radius:0.65rem; font-size:0.875rem; color:#1f2937; background:#fff; transition:border-color 0.15s, box-shadow 0.15s; outline:none; box-sizing:border-box; }
    .input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
    .card { background:#fff; border:1.5px solid #f1f2f4; border-radius:1.1rem; padding:1.5rem; margin-bottom:1.25rem; }
    .card-title { font-size:0.85rem; font-weight:700; color:#374151; display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem; }
    .dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:0.9rem; }
    @media(max-width:640px){ .grid-2 { grid-template-columns:1fr; } }
    .avatar-ring { width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid #e0e7ff; }
    .avatar-initials { width:90px; height:90px; border-radius:50%; background:linear-gradient(135deg,#4f46e5,#7c3aed); display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:700; color:#fff; }
</style>

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.75rem">
    <div>
        <h2 style="font-size:1.4rem;font-weight:800;color:#111827;margin:0">My Profile</h2>
        <p style="font-size:0.82rem;color:#9ca3af;margin-top:0.2rem">Manage your account details, avatar, and password</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.82rem;font-weight:600;color:#6b7280;background:#f3f4f6;padding:0.5rem 0.9rem;border-radius:0.65rem;text-decoration:none" onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
        <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Dashboard
    </a>
</div>

<form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Avatar + Basic Info --}}
    <div class="card">
        <div class="card-title">
            <span class="dot" style="background:#6366f1"></span>
            Profile Photo & Info
        </div>

        {{-- Avatar Preview --}}
        <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:1.5rem;flex-wrap:wrap">
            <div id="avatarPreviewWrap">
                @if($user->avatar && file_exists(public_path($user->avatar)))
                    <img id="avatarPreviewImg" src="{{ asset($user->avatar) }}" class="avatar-ring" alt="Profile Photo">
                @else
                    <div id="avatarPreviewInitials" class="avatar-initials">
                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                    </div>
                    <img id="avatarPreviewImg" src="" class="avatar-ring" alt="Profile Photo" style="display:none">
                @endif
            </div>
            <div>
                <div style="font-size:1rem;font-weight:700;color:#111827">{{ $user->first_name }} {{ $user->last_name }}</div>
                <div style="font-size:0.8rem;color:#9ca3af;margin-top:0.15rem">{{ $user->email }}</div>
                <div style="margin-top:0.75rem">
                    <label for="avatar" style="display:inline-flex;align-items:center;gap:0.4rem;background:#eef2ff;color:#4f46e5;font-size:0.78rem;font-weight:700;padding:0.42rem 0.85rem;border-radius:0.55rem;cursor:pointer;transition:background 0.15s" onmouseover="this.style.background='#e0e7ff'" onmouseout="this.style.background='#eef2ff'">
                        <svg style="width:0.82rem;height:0.82rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Upload New Photo
                    </label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" style="display:none" onchange="previewAvatar(this)">
                    <p style="font-size:0.7rem;color:#9ca3af;margin-top:0.4rem">PNG, JPG, GIF or WebP — max 2MB</p>
                </div>
            </div>
        </div>

        {{-- Name + Phone --}}
        <div class="grid-2" style="margin-bottom:0.9rem">
            <div>
                <label class="label">First Name</label>
                <input type="text" name="first_name" class="input" value="{{ old('first_name', $user->first_name) }}" required>
            </div>
            <div>
                <label class="label">Last Name</label>
                <input type="text" name="last_name" class="input" value="{{ old('last_name', $user->last_name) }}" required>
            </div>
        </div>
        <div class="grid-2">
            <div>
                <label class="label">Email Address</label>
                <input type="email" name="email" class="input" value="{{ old('email', $user->email) }}" required>
            </div>
            <div>
                <label class="label">Phone Number</label>
                <input type="tel" name="phone_number" class="input" value="{{ old('phone_number', $user->phone_number) }}" placeholder="+1 234 567 890">
            </div>
        </div>
    </div>

    {{-- Password Change --}}
    <div class="card">
        <div class="card-title">
            <span class="dot" style="background:#f59e0b"></span>
            Change Password
            <span style="font-size:0.68rem;color:#9ca3af;background:#f9fafb;border:1px solid #f3f4f6;padding:0.1rem 0.5rem;border-radius:999px;font-weight:500;margin-left:0.25rem">Leave blank to keep current password</span>
        </div>
        <div class="grid-2">
            <div>
                <label class="label">New Password</label>
                <input type="password" name="password" class="input" placeholder="••••••••" autocomplete="new-password">
            </div>
            <div>
                <label class="label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="input" placeholder="••••••••" autocomplete="new-password">
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div style="display:flex;justify-content:flex-end;gap:0.75rem;padding-bottom:1.5rem">
        <button type="submit" style="background:#4f46e5;color:#fff;padding:0.65rem 1.75rem;border-radius:0.75rem;font-weight:700;font-size:0.875rem;border:none;cursor:pointer;transition:background 0.15s;display:inline-flex;align-items:center;gap:0.45rem" onmouseover="this.style.background='#4338ca'" onmouseout="this.style.background='#4f46e5'">
            <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Changes
        </button>
    </div>
</form>

<script>
    function previewAvatar(input) {
        const img = document.getElementById('avatarPreviewImg');
        const initials = document.getElementById('avatarPreviewInitials');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.style.display = 'block';
                if (initials) initials.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
