<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posyandu extends Model
{
    use SoftDeletes;

    protected $table = 'posyandu';

    protected $fillable = [
        'nama', 'kode', 'lokasi', 'rw', 'jenis',
        'jumlah_kader', 'jumlah_balita',
        'ketua_kader', 'kontak', 'deskripsi', 'status',
    ];

    protected $casts = [
        'jumlah_kader'  => 'integer',
        'jumlah_balita' => 'integer',
    ];

    // ── Konstanta ────────────────────────────────────────────────────────────
    const JENIS = [
        'balita'  => 'Balita',
        'lansia'  => 'Lansia',
        'terpadu' => 'Terpadu (Balita & Lansia)',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────
    public function jadwal(): HasMany
    {
        return $this->hasMany(PosyanduJadwal::class, 'posyandu_id');
    }

    public function jadwalMendatang(): HasMany
    {
        return $this->hasMany(PosyanduJadwal::class, 'posyandu_id')
                    ->where('tanggal', '>=', now()->toDateString())
                    ->whereIn('status', ['terjadwal', 'berlangsung'])
                    ->orderBy('tanggal');
    }

    // ── Accessor ─────────────────────────────────────────────────────────────
    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? $this->jenis;
    }

    // ── Scope ────────────────────────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
