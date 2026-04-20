<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BansosProgram extends Model
{
    use SoftDeletes;

    protected $table = 'bansos_program';

    protected $fillable = [
        'nama', 'kode', 'deskripsi', 'jenis', 'nominal_per_bulan', 'status',
    ];

    protected $casts = [
        'nominal_per_bulan' => 'decimal:2',
    ];

    // ── Konstanta ────────────────────────────────────────────────────────────
    const JENIS = [
        'pusat'      => 'Pemerintah Pusat',
        'provinsi'   => 'Pemerintah Provinsi',
        'kabupaten'  => 'Pemerintah Kabupaten/Kota',
        'desa'       => 'Pemerintah Desa',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────
    public function penerima(): HasMany
    {
        return $this->hasMany(BansosPenerima::class, 'program_id');
    }

    public function penerimaAktif(): HasMany
    {
        return $this->hasMany(BansosPenerima::class, 'program_id')
                    ->where('status', 'aktif');
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