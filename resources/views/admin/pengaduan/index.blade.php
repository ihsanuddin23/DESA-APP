@extends('layouts.app')
@section('title', 'Kelola Pengaduan')
@section('page-title', 'Kelola Pengaduan Warga')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: .85rem;
            padding: 1.25rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: .6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-label {
            font-size: .72rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            font-family: 'JetBrains Mono', monospace;
        }

        .filter-bar {
            background: #fff;
            border-radius: .75rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 4px rgba(15, 23, 42, .04);
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-bar .search-input {
            flex-grow: 1;
            min-width: 240px;
            padding: .55rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: .5rem;
            font-size: .85rem;
        }

        .filter-bar .filter-select {
            padding: .55rem .85rem;
            border: 1.5px solid #e2e8f0;
            border-radius: .5rem;
            font-size: .85rem;
            background: white;
        }

        .status-badge {
            display: inline-block;
            padding: .2rem .6rem;
            font-size: .72rem;
            font-weight: 600;
            border-radius: 99px;
        }

        .status-badge.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.info {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .prioritas-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: .35rem;
        }

        .prioritas-dot.tinggi {
            background: #dc2626;
        }

        .prioritas-dot.sedang {
            background: #f59e0b;
        }

        .prioritas-dot.rendah {
            background: #10b981;
        }

        .kode-tiket-mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: .75rem;
            color: #64748b;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Kelola Pengaduan Warga</h5>
            <div class="sub">Tanggapi aduan yang masuk dari warga</div>
        </div>
        <div class="dropdown">
            <button class="btn-primary-sm" type="button" data-bs-toggle="dropdown"
                style="background:#10b981;border-color:#10b981;">
                <i class="bi bi-download"></i> Export
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="font-size:.85rem;">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.export.pengaduan.excel', request()->all()) }}">
                        <i class="bi bi-file-earmark-excel-fill me-2" style="color:#10b981;"></i>
                        Download Excel (.xlsx)
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.export.pengaduan.pdf', request()->all()) }}">
                        <i class="bi bi-file-earmark-pdf-fill me-2" style="color:#dc2626;"></i>
                        Download PDF
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- ── Stats Cards ── --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f1f5f9;color:#475569;">
                <i class="bi bi-inbox-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Aduan</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#92400e;">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div>
                <div class="stat-label">Baru (Belum Ditangani)</div>
                <div class="stat-value">{{ $stats['baru'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;color:#1e40af;">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <div>
                <div class="stat-label">Sedang Diproses</div>
                <div class="stat-value">{{ $stats['diproses'] }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5;color:#065f46;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-label">Selesai</div>
                <div class="stat-value">{{ $stats['selesai'] }}</div>
            </div>
        </div>
    </div>

    {{-- ── Filter Bar ── --}}
    <form method="GET" action="{{ route('admin.pengaduan.index') }}" class="filter-bar">
        <input type="text" name="search" class="search-input" placeholder="Cari judul, nama, kode tiket..."
            value="{{ request('search') }}">

        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach (\App\Models\Pengaduan::STATUSES as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}
                </option>
            @endforeach
        </select>

        <select name="kategori" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach (\App\Models\Pengaduan::KATEGORI as $key => $label)
                <option value="{{ $key }}" {{ request('kategori') === $key ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>

        <select name="prioritas" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Prioritas</option>
            @foreach (\App\Models\Pengaduan::PRIORITAS as $key => $label)
                <option value="{{ $key }}" {{ request('prioritas') === $key ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn-primary-sm">
            <i class="bi bi-funnel-fill"></i> Filter
        </button>

        @if (request()->hasAny(['search', 'status', 'kategori', 'prioritas']))
            <a href="{{ route('admin.pengaduan.index') }}" style="color:#64748b;font-size:.82rem;text-decoration:none;">
                <i class="bi bi-x-circle"></i> Reset
            </a>
        @endif
    </form>

    {{-- ── Tabel Pengaduan ── --}}
    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-megaphone-fill me-2" style="color:#1a56db;"></i>Daftar Pengaduan</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $pengaduan->total() }} aduan ditemukan</span>
        </div>

        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Kode & Pengadu</th>
                        <th>Judul & Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Masuk</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduan as $p)
                        <tr>
                            <td class="ps-4">
                                <div class="kode-tiket-mono" style="font-weight:600;color:#1e40af;">{{ $p->kode_tiket }}
                                </div>
                                <div style="font-size:.82rem;color:#334155;font-weight:600;margin-top:.2rem;">
                                    {{ $p->nama_pengadu }}</div>
                                @if ($p->rt || $p->rw)
                                    <div style="font-size:.72rem;color:#94a3b8;">
                                        RT {{ $p->rt ?? '-' }} / RW {{ $p->rw ?? '-' }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600;color:#0f172a;font-size:.85rem;line-height:1.3;">
                                    {{ Str::limit($p->judul, 55) }}</div>
                                <div style="margin-top:.3rem;">
                                    <span class="status-badge" style="background:#f0f9ff;color:#075985;">
                                        {{ $p->kategori_label }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="prioritas-dot {{ $p->prioritas }}"></span>
                                <span
                                    style="font-size:.82rem;color:#334155;text-transform:capitalize;">{{ $p->prioritas }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $p->status_color }}">
                                    {{ $p->status_label }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size:.78rem;color:#475569;">{{ $p->created_at->diffForHumans() }}</div>
                                <div style="font-size:.7rem;color:#94a3b8;">{{ $p->created_at->isoFormat('D MMM YYYY') }}
                                </div>
                            </td>
                            <td class="pe-4">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.pengaduan.show', $p) }}"
                                        style="padding:.35rem .65rem;border:1.5px solid #1a56db;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;font-weight:500;display:inline-flex;align-items:center;gap:.3rem;">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    @if (auth()->user()->isAdmin())
                                        <form method="POST" action="{{ route('admin.pengaduan.destroy', $p) }}"
                                            onsubmit="return confirm('Yakin hapus aduan ini?')" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                style="padding:.35rem .6rem;border:1.5px solid #fca5a5;border-radius:.45rem;background:#fff5f5;color:#dc2626;cursor:pointer;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding:3rem 1rem;">
                                <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                                <div class="empty-title">Belum ada pengaduan</div>
                                <div class="empty-sub">Pengaduan dari warga akan muncul di sini</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengaduan->hasPages())
            <div class="pagination-wrap">
                <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                    {{ $pengaduan->firstItem() }}–{{ $pengaduan->lastItem() }} dari {{ $pengaduan->total() }}</span>
                {{ $pengaduan->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

@endsection
