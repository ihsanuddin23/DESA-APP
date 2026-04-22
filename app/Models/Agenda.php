<?php
// app/Models/Agenda.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Agenda extends Model
{
    protected $table = 'agenda';

    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'penyelenggara',
        'kontak_person',
        'telepon',
        'kategori',
        'status',
        'gambar',
        'is_highlight',
        'created_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_highlight' => 'boolean',
    ];

    // ══════════════════════════════════════════════════════════════════════════
    // RELATIONSHIPS
    // ══════════════════════════════════════════════════════════════════════════

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // SCOPES
    // ══════════════════════════════════════════════════════════════════════════

    public function scopePublikasi($query)
    {
        return $query->where('status', 'publikasi');
    }

    public function scopeMendatang($query)
    {
        return $query->where('tanggal_mulai', '>=', now()->toDateString());
    }

    public function scopeBerlangsung($query)
    {
        return $query->where('tanggal_mulai', '<=', now()->toDateString())
                     ->where(function ($q) {
                         $q->whereNull('tanggal_selesai')
                           ->orWhere('tanggal_selesai', '>=', now()->toDateString());
                     });
    }

    public function scopeSelesai($query)
    {
        return $query->where(function ($q) {
            $q->where('tanggal_selesai', '<', now()->toDateString())
              ->orWhere(function ($q2) {
                  $q2->whereNull('tanggal_selesai')
                     ->where('tanggal_mulai', '<', now()->toDateString());
              });
        });
    }

    public function scopeHighlight($query)
    {
        return $query->where('is_highlight', true);
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal_mulai', now()->month)
                     ->whereYear('tanggal_mulai', now()->year);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ACCESSORS
    // ══════════════════════════════════════════════════════════════════════════

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'rapat' => 'Rapat',
            'musyawarah' => 'Musyawarah',
            'gotong_royong' => 'Gotong Royong',
            'pelatihan' => 'Pelatihan',
            'sosialisasi' => 'Sosialisasi',
            'keagamaan' => 'Keagamaan',
            'budaya' => 'Budaya',
            'olahraga' => 'Olahraga',
            'kesehatan' => 'Kesehatan',
            'pendidikan' => 'Pendidikan',
            'lainnya' => 'Lainnya',
            default => ucfirst($this->kategori),
        };
    }

    public function getKategoriColorAttribute(): string
    {
        return match ($this->kategori) {
            'rapat' => '#3b82f6',
            'musyawarah' => '#8b5cf6',
            'gotong_royong' => '#22c55e',
            'pelatihan' => '#f59e0b',
            'sosialisasi' => '#06b6d4',
            'keagamaan' => '#10b981',
            'budaya' => '#ec4899',
            'olahraga' => '#ef4444',
            'kesehatan' => '#14b8a6',
            'pendidikan' => '#6366f1',
            'lainnya' => '#64748b',
            default => '#64748b',
        };
    }

    public function getKategoriBgAttribute(): string
    {
        return match ($this->kategori) {
            'rapat' => '#dbeafe',
            'musyawarah' => '#ede9fe',
            'gotong_royong' => '#dcfce7',
            'pelatihan' => '#fef3c7',
            'sosialisasi' => '#cffafe',
            'keagamaan' => '#d1fae5',
            'budaya' => '#fce7f3',
            'olahraga' => '#fee2e2',
            'kesehatan' => '#ccfbf1',
            'pendidikan' => '#e0e7ff',
            'lainnya' => '#f1f5f9',
            default => '#f1f5f9',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'publikasi' => 'Publikasi',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'badge-warning',
            'publikasi' => 'badge-success',
            'selesai' => 'badge-gray',
            'dibatalkan' => 'badge-danger',
            default => 'badge-gray',
        };
    }

    public function getTanggalFormatAttribute(): string
    {
        $mulai = $this->tanggal_mulai->translatedFormat('d F Y');

        if ($this->tanggal_selesai && $this->tanggal_selesai->ne($this->tanggal_mulai)) {
            $selesai = $this->tanggal_selesai->translatedFormat('d F Y');
            return "{$mulai} - {$selesai}";
        }

        return $mulai;
    }

    public function getWaktuFormatAttribute(): ?string
    {
        if (!$this->waktu_mulai) return null;

        $mulai = \Carbon\Carbon::parse($this->waktu_mulai)->format('H:i');

        if ($this->waktu_selesai) {
            $selesai = \Carbon\Carbon::parse($this->waktu_selesai)->format('H:i');
            return "{$mulai} - {$selesai} WIB";
        }

        return "{$mulai} WIB";
    }

    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) return null;
        return asset('storage/' . $this->gambar);
    }

    public function getIsMendatangAttribute(): bool
    {
        return $this->tanggal_mulai->gte(now()->toDateString());
    }

    public function getIsBerlangsungAttribute(): bool
    {
        $today = now()->toDateString();
        $mulai = $this->tanggal_mulai->toDateString();
        $selesai = $this->tanggal_selesai?->toDateString() ?? $mulai;

        return $mulai <= $today && $selesai >= $today;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // METHODS
    // ══════════════════════════════════════════════════════════════════════════

    protected static function booted(): void
    {
        static::creating(function ($agenda) {
            if (empty($agenda->slug)) {
                $agenda->slug = Str::slug($agenda->judul) . '-' . Str::random(5);
            }
        });
    }

    public static function kategoriOptions(): array
    {
        return [
            'rapat' => 'Rapat',
            'musyawarah' => 'Musyawarah',
            'gotong_royong' => 'Gotong Royong',
            'pelatihan' => 'Pelatihan',
            'sosialisasi' => 'Sosialisasi',
            'keagamaan' => 'Keagamaan',
            'budaya' => 'Budaya',
            'olahraga' => 'Olahraga',
            'kesehatan' => 'Kesehatan',
            'pendidikan' => 'Pendidikan',
            'lainnya' => 'Lainnya',
        ];
    }
}
