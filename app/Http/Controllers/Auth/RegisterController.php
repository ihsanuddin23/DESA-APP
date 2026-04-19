<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\{AuthService, AuditService};
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function showRegistrationForm(Request $request): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route(auth()->user()->getDashboardRoute());
        }

        $captcha = $this->generateCaptcha();
        return view('auth.register', compact('captcha'));
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = $this->authService->registerUser($request->validated(), $request);

        AuditService::logRegister($user);

        // Don't log the user in yet — require email verification first
        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi berhasil! Kami telah mengirimkan kode OTP ke email Anda untuk verifikasi.');
    }

    public function refreshCaptcha(): \Illuminate\Http\JsonResponse
    {
        $captcha = $this->generateCaptcha();
        return response()->json(['question' => $captcha['question']]);
    }

    private function generateCaptcha(): array
    {
        $a      = random_int(1, 15);
        $b      = random_int(1, 15);
        session(['captcha_answer' => $a + $b]);
        return ['question' => "{$a} + {$b} = ?"];
    }
}
