<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{User, AuditLog, LoginAttempt, Penduduk, Berita, Pengumuman, Pengaduan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // ─── Admin Dashboard ──────────────────────────────────────────────────────
    public function admin(Request $request): View
    {
        // ── Stats utama ──
        $stats = [
            'total_users'    => User::count(),
            'active_users'   => User::where('is_active', true)->count(),
            'total_warga'    => Penduduk::count(),
            'total_kk'       => Penduduk::distinct('no_kk')->count('no_kk'),
            'pengaduan_baru' => class_exists(Pengaduan::class) ? Pengaduan::where('status', 'baru')->count() : 0,
            'failed_logins'  => LoginAttempt::where('successful', false)
                                    ->where('attempted_at', '>=', now()->subDay())
                                    ->count(),
        ];

        // ── Data untuk Piramida Penduduk ──
        // Kelompok usia: 0-5, 6-12, 13-17, 18-25, 26-40, 41-55, 56-65, 66+
        $kelompokUsia = [
            ['label' => '0-5',   'min' => 0,  'max' => 5],
            ['label' => '6-12',  'min' => 6,  'max' => 12],
            ['label' => '13-17', 'min' => 13, 'max' => 17],
            ['label' => '18-25', 'min' => 18, 'max' => 25],
            ['label' => '26-40', 'min' => 26, 'max' => 40],
            ['label' => '41-55', 'min' => 41, 'max' => 55],
            ['label' => '56-65', 'min' => 56, 'max' => 65],
            ['label' => '66+',   'min' => 66, 'max' => 200],
        ];

        $piramida = [];
        foreach ($kelompokUsia as $kelompok) {
            $piramida['labels'][] = $kelompok['label'];
            $piramida['laki']  []= -Penduduk::whereBetween('usia', [$kelompok['min'], $kelompok['max']])
                                        ->where('jenis_kelamin', 'L')->count(); // negatif untuk piramida
            $piramida['perempuan'][] = Penduduk::whereBetween('usia', [$kelompok['min'], $kelompok['max']])
                                        ->where('jenis_kelamin', 'P')->count();
        }

        // ── Distribusi Pekerjaan (top 8) ──
        $pekerjaan = Penduduk::select('pekerjaan', DB::raw('COUNT(*) as total'))
            ->whereNotNull('pekerjaan')
            ->where('pekerjaan', '!=', '')
            ->groupBy('pekerjaan')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ── Distribusi Pendidikan ──
        $pendidikan = Penduduk::select('pendidikan', DB::raw('COUNT(*) as total'))
            ->whereNotNull('pendidikan')
            ->where('pendidikan', '!=', '')
            ->groupBy('pendidikan')
            ->orderByDesc('total')
            ->get();

        // ── Distribusi Agama ──
        $agama = Penduduk::select('agama', DB::raw('COUNT(*) as total'))
            ->whereNotNull('agama')
            ->groupBy('agama')
            ->orderByDesc('total')
            ->get();

        // ── Tren Warga Baru per Bulan (12 bulan terakhir) ──
        $trenBulanan = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $trenBulanan['labels'][] = $bulan->isoFormat('MMM YY');
            $trenBulanan['data'][] = Penduduk::whereYear('created_at', $bulan->year)
                                           ->whereMonth('created_at', $bulan->month)
                                           ->count();
        }

        // ── Top 5 RT dengan Warga Terbanyak ──
        $topRt = Penduduk::select('rt', 'rw', DB::raw('COUNT(*) as total'))
            ->whereNotNull('rt')
            ->groupBy('rt', 'rw')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ── Stats Pengaduan per Status (kalau ada) ──
        $pengaduanStats = [];
        if (class_exists(Pengaduan::class)) {
            $pengaduanStats = [
                'baru'     => Pengaduan::where('status', 'baru')->count(),
                'diproses' => Pengaduan::where('status', 'diproses')->count(),
                'selesai'  => Pengaduan::where('status', 'selesai')->count(),
                'ditolak'  => Pengaduan::where('status', 'ditolak')->count(),
            ];
        }

        // ── Recent Activity & Failed Logins ──
        $recentActivities = AuditLog::with('user')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $recentFailedLogins = LoginAttempt::where('successful', false)
            ->orderByDesc('attempted_at')
            ->limit(8)
            ->get();

        return view('dashboard.admin', compact(
            'stats', 'piramida', 'pekerjaan', 'pendidikan', 'agama',
            'trenBulanan', 'topRt', 'pengaduanStats',
            'recentActivities', 'recentFailedLogins'
        ));
    }

    // ─── Staff Desa Dashboard ─────────────────────────────────────────────────
    public function staff(Request $request): View
    {
        $stats = [
            'total_penduduk'   => Penduduk::count(),
            'total_berita'     => class_exists(Berita::class) ? Berita::count() : 0,
            'total_pengumuman' => class_exists(Pengumuman::class) ? Pengumuman::where('status', 'aktif')->count() : 0,
        ];

        return view('dashboard.staff', compact('stats'));
    }

    // ─── RW Dashboard ─────────────────────────────────────────────────────────
    public function rw(Request $request): View
    {
        $user = $request->user();

        // Semua penduduk di RW-nya
        $totalWarga = Penduduk::where('rw', $user->rw)->count();

        // Daftar RT di RW-nya (dengan jumlah warga masing-masing)
        $rtList = Penduduk::where('rw', $user->rw)
            ->select('rt')
            ->selectRaw('COUNT(*) as jumlah_warga')
            ->selectRaw('COUNT(DISTINCT no_kk) as jumlah_kk')
            ->groupBy('rt')
            ->orderBy('rt')
            ->get();

        // Ketua RT di wilayah (kalau ada user dengan role rt di RW ini)
        $ketuaRt = User::where('role', 'rt')
            ->where('rw', $user->rw)
            ->orderBy('rt')
            ->get()
            ->keyBy('rt');

        return view('dashboard.rw', compact('user', 'totalWarga', 'rtList', 'ketuaRt'));
    }

    // ─── RT Dashboard ─────────────────────────────────────────────────────────
    public function rt(Request $request): View
    {
        $user = $request->user();

        // Stats warga di RT
        $query = Penduduk::query();
        if ($user->isRt()) {
            $query->where('rt', $user->rt);
            if ($user->rw) $query->where('rw', $user->rw);
        }

        $stats = [
            'total_warga'  => (clone $query)->count(),
            'laki_laki'    => (clone $query)->where('jenis_kelamin', 'L')->count(),
            'perempuan'    => (clone $query)->where('jenis_kelamin', 'P')->count(),
            'total_kk'     => (clone $query)->distinct('no_kk')->count('no_kk'),
        ];

        $wargaList = $query->orderBy('nama')->limit(10)->get();

        return view('dashboard.rt', compact('user', 'stats', 'wargaList'));
    }

    // ─── Warga Dashboard ──────────────────────────────────────────────────────
    public function warga(Request $request): View
    {
        $user = $request->user();
        $activityLog = AuditLog::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard.warga', compact('user', 'activityLog'));
    }
}
