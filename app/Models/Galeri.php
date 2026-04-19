<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeri';

    protected $fillable = [
        'judul', 'keterangan', 'file', 'status', 'user_id',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
