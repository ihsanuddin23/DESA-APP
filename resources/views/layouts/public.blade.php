<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Sistem Informasi Desa {{ config('sid.nama_desa', 'Desa') }} — Pelayanan publik digital untuk warga.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') — {{ config('sid.nama_desa', 'Desa Sukamaju') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap"
        rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Halaman Publik CSS --}}
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">

    @stack('styles')
</head>

<body>

    {{-- ── NAVBAR ──────────────────────────────────────────────────────────────── --}}
    <nav class="sid-navbar navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <div class="sid-logo-box">
                    <i class="bi bi-house-fill"></i>
                </div>
                <div class="sid-brand-text">
                    <span class="sid-brand-nama">{{ config('sid.nama_desa', 'Desa Sukamaju') }}</span>
                    <small class="sid-brand-alamat">{{ config('sid.kecamatan', 'Kec. Ciawi') }},
                        {{ config('sid.kabupaten', 'Kab. Bogor') }}</small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navPublic">
                <i class="bi bi-list text-white fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="navPublic">
                <ul class="navbar-nav mx-auto gap-1">
                    <li class="nav-item">
                        <a class="sid-nav-link {{ request()->routeIs('home') ? 'aktif' : '' }}"
                            href="{{ route('home') }}">
                            Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="sid-nav-link {{ request()->routeIs('profil') ? 'aktif' : '' }}"
                            href="{{ route('profil') }}">
                            Profil Desa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="sid-nav-link {{ request()->routeIs('berita*') ? 'aktif' : '' }}"
                            href="{{ route('berita') }}">
                            Berita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="sid-nav-link {{ request()->routeIs('pengumuman*') ? 'aktif' : '' }}"
                            href="{{ route('pengumuman') }}">
                            Pengumuman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="sid-nav-link" href="{{ route('home') }}#layanan">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="sid-nav-link {{ request()->routeIs('galeri*') ? 'aktif' : '' }}"
                            href="{{ route('galeri') }}">
                            Galeri
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="sid-nav-link {{ request()->routeIs('kontak') ? 'aktif' : '' }}"
                            href="{{ route('kontak') }}">
                            Kontak
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="sid-btn-masuk">
                            <i class="bi bi-grid-fill me-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="sid-btn-masuk">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ── TICKER PENGUMUMAN ────────────────────────────────────────────────────── --}}
    @if (isset($pengumumanTicker) && $pengumumanTicker->count())
        <div class="sid-ticker">
            <div class="container d-flex align-items-center gap-3">
                <span class="sid-ticker-label">PENGUMUMAN</span>
                <div class="sid-ticker-track">
                    <div class="sid-ticker-inner">
                        @foreach ($pengumumanTicker as $item)
                            <span>{{ $item->judul }}</span>
                            <span class="sid-ticker-sep">•</span>
                        @endforeach
                        {{-- duplikat untuk seamless loop --}}
                        @foreach ($pengumumanTicker as $item)
                            <span>{{ $item->judul }}</span>
                            <span class="sid-ticker-sep">•</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ── KONTEN UTAMA ─────────────────────────────────────────────────────────── --}}
    @yield('content')

    {{-- ── FOOTER ───────────────────────────────────────────────────────────────── --}}
    <footer class="sid-footer">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <div class="sid-footer-brand">
                        <i class="bi bi-house-fill me-2"></i>{{ config('sid.nama_desa', 'Desa Sukamaju') }}
                    </div>
                    <p class="sid-footer-desc">
                        {{ config('sid.alamat', 'Jl. Raya Sukamaju No. 1') }},
                        {{ config('sid.kecamatan', 'Kec. Ciawi') }},
                        {{ config('sid.kabupaten', 'Kab. Bogor') }},
                        {{ config('sid.provinsi', 'Jawa Barat') }}
                        {{ config('sid.kode_pos', '16720') }}.
                    </p>
                    <div class="sid-footer-kontak">
                        <div><i class="bi bi-telephone-fill me-2"></i>{{ config('sid.telepon', '(0251) 8123-456') }}
                        </div>
                        <div><i class="bi bi-envelope-fill me-2"></i>{{ config('sid.email', 'desa@sukamaju.desa.id') }}
                        </div>
                        <div><i class="bi bi-clock-fill me-2"></i>Senin–Jumat, 08.00–15.00 WIB</div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="sid-footer-heading">Layanan</h6>
                    <ul class="sid-footer-list">
                        <li><a href="#">Surat Keterangan</a></li>
                        <li><a href="#">Kartu Keluarga</a></li>
                        <li><a href="#">Izin Usaha</a></li>
                        <li><a href="#">Bantuan Sosial</a></li>
                        <li><a href="#">Aduan Warga</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="sid-footer-heading">Informasi</h6>
                    <ul class="sid-footer-list">
                        <li><a href="{{ route('profil') }}">Profil Desa</a></li>
                        <li><a href="{{ route('berita') }}">Berita Desa</a></li>
                        <li><a href="{{ route('pengumuman') }}">Pengumuman</a></li>
                        <li><a href="#">APBDes 2026</a></li>
                        <li><a href="{{ route('kontak') }}">Kontak Kami</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="sid-footer-heading">Lokasi Kantor Desa</h6>
                    <div class="sid-footer-map">
                        <iframe
                            src="https://maps.google.com/maps?q={{ config('sid.lat', '-6.3331072') }},{{ config('sid.lng', '107.0844255') }}&z=15&output=embed"
                            width="100%" height="150" style="border:0;border-radius:8px;" allowfullscreen
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <a href="https://www.google.com/maps/place/{{ config('sid.lat') }},{{ config('sid.lng') }}"
                            target="_blank" rel="noopener"
                            class="d-block text-center mt-2 small text-decoration-none">
                            <i class="bi bi-geo-alt-fill"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>
            </div>

            <div class="sid-footer-bottom">
                <span>© {{ date('Y') }} {{ config('sid.nama_desa', 'Desa Sukamaju') }} · Sistem Informasi Desa
                    v1.0</span>
                <span>Dibangun dengan Laravel 12 + Bootstrap 5</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
