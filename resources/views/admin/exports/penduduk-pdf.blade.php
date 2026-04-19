<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Penduduk</title>
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

        .header .location {
            font-size: 10px;
            color: #64748b;
            margin-top: 4px;
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
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .nik-mono {
            font-family: 'Courier New', monospace;
            font-size: 7.5px;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
            padding: 2px 6px;
            border-radius: 99px;
            font-size: 7px;
            font-weight: bold;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 99px;
            font-size: 7px;
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 8px;
            color: #64748b;
            display: table;
            width: 100%;
        }

        .footer-left,
        .footer-right {
            display: table-cell;
            vertical-align: top;
        }

        .footer-right {
            text-align: right;
        }

        .ttd-box {
            margin-top: 30px;
            width: 40%;
            margin-left: 55%;
            text-align: center;
            font-size: 9px;
        }

        .ttd-box .nama {
            margin-top: 50px;
            border-top: 1px solid #334155;
            padding-top: 3px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Laporan Data Penduduk</h1>
        <div class="subtitle">{{ strtoupper($nama_desa) }}</div>
        <div class="location">{{ $kecamatan }} · {{ $kabupaten }}</div>
    </div>

    <div class="info-box">
        <strong>Ruang Lingkup:</strong> {{ $scopeLabel }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Total:</strong> {{ $penduduk->count() }} jiwa
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Dicetak:</strong> {{ $tanggal }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Operator:</strong> {{ $user->name }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%;" class="text-center">No</th>
                <th style="width:12%;">NIK</th>
                <th style="width:20%;">Nama</th>
                <th style="width:3%;" class="text-center">L/P</th>
                <th style="width:4%;" class="text-center">Usia</th>
                <th style="width:8%;">Agama</th>
                <th style="width:10%;">Pekerjaan</th>
                <th style="width:9%;">Status Kawin</th>
                <th style="width:4%;" class="text-center">RT</th>
                <th style="width:4%;" class="text-center">RW</th>
                <th style="width:17%;">Alamat</th>
                <th style="width:6%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penduduk as $i => $p)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td class="nik-mono">{{ $p->nik }}</td>
                    <td>{{ $p->nama }}</td>
                    <td class="text-center">{{ $p->jenis_kelamin }}</td>
                    <td class="text-center">{{ $p->usia }}</td>
                    <td>{{ $p->agama }}</td>
                    <td>{{ $p->pekerjaan ?? '—' }}</td>
                    <td>{{ $p->status_perkawinan }}</td>
                    <td class="text-center">{{ $p->rt }}</td>
                    <td class="text-center">{{ $p->rw ?? '—' }}</td>
                    <td>{{ $p->alamat ?? '—' }}</td>
                    <td class="text-center">
                        @if ($p->status_aktif)
                            <span class="status-active">AKTIF</span>
                        @else
                            <span class="status-inactive">NON</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align:center; padding: 20px; color: #94a3b8;">
                        Tidak ada data penduduk yang cocok dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-box">
        <div>{{ $nama_desa }}, {{ now()->isoFormat('D MMMM YYYY') }}</div>
        <div>Dicetak oleh,</div>
        <div class="nama">{{ $user->name }}</div>
        <div>{{ $user->role_label }}</div>
    </div>

    <div class="footer">
        <div class="footer-left">
            Dokumen ini dicetak otomatis dari SID {{ $nama_desa }}.
        </div>
        <div class="footer-right">
            Halaman <span class="pagenum"></span>
        </div>
    </div>

</body>

</html>
