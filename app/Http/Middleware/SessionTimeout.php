<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Session timeout in minutes — can override per-middleware or via config.
     */
    protected int $timeoutMinutes = 30;

    public function handle(Request $request, Closure $next, int $minutes = null): Response
    {
        $timeout = ($minutes ?? $this->timeoutMinutes) * 60;

        if (Auth::check()) {
            $lastActivity = session('last_activity_at', time());

            if (time() - $lastActivity > $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Sesi habis. Silakan login kembali.'], 401);
                }

                return redirect()->route('login')
                    ->with('warning', 'Sesi Anda telah berakhir karena tidak aktif selama ' . ($timeout / 60) . ' menit.');
            }

            // Refresh last activity timestamp
            session(['last_activity_at' => time()]);
        }

        return $next($request);
    }
}
