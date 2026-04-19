<?php
// ============================================================
// FILE: app/Models/LoginAttempt.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'email', 'ip_address', 'user_agent',
        'successful', 'failure_reason', 'attempted_at',
    ];

    protected function casts(): array
    {
        return [
            'successful'   => 'boolean',
            'attempted_at' => 'datetime',
        ];
    }

    // Recent failed attempts for an email (last N minutes)
    public static function recentFailedByEmail(string $email, int $minutes = 15): int
    {
        return static::where('email', $email)
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    // Recent failed attempts for an IP
    public static function recentFailedByIp(string $ip, int $minutes = 15): int
    {
        return static::where('ip_address', $ip)
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();
    }
}