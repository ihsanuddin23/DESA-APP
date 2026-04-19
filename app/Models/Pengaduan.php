<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengaduan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengaduan';

    protected $fillable = [
        'kode_tiket',
        'nama_pengadu', 'kontak', 'nik', 'rt', 'rw',
        'kategori', 'judul', 'isi', 'lokasi', 'foto_bukti',
        'status', 'tanggapan', 'ditangani_oleh', 'ditanggapi_pada',
        'prioritas', 'ip_address',
    ];

    protected $casts = [
        'ditanggapi_pada' => 'datetime',
    ];

    // ── Constants ──────────────────────────────────────────────────────────
    const STATUSES = [
        'baru'     => 'Baru',
        'diproses' => 'Sedang Diproses',
        'selesai'  => 'Selesai',
        'ditolak'  => 'Ditolak',
    ];

    const KATEGORI = [
        'infrastruktur' => 'Infrastruktur',
        'kebersihan'    => 'Kebersihan',
        'keamanan'      => 'Keamanan',
        'pelayanan'     => 'Pelayanan Publik',
        'sosial'        => 'Sosial',
        'lingkungan'    => 'Lingkungan',
        'lainnya'       => 'Lainnya',
    ];

    const PRIORITAS = [
        'rendah' => 'Rendah',
        'sedang' => 'Sedang',
        'tinggi' => 'Tinggi',
    ];

    // ── Relations ──────────────────────────────────────────────────────────
    public function penanganan()
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    // ── Accessors ──────────────────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getKategoriLabelAttribute(): string
    {
        return self::KATEGORI[$this->kategori] ?? $this->kategori;
    }

    public function getPrioritasLabelAttribute(): string
    {
        return self::PRIORITAS[$this->prioritas] ?? $this->prioritas;
    }

    /**
     * Warna CSS untuk badge status.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'baru'     => 'warning',
            'diproses' => 'info',
            'selesai'  => 'success',
            'ditolak'  => 'danger',
            default    => 'secondary',
        };
    }

    // ── Helpers ────────────────────────────────────────────────────────────
    /**
     * Generate kode tiket unik: PGD-YYYYMMDD-XXXX
     */
    public static function generateKodeTiket(): string
    {
        do {
            $kode = 'PGD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        } while (self::where('kode_tiket', $kode)->exists());

        return $kode;
    }
}