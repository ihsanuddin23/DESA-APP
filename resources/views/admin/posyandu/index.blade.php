@extends('layouts.app')
@section('title', 'Kelola Posyandu')
@section('page-title', 'Kelola Posyandu')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Kelola Posyandu</h5>
            <div class="sub">Master data posyandu beserta kader dan lokasi</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.posyandu.jadwal.index') }}" class="btn-primary-sm" style="background:#64748b;">
                <i class="bi bi-calendar-event"></i> Jadwal
            </a>
            <a href="{{ route('admin.posyandu.create') }}" class="btn-primary-sm">
                <i class="bi bi-plus-lg"></i> Tambah Posyandu
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
            <input type="text" name="cari" class="filter-input" placeholder="Cari nama/kode/lokasi..."
                value="{{ request('cari') }}">
        </div>
        <select name="rw" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua RW</option>
            @foreach ($rwList as $rw)
                <option value="{{ $rw }}" {{ request('rw') === $rw ? 'selected' : '' }}>RW {{ $rw }}
                </option>
            @endforeach
        </select>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="btn-primary-sm"><i class="bi bi-funnel"></i> Filter</button>
        @if (request()->hasAny(['cari', 'rw', 'status']))
            <a href="{{ route('admin.posyandu.index') }}" style="font-size:.82rem;color:#94a3b8;text-decoration:none;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        @endif
    </form>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-heart-pulse me-2" style="color:#1a56db;"></i>Daftar Posyandu</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $posyandu->total() }} posyandu ditemukan</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama Posyandu</th>
                        <th>Kode</th>
                        <th>Lokasi</th>
                        <th>RW</th>
                        <th>Jenis</th>
                        <th>Kader / Balita</th>
                        <th>Ketua Kader</th>
                        <th>Status</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posyandu as $item)
                        <tr>
                            <td class="ps-4">
                                <div style="font-weight:600;color:#0f172a;">{{ $item->nama }}</div>
                                @if ($item->jadwal_mendatang_count > 0)
                                    <div style="color:#0ea5e9;font-size:.72rem;">
                                        <i class="bi bi-calendar-check me-1"></i>{{ $item->jadwal_mendatang_count }} jadwal
                                        mendatang
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge" style="background:#eff6ff;color:#1e40af;font-family:monospace;">
                                    {{ $item->kode }}
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:.82rem;max-width:200px;">
                                {{ Str::limit($item->lokasi, 40) }}
                            </td>
                            <td style="color:#64748b;font-size:.82rem;">{{ $item->rw ?: '—' }}</td>
                            <td>
                                @php
                                    $jenisColor = match ($item->jenis) {
                                        'balita' => ['bg' => '#FEF9E7', 'fg' => '#d97706'],
                                        'lansia' => ['bg' => '#EEEDFE', 'fg' => '#7c3aed'],
                                        'terpadu' => ['bg' => '#E8F5EE', 'fg' => '#2d8659'],
                                    };
                                @endphp
                                <span class="status-badge"
                                    style="background:{{ $jenisColor['bg'] }};color:{{ $jenisColor['fg'] }};">
                                    {{ $item->jenis_label }}
                                </span>
                            </td>
                            <td style="color:#64748b;font-size:.82rem;">
                                <i class="bi bi-people-fill me-1"></i>{{ $item->jumlah_kader }} kader<br>
                                <i class="bi bi-emoji-smile me-1"></i>{{ $item->jumlah_balita }}
                                {{ $item->jenis === 'lansia' ? 'lansia' : 'balita' }}
                            </td>
                            <td style="color:#64748b;font-size:.82rem;">
                                {{ $item->ketua_kader ?: '—' }}
                                @if ($item->kontak)
                                    <div style="color:#94a3b8;font-size:.72rem;">
                                        <i class="bi bi-whatsapp"></i> {{ $item->kontak }}
                                    </div>
                                @endif
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
                                    <a href="{{ route('admin.posyandu.edit', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;"
                                        title="Edit"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.posyandu.destroy', $item) }}"
                                        onsubmit="return confirm('Yakin hapus posyandu {{ $item->nama }}? Jadwal terkait juga akan terhapus.')">
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
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-heart-pulse"></i></div>
                                    <div class="empty-title">Belum ada posyandu</div>
                                    <div class="empty-sub">Klik "Tambah Posyandu" untuk menambahkan data baru</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($posyandu->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                    {{ $posyandu->firstItem() }}–{{ $posyandu->lastItem() }} dari {{ $posyandu->total() }}</span>
                {{ $posyandu->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

@endsection
