<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilDesa extends Model
{
    protected $table = 'profil_desa';

    protected $fillable = [
        'nama_desa', 'kode_desa', 'kepala_desa', 'tahun_berdiri',
        'visi', 'misi', 'sejarah', 'geografis', 'demografi',
        'luas_wilayah_km2', 'jumlah_dusun', 'jumlah_rw', 'jumlah_rt',
        'logo', 'foto_kantor',
        'alamat_kantor', 'telepon', 'email', 'jam_pelayanan',
    ];

    /**
     * Helper untuk ambil instance tunggal (singleton pattern).
     * Selalu ambil row pertama — tidak ada kasus multi-row.
     */
    public static function get(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'nama_desa'     => config('sid.nama_desa', 'Desa Cikedokan'),
            'kepala_desa'   => 'Belum diisi',
            'visi'          => 'Silakan isi visi desa dari panel admin.',
            'misi'          => 'Silakan isi misi desa dari panel admin.',
            'sejarah'       => 'Silakan isi sejarah desa dari panel admin.',
        ]);
    }
}
