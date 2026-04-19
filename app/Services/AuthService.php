<?php

namespace App\Services;

use App\Models\{User, LoginAttempt, BlockedIp, OtpCode};
use App\Notifications\Auth\SuspiciousLoginNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Log, RateLimiter};
use Illuminate\Validation\ValidationException;

class AuthService
{
    // ─── Brute Force Config ───────────────────────────────────────────────────
    const MAX_ATTEMPTS_EMAIL = 5;   // max failed per email in 15 min
    const MAX_ATTEMPTS_IP    = 20;  // max failed per IP in 15 min
    const LOCKOUT_MINUTES    = 15;  // lockout window
    const AUTO_BLOCK_IP_AT   = 50;  // auto-block IP after X global fails

    /**
     * Attempt to authenticate the user with full security checks.
     *
     * @throws ValidationException
     */
    public function attemptLogin(Request $request): User
    {
        $email    = strtolower(trim($request->input('email')));
        $password = $request->input('password');
        $ip       = $request->ip();
        $ua       = $request->userAgent();

        // 1. Check if IP is blocked
        if (BlockedIp::isBlocked($ip)) {
            $this->logAttempt($email, $ip, $ua, false, 'ip_blocked');
            throw ValidationException::withMessages([
                'email' => [__('auth.throttle', ['seconds' => 3600])],
            ]);
        }

        // 2. Check rate limiter (RateLimiter = Laravel built-in via cache)
        $this->checkRateLimit($request);

        // 3. Check failed attempts for email
        $emailFails = LoginAttempt::recentFailedByEmail($email, self::LOCKOUT_MINUTES);
        if ($emailFails >= self::MAX_ATTEMPTS_EMAIL) {
            $this->logAttempt($email, $ip, $ua, false, 'too_many_attempts_email');
            RateLimiter::hit($this->throttleKey($request), self::LOCKOUT_MINUTES * 60);
            throw ValidationException::withMessages([
                'email' => [__('auth.throttle', ['seconds' => self::LOCKOUT_MINUTES * 60])],
            ]);
        }

        // 4. Check failed attempts for IP
        $ipFails = LoginAttempt::recentFailedByIp($ip, self::LOCKOUT_MINUTES);
        if ($ipFails >= self::MAX_ATTEMPTS_IP) {
            $this->handleAutoBlockIp($ip, $ipFails);
            $this->logAttempt($email, $ip, $ua, false, 'too_many_attempts_ip');
            throw ValidationException::withMessages([
                'email' => [__('auth.throttle', ['seconds' => self::LOCKOUT_MINUTES * 60])],
            ]);
        }

        // 5. Attempt auth – use generic error to avoid info leakage
        if (!Auth::attempt(['email' => $email, 'password' => $password], $request->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey($request), self::LOCKOUT_MINUTES * 60);
            $this->logAttempt($email, $ip, $ua, false, 'invalid_credentials');
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')], // generic message only
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        // 6. Check account active
        if (!$user->is_active) {
            Auth::logout();
            $this->logAttempt($email, $ip, $ua, false, 'account_inactive');
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')], // still generic
            ]);
        }

        // 7. Check account locked
        if ($user->isLocked()) {
            Auth::logout();
            $this->logAttempt($email, $ip, $ua, false, 'account_locked');
            throw ValidationException::withMessages([
                'email' => [__('auth.throttle', ['seconds' => $user->locked_until->diffInSeconds(now())])],
            ]);
        }

        // 8. Regenerate session AFTER successful auth
        $request->session()->regenerate();

        // 9. Clear rate limiter on success
        RateLimiter::clear($this->throttleKey($request));

        // 10. Detect suspicious login (new IP/device)
        $this->detectSuspiciousLogin($user, $ip, $ua);

        // 11. Update login tracking
        $user->update([
            'last_login_at'     => now(),
            'last_login_ip'     => $ip,
            'last_login_device' => $this->parseDevice($ua),
            'login_count'       => $user->login_count + 1,
            'locked_until'      => null, // clear any old lock
        ]);

        // 12. Log successful attempt
        $this->logAttempt($email, $ip, $ua, true, null);

        return $user;
    }

    /**
     * Register a new user securely.
     */
    public function registerUser(array $data, Request $request): User
    {
        $user = User::create([
            'name'     => strip_tags($data['name']),
            'email'    => strtolower(trim($data['email'])),
            'password' => Hash::make($data['password']), // bcrypt via Hash::make
            'role'     => User::ROLE_WARGA,
            'is_active'=> true,
            'nik'      => $data['nik'] ?? null,
            'phone'    => $data['phone'] ?? null,
        ]);

        // Send email verification OTP
        $user->sendEmailVerificationNotification();

        return $user;
    }

    /**
     * Logout user securely - invalidate and regenerate session token.
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();       // destroy session data
        $request->session()->regenerateToken();  // regenerate CSRF token
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    private function checkRateLimit(Request $request): void
    {
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS_EMAIL)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => [__('auth.throttle', ['seconds' => $seconds])],
            ]);
        }
    }

    private function throttleKey(Request $request): string
    {
        // Combine email + IP for throttle key
        return 'login:' . strtolower($request->input('email')) . '|' . $request->ip();
    }

    private function logAttempt(string $email, string $ip, ?string $ua, bool $success, ?string $reason): void
    {
        try {
            LoginAttempt::create([
                'email'          => $email,
                'ip_address'     => $ip,
                'user_agent'     => $ua,
                'successful'     => $success,
                'failure_reason' => $reason,
                'attempted_at'   => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log login attempt: ' . $e->getMessage());
        }
    }

    private function handleAutoBlockIp(string $ip, int $failCount): void
    {
        if ($failCount >= self::AUTO_BLOCK_IP_AT) {
            BlockedIp::autoBlock($ip);
            Log::warning("Auto-blocked IP: {$ip} after {$failCount} failed attempts.");
        }
    }

    private function detectSuspiciousLogin(User $user, string $ip, ?string $ua): void
    {
        if ($user->last_login_ip && $user->last_login_ip !== $ip) {
            try {
                $user->notify(new SuspiciousLoginNotification($ip, $ua));
            } catch (\Exception $e) {
                Log::warning('Failed to send suspicious login notification: ' . $e->getMessage());
            }
        }
    }

    private function parseDevice(?string $ua): string
    {
        if (!$ua) return 'Unknown Device';

        $device = 'Unknown';
        $browser = 'Unknown Browser';

        if (str_contains($ua, 'Mobile'))  $device = 'Mobile';
        elseif (str_contains($ua, 'Tablet')) $device = 'Tablet';
        else $device = 'Desktop';

        if (str_contains($ua, 'Chrome'))  $browser = 'Chrome';
        elseif (str_contains($ua, 'Firefox')) $browser = 'Firefox';
        elseif (str_contains($ua, 'Safari'))  $browser = 'Safari';
        elseif (str_contains($ua, 'Edge'))    $browser = 'Edge';

        return "{$device} / {$browser}";
    }
}
