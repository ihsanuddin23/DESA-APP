<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        private string $code,
        private string $type,
        private int    $expiresMinutes = 10
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $label = match ($this->type) {
            'email_verification' => 'Verifikasi Email',
            'two_factor'         => 'Login Dua Faktor (2FA)',
            'password_reset'     => 'Reset Password',
            default              => 'Kode OTP',
        };

        return (new MailMessage)
            ->subject("Kode OTP {$label} - SID App")
            ->view('emails.otp', [
                'code'           => $this->code,
                'label'          => $label,
                'expiresMinutes' => $this->expiresMinutes,
                'notifiable'     => $notifiable,
            ]);
    }
}
