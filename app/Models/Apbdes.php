<?php
// app/Models/Apbdes.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apbdes extends Model
{
    protected $table = 'apbdes';

    protected $fillable = [
        'tahun',
        'total_pendapatan',
        'total_belanja',
        'total_pembiayaan',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'total_pendapatan' => 'decimal:2',
        'total_belanja' => 'decimal:2',
        'total_pembiayaan' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ApbdesItem::class, 'apbdes_id');
    }

    public function pendapatan(): HasMany
    {
        return $this->items()->where('jenis', 'pendapatan')->orderBy('urutan');
    }

    public function belanja(): HasMany
    {
        return $this->items()->where('jenis', 'belanja')->orderBy('urutan');
    }

    public function pembiayaan(): HasMany
    {
        return $this->items()->where('jenis', 'pembiayaan')->orderBy('urutan');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'aktif' => 'Aktif',
            'selesai' => 'Selesai',
            default => ucfirst($this->status),
        };
    }

    public function getSisaAnggaranAttribute(): float
    {
        return $this->total_pendapatan - $this->total_belanja + $this->total_pembiayaan;
    }

    public function getPersentaseRealisasiAttribute(): float
    {
        if ($this->total_belanja == 0) return 0;
        $totalRealisasi = $this->items()->where('jenis', 'belanja')->sum('realisasi');
        return round(($totalRealisasi / $this->total_belanja) * 100, 2);
    }

    public function hitungTotal(): void
    {
        $this->total_pendapatan = $this->items()->where('jenis', 'pendapatan')->sum('anggaran');
        $this->total_belanja = $this->items()->where('jenis', 'belanja')->sum('anggaran');
        $this->total_pembiayaan = $this->items()->where('jenis', 'pembiayaan')->sum('anggaran');
        $this->save();
    }
}
