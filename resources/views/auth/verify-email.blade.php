{{-- ============================================================ --}}
{{-- FILE: resources/views/auth/verify-email.blade.php          --}}
{{-- ============================================================ --}}
@extends('layouts.auth')
@section('title', 'Verifikasi Email')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div class="logo-icon"><i class="bi bi-envelope-check"></i></div>
        <h4>Verifikasi Email Anda</h4>
        <p>Periksa kotak masuk email Anda</p>
    </div>
    <div class="auth-body text-center">

        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
            </div>
        @endif

        <div class="mb-4">
            <div style="font-size:4rem; color: var(--sid-primary); line-height:1;">
                <i class="bi bi-envelope-open"></i>
            </div>
            <p class="text-muted mt-3 mb-0" style="font-size:0.95rem;">
                Kami mengirimkan <strong>kode OTP 6 digit</strong> ke email:
            </p>
            <p class="fw-bold text-primary">{{ $email ?? 'email Anda' }}</p>
            <p class="text-muted" style="font-size:0.875rem;">
                Klik link atau masukkan kode OTP dari email untuk mengaktifkan akun Anda.
                Kode berlaku selama <strong>10 menit</strong>.
            </p>
        </div>

        <a href="{{ route('verification.otp') }}?email={{ urlencode($email ?? '') }}"
           class="btn btn-primary w-100 mb-3">
            <i class="bi bi-123 me-2"></i>Masukkan Kode OTP
        </a>

        <hr class="my-3">

        {{-- Resend OTP --}}
        <p class="text-muted mb-2" style="font-size:0.875rem">Tidak menerima email?</p>
        <form method="POST" action="{{ route('verification.resend') }}" id="resendForm">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            @error('email') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
            <button type="submit" class="btn btn-outline-primary btn-sm" id="resendBtn">
                <i class="bi bi-send me-1"></i>Kirim Ulang Kode OTP
            </button>
        </form>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="text-secondary text-decoration-none" style="font-size:0.875rem">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
            </a>
        </div>
    </div>
</div>
@endsection
