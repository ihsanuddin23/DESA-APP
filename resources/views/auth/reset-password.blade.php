@extends('layouts.auth')
@section('title', 'Reset Password')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body, input, button, label, p, h4, small, a { font-family: 'Plus Jakarta Sans', sans-serif !important; }
    .rp-card {
        background: #fff; border-radius: 1.25rem;
        box-shadow: 0 20px 60px rgba(15,23,42,.1), 0 4px 16px rgba(15,23,42,.06);
        border: 1px solid #f1f5f9; overflow: hidden;
    }
    .rp-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #1a56db 100%);
        padding: 2.25rem 2rem; text-align: center;
        position: relative; overflow: hidden;
    }
    .rp-header::before {
        content: ''; position: absolute;
        width: 180px; height: 180px;
        background: rgba(255,255,255,.04); border-radius: 50%;
        top: -60px; right: -40px;
    }
    .rp-icon-wrap {
        width: 60px; height: 60px;
        background: rgba(255,255,255,.12); border-radius: 1rem;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem; font-size: 1.6rem;
        border: 1px solid rgba(255,255,255,.2);
    }
    .rp-body { padding: 2rem; }
    .field-label { font-size: .82rem; font-weight: 700; color: #374151; display: block; margin-bottom: .5rem; letter-spacing: .01em; }
    .input-wrap { position: relative; }
    .input-wrap .icon { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: .95rem; pointer-events: none; }
    .input-modern {
        border: 1.5px solid #e2e8f0; border-radius: .65rem;
        padding: .7rem 2.8rem .7rem 2.8rem; font-size: .9rem;
        transition: all .2s; width: 100%;
    }
    .input-modern:focus { border-color: #1a56db; box-shadow: 0 0 0 3.5px rgba(26,86,219,.1); outline: none; }
    .input-modern.no-icon { padding-left: 1rem; }
    .toggle-btn {
        position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
        background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0;
        font-size: .95rem; line-height: 1;
    }
    .strength-bar { height: 3px; border-radius: 2px; transition: all .3s; }
    .rule-item { display: flex; align-items: center; gap: .5rem; font-size: .78rem; color: #94a3b8; transition: color .2s; }
    .rule-item.pass { color: #16a34a; }
    .rule-item i { font-size: .7rem; }
    .btn-reset {
        background: linear-gradient(135deg, #1a56db, #1e429f); color: #fff; border: none;
        border-radius: .65rem; padding: .75rem 1.5rem; font-weight: 700;
        font-size: .9rem; width: 100%; transition: opacity .2s, transform .1s; letter-spacing: .01em;
    }
    .btn-reset:hover { opacity: .92; transform: translateY(-1px); color: #fff; }
</style>
@endpush

@section('content')
<div class="rp-card">
    <div class="rp-header">
        <div class="rp-icon-wrap"><i class="bi bi-key" style="color:#fff;"></i></div>
        <h4 style="color:#fff;font-weight:800;margin:0;letter-spacing:-.5px;">Buat Password Baru</h4>
        <p style="color:rgba(255,255,255,.65);font-size:.85rem;margin:.4rem 0 0;">Pastikan password baru Anda kuat dan unik</p>
    </div>

    <div class="rp-body">

        @if($errors->any())
            <div style="background:#fef2f2;border:1.5px solid #fca5a5;border-radius:.65rem;padding:1rem 1.1rem;margin-bottom:1.25rem;display:flex;gap:.75rem;">
                <i class="bi bi-exclamation-triangle-fill" style="color:#dc2626;font-size:1rem;flex-shrink:0;margin-top:.1rem;"></i>
                <div style="color:#b91c1c;font-size:.875rem;">
                    @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" id="rpForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email --}}
            <div class="mb-3">
                <label class="field-label">ALAMAT EMAIL</label>
                <div class="input-wrap">
                    <i class="bi bi-envelope icon"></i>
                    <input type="email" name="email" class="input-modern @error('email') is-invalid @enderror"
                        value="{{ old('email', $email) }}" required maxlength="255">
                </div>
                @error('email') <div style="color:#dc2626;font-size:.78rem;margin-top:.35rem;">{{ $message }}</div> @enderror
            </div>

            {{-- Password Baru --}}
            <div class="mb-1">
                <label class="field-label">PASSWORD BARU</label>
                <div class="input-wrap">
                    <i class="bi bi-lock icon"></i>
                    <input type="password" name="password" id="pwd" class="input-modern @error('password') is-invalid @enderror"
                        placeholder="Min. 8 karakter" required minlength="8" maxlength="255" autocomplete="new-password">
                    <button type="button" class="toggle-btn" onclick="togglePwd('pwd', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password') <div style="color:#dc2626;font-size:.78rem;margin-top:.35rem;">{{ $message }}</div> @enderror
            </div>

            {{-- Strength bar --}}
            <div class="mb-2">
                <div style="background:#f1f5f9;border-radius:2px;height:3px;margin-top:.5rem;">
                    <div class="strength-bar" id="sBar" style="width:0%;"></div>
                </div>
                <div class="d-flex gap-3 mt-2 flex-wrap">
                    <span class="rule-item" id="r1"><i class="bi bi-circle-fill"></i>8+ karakter</span>
                    <span class="rule-item" id="r2"><i class="bi bi-circle-fill"></i>Huruf besar</span>
                    <span class="rule-item" id="r3"><i class="bi bi-circle-fill"></i>Angka</span>
                    <span class="rule-item" id="r4"><i class="bi bi-circle-fill"></i>Simbol</span>
                </div>
            </div>

            {{-- Konfirmasi --}}
            <div class="mb-4">
                <label class="field-label">KONFIRMASI PASSWORD</label>
                <div class="input-wrap">
                    <i class="bi bi-lock-fill icon"></i>
                    <input type="password" name="password_confirmation" id="pwd2"
                        class="input-modern" placeholder="Ulangi password baru"
                        required maxlength="255" autocomplete="new-password">
                    <button type="button" class="toggle-btn" onclick="togglePwd('pwd2', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div id="matchMsg" style="font-size:.78rem;margin-top:.35rem;"></div>
            </div>

            <button type="submit" class="btn-reset mb-3" id="submitBtn">
                <i class="bi bi-check-circle me-2"></i>Simpan Password Baru
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" style="color:#64748b;font-size:.85rem;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem;font-weight:500;">
                <i class="bi bi-arrow-left" style="font-size:.8rem;"></i>Kembali ke login
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePwd(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.className = input.type === 'text' ? 'bi bi-eye-slash' : 'bi bi-eye';
    }

    const pwd = document.getElementById('pwd');
    const sBar = document.getElementById('sBar');
    const barColors = ['#ef4444','#ef4444','#f59e0b','#3b82f6','#10b981'];

    pwd.addEventListener('input', function() {
        const v = this.value;
        const checks = [v.length >= 8, /[A-Z]/.test(v), /[0-9]/.test(v), /[^A-Za-z0-9]/.test(v)];
        const score = checks.filter(Boolean).length;
        sBar.style.width = (score * 25) + '%';
        sBar.style.background = barColors[score] || '#ef4444';
        document.getElementById('r1').className = 'rule-item' + (v.length >= 8 ? ' pass' : '');
        document.getElementById('r2').className = 'rule-item' + (/[A-Z]/.test(v) ? ' pass' : '');
        document.getElementById('r3').className = 'rule-item' + (/[0-9]/.test(v) ? ' pass' : '');
        document.getElementById('r4').className = 'rule-item' + (/[^A-Za-z0-9]/.test(v) ? ' pass' : '');
    });

    document.getElementById('pwd2').addEventListener('input', function() {
        const match = this.value === pwd.value;
        const el = document.getElementById('matchMsg');
        el.textContent = this.value === '' ? '' : (match ? '✓ Password cocok' : '✗ Tidak cocok');
        el.style.color = match ? '#16a34a' : '#dc2626';
    });

    document.getElementById('rpForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });
</script>
@endpush
