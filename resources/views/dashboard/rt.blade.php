@extends('layouts.app')
@section('title', 'Dashboard RT')
@section('page-title', 'Dashboard RT')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body, .card, .btn, th, td, label, p, h1,h2,h3,h4,h5,h6, input, select {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .rt-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #1a56db 100%);
        border-radius: 1rem;
        padding: 2rem 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .rt-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,.04);
        border-radius: 50%;
    }
    .rt-hero::after {
        content: '';
        position: absolute;
        bottom: -40px; right: 80px;
        width: 140px; height: 140px;
        background: rgba(26,86,219,.3);
        border-radius: 50%;
    }
    .stat-pill {
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: .6rem;
        padding: .75rem 1.25rem;
        backdrop-filter: blur(4px);
    }
    .warga-table th {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #94a3b8;
        border-bottom: 1px solid #f1f5f9;
        padding: .85rem 1rem;
        background: #f8fafc;
    }
    .warga-table td {
        padding: .85rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f8fafc;
        font-size: .875rem;
        color: #334155;
    }
    .warga-table tr:hover td { background: #f8faff; }
    .avatar-sm {
        width: 34px; height: 34px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .8rem; flex-shrink: 0;
    }
    .search-bar {
        border: 1.5px solid #e2e8f0;
        border-radius: .6rem;
        padding: .55rem 1rem .55rem 2.5rem;
        font-size: .875rem;
        transition: border-color .2s;
        width: 260px;
    }
    .search-bar:focus { border-color: #1a56db; box-shadow: 0 0 0 3px rgba(26,86,219,.1); outline: none; }
    .search-wrap { position: relative; }
    .search-wrap i { position: absolute; left: .8rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: .9rem; }
    .badge-role {
        font-size: .7rem; font-weight: 600; padding: .3rem .65rem;
        border-radius: 2rem; letter-spacing: .02em;
    }
    .filter-select {
        border: 1.5px solid #e2e8f0; border-radius: .6rem;
        padding: .5rem .9rem; font-size: .875rem; color: #334155;
        background: #fff; cursor: pointer;
    }
    .filter-select:focus { border-color: #1a56db; outline: none; }
</style>
@endpush

@section('content')

{{-- Hero Banner --}}
<div class="rt-hero mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <div style="color:rgba(255,255,255,.6);font-size:.8rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;margin-bottom:.35rem;">
                Selamat datang
            </div>
            <h4 class="fw-800 mb-1" style="color:#fff;letter-spacing:-.5px;">{{ e(auth()->user()->name) }}</h4>
            <div style="color:rgba(255,255,255,.65);font-size:.875rem;">
                <i class="bi bi-geo-alt me-1"></i>Ketua RT — Sistem Informasi Desa
            </div>
        </div>
        <div class="d-flex gap-3 flex-wrap">
            <div class="stat-pill text-center">
                <div style="color:#fff;font-size:1.5rem;font-weight:800;line-height:1;">{{ $wargaList->total() }}</div>
                <div style="color:rgba(255,255,255,.6);font-size:.75rem;margin-top:.2rem;">Total Warga</div>
            </div>
            <div class="stat-pill text-center">
                <div style="color:#4ade80;font-size:1.5rem;font-weight:800;line-height:1;">
                    {{ $wargaList->where('is_active', true)->count() }}
                </div>
                <div style="color:rgba(255,255,255,.6);font-size:.75rem;margin-top:.2rem;">Aktif</div>
            </div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="card border-0 shadow-sm" style="border-radius:1rem;overflow:hidden;">
    {{-- Toolbar --}}
    <div class="card-header bg-white py-3 px-4 border-bottom" style="border-color:#f1f5f9!important;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h6 class="fw-700 mb-0" style="color:#0f172a;">Daftar Warga</h6>
                <div class="text-muted" style="font-size:.78rem;">Total {{ $wargaList->total() }} warga terdaftar</div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" class="d-flex gap-2 align-items-center">
                    <div class="search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="search-bar"
                            placeholder="Cari nama atau email..." value="{{ request('search') }}">
                    </div>
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm" style="border-radius:.5rem;">
                        <i class="bi bi-funnel"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table warga-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Warga</th>
                    <th>NIK</th>
                    <th>No. HP</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th>Login Terakhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wargaList as $warga)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $colors = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
                                $color  = $colors[crc32($warga->name) % count($colors)];
                            @endphp
                            <div class="avatar-sm" style="background:{{ $color }}1a;color:{{ $color }};">
                                {{ strtoupper(substr($warga->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-600" style="color:#0f172a;">{{ e($warga->name) }}</div>
                                <div style="color:#94a3b8;font-size:.78rem;">{{ $warga->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-size:.8rem;color:#475569;">
                            {{ $warga->nik ?? '—' }}
                        </span>
                    </td>
                    <td style="color:#475569;">{{ $warga->phone ?? '—' }}</td>
                    <td>
                        @if($warga->is_active)
                            <span class="badge-role" style="background:#dcfce7;color:#16a34a;">
                                <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>Aktif
                            </span>
                        @else
                            <span class="badge-role" style="background:#fee2e2;color:#dc2626;">
                                <i class="bi bi-circle-fill me-1" style="font-size:.45rem;vertical-align:middle;"></i>Nonaktif
                            </span>
                        @endif
                    </td>
                    <td style="color:#64748b;font-size:.8rem;">{{ $warga->created_at->format('d M Y') }}</td>
                    <td style="color:#64748b;font-size:.8rem;">
                        {{ $warga->last_login_at?->diffForHumans() ?? 'Belum pernah' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div style="color:#cbd5e1;font-size:3rem;"><i class="bi bi-people"></i></div>
                        <div class="fw-600 mt-2" style="color:#94a3b8;">Tidak ada data warga ditemukan</div>
                        <div style="color:#cbd5e1;font-size:.8rem;">Coba ubah filter pencarian</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($wargaList->hasPages())
    <div class="card-footer bg-white border-top px-4 py-3" style="border-color:#f1f5f9!important;">
        <div class="d-flex align-items-center justify-content-between">
            <div style="font-size:.8rem;color:#94a3b8;">
                Menampilkan {{ $wargaList->firstItem() }}–{{ $wargaList->lastItem() }}
                dari {{ $wargaList->total() }} warga
            </div>
            {{ $wargaList->links() }}
        </div>
    </div>
    @endif
</div>

@endsection
