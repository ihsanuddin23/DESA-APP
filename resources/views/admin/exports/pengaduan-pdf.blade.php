<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan Warga</title>
    <style>
        @page {
            margin: 1.5cm 1cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 9px;
            color: #1f2937;
        }

        .header {
            text-align: center;
            border-bottom: 2.5px solid #1a56db;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header h1 {
            color: #1e40af;
            font-size: 18px;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .subtitle {
            font-size: 13px;
            color: #475569;
            margin: 0;
        }

        .info-box {
            background: #f1f5f9;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 12px;
            font-size: 9px;
            color: #334155;
            border-left: 3px solid #1a56db;
        }

        .info-box strong {
            color: #1e40af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        thead tr {
            background-color: #1a56db;
            color: white;
        }

        th {
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1e40af;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .kode-mono {
            font-family: 'Courier New', monospace;
            font-size: 7.5px;
            font-weight: bold;
            color: #1e40af;
        }

        .status-badge {
            padding: 2px 6px;
            border-radius: 99px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
        }

        .status-baru {
            background: #fef3c7;
            color: #92400e;
        }

        .status-diproses {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-selesai {
            background: #d1fae5;
            color: #065f46;
        }

        .status-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 8px;
            color: #64748b;
        }

        .stats-summary {
            display: table;
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }

        .stat-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            text-align: center;
            width: 25%;
        }

        .stat-cell strong {
            display: block;
            font-size: 16px;
            color: #1e40af;
        }

        .stat-cell .label {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Laporan Pengaduan Warga</h1>
        <div class="subtitle">{{ strtoupper($nama_desa) }}</div>
    </div>

    <div class="info-box">
        <strong>Dicetak:</strong> {{ $tanggal }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Total:</strong> {{ $pengaduan->count() }} pengaduan
        @if ($filters['status'] ?? null)
            &nbsp;|&nbsp;<strong>Status:</strong> {{ ucfirst($filters['status']) }}
        @endif
        @if ($filters['bulan'] ?? null)
            &nbsp;|&nbsp;<strong>Bulan:</strong> {{ $filters['bulan'] }}/{{ $filters['tahun'] ?? date('Y') }}
        @endif
    </div>

    {{-- Stats Summary --}}
    @php
        $statusCounts = $pengaduan->countBy('status');
    @endphp
    <div class="stats-summary">
        <div class="stat-cell">
            <strong>{{ $pengaduan->count() }}</strong>
            <div class="label">Total</div>
        </div>
        <div class="stat-cell">
            <strong style="color:#92400e;">{{ $statusCounts['baru'] ?? 0 }}</strong>
            <div class="label">Baru</div>
        </div>
        <div class="stat-cell">
            <strong style="color:#1e40af;">{{ $statusCounts['diproses'] ?? 0 }}</strong>
            <div class="label">Diproses</div>
        </div>
        <div class="stat-cell">
            <strong style="color:#065f46;">{{ $statusCounts['selesai'] ?? 0 }}</strong>
            <div class="label">Selesai</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%;" class="text-center">No</th>
                <th style="width:11%;">Kode Tiket</th>
                <th style="width:9%;">Tanggal</th>
                <th style="width:13%;">Pengadu</th>
                <th style="width:9%;">Kategori</th>
                <th style="width:22%;">Judul</th>
                <th style="width:8%;" class="text-center">Prioritas</th>
                <th style="width:9%;" class="text-center">Status</th>
                <th style="width:16%;">Ditangani Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengaduan as $i => $p)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td><span class="kode-mono">{{ $p->kode_tiket }}</span></td>
                    <td>{{ $p->created_at->format('d-m-Y') }}<br><small
                            style="color:#94a3b8;">{{ $p->created_at->format('H:i') }}</small></td>
                    <td>
                        <strong>{{ $p->nama_pengadu }}</strong>
                        @if ($p->rt || $p->rw)
                            <br><small>RT {{ $p->rt ?? '-' }}/RW {{ $p->rw ?? '-' }}</small>
                        @endif
                    </td>
                    <td>{{ $p->kategori_label }}</td>
                    <td>{{ Str::limit($p->judul, 80) }}</td>
                    <td class="text-center" style="text-transform: capitalize;">{{ $p->prioritas }}</td>
                    <td class="text-center">
                        <span class="status-badge status-{{ $p->status }}">
                            {{ strtoupper($p->status_label) }}
                        </span>
                    </td>
                    <td>
                        {{ $p->penanganan?->name ?? '—' }}
                        @if ($p->ditanggapi_pada)
                            <br><small style="color:#94a3b8;">{{ $p->ditanggapi_pada->format('d-m-Y H:i') }}</small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding: 20px; color: #94a3b8;">
                        Tidak ada data pengaduan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dicetak otomatis dari SID {{ $nama_desa }} oleh {{ auth()->user()->name }}.
    </div>

</body>

</html>
