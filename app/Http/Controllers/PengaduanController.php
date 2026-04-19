<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Notifications\PengaduanBaru;
use App\Services\NotificationService;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PengaduanController extends Controller
{
    /**
     * Form pengaduan publik + daftar pengaduan yang sudah selesai (untuk transparansi).
     */
    public function index(): View
    {
        // Tampilkan hanya pengaduan yang sudah selesai, untuk transparansi
        $pengaduanSelesai = Pengaduan::where('status', 'selesai')
            ->latest('ditanggapi_pada')
            ->limit(10)
            ->get();

        return view('aduan.index', compact('pengaduanSelesai'));
    }

    /**
     * Submit pengaduan dari warga.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'nama_pengadu' => 'required|string|min:3|max:100',
                'kontak'       => 'required|string|max:50',
                'nik'          => 'nullable|string|size:16',
                'rt'           => 'nullable|string|max:3',
                'rw'           => 'nullable|string|max:3',
                'kategori'     => 'required|in:' . implode(',', array_keys(Pengaduan::KATEGORI)),
                'judul'        => 'required|string|min:5|max:200',
                'isi'          => 'required|string|min:20|max:5000',
                'lokasi'       => 'nullable|string|max:255',
                'foto_bukti'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            ], [
                'nama_pengadu.required' => 'Nama tidak boleh kosong.',
                'nama_pengadu.min'      => 'Nama minimal 3 karakter.',
                'kontak.required'       => 'Nomor HP atau email wajib diisi.',
                'judul.required'        => 'Judul aduan wajib diisi.',
                'judul.min'             => 'Judul minimal 5 karakter.',
                'isi.required'          => 'Isi aduan wajib diisi.',
                'isi.min'               => 'Jelaskan aduan dengan minimal 20 karakter.',
                'kategori.required'     => 'Pilih kategori pengaduan.',
                'foto_bukti.image'      => 'File harus berupa gambar.',
                'foto_bukti.max'        => 'Ukuran foto maksimal 4MB.',
            ]);

            // Upload foto bukti
            if ($request->hasFile('foto_bukti')) {
                $validated['foto_bukti'] = $request->file('foto_bukti')
                    ->store('pengaduan', 'public');
            }

            // Generate kode tiket + IP tracking
            $validated['kode_tiket'] = Pengaduan::generateKodeTiket();
            $validated['ip_address'] = $request->ip();
            $validated['status']     = 'baru';

            $pengaduan = Pengaduan::create($validated);

            Log::info('PENGADUAN baru masuk', [
                'kode'     => $pengaduan->kode_tiket,
                'kategori' => $pengaduan->kategori,
                'ip'       => $request->ip(),
            ]);

            // Kirim notifikasi ke admin + staff desa
            NotificationService::notifyAdminAndStaff(new PengaduanBaru($pengaduan));

            return redirect()->route('aduan.index')
                ->with('success_kode', $pengaduan->kode_tiket)
                ->with('success', "✅ Aduan Anda berhasil dikirim dengan kode tiket: <strong>{$pengaduan->kode_tiket}</strong>. Simpan kode ini untuk melacak status.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('PENGADUAN error', ['msg' => $e->getMessage()]);
            return back()->withInput()
                ->with('error', '❌ Gagal mengirim aduan: ' . $e->getMessage());
        }
    }

    /**
     * Tracking pengaduan via kode tiket.
     */
    public function lacak(Request $request): View
    {
        $request->validate([
            'kode_tiket' => 'nullable|string|max:25',
        ]);

        $pengaduan = null;
        if ($request->filled('kode_tiket')) {
            $pengaduan = Pengaduan::with('penanganan')
                ->where('kode_tiket', trim($request->kode_tiket))
                ->first();
        }

        return view('aduan.lacak', compact('pengaduan'));
    }
}