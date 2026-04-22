@extends('layouts.app')
@section('title', 'Kelola APBDes')
@section('page-title', 'Kelola APBDes')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>APBDes</h5>
            <div class="sub">Kelola Anggaran Pendapatan dan Belanja Desa</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.apbdes.create') }}" class="btn-primary-sm">
                <i class="bi bi-plus-lg"></i> Tambah Tahun
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
        <select name="tahun" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Tahun</option>
            @foreach ($tahunList as $thn)
                <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}
                </option>
            @endforeach
        </select>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        @if (request()->hasAny(['tahun', 'status']))
            <a href="{{ route('admin.apbdes.index') }}" style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </form>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-wallet2 me-2" style="color:#1a56db;"></i>Daftar APBDes</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $apbdes->total() }} data ditemukan</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tahun</th>
                        <th>Pendapatan</th>
                        <th>Belanja</th>
                        <th>Pembiayaan</th>
                        <th>Sisa</th>
                        <th>Status</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($apbdes as $item)
                        <tr>
                            <td class="ps-4">
                                <div style="font-weight:700;color:#0f172a;font-size:1.1rem;">{{ $item->tahun }}</div>
                            </td>
                            <td style="color:#166534;font-weight:600;">Rp
                                {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                            <td style="color:#dc2626;font-weight:600;">Rp
                                {{ number_format($item->total_belanja, 0, ',', '.') }}</td>
                            <td style="color:#2563eb;font-weight:600;">Rp
                                {{ number_format($item->total_pembiayaan, 0, ',', '.') }}</td>
                            <td style="font-weight:600;color:{{ $item->sisa_anggaran >= 0 ? '#166534' : '#dc2626' }};">
                                Rp {{ number_format($item->sisa_anggaran, 0, ',', '.') }}
                            </td>
                            <td>
                                @php
                                    $badge = match ($item->status) {
                                        'aktif' => 'badge-success',
                                        'draft' => 'badge-warning',
                                        'selesai' => 'badge-gray',
                                        default => 'badge-gray',
                                    };
                                @endphp
                                <span class="status-badge {{ $badge }}">
                                    <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>{{ $item->status_label }}
                                </span>
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="{{ route('admin.apbdes.show', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#166534;text-decoration:none;font-size:.82rem;"
                                        title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.apbdes.items.index', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#2563eb;text-decoration:none;font-size:.82rem;"
                                        title="Kelola Item"><i class="bi bi-list-ul"></i></a>
                                    <a href="{{ route('admin.apbdes.edit', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;"
                                        title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.apbdes.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus APBDes {{ $item->tahun }}? Semua item akan ikut terhapus.')">
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
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-wallet2"></i></div>
                                    <div class="empty-title">Belum ada data APBDes</div>
                                    <div class="empty-sub">Klik "Tambah Tahun" untuk menambahkan data baru</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($apbdes->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                    {{ $apbdes->firstItem() }}-{{ $apbdes->lastItem() }} dari {{ $apbdes->total() }}</span>
                {{ $apbdes->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

@endsection
