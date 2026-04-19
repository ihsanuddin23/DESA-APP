<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();

            // Kode tiket unik untuk tracking (mis: PGD-20260419-A3F7)
            $table->string('kode_tiket', 25)->unique();

            // Identitas pengadu
            $table->string('nama_pengadu', 100);
            $table->string('kontak', 50)->nullable()->comment('No HP / Email');
            $table->string('nik', 16)->nullable();
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();

            // Konten aduan
            $table->enum('kategori', [
                'infrastruktur',
                'kebersihan',
                'keamanan',
                'pelayanan',
                'sosial',
                'lingkungan',
                'lainnya',
            ]);
            $table->string('judul', 200);
            $table->text('isi');
            $table->string('lokasi', 255)->nullable();
            $table->string('foto_bukti')->nullable()->comment('Path file foto');

            // Status workflow
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])
                  ->default('baru');

            // Tanggapan dari admin/staff
            $table->text('tanggapan')->nullable();
            $table->foreignId('ditangani_oleh')->nullable()
                  ->constrained('users')->nullOnDelete()
                  ->comment('User admin/staff yang menangani');
            $table->timestamp('ditanggapi_pada')->nullable();

            // Prioritas (diisi admin)
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])
                  ->default('sedang');

            // IP untuk anti-spam
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('status');
            $table->index('kategori');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
