<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Administrator (full access) ──────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@sid.app'],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('Admin@1234'),
                'role'              => User::ROLE_ADMIN,
                'is_active'         => true,
                'email_verified_at' => now(),
                'two_factor_enabled'=> false,
            ]
        );

        // ── 2. Staff Desa (konten + penduduk, tanpa user management) ────────
        User::updateOrCreate(
            ['email' => 'staff@sid.app'],
            [
                'name'              => 'Staff Desa',
                'password'          => Hash::make('Staff@1234'),
                'role'              => User::ROLE_STAFF_DESA,
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // ── 3. Ketua RW 002 (lihat warga di RW-nya saja) ────────────────────
        User::updateOrCreate(
            ['email' => 'rw@sid.app'],
            [
                'name'              => 'Ketua RW 002',
                'password'          => Hash::make('Rw@12345!'),
                'role'              => User::ROLE_RW,
                'rw'                => '002',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // ── 4. Ketua RT 001 di RW 002 (lihat warga di RT-nya saja) ──────────
        User::updateOrCreate(
            ['email' => 'rt@sid.app'],
            [
                'name'              => 'Ketua RT 001',
                'password'          => Hash::make('Rt@12345!'),
                'role'              => User::ROLE_RT,
                'rt'                => '001',
                'rw'                => '002',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // ── 5. Sample Warga ─────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'warga@sid.app'],
            [
                'name'              => 'Budi Santoso',
                'password'          => Hash::make('Warga@123!'),
                'role'              => User::ROLE_WARGA,
                'is_active'         => true,
                'email_verified_at' => now(),
                'nik'               => '3201011234567890',
                'phone'             => '08123456789',
            ]
        );

        // ── Output info ke terminal ─────────────────────────────────────────
        $this->command->info('');
        $this->command->info('✓ Seeder berhasil! Akun default siap digunakan:');
        $this->command->info('');
        $this->command->table(
            ['Role', 'Email', 'Password', 'Scope'],
            [
                ['Administrator', 'admin@sid.app', 'Admin@1234', 'Full access'],
                ['Staff Desa',    'staff@sid.app', 'Staff@1234', 'Konten + Penduduk'],
                ['Ketua RW',      'rw@sid.app',    'Rw@12345!',  'RW 002'],
                ['Ketua RT',      'rt@sid.app',    'Rt@12345!',  'RT 001 / RW 002'],
                ['Warga',         'warga@sid.app', 'Warga@123!', 'Dashboard pribadi'],
            ]
        );
        $this->command->warn('⚠  Segera ubah password default setelah instalasi!');
        $this->command->info('');
    }
}
