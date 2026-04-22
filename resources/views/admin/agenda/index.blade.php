@extends('layouts.app')
@section('title', 'Kelola Agenda')
@section('page-title', 'Kelola Agenda')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Agenda Kegiatan</h5>
            <div class="sub">Kelola jadwal kegiatan dan acara desa</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.agenda.create') }}" class="btn-primary-sm">
                <i class="bi bi-plus-lg"></i> Tambah Agenda
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" class="filter-bar">
        <div class="search-wrap">
            <i class="bi bi-search si"></i>
            <input type="text" name="search" class="filter-input" placeholder="Cari judul..."
                value="{{ request('search') }}">
        </div>
        <select name="kategori" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach (\App\Models\Agenda::kategoriOptions() as $key => $label)
                <option value="{{ $key }}" {{ request('kategori') === $key ? 'selected' : '' }}>{{ $label }}
                </option>
            @endforeach
        </select>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="publikasi" {{ request('status') === 'publikasi' ? 'selected' : '' }}>Publikasi</option>
            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>
        <select name="bulan" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Bulan</option>
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                </option>
            @endfor
        </select>
        <select name="tahun" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Tahun</option>
            @foreach ($tahunList as $thn)
                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary-sm"><i class="bi bi-funnel"></i> Filter</button>
        @if (request()->hasAny(['search', 'kategori', 'status', 'bulan', 'tahun']))
            <a href="{{ route('admin.agenda.index') }}" style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </form>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-calendar-event me-2" style="color:#1a56db;"></i>Daftar Agenda</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $agenda->total() }} data ditemukan</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Judul</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agenda as $item)
                        <tr>
                            <td class="ps-4">
                                <div style="font-weight:600;color:#0f172a;max-width:280px;">
                                    {{ Str::limit($item->judul, 55) }}</div>
                                @if ($item->is_highlight)
                                    <span class="badge bg-warning text-dark" style="font-size:.65rem;"><i
                                            class="bi bi-star-fill"></i> Highlight</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $item->tanggal_mulai->format('d M Y') }}</div>
                                @if ($item->waktu_mulai)
                                    <div style="font-size:.75rem;color:#64748b;">{{ $item->waktu_format }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge"
                                    style="background:{{ $item->kategori_bg }};color:{{ $item->kategori_color }};">
                                    {{ $item->kategori_label }}
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:.85rem;">{{ Str::limit($item->lokasi ?? '-', 25) }}</td>
                            <td>
                                <span class="status-badge {{ $item->status_badge }}">
                                    <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>{{ $item->status_label }}
                                </span>
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="{{ route('admin.agenda.edit', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;display:inline-flex;align-items:center;gap:.3rem;"
                                        title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.agenda.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus agenda ini?')">
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
                                    <div class="empty-icon"><i class="bi bi-calendar-event"></i></div>
                                    <div class="empty-title">Belum ada agenda</div>
                                    <div class="empty-sub">Klik "Tambah Agenda" untuk menambahkan</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($agenda->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                    {{ $agenda->firstItem() }}–{{ $agenda->lastItem() }} dari {{ $agenda->total() }}</span>
                {{ $agenda->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

@endsection
