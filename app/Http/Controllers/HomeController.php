<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Pengumuman;
use App\Models\Penduduk;
use App\Models\StrukturDesa;
use App\Models\Galeri;
use App\Models\ProfilDesa;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Statistik kependudukan
        $statistik = [
            'total_penduduk'    => Penduduk::count(),
            'total_kk'          => Penduduk::distinct('no_kk')->count('no_kk'),
            'total_rt'          => Penduduk::distinct('rt')->count('rt'),
            'laki_laki'         => Penduduk::where('jenis_kelamin', 'L')->count(),
            'perempuan'         => Penduduk::where('jenis_kelamin', 'P')->count(),
            'usia_anak'         => Penduduk::whereBetween('usia', [0, 17])->count(),
            'usia_produktif'    => Penduduk::whereBetween('usia', [18, 55])->count(),
            'usia_lansia'       => Penduduk::where('usia', '>', 55)->count(),
        ];

        // Berita: 1 utama + 4 terbaru lainnya
        $beritaUtama  = Berita::where('status', 'published')
                            ->latest('published_at')
                            ->first();

        $beritaLainnya = Berita::where('status', 'published')
                            ->when($beritaUtama, fn($q) => $q->where('id', '!=', $beritaUtama->id))
                            ->latest('published_at')
                            ->take(4)
                            ->get();

        // Pengumuman aktif
        $pengumuman = Pengumuman::where('status', 'aktif')
                        ->where(function ($q) {
                            $q->whereNull('berlaku_hingga')
                              ->orWhere('berlaku_hingga', '>=', now());
                        })
                        ->orderByRaw("FIELD(prioritas, 'penting', 'info', 'umum')")
                        ->latest()
                        ->take(5)
                        ->get();

        // Struktur pemerintahan
        $strukturDesa = StrukturDesa::where('tampil_publik', true)
                            ->orderBy('urutan')
                            ->take(6)
                            ->get();

        // Galeri foto terbaru
        $galeri = Galeri::where('status', 'published')
                    ->latest()
                    ->take(5)
                    ->get();

        return view('home', compact(
            'statistik',
            'beritaUtama',
            'beritaLainnya',
            'pengumuman',
            'strukturDesa',
            'galeri',
        ));
    }

    public function berita(): View
    {
        $beritaList = Berita::where('status', 'published')
                        ->latest('published_at')
                        ->paginate(9);

        return view('berita.index', compact('beritaList'));
    }

    public function beritaDetail(Berita $berita): View
    {
        abort_if($berita->status !== 'published', 404);

        $berita->increment('views');

        $beritaTerkait = Berita::where('status', 'published')
                            ->where('id', '!=', $berita->id)
                            ->where('kategori', $berita->kategori)
                            ->latest('published_at')
                            ->take(3)
                            ->get();

        return view('berita.detail', compact('berita', 'beritaTerkait'));
    }

    public function pengumuman(): View
    {
        $pengumumanList = Pengumuman::where('status', 'aktif')
                            ->latest()
                            ->paginate(10);

        return view('pengumuman.index', compact('pengumumanList'));
    }

    /**
     * Halaman publik profil desa - ambil dari DB.
     */
    public function profil(): View
    {
        $profil = ProfilDesa::get();

        // Struktur pemerintahan untuk ditampilkan di profil
        $strukturDesa = StrukturDesa::where('tampil_publik', true)
                            ->orderBy('urutan')
                            ->get();

        // Statistik ringkas untuk profil
        $statistik = [
            'total_penduduk' => Penduduk::count(),
            'total_kk'       => Penduduk::distinct('no_kk')->count('no_kk'),
            'laki_laki'      => Penduduk::where('jenis_kelamin', 'L')->count(),
            'perempuan'      => Penduduk::where('jenis_kelamin', 'P')->count(),
        ];

        return view('profil', compact('profil', 'strukturDesa', 'statistik'));
    }
}
