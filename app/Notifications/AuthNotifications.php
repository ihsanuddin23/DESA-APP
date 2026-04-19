<?php

namespace App\Notifications\Auth;

use App\Models\OtpCode;
use App\Services\OtpService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// ─── Email Verification via OTP ───────────────────────────────────────────────
class VerifyEmailOtp extends Notification
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $otp = OtpService::sendOtp($notifiable->email, 'email_verification');

        $verifyUrl = route('verification.otp', ['token' => $otp->token]);

        return (new MailMessage)
            ->subject('Verifikasi Email Anda - SID App')
            ->view('emails.verify-email', [
                'user'      => $notifiable,
                'otp'       => $otp,
                'verifyUrl' => $verifyUrl,
            ]);
    }
}


// ─── Suspicious Login Notification ────────────────────────────────────────────
class SuspiciousLoginNotification extends Notification
{
    use Queueable;

    public function __construct(
        private string  $newIp,
        private ?string $userAgent
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Login dari Perangkat Baru - SID App')
            ->view('emails.suspicious-login', [
                'user'      => $notifiable,
                'newIp'     => $this->newIp,
                'userAgent' => $this->userAgent,
                'loginTime' => now()->format('d M Y, H:i:s'),
            ]);
    }
}
