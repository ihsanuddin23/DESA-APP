{{-- ============================================================ --}}
{{-- FILE: resources/views/dashboard/warga.blade.php            --}}
{{-- ============================================================ --}}
@extends('layouts.app')

@section('title', 'Dashboard Warga')
@section('page-title', 'Dashboard Warga')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-warga.css') }}">
@endpush

@section('content')

    {{-- Welcome Section --}}
    <div class="row g-3 mb-4 animate-in">
        <div class="col-12">
            <div class="card welcome-card shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="welcome-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="welcome-text flex-grow-1">
                            <h5 class="mb-2 text-white">Selamat datang, {{ e(auth()->user()->name) }}!</h5>
                            <div class="welcome-meta text-white">
                                <span>
                                    <i class="bi bi-clock"></i>
                                    {{ auth()->user()->last_login_at?->diffForHumans() ?? 'Pertama kali login' }}
                                </span>
                                @if (auth()->user()->last_login_ip)
                                    <span>
                                        <i class="bi bi-geo-alt"></i>
                                        IP: {{ auth()->user()->last_login_ip }}
                                    </span>
                                @endif
                                <span>
                                    <i class="bi bi-calendar3"></i>
                                    {{ now()->format('l, d F Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="bi bi-person-check" style="font-size: 3rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Security Status Grid --}}
    <div class="status-grid mb-4">

        {{-- Email Verification --}}
        <div class="status-card {{ auth()->user()->hasVerifiedEmail() ? 'verified' : 'unverified' }} animate-in delay-1">
            <div class="d-flex align-items-center gap-3">
                <div class="status-icon {{ auth()->user()->hasVerifiedEmail() ? 'success' : 'warning' }}">
                    <i
                        class="bi bi-{{ auth()->user()->hasVerifiedEmail() ? 'envelope-check' : 'envelope-exclamation' }}-fill"></i>
                </div>
                <div class="status-content">
                    <h6>Status Email</h6>
                    @if (auth()->user()->hasVerifiedEmail())
                        <p class="text-success">
                            <i class="bi bi-check-circle-fill me-1"></i>Terverifikasi
                        </p>
                    @else
                        <a href="{{ route('verification.notice') }}" class="text-warning">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>
                            Belum diverifikasi
                            <i class="bi bi-arrow-right-short"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- 2FA Status --}}
        <div class="status-card {{ auth()->user()->two_factor_enabled ? 'secure' : 'insecure' }} animate-in delay-2">
            <div class="d-flex align-items-center gap-3">
                <div class="status-icon {{ auth()->user()->two_factor_enabled ? 'success' : 'danger' }}">
                    <i class="bi bi-shield-{{ auth()->user()->two_factor_enabled ? 'check' : 'lock' }}-fill"></i>
                </div>
                <div class="status-content">
                    <h6>Keamanan 2FA</h6>
                    @if (auth()->user()->two_factor_enabled)
                        <p class="text-success">
                            <i class="bi bi-check-circle-fill me-1"></i>Autentikasi aktif
                        </p>
                    @else
                        <a href="{{ route('profile.security') }}" class="text-danger">
                            <i class="bi bi-shield-exclamation me-1"></i>
                            Nonaktif — Aktifkan sekarang
                            <i class="bi bi-arrow-right-short"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Login Stats --}}
        <div class="status-card neutral animate-in delay-3">
            <div class="d-flex align-items-center gap-3">
                <div class="status-icon info">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>
                <div class="status-content">
                    <h6>Total Login</h6>
                    <p>
                        <strong>{{ number_format(auth()->user()->login_count) }}</strong> kali
                        <span class="text-muted" style="font-size: 0.75rem;">(sejak bergabung)</span>
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Activity Log --}}
    <div class="card activity-card animate-in delay-3">
        <div class="activity-header">
            <h6>
                <i class="bi bi-clock-history"></i>
                Riwayat Aktivitas Terbaru
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th style="padding-left: 1.5rem;">Aktivitas</th>
                            <th>Detail</th>
                            <th>Alamat IP</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activityLog as $log)
                            <tr>
                                <td style="padding-left: 1.5rem;">
                                    <span class="activity-badge">
                                        <i
                                            class="bi bi-{{ match ($log->action) {
                                                'login' => 'box-arrow-in-right',
                                                'logout' => 'box-arrow-right',
                                                'profile_update' => 'person-gear',
                                                'password_change' => 'key',
                                                default => 'circle-fill',
                                            } }}"></i>
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="activity-desc" title="{{ $log->description ?? '-' }}">
                                        {{ $log->description ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <code class="activity-ip">{{ $log->ip_address ?? '-' }}</code>
                                </td>
                                <td class="activity-time">
                                    {{ $log->created_at->format('d M Y, H:i') }}
                                    <br>
                                    <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>Belum ada aktivitas tercatat</p>
                                        <small class="text-muted">Aktivitas login dan perubahan profil akan muncul di
                                            sini</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($activityLog->count() > 0)
            <div class="card-footer bg-white border-top-0 text-center py-3">
                <small class="text-muted">
                    Menampilkan {{ $activityLog->count() }} aktivitas terakhir
                </small>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard-warga.js') }}"></script>
@endpush
