<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\{OtpCode, User};
use App\Services\{AuditService, OtpService};
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\View\View;

class VerifyEmailController extends Controller
{
    // ─── Show "Check Your Email" notice ──────────────────────────────────────
    public function notice(Request $request): View|RedirectResponse
    {
        // Already logged-in verified users → dashboard
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->route($request->user()->getDashboardRoute());
        }

        // Get email from session (set during registration)
        $email = session('verification_email') ?? $request->user()?->email;

        return view('auth.verify-email', compact('email'));
    }

    // ─── Show OTP input form ───────────────────────────────────────────────────
    public function showOtpForm(Request $request): View
    {
        $token = $request->query('token');
        return view('auth.verify-otp', compact('token'));
    }

    // ─── Verify OTP ────────────────────────────────────────────────────────────
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string', 'size:64'],
            'code'  => ['required', 'digits:6'],
        ], [
            'token.required' => 'Token tidak valid.',
            'code.required'  => 'Kode OTP wajib diisi.',
            'code.digits'    => 'Kode OTP harus 6 digit angka.',
        ]);

        $otp = OtpService::verify($request->token, $request->code, 'email_verification');

        if (!$otp) {
            return back()->withErrors(['code' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        // Mark email as verified
        $user = User::where('email', $otp->email)->first();

        if (!$user) {
            return back()->withErrors(['code' => 'Akun tidak ditemukan.']);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
            AuditService::logEmailVerified($user);
        }

        // Log the user in after successful verification
        auth()->login($user);
        $request->session()->regenerate();

        return redirect()->route($user->getDashboardRoute())
            ->with('success', 'Email berhasil diverifikasi! Selamat datang, ' . e($user->name) . '!');
    }

    // ─── Resend OTP ─────────────────────────────────────────────────────────────
    public function resend(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $email = strtolower($request->email);
        $user  = User::where('email', $email)->first();

        // Generic response to avoid user enumeration
        if (!$user || $user->hasVerifiedEmail()) {
            return back()->with('info', 'Jika email terdaftar dan belum diverifikasi, kode OTP telah dikirim ulang.');
        }

        // Throttle resend: max 1 per 60 seconds
        if (OtpService::hasRecentOtp($email, 'email_verification', 60)) {
            return back()->withErrors(['email' => 'Tunggu 60 detik sebelum meminta kode baru.']);
        }

        OtpService::sendOtp($email, 'email_verification', $request->ip());

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
