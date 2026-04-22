@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

    {{-- ── HERO ─────────────────────────────────────────────────────────────────── --}}
    <section class="sid-hero">
        <div class="container">
            <div class="sid-hero-inner text-center">
                <span class="sid-hero-badge">
                    <i class="bi bi-house-fill me-1"></i>
                    Sistem Informasi {{ config('sid.nama_desa', 'Desa Sukamaju') }}
                </span>
                <h1 class="sid-hero-title">
                    Pelayanan Desa <em>Lebih Cepat,</em><br>
                    Lebih Transparan
                </h1>
                <p class="sid-hero-lead">
                    Urus surat, pantau informasi, dan akses data desa kapan saja dan di mana saja.
                    Semua dalam satu platform digital untuk warga {{ config('sid.nama_desa', 'Desa Sukamaju') }}.
                </p>
                <div class="sid-hero-actions">
                    @auth
                        <a href="{{ route(auth()->user()->getDashboardRoute()) }}" class="sid-btn-primer">
                            <i class="bi bi-grid-fill me-2"></i>Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="sid-btn-primer">
                            <i class="bi bi-person-plus-fill me-2"></i>Daftar Sekarang
                        </a>
                    @endauth
                    <a href="#layanan" class="sid-btn-sekunder">
                        <i class="bi bi-grid me-2"></i>Lihat Layanan
                    </a>
                </div>
            </div>

            {{-- Statistik Strip --}}
            <div class="sid-stat-strip">
                <div class="sid-stat-item">
                    <div class="sid-stat-num">{{ number_format($statistik['total_penduduk']) }}</div>
                    <div class="sid-stat-label">Total Penduduk</div>
                </div>
                <div class="sid-stat-item">
                    <div class="sid-stat-num">{{ number_format($statistik['total_kk']) }}</div>
                    <div class="sid-stat-label">Kepala Keluarga</div>
                </div>
                <div class="sid-stat-item">
                    <div class="sid-stat-num">{{ $statistik['total_rt'] }}</div>
                    <div class="sid-stat-label">Rukun Tetangga</div>
                </div>
                <div class="sid-stat-item">
                    @php
                        $pct =
                            $statistik['total_penduduk'] > 0
                                ? round(
                                    (($statistik['laki_laki'] + $statistik['perempuan']) /
                                        $statistik['total_penduduk']) *
                                        100,
                                )
                                : 0;
                    @endphp
                    <div class="sid-stat-num">{{ $pct }}%</div>
                    <div class="sid-stat-label">Cakupan Data NIK</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── LAYANAN ONLINE ───────────────────────────────────────────────────────── --}}
    <section class="sid-section" id="layanan">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Layanan <span>Online</span></h2>
            </div>

            <div class="row g-3">
                @php
                    $layanan = [
                        [
                            'ikon' => 'bi-heart-pulse-fill',
                            'warna' => '#E6F1FB',
                            'judul' => 'Posyandu',
                            'deskripsi' => 'Jadwal & pendaftaran posyandu',
                            'route' => 'posyandu',
                        ],
                        [
                            'ikon' => 'bi-cash-coin',
                            'warna' => '#EEEDFE',
                            'judul' => 'Bantuan Sosial',
                            'deskripsi' => 'Cek status & daftar bansos',
                            'route' => 'bansos',
                        ],
                        [
                            'ikon' => 'bi-bar-chart-fill',
                            'warna' => '#FAEEDA',
                            'judul' => 'Laporan Keuangan',
                            'deskripsi' => 'APBDes & realisasi anggaran',
                            'route' => 'apbdes',
                        ],
                        [
                            'ikon' => 'bi-megaphone-fill',
                            'warna' => '#E8F5EE',
                            'judul' => 'Aduan Warga',
                            'deskripsi' => 'Sampaikan keluhan & aspirasi',
                            'route' => 'aduan',
                        ],
                        [
                            'ikon' => 'bi-calendar-event-fill',
                            'warna' => '#E8F4FD',
                            'judul' => 'Agenda Kegiatan',
                            'deskripsi' => 'Jadwal kegiatan desa',
                            'route' => 'agenda',
                        ],
                    ];
                @endphp

                @foreach ($layanan as $item)
                    <div class="col-6 col-md-3">
                        <a href="{{ route($item['route']) }}" class="sid-layanan-card">
                            <div class="sid-layanan-icon" style="background: {{ $item['warna'] }}">
                                <i class="bi {{ $item['ikon'] }}"></i>
                            </div>
                            <h3 class="sid-layanan-judul">{{ $item['judul'] }}</h3>
                            <p class="sid-layanan-desc">{{ $item['deskripsi'] }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── BERITA DESA ──────────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Berita <span>Desa</span></h2>
                <a href="{{ route('berita') }}" class="sid-lihat-semua">
                    Semua berita <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if ($beritaUtama || $beritaLainnya->count())
                <div class="row g-3">
                    {{-- Berita Utama --}}
                    @if ($beritaUtama)
                        <div class="col-lg-7">
                            <a href="{{ route('berita.detail', $beritaUtama->slug) }}"
                                class="sid-berita-utama d-block text-decoration-none">
                                <div class="sid-berita-utama-img">
                                    @if ($beritaUtama->foto)
                                        <img src="{{ Storage::url($beritaUtama->foto) }}" alt="{{ $beritaUtama->judul }}">
                                    @else
                                        <div class="sid-berita-placeholder">
                                            <i class="bi bi-newspaper"></i>
                                        </div>
                                    @endif
                                    <span class="sid-berita-tag">{{ ucfirst($beritaUtama->kategori) }}</span>
                                </div>
                                <div class="sid-berita-utama-body">
                                    <h3 class="sid-berita-utama-judul">{{ $beritaUtama->judul }}</h3>
                                    <p class="sid-berita-utama-ringkasan">
                                        {{ Str::limit(strip_tags($beritaUtama->konten), 140) }}
                                    </p>
                                    <div class="sid-berita-meta">
                                        <span>{{ $beritaUtama->published_at->isoFormat('D MMMM YYYY') }}</span>
                                        <span class="sid-meta-dot"></span>
                                        <span>{{ $beritaUtama->penulis ?? 'Admin Desa' }}</span>
                                        <span class="sid-meta-dot"></span>
                                        <span>{{ ceil(str_word_count(strip_tags($beritaUtama->konten)) / 200) }} menit
                                            baca</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    {{-- Berita Lainnya --}}
                    <div class="col-lg-5">
                        <div class="d-flex flex-column gap-3 h-100">
                            @forelse($beritaLainnya as $item)
                                <a href="{{ route('berita.detail', $item->slug) }}"
                                    class="sid-berita-mini text-decoration-none">
                                    <div class="sid-berita-mini-img">
                                        @if ($item->foto)
                                            <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->judul }}">
                                        @else
                                            <div class="sid-berita-mini-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="sid-berita-mini-body">
                                        <h4 class="sid-berita-mini-judul">{{ Str::limit($item->judul, 70) }}</h4>
                                        <span
                                            class="sid-berita-mini-tgl">{{ $item->published_at->isoFormat('D MMM YYYY') }}</span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-muted">Belum ada berita lainnya.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-newspaper fs-1 d-block mb-3"></i>
                    Belum ada berita yang dipublikasikan.
                </div>
            @endif
        </div>
    </section>

    {{-- ── PENGUMUMAN ───────────────────────────────────────────────────────────── --}}
    <section class="sid-section">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Pengumuman <span>Resmi</span></h2>
                <a href="{{ route('pengumuman') }}" class="sid-lihat-semua">
                    Semua pengumuman <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @forelse($pengumuman as $item)
                <div class="sid-pengumuman-item">
                    @php
                        $badgeClass = match ($item->prioritas) {
                            'penting' => 'sid-badge-penting',
                            'info' => 'sid-badge-info',
                            default => 'sid-badge-umum',
                        };
                        $badgeIcon = match ($item->prioritas) {
                            'penting' => 'bi-exclamation-triangle-fill',
                            'info' => 'bi-info-circle-fill',
                            default => 'bi-clipboard-fill',
                        };
                    @endphp
                    <span class="sid-badge {{ $badgeClass }}">
                        <i class="bi {{ $badgeIcon }} me-1"></i>
                        {{ ucfirst($item->prioritas) }}
                    </span>
                    <div class="sid-pengumuman-body">
                        <div class="sid-pengumuman-judul">{{ $item->judul }}</div>
                        <div class="sid-pengumuman-tgl">
                            Diterbitkan {{ $item->created_at->isoFormat('D MMMM YYYY') }}
                            @if ($item->berlaku_hingga)
                                · Berlaku hingga {{ $item->berlaku_hingga->isoFormat('D MMMM YYYY') }}
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-megaphone fs-2 d-block mb-2"></i>
                    Tidak ada pengumuman aktif saat ini.
                </div>
            @endforelse
        </div>
    </section>

    {{-- ── DATA KEPENDUDUKAN ────────────────────────────────────────────────────── --}}
    <section class="sid-data-section">
        <div class="container">
            <h2 class="sid-data-title">Data Kependudukan Desa</h2>
            <p class="sid-data-subtitle">
                Update terakhir: {{ now()->isoFormat('D MMMM YYYY') }} — Data real-time dari database desa
            </p>

            <div class="row g-3">
                {{-- Total Penduduk --}}
                <div class="col-md-4">
                    <div class="sid-data-card">
                        <div class="sid-data-label">Total Penduduk</div>
                        <div class="sid-data-num">{{ number_format($statistik['total_penduduk']) }}</div>
                        <div class="sid-data-sub">Terdaftar & terverifikasi NIK</div>
                        @if ($statistik['total_penduduk'] > 0)
                            @php
                                $pctL = round(($statistik['laki_laki'] / $statistik['total_penduduk']) * 100);
                            @endphp
                            <div class="sid-data-bar-wrap mt-3">
                                <div class="sid-data-bar-label">
                                    <span>L: {{ number_format($statistik['laki_laki']) }}</span>
                                    <span>P: {{ number_format($statistik['perempuan']) }}</span>
                                </div>
                                <div class="sid-data-bar-bg">
                                    <div class="sid-data-bar-fill" style="width: {{ $pctL }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Kelompok Usia --}}
                <div class="col-md-4">
                    <div class="sid-data-card">
                        <div class="sid-data-label">Kelompok Usia</div>
                        <div class="sid-data-num">{{ number_format($statistik['usia_produktif']) }}</div>
                        <div class="sid-data-sub">Usia produktif 18–55 tahun</div>
                        @if ($statistik['total_penduduk'] > 0)
                            @php
                                $pctAnak = round(($statistik['usia_anak'] / $statistik['total_penduduk']) * 100);
                                $pctProd = round(($statistik['usia_produktif'] / $statistik['total_penduduk']) * 100);
                                $pctLansia = round(($statistik['usia_lansia'] / $statistik['total_penduduk']) * 100);
                            @endphp
                            <div class="sid-data-bar-wrap mt-3">
                                <div class="sid-data-bar-label">
                                    <span>0–17 thn</span>
                                    <span>{{ number_format($statistik['usia_anak']) }} jiwa</span>
                                </div>
                                <div class="sid-data-bar-bg">
                                    <div class="sid-data-bar-fill"
                                        style="width: {{ $pctAnak }}%; background: rgba(255,255,255,0.45)"></div>
                                </div>
                            </div>
                            <div class="sid-data-bar-wrap mt-2">
                                <div class="sid-data-bar-label">
                                    <span>18–55 thn</span>
                                    <span>{{ number_format($statistik['usia_produktif']) }} jiwa</span>
                                </div>
                                <div class="sid-data-bar-bg">
                                    <div class="sid-data-bar-fill" style="width: {{ $pctProd }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Struktur RT --}}
                <div class="col-md-4">
                    <div class="sid-data-card">
                        <div class="sid-data-label">Wilayah Desa</div>
                        <div class="sid-data-num">{{ $statistik['total_rt'] }} RT</div>
                        <div class="sid-data-sub">Total rukun tetangga aktif</div>
                        <div class="sid-data-bar-wrap mt-3">
                            <div class="sid-data-bar-label">
                                <span>Total KK terdaftar</span>
                                <span>{{ number_format($statistik['total_kk']) }}</span>
                            </div>
                            <div class="sid-data-bar-bg">
                                <div class="sid-data-bar-fill" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- SECTION: Agenda Mendatang --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    @if ($agendaMendatang->count() > 0)
        <section class="sid-section sid-section-putih">
            <div class="container">
                <div class="sid-section-header">
                    <div>
                        <h2 class="sid-section-title">Agenda Kegiatan</h2>
                        <p class="sid-section-sub">Kegiatan dan acara yang akan datang</p>
                    </div>
                    <a href="{{ route('agenda') }}" class="sid-link-more">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="row g-4">
                    @foreach ($agendaMendatang as $agenda)
                        <div class="col-md-6 col-lg-4">
                            <div class="sid-agenda-card">
                                <div class="sid-agenda-date">
                                    <span class="day">{{ $agenda->tanggal_mulai->format('d') }}</span>
                                    <span class="month">{{ $agenda->tanggal_mulai->translatedFormat('M') }}</span>
                                </div>
                                <div class="sid-agenda-content">
                                    <span class="sid-agenda-kategori"
                                        style="background:{{ $agenda->kategori_bg }};color:{{ $agenda->kategori_color }};">
                                        {{ $agenda->kategori_label }}
                                    </span>
                                    <h5 class="sid-agenda-title">
                                        <a
                                            href="{{ route('agenda.detail', $agenda) }}">{{ Str::limit($agenda->judul, 50) }}</a>
                                    </h5>
                                    <div class="sid-agenda-meta">
                                        @if ($agenda->waktu_mulai)
                                            <span><i class="bi bi-clock"></i>
                                                {{ \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i') }} WIB</span>
                                        @endif
                                        @if ($agenda->lokasi)
                                            <span><i class="bi bi-geo-alt"></i>
                                                {{ Str::limit($agenda->lokasi, 20) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── STRUKTUR PEMERINTAHAN ────────────────────────────────────────────────── --}}
    @if ($strukturDesa->count())
        <section class="sid-section">
            <div class="container">
                <div class="sid-section-header">
                    <h2 class="sid-section-title">Pemerintahan <span>Desa</span></h2>
                </div>

                <div class="row g-3">
                    @foreach ($strukturDesa as $perangkat)
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="sid-struktur-card text-center">
                                <div class="sid-struktur-avatar">
                                    @if ($perangkat->foto)
                                        <img src="{{ Storage::url($perangkat->foto) }}" alt="{{ $perangkat->nama }}">
                                    @else
                                        {{ strtoupper(substr($perangkat->nama, 0, 2)) }}
                                    @endif
                                </div>
                                <div class="sid-struktur-nama">{{ $perangkat->nama }}</div>
                                <div class="sid-struktur-jabatan">{{ $perangkat->jabatan }}</div>
                                @if ($perangkat->keterangan)
                                    <div class="sid-struktur-ket">{{ $perangkat->keterangan }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── GALERI ───────────────────────────────────────────────────────────────── --}}
    @if ($galeri->count())
        <section class="sid-section sid-section-putih">
            <div class="container">
                <div class="sid-section-header">
                    <h2 class="sid-section-title">Galeri <span>Desa</span></h2>
                    <a href="{{ route('galeri') }}" class="sid-lihat-semua">
                        Lihat semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="sid-galeri-grid">
                    @foreach ($galeri as $i => $foto)
                        <div class="sid-galeri-item {{ $i === 0 ? 'sid-galeri-featured' : '' }}"
                            style="background-image: url('{{ Storage::url($foto->file) }}');"
                            title="{{ $foto->keterangan }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
