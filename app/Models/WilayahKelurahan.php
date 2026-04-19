<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class WilayahKelurahan extends Model
{
    use HasFactory;

    protected $table = 'wilayah_kelurahan';

    protected $fillable = [
        'kecamatan_id', 'kode', 'nama',
        'lat', 'lng',
        'bb_minlat', 'bb_maxlat', 'bb_minlng', 'bb_maxlng',
        'geojson_polygon',
    ];

    protected $casts = [
        'lat'       => 'decimal:7',
        'lng'       => 'decimal:7',
        'bb_minlat' => 'decimal:7',
        'bb_maxlat' => 'decimal:7',
        'bb_minlng' => 'decimal:7',
        'bb_maxlng' => 'decimal:7',
    ];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(WilayahKecamatan::class, 'kecamatan_id');
    }

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'kelurahan_id');
    }
}