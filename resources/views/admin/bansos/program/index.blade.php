@extends('layouts.app')
@section('title', 'Kelola Program Bansos')
@section('page-title', 'Kelola Program Bansos')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Kelola Program Bansos</h5>
            <div class="sub">Tambah, edit, dan hapus program bantuan sosial</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.bansos.penerima.index') }}" class="btn-primary-sm" style="background:#64748b;">
                <i class="bi bi-people"></i> Data Penerima
            </a>
            <a href="{{ route('admin.bansos.program.create') }}" class="btn-primary-sm">
                <i class="bi bi-plus-lg"></i> Tambah Program
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-grid me-2" style="color:#1a56db;"></i>Daftar Program Bansos</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $programs->total() }} program ditemukan</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama Program</th>
                        <th>Kode</th>
                        <th>Jenis</th>
                        <th>Nominal/Bulan</th>
                        <th>Penerima Aktif</th>
                        <th>Status</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($programs as $item)
                        <tr>
                            <td class="ps-4">
                                <div style="font-weight:600;color:#0f172a;max-width:280px;">
                                    {{ Str::limit($item->nama, 55) }}</div>
                                @if ($item->deskripsi)
                                    <div style="color:#94a3b8;font-size:.75rem;">{{ Str::limit($item->deskripsi, 70) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge" style="background:#eff6ff;color:#1e40af;font-family:monospace;">
                                    {{ $item->kode }}
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:.82rem;">{{ $item->jenis_label }}</td>
                            <td style="color:#0f172a;font-size:.82rem;">
                                @if ($item->nominal_per_bulan)
                                    Rp {{ number_format($item->nominal_per_bulan, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge" style="background:#f1f5f9;color:#334155;">
                                    {{ $item->penerima_aktif_count }} orang
                                </span>
                            </td>
                            <td>
                                @if ($item->status === 'aktif')
                                    <span class="status-badge badge-success"><i class="bi bi-circle-fill"
                                            style="font-size:.4rem;"></i>Aktif</span>
                                @else
                                    <span class="status-badge badge-gray"><i class="bi bi-circle-fill"
                                            style="font-size:.4rem;"></i>Nonaktif</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="{{ route('admin.bansos.program.edit', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;display:inline-flex;align-items:center;gap:.3rem;"
                                        title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.bansos.program.destroy', $item) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus program ini? Data penerima terkait juga akan terpengaruh.')">
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
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-grid"></i></div>
                                    <div class="empty-title">Belum ada program bansos</div>
                                    <div class="empty-sub">Klik "Tambah Program" untuk menambahkan program baru</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($programs->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                    {{ $programs->firstItem() }}–{{ $programs->lastItem() }} dari {{ $programs->total() }}</span>
                {{ $programs->links() }}
            </div>
        @endif
    </div>

@endsection
