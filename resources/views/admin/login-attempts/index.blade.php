@extends('layouts.app')
@section('title', 'Percobaan Login')
@section('page-title', 'Percobaan Login')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Log Percobaan Login</h5>
            <div class="sub">Monitor semua percobaan login ke sistem</div>
        </div>
        {{-- Stats pills --}}
        <div class="d-flex gap-2">
            @php
                $totalToday = \App\Models\LoginAttempt::whereDate('attempted_at', today())->count();
                $failedToday = \App\Models\LoginAttempt::whereDate('attempted_at', today())
                    ->where('successful', false)
                    ->count();
            @endphp
            <div
                style="background:#f0fdf4;border:1px solid #86efac;border-radius:.6rem;padding:.5rem .9rem;text-align:center;">
                <div style="font-size:1.1rem;font-weight:800;color:#15803d;line-height:1;">{{ $totalToday }}</div>
                <div style="font-size:.7rem;color:#16a34a;">Hari ini</div>
            </div>
            <div
                style="background:#fef2f2;border:1px solid #fca5a5;border-radius:.6rem;padding:.5rem .9rem;text-align:center;">
                <div style="font-size:1.1rem;font-weight:800;color:#b91c1c;line-height:1;">{{ $failedToday }}</div>
                <div style="font-size:.7rem;color:#dc2626;">Gagal hari ini</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="filter-bar">
        <div class="search-wrap">
            <i class="bi bi-envelope si"></i>
            <input type="text" name="email" class="filter-input" placeholder="Filter email..."
                value="{{ request('email') }}">
        </div>
        <div class="search-wrap">
            <i class="bi bi-geo-alt si"></i>
            <input type="text" name="ip" class="filter-input" placeholder="Filter IP..." value="{{ request('ip') }}"
                style="min-width:150px;">
        </div>
        <select name="successful" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Hasil</option>
            <option value="1" {{ request('successful') === '1' ? 'selected' : '' }}>Berhasil</option>
            <option value="0" {{ request('successful') === '0' ? 'selected' : '' }}>Gagal</option>
        </select>
        <button type="submit" class="btn-primary-sm"><i class="bi bi-funnel"></i> Filter</button>
        @if (request()->hasAny(['email', 'ip', 'successful']))
            <a href="{{ route('admin.login-attempts.index') }}"
                style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </form>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-shield-exclamation me-2" style="color:#f59e0b;"></i>Riwayat Percobaan Login</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $attempts->total() }} entri</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Email</th>
                        <th>IP Address</th>
                        <th>Hasil</th>
                        <th>Alasan Gagal</th>
                        <th>Perangkat</th>
                        <th class="pe-4">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            <td class="ps-4">
                                <span style="font-size:.85rem;font-weight:500;color:#0f172a;">{{ $attempt->email }}</span>
                            </td>
                            <td><code>{{ $attempt->ip_address }}</code></td>
                            <td>
                                @if ($attempt->successful)
                                    <span class="status-badge badge-success"><i
                                            class="bi bi-check-circle-fill"></i>Berhasil</span>
                                @else
                                    <span class="status-badge badge-danger"><i class="bi bi-x-circle-fill"></i>Gagal</span>
                                @endif
                            </td>
                            <td>
                                @if ($attempt->failure_reason)
                                    @php
                                        $reasonMap = [
                                            'invalid_credentials' => ['badge-danger', 'Kredensial salah'],
                                            'account_inactive' => ['badge-warning', 'Akun nonaktif'],
                                            'account_locked' => ['badge-warning', 'Akun terkunci'],
                                            'too_many_attempts_email' => ['badge-danger', 'Terlalu banyak coba'],
                                            'too_many_attempts_ip' => ['badge-danger', 'IP rate limit'],
                                            'ip_blocked' => ['badge-danger', 'IP diblokir'],
                                        ];
                                        [$rc, $rl] = $reasonMap[$attempt->failure_reason] ?? [
                                            'badge-gray',
                                            $attempt->failure_reason,
                                        ];
                                    @endphp
                                    <span class="status-badge {{ $rc }}">{{ $rl }}</span>
                                @else
                                    <span style="color:#94a3b8;font-size:.8rem;">—</span>
                                @endif
                            </td>
                            <td style="font-size:.78rem;color:#64748b;max-width:180px;">
                                <span
                                    style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px;"
                                    title="{{ $attempt->user_agent }}">
                                    {{ $attempt->user_agent ? Str::limit($attempt->user_agent, 40) : '—' }}
                                </span>
                            </td>
                            <td class="pe-4" style="white-space:nowrap;">
                                <div style="font-size:.8rem;color:#0f172a;font-weight:500;">
                                    {{ $attempt->attempted_at->format('d M Y') }}</div>
                                <div style="font-size:.75rem;color:#94a3b8;">{{ $attempt->attempted_at->format('H:i:s') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-shield-check"></i></div>
                                    <div class="empty-title">Tidak ada percobaan login</div>
                                    <div class="empty-sub">Belum ada aktivitas login yang tercatat</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($attempts->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                <span style="font-size:.78rem;color:#94a3b8;">Halaman {{ $attempts->currentPage() }} dari
                    {{ $attempts->lastPage() }}</span>
                {{ $attempts->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

@endsection
