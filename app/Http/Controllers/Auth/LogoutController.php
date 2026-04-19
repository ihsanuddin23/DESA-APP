<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\{AuthService, AuditService};
use Illuminate\Http\{Request, RedirectResponse};

class LogoutController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function logout(Request $request): RedirectResponse
    {
        // Log before logout (while user is still in session)
        AuditService::logLogout();

        // Full session destruction via AuthService
        $this->authService->logout($request);

        return redirect()->route('login')
            ->with('success', 'Anda berhasil keluar. Sampai jumpa!');
    }
}
