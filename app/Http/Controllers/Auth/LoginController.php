<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\{AuthService, AuditService};
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(private AuthService $authService) {}

    // ─── Show Login Form ──────────────────────────────────────────────────────
    public function showLoginForm(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route(auth()->user()->getDashboardRoute());
        }

        $captcha = $this->generateCaptcha();
        return view('auth.login', compact('captcha'));
    }

    // ─── Handle Login ─────────────────────────────────────────────────────────
    public function login(LoginRequest $request): RedirectResponse
    {
        // Attempt login via AuthService (handles all security checks)
        $user = $this->authService->attemptLogin($request);

        // ── Email not verified? → redirect to verify email page
        if (!$user->hasVerifiedEmail()) {
            AuditService::log('login_unverified', 'Login with unverified email');
            return redirect()->route('verification.notice')
                ->with('warning', 'Harap verifikasi email Anda terlebih dahulu.');
        }

        // ── 2FA enabled? → redirect to 2FA challenge
        if ($user->two_factor_enabled) {
            session(['2fa_user_id' => $user->id, '2fa_remember' => $request->boolean('remember')]);
            auth()->logout(); // temporarily logout until 2FA passes
            return redirect()->route('two-factor.challenge');
        }

        // ── Normal login success
        AuditService::logLogin("Login berhasil dari IP: {$request->ip()}");

        return redirect()
            ->intended(route($user->getDashboardRoute()))
            ->with('success', 'Selamat datang kembali, ' . e($user->name) . '!');
    }

    // ─── Generate Math CAPTCHA ─────────────────────────────────────────────────
    public function refreshCaptcha(): \Illuminate\Http\JsonResponse
    {
        $captcha = $this->generateCaptcha();
        return response()->json(['question' => $captcha['question']]);
    }

    private function generateCaptcha(): array
    {
        $a        = random_int(1, 15);
        $b        = random_int(1, 15);
        $answer   = $a + $b;
        session(['captcha_answer' => $answer]);

        return ['question' => "{$a} + {$b} = ?"];
    }
}
