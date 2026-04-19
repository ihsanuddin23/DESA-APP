<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penduduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penduduk';

    protected $fillable = [
        'nik',
        'no_kk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'usia',
        'agama',
        'status_perkawinan',
        'pekerjaan',
        'pendidikan',
        'alamat',
        'rt',
        'rw',
        'provinsi_id',
        'kabkota_id',
        'kecamatan_id',
        'kelurahan_id',
        'status_hubungan_keluarga',
        'kewarganegaraan',
        'status_aktif',
        'user_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'status_aktif'  => 'boolean',
    ];

    // ── Auto-hitung usia dari tanggal lahir saat create/update ───────
    protected static function booted(): void
    {
        static::saving(function (Penduduk $penduduk) {
            if ($penduduk->tanggal_lahir) {
                $penduduk->usia = $penduduk->tanggal_lahir->age;
            }
        });
    }

    // ── Scope: hanya penduduk yang aktif ─────────────────────────────
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    // ── Scope: berdasarkan jenis kelamin ─────────────────────────────
    public function scopeLakiLaki($query)
    {
        return $query->where('jenis_kelamin', 'L');
    }

    public function scopePerempuan($query)
    {
        return $query->where('jenis_kelamin', 'P');
    }

    // ── Relasi ke User (jika penduduk punya akun) ────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Relasi ke Wilayah Administratif ──────────────────────────────
    public function provinsi()
    {
        return $this->belongsTo(WilayahProvinsi::class, 'provinsi_id');
    }

    public function kabkota()
    {
        return $this->belongsTo(WilayahKabkota::class, 'kabkota_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(WilayahKecamatan::class, 'kecamatan_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(WilayahKelurahan::class, 'kelurahan_id');
    }

    /**
     * Helper: alamat lengkap dengan hierarki wilayah
     */
    public function getAlamatLengkapAttribute(): string
    {
        $parts = [];

        if ($this->alamat)             $parts[] = $this->alamat;
        if ($this->rt)                 $parts[] = 'RT ' . $this->rt;
        if ($this->rw)                 $parts[] = 'RW ' . $this->rw;
        if ($this->kelurahan?->nama)   $parts[] = $this->kelurahan->nama;
        if ($this->kecamatan?->nama)   $parts[] = 'Kec. ' . $this->kecamatan->nama;
        if ($this->kabkota?->nama)     $parts[] = $this->kabkota->nama;
        if ($this->provinsi?->nama)    $parts[] = $this->provinsi->nama;

        return implode(', ', $parts);
    }
}
