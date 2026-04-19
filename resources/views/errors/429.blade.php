@extends('layouts.auth')
@section('title', 'Terlalu Banyak Permintaan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body, * { font-family: 'Plus Jakarta Sans', sans-serif !important; }
    .err-card {
        background: #fff; border-radius: 1.25rem;
        box-shadow: 0 20px 60px rgba(15,23,42,.1);
        border: 1px solid #f1f5f9; overflow: hidden; text-align: center;
    }
    .err-header {
        background: linear-gradient(135deg, #7f1d1d, #b91c1c, #ef4444);
        padding: 2.5rem 2rem; position: relative; overflow: hidden;
    }
    .err-header::before {
        content: ''; position: absolute;
        width: 200px; height: 200px; background: rgba(255,255,255,.05);
        border-radius: 50%; top: -80px; right: -40px;
    }
    .err-num {
        font-size: 4.5rem; font-weight: 800; color: #fff;
        letter-spacing: -4px; line-height: 1; opacity: .15;
        position: absolute; bottom: .5rem; right: 1.5rem;
    }
    .err-icon {
        width: 68px; height: 68px;
        background: rgba(255,255,255,.15); border-radius: 1.1rem;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem; font-size: 1.8rem;
        border: 1px solid rgba(255,255,255,.25);
    }
    .err-body { padding: 2rem 2rem 2.5rem; }
    .timer-ring {
        width: 80px; height: 80px;
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border: 2px solid #fca5a5; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem; font-weight: 800; color: #b91c1c;
    }
</style>
@endpush

@section('content')
<div class="err-card">
    <div class="err-header">
        <div class="err-icon"><i class="bi bi-hourglass-split" style="color:#fff;"></i></div>
        <h4 style="color:#fff;font-weight:800;margin:0;letter-spacing:-.5px;">Terlalu Banyak Permintaan</h4>
        <p style="color:rgba(255,255,255,.7);font-size:.875rem;margin:.4rem 0 0;">Aktivitas Anda terdeteksi mencurigakan</p>
        <span class="err-num">429</span>
    </div>
    <div class="err-body">
        <div class="timer-ring" id="timerDisplay">—</div>
        <h6 style="font-weight:700;color:#0f172a;margin-bottom:.5rem;">Akses Sementara Dibatasi</h6>
        <p style="color:#64748b;font-size:.875rem;line-height:1.7;margin-bottom:1.5rem;">
            Anda telah melakukan terlalu banyak percobaan dalam waktu singkat.
            Sistem memblokir akses sementara untuk melindungi keamanan.
            Silakan tunggu sebelum mencoba kembali.
        </p>

        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:.75rem;padding:1rem;margin-bottom:1.5rem;text-align:left;">
            <div style="font-size:.78rem;font-weight:700;color:#b91c1c;letter-spacing:.04em;margin-bottom:.5rem;">APA YANG HARUS DILAKUKAN?</div>
            <ul style="color:#64748b;font-size:.82rem;line-height:1.8;margin:0;padding-left:1.2rem;">
                <li>Tunggu beberapa menit sebelum mencoba lagi</li>
                <li>Pastikan Anda menggunakan kredensial yang benar</li>
                <li>Hubungi administrator jika masalah berlanjut</li>
            </ul>
        </div>

        <a href="{{ route('login') }}"
           style="display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#1a56db,#1e429f);color:#fff;text-decoration:none;padding:.7rem 1.5rem;border-radius:.65rem;font-weight:700;font-size:.875rem;">
            <i class="bi bi-arrow-left"></i> Kembali ke Login
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Countdown 60s
    let sec = 60;
    const el = document.getElementById('timerDisplay');
    const t = setInterval(() => {
        el.textContent = sec + 's';
        if (--sec < 0) {
            clearInterval(t);
            el.textContent = '✓';
            el.style.background = 'linear-gradient(135deg,#f0fdf4,#dcfce7)';
            el.style.border = '2px solid #86efac';
            el.style.color = '#15803d';
            el.style.fontSize = '1.8rem';
        }
    }, 1000);
</script>
@endpush
