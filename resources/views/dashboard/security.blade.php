@extends('layouts.app')

@section('title', 'Keamanan Akun')
@section('page-title', 'Keamanan Akun')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/security.css') }}">
@endpush

@section('content')
    <div class="row g-4">

        {{-- ── Change Password ─────────────────────────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="security-card animate-left">
                <div class="security-header">
                    <i class="bi bi-key-fill primary"></i>
                    <h6>Ubah Password</h6>
                </div>
                <div class="security-body">

                    @if ($errors->passwordUpdate->any())
                        <div class="alert-custom danger">
                            @foreach ($errors->passwordUpdate->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.password.update') }}" id="pwdForm"
                        class="password-form">
                        @csrf
                        @method('PUT')

                        {{-- Current Password --}}
                        <div class="form-group">
                            <label>
                                <i class="bi bi-lock"></i>
                                Password Saat Ini
                            </label>
                            <div class="input-group-custom">
                                <span class="input-icon"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" name="current_password" id="currentPwd"
                                    placeholder="Masukkan password saat ini" required maxlength="255"
                                    autocomplete="current-password">
                                <button type="button" class="toggle-btn" data-toggle-password="currentPwd"
                                    aria-label="Tampilkan password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- New Password --}}
                        <div class="form-group">
                            <label>
                                <i class="bi bi-lock-fill"></i>
                                Password Baru
                            </label>
                            <div class="input-group-custom">
                                <span class="input-icon"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" id="newPwd"
                                    placeholder="Minimal 8 karakter, huruf besar, angka & simbol" required minlength="8"
                                    maxlength="255" autocomplete="new-password">
                                <button type="button" class="toggle-btn" data-toggle-password="newPwd"
                                    aria-label="Tampilkan password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            {{-- Strength Meter --}}
                            <div class="strength-meter">
                                <div class="strength-bar-bg">
                                    <div class="strength-bar" id="pwdStrengthBar"></div>
                                </div>
                                <div class="strength-label" id="pwdStrengthLabel"></div>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="form-group">
                            <label>
                                <i class="bi bi-lock-fill"></i>
                                Konfirmasi Password Baru
                            </label>
                            <div class="input-group-custom">
                                <span class="input-icon"><i class="bi bi-check-circle"></i></span>
                                <input type="password" name="password_confirmation" id="confirmPwd"
                                    placeholder="Ulangi password baru" required maxlength="255" autocomplete="new-password">
                            </div>
                            <div class="match-indicator" id="pwdMatchMsg"></div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="bi bi-check-lg"></i>
                            Simpan Password Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Two-Factor Authentication ───────────────────────────────────────── --}}
        <div class="col-12 col-lg-6">
            <div class="security-card animate-right">
                <div class="security-header">
                    <i class="bi bi-shield-lock-fill success"></i>
                    <h6>Autentikasi Dua Faktor (2FA)</h6>
                </div>
                <div class="security-body">

                    @if (auth()->user()->two_factor_enabled)
                        {{-- 2FA Active --}}
                        <div class="status-banner success">
                            <i class="bi bi-shield-check-fill"></i>
                            <div>
                                <strong>2FA Aktif</strong>
                                <p>Akun Anda dilindungi dengan autentikasi dua faktor via email OTP. Setiap login akan
                                    memerlukan kode verifikasi yang dikirim ke email Anda.</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('two-factor.disable') }}" class="twofa-form">
                            @csrf
                            @method('DELETE')

                            <div class="form-group">
                                <label>Masukkan password untuk menonaktifkan 2FA</label>
                                <input type="password" name="password" class="@error('password') is-invalid @enderror"
                                    placeholder="Password Anda" required maxlength="255" autocomplete="current-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn-2fa-disable">
                                <i class="bi bi-shield-x"></i>
                                Nonaktifkan 2FA
                            </button>
                        </form>
                    @else
                        {{-- 2FA Inactive --}}
                        <div class="status-banner warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                <strong>2FA Nonaktif</strong>
                                <p>Aktifkan untuk perlindungan login tambahan. Kami sangat merekomendasikan mengaktifkan 2FA
                                    untuk keamanan akun Anda.</p>
                            </div>
                        </div>

                        <p class="info-text">
                            Dengan mengaktifkan 2FA, setiap kali login Anda akan diminta memasukkan <strong>kode
                                OTP</strong> yang dikirim ke email. Ini mencegah akses tidak sah meskipun password Anda
                            bocor.
                        </p>

                        <form method="POST" action="{{ route('two-factor.enable') }}">
                            @csrf
                            <button type="submit" class="btn-2fa-enable">
                                <i class="bi bi-shield-check"></i>
                                Aktifkan 2FA Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- ── Login Info ────────────────────────────────────────────────────── --}}
            <div class="security-card animate-right delay-1 mt-4">
                <div class="security-header">
                    <i class="bi bi-laptop info"></i>
                    <h6>Informasi Login Terakhir</h6>
                </div>
                <div class="security-body">
                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-label">
                                <i class="bi bi-geo-alt"></i>
                                IP Address
                            </span>
                            <span class="info-value">
                                @if (auth()->user()->last_login_ip)
                                    <code>{{ auth()->user()->last_login_ip }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="bi bi-display"></i>
                                Perangkat
                            </span>
                            <span class="info-value">{{ auth()->user()->last_login_device ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="bi bi-clock"></i>
                                Waktu Login
                            </span>
                            <span class="info-value">
                                @if (auth()->user()->last_login_at)
                                    {{ auth()->user()->last_login_at->format('d M Y, H:i') }}
                                    <small
                                        class="text-muted d-block">{{ auth()->user()->last_login_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="bi bi-bar-chart"></i>
                                Total Login
                            </span>
                            <span class="info-value highlight">{{ number_format(auth()->user()->login_count) }}
                                kali</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/security.js') }}"></script>
@endpush
