<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};

class AuditService
{
    public static function log(
        string  $action,
        string  $description = null,
        mixed   $model       = null,
        array   $oldValues   = null,
        array   $newValues   = null,
        Request $request     = null
    ): void {
        try {
            $req = $request ?? request();

            AuditLog::create([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'description' => $description,
                'model_type'  => $model ? get_class($model) : null,
                'model_id'    => $model?->getKey(),
                'old_values'  => $oldValues,
                'new_values'  => $newValues,
                'ip_address'  => $req?->ip(),
                'user_agent'  => $req?->userAgent(),
                'route'       => $req?->path(),
                'method'      => $req?->method(),
                'created_at'  => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('AuditService error: ' . $e->getMessage());
        }
    }

    // ─── Shorthand Helpers ────────────────────────────────────────────────────
    public static function logLogin(string $description = 'User logged in'): void
    {
        static::log('login', $description);
    }

    public static function logLogout(): void
    {
        static::log('logout', 'User logged out');
    }

    public static function logRegister($user): void
    {
        static::log('register', "New user registered: {$user->email}", $user);
    }

    public static function logFailedLogin(string $email): void
    {
        static::log('login_failed', "Failed login attempt for: {$email}");
    }

    public static function logProfileUpdate($user, array $old, array $new): void
    {
        static::log('profile_update', 'Profile updated', $user, $old, $new);
    }

    public static function logPasswordChange($user): void
    {
        static::log('password_change', 'Password changed', $user);
    }

    public static function logEmailVerified($user): void
    {
        static::log('email_verified', 'Email verified', $user);
    }

    public static function log2faEnabled($user): void
    {
        static::log('2fa_enabled', 'Two-factor authentication enabled', $user);
    }
}
