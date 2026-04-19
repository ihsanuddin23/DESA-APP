<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\VerifyEmailOtp;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    // ─── Roles Constant ─────────────────────────────────────────────────────
    const ROLE_ADMIN      = 'admin';
    const ROLE_STAFF_DESA = 'staff_desa';
    const ROLE_RW         = 'rw';
    const ROLE_RT         = 'rt';
    const ROLE_WARGA      = 'warga';

    // Daftar semua role untuk dipakai di form/validation
    const ROLES = [
        self::ROLE_ADMIN      => 'Administrator',
        self::ROLE_STAFF_DESA => 'Staff Desa',
        self::ROLE_RW         => 'Ketua RW',
        self::ROLE_RT         => 'Ketua RT',
        self::ROLE_WARGA      => 'Warga',
    ];

    // ─── Fillable ────────────────────────────────────────────────────────────
    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active',
        'two_factor_enabled', 'two_factor_secret',
        'last_login_at', 'last_login_ip', 'last_login_device', 'login_count',
        'locked_until', 'phone', 'nik', 'email_verified_at',
        'rt', 'rw',
    ];

    // ─── Hidden ──────────────────────────────────────────────────────────────
    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret',
    ];

    // ─── Casts ───────────────────────────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'last_login_at'        => 'datetime',
            'locked_until'         => 'datetime',
            'password'             => 'hashed',
            'is_active'            => 'boolean',
            'two_factor_enabled'   => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────
    public function loginAttempts()
    {
        return $this->hasMany(LoginAttempt::class, 'email', 'email');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // ─── Role Helpers ─────────────────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaffDesa(): bool
    {
        return $this->role === self::ROLE_STAFF_DESA;
    }

    public function isRw(): bool
    {
        return $this->role === self::ROLE_RW;
    }

    public function isRt(): bool
    {
        return $this->role === self::ROLE_RT;
    }

    public function isWarga(): bool
    {
        return $this->role === self::ROLE_WARGA;
    }

    /**
     * Apakah user punya akses ke panel admin (admin, staff_desa, rw, rt)?
     */
    public function canAccessAdminPanel(): bool
    {
        return $this->hasRole([
            self::ROLE_ADMIN,
            self::ROLE_STAFF_DESA,
            self::ROLE_RW,
            self::ROLE_RT,
        ]);
    }

    /**
     * Apakah user bisa kelola konten desa (berita, pengumuman, galeri, struktur)?
     * Admin & Staff Desa saja.
     */
    public function canManageContent(): bool
    {
        return $this->hasRole([self::ROLE_ADMIN, self::ROLE_STAFF_DESA]);
    }

    /**
     * Apakah user bisa lihat data penduduk?
     * Admin & Staff Desa lihat semua. RW lihat di wilayah RW-nya. RT lihat di RT-nya.
     */
    public function canViewPenduduk(): bool
    {
        return $this->hasRole([
            self::ROLE_ADMIN,
            self::ROLE_STAFF_DESA,
            self::ROLE_RW,
            self::ROLE_RT,
        ]);
    }

    /**
     * Apakah user bisa hapus penduduk? Hanya admin.
     */
    public function canDeletePenduduk(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Label role untuk tampilan
     */
    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->role, $roles);
    }

    // ─── Active Check ─────────────────────────────────────────────────────────
    public function isActive(): bool
    {
        return $this->is_active;
    }

    // ─── Account Lock Check ──────────────────────────────────────────────────
    public function isLocked(): bool
    {
        if (!$this->locked_until) return false;
        return $this->locked_until->isFuture();
    }

    // ─── Dashboard Redirect ───────────────────────────────────────────────────
    public function getDashboardRoute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN      => 'dashboard.admin',
            self::ROLE_STAFF_DESA => 'dashboard.staff',
            self::ROLE_RW         => 'dashboard.rw',
            self::ROLE_RT         => 'dashboard.rt',
            default               => 'dashboard.warga',
        };
    }

    // ─── Email Verification Notification Override ─────────────────────────────
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailOtp());
    }
}