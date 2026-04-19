<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedIp
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only check on auth routes for performance
        if (BlockedIp::isBlocked($request->ip())) {
            abort(429, 'Akses dari IP Anda diblokir sementara karena aktivitas mencurigakan.');
        }

        return $next($request);
    }
}
