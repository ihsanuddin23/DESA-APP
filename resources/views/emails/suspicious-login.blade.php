{{-- FILE: resources/views/emails/suspicious-login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Mencurigakan - SID App</title>
    <style>
        body{margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Arial,sans-serif;}
        .wrapper{max-width:580px;margin:32px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);}
        .header{background:linear-gradient(135deg,#dc2626,#b91c1c);padding:32px;text-align:center;color:#fff;}
        .header h2{margin:0;font-size:1.4rem;font-weight:700;}
        .body{padding:32px;}
        .alert-box{background:#fef2f2;border:1.5px solid #fca5a5;border-radius:10px;padding:20px;margin:20px 0;}
        .info-row{display:flex;align-items:flex-start;gap:8px;padding:10px 0;border-bottom:1px solid #f1f5f9;font-size:.875rem;}
        .info-label{color:#6b7280;min-width:120px;flex-shrink:0;font-weight:600;}
        .footer{background:#f8fafc;padding:20px 32px;text-align:center;color:#94a3b8;font-size:.8rem;border-top:1px solid #e2e8f0;}
        .btn{display:inline-block;background:#dc2626;color:#fff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:700;margin-top:16px;}
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h2>⚠️ Login dari Perangkat Baru</h2>
        <p style="margin:8px 0 0;opacity:.85;font-size:.9rem;">SID App — Notifikasi Keamanan</p>
    </div>
    <div class="body">
        <p style="color:#374151;margin:0 0 8px">Halo, <strong>{{ $user->name }}</strong>!</p>
        <p style="color:#6b7280;font-size:.9rem;margin:0 0 16px">
            Kami mendeteksi login ke akun Anda dari alamat IP atau perangkat yang berbeda dari biasanya.
        </p>

        <div class="alert-box">
            <strong style="color:#dc2626;font-size:1rem;">Detail Login Baru:</strong>
            <div class="info-row">
                <span class="info-label">IP Address</span>
                <code>{{ $newIp }}</code>
            </div>
            <div class="info-row">
                <span class="info-label">Browser/Perangkat</span>
                <span>{{ $userAgent ?? 'Tidak diketahui' }}</span>
            </div>
            <div class="info-row" style="border:none;">
                <span class="info-label">Waktu</span>
                <span>{{ $loginTime }} WIB</span>
            </div>
        </div>

        <p style="color:#374151;font-size:.9rem;">
            <strong>Jika ini adalah Anda</strong>, Anda dapat mengabaikan email ini.
        </p>
        <p style="color:#374151;font-size:.9rem;">
            <strong>Jika ini BUKAN Anda</strong>, segera amankan akun Anda:
        </p>
        <ol style="color:#374151;font-size:.875rem;line-height:1.8;">
            <li>Segera <strong>ubah password</strong> Anda</li>
            <li>Aktifkan <strong>autentikasi dua faktor (2FA)</strong></li>
            <li>Hubungi administrator jika perlu</li>
        </ol>

        <div style="text-align:center;">
            <a href="{{ route('login') }}" class="btn">🔒 Amankan Akun Saya</a>
        </div>
    </div>
    <div class="footer">
        <p style="margin:0">Email ini dikirim otomatis karena aktivitas login terdeteksi di akun Anda.</p>
    </div>
</div>
</body>
</html>
