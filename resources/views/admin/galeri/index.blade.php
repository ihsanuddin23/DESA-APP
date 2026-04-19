@extends('layouts.app')
@section('title', 'Kelola Galeri')
@section('page-title', 'Kelola Galeri')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .galeri-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
            padding: 1.25rem;
        }

        .galeri-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: .7rem;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(15, 23, 42, .04);
            display: flex;
            flex-direction: column;
            transition: box-shadow .2s;
        }

        .galeri-card:hover {
            box-shadow: 0 4px 16px rgba(15, 23, 42, .12);
        }

        .galeri-thumb {
            aspect-ratio: 1/1;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            overflow: hidden;
        }

        .galeri-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .galeri-body {
            padding: .75rem;
            display: flex;
            flex-direction: column;
            gap: .3rem;
            flex-grow: 1;
        }

        .galeri-judul {
            font-weight: 600;
            color: #0f172a;
            font-size: .85rem;
            line-height: 1.3;
        }

        .galeri-keterangan {
            color: #64748b;
            font-size: .72rem;
        }

        .galeri-actions {
            padding: .5rem .75rem;
            border-top: 1px solid #f1f5f9;
            display: flex;
            gap: .35rem;
            justify-content: flex-end;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Kelola Galeri</h5>
            <div class="sub">Unggah, edit, dan hapus foto galeri desa</div>
        </div>
        <a href="{{ route('admin.galeri.create') }}" class="btn-primary-sm">
            <i class="bi bi-plus-lg"></i> Tambah Foto
        </a>
    </div>

    <form method="GET" class="filter-bar">
        <div class="search-wrap">
            <i class="bi bi-search si"></i>
            <input type="text" name="search" class="filter-input" placeholder="Cari judul foto..."
                value="{{ request('search') }}">
        </div>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
        </select>
        <button type="submit" class="btn-primary-sm"><i class="bi bi-funnel"></i> Filter</button>
        @if (request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.galeri.index') }}" style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </form>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-images me-2" style="color:#1a56db;"></i>Daftar Foto Galeri</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $galeri->total() }} foto ditemukan</span>
        </div>

        @if ($galeri->count() > 0)
            <div class="galeri-grid">
                @foreach ($galeri as $item)
                    <div class="galeri-card">
                        <div class="galeri-thumb">
                            @if ($item->file && file_exists(public_path('storage/' . $item->file)))
                                <img src="{{ Storage::url($item->file) }}" alt="{{ $item->judul }}">
                            @else
                                <i class="bi bi-image" style="font-size:2.5rem;"></i>
                            @endif
                        </div>
                        <div class="galeri-body">
                            <div class="galeri-judul">{{ Str::limit($item->judul, 40) }}</div>
                            @if ($item->keterangan)
                                <div class="galeri-keterangan">{{ Str::limit($item->keterangan, 55) }}</div>
                            @endif
                            <div style="margin-top:.35rem;">
                                @if ($item->status === 'published')
                                    <span class="status-badge badge-success" style="font-size:.65rem;"><i
                                            class="bi bi-circle-fill" style="font-size:.35rem;"></i>Published</span>
                                @else
                                    <span class="status-badge badge-gray" style="font-size:.65rem;"><i
                                            class="bi bi-circle-fill" style="font-size:.35rem;"></i>Draft</span>
                                @endif
                            </div>
                        </div>
                        <div class="galeri-actions">
                            <a href="{{ route('admin.galeri.edit', $item) }}"
                                style="padding:.3rem .55rem;border:1.5px solid #e2e8f0;border-radius:.4rem;color:#1a56db;text-decoration:none;font-size:.75rem;display:inline-flex;align-items:center;gap:.25rem;">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.galeri.destroy', $item) }}"
                                onsubmit="return confirm('Yakin ingin menghapus foto ini?')" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    style="padding:.3rem .55rem;border:1.5px solid #fca5a5;border-radius:.4rem;background:#fff5f5;color:#dc2626;font-size:.75rem;cursor:pointer;display:inline-flex;align-items:center;gap:.25rem;">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($galeri->hasPages())
                <div
                    style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                    <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                        {{ $galeri->firstItem() }}–{{ $galeri->lastItem() }} dari {{ $galeri->total() }}</span>
                    {{ $galeri->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-images"></i></div>
                <div class="empty-title">Belum ada foto</div>
                <div class="empty-sub">Klik "Tambah Foto" untuk unggah foto baru</div>
            </div>
        @endif
    </div>

@endsection
