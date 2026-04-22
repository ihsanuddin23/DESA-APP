@extends('layouts.app')
@section('title', 'Audit Log')
@section('page-title', 'Audit Log')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Audit Log</h5>
            <div class="sub">Rekam jejak seluruh aktivitas sistem</div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="filter-bar">
        <select name="action" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Aksi</option>
            @foreach (['login', 'logout', 'register', 'login_failed', 'profile_update', 'password_change', 'email_verified', '2fa_enabled', '2fa_disabled', 'admin_toggle_active', 'admin_change_role', 'admin_delete_user', 'admin_block_ip', 'admin_unblock_ip'] as $act)
                <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}
                </option>
            @endforeach
        </select>
        <div class="search-wrap">
            <i class="bi bi-geo-alt si"></i>
            <input type="text" name="ip" class="filter-input" placeholder="Filter IP address..."
                value="{{ request('ip') }}" style="min-width:180px;">
        </div>
        <input type="date" name="date" class="filter-select" value="{{ request('date') }}"
            onchange="this.form.submit()">
        <button type="submit" class="btn-primary-sm"><i class="bi bi-funnel"></i> Filter</button>
        @if (request()->hasAny(['action', 'ip', 'date']))
            <a href="{{ route('admin.audit.index') }}" style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </form>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-journal-text me-2" style="color:#1a56db;"></i>Riwayat Aktivitas</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $logs->total() }} entri</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Pengguna</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP Address</th>
                        <th>Route</th>
                        <th class="pe-4">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $actionMap = [
                                'login' => ['badge-success', 'bi-box-arrow-in-right'],
                                'logout' => ['badge-gray', 'bi-box-arrow-right'],
                                'register' => ['badge-info', 'bi-person-plus'],
                                'login_failed' => ['badge-danger', 'bi-x-circle'],
                                'login_unverified' => ['badge-warning', 'bi-envelope-x'],
                                'password_change' => ['badge-warning', 'bi-key'],
                                'profile_update' => ['badge-info', 'bi-pencil'],
                                'email_verified' => ['badge-success', 'bi-envelope-check'],
                                '2fa_enabled' => ['badge-success', 'bi-shield-check'],
                                '2fa_disabled' => ['badge-danger', 'bi-shield-x'],
                                'admin_toggle_active' => ['badge-warning', 'bi-toggle-on'],
                                'admin_change_role' => ['badge-info', 'bi-person-gear'],
                                'admin_delete_user' => ['badge-danger', 'bi-trash'],
                                'admin_block_ip' => ['badge-danger', 'bi-ban'],
                                'admin_unblock_ip' => ['badge-success', 'bi-check-circle'],
                            ];
                            [$badgeCls, $iconCls] = $actionMap[$log->action] ?? ['badge-gray', 'bi-activity'];
                        @endphp
                        <tr>
                            <td class="ps-4">
                                @if ($log->user)
                                    @php $color = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#ef4444'][crc32($log->user->name) % 5]; @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="av"
                                            style="background:{{ $color }}18;color:{{ $color }};">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:600;font-size:.82rem;color:#0f172a;">
                                                {{ e($log->user->name) }}</div>
                                            <div style="color:#94a3b8;font-size:.72rem;">{{ $log->user->role }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span style="color:#94a3b8;font-size:.82rem;">Sistem</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $badgeCls }}">
                                    <i class="bi {{ $iconCls }}"></i>
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td style="max-width:200px;">
                                <span
                                    style="font-size:.8rem;color:#475569;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;max-width:200px;">
                                    {{ $log->description ?? '—' }}
                                </span>
                            </td>
                            <td><code>{{ $log->ip_address ?? '—' }}</code></td>
                            <td style="font-size:.78rem;color:#64748b;">
                                <span style="background:#f1f5f9;padding:.2rem .5rem;border-radius:.3rem;">
                                    {{ $log->method ?? '' }} {{ $log->route ?? '—' }}
                                </span>
                            </td>
                            <td class="pe-4" style="white-space:nowrap;">
                                <div style="font-size:.8rem;color:#0f172a;font-weight:500;">
                                    {{ $log->created_at->format('d M Y') }}</div>
                                <div style="font-size:.75rem;color:#94a3b8;">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-journal-x"></i></div>
                                    <div class="empty-title">Tidak ada log aktivitas</div>
                                    <div class="empty-sub">Belum ada aktivitas yang dicatat</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logs->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                <span style="font-size:.78rem;color:#94a3b8;">Halaman {{ $logs->currentPage() }} dari
                    {{ $logs->lastPage() }}</span>
                {{ $logs->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

@endsection
