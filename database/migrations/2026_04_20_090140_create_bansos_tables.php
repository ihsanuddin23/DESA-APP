<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel program bansos ──────────────────────────────────────────────
        Schema::create('bansos_program', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                                          // PKH, BPNT, BLT Dana Desa, dll
            $table->string('kode')->unique();                                // PKH, BPNT, BLT-DD
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['pusat', 'provinsi', 'kabupaten', 'desa'])->default('pusat');
            $table->decimal('nominal_per_bulan', 12, 2)->nullable();        // nominal bantuan/bulan
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });

        // ── Tabel penerima bansos ─────────────────────────────────────────────
        Schema::create('bansos_penerima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('bansos_program')->cascadeOnDelete();
            $table->foreignId('penduduk_id')->nullable()->constrained('penduduk')->nullOnDelete();
            $table->string('nik', 16);
            $table->string('nama_penerima');
            $table->string('no_kk', 16)->nullable();
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('alamat')->nullable();
            $table->year('tahun');
            $table->enum('periode', ['januari', 'februari', 'maret', 'april', 'mei', 'juni',
                                     'juli', 'agustus', 'september', 'oktober', 'november', 'desember', 'tahunan'])
                  ->default('tahunan');
            $table->decimal('nominal', 12, 2)->nullable();
            $table->enum('status', ['aktif', 'nonaktif', 'dicoret'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->foreignId('input_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['program_id', 'tahun', 'status']);
            $table->index(['nik', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bansos_penerima');
        Schema::dropIfExists('bansos_program');
    }
};