@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')

@push('styles')
@include('admin._admin-styles')
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h5>Manajemen Pengguna</h5>
        <div class="sub">Kelola seluruh akun pengguna sistem</div>
    </div>
    <a href="{{ route('register') }}" class="btn-primary-sm">
        <i class="bi bi-person-plus"></i> Tambah Pengguna
    </a>
</div>

{{-- Filter Bar --}}
<form method="GET" class="filter-bar">
    <div class="search-wrap">
        <i class="bi bi-search si"></i>
        <input type="text" name="search" class="filter-input"
            placeholder="Cari nama, email..." value="{{ request('search') }}">
    </div>
    <select name="role" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Role</option>
        <option value="admin"  {{ request('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
        <option value="rt"     {{ request('role') === 'rt'     ? 'selected' : '' }}>RT</option>
        <option value="warga"  {{ request('role') === 'warga'  ? 'selected' : '' }}>Warga</option>
    </select>
    <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
    </select>
    <button type="submit" class="btn-primary-sm">
        <i class="bi bi-funnel"></i> Filter
    </button>
    @if(request()->hasAny(['search','role','status']))
        <a href="{{ route('admin.users.index') }}" style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
            <i class="bi bi-x-circle me-1"></i>Reset
        </a>
    @endif
</form>

{{-- Table --}}
<div class="data-card">
    <div class="data-card-header">
        <h6><i class="bi bi-people-fill me-2" style="color:#1a56db;"></i>Daftar Pengguna</h6>
        <span style="font-size:.78rem;color:#94a3b8;">{{ $users->total() }} pengguna ditemukan</span>
    </div>
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Pengguna</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Email Verified</th>
                    <th>2FA</th>
                    <th>Login Terakhir</th>
                    <th class="pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $colors = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444','#8b5cf6'];
                    $color  = $colors[crc32($user->name) % count($colors)];
                @endphp
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="av" style="background:{{ $color }}18;color:{{ $color }};">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;color:#0f172a;">{{ e($user->name) }}</div>
                                <div style="color:#94a3b8;font-size:.75rem;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php $roleColors = ['admin'=>['#fde68a','#92400e'],'rt'=>['#dbeafe','#1e40af'],'warga'=>['#dcfce7','#15803d']]; $rc = $roleColors[$user->role] ?? ['#f1f5f9','#64748b']; @endphp
                        <span class="status-badge" style="background:{{ $rc[0] }};color:{{ $rc[1] }};">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="status-badge badge-success"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i>Aktif</span>
                        @else
                            <span class="status-badge badge-danger"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i>Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        @if($user->hasVerifiedEmail())
                            <span class="status-badge badge-success"><i class="bi bi-check-lg"></i>Verified</span>
                        @else
                            <span class="status-badge badge-warning"><i class="bi bi-clock"></i>Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($user->two_factor_enabled)
                            <span class="status-badge badge-info"><i class="bi bi-shield-check"></i>Aktif</span>
                        @else
                            <span class="status-badge badge-gray">Nonaktif</span>
                        @endif
                    </td>
                    <td style="color:#64748b;font-size:.8rem;">
                        {{ $user->last_login_at?->diffForHumans() ?? '—' }}
                    </td>
                    <td class="pe-4">
                        <div class="d-flex align-items-center gap-1">
                            <a href="{{ route('admin.users.show', $user) }}"
                               style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#64748b;text-decoration:none;font-size:.82rem;display:inline-flex;align-items:center;gap:.3rem;"
                               title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    style="padding:.35rem .6rem;border:1.5px solid {{ $user->is_active ? '#fca5a5' : '#86efac' }};border-radius:.45rem;background:{{ $user->is_active ? '#fff5f5' : '#f0fdf4' }};color:{{ $user->is_active ? '#dc2626' : '#16a34a' }};font-size:.82rem;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;"
                                    title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    onclick="return confirm('Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun ini?')">
                                    <i class="bi bi-{{ $user->is_active ? 'lock' : 'unlock' }}"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-people"></i></div>
                            <div class="empty-title">Tidak ada pengguna ditemukan</div>
                            <div class="empty-sub">Coba ubah filter pencarian</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
        <span style="font-size:.78rem;color:#94a3b8;">
            Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }}
        </span>
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
