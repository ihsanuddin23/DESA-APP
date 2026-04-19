<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukturDesa extends Model
{
    protected $table = 'struktur_desa';

    protected $fillable = [
        'nama', 'jabatan', 'keterangan', 'foto',
        'telepon', 'tampil_publik', 'urutan',
    ];

    protected $casts = [
        'tampil_publik' => 'boolean',
    ];
}