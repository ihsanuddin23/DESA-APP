<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Identitas Desa
    |--------------------------------------------------------------------------
    |
    | Data identitas desa yang ditampilkan di seluruh halaman website.
    | Nilai diambil dari file .env, ubah di sana — bukan di file ini.
    |
    */

    'nama_desa'  => env('SID_NAMA_DESA',  'Desa Sukamaju'),
    'kecamatan'  => env('SID_KECAMATAN',  'Kec. Ciawi'),
    'kabupaten'  => env('SID_KABUPATEN',  'Kab. Bogor'),
    'provinsi'   => env('SID_PROVINSI',   'Jawa Barat'),
    'kode_pos'   => env('SID_KODE_POS',   '16720'),

    /*
    |--------------------------------------------------------------------------
    | Kode Wilayah Default (Kemendagri)
    |--------------------------------------------------------------------------
    |
    | Kode wilayah administratif desa. Nilai ini dipakai sebagai default
    | di form tambah penduduk supaya admin tidak perlu pilih 4x tiap input.
    |
    */

    'kode_provinsi'  => env('SID_KODE_PROVINSI',  '32'),         // Jawa Barat
    'kode_kabkota'   => env('SID_KODE_KABKOTA',   '3216'),       // Kab. Bekasi
    'kode_kecamatan' => env('SID_KODE_KECAMATAN', '321608'),     // Cikarang Barat
    'kode_kelurahan' => env('SID_KODE_KELURAHAN', '3216082011'), // Cikedokan

    /*
    |--------------------------------------------------------------------------
    | Kontak Desa
    |--------------------------------------------------------------------------
    */

    'telepon'    => env('SID_TELEPON',    '(0251) 8123-456'),
    'email'      => env('SID_EMAIL',      'desa@sukamaju.desa.id'),
    'alamat'     => env('SID_ALAMAT',     'Kecamatan Cikarang Barat, Kabupaten Bekasi, Jawa Barat.'),

    /*
    |--------------------------------------------------------------------------
    | Koordinat Maps
    |--------------------------------------------------------------------------
    */

    'lat'        => env('SID_LAT',        '-6.65'),
    'lng'        => env('SID_LNG',        '106.85'),

    /*
    |--------------------------------------------------------------------------
    | Sosial Media (opsional)
    |--------------------------------------------------------------------------
    */

    'facebook'   => env('SID_FACEBOOK',   ''),
    'instagram'  => env('SID_INSTAGRAM',  ''),
    'youtube'    => env('SID_YOUTUBE',    ''),

];