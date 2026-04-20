<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PengaduanController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    LogoutController,
    VerifyEmailController,
    TwoFactorController,
};
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\{
    UserManagementController,
    AuditLogController,
    BlockedIpController,
    LoginAttemptController,
    BeritaController,
    PengumumanController,
    GaleriController,
    StrukturDesaController,
    PendudukController,
    PengaduanController as AdminPengaduanController,
    WilayahController,
    ExportController,
    ProfilDesaController,
    BansosController,
    PosyanduController,
};

// ────────────────────────────────────────────────────────────────────────────
// PUBLIC — No auth required
// ────────────────────────────────────────────────────────────────────────────
Route::middleware(['web', 'blocked.ip'])->group(function () {

    // ── Halaman Publik ───────────────────────────────────────────────────────
    Route::get('/',                     [HomeController::class, 'index'])->name('home');
    Route::get('/berita',               [HomeController::class, 'berita'])->name('berita');
    Route::get('/berita/{berita:slug}', [HomeController::class, 'beritaDetail'])->name('berita.detail');
    Route::get('/pengumuman',           [HomeController::class, 'pengumuman'])->name('pengumuman');
    Route::get('/profil',               [HomeController::class, 'profil'])->name('profil');
    Route::get('/galeri',               [HomeController::class, 'galeri'])->name('galeri');
    Route::get('/kontak',               fn() => view('kontak'))->name('kontak');
    Route::get('/apbdes',               fn() => view('apbdes'))->name('apbdes');
    Route::get('/posyandu',             [PosyanduController::class, 'public'])->name('posyandu');

    // ── Aduan ────────────────────────────────────────────────────────────────
    Route::get('/aduan',                [PengaduanController::class, 'index'])->name('aduan');
    Route::post('/aduan',               [PengaduanController::class, 'store'])->name('aduan.store');
    Route::get('/aduan/lacak',          [PengaduanController::class, 'lacak'])->name('aduan.lacak');

    // ── Bansos (halaman publik + cek status) ────────────────────────────────
    Route::get('/bansos',               [BansosController::class, 'public'])->name('bansos');
    Route::post('/bansos/cek',          [BansosController::class, 'cekStatus'])
         ->middleware('throttle:5,1')
         ->name('bansos.cek');

    // ── Auth Routes ──────────────────────────────────────────────────────────
    Route::middleware('guest')->group(function () {

        // Login
        Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])
            ->middleware('throttle:10,1');

        // Register
        Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register'])
            ->middleware('throttle:5,1');

        // CAPTCHA refresh
        Route::get('/captcha/refresh',          [LoginController::class,    'refreshCaptcha'])->name('captcha.refresh');
        Route::get('/captcha/refresh/register', [RegisterController::class, 'refreshCaptcha'])->name('captcha.refresh.register');
    });

    // ── Email Verification ───────────────────────────────────────────────────
    Route::get('/email/verify',         [VerifyEmailController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/otp',     [VerifyEmailController::class, 'showOtpForm'])->name('verification.otp');
    Route::post('/email/verify/otp',    [VerifyEmailController::class, 'verifyOtp'])->name('verification.otp.verify')->middleware('throttle:10,1');
    Route::post('/email/verify/resend', [VerifyEmailController::class, 'resend'])->name('verification.resend')->middleware('throttle:3,1');

    // ── Two-Factor Challenge (user in 2FA limbo — not fully logged in) ───────
    Route::get('/two-factor/challenge', [TwoFactorController::class, 'challenge'])->name('two-factor.challenge');
    Route::post('/two-factor/verify',   [TwoFactorController::class, 'verify'])->name('two-factor.verify')->middleware('throttle:10,1');
    Route::post('/two-factor/resend',   [TwoFactorController::class, 'resend'])->name('two-factor.resend')->middleware('throttle:3,1');

    // ── Password Reset (built-in Laravel) ────────────────────────────────────
    Route::get('/forgot-password',        fn() => view('auth.forgot-password'))->name('password.request');
    Route::post('/forgot-password',       [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',        [\App\Http\Controllers\Auth\PasswordResetController::class, 'resetPassword'])->name('password.update')->middleware('throttle:5,1');
});

// ────────────────────────────────────────────────────────────────────────────
// AUTHENTICATED — Requires login + active account + session timeout check
// ────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'active', 'session.timeout:30', 'log.activity'])->group(function () {

    // ── Logout ───────────────────────────────────────────────────────────────
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // ── Session Extend ───────────────────────────────────────────────────────
    Route::post('/session/extend', function () {
        session(['last_activity_at' => time()]);
        return back();
    })->name('session.extend');

    // ── Dashboards (role-specific) ───────────────────────────────────────────
    Route::get('/dashboard/admin',  [DashboardController::class, 'admin'])->name('dashboard.admin')->middleware('role:admin');
    Route::get('/dashboard/staff',  [DashboardController::class, 'staff'])->name('dashboard.staff')->middleware('role:admin,staff_desa');
    Route::get('/dashboard/rw',     [DashboardController::class, 'rw'])->name('dashboard.rw')->middleware('role:admin,rw');
    Route::get('/dashboard/rt',     [DashboardController::class, 'rt'])->name('dashboard.rt')->middleware('role:admin,rw,rt');
    Route::get('/dashboard',        [DashboardController::class, 'warga'])->name('dashboard.warga');

    // ── Profile ──────────────────────────────────────────────────────────────
    Route::get('/profile',          [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/security', [ProfileController::class, 'security'])->name('profile.security');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // ── Two-Factor Management (from profile) ─────────────────────────────────
    Route::post('/two-factor/enable',    [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::delete('/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');

    // ── Notifications (semua user login) ─────────────────────────────────────
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',          [NotificationController::class, 'index'])->name('index');
        Route::get('/dropdown',  [NotificationController::class, 'dropdown'])->name('dropdown');
        Route::get('/{id}',      [NotificationController::class, 'show'])->name('show');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}',   [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/',       [NotificationController::class, 'destroyAll'])->name('destroy-all');
    });

    // ══════════════════════════════════════════════════════════════════════════
    // ADMIN PANEL — 3 level akses
    // ══════════════════════════════════════════════════════════════════════════
    Route::prefix('admin')->name('admin.')->group(function () {

        // ── Level 1: ADMIN ONLY (full system control) ──────────────────────────
        Route::middleware('role:admin')->group(function () {
            // User Management
            Route::resource('users', UserManagementController::class)->names('users');
            Route::patch('users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])->name('users.toggle-active');
            Route::patch('users/{user}/role',          [UserManagementController::class, 'updateRole'])->name('users.update-role');

            // Security & Audit
            Route::get('audit-logs',          [AuditLogController::class,     'index'])->name('audit.index');
            Route::get('blocked-ips',         [BlockedIpController::class,    'index'])->name('blocked-ips.index');
            Route::post('blocked-ips',        [BlockedIpController::class,    'store'])->name('blocked-ips.store');
            Route::delete('blocked-ips/{id}', [BlockedIpController::class,    'destroy'])->name('blocked-ips.destroy');
            Route::get('login-attempts',      [LoginAttemptController::class, 'index'])->name('login-attempts.index');
        });

        // ── Level 2: ADMIN + STAFF DESA (content management) ───────────────────
        Route::middleware('role:admin,staff_desa')->group(function () {
            // Kelola Berita
            Route::resource('berita', BeritaController::class)
                ->parameters(['berita' => 'berita']);

            // Kelola Pengumuman
            Route::resource('pengumuman', PengumumanController::class)
                ->parameters(['pengumuman' => 'pengumuman'])
                ->except(['show']);

            // Kelola Galeri
            Route::resource('galeri', GaleriController::class)
                ->parameters(['galeri' => 'galeri'])
                ->except(['show']);

            // Kelola Struktur Desa
            Route::resource('struktur-desa', StrukturDesaController::class)
                ->parameters(['struktur-desa' => 'strukturDesa'])
                ->except(['show']);

            // Kelola Bansos
            Route::prefix('bansos')->name('bansos.')->group(function () {
                // Program
                Route::get('program',                  [BansosController::class, 'programIndex'])->name('program.index');
                Route::get('program/tambah',           [BansosController::class, 'programCreate'])->name('program.create');
                Route::post('program',                 [BansosController::class, 'programStore'])->name('program.store');
                Route::get('program/{program}/edit',   [BansosController::class, 'programEdit'])->name('program.edit');
                Route::put('program/{program}',        [BansosController::class, 'programUpdate'])->name('program.update');
                Route::delete('program/{program}',     [BansosController::class, 'programDestroy'])->name('program.destroy');

                // Penerima
                Route::get('penerima',                 [BansosController::class, 'penerimaIndex'])->name('penerima.index');
                Route::get('penerima/tambah',          [BansosController::class, 'penerimaCreate'])->name('penerima.create');
                Route::post('penerima',                [BansosController::class, 'penerimaStore'])->name('penerima.store');
                Route::get('penerima/{penerima}/edit', [BansosController::class, 'penerimaEdit'])->name('penerima.edit');
                Route::put('penerima/{penerima}',      [BansosController::class, 'penerimaUpdate'])->name('penerima.update');
                Route::delete('penerima/{penerima}',   [BansosController::class, 'penerimaDestroy'])->name('penerima.destroy');
            });

            // Kelola Posyandu
            Route::prefix('posyandu')->name('posyandu.')->group(function () {
                // Master posyandu
                Route::get('/',                      [PosyanduController::class, 'index'])->name('index');
                Route::get('/tambah',                [PosyanduController::class, 'create'])->name('create');
                Route::post('/',                     [PosyanduController::class, 'store'])->name('store');
                Route::get('/{posyandu}/edit',       [PosyanduController::class, 'edit'])->name('edit');
                Route::put('/{posyandu}',            [PosyanduController::class, 'update'])->name('update');
                Route::delete('/{posyandu}',         [PosyanduController::class, 'destroy'])->name('destroy');

                // Jadwal
                Route::get('/jadwal',                [PosyanduController::class, 'jadwalIndex'])->name('jadwal.index');
                Route::get('/jadwal/tambah',         [PosyanduController::class, 'jadwalCreate'])->name('jadwal.create');
                Route::post('/jadwal',               [PosyanduController::class, 'jadwalStore'])->name('jadwal.store');
                Route::get('/jadwal/{jadwal}/edit',  [PosyanduController::class, 'jadwalEdit'])->name('jadwal.edit');
                Route::put('/jadwal/{jadwal}',       [PosyanduController::class, 'jadwalUpdate'])->name('jadwal.update');
                Route::delete('/jadwal/{jadwal}',    [PosyanduController::class, 'jadwalDestroy'])->name('jadwal.destroy');
            });

            // Kelola Profil Desa (single row, edit only)
            Route::get('profil-desa', [ProfilDesaController::class, 'edit'])->name('profil-desa.edit');
            Route::put('profil-desa', [ProfilDesaController::class, 'update'])->name('profil-desa.update');

            // Kelola Pengaduan Warga
            Route::get('pengaduan',                [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
            Route::get('pengaduan/{pengaduan}',    [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
            Route::put('pengaduan/{pengaduan}',    [AdminPengaduanController::class, 'update'])->name('pengaduan.update');
            Route::delete('pengaduan/{pengaduan}', [AdminPengaduanController::class, 'destroy'])->name('pengaduan.destroy');

            // Export Pengaduan
            Route::get('export/pengaduan/excel', [ExportController::class, 'pengaduanExcel'])->name('export.pengaduan.excel');
            Route::get('export/pengaduan/pdf',   [ExportController::class, 'pengaduanPdf'])->name('export.pengaduan.pdf');
        });

        // ── Level 3: ADMIN + STAFF + RW + RT (data penduduk dengan scope berbeda) ─
        // Akses dibatasi otomatis di controller berdasarkan role user
        Route::middleware('role:admin,staff_desa,rw,rt')->group(function () {
            Route::resource('penduduk', PendudukController::class)
                ->parameters(['penduduk' => 'penduduk']);

            // Export Penduduk (scope otomatis by role user)
            Route::get('export/penduduk/excel', [ExportController::class, 'pendudukExcel'])->name('export.penduduk.excel');
            Route::get('export/penduduk/pdf',   [ExportController::class, 'pendudukPdf'])->name('export.penduduk.pdf');

            // API Wilayah (untuk dropdown bertingkat AJAX di form penduduk)
            Route::prefix('api/wilayah')->name('api.wilayah.')->group(function () {
                Route::get('kabkota',   [WilayahController::class, 'kabkota'])->name('kabkota');
                Route::get('kecamatan', [WilayahController::class, 'kecamatan'])->name('kecamatan');
                Route::get('kelurahan', [WilayahController::class, 'kelurahan'])->name('kelurahan');
            });
        });

    });

});
