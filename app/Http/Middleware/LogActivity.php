<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    // Routes that don't need logging (too noisy)
    protected array $except = [
        'captcha/refresh',
        'heartbeat',
        '_debugbar*',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log authenticated requests with state-changing methods
        if (
            auth()->check() &&
            in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']) &&
            !$this->shouldSkip($request)
        ) {
            AuditService::log(
                action: strtolower($request->method()) . '_request',
                description: "Accessed: {$request->path()}",
                request: $request
            );
        }

        return $response;
    }

    private function shouldSkip(Request $request): bool
    {
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) return true;
        }
        return false;
    }
}
