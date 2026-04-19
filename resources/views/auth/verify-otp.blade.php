{{-- ============================================================ --}}
{{-- FILE: resources/views/auth/verify-otp.blade.php            --}}
{{-- ============================================================ --}}
@extends('layouts.auth')
@section('title', 'Masukkan Kode OTP')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div class="logo-icon"><i class="bi bi-key"></i></div>
        <h4>Masukkan Kode OTP</h4>
        <p>Kode berlaku selama 10 menit</p>
    </div>
    <div class="auth-body">

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
            </div>
        @endif

        <p class="text-muted text-center mb-4" style="font-size:0.9rem;">
            Masukkan 6 digit kode yang telah dikirim ke email Anda.
        </p>

        <form method="POST" action="{{ route('verification.otp.verify') }}" id="otpForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- OTP Input Boxes --}}
            <div class="mb-4">
                <label class="form-label fw-semibold text-center d-block">Kode OTP</label>
                <div class="d-flex justify-content-center gap-2" id="otpInputs">
                    @for($i = 0; $i < 6; $i++)
                        <input type="text"
                            class="form-control text-center fw-bold otp-digit"
                            maxlength="1"
                            inputmode="numeric"
                            pattern="[0-9]"
                            style="width:50px; height:56px; font-size:1.4rem; border-radius:0.5rem;"
                            autocomplete="one-time-code"
                            {{ $i === 0 ? 'autofocus' : '' }}
                        >
                    @endfor
                </div>
                {{-- Hidden input for actual OTP value --}}
                <input type="hidden" name="code" id="otpValue">
                @error('code')
                    <div class="text-danger text-center mt-2" style="font-size:0.8rem;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Countdown Timer --}}
            <div class="text-center mb-3">
                <small class="text-muted">
                    Kode kedaluwarsa dalam: <span id="countdown" class="fw-bold text-danger">10:00</span>
                </small>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary btn-lg" id="submitOtp">
                    <i class="bi bi-check-circle me-2"></i>Verifikasi
                </button>
            </div>
        </form>

        <div class="text-center">
            <a href="{{ route('verification.notice') }}" class="text-secondary text-decoration-none" style="font-size:0.875rem">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ── OTP input auto-advance ────────────────────────────────────────────────
    const inputs = document.querySelectorAll('.otp-digit');

    inputs.forEach((input, index) => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            updateHiddenInput();
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
            }
        });

        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            pasted.split('').slice(0, 6).forEach((c, i) => {
                if (inputs[i]) inputs[i].value = c;
            });
            updateHiddenInput();
            inputs[Math.min(pasted.length, 5)].focus();
        });
    });

    function updateHiddenInput() {
        document.getElementById('otpValue').value = Array.from(inputs).map(i => i.value).join('');
    }

    // ── Countdown Timer ───────────────────────────────────────────────────────
    let seconds = 600;
    const countdownEl = document.getElementById('countdown');
    const timer = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(timer);
            countdownEl.textContent = 'Kedaluwarsa';
            document.getElementById('submitOtp').disabled = true;
        } else {
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            countdownEl.textContent = `${m}:${s}`;
        }
    }, 1000);
</script>
@endpush
