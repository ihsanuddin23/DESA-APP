<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\{User, OtpCode};
use App\Services\{AuditService, OtpService};
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    // ─── Show 2FA challenge form (OTP input) ─────────────────────────────────
    public function challenge(): View|RedirectResponse
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        $user  = User::find(session('2fa_user_id'));
        if (!$user) {
            return redirect()->route('login');
        }

        // Send OTP for 2FA
        OtpService::sendOtp($user->email, 'two_factor');

        return view('auth.two-factor', [
            'email' => substr($user->email, 0, 3) . '***@' . explode('@', $user->email)[1],
        ]);
    }

    // ─── Verify 2FA OTP ───────────────────────────────────────────────────────
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'code'  => ['required', 'digits:6'],
        ]);

        if (!session('2fa_user_id')) {
            return redirect()->route('login')->withErrors(['code' => 'Sesi habis, silakan login ulang.']);
        }

        $user = User::find(session('2fa_user_id'));
        if (!$user) {
            return redirect()->route('login');
        }

        $otp = OtpService::verify($request->token, $request->code, 'two_factor');

        if (!$otp) {
            return back()->withErrors(['code' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        // Clean 2FA session
        session()->forget(['2fa_user_id', '2fa_remember']);

        // Now actually login
        auth()->login($user, session('2fa_remember', false));
        $request->session()->regenerate();

        // Update last login
        $user->update([
            'last_login_at'     => now(),
            'last_login_ip'     => $request->ip(),
            'last_login_device' => 'Via 2FA',
        ]);

        AuditService::logLogin('Login berhasil via 2FA');

        return redirect()->route($user->getDashboardRoute())
            ->with('success', 'Verifikasi 2FA berhasil. Selamat datang, ' . e($user->name) . '!');
    }

    // ─── Resend 2FA OTP ────────────────────────────────────────────────────────
    public function resend(Request $request): RedirectResponse
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('2fa_user_id'));
        if (!$user) {
            return redirect()->route('login');
        }

        if (OtpService::hasRecentOtp($user->email, 'two_factor', 60)) {
            return back()->withErrors(['code' => 'Tunggu 60 detik sebelum meminta kode baru.']);
        }

        OtpService::sendOtp($user->email, 'two_factor');

        return back()->with('success', 'Kode OTP baru telah dikirim.');
    }

    // ─── Enable 2FA (from profile) ────────────────────────────────────────────
    public function enable(Request $request): RedirectResponse
    {
        $user = $request->user();
        $user->update(['two_factor_enabled' => true]);

        AuditService::log2faEnabled($user);

        return back()->with('success', 'Autentikasi dua faktor berhasil diaktifkan.');
    }

    // ─── Disable 2FA (requires password confirmation) ─────────────────────────
    public function disable(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string', 'current_password']]);

        $request->user()->update(['two_factor_enabled' => false]);
        AuditService::log('2fa_disabled', 'Two-factor authentication disabled');

        return back()->with('success', 'Autentikasi dua faktor berhasil dinonaktifkan.');
    }
}
