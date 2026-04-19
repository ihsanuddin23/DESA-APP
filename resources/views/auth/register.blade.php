@extends('layouts.auth')

@section('title', 'Daftar Akun')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endpush

@section('content')
    <div class="auth-card">

        {{-- Header --}}
        <div class="auth-header">
            <div class="auth-header-glow"></div>
            <div class="logo-icon">
                <i class="bi bi-person-plus"></i>
            </div>
            <h4>Buat Akun Baru</h4>
            <p>Sistem Informasi Desa — Registrasi Warga</p>
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

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register') }}" id="registerForm" autocomplete="off" novalidate>
                @csrf

                {{-- Nama --}}
                <div class="form-group-desa">
                    <label for="name" class="form-label-desa">
                        <i class="bi bi-person"></i>
                        Nama Lengkap
                        <span class="required-mark">*</span>
                    </label>
                    <div class="input-group-desa">
                        <span class="input-icon"><i class="bi bi-person"></i></span>
                        <input type="text" class="input-desa @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" placeholder="Nama lengkap Anda" required
                            minlength="3" maxlength="100" autofocus>
                    </div>
                    @error('name')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group-desa">
                    <label for="email" class="form-label-desa">
                        <i class="bi bi-envelope"></i>
                        Alamat Email
                        <span class="required-mark">*</span>
                    </label>
                    <div class="input-group-desa">
                        <span class="input-icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="input-desa @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="nama@email.com" required
                            maxlength="255">
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- NIK --}}
                <div class="form-group-desa">
                    <label for="nik" class="form-label-desa">
                        <i class="bi bi-credit-card"></i>
                        NIK (Opsional)
                    </label>
                    <div class="input-group-desa">
                        <span class="input-icon"><i class="bi bi-credit-card"></i></span>
                        <input type="text" class="input-desa @error('nik') is-invalid @enderror" id="nik"
                            name="nik" value="{{ old('nik') }}" placeholder="16 digit Nomor Induk Kependudukan"
                            maxlength="16" inputmode="numeric">
                    </div>
                    @error('nik')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- No HP --}}
                <div class="form-group-desa">
                    <label for="phone" class="form-label-desa">
                        <i class="bi bi-telephone"></i>
                        Nomor HP (Opsional)
                    </label>
                    <div class="input-group-desa">
                        <span class="input-icon"><i class="bi bi-telephone"></i></span>
                        <input type="tel" class="input-desa @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" maxlength="15"
                            inputmode="tel">
                    </div>
                    @error('phone')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group-desa">
                    <label for="password" class="form-label-desa">
                        <i class="bi bi-lock"></i>
                        Password
                        <span class="required-mark">*</span>
                    </label>
                    <div class="input-group-desa">
                        <span class="input-icon"><i class="bi bi-lock"></i></span>
                        <input type="password" class="input-desa @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Min. 8 karakter, huruf besar, angka & simbol" required
                            minlength="8" maxlength="255" autocomplete="new-password">
                        <button type="button" class="toggle-password" data-toggle-password="password"
                            title="Tampilkan password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password Strength --}}
                <div class="strength-section">
                    <div class="strength-bar-bg">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="strength-text" id="strengthText"></div>
                </div>

                {{-- Password Confirmation --}}
                <div class="form-group-desa">
                    <label for="password_confirmation" class="form-label-desa">
                        <i class="bi bi-lock-fill"></i>
                        Konfirmasi Password
                        <span class="required-mark">*</span>
                    </label>
                    <div class="input-group-desa">
                        <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="input-desa" id="password_confirmation"
                            name="password_confirmation" placeholder="Ulangi password Anda" required maxlength="255"
                            autocomplete="new-password">
                        <button type="button" class="toggle-password" data-toggle-password="password_confirmation"
                            title="Tampilkan password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="match-feedback" id="matchFeedback"></div>
                </div>

                {{-- CAPTCHA --}}
                <div class="captcha-section">
                    <label class="form-label-desa">
                        <i class="bi bi-shield-check"></i>
                        Verifikasi Keamanan
                        <span class="required-mark">*</span>
                    </label>
                    <div class="captcha-row">
                        <div class="captcha-box" id="captchaQuestion">{{ $captcha['question'] ?? '1 + 1 = ?' }}</div>
                        <button type="button" class="btn-refresh" id="refreshCaptcha" title="Ganti soal">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <div class="captcha-input-wrapper">
                            <input type="text" class="input-desa @error('captcha') is-invalid @enderror"
                                name="captcha" id="captchaInput" placeholder="Jawaban" required autocomplete="off"
                                maxlength="5" inputmode="numeric">
                        </div>
                    </div>
                    @error('captcha')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Terms --}}
                <div class="terms-section">
                    <label class="check-desa">
                        <input type="checkbox" name="terms" id="terms" value="1"
                            {{ old('terms') ? 'checked' : '' }} required>
                        <span>
                            Saya menyetujui
                            <a href="#">syarat & ketentuan</a>
                            dan
                            <a href="#">kebijakan privasi</a>
                        </span>
                    </label>
                    @error('terms')
                        <div class="invalid-feedback d-block mt-1">
                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn-desa-submit" id="submitBtn">
                    <i class="bi bi-person-check"></i>
                    Buat Akun
                </button>

            </form>

            {{-- Divider --}}
            <hr class="desa-divider">

            {{-- Login Link --}}
            <p class="login-text">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="login-link">
                    Masuk di sini <i class="bi bi-arrow-right"></i>
                </a>
            </p>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/register.js') }}"></script>
@endpush
