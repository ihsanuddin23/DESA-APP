<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'berita';

    protected $fillable = [
        'judul', 'slug', 'kategori', 'ringkasan', 'konten',
        'foto', 'penulis', 'status', 'published_at', 'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // ── Auto-generate slug dari judul ────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Berita $berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
            }
        });
    }

    // ── Route model binding pakai slug ───────────────────────────────
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Scope: hanya yang published ──────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // ── Relasi ───────────────────────────────────────────────────────
    public function penulisByUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
