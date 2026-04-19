<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_desa', function (Blueprint $table) {
            $table->id();

            // Identitas
            $table->string('nama_desa')->default('Desa Cikedokan');
            $table->string('kode_desa', 20)->nullable()->comment('Kode Kemendagri');
            $table->string('kepala_desa')->nullable();
            $table->string('tahun_berdiri', 4)->nullable();

            // Visi Misi (long text untuk rich content)
            $table->longText('visi')->nullable();
            $table->longText('misi')->nullable();

            // Sejarah & Profil
            $table->longText('sejarah')->nullable();
            $table->longText('geografis')->nullable()->comment('Batas wilayah, topografi');
            $table->longText('demografi')->nullable()->comment('Gambaran umum penduduk');

            // Data statistik manual (fallback kalau belum sync dari tabel penduduk)
            $table->integer('luas_wilayah_km2')->nullable();
            $table->integer('jumlah_dusun')->nullable();
            $table->integer('jumlah_rw')->nullable();
            $table->integer('jumlah_rt')->nullable();

            // Media
            $table->string('logo')->nullable();
            $table->string('foto_kantor')->nullable();

            // Kontak (bisa override yang di .env)
            $table->string('alamat_kantor')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();

            // Jam pelayanan
            $table->string('jam_pelayanan')->nullable()->comment('Contoh: Senin-Jumat 08.00-16.00');

            $table->timestamps();
        });

        // Insert 1 row default supaya tidak perlu cek exists di controller
        DB::table('profil_desa')->insert([
            'nama_desa'     => config('sid.nama_desa', 'Desa Cikedokan'),
            'kepala_desa'   => 'Belum diisi',
            'visi'          => 'Mewujudkan desa yang maju, mandiri, dan sejahtera.',
            'misi'          => "1. Meningkatkan kualitas pelayanan publik\n2. Memberdayakan ekonomi masyarakat\n3. Membangun infrastruktur berkelanjutan\n4. Melestarikan budaya dan lingkungan",
            'sejarah'       => 'Silakan isi sejarah desa Anda melalui panel admin.',
            'alamat_kantor' => 'Jl. Raya Cikedokan',
            'jam_pelayanan' => 'Senin - Jumat, 08.00 - 16.00 WIB',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_desa');
    }
};