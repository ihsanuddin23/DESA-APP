<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BansosProgram;
use App\Models\BansosPenerima;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BansosController extends Controller
{
    // ══════════════════════════════════════════════════════════════════════════
    // PUBLIC (halaman /bansos — tidak perlu login)
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Halaman publik daftar program bansos.
     * Hanya menampilkan program dengan status aktif.
     */
    public function public(): View
    {
        $programs = BansosProgram::where('status', 'aktif')
                        ->orderBy('jenis')
                        ->orderBy('nama')
                        ->get();

        $tahunIni = (int) date('Y');

        $totalProgram       = $programs->count();
        $totalPenerimaAktif = BansosPenerima::where('status', 'aktif')
                                ->where('tahun', $tahunIni)
                                ->count();
        $totalNominalTahun  = BansosPenerima::where('status', 'aktif')
                                ->where('tahun', $tahunIni)
                                ->sum('nominal');

        return view('bansos', compact(
            'programs',
            'totalProgram',
            'totalPenerimaAktif',
            'totalNominalTahun'
        ));
    }

    /**
     * Cek status penerima bansos (publik, pakai NIK + Nama).
     *
     * Prinsip keamanan:
     *  - Rate limiting via middleware throttle
     *  - Nama di-mask sebagian saat ditampilkan
     *  - Tidak mengungkap alamat, no_kk, atau nominal (cegah social engineering)
     *  - Pesan tidak-ketemu samar (cegah enumerasi NIK)
     */
    public function cekStatus(Request $request): View
    {
        $data = $request->validate([
            'nik'           => 'required|string|size:16|regex:/^[0-9]+$/',
            'nama_penerima' => 'required|string|min:3|max:100',
        ], [
            'nik.size'           => 'NIK harus 16 digit angka.',
            'nik.regex'          => 'NIK hanya boleh berisi angka.',
            'nama_penerima.min'  => 'Nama minimal 3 karakter.',
        ]);

        $nik  = trim($data['nik']);
        $nama = trim($data['nama_penerima']);

        // Query: cocokkan NIK, lalu cek kesamaan nama (case-insensitive)
        // Ambil semua record (bisa >1 kalau satu orang terdaftar di beberapa program)
        $rows = BansosPenerima::with('program')
                    ->where('nik', $nik)
                    ->whereRaw('LOWER(nama_penerima) = ?', [mb_strtolower($nama)])
                    ->orderByDesc('tahun')
                    ->orderByDesc('created_at')
                    ->get();

        // Reload data publik dasar (untuk render halaman utuh)
        $programs = BansosProgram::where('status', 'aktif')
                        ->orderBy('jenis')
                        ->orderBy('nama')
                        ->get();

        $tahunIni           = (int) date('Y');
        $totalProgram       = $programs->count();
        $totalPenerimaAktif = BansosPenerima::where('status', 'aktif')
                                ->where('tahun', $tahunIni)
                                ->count();
        $totalNominalTahun  = BansosPenerima::where('status', 'aktif')
                                ->where('tahun', $tahunIni)
                                ->sum('nominal');

        // Siapkan hasil cek
        if ($rows->isEmpty()) {
            $cekHasil = [
                'status'  => 'tidak_ditemukan',
                'nik'     => $this->maskNik($nik),
                'pesan'   => 'Data dengan NIK dan nama tersebut tidak ditemukan dalam daftar penerima bansos.',
                'records' => collect(),
            ];
        } else {
            // Cek apakah ada record aktif tahun berjalan
            $adaAktifTahunIni = $rows->where('status', 'aktif')
                                     ->where('tahun', $tahunIni)
                                     ->isNotEmpty();

            $cekHasil = [
                'status'   => $adaAktifTahunIni ? 'aktif' : 'riwayat',
                'nik'      => $this->maskNik($nik),
                'nama'     => $this->maskNama($rows->first()->nama_penerima),
                'pesan'    => $adaAktifTahunIni
                                ? 'Nama Anda terdaftar sebagai penerima bansos aktif tahun ' . $tahunIni . '.'
                                : 'Data ditemukan, namun tidak ada program aktif tahun ' . $tahunIni . '.',
                'records'  => $rows,
                'tahunIni' => $tahunIni,
            ];
        }

        return view('bansos', compact(
            'programs',
            'totalProgram',
            'totalPenerimaAktif',
            'totalNominalTahun',
            'cekHasil'
        ));
    }

    /**
     * Mask NIK: tampilkan 4 digit awal + 4 digit akhir.
     * contoh: 3201012001800001 → 3201********0001
     */
    private function maskNik(string $nik): string
    {
        if (strlen($nik) !== 16) return $nik;
        return substr($nik, 0, 4) . str_repeat('*', 8) . substr($nik, -4);
    }

    /**
     * Mask nama: tampilkan 2 karakter awal tiap kata + ****.
     * contoh: "Siti Aminah" → "Si** Am****"
     */
    private function maskNama(string $nama): string
    {
        $parts = preg_split('/\s+/', trim($nama));
        $masked = array_map(function ($p) {
            if (mb_strlen($p) <= 2) return $p;
            return mb_substr($p, 0, 2) . str_repeat('*', max(mb_strlen($p) - 2, 2));
        }, $parts);
        return implode(' ', $masked);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // PROGRAM
    // ══════════════════════════════════════════════════════════════════════════

    public function programIndex(): View
    {
        $programs = BansosProgram::withCount('penerimaAktif')
                        ->latest()
                        ->paginate(15);

        return view('admin.bansos.program.index', compact('programs'));
    }

    public function programCreate(): View
    {
        return view('admin.bansos.program.form', [
            'program' => null,
            'jenis'   => BansosProgram::JENIS,
        ]);
    }

    public function programStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama'               => 'required|string|max:100',
            'kode'               => 'required|string|max:20|unique:bansos_program,kode',
            'deskripsi'          => 'nullable|string',
            'jenis'              => 'required|in:' . implode(',', array_keys(BansosProgram::JENIS)),
            'nominal_per_bulan'  => 'nullable|numeric|min:0',
            'status'             => 'required|in:aktif,nonaktif',
        ]);

        BansosProgram::create($data);

        return redirect()->route('admin.bansos.program.index')
                         ->with('success', 'Program bansos berhasil ditambahkan.');
    }

    public function programEdit(BansosProgram $program): View
    {
        return view('admin.bansos.program.form', [
            'program' => $program,
            'jenis'   => BansosProgram::JENIS,
        ]);
    }

    public function programUpdate(Request $request, BansosProgram $program): RedirectResponse
    {
        $data = $request->validate([
            'nama'               => 'required|string|max:100',
            'kode'               => 'required|string|max:20|unique:bansos_program,kode,' . $program->id,
            'deskripsi'          => 'nullable|string',
            'jenis'              => 'required|in:' . implode(',', array_keys(BansosProgram::JENIS)),
            'nominal_per_bulan'  => 'nullable|numeric|min:0',
            'status'             => 'required|in:aktif,nonaktif',
        ]);

        $program->update($data);

        return redirect()->route('admin.bansos.program.index')
                         ->with('success', 'Program bansos berhasil diperbarui.');
    }

    public function programDestroy(BansosProgram $program): RedirectResponse
    {
        $program->delete();

        return redirect()->route('admin.bansos.program.index')
                         ->with('success', 'Program bansos berhasil dihapus.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // PENERIMA
    // ══════════════════════════════════════════════════════════════════════════

    public function penerimaIndex(Request $request): View
    {
        $query = BansosPenerima::with('program')
                    ->latest();

        // Filter program
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian NIK / nama
        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama_penerima', 'like', "%{$cari}%")
                  ->orWhere('nik', 'like', "%{$cari}%");
            });
        }

        $penerima  = $query->paginate(20)->withQueryString();
        $programs  = BansosProgram::aktif()->orderBy('nama')->get();
        $tahunList = BansosPenerima::selectRaw('DISTINCT tahun')
                        ->orderByDesc('tahun')
                        ->pluck('tahun');

        return view('admin.bansos.penerima.index', compact(
            'penerima', 'programs', 'tahunList'
        ));
    }

    public function penerimaCreate(): View
    {
        $programs = BansosProgram::aktif()->orderBy('nama')->get();

        return view('admin.bansos.penerima.form', [
            'penerima' => null,
            'programs' => $programs,
            'status'   => BansosPenerima::STATUS,
            'periode'  => BansosPenerima::PERIODE,
        ]);
    }

    public function penerimaStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'program_id'    => 'required|exists:bansos_program,id',
            'nik'           => 'required|string|size:16',
            'nama_penerima' => 'required|string|max:100',
            'no_kk'         => 'nullable|string|size:16',
            'rt'            => 'nullable|string|max:3',
            'rw'            => 'nullable|string|max:3',
            'alamat'        => 'nullable|string|max:255',
            'tahun'         => 'required|integer|min:2000|max:2100',
            'periode'       => 'required|in:' . implode(',', array_keys(BansosPenerima::PERIODE)),
            'nominal'       => 'nullable|numeric|min:0',
            'status'        => 'required|in:aktif,nonaktif,dicoret',
            'keterangan'    => 'nullable|string',
        ]);

        $data['input_oleh'] = auth()->id();

        BansosPenerima::create($data);

        return redirect()->route('admin.bansos.penerima.index')
                         ->with('success', 'Data penerima bansos berhasil ditambahkan.');
    }

    public function penerimaEdit(BansosPenerima $penerima): View
    {
        $programs = BansosProgram::aktif()->orderBy('nama')->get();

        return view('admin.bansos.penerima.form', [
            'penerima' => $penerima,
            'programs' => $programs,
            'status'   => BansosPenerima::STATUS,
            'periode'  => BansosPenerima::PERIODE,
        ]);
    }

    public function penerimaUpdate(Request $request, BansosPenerima $penerima): RedirectResponse
    {
        $data = $request->validate([
            'program_id'    => 'required|exists:bansos_program,id',
            'nik'           => 'required|string|size:16',
            'nama_penerima' => 'required|string|max:100',
            'no_kk'         => 'nullable|string|size:16',
            'rt'            => 'nullable|string|max:3',
            'rw'            => 'nullable|string|max:3',
            'alamat'        => 'nullable|string|max:255',
            'tahun'         => 'required|integer|min:2000|max:2100',
            'periode'       => 'required|in:' . implode(',', array_keys(BansosPenerima::PERIODE)),
            'nominal'       => 'nullable|numeric|min:0',
            'status'        => 'required|in:aktif,nonaktif,dicoret',
            'keterangan'    => 'nullable|string',
        ]);

        $penerima->update($data);

        return redirect()->route('admin.bansos.penerima.index')
                         ->with('success', 'Data penerima bansos berhasil diperbarui.');
    }

    public function penerimaDestroy(BansosPenerima $penerima): RedirectResponse
    {
        $penerima->delete();

        return redirect()->route('admin.bansos.penerima.index')
                         ->with('success', 'Data penerima bansos berhasil dihapus.');
    }
}
