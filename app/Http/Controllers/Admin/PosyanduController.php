<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Posyandu;
use App\Models\PosyanduJadwal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PosyanduController extends Controller
{
    // ══════════════════════════════════════════════════════════════════════════
    // PUBLIC (halaman /posyandu — tidak perlu login)
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Halaman publik posyandu — menampilkan semua posyandu aktif
     * beserta jadwal mendatang.
     */
    public function public(): View
    {
        $posyandu = Posyandu::where('status', 'aktif')
                        ->orderBy('rw')
                        ->orderBy('nama')
                        ->get();

        // Jadwal mendatang (30 hari ke depan)
        $jadwalMendatang = PosyanduJadwal::with('posyandu')
                            ->mendatang()
                            ->where('tanggal', '<=', now()->addDays(30)->toDateString())
                            ->orderBy('tanggal')
                            ->orderBy('waktu_mulai')
                            ->get();

        // Statistik ringkasan
        $totalPosyandu  = $posyandu->count();
        $totalBalita    = $posyandu->where('jenis', '!=', 'lansia')->sum('jumlah_balita');
        $totalKader     = $posyandu->sum('jumlah_kader');
        $jadwalBulanIni = PosyanduJadwal::whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year)
                            ->count();

        return view('posyandu', compact(
            'posyandu',
            'jadwalMendatang',
            'totalPosyandu',
            'totalBalita',
            'totalKader',
            'jadwalBulanIni'
        ));
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ADMIN — POSYANDU (master data)
    // ══════════════════════════════════════════════════════════════════════════

    public function index(Request $request): View
    {
        $query = Posyandu::withCount(['jadwal', 'jadwalMendatang'])
                    ->latest();

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhere('kode', 'like', "%{$cari}%")
                  ->orWhere('lokasi', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }

        $posyandu = $query->paginate(15)->withQueryString();
        $rwList   = Posyandu::select('rw')->distinct()->whereNotNull('rw')->orderBy('rw')->pluck('rw');

        return view('admin.posyandu.index', compact('posyandu', 'rwList'));
    }

    public function create(): View
    {
        return view('admin.posyandu.form', [
            'posyandu' => null,
            'jenis'    => Posyandu::JENIS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama'          => 'required|string|max:100',
            'kode'          => 'required|string|max:20|unique:posyandu,kode',
            'lokasi'        => 'required|string|max:255',
            'rw'            => 'nullable|string|max:10',
            'jenis'         => 'required|in:' . implode(',', array_keys(Posyandu::JENIS)),
            'jumlah_kader'  => 'nullable|integer|min:0',
            'jumlah_balita' => 'nullable|integer|min:0',
            'ketua_kader'   => 'nullable|string|max:100',
            'kontak'        => 'nullable|string|max:20',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        Posyandu::create($data);

        return redirect()->route('admin.posyandu.index')
                         ->with('success', 'Posyandu berhasil ditambahkan.');
    }

    public function edit(Posyandu $posyandu): View
    {
        return view('admin.posyandu.form', [
            'posyandu' => $posyandu,
            'jenis'    => Posyandu::JENIS,
        ]);
    }

    public function update(Request $request, Posyandu $posyandu): RedirectResponse
    {
        $data = $request->validate([
            'nama'          => 'required|string|max:100',
            'kode'          => 'required|string|max:20|unique:posyandu,kode,' . $posyandu->id,
            'lokasi'        => 'required|string|max:255',
            'rw'            => 'nullable|string|max:10',
            'jenis'         => 'required|in:' . implode(',', array_keys(Posyandu::JENIS)),
            'jumlah_kader'  => 'nullable|integer|min:0',
            'jumlah_balita' => 'nullable|integer|min:0',
            'ketua_kader'   => 'nullable|string|max:100',
            'kontak'        => 'nullable|string|max:20',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $posyandu->update($data);

        return redirect()->route('admin.posyandu.index')
                         ->with('success', 'Data posyandu berhasil diperbarui.');
    }

    public function destroy(Posyandu $posyandu): RedirectResponse
    {
        $posyandu->delete();

        return redirect()->route('admin.posyandu.index')
                         ->with('success', 'Posyandu berhasil dihapus.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ADMIN — JADWAL
    // ══════════════════════════════════════════════════════════════════════════

    public function jadwalIndex(Request $request): View
    {
        $query = PosyanduJadwal::with('posyandu')->orderByDesc('tanggal');

        if ($request->filled('posyandu_id')) {
            $query->where('posyandu_id', $request->posyandu_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $jadwal   = $query->paginate(20)->withQueryString();
        $posyandu = Posyandu::aktif()->orderBy('nama')->get();

        return view('admin.posyandu.jadwal.index', compact('jadwal', 'posyandu'));
    }

    public function jadwalCreate(): View
    {
        $posyandu = Posyandu::aktif()->orderBy('nama')->get();

        return view('admin.posyandu.jadwal.form', [
            'jadwal'   => null,
            'posyandu' => $posyandu,
            'status'   => PosyanduJadwal::STATUS,
        ]);
    }

    public function jadwalStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'posyandu_id'   => 'required|exists:posyandu,id',
            'tanggal'       => 'required|date',
            'waktu_mulai'   => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'kegiatan'      => 'required|string|max:255',
            'catatan'       => 'nullable|string',
            'status'        => 'required|in:' . implode(',', array_keys(PosyanduJadwal::STATUS)),
        ]);

        $data['input_oleh'] = auth()->id();

        PosyanduJadwal::create($data);

        return redirect()->route('admin.posyandu.jadwal.index')
                         ->with('success', 'Jadwal posyandu berhasil ditambahkan.');
    }

    public function jadwalEdit(PosyanduJadwal $jadwal): View
    {
        $posyandu = Posyandu::aktif()->orderBy('nama')->get();

        return view('admin.posyandu.jadwal.form', [
            'jadwal'   => $jadwal,
            'posyandu' => $posyandu,
            'status'   => PosyanduJadwal::STATUS,
        ]);
    }

    public function jadwalUpdate(Request $request, PosyanduJadwal $jadwal): RedirectResponse
    {
        $data = $request->validate([
            'posyandu_id'   => 'required|exists:posyandu,id',
            'tanggal'       => 'required|date',
            'waktu_mulai'   => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'kegiatan'      => 'required|string|max:255',
            'catatan'       => 'nullable|string',
            'status'        => 'required|in:' . implode(',', array_keys(PosyanduJadwal::STATUS)),
        ]);

        $jadwal->update($data);

        return redirect()->route('admin.posyandu.jadwal.index')
                         ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function jadwalDestroy(PosyanduJadwal $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route('admin.posyandu.jadwal.index')
                         ->with('success', 'Jadwal berhasil dihapus.');
    }
}