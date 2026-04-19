@extends('layouts.app')
@section('title', 'Kelola Struktur Desa')
@section('page-title', 'Kelola Struktur Desa')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .avatar-mini {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: linear-gradient(135deg, #e8f5ee, #d4ebe0);
            color: #2d8659;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .8rem;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Kelola Struktur Desa</h5>
            <div class="sub">Tambah, edit, dan hapus data perangkat desa</div>
        </div>
        <a href="{{ route('admin.struktur-desa.create') }}" class="btn-primary-sm">
            <i class="bi bi-plus-lg"></i> Tambah Perangkat
        </a>
    </div>

    <form method="GET" class="filter-bar">
        <div class="search-wrap">
            <i class="bi bi-search si"></i>
            <input type="text" name="search" class="filter-input" placeholder="Cari nama atau jabatan..."
                value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn-primary-sm"><i class="bi bi-funnel"></i> Cari</button>
        @if (request('search'))
            <a href="{{ route('admin.struktur-desa.index') }}" style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </form>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-diagram-3-fill me-2" style="color:#1a56db;"></i>Daftar Perangkat Desa</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $strukturDesa->total() }} perangkat ditemukan</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:80px;">Urutan</th>
                        <th>Perangkat</th>
                        <th>Jabatan</th>
                        <th>Telepon</th>
                        <th>Tampil Publik</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($strukturDesa as $item)
                        <tr>
                            <td class="ps-4" style="font-weight:600;color:#1a56db;">#{{ $item->urutan }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($item->foto && file_exists(public_path('storage/' . $item->foto)))
                                        <img src="{{ Storage::url($item->foto) }}" class="avatar-mini"
                                            alt="{{ $item->nama }}">
                                    @else
                                        <div class="avatar-mini">{{ strtoupper(substr($item->nama, 0, 2)) }}</div>
                                    @endif
                                    <div>
                                        <div style="font-weight:600;color:#0f172a;font-size:.88rem;">{{ $item->nama }}
                                        </div>
                                        @if ($item->keterangan)
                                            <div style="color:#94a3b8;font-size:.72rem;">
                                                {{ Str::limit($item->keterangan, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="color:#334155;font-size:.85rem;">{{ $item->jabatan }}</td>
                            <td style="color:#64748b;font-size:.82rem;">{{ $item->telepon ?? '—' }}</td>
                            <td>
                                @if ($item->tampil_publik)
                                    <span class="status-badge badge-success"><i class="bi bi-eye-fill"
                                            style="font-size:.7rem;"></i>Tampil</span>
                                @else
                                    <span class="status-badge badge-gray"><i class="bi bi-eye-slash-fill"
                                            style="font-size:.7rem;"></i>Disembunyikan</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="{{ route('admin.struktur-desa.edit', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;display:inline-flex;align-items:center;gap:.3rem;">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.struktur-desa.destroy', $item) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus data perangkat ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="padding:.35rem .6rem;border:1.5px solid #fca5a5;border-radius:.45rem;background:#fff5f5;color:#dc2626;font-size:.82rem;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-diagram-3"></i></div>
                                    <div class="empty-title">Belum ada perangkat desa</div>
                                    <div class="empty-sub">Klik "Tambah Perangkat" untuk menambahkan data baru</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($strukturDesa->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                    {{ $strukturDesa->firstItem() }}–{{ $strukturDesa->lastItem() }} dari
                    {{ $strukturDesa->total() }}</span>
                {{ $strukturDesa->links() }}
            </div>
        @endif
    </div>

@endsection
