<?php
// database/migrations/2024_01_01_000001_create_apbdes_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel utama APBDes per tahun
        Schema::create('apbdes', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->unique();
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('total_belanja', 15, 2)->default(0);
            $table->decimal('total_pembiayaan', 15, 2)->default(0);
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Detail item APBDes (pendapatan, belanja, pembiayaan)
        Schema::create('apbdes_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apbdes_id')->constrained('apbdes')->cascadeOnDelete();
            $table->enum('jenis', ['pendapatan', 'belanja', 'pembiayaan']);
            $table->string('kode_rekening', 50)->nullable();
            $table->string('uraian');
            $table->string('kategori')->nullable(); // sub-kategori
            $table->decimal('anggaran', 15, 2)->default(0);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['apbdes_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apbdes_items');
        Schema::dropIfExists('apbdes');
    }
};
