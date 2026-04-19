{{-- ============================================================ --}}
{{-- FILE: resources/views/emails/otp.blade.php                 --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP - SID App</title>
    <style>
        body{margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;}
        .wrapper{max-width:580px;margin:32px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);}
        .header{background:linear-gradient(135deg,#1a56db,#1e429f);padding:32px;text-align:center;color:#fff;}
        .header h2{margin:0;font-size:1.4rem;font-weight:700;}
        .header p{margin:8px 0 0;opacity:.85;font-size:.9rem;}
        .body{padding:32px;}
        .otp-box{background:#f0f4ff;border:2px dashed #1a56db;border-radius:10px;padding:24px;text-align:center;margin:24px 0;}
        .otp-code{font-size:2.5rem;font-weight:800;color:#1a56db;letter-spacing:12px;font-family:monospace;}
        .otp-note{color:#6b7280;font-size:.8rem;margin-top:8px;}
        .info-row{display:flex;align-items:center;gap:8px;padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:.875rem;}
        .info-label{color:#6b7280;min-width:100px;}
        .footer{background:#f8fafc;padding:20px 32px;text-align:center;color:#94a3b8;font-size:.8rem;border-top:1px solid #e2e8f0;}
        .warning{background:#fff7ed;border:1px solid #fed7aa;border-radius:8px;padding:16px;margin-top:16px;color:#92400e;font-size:.875rem;}
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h2>🔐 Kode OTP {{ $label }}</h2>
        <p>Sistem Informasi Desa (SID App)</p>
    </div>
    <div class="body">
        <p style="color:#374151;margin:0 0 8px">Halo, <strong>{{ $notifiable->name }}</strong>!</p>
        <p style="color:#6b7280;font-size:.9rem;margin:0 0 16px">
            Berikut adalah kode OTP untuk <strong>{{ $label }}</strong> Anda:
        </p>

        <div class="otp-box">
            <div class="otp-code">{{ $code }}</div>
            <div class="otp-note">
                <strong>Berlaku {{ $expiresMinutes }} menit</strong> · Jangan bagikan kode ini kepada siapapun
            </div>
        </div>

        <div style="background:#f9fafb;border-radius:8px;padding:16px;margin-bottom:16px;">
            <div class="info-row">
                <span class="info-label">Tipe</span>
                <span>{{ $label }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Waktu</span>
                <span>{{ now()->format('d M Y, H:i:s') }} WIB</span>
            </div>
            <div class="info-row" style="border:none;">
                <span class="info-label">Kedaluwarsa</span>
                <span>{{ now()->addMinutes($expiresMinutes)->format('d M Y, H:i:s') }} WIB</span>
            </div>
        </div>

        <div class="warning">
            <strong>⚠ Peringatan Keamanan:</strong><br>
            Tim SID App <strong>tidak pernah</strong> meminta kode OTP Anda melalui telepon, WhatsApp, atau pesan lainnya.
            Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini dan segera ubah password Anda.
        </div>
    </div>
    <div class="footer">
        <p style="margin:0">Email ini dikirim secara otomatis oleh sistem SID App.</p>
        <p style="margin:4px 0 0">Jangan membalas email ini.</p>
    </div>
</div>
</body>
</html>
