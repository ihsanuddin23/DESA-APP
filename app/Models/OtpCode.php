<?php
// ============================================================
// FILE: app/Models/OtpCode.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OtpCode extends Model
{
    protected $fillable = [
        'email', 'code', 'type', 'token',
        'is_used', 'attempt_count', 'expires_at',
        'used_at', 'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'is_used'    => 'boolean',
            'expires_at' => 'datetime',
            'used_at'    => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isMaxAttempts(): bool
    {
        return $this->attempt_count >= 5;
    }

    // Generate a new OTP record
    public static function generate(string $email, string $type, string $ip = null): self
    {
        // Invalidate previous OTPs of same type
        static::where('email', $email)
            ->where('type', $type)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        $plainCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return static::create([
            'email'      => $email,
            'code'       => bcrypt($plainCode),
            'type'       => $type,
            'token'      => Str::random(64),
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $ip,
        ]) + ['plain_code' => $plainCode]; // temp holder, not saved
    }

    // Verify OTP - returns bool
    public function verify(string $inputCode): bool
    {
        $this->increment('attempt_count');

        if ($this->isExpired() || $this->is_used || $this->isMaxAttempts()) {
            return false;
        }

        if (!password_verify($inputCode, $this->code)) {
            $this->save();
            return false;
        }

        $this->update(['is_used' => true, 'used_at' => now()]);
        return true;
    }
}