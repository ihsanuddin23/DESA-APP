<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PosyanduSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ═══════════════════════════════════════════════════════════════════════
        // 1. POSYANDU (Master Data)
        // ═══════════════════════════════════════════════════════════════════════
        DB::table('posyandu')->insert([
            [
                'nama'          => 'Posyandu Melati I',
                'kode'          => 'PM1',
                'lokasi'        => 'Balai RW 001',
                'rw'            => '001',
                'jenis'         => 'balita',
                'jumlah_kader'  => 5,
                'jumlah_balita' => 68,
                'ketua_kader'   => 'Ibu Siti Aminah',
                'kontak'        => '081234567801',
                'deskripsi'     => 'Posyandu balita di wilayah RW 001, aktif setiap Selasa minggu ke-2.',
                'status'        => 'aktif',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'nama'          => 'Posyandu Melati II',
                'kode'          => 'PM2',
                'lokasi'        => 'Rumah Bu Rini, RT 002',
                'rw'            => '001',
                'jenis'         => 'balita',
                'jumlah_kader'  => 6,
                'jumlah_balita' => 54,
                'ketua_kader'   => 'Ibu Rini Hastuti',
                'kontak'        => '081234567802',
                'deskripsi'     => 'Posyandu balita di RT 002, pelayanan rutin setiap Kamis minggu ke-2.',
                'status'        => 'aktif',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'nama'          => 'Posyandu Mawar',
                'kode'          => 'MW',
                'lokasi'        => 'Balai RW 002',
                'rw'            => '002',
                'jenis'         => 'balita',
                'jumlah_kader'  => 5,
                'jumlah_balita' => 62,
                'ketua_kader'   => 'Ibu Dewi Lestari',
                'kontak'        => '081234567803',
                'deskripsi'     => 'Posyandu balita utama di RW 002.',
                'status'        => 'aktif',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'nama'          => 'Posyandu Anggrek',
                'kode'          => 'AG',
                'lokasi'        => 'PAUD Tunas Bangsa',
                'rw'            => '002',
                'jenis'         => 'balita',
                'jumlah_kader'  => 6,
                'jumlah_balita' => 71,
                'ketua_kader'   => 'Ibu Endah Puspitasari',
                'kontak'        => '081234567804',
                'deskripsi'     => 'Posyandu yang terintegrasi dengan PAUD Tunas Bangsa.',
                'status'        => 'aktif',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'nama'          => 'Posyandu Kenanga',
                'kode'          => 'KN',
                'lokasi'        => 'Balai RW 003',
                'rw'            => '003',
                'jenis'         => 'balita',
                'jumlah_kader'  => 5,
                'jumlah_balita' => 48,
                'ketua_kader'   => 'Ibu Sumiyati',
                'kontak'        => '081234567805',
                'deskripsi'     => 'Posyandu balita di RW 003.',
                'status'        => 'aktif',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'nama'          => 'Posyandu Lansia Cempaka',
                'kode'          => 'CP-LNS',
                'lokasi'        => 'Balai Desa',
                'rw'            => 'Desa',
                'jenis'         => 'lansia',
                'jumlah_kader'  => 5,
                'jumlah_balita' => 37,
                'ketua_kader'   => 'Ibu Ratna Juwita',
                'kontak'        => '081234567806',
                'deskripsi'     => 'Posyandu khusus lansia tingkat desa, pemeriksaan kesehatan & senam lansia.',
                'status'        => 'aktif',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);

        // Ambil ID posyandu untuk relasi jadwal
        $posyanduIds = DB::table('posyandu')->pluck('id', 'kode');

        // ═══════════════════════════════════════════════════════════════════════
        // 2. JADWAL POSYANDU — 2 bulan mendatang (berjalan + yang akan datang)
        // ═══════════════════════════════════════════════════════════════════════
        $jadwalTemplate = [
            // Posyandu Melati I - Selasa minggu ke-2
            ['kode' => 'PM1', 'minggu_ke' => 2, 'hari' => 2, 'mulai' => '08:00:00', 'selesai' => '11:00:00'],
            // Posyandu Melati II - Kamis minggu ke-2
            ['kode' => 'PM2', 'minggu_ke' => 2, 'hari' => 4, 'mulai' => '08:00:00', 'selesai' => '11:00:00'],
            // Posyandu Mawar - Rabu minggu ke-3
            ['kode' => 'MW',  'minggu_ke' => 3, 'hari' => 3, 'mulai' => '08:00:00', 'selesai' => '11:00:00'],
            // Posyandu Anggrek - Jumat minggu ke-3
            ['kode' => 'AG',  'minggu_ke' => 3, 'hari' => 5, 'mulai' => '08:00:00', 'selesai' => '11:00:00'],
            // Posyandu Kenanga - Senin minggu ke-4
            ['kode' => 'KN',  'minggu_ke' => 4, 'hari' => 1, 'mulai' => '08:00:00', 'selesai' => '11:00:00'],
            // Posyandu Lansia Cempaka - Sabtu minggu ke-1
            ['kode' => 'CP-LNS', 'minggu_ke' => 1, 'hari' => 6, 'mulai' => '07:30:00', 'selesai' => '10:00:00'],
        ];

        $kegiatanOptions = [
            'Penimbangan & pengukuran rutin',
            'Imunisasi dasar',
            'Pemberian Vitamin A',
            'Pemeriksaan ibu hamil (ANC)',
            'Pemberian Makanan Tambahan (PMT)',
            'Penyuluhan gizi & MPASI',
        ];

        $kegiatanLansia = [
            'Pemeriksaan tekanan darah',
            'Senam lansia bersama',
            'Konsultasi kesehatan',
            'Pengukuran gula darah & kolesterol',
        ];

        $rows = [];

        // Generate untuk bulan ini & bulan depan
        foreach ([0, 1] as $bulanOffset) {
            $bulan = $now->copy()->addMonths($bulanOffset);

            foreach ($jadwalTemplate as $jadwal) {
                // Hitung tanggal berdasarkan minggu ke-N dan hari ke-M di bulan tsb
                $firstDay = $bulan->copy()->startOfMonth();
                // Cari hari pertama yang sesuai (hari ke-M)
                while ($firstDay->dayOfWeek !== $jadwal['hari']) {
                    $firstDay->addDay();
                }
                // Tambahkan (minggu_ke - 1) minggu
                $tanggal = $firstDay->copy()->addWeeks($jadwal['minggu_ke'] - 1);

                // Skip kalau tanggal sudah lewat
                if ($tanggal->lt($now->copy()->startOfDay())) {
                    continue;
                }

                // Tentukan kegiatan (pakai pool acak yang berbeda-beda)
                $pool = str_contains($jadwal['kode'], 'LNS') ? $kegiatanLansia : $kegiatanOptions;
                $kegiatan = $pool[array_rand($pool)];

                // Status: kalau bulan ini → terjadwal; kalau sudah sangat dekat → berlangsung
                $status = 'terjadwal';

                $rows[] = [
                    'posyandu_id'   => $posyanduIds[$jadwal['kode']] ?? null,
                    'tanggal'       => $tanggal->toDateString(),
                    'waktu_mulai'   => $jadwal['mulai'],
                    'waktu_selesai' => $jadwal['selesai'],
                    'kegiatan'      => $kegiatan,
                    'catatan'       => null,
                    'status'        => $status,
                    'input_oleh'    => null,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }
        }

        // Tambah beberapa data historis (jadwal yang sudah selesai)
        foreach ([-1, -2] as $bulanOffset) {
            $bulan = $now->copy()->addMonths($bulanOffset);

            foreach ($jadwalTemplate as $jadwal) {
                $firstDay = $bulan->copy()->startOfMonth();
                while ($firstDay->dayOfWeek !== $jadwal['hari']) {
                    $firstDay->addDay();
                }
                $tanggal = $firstDay->copy()->addWeeks($jadwal['minggu_ke'] - 1);

                $pool = str_contains($jadwal['kode'], 'LNS') ? $kegiatanLansia : $kegiatanOptions;
                $kegiatan = $pool[array_rand($pool)];

                $rows[] = [
                    'posyandu_id'   => $posyanduIds[$jadwal['kode']] ?? null,
                    'tanggal'       => $tanggal->toDateString(),
                    'waktu_mulai'   => $jadwal['mulai'],
                    'waktu_selesai' => $jadwal['selesai'],
                    'kegiatan'      => $kegiatan,
                    'catatan'       => 'Kegiatan rutin bulanan',
                    'status'        => 'selesai',
                    'input_oleh'    => null,
                    'created_at'    => $now->copy()->addMonths($bulanOffset),
                    'updated_at'    => $now->copy()->addMonths($bulanOffset),
                ];
            }
        }

        DB::table('posyandu_jadwal')->insert($rows);

        $this->command->info('✓ PosyanduSeeder selesai: 6 posyandu + ' . count($rows) . ' jadwal.');
    }
}
