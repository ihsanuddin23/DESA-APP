@extends('layouts.public')

@section('title', 'APBDes ' . $tahun)

@push('styles')
    <style>
        .apbdes-summary-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .apbdes-summary-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }

        .apbdes-summary-card .label {
            font-size: .85rem;
            color: #64748b;
            margin-bottom: .25rem;
        }

        .apbdes-summary-card .value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .apbdes-section {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .apbdes-section-header {
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .apbdes-section-header h5 {
            margin: 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .apbdes-table {
            width: 100%;
            border-collapse: collapse;
        }

        .apbdes-table th {
            background: #f8fafc;
            padding: .75rem 1rem;
            font-size: .75rem;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 600;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .apbdes-table td {
            padding: .75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: .9rem;
        }

        .apbdes-table tr:last-child td {
            border-bottom: none;
        }

        .progress-slim {
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-slim-bar {
            height: 100%;
            border-radius: 3px;
        }
    </style>
@endpush

@section('content')

    {{-- Hero Section --}}
    <section class="sid-hero">
        <div class="container">
            <div class="sid-hero-inner text-center">
                <span class="sid-hero-badge">
                    <i class="bi bi-wallet2 me-1"></i>
                    Transparansi Anggaran
                </span>
                <h1 class="sid-hero-title">
                    APBDes <em>{{ $tahun }}</em>
                </h1>
                <p class="sid-hero-lead">
                    Anggaran Pendapatan dan Belanja Desa {{ config('sid.nama_desa') }} Tahun {{ $tahun }}.
                    Keterbukaan informasi untuk mewujudkan tata kelola desa yang transparan dan akuntabel.
                </p>

                {{-- Pilih Tahun --}}
                @if ($tahunList->count() > 1)
                    <form method="GET" class="mt-4 d-inline-block">
                        <select name="tahun" class="form-select form-select-sm d-inline-block w-auto"
                            onchange="this.form.submit()">
                            @foreach ($tahunList as $thn)
                                <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>Tahun
                                    {{ $thn }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </div>
        </div>
    </section>

    @if ($apbdes)
        {{-- Summary Cards --}}
        <section class="sid-section sid-section-putih">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="apbdes-summary-card">
                            <div class="icon" style="background:#dcfce7;color:#166534;">
                                <i class="bi bi-arrow-down-circle"></i>
                            </div>
                            <div class="label">Total Pendapatan</div>
                            <div class="value" style="color:#166534;">Rp
                                {{ number_format($apbdes->total_pendapatan, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="apbdes-summary-card">
                            <div class="icon" style="background:#fee2e2;color:#dc2626;">
                                <i class="bi bi-arrow-up-circle"></i>
                            </div>
                            <div class="label">Total Belanja</div>
                            <div class="value" style="color:#dc2626;">Rp
                                {{ number_format($apbdes->total_belanja, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="apbdes-summary-card">
                            <div class="icon" style="background:#dbeafe;color:#2563eb;">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                            <div class="label">Pembiayaan</div>
                            <div class="value" style="color:#2563eb;">Rp
                                {{ number_format($apbdes->total_pembiayaan, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="apbdes-summary-card">
                            <div class="icon"
                                style="background:{{ $apbdes->sisa_anggaran >= 0 ? '#dcfce7' : '#fee2e2' }};color:{{ $apbdes->sisa_anggaran >= 0 ? '#166534' : '#dc2626' }};">
                                <i class="bi bi-calculator"></i>
                            </div>
                            <div class="label">Sisa Anggaran</div>
                            <div class="value" style="color:{{ $apbdes->sisa_anggaran >= 0 ? '#166534' : '#dc2626' }};">Rp
                                {{ number_format($apbdes->sisa_anggaran, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Detail Sections --}}
        <section class="sid-section" style="background:#f8fafc;">
            <div class="container">

                {{-- Pendapatan --}}
                <div class="apbdes-section">
                    <div class="apbdes-section-header" style="background:#f0fdf4;">
                        <h5 style="color:#166534;"><i class="bi bi-arrow-down-circle"></i> Pendapatan</h5>
                        <span style="color:#166534;font-weight:700;">Rp
                            {{ number_format($apbdes->total_pendapatan, 0, ',', '.') }}</span>
                    </div>
                    <table class="apbdes-table">
                        <thead>
                            <tr>
                                <th style="width:120px;">Kode</th>
                                <th>Uraian</th>
                                <th style="width:180px;">Anggaran</th>
                                <th style="width:180px;">Realisasi</th>
                                <th style="width:100px;">Capaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($apbdes->pendapatan as $item)
                                <tr>
                                    <td><code
                                            style="background:#e0e7ff;color:#3730a3;padding:2px 6px;border-radius:4px;font-size:.8rem;">{{ $item->kode_rekening ?: '-' }}</code>
                                    </td>
                                    <td>
                                        <div style="font-weight:500;">{{ $item->uraian }}</div>
                                        @if ($item->kategori)
                                            <div style="font-size:.75rem;color:#64748b;">{{ $item->kategori }}</div>
                                        @endif
                                    </td>
                                    <td style="font-weight:600;color:#166534;">Rp
                                        {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="progress-slim mb-1">
                                            <div class="progress-slim-bar"
                                                style="width:{{ min($item->persentase_realisasi, 100) }}%;background:#166534;">
                                            </div>
                                        </div>
                                        <span
                                            style="font-size:.75rem;color:#64748b;">{{ $item->persentase_realisasi }}%</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data pendapatan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Belanja --}}
                <div class="apbdes-section">
                    <div class="apbdes-section-header" style="background:#fef2f2;">
                        <h5 style="color:#dc2626;"><i class="bi bi-arrow-up-circle"></i> Belanja</h5>
                        <span style="color:#dc2626;font-weight:700;">Rp
                            {{ number_format($apbdes->total_belanja, 0, ',', '.') }}</span>
                    </div>
                    <table class="apbdes-table">
                        <thead>
                            <tr>
                                <th style="width:120px;">Kode</th>
                                <th>Uraian</th>
                                <th style="width:180px;">Anggaran</th>
                                <th style="width:180px;">Realisasi</th>
                                <th style="width:100px;">Capaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($apbdes->belanja as $item)
                                <tr>
                                    <td><code
                                            style="background:#e0e7ff;color:#3730a3;padding:2px 6px;border-radius:4px;font-size:.8rem;">{{ $item->kode_rekening ?: '-' }}</code>
                                    </td>
                                    <td>
                                        <div style="font-weight:500;">{{ $item->uraian }}</div>
                                        @if ($item->kategori)
                                            <div style="font-size:.75rem;color:#64748b;">{{ $item->kategori }}</div>
                                        @endif
                                    </td>
                                    <td style="font-weight:600;color:#dc2626;">Rp
                                        {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="progress-slim mb-1">
                                            <div class="progress-slim-bar"
                                                style="width:{{ min($item->persentase_realisasi, 100) }}%;background:#dc2626;">
                                            </div>
                                        </div>
                                        <span
                                            style="font-size:.75rem;color:#64748b;">{{ $item->persentase_realisasi }}%</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data belanja</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pembiayaan --}}
                <div class="apbdes-section">
                    <div class="apbdes-section-header" style="background:#eff6ff;">
                        <h5 style="color:#2563eb;"><i class="bi bi-arrow-left-right"></i> Pembiayaan</h5>
                        <span style="color:#2563eb;font-weight:700;">Rp
                            {{ number_format($apbdes->total_pembiayaan, 0, ',', '.') }}</span>
                    </div>
                    <table class="apbdes-table">
                        <thead>
                            <tr>
                                <th style="width:120px;">Kode</th>
                                <th>Uraian</th>
                                <th style="width:180px;">Anggaran</th>
                                <th style="width:180px;">Realisasi</th>
                                <th style="width:100px;">Capaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($apbdes->pembiayaan as $item)
                                <tr>
                                    <td><code
                                            style="background:#e0e7ff;color:#3730a3;padding:2px 6px;border-radius:4px;font-size:.8rem;">{{ $item->kode_rekening ?: '-' }}</code>
                                    </td>
                                    <td>
                                        <div style="font-weight:500;">{{ $item->uraian }}</div>
                                        @if ($item->kategori)
                                            <div style="font-size:.75rem;color:#64748b;">{{ $item->kategori }}</div>
                                        @endif
                                    </td>
                                    <td style="font-weight:600;color:#2563eb;">Rp
                                        {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="progress-slim mb-1">
                                            <div class="progress-slim-bar"
                                                style="width:{{ min($item->persentase_realisasi, 100) }}%;background:#2563eb;">
                                            </div>
                                        </div>
                                        <span
                                            style="font-size:.75rem;color:#64748b;">{{ $item->persentase_realisasi }}%</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data pembiayaan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Disclaimer --}}
                <div class="p-4 rounded-3" style="background:#fffbeb;border:1px solid #fcd34d;">
                    <div class="d-flex gap-3">
                        <i class="bi bi-info-circle" style="color:#d97706;font-size:1.5rem;"></i>
                        <div>
                            <h6 class="fw-bold mb-1" style="color:#92400e;">Catatan</h6>
                            <p class="mb-0 small" style="color:#78350f;">
                                Data APBDes ini merupakan ringkasan anggaran dan realisasi yang dikelola oleh Pemerintah
                                Desa {{ config('sid.nama_desa') }}.
                                Untuk informasi lebih detail, silakan datang ke Kantor Desa atau hubungi perangkat desa
                                setempat.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    @else
        {{-- Empty State --}}
        <section class="sid-section sid-section-putih">
            <div class="container">
                <div class="text-center py-5">
                    <div style="font-size:4rem;color:#cbd5e1;margin-bottom:1rem;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h4 class="fw-bold text-muted">Data APBDes Tidak Tersedia</h4>
                    <p class="text-muted">Data APBDes untuk tahun {{ $tahun }} belum dipublikasikan atau masih
                        dalam proses penyusunan.</p>
                    @if ($tahunList->count() > 0)
                        <p class="text-muted">Silakan pilih tahun lain yang tersedia.</p>
                    @endif
                </div>
            </div>
        </section>
    @endif

@endsection
