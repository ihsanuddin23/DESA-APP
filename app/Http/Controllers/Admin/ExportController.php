<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\{Alignment, Border, Fill};
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    // ═══════════════════════════════════════════════════════════════════════
    // PENDUDUK — EXCEL
    // ═══════════════════════════════════════════════════════════════════════
    public function pendudukExcel(Request $request): StreamedResponse
    {
        $penduduk = $this->queryPenduduk($request);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Penduduk');

        // ── Header ──
        $headers = [
            'No', 'NIK', 'No. KK', 'Nama', 'L/P', 'Tempat Lahir', 'Tanggal Lahir',
            'Usia', 'Agama', 'Pendidikan', 'Pekerjaan', 'Status Perkawinan',
            'Status Hub. Keluarga', 'Kewarganegaraan', 'RT', 'RW', 'Alamat', 'Status',
        ];
        $sheet->fromArray($headers, null, 'A1');

        // ── Style Header ──
        $sheet->getStyle('A1:R1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A56DB'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '1E40AF']],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // ── Isi Data ──
        $row = 2;
        foreach ($penduduk as $i => $p) {
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValueExplicit("B{$row}", $p->nik, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("C{$row}", $p->no_kk, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("D{$row}", $p->nama);
            $sheet->setCellValue("E{$row}", $p->jenis_kelamin);
            $sheet->setCellValue("F{$row}", $p->tempat_lahir);
            $sheet->setCellValue("G{$row}", optional($p->tanggal_lahir)->format('d-m-Y'));
            $sheet->setCellValue("H{$row}", $p->usia);
            $sheet->setCellValue("I{$row}", $p->agama);
            $sheet->setCellValue("J{$row}", $p->pendidikan);
            $sheet->setCellValue("K{$row}", $p->pekerjaan);
            $sheet->setCellValue("L{$row}", $p->status_perkawinan);
            $sheet->setCellValue("M{$row}", $p->status_hubungan_keluarga);
            $sheet->setCellValue("N{$row}", $p->kewarganegaraan ?? 'WNI');
            $sheet->setCellValueExplicit("O{$row}", $p->rt, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("P{$row}", $p->rw, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("Q{$row}", $p->alamat);
            $sheet->setCellValue("R{$row}", $p->status_aktif ? 'Aktif' : 'Non-aktif');
            $row++;
        }

        // ── Column Widths ──
        $widths = [
            'A' => 5, 'B' => 20, 'C' => 20, 'D' => 30, 'E' => 6, 'F' => 18,
            'G' => 14, 'H' => 6, 'I' => 12, 'J' => 18, 'K' => 20, 'L' => 18,
            'M' => 22, 'N' => 16, 'O' => 6, 'P' => 6, 'Q' => 35, 'R' => 12,
        ];
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // ── Border Semua Cell Data ──
        $lastRow = $row - 1;
        if ($lastRow >= 2) {
            $sheet->getStyle("A2:R{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ]);

            // Center alignment kolom tertentu
            $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B2:C{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E2:E{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("H2:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("O2:P{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return $this->downloadSpreadsheet($spreadsheet, 'penduduk_' . date('Ymd_His') . '.xlsx');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PENDUDUK — PDF
    // ═══════════════════════════════════════════════════════════════════════
    public function pendudukPdf(Request $request)
    {
        $penduduk = $this->queryPenduduk($request);
        $user = auth()->user();

        $data = [
            'penduduk'   => $penduduk,
            'filters'    => $request->only(['rt', 'jenis_kelamin', 'agama', 'status_perkawinan', 'kelompok_usia']),
            'tanggal'    => now()->isoFormat('D MMMM YYYY, HH:mm'),
            'nama_desa'  => config('sid.nama_desa', 'Desa'),
            'kecamatan'  => config('sid.kecamatan', '—'),
            'kabupaten'  => config('sid.kabupaten', '—'),
            'user'       => $user,
            'scopeLabel' => $this->getScopeLabel($user),
        ];

        $pdf = Pdf::loadView('admin.exports.penduduk-pdf', $data)
                  ->setPaper('a4', 'landscape');

        return $pdf->download('penduduk_' . date('Ymd_His') . '.pdf');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PENGADUAN — EXCEL
    // ═══════════════════════════════════════════════════════════════════════
    public function pengaduanExcel(Request $request): StreamedResponse
    {
        $pengaduan = $this->queryPengaduan($request);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pengaduan Warga');

        $headers = [
            'No', 'Kode Tiket', 'Tanggal Masuk', 'Nama Pengadu', 'Kontak',
            'RT', 'RW', 'Kategori', 'Judul', 'Isi Aduan', 'Lokasi',
            'Prioritas', 'Status', 'Tanggapan', 'Ditangani Oleh', 'Tgl Tanggapan',
        ];
        $sheet->fromArray($headers, null, 'A1');

        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A56DB'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '1E40AF']],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        $row = 2;
        foreach ($pengaduan as $i => $p) {
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $p->kode_tiket);
            $sheet->setCellValue("C{$row}", $p->created_at->format('d-m-Y H:i'));
            $sheet->setCellValue("D{$row}", $p->nama_pengadu);
            $sheet->setCellValue("E{$row}", $p->kontak);
            $sheet->setCellValueExplicit("F{$row}", $p->rt, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit("G{$row}", $p->rw, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("H{$row}", $p->kategori_label);
            $sheet->setCellValue("I{$row}", $p->judul);
            $sheet->setCellValue("J{$row}", $p->isi);
            $sheet->setCellValue("K{$row}", $p->lokasi);
            $sheet->setCellValue("L{$row}", ucfirst($p->prioritas));
            $sheet->setCellValue("M{$row}", $p->status_label);
            $sheet->setCellValue("N{$row}", $p->tanggapan ?? '—');
            $sheet->setCellValue("O{$row}", $p->penanganan?->name ?? '—');
            $sheet->setCellValue("P{$row}", optional($p->ditanggapi_pada)->format('d-m-Y H:i') ?? '—');
            $row++;
        }

        $widths = [
            'A' => 5, 'B' => 22, 'C' => 18, 'D' => 25, 'E' => 20,
            'F' => 6, 'G' => 6, 'H' => 18, 'I' => 40, 'J' => 50,
            'K' => 30, 'L' => 12, 'M' => 15, 'N' => 45, 'O' => 22, 'P' => 18,
        ];
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        $lastRow = $row - 1;
        if ($lastRow >= 2) {
            $sheet->getStyle("A2:P{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ]);
        }

        return $this->downloadSpreadsheet($spreadsheet, 'pengaduan_' . date('Ymd_His') . '.xlsx');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PENGADUAN — PDF
    // ═══════════════════════════════════════════════════════════════════════
    public function pengaduanPdf(Request $request)
    {
        $pengaduan = $this->queryPengaduan($request);

        $data = [
            'pengaduan' => $pengaduan,
            'filters'   => $request->only(['status', 'kategori', 'prioritas', 'bulan', 'tahun']),
            'tanggal'   => now()->isoFormat('D MMMM YYYY, HH:mm'),
            'nama_desa' => config('sid.nama_desa', 'Desa'),
        ];

        $pdf = Pdf::loadView('admin.exports.pengaduan-pdf', $data)
                  ->setPaper('a4', 'landscape');

        return $pdf->download('pengaduan_' . date('Ymd_His') . '.pdf');
    }

    // ═══════════════════════════════════════════════════════════════════════
    // HELPER METHODS
    // ═══════════════════════════════════════════════════════════════════════

    /**
     * Query penduduk dengan filter + scope by role.
     */
    private function queryPenduduk(Request $request)
    {
        $query = Penduduk::query();
        $user = auth()->user();

        // Scope by role
        if ($user->isRt()) {
            $query->where('rt', $user->rt);
            if ($user->rw) $query->where('rw', $user->rw);
        } elseif ($user->isRw()) {
            $query->where('rw', $user->rw);
        }

        // Apply filter
        $query->when($request->rt,                fn($q, $v) => $q->where('rt', $v))
              ->when($request->jenis_kelamin,     fn($q, $v) => $q->where('jenis_kelamin', $v))
              ->when($request->agama,             fn($q, $v) => $q->where('agama', $v))
              ->when($request->status_perkawinan, fn($q, $v) => $q->where('status_perkawinan', $v));

        if ($request->filled('kelompok_usia')) {
            match ($request->kelompok_usia) {
                'anak'      => $query->whereBetween('usia', [0, 17]),
                'produktif' => $query->whereBetween('usia', [18, 55]),
                'lansia'    => $query->where('usia', '>', 55),
                default     => null,
            };
        }

        return $query->orderBy('rt')->orderBy('no_kk')->orderBy('nama')->get();
    }

    /**
     * Query pengaduan dengan filter.
     */
    private function queryPengaduan(Request $request)
    {
        $query = Pengaduan::with('penanganan');

        $query->when($request->status,    fn($q, $v) => $q->where('status', $v))
              ->when($request->kategori,  fn($q, $v) => $q->where('kategori', $v))
              ->when($request->prioritas, fn($q, $v) => $q->where('prioritas', $v))
              ->when($request->bulan,     fn($q, $v) => $q->whereMonth('created_at', $v))
              ->when($request->tahun,     fn($q, $v) => $q->whereYear('created_at', $v));

        return $query->latest()->get();
    }

    /**
     * Stream download Excel file.
     */
    private function downloadSpreadsheet(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'max-age=0',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function getScopeLabel($user): string
    {
        if ($user->isRt()) return 'RT ' . $user->rt . ($user->rw ? ' / RW ' . $user->rw : '');
        if ($user->isRw()) return 'RW ' . $user->rw;
        return 'Seluruh Desa';
    }
}