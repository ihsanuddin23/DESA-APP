@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <div class="row g-4">

        {{-- ── Info Profil Sidebar ─────────────────────────────────────────────── --}}
        <div class="col-12 col-lg-4">
            <div class="profile-card animate-left">
                <div class="card-body text-center p-4">

                    {{-- Avatar --}}
                    <div class="profile-avatar-wrapper mb-3">
                        <div class="profile-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>

                    <h5 class="profile-name">{{ e($user->name) }}</h5>
                    <span class="profile-role">
                        <i class="bi bi-person-badge"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                    <div class="profile-email">
                        <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                    </div>

                    <div class="profile-divider"></div>

                    {{-- Stats --}}
                    <div class="profile-stats">
                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-power"></i>Status Akun
                            </span>
                            @if ($user->is_active)
                                <span class="stat-badge success">Aktif</span>
                            @else
                                <span class="stat-badge danger">Nonaktif</span>
                            @endif
                        </div>

                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-envelope-check"></i>Verifikasi Email
                            </span>
                            @if ($user->hasVerifiedEmail())
                                <span class="stat-badge success">Terverifikasi</span>
                            @else
                                <span class="stat-badge warning">Belum</span>
                            @endif
                        </div>

                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-shield-check"></i>2FA
                            </span>
                            @if ($user->two_factor_enabled)
                                <span class="stat-badge success">Aktif</span>
                            @else
                                <span class="stat-badge danger">Nonaktif</span>
                            @endif
                        </div>

                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-calendar-plus"></i>Bergabung
                            </span>
                            <span class="stat-value">{{ $user->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="stat-row">
                            <span class="stat-label">
                                <i class="bi bi-box-arrow-in-right"></i>Total Login
                            </span>
                            <span class="stat-value text-primary">{{ number_format($user->login_count) }} kali</span>
                        </div>
                    </div>

                    <div class="profile-divider"></div>

                    <a href="{{ route('profile.security') }}" class="btn-security">
                        <i class="bi bi-shield-lock"></i>
                        Pengaturan Keamanan
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Edit Profil Form ────────────────────────────────────────────────── --}}
        <div class="col-12 col-lg-8">
            <div class="form-card animate-right">
                <div class="form-card-header">
                    <h6>
                        <i class="bi bi-pencil-square"></i>
                        Edit Profil
                    </h6>
                </div>

                <div class="form-card-body">

                    @if (session('success'))
                        <div class="alert-custom success mb-3">
                            <i class="bi bi-check-circle-fill"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert-custom danger mb-3">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-person"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name', $user->name) }}" required minlength="3" maxlength="100"
                                placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-envelope"></i>
                                Email
                                <span class="hint">(tidak dapat diubah)</span>
                            </label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                        </div>

                        {{-- NIK --}}
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-credit-card"></i>
                                NIK
                                @if ($user->nik)
                                    <span class="hint">(tidak dapat diubah)</span>
                                @endif
                            </label>
                            <input type="text" class="form-control {{ $user->nik ? '' : 'is-invalid' }}"
                                value="{{ $user->nik ?? 'Belum diisi' }}" {{ $user->nik ? 'readonly' : '' }}>
                            @if (!$user->nik)
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    NIK hanya bisa diisi sekali dan tidak dapat diubah setelahnya.
                                </div>
                            @endif
                        </div>

                        {{-- No HP --}}
                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-telephone"></i>
                                Nomor HP
                            </label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone"
                                value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx" maxlength="15">
                            @error('phone')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="btn-group-actions">
                            <button type="submit" class="btn-primary-custom">
                                <i class="bi bi-check-lg"></i>
                                Simpan Perubahan
                            </button>
                            <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="btn-secondary-custom">
                                <i class="bi bi-x-lg"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── Login History ─────────────────────────────────────────────────── --}}
            <div class="history-card animate-up delay-2">
                <div class="history-header">
                    <h6>
                        <i class="bi bi-clock-history"></i>
                        Informasi Login Terakhir
                    </h6>
                </div>
                <div class="history-body">
                    <div class="history-grid">
                        <div class="history-item">
                            <div class="history-label">
                                <i class="bi bi-geo-alt"></i>
                                IP Address
                            </div>
                            <div class="history-value">
                                @if ($user->last_login_ip)
                                    <code>{{ $user->last_login_ip }}</code>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="history-item">
                            <div class="history-label">
                                <i class="bi bi-laptop"></i>
                                Perangkat
                            </div>
                            <div class="history-value">
                                {{ $user->last_login_device ?? '-' }}
                            </div>
                        </div>

                        <div class="history-item">
                            <div class="history-label">
                                <i class="bi bi-clock"></i>
                                Waktu Login
                            </div>
                            <div class="history-value">
                                @if ($user->last_login_at)
                                    {{ $user->last_login_at->format('d M Y, H:i') }}
                                    <br>
                                    <small class="text-muted">{{ $user->last_login_at->diffForHumans() }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="history-item">
                            <div class="history-label">
                                <i class="bi bi-bar-chart"></i>
                                Total Login
                            </div>
                            <div class="history-value highlight">
                                {{ number_format($user->login_count) }} kali
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/profile.js') }}"></script>
@endpush
