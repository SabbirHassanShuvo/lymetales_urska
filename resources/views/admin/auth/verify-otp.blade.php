<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP — Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .otp-input { width: 52px; height: 60px; text-align: center; font-size: 1.5rem; font-weight: 800; border: 2px solid #e5e7eb; border-radius: 12px; background: #f9fafb; outline: none; transition: all 0.15s; caret-color: transparent; }
        .otp-input:focus { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 4px rgba(99,102,241,0.12); }
        .otp-input.filled { border-color: #6366f1; color: #4f46e5; background: #eef2ff; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-indigo-50/30 to-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 shadow-lg shadow-indigo-200 mb-4">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Enter your OTP</h1>
            <p class="text-sm text-gray-500 mt-1.5">
                We sent a 6-digit code to <span class="font-semibold text-indigo-600">{{ $email }}</span>
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

            @if($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <p class="text-sm font-semibold text-red-700">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('admin.forgot-password.verify') }}" method="POST" id="otpForm">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-6">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4 text-center">Enter 6-digit code</p>
                    <div class="flex justify-center gap-2.5" id="otpInputs">
                        @for($i = 0; $i < 6; $i++)
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                class="otp-input" data-index="{{ $i }}" autocomplete="off">
                        @endfor
                    </div>
                    <input type="hidden" name="otp" id="otpHidden">
                </div>

                {{-- Countdown timer --}}
                <div class="flex items-center justify-center gap-2 mb-5">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-semibold" id="countdown">
                        Code expires in <span class="text-indigo-600" id="timerDisplay">10:00</span>
                    </span>
                </div>

                <button type="submit" id="verifyBtn"
                    class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md shadow-indigo-100">
                    Verify OTP & Continue
                </button>
            </form>

            <div class="mt-5 pt-5 border-t border-gray-50 flex items-center justify-between text-sm">
                <a href="{{ route('admin.forgot-password') }}" class="text-gray-500 hover:text-gray-700 font-medium">
                    ← Change email
                </a>
                <form action="{{ route('admin.forgot-password.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="text-indigo-600 font-semibold hover:text-indigo-700">
                        Resend OTP
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // OTP Input navigation
        const inputs = document.querySelectorAll('.otp-input');
        const hidden = document.getElementById('otpHidden');

        function updateHidden() {
            hidden.value = Array.from(inputs).map(i => i.value).join('');
        }

        inputs.forEach((input, idx) => {
            input.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val.slice(-1);
                e.target.classList.toggle('filled', val !== '');
                updateHidden();
                if (val && idx < inputs.length - 1) inputs[idx + 1].focus();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && idx > 0) {
                    inputs[idx - 1].value = '';
                    inputs[idx - 1].classList.remove('filled');
                    inputs[idx - 1].focus();
                    updateHidden();
                }
            });
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                paste.split('').forEach((ch, i) => {
                    if (inputs[i]) {
                        inputs[i].value = ch;
                        inputs[i].classList.add('filled');
                    }
                });
                updateHidden();
                if (inputs[paste.length]) inputs[Math.min(paste.length, 5)].focus();
            });
        });

        // Countdown timer (10 minutes)
        let seconds = 10 * 60;
        const timerDisplay = document.getElementById('timerDisplay');
        const timer = setInterval(() => {
            seconds--;
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            timerDisplay.textContent = `${m}:${s}`;
            if (seconds <= 0) {
                clearInterval(timer);
                timerDisplay.textContent = 'Expired';
                timerDisplay.style.color = '#ef4444';
            }
        }, 1000);
    </script>
</body>
</html>
