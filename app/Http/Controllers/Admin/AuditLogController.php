<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = AuditLog::with('user')
            ->when($request->action,  fn($q, $a)  => $q->where('action', $a))
            ->when($request->user_id, fn($q, $u)  => $q->where('user_id', $u))
            ->when($request->ip,      fn($q, $ip) => $q->where('ip_address', $ip))
            ->when($request->date,    fn($q, $d)  => $q->whereDate('created_at', $d))
            ->orderByDesc('created_at')
            ->paginate(50)
            ->withQueryString();

        return view('admin.audit.index', compact('logs'));
    }
}
