<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Perluas enum role dari 3 jadi 5 role ──
        // MySQL MODIFY COLUMN di-pakai karena Laravel tidak support ALTER enum langsung.
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role ENUM('admin', 'staff_desa', 'rw', 'rt', 'warga')
            NOT NULL DEFAULT 'warga'
        ");

        // ── 2. Tambah kolom rt & rw untuk assign jabatan ──
        Schema::table('users', function (Blueprint $table) {
            $table->string('rt', 3)->nullable()->after('nik')
                  ->comment('RT yang dipegang (untuk role rt). Contoh: 001');
            $table->string('rw', 3)->nullable()->after('rt')
                  ->comment('RW yang dipegang (untuk role rw dan rt). Contoh: 002');

            // Index untuk query cepat penduduk by rt/rw
            $table->index('rt');
            $table->index('rw');
        });
    }

    public function down(): void
    {
        // ── 1. Kembalikan enum role ke versi lama ──
        // Pastikan dulu tidak ada user dengan role staff_desa atau rw
        DB::statement("UPDATE users SET role = 'warga' WHERE role IN ('staff_desa', 'rw')");
        DB::statement("
            ALTER TABLE users
            MODIFY COLUMN role ENUM('admin', 'rt', 'warga')
            NOT NULL DEFAULT 'warga'
        ");

        // ── 2. Drop kolom rt & rw ──
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['rt']);
            $table->dropIndex(['rw']);
            $table->dropColumn(['rt', 'rw']);
        });
    }
};
