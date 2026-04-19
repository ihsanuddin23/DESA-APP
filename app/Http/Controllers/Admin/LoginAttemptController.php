<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginAttemptController extends Controller
{
    public function index(Request $request): View
    {
        $attempts = LoginAttempt::query()
            ->when($request->email, fn($q, $e)  => $q->where('email', 'like', "%{$e}%"))
            ->when($request->ip,    fn($q, $ip) => $q->where('ip_address', $ip))
            ->when(
                $request->successful !== null,
                fn($q) => $q->where('successful', (bool) $request->successful)
            )
            ->orderByDesc('attempted_at')
            ->paginate(50)
            ->withQueryString();

        return view('admin.login-attempts.index', compact('attempts'));
    }
}
