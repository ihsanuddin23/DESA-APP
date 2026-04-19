<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role management: admin, rt, warga
            $table->enum('role', ['admin', 'rt', 'warga'])->default('warga')->after('email');

            // Account status - blokir akun
            $table->boolean('is_active')->default(true)->after('role');

            // Two Factor Authentication
            $table->boolean('two_factor_enabled')->default(false)->after('is_active');
            $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');

            // Login tracking
            $table->timestamp('last_login_at')->nullable()->after('two_factor_secret');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->text('last_login_device')->nullable()->after('last_login_ip');
            $table->unsignedInteger('login_count')->default(0)->after('last_login_device');

            // Account lockout fields
            $table->timestamp('locked_until')->nullable()->after('login_count');

            // Profile
            $table->string('phone', 20)->nullable()->after('locked_until');
            $table->string('nik', 16)->nullable()->unique()->after('phone');

            // Soft deletes
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'is_active', 'two_factor_enabled', 'two_factor_secret',
                'last_login_at', 'last_login_ip', 'last_login_device', 'login_count',
                'locked_until', 'phone', 'nik', 'deleted_at',
            ]);
        });
    }
};