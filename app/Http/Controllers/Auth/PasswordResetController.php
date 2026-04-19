<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\{Hash, Password};
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    // ── Show "Forgot Password" form ───────────────────────────────────────────
    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    // ── Send reset link ───────────────────────────────────────────────────────
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email:rfc', 'max:255']]);

        // Laravel's built-in password broker handles token generation + email
        $status = Password::sendResetLink($request->only('email'));

        // Generic response — do NOT confirm if email exists to prevent user enumeration
        if ($status === Password::RESET_LINK_SENT || $status === Password::INVALID_USER) {
            return back()->with('status', 'Jika email Anda terdaftar, kami telah mengirimkan tautan reset password.');
        }

        return back()->withErrors(['email' => 'Tidak dapat mengirim email saat ini. Coba lagi nanti.']);
    }

    // ── Show reset form ────────────────────────────────────────────────────────
    public function showResetForm(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // ── Handle password reset ─────────────────────────────────────────────────
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required', 'string'],
            'email'    => ['required', 'email:rfc', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
        }

        return back()->withErrors(['email' => 'Token tidak valid atau sudah kedaluwarsa.']);
    }
}