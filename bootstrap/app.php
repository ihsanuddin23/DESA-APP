<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ── Global web middleware ─────────────────────────────────────────────
        $middleware->web(append: [
            \App\Http\Middleware\CheckActive::class,
        ]);

        // ── Route middleware aliases ──────────────────────────────────────────
        $middleware->alias([
            'role'            => \App\Http\Middleware\CheckRole::class,
            'active'          => \App\Http\Middleware\CheckActive::class,
            'blocked.ip'      => \App\Http\Middleware\CheckBlockedIp::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeout::class,
            'log.activity'    => \App\Http\Middleware\LogActivity::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, Request $request) {
            if ($e->getStatusCode() === 403 && !$request->expectsJson()) {
                return response()->view('errors.403', [
                    'message' => $e->getMessage() ?: 'Anda tidak memiliki akses ke halaman ini.',
                ], 403);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, Request $request) {
            if ($e->getStatusCode() === 429 && !$request->expectsJson()) {
                return response()->view('errors.429', [], 429);
            }
        });

    })->create();