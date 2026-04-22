@extends('layouts.app')
@section('title', 'Item APBDes ' . $apbdes->tahun)
@section('page-title', 'Item APBDes')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Item APBDes {{ $apbdes->tahun }}</h5>
            <div class="sub">Kelola item {{ ucfirst($jenis) }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.apbdes.items.create', ['apbdes' => $apbdes, 'jenis' => $jenis]) }}"
                class="btn-primary-sm">
                <i class="bi bi-plus-lg"></i> Tambah Item
            </a>
            <a href="{{ route('admin.apbdes.show', $apbdes) }}" class="btn-secondary-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="mb-3">
        <a href="{{ route('admin.apbdes.items.index', ['apbdes' => $apbdes, 'jenis' => 'pendapatan']) }}"
            class="btn btn-sm {{ $jenis === 'pendapatan' ? 'btn-success' : 'btn-outline-secondary' }}">
            <i class="bi bi-arrow-down-circle"></i> Pendapatan
        </a>
        <a href="{{ route('admin.apbdes.items.index', ['apbdes' => $apbdes, 'jenis' => 'belanja']) }}"
            class="btn btn-sm {{ $jenis === 'belanja' ? 'btn-danger' : 'btn-outline-secondary' }}">
            <i class="bi bi-arrow-up-circle"></i> Belanja
        </a>
        <a href="{{ route('admin.apbdes.items.index', ['apbdes' => $apbdes, 'jenis' => 'pembiayaan']) }}"
            class="btn btn-sm {{ $jenis === 'pembiayaan' ? 'btn-primary' : 'btn-outline-secondary' }}">
            <i class="bi bi-arrow-left-right"></i> Pembiayaan
        </a>
    </div>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-list-ul me-2" style="color:#1a56db;"></i>Daftar {{ ucfirst($jenis) }}</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $items->total() }} item</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Kode</th>
                        <th>Uraian</th>
                        <th>Anggaran</th>
                        <th>Realisasi</th>
                        <th>%</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td class="ps-4" style="color:#94a3b8;font-size:.8rem;">{{ $item->urutan }}</td>
                            <td><code
                                    style="background:#e0e7ff;color:#3730a3;padding:2px 6px;border-radius:4px;">{{ $item->kode_rekening ?: '-' }}</code>
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $item->uraian }}</div>
                                @if ($item->kategori)
                                    <div style="font-size:.75rem;color:#64748b;">{{ $item->kategori }}</div>
                                @endif
                            </td>
                            <td style="font-weight:600;">Rp {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge"
                                    style="background:{{ $item->persentase_realisasi >= 80 ? '#dcfce7' : ($item->persentase_realisasi >= 50 ? '#fef3c7' : '#fee2e2') }};color:{{ $item->persentase_realisasi >= 80 ? '#166534' : ($item->persentase_realisasi >= 50 ? '#92400e' : '#991b1b') }};">
                                    {{ $item->persentase_realisasi }}%
                                </span>
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="{{ route('admin.apbdes.items.edit', ['apbdes' => $apbdes, 'item' => $item]) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;"
                                        title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST"
                                        action="{{ route('admin.apbdes.items.destroy', ['apbdes' => $apbdes, 'item' => $item]) }}"
                                        onsubmit="return confirm('Hapus item ini?')">
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
                                    <div class="empty-icon"><i class="bi bi-list-ul"></i></div>
                                    <div class="empty-title">Belum ada item {{ $jenis }}</div>
                                    <div class="empty-sub">Klik "Tambah Item" untuk menambahkan</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($items->hasPages())
            <div style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;">
                {{ $items->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

@endsection
