@extends('layouts.app')
@section('title', 'Kelola Pengumuman')
@section('page-title', 'Kelola Pengumuman')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Kelola Pengumuman</h5>
            <div class="sub">Tambah, edit, dan hapus pengumuman desa</div>
        </div>
        <a href="{{ route('admin.pengumuman.create') }}" class="btn-primary-sm">
            <i class="bi bi-plus-lg"></i> Tambah Pengumuman
        </a>
    </div>

    <form method="GET" class="filter-bar">
        <div class="search-wrap">
            <i class="bi bi-search si"></i>
            <input type="text" name="search" class="filter-input" placeholder="Cari judul pengumuman..."
                value="{{ request('search') }}">
        </div>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
        </select>
        <select name="prioritas" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Prioritas</option>
            <option value="penting" {{ request('prioritas') === 'penting' ? 'selected' : '' }}>Penting</option>
            <option value="info" {{ request('prioritas') === 'info' ? 'selected' : '' }}>Info</option>
            <option value="umum" {{ request('prioritas') === 'umum' ? 'selected' : '' }}>Umum</option>
        </select>
        <button type="submit" class="btn-primary-sm"><i class="bi bi-funnel"></i> Filter</button>
        @if (request()->hasAny(['search', 'status', 'prioritas']))
            <a href="{{ route('admin.pengumuman.index') }}" style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </form>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-megaphone-fill me-2" style="color:#1a56db;"></i>Daftar Pengumuman</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $pengumuman->total() }} pengumuman ditemukan</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Judul</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Berlaku Hingga</th>
                        <th>Tanggal</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengumuman as $item)
                        <tr>
                            <td class="ps-4">
                                <div style="font-weight:600;color:#0f172a;max-width:320px;">
                                    {{ Str::limit($item->judul, 60) }}</div>
                                @if ($item->isi)
                                    <div style="color:#94a3b8;font-size:.75rem;">{{ Str::limit($item->isi, 70) }}</div>
                                @endif
                                @if ($item->file_lampiran)
                                    <div style="margin-top:.2rem;"><i class="bi bi-paperclip"
                                            style="color:#64748b;font-size:.75rem;"></i> <span
                                            style="color:#64748b;font-size:.75rem;">Ada lampiran</span></div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $pClass = match ($item->prioritas) {
                                        'penting' => 'background:#fee2e2;color:#991b1b;',
                                        'info' => 'background:#dbeafe;color:#1e40af;',
                                        default => 'background:#f1f5f9;color:#475569;',
                                    };
                                @endphp
                                <span class="status-badge" style="{{ $pClass }}">
                                    {{ ucfirst($item->prioritas) }}
                                </span>
                            </td>
                            <td>
                                @if ($item->status === 'aktif')
                                    <span class="status-badge badge-success"><i class="bi bi-circle-fill"
                                            style="font-size:.4rem;"></i>Aktif</span>
                                @else
                                    <span class="status-badge badge-gray"><i class="bi bi-circle-fill"
                                            style="font-size:.4rem;"></i>Non-aktif</span>
                                @endif
                            </td>
                            <td style="color:#64748b;font-size:.8rem;">
                                {{ $item->berlaku_hingga ? $item->berlaku_hingga->format('d/m/Y') : '—' }}
                            </td>
                            <td style="color:#64748b;font-size:.8rem;">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="{{ route('admin.pengumuman.edit', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;display:inline-flex;align-items:center;gap:.3rem;"
                                        title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.pengumuman.destroy', $item) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="padding:.35rem .6rem;border:1.5px solid #fca5a5;border-radius:.45rem;background:#fff5f5;color:#dc2626;font-size:.82rem;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;"
                                            title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-megaphone"></i></div>
                                    <div class="empty-title">Belum ada pengumuman</div>
                                    <div class="empty-sub">Klik "Tambah Pengumuman" untuk membuat pengumuman baru</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pengumuman->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                    {{ $pengumuman->firstItem() }}–{{ $pengumuman->lastItem() }} dari {{ $pengumuman->total() }}</span>
                {{ $pengumuman->links() }}
            </div>
        @endif
    </div>

@endsection
