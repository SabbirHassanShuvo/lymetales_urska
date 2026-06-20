<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminPasswordResetOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ─── Login ───────────────────────────────────────────────────────────────

    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended('admin/dashboard');
            }
            Auth::logout();
            return back()->withErrors(['email' => 'Access denied. Only admins can log in here.']);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    // ─── Forgot Password — Step 1: Show Form ─────────────────────────────────

    public function showForgotPassword()
    {
        if (Auth::check()) return redirect()->route('admin.dashboard');
        return view('admin.auth.forgot-password');
    }

    // ─── Forgot Password — Step 2: Send OTP ──────────────────────────────────

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->where('role', 'admin')->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No admin account found with that email address.'])->onlyInput('email');
        }

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(10);

        // Delete any old OTPs for this email and insert new
        DB::table('admin_password_resets')->where('email', $user->email)->delete();
        DB::table('admin_password_resets')->insert([
            'email'      => $user->email,
            'otp'        => $otp,
            'expires_at' => $expiresAt,
            'used'       => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send email
        try {
            Mail::to($user->email)->send(new AdminPasswordResetOtp($otp, $user->first_name));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email. Please check SMTP settings. Error: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.forgot-password.otp-form', ['email' => $user->email])
            ->with('success', 'OTP sent! Please check your email inbox.');
    }

    // ─── Forgot Password — Step 3: Show OTP Verification ────────────────────

    public function showVerifyOtp(Request $request)
    {
        $email = $request->query('email') ?? $request->session()->get('reset_email');
        if (!$email) return redirect()->route('admin.forgot-password');
        return view('admin.auth.verify-otp', compact('email'));
    }

    // ─── Forgot Password — Step 4: Verify OTP ────────────────────────────────

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $record = DB::table('admin_password_resets')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'The OTP is invalid or has expired. Please request a new one.'])
                ->withInput(['email' => $request->email]);
        }

        // Mark as used
        DB::table('admin_password_resets')
            ->where('email', $request->email)
            ->update(['used' => true]);

        // Generate a secure reset token to pass to the reset form
        $token = Str::random(64);
        $request->session()->put('password_reset_token', $token);
        $request->session()->put('password_reset_email', $request->email);

        return redirect()->route('admin.forgot-password.reset-form', [
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    // ─── Forgot Password — Step 5: Show Reset Form ───────────────────────────

    public function showResetPassword(Request $request)
    {
        $email = $request->query('email') ?? $request->session()->get('password_reset_email');
        $token = $request->query('token') ?? $request->session()->get('password_reset_token');

        if (!$email || !$token || $request->session()->get('password_reset_token') !== $token) {
            return redirect()->route('admin.forgot-password')->withErrors(['email' => 'Invalid or expired reset session. Please start over.']);
        }

        return view('admin.auth.reset-password', compact('email', 'token'));
    }

    // ─── Forgot Password — Step 6: Apply New Password ────────────────────────

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'token'                 => 'required|string',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        // Verify session token
        if ($request->session()->get('password_reset_token') !== $request->token) {
            return redirect()->route('admin.forgot-password')->withErrors(['email' => 'Invalid reset session. Please start over.']);
        }

        $user = User::where('email', $request->email)->where('role', 'admin')->first();
        if (!$user) {
            return redirect()->route('admin.forgot-password')->withErrors(['email' => 'Admin account not found.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Clear session
        $request->session()->forget(['password_reset_token', 'password_reset_email']);

        // Clean up any remaining OTP rows
        DB::table('admin_password_resets')->where('email', $request->email)->delete();

        return redirect()->route('admin.login')->with('password_reset_success', 'Password reset successfully! You can now log in with your new password.');
    }
}
