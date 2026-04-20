<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosyanduJadwal extends Model
{
    use SoftDeletes;

    protected $table = 'posyandu_jadwal';

    protected $fillable = [
        'posyandu_id', 'tanggal', 'waktu_mulai', 'waktu_selesai',
        'kegiatan', 'catatan', 'status', 'input_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // ── Konstanta ────────────────────────────────────────────────────────────
    const STATUS = [
        'terjadwal'   => 'Terjadwal',
        'berlangsung' => 'Berlangsung',
        'selesai'     => 'Selesai',
        'batal'       => 'Dibatalkan',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────
    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class, 'posyandu_id');
    }

    public function inputOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'input_oleh');
    }

    // ── Accessor ─────────────────────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function getWaktuFormatAttribute(): string
    {
        if (!$this->waktu_mulai) return '-';
        $mulai  = substr($this->waktu_mulai, 0, 5);
        $selesai = $this->waktu_selesai ? substr($this->waktu_selesai, 0, 5) : null;
        return $selesai ? "$mulai – $selesai" : $mulai;
    }

    // ── Scope ────────────────────────────────────────────────────────────────
    public function scopeMendatang($query)
    {
        return $query->where('tanggal', '>=', now()->toDateString())
                     ->whereIn('status', ['terjadwal', 'berlangsung']);
    }
}
