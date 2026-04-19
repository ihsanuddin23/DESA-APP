@extends('layouts.app')
@section('title', 'Kelola Penduduk')
@section('page-title', 'Kelola Penduduk')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: .85rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: .75rem;
            padding: 1rem 1.15rem;
            box-shadow: 0 1px 4px rgba(15, 23, 42, .04);
            display: flex;
            align-items: center;
            gap: .85rem;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: .55rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .stat-label {
            font-size: .72rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        .stat-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            font-family: 'JetBrains Mono', monospace;
        }

        .filter-advanced {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: .65rem;
            padding: 1rem 1.15rem;
            margin-bottom: 1rem;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: .65rem;
        }

        .filter-grid>* {
            min-width: 0;
        }

        .avatar-penduduk {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .78rem;
            flex-shrink: 0;
        }

        .avatar-penduduk.female {
            background: linear-gradient(135deg, #fce7f3, #fbcfe8);
            color: #9d174d;
        }

        .nik-mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: .78rem;
            color: #64748b;
            letter-spacing: .02em;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>
                @if (auth()->user()->isRt())
                    Warga RT {{ auth()->user()->rt }}{{ auth()->user()->rw ? ' / RW ' . auth()->user()->rw : '' }}
                @elseif(auth()->user()->isRw())
                    Warga RW {{ auth()->user()->rw }}
                @else
                    Kelola Penduduk
                @endif
            </h5>
            <div class="sub">{{ $scopeInfo ?? 'Data warga ' . config('sid.nama_desa', 'Desa') }}</div>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            {{-- Export Dropdown --}}
            <div class="dropdown">
                <button class="btn-primary-sm" type="button" data-bs-toggle="dropdown"
                    style="background:#10b981;border-color:#10b981;">
                    <i class="bi bi-download"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="font-size:.85rem;">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.export.penduduk.excel', request()->all()) }}">
                            <i class="bi bi-file-earmark-excel-fill me-2" style="color:#10b981;"></i>
                            Download Excel (.xlsx)
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.export.penduduk.pdf', request()->all()) }}">
                            <i class="bi bi-file-earmark-pdf-fill me-2" style="color:#dc2626;"></i>
                            Download PDF
                        </a>
                    </li>
                </ul>
            </div>
            <a href="{{ route('admin.penduduk.create') }}" class="btn-primary-sm">
                <i class="bi bi-person-plus-fill"></i> Tambah Penduduk
            </a>
        </div>
    </div>

    {{-- ── STATS CARDS ────────────────────────────────────────────────────────── --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff; color:#1e40af;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Penduduk</div>
                <div class="stat-value">{{ number_format($stats['total']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff; color:#1e40af;">
                <i class="bi bi-gender-male"></i>
            </div>
            <div>
                <div class="stat-label">Laki-laki</div>
                <div class="stat-value">{{ number_format($stats['laki_laki']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf2f8; color:#9d174d;">
                <i class="bi bi-gender-female"></i>
            </div>
            <div>
                <div class="stat-label">Perempuan</div>
                <div class="stat-value">{{ number_format($stats['perempuan']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#ecfdf5; color:#065f46;">
                <i class="bi bi-house-heart-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total KK</div>
                <div class="stat-value">{{ number_format($stats['total_kk']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7; color:#92400e;">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total RT</div>
                <div class="stat-value">{{ $stats['total_rt'] }}</div>
            </div>
        </div>
    </div>

    {{-- ── FILTER BAR ─────────────────────────────────────────────────────────── --}}
    <form method="GET" class="filter-advanced">
        <div class="filter-grid">
            <div class="search-wrap">
                <i class="bi bi-search si"></i>
                <input type="text" name="search" class="filter-input" placeholder="Cari nama, NIK, atau no. KK..."
                    value="{{ request('search') }}">
            </div>

            @unless (auth()->user()->isRt())
                <select name="rt" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua RT</option>
                    @foreach ($daftarRt as $rt)
                        <option value="{{ $rt }}" {{ request('rt') === $rt ? 'selected' : '' }}>RT
                            {{ $rt }}</option>
                    @endforeach
                </select>
            @endunless

            <select name="jenis_kelamin" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Gender</option>
                <option value="L" {{ request('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ request('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>

            <select name="kelompok_usia" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Usia</option>
                <option value="anak" {{ request('kelompok_usia') === 'anak' ? 'selected' : '' }}>Anak (0-17)
                </option>
                <option value="produktif" {{ request('kelompok_usia') === 'produktif' ? 'selected' : '' }}>Produktif
                    (18-55)</option>
                <option value="lansia" {{ request('kelompok_usia') === 'lansia' ? 'selected' : '' }}>Lansia (55+)
                </option>
            </select>

            <select name="agama" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Agama</option>
                @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $a)
                    <option value="{{ $a }}" {{ request('agama') === $a ? 'selected' : '' }}>
                        {{ $a }}</option>
                @endforeach
            </select>

            <select name="status_perkawinan" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach (['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $s)
                    <option value="{{ $s }}" {{ request('status_perkawinan') === $s ? 'selected' : '' }}>
                        {{ $s }}</option>
                @endforeach
            </select>

            <select name="status_aktif" class="filter-select" onchange="this.form.submit()">
                <option value="">Aktif & Non-aktif</option>
                <option value="1" {{ request('status_aktif') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status_aktif') === '0' ? 'selected' : '' }}>Non-aktif</option>
            </select>

            <div style="display:flex; gap:.5rem;">
                <button type="submit" class="btn-primary-sm" style="flex-grow:1;"><i class="bi bi-funnel"></i>
                    Filter</button>
                @if (request()->hasAny(['search', 'rt', 'jenis_kelamin', 'kelompok_usia', 'agama', 'status_perkawinan', 'status_aktif']))
                    <a href="{{ route('admin.penduduk.index') }}" class="btn-primary-sm"
                        style="background:#f1f5f9; color:#64748b; padding:.5rem .85rem; text-decoration:none;"
                        title="Reset filter">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- ── TABEL PENDUDUK ──────────────────────────────────────────────────────── --}}
    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-person-lines-fill me-2" style="color:#1a56db;"></i>Daftar Penduduk</h6>
            <span style="font-size:.78rem;color:#94a3b8;">{{ $penduduk->total() }} penduduk ditemukan</span>
        </div>
        <div class="table-responsive">
            <table class="table data-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama & NIK</th>
                        <th>L/P</th>
                        <th>Usia</th>
                        <th>Alamat</th>
                        <th>Pekerjaan</th>
                        <th>Status</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penduduk as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-penduduk {{ $item->jenis_kelamin === 'P' ? 'female' : '' }}">
                                        {{ strtoupper(substr($item->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;color:#0f172a;font-size:.85rem;">{{ $item->nama }}
                                        </div>
                                        <div class="nik-mono">
                                            NIK: {{ $item->nik }} · KK: {{ $item->no_kk }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($item->jenis_kelamin === 'L')
                                    <span class="status-badge" style="background:#eff6ff;color:#1e40af;"><i
                                            class="bi bi-gender-male"></i>L</span>
                                @else
                                    <span class="status-badge" style="background:#fdf2f8;color:#9d174d;"><i
                                            class="bi bi-gender-female"></i>P</span>
                                @endif
                            </td>
                            <td style="color:#334155;font-size:.85rem;font-weight:600;">{{ $item->usia }} thn</td>
                            <td style="color:#64748b;font-size:.8rem;">
                                <div>RT {{ $item->rt }}{{ $item->rw ? ' / RW ' . $item->rw : '' }}</div>
                                @if ($item->alamat)
                                    <div style="font-size:.72rem;color:#94a3b8;">{{ Str::limit($item->alamat, 40) }}</div>
                                @endif
                            </td>
                            <td style="color:#64748b;font-size:.82rem;">{{ $item->pekerjaan ?? '—' }}</td>
                            <td>
                                @if ($item->status_aktif)
                                    <span class="status-badge badge-success"><i class="bi bi-circle-fill"
                                            style="font-size:.4rem;"></i>Aktif</span>
                                @else
                                    <span class="status-badge badge-gray"><i class="bi bi-circle-fill"
                                            style="font-size:.4rem;"></i>Non-aktif</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-1">
                                    <a href="{{ route('admin.penduduk.show', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#475569;text-decoration:none;font-size:.82rem;display:inline-flex;align-items:center;gap:.3rem;"
                                        title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.penduduk.edit', $item) }}"
                                        style="padding:.35rem .6rem;border:1.5px solid #e2e8f0;border-radius:.45rem;color:#1a56db;text-decoration:none;font-size:.82rem;display:inline-flex;align-items:center;gap:.3rem;"
                                        title="Edit"><i class="bi bi-pencil"></i></a>
                                    @if (auth()->user()->canDeletePenduduk())
                                        <form method="POST" action="{{ route('admin.penduduk.destroy', $item) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus data {{ $item->nama }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                style="padding:.35rem .6rem;border:1.5px solid #fca5a5;border-radius:.45rem;background:#fff5f5;color:#dc2626;font-size:.82rem;cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;"
                                                title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="bi bi-people"></i></div>
                                    <div class="empty-title">
                                        @if (request()->hasAny(['search', 'rt', 'jenis_kelamin', 'kelompok_usia', 'agama', 'status_perkawinan']))
                                            Tidak ada hasil
                                        @else
                                            Belum ada data penduduk
                                        @endif
                                    </div>
                                    <div class="empty-sub">
                                        @if (request()->hasAny(['search', 'rt', 'jenis_kelamin', 'kelompok_usia', 'agama', 'status_perkawinan']))
                                            Coba ubah filter atau kata kunci pencarian
                                        @else
                                            Klik "Tambah Penduduk" untuk menambahkan data baru
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($penduduk->hasPages())
            <div
                style="padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem;">
                <span style="font-size:.78rem;color:#94a3b8;">Menampilkan
                    {{ $penduduk->firstItem() }}–{{ $penduduk->lastItem() }} dari {{ $penduduk->total() }}</span>
                {{ $penduduk->links() }}
            </div>
        @endif
    </div>

@endsection
