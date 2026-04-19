{{-- ============================================================ --}}
{{-- FILE: resources/views/errors/403.blade.php                 --}}
{{-- ============================================================ --}}
@extends('layouts.auth')
@section('title', 'Akses Ditolak')
@section('content')
<div class="auth-card text-center">
    <div class="auth-header" style="background:linear-gradient(135deg,#dc2626,#b91c1c);">
        <div class="logo-icon" style="font-size:2rem;">🚫</div>
        <h4>403 — Akses Ditolak</h4>
        <p>Anda tidak memiliki izin untuk halaman ini</p>
    </div>
    <div class="auth-body">
        <p class="text-muted">{{ $message ?? 'Anda tidak memiliki akses ke halaman ini.' }}</p>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        @auth
        <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="btn btn-primary">
            <i class="bi bi-house me-1"></i>Dashboard
        </a>
        @else
        <a href="{{ route('login') }}" class="btn btn-primary">
            <i class="bi bi-box-arrow-in-right me-1"></i>Login
        </a>
        @endauth
    </div>
</div>
@endsection


{{-- ============================================================ --}}
{{-- FILE: resources/views/errors/429.blade.php                  --}}
{{-- ============================================================ --}}
{{-- This file is separate in real use — shown here for reference --}}
