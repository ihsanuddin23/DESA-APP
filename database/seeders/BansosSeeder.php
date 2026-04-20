<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BansosSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $tahun = (int) date('Y');

        // ═══════════════════════════════════════════════════════════════════════
        // 1. PROGRAM BANSOS
        // ═══════════════════════════════════════════════════════════════════════
        DB::table('bansos_program')->insert([
            [
                'nama'              => 'Program Keluarga Harapan',
                'kode'              => 'PKH',
                'deskripsi'         => 'Bantuan tunai bersyarat untuk keluarga miskin dengan ibu hamil, balita, anak sekolah, lansia, dan penyandang disabilitas berat.',
                'jenis'             => 'pusat',
                'nominal_per_bulan' => 750000,
                'status'            => 'aktif',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'nama'              => 'Bantuan Pangan Non-Tunai',
                'kode'              => 'BPNT',
                'deskripsi'         => 'Bantuan pangan pokok dalam bentuk saldo elektronik yang dapat ditukarkan dengan sembako di e-Warong terdekat.',
                'jenis'             => 'pusat',
                'nominal_per_bulan' => 200000,
                'status'            => 'aktif',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'nama'              => 'BLT Dana Desa',
                'kode'              => 'BLT-DD',
                'deskripsi'         => 'Bantuan Langsung Tunai dari alokasi Dana Desa untuk warga terdampak kemiskinan ekstrem dan rentan di desa.',
                'jenis'             => 'desa',
                'nominal_per_bulan' => 300000,
                'status'            => 'aktif',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'nama'              => 'Kartu Sembako',
                'kode'              => 'KS',
                'deskripsi'         => 'Program pemberian bantuan sembako dalam bentuk saldo elektronik bulanan untuk KPM.',
                'jenis'             => 'pusat',
                'nominal_per_bulan' => 200000,
                'status'            => 'aktif',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'nama'              => 'Bantuan Lansia',
                'kode'              => 'BLU',
                'deskripsi'         => 'Bantuan kesejahteraan sosial untuk warga lanjut usia (60 tahun ke atas) dari keluarga kurang mampu.',
                'jenis'             => 'kabupaten',
                'nominal_per_bulan' => 200000,
                'status'            => 'aktif',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'nama'              => 'Asistensi Sosial Penyandang Disabilitas',
                'kode'              => 'ASPDB',
                'deskripsi'         => 'Asistensi sosial berupa bantuan tunai untuk penyandang disabilitas berat yang tidak mampu merawat diri.',
                'jenis'             => 'provinsi',
                'nominal_per_bulan' => 300000,
                'status'            => 'aktif',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'nama'              => 'BLT Minyak Goreng (Arsip)',
                'kode'              => 'BLT-MG',
                'deskripsi'         => 'Program BLT minyak goreng yang diselenggarakan tahun sebelumnya — saat ini tidak aktif.',
                'jenis'             => 'pusat',
                'nominal_per_bulan' => 100000,
                'status'            => 'nonaktif',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ]);

        // Ambil ID program untuk relasi
        $programIds = DB::table('bansos_program')->pluck('id', 'kode');

        // ═══════════════════════════════════════════════════════════════════════
        // 2. PENERIMA BANSOS — Data Dummy
        // ═══════════════════════════════════════════════════════════════════════
        $penerima = [
            // ── PKH (Program Keluarga Harapan) ─────────────────────────────────
            ['kode' => 'PKH',    'nik' => '3201012001800001', 'nama' => 'Siti Aminah',        'no_kk' => '3201010001230001', 'rt' => '001', 'rw' => '001', 'alamat' => 'Jl. Melati No. 12',     'nominal' => 750000, 'status' => 'aktif', 'ket' => 'Ibu hamil, balita 1 orang'],
            ['kode' => 'PKH',    'nik' => '3201010304790002', 'nama' => 'Ahmad Suryanto',     'no_kk' => '3201010001230002', 'rt' => '001', 'rw' => '001', 'alamat' => 'Jl. Melati No. 18',     'nominal' => 750000, 'status' => 'aktif', 'ket' => 'Anak SD 2 orang'],
            ['kode' => 'PKH',    'nik' => '3201015501850003', 'nama' => 'Rina Kartika',       'no_kk' => '3201010001230003', 'rt' => '002', 'rw' => '001', 'alamat' => 'Jl. Mawar No. 5',       'nominal' => 750000, 'status' => 'aktif', 'ket' => 'Anak SMP 1 orang'],
            ['kode' => 'PKH',    'nik' => '3201010807820004', 'nama' => 'Budi Santoso',       'no_kk' => '3201010001230004', 'rt' => '002', 'rw' => '001', 'alamat' => 'Jl. Mawar No. 21',      'nominal' => 750000, 'status' => 'aktif', 'ket' => 'Disabilitas berat'],
            ['kode' => 'PKH',    'nik' => '3201014402900005', 'nama' => 'Dewi Lestari',       'no_kk' => '3201010001230005', 'rt' => '003', 'rw' => '001', 'alamat' => 'Jl. Anggrek No. 7',     'nominal' => 750000, 'status' => 'aktif', 'ket' => 'Ibu hamil'],
            ['kode' => 'PKH',    'nik' => '3201011205750006', 'nama' => 'Joko Widodo',        'no_kk' => '3201010001230006', 'rt' => '003', 'rw' => '002', 'alamat' => 'Jl. Anggrek No. 15',    'nominal' => 750000, 'status' => 'nonaktif', 'ket' => 'Anak sudah lulus SMA, keluar program'],

            // ── BPNT (Bantuan Pangan Non-Tunai) ────────────────────────────────
            ['kode' => 'BPNT',   'nik' => '3201017001650007', 'nama' => 'Sumiyati',           'no_kk' => '3201010001230007', 'rt' => '001', 'rw' => '001', 'alamat' => 'Jl. Melati No. 25',     'nominal' => 200000, 'status' => 'aktif', 'ket' => null],
            ['kode' => 'BPNT',   'nik' => '3201011603780008', 'nama' => 'Suparman',           'no_kk' => '3201010001230008', 'rt' => '002', 'rw' => '001', 'alamat' => 'Jl. Mawar No. 33',      'nominal' => 200000, 'status' => 'aktif', 'ket' => null],
            ['kode' => 'BPNT',   'nik' => '3201014905820009', 'nama' => 'Ratna Juwita',       'no_kk' => '3201010001230009', 'rt' => '002', 'rw' => '002', 'alamat' => 'Jl. Kenanga No. 2',     'nominal' => 200000, 'status' => 'aktif', 'ket' => null],
            ['kode' => 'BPNT',   'nik' => '3201012810700010', 'nama' => 'Hasan Basri',        'no_kk' => '3201010001230010', 'rt' => '003', 'rw' => '002', 'alamat' => 'Jl. Kenanga No. 10',    'nominal' => 200000, 'status' => 'aktif', 'ket' => null],
            ['kode' => 'BPNT',   'nik' => '3201016201880011', 'nama' => 'Endah Puspitasari',  'no_kk' => '3201010001230011', 'rt' => '004', 'rw' => '002', 'alamat' => 'Jl. Dahlia No. 8',      'nominal' => 200000, 'status' => 'aktif', 'ket' => null],
            ['kode' => 'BPNT',   'nik' => '3201010710680012', 'nama' => 'Bambang Pamungkas',  'no_kk' => '3201010001230012', 'rt' => '004', 'rw' => '002', 'alamat' => 'Jl. Dahlia No. 19',     'nominal' => 200000, 'status' => 'aktif', 'ket' => null],
            ['kode' => 'BPNT',   'nik' => '3201015708920013', 'nama' => 'Fitriani Nur',       'no_kk' => '3201010001230013', 'rt' => '005', 'rw' => '003', 'alamat' => 'Jl. Cempaka No. 3',     'nominal' => 200000, 'status' => 'dicoret', 'ket' => 'Sudah pindah domisili'],

            // ── BLT-DD (BLT Dana Desa) ─────────────────────────────────────────
            ['kode' => 'BLT-DD', 'nik' => '3201010905690014', 'nama' => 'Tukiman',            'no_kk' => '3201010001230014', 'rt' => '001', 'rw' => '001', 'alamat' => 'Jl. Melati No. 40',     'nominal' => 300000, 'status' => 'aktif', 'ket' => 'Kemiskinan ekstrem'],
            ['kode' => 'BLT-DD', 'nik' => '3201017112620015', 'nama' => 'Marwati',            'no_kk' => '3201010001230015', 'rt' => '002', 'rw' => '001', 'alamat' => 'Jl. Mawar No. 50',      'nominal' => 300000, 'status' => 'aktif', 'ket' => 'Janda, tidak bekerja'],
            ['kode' => 'BLT-DD', 'nik' => '3201012308550016', 'nama' => 'Sutrisno',           'no_kk' => '3201010001230016', 'rt' => '003', 'rw' => '001', 'alamat' => 'Jl. Anggrek No. 22',    'nominal' => 300000, 'status' => 'aktif', 'ket' => 'Lansia, sakit kronis'],
            ['kode' => 'BLT-DD', 'nik' => '3201014203770017', 'nama' => 'Yuyun Wahyuni',      'no_kk' => '3201010001230017', 'rt' => '005', 'rw' => '003', 'alamat' => 'Jl. Cempaka No. 14',    'nominal' => 300000, 'status' => 'aktif', 'ket' => 'Kepala keluarga perempuan'],
            ['kode' => 'BLT-DD', 'nik' => '3201010411860018', 'nama' => 'Rudi Hartono',       'no_kk' => '3201010001230018', 'rt' => '006', 'rw' => '003', 'alamat' => 'Jl. Flamboyan No. 6',   'nominal' => 300000, 'status' => 'aktif', 'ket' => 'Terdampak PHK'],

            // ── KS (Kartu Sembako) ─────────────────────────────────────────────
            ['kode' => 'KS',     'nik' => '3201016509730019', 'nama' => 'Kartika Sari',       'no_kk' => '3201010001230019', 'rt' => '001', 'rw' => '001', 'alamat' => 'Jl. Melati No. 55',     'nominal' => 200000, 'status' => 'aktif', 'ket' => null],
            ['kode' => 'KS',     'nik' => '3201011202810020', 'nama' => 'Dedi Mulyadi',       'no_kk' => '3201010001230020', 'rt' => '002', 'rw' => '002', 'alamat' => 'Jl. Kenanga No. 18',    'nominal' => 200000, 'status' => 'aktif', 'ket' => null],
            ['kode' => 'KS',     'nik' => '3201017508790021', 'nama' => 'Susilawati',         'no_kk' => '3201010001230021', 'rt' => '004', 'rw' => '002', 'alamat' => 'Jl. Dahlia No. 27',     'nominal' => 200000, 'status' => 'aktif', 'ket' => null],
            ['kode' => 'KS',     'nik' => '3201010301900022', 'nama' => 'Agus Salim',         'no_kk' => '3201010001230022', 'rt' => '005', 'rw' => '003', 'alamat' => 'Jl. Cempaka No. 20',    'nominal' => 200000, 'status' => 'aktif', 'ket' => null],
            ['kode' => 'KS',     'nik' => '3201013006830023', 'nama' => 'Ani Ristanti',       'no_kk' => '3201010001230023', 'rt' => '006', 'rw' => '003', 'alamat' => 'Jl. Flamboyan No. 15',  'nominal' => 200000, 'status' => 'aktif', 'ket' => null],

            // ── BLU (Bantuan Lansia) ───────────────────────────────────────────
            ['kode' => 'BLU',    'nik' => '3201010105550024', 'nama' => 'Mbah Karto',         'no_kk' => '3201010001230024', 'rt' => '001', 'rw' => '001', 'alamat' => 'Jl. Melati No. 60',     'nominal' => 200000, 'status' => 'aktif', 'ket' => 'Usia 69 tahun'],
            ['kode' => 'BLU',    'nik' => '3201016812580025', 'nama' => 'Nenek Saripah',      'no_kk' => '3201010001230025', 'rt' => '003', 'rw' => '001', 'alamat' => 'Jl. Anggrek No. 30',    'nominal' => 200000, 'status' => 'aktif', 'ket' => 'Usia 66 tahun, hidup sendiri'],
            ['kode' => 'BLU',    'nik' => '3201011511520026', 'nama' => 'Kakek Suryo',        'no_kk' => '3201010001230026', 'rt' => '004', 'rw' => '002', 'alamat' => 'Jl. Dahlia No. 33',     'nominal' => 200000, 'status' => 'aktif', 'ket' => 'Usia 71 tahun'],
            ['kode' => 'BLU',    'nik' => '3201012509540027', 'nama' => 'Mbah Wagiyem',       'no_kk' => '3201010001230027', 'rt' => '006', 'rw' => '003', 'alamat' => 'Jl. Flamboyan No. 22',  'nominal' => 200000, 'status' => 'aktif', 'ket' => 'Usia 69 tahun, janda'],

            // ── ASPDB (Asistensi Sosial Penyandang Disabilitas) ────────────────
            ['kode' => 'ASPDB',  'nik' => '3201010408950028', 'nama' => 'Rizal Pratama',      'no_kk' => '3201010001230028', 'rt' => '002', 'rw' => '001', 'alamat' => 'Jl. Mawar No. 60',      'nominal' => 300000, 'status' => 'aktif', 'ket' => 'Disabilitas fisik berat'],
            ['kode' => 'ASPDB',  'nik' => '3201014710870029', 'nama' => 'Indah Permata',      'no_kk' => '3201010001230029', 'rt' => '005', 'rw' => '003', 'alamat' => 'Jl. Cempaka No. 28',    'nominal' => 300000, 'status' => 'aktif', 'ket' => 'Disabilitas mental berat'],
            ['kode' => 'ASPDB',  'nik' => '3201011206910030', 'nama' => 'Eko Saputra',        'no_kk' => '3201010001230030', 'rt' => '006', 'rw' => '003', 'alamat' => 'Jl. Flamboyan No. 30',  'nominal' => 300000, 'status' => 'aktif', 'ket' => 'Disabilitas ganda'],
        ];

        $rows = [];
        foreach ($penerima as $p) {
            $rows[] = [
                'program_id'    => $programIds[$p['kode']] ?? null,
                'penduduk_id'   => null,
                'nik'           => $p['nik'],
                'nama_penerima' => $p['nama'],
                'no_kk'         => $p['no_kk'],
                'rt'            => $p['rt'],
                'rw'            => $p['rw'],
                'alamat'        => $p['alamat'],
                'tahun'         => $tahun,
                'periode'       => 'tahunan',
                'nominal'       => $p['nominal'],
                'status'        => $p['status'],
                'keterangan'    => $p['ket'],
                'input_oleh'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        // Tambahkan beberapa data tahun lalu (untuk filter tahun berjalan)
        $tahunLalu = $tahun - 1;
        $dataLama = [
            ['kode' => 'PKH',    'nik' => '3201012001800001', 'nama' => 'Siti Aminah',      'rt' => '001', 'rw' => '001', 'nominal' => 750000],
            ['kode' => 'BPNT',   'nik' => '3201017001650007', 'nama' => 'Sumiyati',         'rt' => '001', 'rw' => '001', 'nominal' => 200000],
            ['kode' => 'BLT-DD', 'nik' => '3201010905690014', 'nama' => 'Tukiman',          'rt' => '001', 'rw' => '001', 'nominal' => 300000],
            ['kode' => 'KS',     'nik' => '3201016509730019', 'nama' => 'Kartika Sari',     'rt' => '001', 'rw' => '001', 'nominal' => 200000],
            ['kode' => 'BLU',    'nik' => '3201010105550024', 'nama' => 'Mbah Karto',       'rt' => '001', 'rw' => '001', 'nominal' => 200000],
        ];

        foreach ($dataLama as $p) {
            $rows[] = [
                'program_id'    => $programIds[$p['kode']] ?? null,
                'penduduk_id'   => null,
                'nik'           => $p['nik'],
                'nama_penerima' => $p['nama'],
                'no_kk'         => null,
                'rt'            => $p['rt'],
                'rw'            => $p['rw'],
                'alamat'        => null,
                'tahun'         => $tahunLalu,
                'periode'       => 'tahunan',
                'nominal'       => $p['nominal'],
                'status'        => 'nonaktif',
                'keterangan'    => 'Data historis ' . $tahunLalu,
                'input_oleh'    => null,
                'created_at'    => $now->copy()->subYear(),
                'updated_at'    => $now->copy()->subYear(),
            ];
        }

        DB::table('bansos_penerima')->insert($rows);

        $this->command->info('✓ BansosSeeder selesai: 7 program + ' . count($rows) . ' data penerima.');
    }
}
