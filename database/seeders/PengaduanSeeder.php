<?php

namespace Database\Seeders;

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PengaduanSeeder extends Seeder
{
    public function run(): void
    {
        // Cari admin pertama untuk di-assign sebagai penangan
        $admin = User::where('role', 'admin')->first();
        $staff = User::where('role', 'staff_desa')->first();

        $pengaduanList = [
            // ═══════ PENGADUAN SELESAI ═══════
            [
                'nama_pengadu' => 'Budi Santoso',
                'kontak'       => '081234567890',
                'nik'          => '3216082001850001',
                'rt'           => '001', 'rw' => '002',
                'kategori'     => 'infrastruktur',
                'judul'        => 'Jalan berlubang di depan gang masjid Al-Ikhlas',
                'isi'          => 'Sudah 2 minggu jalan di depan gang masjid Al-Ikhlas RT 001/RW 002 berlubang besar dengan diameter sekitar 50cm dan kedalaman 15cm. Kondisi ini sangat membahayakan pengendara motor, terutama di malam hari. Sudah beberapa kali pengendara jatuh karena tidak melihat lubang tersebut. Mohon segera diperbaiki sebelum terjadi kecelakaan yang lebih parah.',
                'lokasi'       => 'Jl. Mawar Raya, depan gang masjid Al-Ikhlas, RT 001/RW 002',
                'prioritas'    => 'tinggi',
                'status'       => 'selesai',
                'tanggapan'    => 'Terima kasih Pak Budi atas laporannya. Tim Dinas PU sudah survey lokasi pada tanggal 15 April dan perbaikan jalan telah dilaksanakan pada tanggal 18 April 2026. Jalan sudah ditambal dengan aspal baru. Kami mohon maaf atas ketidaknyamanannya dan terima kasih atas kepedulian Bapak terhadap lingkungan sekitar.',
                'ditangani_oleh'  => $admin?->id,
                'ditanggapi_pada' => Carbon::now()->subDays(2),
                'created_at'      => Carbon::now()->subDays(15),
                'updated_at'      => Carbon::now()->subDays(2),
            ],
            [
                'nama_pengadu' => 'Siti Rahayu',
                'kontak'       => 'siti.rahayu@gmail.com',
                'nik'          => '3216082001900002',
                'rt'           => '002', 'rw' => '002',
                'kategori'     => 'kebersihan',
                'judul'        => 'Tumpukan sampah tidak diangkut seminggu',
                'isi'          => 'TPS sampah di RT 002 RW 002 sudah seminggu tidak diangkut petugas kebersihan. Baunya sudah sangat mengganggu warga sekitar, dan banyak lalat serta tikus yang mulai berdatangan. Kami khawatir akan menimbulkan penyakit. Mohon tindak lanjut segera.',
                'lokasi'       => 'TPS RT 002/RW 002, belakang warung Bu Ijah',
                'prioritas'    => 'tinggi',
                'status'       => 'selesai',
                'tanggapan'    => 'Mohon maaf atas keterlambatan pengangkutan sampah. Setelah kami cek, ada masalah dengan kendaraan pengangkut yang sedang rusak. Alhamdulillah sudah diperbaiki dan sampah sudah diangkut pada tanggal 20 April 2026. Jadwal pengangkutan normal kembali setiap Senin, Rabu, dan Jumat. Terima kasih atas laporannya.',
                'ditangani_oleh'  => $staff?->id ?? $admin?->id,
                'ditanggapi_pada' => Carbon::now()->subDays(5),
                'created_at'      => Carbon::now()->subDays(10),
                'updated_at'      => Carbon::now()->subDays(5),
            ],
            [
                'nama_pengadu' => 'Ahmad Fauzi',
                'kontak'       => '082345678901',
                'nik'          => '3216082001780003',
                'rt'           => '003', 'rw' => '002',
                'kategori'     => 'pelayanan',
                'judul'        => 'Pelayanan pengurusan surat domisili lambat',
                'isi'          => 'Saya mengurus surat keterangan domisili sejak tanggal 5 April dan sampai sekarang belum selesai. Padahal hanya menunggu tanda tangan pak kades. Ini sangat menghambat saya untuk keperluan administrasi bank. Mohon pelayanan ditingkatkan.',
                'lokasi'       => 'Kantor Desa Cikedokan',
                'prioritas'    => 'sedang',
                'status'       => 'selesai',
                'tanggapan'    => 'Mohon maaf atas keterlambatan, Pak Ahmad. Surat domisili Bapak sudah selesai dan bisa diambil di kantor desa. Ke depannya kami akan menerapkan sistem tracking surat online agar prosesnya lebih transparan. Terima kasih atas masukan konstruktifnya.',
                'ditangani_oleh'  => $admin?->id,
                'ditanggapi_pada' => Carbon::now()->subDays(7),
                'created_at'      => Carbon::now()->subDays(12),
                'updated_at'      => Carbon::now()->subDays(7),
            ],

            // ═══════ PENGADUAN DIPROSES ═══════
            [
                'nama_pengadu' => 'Dewi Lestari',
                'kontak'       => '083456789012',
                'nik'          => '3216082001920004',
                'rt'           => '001', 'rw' => '003',
                'kategori'     => 'keamanan',
                'judul'        => 'Lampu penerangan jalan mati di area perumahan',
                'isi'          => '3 tiang lampu penerangan jalan umum (PJU) di area perumahan RT 001/RW 003 mati total sudah 2 minggu. Kondisi malam hari menjadi sangat gelap dan rawan tindak kriminal. Sudah ada beberapa laporan pencurian sepeda motor di area ini akhir-akhir ini. Mohon segera diperbaiki.',
                'lokasi'       => 'Jl. Melati, sepanjang RT 001/RW 003',
                'prioritas'    => 'tinggi',
                'status'       => 'diproses',
                'tanggapan'    => 'Terima kasih laporannya, Bu Dewi. Tim kami sudah mengajukan perbaikan PJU ke Dinas Perhubungan. Estimasi perbaikan akan selesai dalam 3-5 hari kerja ke depan. Sementara ini kami akan menambah patroli siskamling di area tersebut.',
                'ditangani_oleh'  => $admin?->id,
                'ditanggapi_pada' => Carbon::now()->subDays(1),
                'created_at'      => Carbon::now()->subDays(4),
                'updated_at'      => Carbon::now()->subDays(1),
            ],
            [
                'nama_pengadu' => 'Pak Hartono',
                'kontak'       => '085678901234',
                'rt'           => '002', 'rw' => '003',
                'kategori'     => 'lingkungan',
                'judul'        => 'Pohon tua miring berpotensi tumbang',
                'isi'          => 'Di depan rumah saya ada pohon asem tua yang sudah miring sekitar 30 derajat dan akarnya sudah mulai terangkat. Musim hujan ini saya sangat khawatir pohon akan tumbang dan menimpa rumah atau kabel listrik. Mohon ditindaklanjuti oleh petugas.',
                'lokasi'       => 'Jl. Kenanga No. 15, RT 002/RW 003',
                'prioritas'    => 'tinggi',
                'status'       => 'diproses',
                'tanggapan'    => 'Pak Hartono, terima kasih atas laporannya. Kami sudah koordinasi dengan Dinas Pertamanan dan Kehutanan untuk survey dan pemangkasan/penebangan pohon tersebut. Jadwalnya tanggal 25 April 2026. Mohon pagi hari tersebut area rumah Bapak dikosongkan dulu untuk keamanan.',
                'ditangani_oleh'  => $staff?->id ?? $admin?->id,
                'ditanggapi_pada' => Carbon::now()->subHours(8),
                'created_at'      => Carbon::now()->subDays(3),
                'updated_at'      => Carbon::now()->subHours(8),
            ],
            [
                'nama_pengadu' => 'Ibu Ratna',
                'kontak'       => '087890123456',
                'nik'          => '3216082001850005',
                'rt'           => '004', 'rw' => '002',
                'kategori'     => 'sosial',
                'judul'        => 'Permohonan bantuan untuk warga kurang mampu',
                'isi'          => 'Saya ingin melaporkan tetangga saya, Pak Sulaiman (usia 72 tahun), yang tinggal sendirian dan kondisi ekonominya sangat memprihatinkan. Beliau belum terdata sebagai penerima bansos padahal sangat membutuhkan. Mohon didata dan dibantu sesuai program yang ada.',
                'lokasi'       => 'Jl. Anggrek No. 8, RT 004/RW 002',
                'prioritas'    => 'sedang',
                'status'       => 'diproses',
                'tanggapan'    => 'Terima kasih, Bu Ratna, atas kepeduliannya. Tim kesejahteraan sosial desa akan mengunjungi Pak Sulaiman minggu ini untuk verifikasi data dan memproses pendaftaran beliau ke program bansos (PKH/BPNT/BLT). Kami akan update progresnya.',
                'ditangani_oleh'  => $admin?->id,
                'ditanggapi_pada' => Carbon::now()->subDays(2),
                'created_at'      => Carbon::now()->subDays(6),
                'updated_at'      => Carbon::now()->subDays(2),
            ],

            // ═══════ PENGADUAN BARU (BELUM DITANGANI) ═══════
            [
                'nama_pengadu' => 'Rizal Maulana',
                'kontak'       => '089012345678',
                'nik'          => '3216082001950006',
                'rt'           => '003', 'rw' => '001',
                'kategori'     => 'infrastruktur',
                'judul'        => 'Saluran air tersumbat menyebabkan banjir',
                'isi'          => 'Ketika hujan deras kemarin malam, air meluap dari saluran drainase karena tersumbat sampah dan lumpur. Air masuk ke beberapa rumah warga termasuk rumah saya. Perabot dan barang elektronik rusak. Mohon segera dilakukan pembersihan dan perbaikan saluran.',
                'lokasi'       => 'Jl. Cempaka, RT 003/RW 001',
                'prioritas'    => 'tinggi',
                'status'       => 'baru',
                'created_at'   => Carbon::now()->subHours(3),
                'updated_at'   => Carbon::now()->subHours(3),
            ],
            [
                'nama_pengadu' => 'Linda Kusumawati',
                'kontak'       => 'linda.k@yahoo.com',
                'nik'          => '3216082001880007',
                'rt'           => '001', 'rw' => '002',
                'kategori'     => 'kebersihan',
                'judul'        => 'Warga membuang sampah sembarangan ke sungai',
                'isi'          => 'Beberapa warga sering terlihat membuang sampah rumah tangga ke sungai kecil di belakang RT 001/RW 002. Ini mencemari lingkungan dan bisa menyebabkan banjir saat hujan. Mohon ada sosialisasi atau penindakan tegas untuk warga yang melakukan pelanggaran ini.',
                'lokasi'       => 'Sungai kecil belakang RT 001/RW 002',
                'prioritas'    => 'sedang',
                'status'       => 'baru',
                'created_at'   => Carbon::now()->subHours(8),
                'updated_at'   => Carbon::now()->subHours(8),
            ],
            [
                'nama_pengadu' => 'Pak Wahyu',
                'kontak'       => '081122334455',
                'rt'           => '005', 'rw' => '002',
                'kategori'     => 'keamanan',
                'judul'        => 'Anak muda nongkrong sampai larut malam',
                'isi'          => 'Setiap malam ada kerumunan anak muda nongkrong di pos ronda sampai jam 2 pagi. Suara berisik dan sering terdengar ucapan kasar yang mengganggu istirahat warga. Mohon ada teguran dari pak RT atau pihak desa.',
                'lokasi'       => 'Pos ronda RT 005/RW 002',
                'prioritas'    => 'rendah',
                'status'       => 'baru',
                'created_at'   => Carbon::now()->subDay(),
                'updated_at'   => Carbon::now()->subDay(),
            ],
            [
                'nama_pengadu' => 'Bu Sumiyati',
                'kontak'       => '087766554433',
                'rt'           => '002', 'rw' => '001',
                'kategori'     => 'pelayanan',
                'judul'        => 'Permohonan sosialisasi program kesehatan lansia',
                'isi'          => 'Sebagai ketua kelompok lansia RT 002/RW 001, saya ingin mengusulkan diadakannya sosialisasi program kesehatan untuk lansia di kantor desa. Banyak lansia yang belum tahu hak-haknya untuk pemeriksaan kesehatan gratis. Mohon dipertimbangkan.',
                'lokasi'       => 'RT 002/RW 001',
                'prioritas'    => 'sedang',
                'status'       => 'baru',
                'created_at'   => Carbon::now()->subHours(5),
                'updated_at'   => Carbon::now()->subHours(5),
            ],

            // ═══════ PENGADUAN DITOLAK ═══════
            [
                'nama_pengadu' => 'Anonim',
                'kontak'       => 'xxx@email.com',
                'rt'           => '001', 'rw' => '001',
                'kategori'     => 'lainnya',
                'judul'        => 'Tuduhan tanpa bukti',
                'isi'          => 'Saya mau lapor tetangga saya aneh sekali dan pasti menyembunyikan sesuatu. Pokoknya dia pasti salah.',
                'lokasi'       => 'RT 001/RW 001',
                'prioritas'    => 'rendah',
                'status'       => 'ditolak',
                'tanggapan'    => 'Mohon maaf, aduan ini tidak dapat kami proses karena:

1. Tidak ada bukti atau kronologi yang jelas tentang masalah yang dilaporkan
2. Identitas pengadu tidak lengkap (anonim)
3. Bersifat tuduhan tanpa dasar yang berpotensi mencemarkan nama baik

Jika Bapak/Ibu memiliki keluhan yang spesifik, mohon kirim ulang dengan identitas lengkap dan kronologi yang jelas. Terima kasih atas pengertiannya.',
                'ditangani_oleh'  => $admin?->id,
                'ditanggapi_pada' => Carbon::now()->subDays(3),
                'created_at'      => Carbon::now()->subDays(5),
                'updated_at'      => Carbon::now()->subDays(3),
            ],
            [
                'nama_pengadu' => 'Joko Susilo',
                'kontak'       => '081234998877',
                'rt'           => '003', 'rw' => '003',
                'kategori'     => 'lainnya',
                'judul'        => 'Minta uang langsung ke kantor desa',
                'isi'          => 'Saya lagi butuh uang untuk bayar hutang bank. Tolong pak kades kasih saya pinjaman uang dari kas desa ya, nanti saya kembalikan bulan depan.',
                'prioritas'    => 'rendah',
                'status'       => 'ditolak',
                'tanggapan'    => 'Pak Joko, mohon maaf permintaan tidak dapat kami penuhi karena:

1. Kas desa tidak diperuntukkan untuk pinjaman pribadi warga
2. Pencairan dana desa memiliki prosedur dan peruntukkan yang sudah diatur dalam APBDes

Untuk bantuan keuangan, Bapak bisa coba ajukan ke program bansos jika memenuhi kriteria, atau konsultasi dengan pendamping PKH di desa. Semoga dapat dimaklumi.',
                'ditangani_oleh'  => $admin?->id,
                'ditanggapi_pada' => Carbon::now()->subDays(8),
                'created_at'      => Carbon::now()->subDays(10),
                'updated_at'      => Carbon::now()->subDays(8),
            ],
        ];

        foreach ($pengaduanList as $data) {
            // Generate kode tiket unik
            $data['kode_tiket'] = Pengaduan::generateKodeTiket();
            $data['ip_address'] = fake_ip();

            Pengaduan::create($data);
        }

        $total = count($pengaduanList);
        $this->command->info('');
        $this->command->info("✓ Seeder PENGADUAN berhasil! {$total} data dummy tersimpan:");
        $this->command->info('');
        $this->command->table(
            ['Status', 'Jumlah'],
            [
                ['Baru (belum ditangani)', Pengaduan::where('status', 'baru')->count()],
                ['Diproses',               Pengaduan::where('status', 'diproses')->count()],
                ['Selesai',                Pengaduan::where('status', 'selesai')->count()],
                ['Ditolak',                Pengaduan::where('status', 'ditolak')->count()],
            ]
        );
        $this->command->info('');
    }
}

// Helper function untuk generate IP acak (kalau belum ada)
if (!function_exists('fake_ip')) {
    function fake_ip(): string
    {
        return mt_rand(1, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(1, 255);
    }
}