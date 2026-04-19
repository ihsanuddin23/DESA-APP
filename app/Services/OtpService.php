<?php

namespace App\Services;

use App\Models\{OtpCode, User};
use App\Notifications\Auth\OtpNotification;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Generate OTP, store it, and send via email.
     * Returns the OtpCode record (with plain_code accessible before save).
     */
    public static function sendOtp(string $email, string $type, string $ip = null): OtpCode
    {
        // Generate 6-digit OTP
        $plainCode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Invalidate previous unused OTPs of same type for same email
        OtpCode::where('email', $email)
            ->where('type', $type)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Store hashed OTP
        $otp = OtpCode::create([
            'email'      => $email,
            'code'       => bcrypt($plainCode),
            'type'       => $type,
            'token'      => bin2hex(random_bytes(32)),
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $ip,
        ]);

        // Find user and send notification
        $user = User::where('email', $email)->first();
        if ($user) {
            try {
                $user->notify(new OtpNotification($plainCode, $type, 10));
            } catch (\Exception $e) {
                Log::error("OTP send failed for {$email}: " . $e->getMessage());
            }
        }

        // Attach plain code temporarily for display/testing (not stored)
        $otp->plain_code = $plainCode;

        return $otp;
    }

    /**
     * Verify OTP token + code pair.
     * Returns the OtpCode if valid, null otherwise.
     */
    public static function verify(string $token, string $inputCode, string $type): ?OtpCode
    {
        $otp = OtpCode::where('token', $token)
            ->where('type', $type)
            ->where('is_used', false)
            ->first();

        if (!$otp) return null;

        // Increment attempt count first
        $otp->increment('attempt_count');

        if ($otp->isExpired() || $otp->isMaxAttempts()) {
            return null;
        }

        if (!password_verify($inputCode, $otp->code)) {
            return null;
        }

        $otp->update(['is_used' => true, 'used_at' => now()]);

        return $otp;
    }

    /**
     * Find active OTP by email and type (for resend throttle check).
     */
    public static function hasRecentOtp(string $email, string $type, int $waitSeconds = 60): bool
    {
        return OtpCode::where('email', $email)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('created_at', '>=', now()->subSeconds($waitSeconds))
            ->exists();
    }
}
