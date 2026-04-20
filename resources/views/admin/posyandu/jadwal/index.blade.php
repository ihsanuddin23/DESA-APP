@extends('layouts.app')
@section('title', 'Jadwal Posyandu')
@section('page-title', 'Jadwal Posyandu')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Jadwal Kunjungan Posyandu</h5>
            <div class="sub">Kelola jadwal kegiatan posyandu per bulan</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.posyandu.index') }}" class="btn-primary-sm" style="background:#64748b;">
                <i class="bi bi-heart-pulse"></i> Data Posyandu
            </a>
            <a href="{{ route('admin.posyandu.jadwal.create') }}" class="btn-primary-sm">
                <i class="bi bi-plus-lg"></i> Tambah Jadwal
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="GET" class="filter-bar">
        <select name="posyandu_id" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Posyandu</option>
            @foreach ($posyandu as $p)
                <option value="{{ $p->id }}" {{ request('posyandu_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}</option>
            @endforeach
        </select>
        <select name="bulan" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Bulan</option>
            @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $i => $nama)
                <option value="{{ $i + 1 }}" {{ request('bulan') == $i + 1 ? 'selected' : '' }}>{{ $nama }}
                </option>
            @endforeach
        </select>
        <select name="tahun" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Tahun</option>
            @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}
                </option>
            @endfor
        </select>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="terjadwal" {{ request('status') === 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
            <option value="berlangsung" {{ request('status') === 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        @if (request()->hasAny(['posyandu_id', 'bulan', 'tahun', 'status']))
            <a href="{{ route('admin.posyandu.jadwal.index') }}"
                style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </form>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-calendar-event me-2" style="color:#1a56db;"></i>Daftar Jadwal</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $jadwal->total() }} jadwal ditemukan</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Waktu</th>
                        <th>Posyandu</th>
                        <th>Kegiatan</th>
                        <th>Status</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $item)
                        <tr>
                            <td class="ps-4">
                                <div style="font-weight:600;color:#0f172a;">
                                    {{ $item->tanggal->isoFormat('D MMM YYYY') }}
                                </div>
                                <div style="color:#94a3b8;font-size:.72rem;">
                                    {{ $item->tanggal->isoFormat('dddd') }}
                                </div>
                            </td>
                            <td style="color:#64748b;font-size:.82rem;font-family:monospace;">
                                {{ $item->waktu_format }}
                            </td>
                            <td>
                                <div style="font-weight:600;color:#0f172a;font-size:.88rem;">{{ $item->posyandu->nama }}
                                </div>
                                <div style="color:#94a3b8;font-size:.72rem;">{{ $item->posyandu->lokasi }}</div>
                            </td>
                            <td style="color:#334155;font-size:.85rem;">
                                {{ $item->kegiatan }}
                                @if ($item->catatan)
                                    <div style="color:#94a3b8;font-size:.72rem;margin-top:.2rem;">
                                        <i class="bi bi-chat-left-text me-1"></i>{{ Str::limit($item->catatan, 60) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badge = match ($item->status) {
                                        'terjadwal' => ['bg' => '#eff6ff', 'fg' => '#1e40af'],
                                        'berlangsung' => ['bg' => '#fef3c7', 'fg' => '#92400e'],
                                        'selesai' => ['bg' => '#d1fae5', 'fg' => '#065f46'],
                                        'batal' => ['bg' => '#fee2e2', 'fg' => '#991b1b'],
                                    };
                                @endphp
                                <span class="status-badge"
                                    style="background:{{ $badge['bg'] }};color:{{ $badge['fg'] }};">
                                    <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>{{ $item->status_label }}
                                </span>
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="{{ route('admin.posyandu.jadwal.edit', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;"
                                        title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.posyandu.jadwal.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="padding:.35rem .6rem;border:1.5px solid #fca5a5;border-radius:.45rem;background:#fff5f5;color:#dc2626;font-size:.82rem;cursor:pointer;"
                                            title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-calendar-event"></i></div>
                                    <div class="empty-title">Belum ada jadwal</div>
                                    <div class="empty-sub">Klik "Tambah Jadwal" untuk menambahkan jadwal baru</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($jadwal->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                    {{ $jadwal->firstItem() }}–{{ $jadwal->lastItem() }} dari {{ $jadwal->total() }}</span>
                {{ $jadwal->links() }}
            </div>
        @endif
    </div>

@endsection
