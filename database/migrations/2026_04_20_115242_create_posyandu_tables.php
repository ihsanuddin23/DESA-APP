<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel posyandu (master data) ──────────────────────────────────────
        Schema::create('posyandu', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);                              // Posyandu Melati I, dll
            $table->string('kode', 20)->unique();                     // PM1, PM2, MW1
            $table->string('lokasi', 255);                            // Balai RW 001 / Rumah Bu Rini
            $table->string('rw', 10)->nullable();                     // 001, 002, 003
            $table->enum('jenis', ['balita', 'lansia', 'terpadu'])->default('balita');
            $table->unsignedSmallInteger('jumlah_kader')->default(0);
            $table->unsignedSmallInteger('jumlah_balita')->default(0);
            $table->string('ketua_kader', 100)->nullable();
            $table->string('kontak', 20)->nullable();                 // WA ketua kader
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['rw', 'status']);
        });

        // ── Tabel jadwal kunjungan posyandu ──────────────────────────────────
        Schema::create('posyandu_jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')->constrained('posyandu')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('kegiatan', 255);                          // Penimbangan rutin, Imunisasi, dll
            $table->text('catatan')->nullable();
            $table->enum('status', ['terjadwal', 'berlangsung', 'selesai', 'batal'])->default('terjadwal');
            $table->foreignId('input_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['posyandu_id', 'tanggal']);
            $table->index(['tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posyandu_jadwal');
        Schema::dropIfExists('posyandu');
    }
};
