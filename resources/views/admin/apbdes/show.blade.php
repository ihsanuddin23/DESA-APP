@extends('layouts.app')
@section('title', 'Detail APBDes ' . $apbdes->tahun)
@section('page-title', 'Detail APBDes')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .summary-card {
            background: #fff;
            border-radius: .75rem;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
        }

        .summary-card .label {
            font-size: .75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .summary-card .value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: .25rem;
        }

        .item-section {
            background: #fff;
            border-radius: .75rem;
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }

        .item-section-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-section-body {
            padding: 0;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>APBDes Tahun {{ $apbdes->tahun }}</h5>
            <div class="sub">Detail anggaran dan realisasi</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.apbdes.items.index', $apbdes) }}" class="btn-primary-sm">
                <i class="bi bi-list-ul"></i> Kelola Item
            </a>
            <a href="{{ route('admin.apbdes.index') }}" class="btn-secondary-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="summary-card" style="border-left: 4px solid #166534;">
                <div class="label">Total Pendapatan</div>
                <div class="value" style="color:#166534;">Rp {{ number_format($apbdes->total_pendapatan, 0, ',', '.') }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card" style="border-left: 4px solid #dc2626;">
                <div class="label">Total Belanja</div>
                <div class="value" style="color:#dc2626;">Rp {{ number_format($apbdes->total_belanja, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card" style="border-left: 4px solid #2563eb;">
                <div class="label">Pembiayaan</div>
                <div class="value" style="color:#2563eb;">Rp {{ number_format($apbdes->total_pembiayaan, 0, ',', '.') }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card"
                style="border-left: 4px solid {{ $apbdes->sisa_anggaran >= 0 ? '#166534' : '#dc2626' }};">
                <div class="label">Sisa Anggaran</div>
                <div class="value" style="color:{{ $apbdes->sisa_anggaran >= 0 ? '#166534' : '#dc2626' }};">Rp
                    {{ number_format($apbdes->sisa_anggaran, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Pendapatan --}}
    <div class="item-section">
        <div class="item-section-header" style="background:#f0fdf4;">
            <h6 class="mb-0" style="color:#166534;"><i class="bi bi-arrow-down-circle me-2"></i>Pendapatan</h6>
            <span style="font-size:.8rem;color:#166534;font-weight:600;">Rp
                {{ number_format($apbdes->total_pendapatan, 0, ',', '.') }}</span>
        </div>
        <div class="item-section-body">
            <table class="table mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th style="width:120px;">Kode</th>
                        <th>Uraian</th>
                        <th style="width:180px;">Anggaran</th>
                        <th style="width:180px;">Realisasi</th>
                        <th style="width:100px;">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($apbdes->pendapatan as $item)
                        <tr>
                            <td><code
                                    style="background:#e0e7ff;color:#3730a3;padding:2px 6px;border-radius:4px;">{{ $item->kode_rekening ?: '-' }}</code>
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $item->uraian }}</div>
                                @if ($item->kategori)
                                    <div style="font-size:.75rem;color:#64748b;">{{ $item->kategori }}</div>
                                @endif
                            </td>
                            <td style="font-weight:600;color:#166534;">Rp {{ number_format($item->anggaran, 0, ',', '.') }}
                            </td>
                            <td style="color:#0f172a;">Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                            <td>
                                <div class="progress" style="height:6px;background:#e2e8f0;">
                                    <div class="progress-bar"
                                        style="width:{{ min($item->persentase_realisasi, 100) }}%;background:#166534;">
                                    </div>
                                </div>
                                <span style="font-size:.75rem;color:#64748b;">{{ $item->persentase_realisasi }}%</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada item pendapatan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Belanja --}}
    <div class="item-section">
        <div class="item-section-header" style="background:#fef2f2;">
            <h6 class="mb-0" style="color:#dc2626;"><i class="bi bi-arrow-up-circle me-2"></i>Belanja</h6>
            <span style="font-size:.8rem;color:#dc2626;font-weight:600;">Rp
                {{ number_format($apbdes->total_belanja, 0, ',', '.') }}</span>
        </div>
        <div class="item-section-body">
            <table class="table mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th style="width:120px;">Kode</th>
                        <th>Uraian</th>
                        <th style="width:180px;">Anggaran</th>
                        <th style="width:180px;">Realisasi</th>
                        <th style="width:100px;">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($apbdes->belanja as $item)
                        <tr>
                            <td><code
                                    style="background:#e0e7ff;color:#3730a3;padding:2px 6px;border-radius:4px;">{{ $item->kode_rekening ?: '-' }}</code>
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $item->uraian }}</div>
                                @if ($item->kategori)
                                    <div style="font-size:.75rem;color:#64748b;">{{ $item->kategori }}</div>
                                @endif
                            </td>
                            <td style="font-weight:600;color:#dc2626;">Rp {{ number_format($item->anggaran, 0, ',', '.') }}
                            </td>
                            <td style="color:#0f172a;">Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                            <td>
                                <div class="progress" style="height:6px;background:#e2e8f0;">
                                    <div class="progress-bar"
                                        style="width:{{ min($item->persentase_realisasi, 100) }}%;background:#dc2626;">
                                    </div>
                                </div>
                                <span style="font-size:.75rem;color:#64748b;">{{ $item->persentase_realisasi }}%</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada item belanja</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pembiayaan --}}
    <div class="item-section">
        <div class="item-section-header" style="background:#eff6ff;">
            <h6 class="mb-0" style="color:#2563eb;"><i class="bi bi-arrow-left-right me-2"></i>Pembiayaan</h6>
            <span style="font-size:.8rem;color:#2563eb;font-weight:600;">Rp
                {{ number_format($apbdes->total_pembiayaan, 0, ',', '.') }}</span>
        </div>
        <div class="item-section-body">
            <table class="table mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th style="width:120px;">Kode</th>
                        <th>Uraian</th>
                        <th style="width:180px;">Anggaran</th>
                        <th style="width:180px;">Realisasi</th>
                        <th style="width:100px;">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($apbdes->pembiayaan as $item)
                        <tr>
                            <td><code
                                    style="background:#e0e7ff;color:#3730a3;padding:2px 6px;border-radius:4px;">{{ $item->kode_rekening ?: '-' }}</code>
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $item->uraian }}</div>
                                @if ($item->kategori)
                                    <div style="font-size:.75rem;color:#64748b;">{{ $item->kategori }}</div>
                                @endif
                            </td>
                            <td style="font-weight:600;color:#2563eb;">Rp
                                {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                            <td style="color:#0f172a;">Rp {{ number_format($item->realisasi, 0, ',', '.') }}</td>
                            <td>
                                <div class="progress" style="height:6px;background:#e2e8f0;">
                                    <div class="progress-bar"
                                        style="width:{{ min($item->persentase_realisasi, 100) }}%;background:#2563eb;">
                                    </div>
                                </div>
                                <span style="font-size:.75rem;color:#64748b;">{{ $item->persentase_realisasi }}%</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada item pembiayaan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
