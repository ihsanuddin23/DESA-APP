<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengaduanController extends Controller
{
    /**
     * Daftar semua pengaduan.
     */
    public function index(Request $request): View
    {
        $query = Pengaduan::with('penanganan');

        // Filter
        $query->when($request->status,   fn($q, $v) => $q->where('status', $v))
              ->when($request->kategori, fn($q, $v) => $q->where('kategori', $v))
              ->when($request->prioritas, fn($q, $v) => $q->where('prioritas', $v));

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                  ->orWhere('nama_pengadu', 'like', "%{$s}%")
                  ->orWhere('kode_tiket', 'like', "%{$s}%");
            });
        }

        // ✅ PERUBAHAN: Ubah dari 15 menjadi 5 (maksimal 5 per halaman)
        $pengaduan = $query->latest()
            ->paginate(5) // <--- INI YANG DIUBAH
            ->withQueryString();

        // Stats ringkas
        $stats = [
            'total'    => Pengaduan::count(),
            'baru'     => Pengaduan::where('status', 'baru')->count(),
            'diproses' => Pengaduan::where('status', 'diproses')->count(),
            'selesai'  => Pengaduan::where('status', 'selesai')->count(),
        ];

        return view('admin.pengaduan.index', compact('pengaduan', 'stats'));
    }

    /**
     * Detail 1 pengaduan.
     */
    public function show(Pengaduan $pengaduan): View
    {
        $pengaduan->load('penanganan');
        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Update tanggapan + status pengaduan.
     */
    public function update(Request $request, Pengaduan $pengaduan): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'status'    => 'required|in:baru,diproses,selesai,ditolak',
                'tanggapan' => 'nullable|string|max:3000',
                'prioritas' => 'required|in:rendah,sedang,tinggi',
            ]);

            // Kalau ada perubahan status / isi tanggapan, record penanganan
            $validated['ditangani_oleh']  = auth()->id();
            $validated['ditanggapi_pada'] = now();

            $pengaduan->update($validated);

            Log::info('PENGADUAN diupdate', [
                'id'     => $pengaduan->id,
                'status' => $pengaduan->status,
                'by'     => auth()->id(),
            ]);

            return redirect()->route('admin.pengaduan.show', $pengaduan)
                ->with('success', '✅ Tanggapan berhasil disimpan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', '❌ Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pengaduan (soft delete, admin only).
     */
    public function destroy(Pengaduan $pengaduan): RedirectResponse
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', '❌ Hanya admin yang bisa hapus pengaduan.');
        }

        try {
            // Hapus foto bukti kalau ada
            if ($pengaduan->foto_bukti && Storage::disk('public')->exists($pengaduan->foto_bukti)) {
                Storage::disk('public')->delete($pengaduan->foto_bukti);
            }

            $pengaduan->delete();

            return redirect()->route('admin.pengaduan.index')
                ->with('success', '✅ Pengaduan berhasil dihapus.');

        } catch (\Throwable $e) {
            return back()->with('error', '❌ Gagal hapus: ' . $e->getMessage());
        }
    }
}
