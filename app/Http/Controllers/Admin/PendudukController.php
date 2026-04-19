<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\WilayahProvinsi;
use App\Models\WilayahKabkota;
use App\Models\WilayahKecamatan;
use App\Models\WilayahKelurahan;
use App\Notifications\PendudukDitambahkan;
use App\Notifications\PendudukDiperbarui;
use App\Notifications\PendudukDihapus;
use App\Services\NotificationService;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PendudukController extends Controller
{
    /**
     * Tampilkan daftar penduduk dengan filter lengkap.
     * Akses dibatasi berdasarkan role:
     *  - admin & staff_desa → lihat semua penduduk
     *  - rw → lihat penduduk di RW-nya saja
     *  - rt → lihat penduduk di RT-nya saja
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $query = Penduduk::query();

        // ── SCOPE BY ROLE (paling atas, tidak bisa di-bypass filter) ──
        if ($user->isRt()) {
            // RT hanya bisa lihat penduduk di RT-nya
            $query->where('rt', $user->rt);
            if ($user->rw) {
                $query->where('rw', $user->rw);
            }
        } elseif ($user->isRw()) {
            // RW hanya bisa lihat penduduk di RW-nya
            $query->where('rw', $user->rw);
        }
        // admin & staff_desa: tidak ada filter scope, lihat semua

        // ── Pencarian (nama, NIK, no_kk) ──────────────────────────────
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama',  'like', "%{$search}%")
                  ->orWhere('nik',   'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%");
            });
        }

        // ── Filter spesifik (RT hanya bisa filter di dalam RT-nya sendiri) ──
        $query->when($request->rt && !$user->isRt(), fn($q) => $q->where('rt', $request->rt))
              ->when($request->jenis_kelamin,    fn($q, $v) => $q->where('jenis_kelamin', $v))
              ->when($request->agama,            fn($q, $v) => $q->where('agama', $v))
              ->when($request->status_perkawinan,fn($q, $v) => $q->where('status_perkawinan', $v))
              ->when($request->status_aktif !== null && $request->status_aktif !== '',
                     fn($q) => $q->where('status_aktif', (bool) $request->status_aktif));

        // ── Filter kelompok usia ──────────────────────────────────────
        if ($request->filled('kelompok_usia')) {
            match ($request->kelompok_usia) {
                'anak'      => $query->whereBetween('usia', [0, 17]),
                'produktif' => $query->whereBetween('usia', [18, 55]),
                'lansia'    => $query->where('usia', '>', 55),
                default     => null,
            };
        }

        $penduduk = $query->orderBy('rt')
                          ->orderBy('no_kk')
                          ->orderBy('nama')
                          ->paginate(20)
                          ->withQueryString();

        // ── Stats ringkas (disesuaikan scope role) ────────────────────
        $statsQuery = Penduduk::query();
        if ($user->isRt()) {
            $statsQuery->where('rt', $user->rt);
            if ($user->rw) $statsQuery->where('rw', $user->rw);
        } elseif ($user->isRw()) {
            $statsQuery->where('rw', $user->rw);
        }

        $stats = [
            'total'      => (clone $statsQuery)->count(),
            'laki_laki'  => (clone $statsQuery)->where('jenis_kelamin', 'L')->count(),
            'perempuan'  => (clone $statsQuery)->where('jenis_kelamin', 'P')->count(),
            'total_kk'   => (clone $statsQuery)->distinct('no_kk')->count('no_kk'),
            'total_rt'   => (clone $statsQuery)->distinct('rt')->count('rt'),
        ];

        // ── Daftar RT untuk dropdown filter (sesuai scope) ────────────
        $daftarRtQuery = Penduduk::query();
        if ($user->isRw()) $daftarRtQuery->where('rw', $user->rw);
        $daftarRt = $daftarRtQuery->distinct()->orderBy('rt')->pluck('rt');

        // ── Info scope untuk header view ──────────────────────────────
        $scopeInfo = match (true) {
            $user->isRt()  => "Data warga RT {$user->rt}" . ($user->rw ? " / RW {$user->rw}" : ''),
            $user->isRw()  => "Data warga RW {$user->rw}",
            default        => 'Data warga ' . config('sid.nama_desa', 'Desa'),
        };

        return view('admin.penduduk.index', compact('penduduk', 'stats', 'daftarRt', 'scopeInfo'));
    }

    /**
     * Form tambah penduduk baru.
     *
     * Default wilayah di-set ke desa yang mengelola aplikasi ini
     * (berdasarkan config/sid.php → kode_*). Admin tinggal ubah
     * kalau penduduk berasal dari luar desa.
     */
    public function create(): View
    {
        $provinsi = WilayahProvinsi::orderBy('nama')->get();

        // ── Cari wilayah default berdasarkan kode di config ──
        $defaultProvinsi  = WilayahProvinsi::where('kode', config('sid.kode_provinsi'))->first();
        $defaultKabkota   = WilayahKabkota::where('kode', config('sid.kode_kabkota'))->first();
        $defaultKecamatan = WilayahKecamatan::where('kode', config('sid.kode_kecamatan'))->first();
        $defaultKelurahan = WilayahKelurahan::where('kode', config('sid.kode_kelurahan'))->first();

        // ── Pre-load daftar dropdown bertingkat sesuai default ──
        $kabkota = $defaultProvinsi
            ? WilayahKabkota::where('provinsi_id', $defaultProvinsi->id)->orderBy('nama')->get()
            : collect();

        $kecamatan = $defaultKabkota
            ? WilayahKecamatan::where('kabkota_id', $defaultKabkota->id)->orderBy('nama')->get()
            : collect();

        $kelurahan = $defaultKecamatan
            ? WilayahKelurahan::where('kecamatan_id', $defaultKecamatan->id)->orderBy('nama')->get()
            : collect();

        return view('admin.penduduk.create', compact(
            'provinsi', 'kabkota', 'kecamatan', 'kelurahan',
            'defaultProvinsi', 'defaultKabkota', 'defaultKecamatan', 'defaultKelurahan'
        ));
    }

    /**
     * Simpan penduduk baru ke database.
     * Jika user adalah RT, RT/RW penduduk otomatis di-set ke RT/RW user.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $user = auth()->user();
            $validated = $this->validatePenduduk($request);

            // Hitung usia otomatis dari tanggal lahir
            $validated['usia']            = \Carbon\Carbon::parse($validated['tanggal_lahir'])->age;
            $validated['status_aktif']    = $request->has('status_aktif');
            $validated['kewarganegaraan'] = $validated['kewarganegaraan'] ?? 'WNI';
            $validated['user_id']         = $user->id;

            // ── RT hanya boleh tambah penduduk di RT-nya sendiri ──
            if ($user->isRt()) {
                $validated['rt'] = $user->rt;
                if ($user->rw) $validated['rw'] = $user->rw;
            }

            $item = Penduduk::create($validated);
            Log::info('CREATE PENDUDUK berhasil', [
                'id'      => $item->id,
                'nama'    => $item->nama,
                'by_user' => $user->id,
                'by_role' => $user->role,
            ]);

            // ── Kirim notifikasi ke admin & staff desa (kecuali user sendiri) ──
            // Hanya trigger notif kalau yang tambah adalah RT atau RW (bukan admin/staff sendiri)
            if ($user->isRt() || $user->isRw()) {
                NotificationService::notifyAdminAndStaff(
                    new PendudukDitambahkan($item, $user),
                    $user->id
                );
            }

            return redirect()->route('admin.penduduk.index')
                ->with('success', "✅ Data penduduk \"{$item->nama}\" berhasil ditambahkan.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('CREATE PENDUDUK error', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('error', '❌ Gagal simpan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail penduduk.
     */
    public function show(Penduduk $penduduk): View
    {
        $this->authorizeAccess($penduduk);

        // Eager load relasi wilayah supaya tidak N+1 query
        $penduduk->load(['provinsi', 'kabkota', 'kecamatan', 'kelurahan']);

        // Hitung anggota keluarga lain dalam 1 KK
        $anggotaKeluarga = Penduduk::where('no_kk', $penduduk->no_kk)
                                   ->where('id', '!=', $penduduk->id)
                                   ->orderByRaw("FIELD(status_hubungan_keluarga, 'Kepala Keluarga', 'Istri', 'Anak', 'Orang Tua', 'Famili Lain', 'Lainnya')")
                                   ->get();

        return view('admin.penduduk.show', compact('penduduk', 'anggotaKeluarga'));
    }

    /**
     * Form edit penduduk.
     */
    public function edit(Penduduk $penduduk): View
    {
        $this->authorizeAccess($penduduk);

        $provinsi = WilayahProvinsi::orderBy('nama')->get();

        // Pre-load opsi bertingkat sesuai FK yang sudah terisi
        $kabkota = $penduduk->provinsi_id
            ? WilayahKabkota::where('provinsi_id', $penduduk->provinsi_id)->orderBy('nama')->get()
            : collect();

        $kecamatan = $penduduk->kabkota_id
            ? WilayahKecamatan::where('kabkota_id', $penduduk->kabkota_id)->orderBy('nama')->get()
            : collect();

        $kelurahan = $penduduk->kecamatan_id
            ? WilayahKelurahan::where('kecamatan_id', $penduduk->kecamatan_id)->orderBy('nama')->get()
            : collect();

        return view('admin.penduduk.edit', compact('penduduk', 'provinsi', 'kabkota', 'kecamatan', 'kelurahan'));
    }

    /**
     * Update data penduduk.
     */
    public function update(Request $request, Penduduk $penduduk): RedirectResponse
    {
        $this->authorizeAccess($penduduk);

        try {
            $validated = $this->validatePenduduk($request, $penduduk->id);

            $validated['usia']         = \Carbon\Carbon::parse($validated['tanggal_lahir'])->age;
            $validated['status_aktif'] = $request->has('status_aktif');

            // ── RT tidak boleh memindahkan penduduk ke RT/RW lain ──
            $user = auth()->user();
            if ($user->isRt()) {
                $validated['rt'] = $user->rt;
                if ($user->rw) $validated['rw'] = $user->rw;
            }

            $penduduk->update($validated);
            Log::info('UPDATE PENDUDUK berhasil', ['id' => $penduduk->id, 'by' => $user->id]);

            // ── Kirim notifikasi ke admin & staff desa (kalau yang update RT/RW) ──
            if ($user->isRt() || $user->isRw()) {
                NotificationService::notifyAdminAndStaff(
                    new PendudukDiperbarui($penduduk->fresh(), $user),
                    $user->id
                );
            }

            return redirect()->route('admin.penduduk.show', $penduduk)
                ->with('success', "✅ Data penduduk \"{$penduduk->nama}\" berhasil diperbarui.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('UPDATE PENDUDUK error', ['msg' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('error', '❌ Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data penduduk (soft delete).
     * Hanya admin yang boleh hapus data penduduk.
     */
    public function destroy(Penduduk $penduduk): RedirectResponse
    {
        // Hanya admin yang bisa hapus
        if (!auth()->user()->canDeletePenduduk()) {
            return redirect()->back()
                ->with('error', '❌ Anda tidak memiliki izin untuk menghapus data penduduk.');
        }

        try {
            $nama = $penduduk->nama;
            $nik  = $penduduk->nik;
            $penduduk->delete();

            // ── Notifikasi ke admin lain (kalau ada lebih dari 1 admin) ──
            NotificationService::notifyAdmin(
                new PendudukDihapus($nama, $nik, auth()->user()),
                auth()->id()
            );

            return redirect()->route('admin.penduduk.index')
                ->with('success', "✅ Data penduduk \"{$nama}\" berhasil dihapus.");

        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('error', '❌ Gagal hapus: ' . $e->getMessage());
        }
    }

    /**
     * Validasi akses user terhadap data penduduk tertentu.
     * Admin & staff_desa: bisa akses semua.
     * RW: hanya penduduk di RW-nya.
     * RT: hanya penduduk di RT-nya.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    private function authorizeAccess(Penduduk $penduduk): void
    {
        $user = auth()->user();

        if ($user->isAdmin() || $user->isStaffDesa()) {
            return; // Full access
        }

        if ($user->isRw()) {
            if ($penduduk->rw !== $user->rw) {
                abort(403, 'Anda hanya bisa akses penduduk di RW Anda sendiri.');
            }
            return;
        }

        if ($user->isRt()) {
            if ($penduduk->rt !== $user->rt) {
                abort(403, 'Anda hanya bisa akses penduduk di RT Anda sendiri.');
            }
            if ($user->rw && $penduduk->rw !== $user->rw) {
                abort(403, 'Anda hanya bisa akses penduduk di RT/RW Anda sendiri.');
            }
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke data ini.');
    }

    /**
     * Validasi field penduduk. $ignoreId dipakai untuk unique NIK saat update.
     */
    private function validatePenduduk(Request $request, ?int $ignoreId = null): array
    {
        $nikRule = 'required|string|size:16';
        $nikRule .= $ignoreId
            ? "|unique:penduduk,nik,{$ignoreId}"
            : '|unique:penduduk,nik';

        return $request->validate([
            // ── Identitas ─────────────────────────────────────────────
            'nik'           => $nikRule,
            'no_kk'         => 'required|string|size:16',
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',

            // ── Kelahiran ─────────────────────────────────────────────
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',

            // ── Sosial ────────────────────────────────────────────────
            'agama'             => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Lainnya',
            'status_perkawinan' => 'required|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'pekerjaan'         => 'nullable|string|max:100',
            'pendidikan'        => 'nullable|string|max:100',

            // ── Alamat ────────────────────────────────────────────────
            'alamat' => 'nullable|string|max:500',
            'rt'     => 'required|string|max:3',
            'rw'     => 'nullable|string|max:3',

            // ── Wilayah Administratif ─────────────────────────────────
            'provinsi_id'  => 'nullable|integer|exists:wilayah_provinsi,id',
            'kabkota_id'   => 'nullable|integer|exists:wilayah_kabkota,id',
            'kecamatan_id' => 'nullable|integer|exists:wilayah_kecamatan,id',
            'kelurahan_id' => 'nullable|integer|exists:wilayah_kelurahan,id',

            // ── Keluarga ──────────────────────────────────────────────
            'status_hubungan_keluarga' => 'required|in:Kepala Keluarga,Istri,Anak,Orang Tua,Famili Lain,Lainnya',
            'kewarganegaraan'          => 'nullable|string|max:50',
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar di database.',
            'nik.size'   => 'NIK harus tepat 16 digit.',
            'no_kk.size' => 'Nomor KK harus tepat 16 digit.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'provinsi_id.exists'  => 'Provinsi yang dipilih tidak valid.',
            'kabkota_id.exists'   => 'Kab/Kota yang dipilih tidak valid.',
            'kecamatan_id.exists' => 'Kecamatan yang dipilih tidak valid.',
            'kelurahan_id.exists' => 'Kelurahan/Desa yang dipilih tidak valid.',
        ]);
    }
}
