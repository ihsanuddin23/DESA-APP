<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- Prevent caching of auth pages --}}
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>@yield('title', 'Autentikasi') — SID App</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    {{-- Bootstrap 5 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Custom Auth Layout CSS --}}
    <link rel="stylesheet" href="{{ asset('css/auth-layout.css') }}">

    @stack('styles')
</head>

<body>

    {{-- ❌ HAPUS BAGIAN INI - Tidak perlu di halaman auth
    <div id="session-timer">
        <i class="bi bi-clock-history me-2"></i>
        Sesi akan berakhir dalam <span id="timer-countdown">5:00</span>
        <a href="#" onclick="event.preventDefault(); location.reload();" class="ms-2">Perpanjang</a>
    </div>
    --}}

    {{-- Main auth page wrapper --}}
    <div class="auth-page-desa">
        {{-- Decorative elements --}}
        <div class="auth-deco-1"></div>
        <div class="auth-deco-2"></div>
        <div class="auth-deco-3"></div>
        <div class="auth-border-bottom"></div>

        {{-- Content container --}}
        <div class="auth-container">

            {{-- Flash Messages --}}
            @foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'] as $type => $class)
                @if (session($type))
                    <div class="alert-desa {{ $class }} alert-dismissible fade show" role="alert">
                        <i
                            class="bi bi-{{ $type === 'success'
                                ? 'check-circle-fill'
                                : ($type === 'error'
                                    ? 'x-octagon-fill'
                                    : ($type === 'warning'
                                        ? 'exclamation-triangle-fill'
                                        : 'info-circle-fill')) }}"></i>
                        <div>{{ session($type) }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endforeach

            {{-- Main content from child views --}}
            @yield('content')

            {{-- Security badge --}}
            <div class="security-badge-desa">
                <i class="bi bi-shield-lock-fill"></i>
                <span>Koneksi aman & terenkripsi · SID Desa App</span>
            </div>
        </div>
    </div>

    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom Auth Layout JS --}}
    <script src="{{ asset('js/auth-layout.js') }}"></script>

    @stack('scripts')
</body>

</html>
