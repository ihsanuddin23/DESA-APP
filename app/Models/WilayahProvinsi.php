<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WilayahProvinsi extends Model
{
    use HasFactory;

    protected $table = 'wilayah_provinsi';

    protected $fillable = ['kode', 'nama'];

    /**
     * Relasi: 1 Provinsi punya banyak Kab/Kota
     */
    public function kabkota(): HasMany
    {
        return $this->hasMany(WilayahKabkota::class, 'provinsi_id');
    }

    /**
     * Relasi: 1 Provinsi punya banyak Penduduk (opsional, setelah FK penduduk)
     */
    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'provinsi_id');
    }
}
