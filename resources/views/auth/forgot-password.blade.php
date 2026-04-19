@extends('layouts.auth')

@section('title', 'Lupa Password')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
@endpush

@section('content')
    <div class="auth-card">

        {{-- Header --}}
        <div class="auth-header">
            <div class="auth-header-glow"></div>
            <div class="logo-icon">
                <i class="bi bi-envelope-open"></i>
            </div>
            <h4>Lupa Password?</h4>
            <p>Tenang, kami kirimkan tautan reset ke email Anda</p>
        </div>

        {{-- Body --}}
        <div class="auth-body">

            {{-- Success Message --}}
            @if (session('status'))
                <div class="alert-desa success" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert-desa danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Info Box --}}
            <div class="info-box-desa">
                <i class="bi bi-info-circle-fill"></i>
                <div>
                    Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk mereset password Anda.
                    Tautan berlaku selama <strong>60 menit</strong>.
                </div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}" id="fpForm">
                @csrf

                <div class="form-group-desa">
                    <label for="email" class="form-label-desa">
                        Alamat Email
                    </label>
                    <div class="input-group-desa">
                        <span class="input-icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email"
                            class="input-desa @error('email') is-invalid @enderror" value="{{ old('email') }}"
                            placeholder="nama@email.com" required autocomplete="email" autofocus maxlength="255">
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="bi bi-exclamation-circle"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-desa-submit" id="submitBtn">
                    <i class="bi bi-send"></i>
                    Kirim Tautan Reset
                </button>
            </form>

            {{-- Back Link --}}
            <div class="text-center">
                <a href="{{ route('login') }}" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke halaman login
                </a>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/forgot-password.js') }}"></script>
@endpush
