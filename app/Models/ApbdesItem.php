<?php
// app/Models/ApbdesItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApbdesItem extends Model
{
    protected $table = 'apbdes_items';

    protected $fillable = [
        'apbdes_id',
        'jenis',
        'kode_rekening',
        'uraian',
        'kategori',
        'anggaran',
        'realisasi',
        'keterangan',
        'urutan',
    ];

    protected $casts = [
        'anggaran' => 'decimal:2',
        'realisasi' => 'decimal:2',
        'urutan' => 'integer',
    ];

    public function apbdes(): BelongsTo
    {
        return $this->belongsTo(Apbdes::class, 'apbdes_id');
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'pendapatan' => 'Pendapatan',
            'belanja' => 'Belanja',
            'pembiayaan' => 'Pembiayaan',
            default => ucfirst($this->jenis),
        };
    }

    public function getPersentaseRealisasiAttribute(): float
    {
        if ($this->anggaran == 0) return 0;
        return round(($this->realisasi / $this->anggaran) * 100, 2);
    }

    protected static function booted(): void
    {
        static::saved(function ($item) {
            $item->apbdes->hitungTotal();
        });

        static::deleted(function ($item) {
            $item->apbdes->hitungTotal();
        });
    }
}