<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BansosPenerima extends Model
{
    use SoftDeletes;

    protected $table = 'bansos_penerima';

    protected $fillable = [
        'program_id', 'penduduk_id', 'nik', 'nama_penerima', 'no_kk',
        'rt', 'rw', 'alamat', 'tahun', 'periode', 'nominal', 'status',
        'keterangan', 'input_oleh',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tahun'   => 'integer',
    ];

    // ── Konstanta ────────────────────────────────────────────────────────────
    const STATUS = [
        'aktif'   => 'Aktif',
        'nonaktif'=> 'Nonaktif',
        'dicoret' => 'Dicoret',
    ];

    const PERIODE = [
        'januari'   => 'Januari',   'februari'  => 'Februari',
        'maret'     => 'Maret',     'april'     => 'April',
        'mei'       => 'Mei',       'juni'      => 'Juni',
        'juli'      => 'Juli',      'agustus'   => 'Agustus',
        'september' => 'September', 'oktober'   => 'Oktober',
        'november'  => 'November',  'desember'  => 'Desember',
        'tahunan'   => 'Tahunan',
    ];

    // ── Relasi ───────────────────────────────────────────────────────────────
    public function program(): BelongsTo
    {
        return $this->belongsTo(BansosProgram::class, 'program_id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
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

    public function getPeriodeLabelAttribute(): string
    {
        return self::PERIODE[$this->periode] ?? $this->periode;
    }

    // ── Scope ────────────────────────────────────────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeTahun($query, int $tahun)
    {
        return $query->where('tahun', $tahun);
    }
}