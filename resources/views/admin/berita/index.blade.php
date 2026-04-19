@extends('layouts.app')
@section('title', 'Kelola Berita')
@section('page-title', 'Kelola Berita')

@push('styles')
@include('admin._admin-styles')
@endpush

@section('content')

<div class="page-header">
    <div>
        <h5>Kelola Berita</h5>
        <div class="sub">Tambah, edit, dan hapus berita desa</div>
    </div>
    <a href="{{ route('admin.berita.create') }}" class="btn-primary-sm">
        <i class="bi bi-plus-lg"></i> Tambah Berita
    </a>
</div>

<form method="GET" class="filter-bar">
    <div class="search-wrap">
        <i class="bi bi-search si"></i>
        <input type="text" name="search" class="filter-input" placeholder="Cari judul berita..." value="{{ request('search') }}">
    </div>
    <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
        <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
    </select>
    <select name="kategori" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        @foreach(['umum','pemerintahan','kegiatan','pengumuman','pembangunan'] as $kat)
            <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ ucfirst($kat) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary-sm"><i class="bi bi-funnel"></i> Filter</button>
    @if(request()->hasAny(['search','status','kategori']))
        <a href="{{ route('admin.berita.index') }}" style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
            <i class="bi bi-x-circle me-1"></i>Reset
        </a>
    @endif
</form>

<div class="data-card">
    <div class="data-card-header">
        <h6><i class="bi bi-newspaper me-2" style="color:#1a56db;"></i>Daftar Berita</h6>
        <span style="font-size:.78rem;color:#94a3b8;">{{ $berita->total() }} berita ditemukan</span>
    </div>
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Judul</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($berita as $item)
                <tr>
                    <td class="ps-4">
                        <div style="font-weight:600;color:#0f172a;max-width:280px;">{{ Str::limit($item->judul, 55) }}</div>
                        @if($item->ringkasan)
                            <div style="color:#94a3b8;font-size:.75rem;">{{ Str::limit($item->ringkasan, 60) }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge" style="background:#eff6ff;color:#1e40af;">
                            {{ ucfirst($item->kategori) }}
                        </span>
                    </td>
                    <td style="color:#64748b;font-size:.82rem;">{{ $item->penulis ?? '—' }}</td>
                    <td>
                        @if($item->status === 'published')
                            <span class="status-badge badge-success"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i>Published</span>
                        @else
                            <span class="status-badge badge-gray"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i>Draft</span>
                        @endif
                    </td>
                    <td style="color:#64748b;font-size:.8rem;">{{ $item->created_at->format('d/m/Y') }}</td>
                    <td class="pe-4">
                        <div class="d-flex align-items-center gap-1">
                            <a href="{{ route('admin.berita.edit', $item) }}"
                               style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;display:inline-flex;align-items:center;gap:.3rem;"
                               title="Edit"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.berita.destroy', $item) }}" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
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
                            <div class="empty-icon"><i class="bi bi-newspaper"></i></div>
                            <div class="empty-title">Belum ada berita</div>
                            <div class="empty-sub">Klik "Tambah Berita" untuk menambahkan berita baru</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($berita->hasPages())
    <div style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
        <span style="font-size:.78rem;color:#94a3b8;">Menampilkan {{ $berita->firstItem() }}–{{ $berita->lastItem() }} dari {{ $berita->total() }}</span>
        {{ $berita->links() }}
    </div>
    @endif
</div>

@endsection
