<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/30 to-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            @php
                $adminLogoPath = \App\Models\Setting::getVal('admin_logo_path', '');
                $adminSiteName = \App\Models\Setting::getVal('admin_site_name', 'LYMETALES');
                $adminTagline  = \App\Models\Setting::getVal('admin_tagline', 'Admin Portal');
            @endphp
            @if($adminLogoPath && file_exists(public_path($adminLogoPath)))
                <div class="flex justify-center mb-4">
                    <img src="{{ asset($adminLogoPath) }}" alt="Admin Logo" style="max-height:52px;object-fit:contain">
                </div>
            @else
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 shadow-lg shadow-indigo-200 mb-4">
                    <span class="text-white text-lg font-black tracking-tight">{{ strtoupper(substr($adminSiteName,0,2)) }}</span>
                </div>
            @endif
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                <span class="text-indigo-600">{{ strtoupper($adminSiteName) }}</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">{{ $adminTagline }}</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

            @if(session('password_reset_success'))
                <div class="mb-5 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-semibold text-green-700">{{ session('password_reset_success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-sm font-semibold text-red-700">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" required autofocus
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all text-sm font-medium placeholder-gray-400"
                        placeholder="admin@example.com">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-semibold text-gray-700">Password</label>
                        <a href="{{ route('admin.forgot-password') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" id="loginPassword" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all text-sm font-medium pr-11 placeholder-gray-400"
                            placeholder="••••••••">
                        <button type="button" onclick="toggleLoginPass()" class="absolute right-3.5 top-3.5 text-gray-400 hover:text-gray-600">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md shadow-indigo-100">
                    Sign In to Dashboard
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            {{ $adminSiteName }} © {{ date('Y') }} · Admin Portal
        </p>
    </div>

    <script>
        function toggleLoginPass() {
            const input = document.getElementById('loginPassword');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
