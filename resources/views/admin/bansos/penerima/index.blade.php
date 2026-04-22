@extends('layouts.app')
@section('title', 'Data Penerima Bansos')
@section('page-title', 'Data Penerima Bansos')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Data Penerima Bansos</h5>
            <div class="sub">Kelola data penerima bantuan sosial per program</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.bansos.program.index') }}" class="btn-primary-sm" style="background:#64748b;">
                <i class="bi bi-grid"></i> Program
            </a>
            <a href="{{ route('admin.bansos.penerima.create') }}" class="btn-primary-sm">
                <i class="bi bi-plus-lg"></i> Tambah Penerima
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
        <div class="search-wrap">
            <i class="bi bi-search si"></i>
            <input type="text" name="cari" class="filter-input" placeholder="Cari NIK atau nama penerima..."
                value="{{ request('cari') }}">
        </div>
        <select name="program_id" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Program</option>
            @foreach ($programs as $prog)
                <option value="{{ $prog->id }}" {{ request('program_id') == $prog->id ? 'selected' : '' }}>
                    {{ $prog->nama }}
                </option>
            @endforeach
        </select>
        <select name="tahun" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Tahun</option>
            @foreach ($tahunList as $thn)
                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}
                </option>
            @endforeach
        </select>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            <option value="dicoret" {{ request('status') === 'dicoret' ? 'selected' : '' }}>Dicoret</option>
        </select>
        <button type="submit" class="btn-primary-sm"><i class="bi bi-funnel"></i> Filter</button>
        @if (request()->hasAny(['cari', 'program_id', 'tahun', 'status']))
            <a href="{{ route('admin.bansos.penerima.index') }}"
                style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </form>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-people me-2" style="color:#1a56db;"></i>Daftar Penerima</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $penerima->total() }} penerima ditemukan</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Nama / NIK</th>
                        <th>Program</th>
                        <th>RT/RW</th>
                        <th>Tahun</th>
                        <th>Periode</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penerima as $item)
                        <tr>
                            <td class="ps-4" style="color:#94a3b8;font-size:.8rem;">
                                {{ $penerima->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div style="font-weight:600;color:#0f172a;">{{ $item->nama_penerima }}</div>
                                <div style="color:#94a3b8;font-size:.75rem;font-family:monospace;">{{ $item->nik }}
                                </div>
                            </td>
                            <td>
                                <span class="status-badge" style="background:#eff6ff;color:#1e40af;font-family:monospace;">
                                    {{ $item->program->kode }}
                                </span>
                                <div style="color:#64748b;font-size:.72rem;margin-top:.2rem;">
                                    {{ Str::limit($item->program->nama, 25) }}</div>
                            </td>
                            <td style="color:#64748b;font-size:.82rem;">
                                @if ($item->rt || $item->rw)
                                    RT {{ $item->rt ?? '-' }} / RW {{ $item->rw ?? '-' }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td style="color:#64748b;font-size:.82rem;">{{ $item->tahun }}</td>
                            <td style="color:#64748b;font-size:.82rem;">{{ $item->periode_label }}</td>
                            <td style="color:#0f172a;font-size:.82rem;">
                                @if ($item->nominal)
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badge = match ($item->status) {
                                        'aktif' => 'badge-success',
                                        'nonaktif' => 'badge-gray',
                                        'dicoret' => 'badge-danger',
                                        default => 'badge-gray',
                                    };
                                @endphp
                                <span class="status-badge {{ $badge }}">
                                    <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>{{ $item->status_label }}
                                </span>
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="{{ route('admin.bansos.penerima.edit', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;display:inline-flex;align-items:center;gap:.3rem;"
                                        title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.bansos.penerima.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus data {{ $item->nama_penerima }}?')">
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
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-people"></i></div>
                                    <div class="empty-title">Belum ada data penerima</div>
                                    <div class="empty-sub">Klik "Tambah Penerima" untuk menambahkan data baru</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($penerima->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                    {{ $penerima->firstItem() }}–{{ $penerima->lastItem() }} dari {{ $penerima->total() }}</span>
                {{ $penerima->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

@endsection
