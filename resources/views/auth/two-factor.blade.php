@extends('layouts.auth')
@section('title', 'Verifikasi Dua Faktor')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div class="logo-icon"><i class="bi bi-shield-check"></i></div>
        <h4>Verifikasi Dua Faktor</h4>
        <p>Konfirmasi identitas Anda</p>
    </div>
    <div class="auth-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="text-center mb-4">
            <div style="font-size:3rem; color:var(--sid-primary);">
                <i class="bi bi-phone"></i>
            </div>
            <p class="text-muted mt-2 mb-0" style="font-size:0.9rem;">
                Kode OTP telah dikirimkan ke email:
            </p>
            <p class="fw-bold text-primary mb-0">{{ $email }}</p>
            <small class="text-muted">Berlaku selama <strong>10 menit</strong></small>
        </div>

        <form method="POST" action="{{ route('two-factor.verify') }}" id="tfaForm">
            @csrf
            <input type="hidden" name="token" id="tfaToken" value="">

            {{-- OTP Boxes --}}
            <div class="mb-4">
                <label class="form-label fw-semibold text-center d-block">Kode OTP (6 digit)</label>
                <div class="d-flex justify-content-center gap-2">
                    @for($i = 0; $i < 6; $i++)
                        <input type="text"
                            class="form-control text-center fw-bold otp-digit"
                            maxlength="1" inputmode="numeric" pattern="[0-9]"
                            style="width:50px;height:56px;font-size:1.4rem;border-radius:0.5rem;"
                            {{ $i === 0 ? 'autofocus' : '' }}>
                    @endfor
                </div>
                <input type="hidden" name="code" id="otpValue">
                @error('code')
                    <div class="text-danger text-center mt-2" style="font-size:0.8rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Countdown --}}
            <div class="text-center mb-3">
                <small class="text-muted">
                    Kode kedaluwarsa dalam: <span id="countdown" class="fw-bold text-danger">10:00</span>
                </small>
            </div>

            <div class="d-grid mb-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check2-shield me-2"></i>Verifikasi
                </button>
            </div>
        </form>

        <div class="text-center mt-3">
            {{-- Resend --}}
            <form method="POST" action="{{ route('two-factor.resend') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none p-0" style="font-size:0.875rem">
                    <i class="bi bi-send me-1"></i>Kirim ulang kode
                </button>
            </form>
            <span class="text-muted mx-2">|</span>
            <a href="{{ route('login') }}" class="text-secondary text-decoration-none" style="font-size:0.875rem">
                <i class="bi bi-arrow-left me-1"></i>Batal
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const inputs = document.querySelectorAll('.otp-digit');
    inputs.forEach((inp, i) => {
        inp.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value && i < inputs.length - 1) inputs[i + 1].focus();
            sync();
        });
        inp.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && i > 0) {
                inputs[i - 1].focus();
                inputs[i - 1].value = '';
                sync();
            }
        });
        inp.addEventListener('paste', function (e) {
            e.preventDefault();
            const d = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            d.split('').slice(0, 6).forEach((c, j) => { if (inputs[j]) inputs[j].value = c; });
            sync();
            inputs[Math.min(d.length, 5)].focus();
        });
    });
    function sync() {
        document.getElementById('otpValue').value = Array.from(inputs).map(x => x.value).join('');
    }

    // Countdown
    let sec = 600;
    const el = document.getElementById('countdown');
    const t = setInterval(() => {
        sec--;
        if (sec <= 0) { clearInterval(t); el.textContent = 'Kedaluwarsa'; }
        else { el.textContent = String(Math.floor(sec/60)).padStart(2,'0') + ':' + String(sec%60).padStart(2,'0'); }
    }, 1000);
</script>
@endpush
