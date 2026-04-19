<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use SoftDeletes;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul', 'isi', 'prioritas', 'status',
        'file_lampiran', 'berlaku_hingga', 'user_id',
    ];

    protected $casts = [
        'berlaku_hingga' => 'datetime',
    ];

    // ── Scope: masih aktif & belum kadaluarsa ────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')
                     ->where(function ($q) {
                         $q->whereNull('berlaku_hingga')
                           ->orWhere('berlaku_hingga', '>=', now());
                     });
    }

    public function penulis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
