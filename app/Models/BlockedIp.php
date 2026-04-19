<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    protected $fillable = [
        'ip_address', 'reason', 'is_permanent',
        'blocked_until', 'attempt_count',
    ];

    protected function casts(): array
    {
        return [
            'blocked_until' => 'datetime',
            'is_permanent'  => 'boolean',
        ];
    }

    public function isActive(): bool
    {
        if ($this->is_permanent) return true;
        return $this->blocked_until && $this->blocked_until->isFuture();
    }

    public static function isBlocked(string $ip): bool
    {
        $record = static::where('ip_address', $ip)->first();
        return $record && $record->isActive();
    }

    // Auto-block an IP after too many failed attempts
    public static function autoBlock(string $ip, string $reason = 'Too many failed login attempts'): void
    {
        static::updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason'       => $reason,
                'is_permanent' => false,
                'blocked_until'=> now()->addHours(24),
            ]
        );
    }
}