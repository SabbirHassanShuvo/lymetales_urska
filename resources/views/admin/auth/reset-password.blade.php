<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .strength-bar { height: 4px; border-radius: 99px; transition: all 0.3s; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/30 to-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 shadow-lg shadow-indigo-200 mb-4">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Set new password</h1>
            <p class="text-sm text-gray-500 mt-1.5">Choose a strong password for your admin account</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

            @if($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-sm font-semibold text-red-700">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('admin.forgot-password.reset') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="passwordInput" required minlength="8"
                            placeholder="Minimum 8 characters"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all text-sm font-medium pr-11 placeholder-gray-400"
                            oninput="checkStrength(this.value)">
                        <button type="button" onclick="togglePass('passwordInput', this)" class="absolute right-3.5 top-3.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5 eye-off" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    {{-- Strength Bar --}}
                    <div class="mt-2 flex gap-1.5">
                        <div class="strength-bar flex-1 bg-gray-200" id="s1"></div>
                        <div class="strength-bar flex-1 bg-gray-200" id="s2"></div>
                        <div class="strength-bar flex-1 bg-gray-200" id="s3"></div>
                        <div class="strength-bar flex-1 bg-gray-200" id="s4"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1" id="strengthLabel"></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="confirmInput" required
                            placeholder="Re-enter password"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 transition-all text-sm font-medium pr-11 placeholder-gray-400">
                        <button type="button" onclick="togglePass('confirmInput', this)" class="absolute right-3.5 top-3.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5 eye-off" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md shadow-indigo-100 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePass(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function checkStrength(val) {
            const bars = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document.getElementById('s4')];
            const label = document.getElementById('strengthLabel');
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
            const labels = ['Weak', 'Fair', 'Good', 'Strong'];

            bars.forEach((b, i) => {
                b.style.background = i < score ? colors[score - 1] : '#e5e7eb';
            });
            label.textContent = val.length > 0 ? (labels[score - 1] || 'Weak') : '';
            label.style.color = val.length > 0 ? (colors[score - 1] || '#ef4444') : '';
        }
    </script>
</body>
</html>
