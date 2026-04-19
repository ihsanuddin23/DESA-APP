<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();

            // ── Identitas ────────────────────────────────────────────
            $table->string('nik', 16)->unique();
            $table->string('no_kk', 16);
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);

            // ── Kelahiran ────────────────────────────────────────────
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir');
            $table->unsignedTinyInteger('usia')->default(0);

            // ── Sosial ───────────────────────────────────────────────
            $table->enum('agama', [
                'Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'
            ])->default('Islam');
            $table->enum('status_perkawinan', [
                'Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'
            ])->default('Belum Kawin');
            $table->string('pekerjaan')->nullable();
            $table->string('pendidikan')->nullable();

            // ── Alamat ───────────────────────────────────────────────
            $table->text('alamat')->nullable();
            $table->string('rt', 3);
            $table->string('rw', 3)->nullable();

            // ── Keluarga ─────────────────────────────────────────────
            $table->enum('status_hubungan_keluarga', [
                'Kepala Keluarga', 'Istri', 'Anak', 'Orang Tua', 'Famili Lain', 'Lainnya'
            ])->default('Anak');
            $table->string('kewarganegaraan', 50)->default('WNI');

            // ── Status & relasi ──────────────────────────────────────
            $table->boolean('status_aktif')->default(true);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // ── Index untuk query statistik di HomeController ───────
            $table->index('no_kk');
            $table->index('rt');
            $table->index('jenis_kelamin');
            $table->index('usia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};
