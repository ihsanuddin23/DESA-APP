<?php
// database/migrations/2024_01_15_000001_create_agenda_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->string('kontak_person')->nullable();
            $table->string('telepon')->nullable();
            $table->enum('kategori', [
                'rapat',
                'musyawarah',
                'gotong_royong',
                'pelatihan',
                'sosialisasi',
                'keagamaan',
                'budaya',
                'olahraga',
                'kesehatan',
                'pendidikan',
                'lainnya'
            ])->default('lainnya');
            $table->enum('status', ['draft', 'publikasi', 'selesai', 'dibatalkan'])->default('draft');
            $table->string('gambar')->nullable();
            $table->boolean('is_highlight')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tanggal_mulai', 'status']);
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};