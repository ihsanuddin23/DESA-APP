@extends('layouts.auth')

@section('title', 'Masuk')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
    <div class="auth-card">

        {{-- Header --}}
        <div class="auth-header">
            <div class="auth-header-glow"></div>
            <div class="logo-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h4>Selamat Datang</h4>
            <p>Sistem Informasi Desa — Akses Aman & Terpercaya</p>
        </div>

        {{-- Body --}}
        <div class="auth-body">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert-desa" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" id="loginForm" autocomplete="off" novalidate>
                @csrf

                {{-- Email --}}
                <div class="form-group-desa">
                    <label for="email" class="form-label-desa">
                        <i class="bi bi-envelope"></i>
                        Alamat Email
                    </label>
                    <div class="input-group-desa">
                        <span class="input-icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="input-desa @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="nama@email.com" required
                            autocomplete="username" autofocus maxlength="255">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block mt-1" style="font-size: 0.8125rem; color: #ef4444;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group-desa">
                    <div class="label-row">
                        <label for="password" class="form-label-desa mb-0">
                            <i class="bi bi-lock"></i>
                            Password
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Lupa password?
                        </a>
                    </div>
                    <div class="input-group-desa">
                        <span class="input-icon"><i class="bi bi-lock"></i></span>
                        <input type="password" class="input-desa @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Masukkan password Anda" required autocomplete="current-password"
                            maxlength="255">
                        <button type="button" class="toggle-password" data-toggle-password="password"
                            title="Tampilkan password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-1" style="font-size: 0.8125rem; color: #ef4444;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- CAPTCHA --}}
                <div class="captcha-section">
                    <label class="form-label-desa">
                        <i class="bi bi-shield-check"></i>
                        Verifikasi Keamanan
                    </label>
                    <div class="captcha-row">
                        <div class="captcha-box" id="captchaQuestion">{{ $captcha['question'] ?? '1 + 1 = ?' }}</div>
                        <button type="button" class="btn-refresh" id="refreshCaptcha" title="Ganti soal">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <div class="captcha-input-wrapper">
                            <input type="text" class="input-desa @error('captcha') is-invalid @enderror" name="captcha"
                                id="captchaInput" placeholder="Jawaban" required autocomplete="off" maxlength="5"
                                inputmode="numeric">
                        </div>
                    </div>
                    @error('captcha')
                        <div class="invalid-feedback d-block mt-1" style="font-size: 0.8125rem; color: #ef4444;">
                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="remember-section">
                    <label class="check-desa">
                        <input type="checkbox" name="remember" id="remember" value="1"
                            {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn-desa-submit" id="submitBtn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk ke Sistem
                </button>

            </form>

            {{-- Divider --}}
            <hr class="desa-divider">

            {{-- Register Link --}}
            <p class="register-text">
                Belum punya akun?
                <a href="{{ route('register') }}" class="register-link">
                    Daftar sekarang <i class="bi bi-arrow-right"></i>
                </a>
            </p>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush
