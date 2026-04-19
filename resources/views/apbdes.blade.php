@extends('layouts.public')

@section('title', 'APBDes')

@section('content')

    {{-- ── HERO SECTION ─────────────────────────────────────────────────────────── --}}
    <section class="sid-hero">
        <div class="container">
            <div class="sid-hero-inner text-center">
                <span class="sid-hero-badge">
                    <i class="bi bi-bar-chart-fill me-1"></i>
                    Transparansi Anggaran
                </span>
                <h1 class="sid-hero-title">
                    APBDes <em>{{ date('Y') }}</em>
                </h1>
                <p class="sid-hero-lead">
                    Anggaran Pendapatan dan Belanja Desa {{ config('sid.nama_desa') }}
                    tahun {{ date('Y') }} — transparan dan akuntabel untuk warga.
                </p>
            </div>
        </div>
    </section>

    {{-- ── RINGKASAN APBDES ─────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Ringkasan <span>Anggaran</span></h2>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="sid-data-card" style="background: linear-gradient(135deg, #2d8659, #1e6b44); color: white;">
                        <i class="bi bi-wallet2" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <div class="sid-data-label mt-3" style="color: rgba(255,255,255,0.9);">Total Pendapatan</div>
                        <div class="sid-data-num" style="color: white;">Rp 2,8 M</div>
                        <div class="sid-data-sub" style="color: rgba(255,255,255,0.8);">Pagu anggaran {{ date('Y') }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sid-data-card" style="background: linear-gradient(135deg, #d97706, #b45309); color: white;">
                        <i class="bi bi-cash-stack" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <div class="sid-data-label mt-3" style="color: rgba(255,255,255,0.9);">Total Belanja</div>
                        <div class="sid-data-num" style="color: white;">Rp 2,6 M</div>
                        <div class="sid-data-sub" style="color: rgba(255,255,255,0.8);">Realisasi hingga Q1
                            {{ date('Y') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sid-data-card" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white;">
                        <i class="bi bi-piggy-bank-fill" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <div class="sid-data-label mt-3" style="color: rgba(255,255,255,0.9);">SILPA</div>
                        <div class="sid-data-num" style="color: white;">Rp 200 Jt</div>
                        <div class="sid-data-sub" style="color: rgba(255,255,255,0.8);">Sisa Lebih Perhitungan Anggaran
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SUMBER PENDAPATAN ────────────────────────────────────────────────────── --}}
    <section class="sid-section">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Sumber <span>Pendapatan</span></h2>
            </div>

            <div class="sid-data-card">
                <div class="row g-3">
                    @php
                        $pendapatan = [
                            [
                                'label' => 'Dana Desa (APBN)',
                                'nominal' => 'Rp 1.200.000.000',
                                'pct' => 43,
                                'warna' => '#2d8659',
                            ],
                            [
                                'label' => 'Alokasi Dana Desa (APBD Kab.)',
                                'nominal' => 'Rp    850.000.000',
                                'pct' => 30,
                                'warna' => '#d97706',
                            ],
                            [
                                'label' => 'Bagi Hasil Pajak & Retribusi',
                                'nominal' => 'Rp    350.000.000',
                                'pct' => 13,
                                'warna' => '#2563eb',
                            ],
                            [
                                'label' => 'Pendapatan Asli Desa (PADes)',
                                'nominal' => 'Rp    250.000.000',
                                'pct' => 9,
                                'warna' => '#dc2626',
                            ],
                            [
                                'label' => 'Bantuan Provinsi',
                                'nominal' => 'Rp    100.000.000',
                                'pct' => 4,
                                'warna' => '#7c3aed',
                            ],
                            [
                                'label' => 'Lain-lain Pendapatan yang Sah',
                                'nominal' => 'Rp     50.000.000',
                                'pct' => 1,
                                'warna' => '#0891b2',
                            ],
                        ];
                    @endphp

                    @foreach ($pendapatan as $item)
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold">{{ $item['label'] }}</span>
                                <span class="fw-bold">{{ $item['nominal'] }}</span>
                            </div>
                            <div class="sid-data-bar-bg" style="height: 24px;">
                                <div
                                    style="width: {{ $item['pct'] }}%; height: 100%; background: {{ $item['warna'] }}; border-radius: 99px; display: flex; align-items: center; padding-left: 10px; color: white; font-size: 0.8rem; font-weight: 600;">
                                    {{ $item['pct'] }}%
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ── ALOKASI BELANJA ──────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Alokasi <span>Belanja</span></h2>
            </div>

            <div class="row g-3">
                @php
                    $belanja = [
                        [
                            'icon' => 'bi-hammer',
                            'warna' => '#E8F5EE',
                            'color' => '#2d8659',
                            'label' => 'Pembangunan Desa',
                            'nominal' => 'Rp 1,2 M',
                            'desc' => 'Infrastruktur, jalan, drainase',
                        ],
                        [
                            'icon' => 'bi-people-fill',
                            'warna' => '#FEF9E7',
                            'color' => '#d97706',
                            'label' => 'Pembinaan Masyarakat',
                            'nominal' => 'Rp 450 Jt',
                            'desc' => 'PKK, Karang Taruna, Posyandu',
                        ],
                        [
                            'icon' => 'bi-buildings',
                            'warna' => '#E6F1FB',
                            'color' => '#2563eb',
                            'label' => 'Penyelenggaraan Pemerintahan',
                            'nominal' => 'Rp 520 Jt',
                            'desc' => 'Operasional kantor & perangkat',
                        ],
                        [
                            'icon' => 'bi-shield-fill-check',
                            'warna' => '#FDECEA',
                            'color' => '#dc2626',
                            'label' => 'Pemberdayaan Masyarakat',
                            'nominal' => 'Rp 300 Jt',
                            'desc' => 'Pelatihan, UMKM, pertanian',
                        ],
                        [
                            'icon' => 'bi-heart-pulse-fill',
                            'warna' => '#EEEDFE',
                            'color' => '#7c3aed',
                            'label' => 'Penanggulangan Bencana',
                            'nominal' => 'Rp 130 Jt',
                            'desc' => 'Darurat & keadaan mendesak',
                        ],
                        [
                            'icon' => 'bi-cash-coin',
                            'warna' => '#EAF3DE',
                            'color' => '#65a30d',
                            'label' => 'Bantuan Sosial',
                            'nominal' => 'Rp 200 Jt',
                            'desc' => 'BLT-Desa & bansos lainnya',
                        ],
                    ];
                @endphp

                @foreach ($belanja as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="sid-layanan-card h-100">
                            <div class="sid-layanan-icon" style="background: {{ $item['warna'] }};">
                                <i class="bi {{ $item['icon'] }}" style="color: {{ $item['color'] }};"></i>
                            </div>
                            <h3 class="sid-layanan-judul">{{ $item['label'] }}</h3>
                            <p class="sid-layanan-desc">{{ $item['desc'] }}</p>
                            <div class="fw-bold fs-5 mt-2" style="color: {{ $item['color'] }};">
                                {{ $item['nominal'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── INFO TAMBAHAN ────────────────────────────────────────────────────────── --}}
    <section class="sid-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="sid-data-card h-100">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>
                            Tentang APBDes
                        </h5>
                        <p class="text-muted" style="line-height: 1.9;">
                            Anggaran Pendapatan dan Belanja Desa (APBDes) adalah rencana keuangan tahunan
                            pemerintahan desa yang disusun dan dibahas bersama Badan Permusyawaratan Desa (BPD)
                            dan disepakati dalam musyawarah desa.
                        </p>
                        <p class="text-muted" style="line-height: 1.9;">
                            APBDes menjadi wujud nyata transparansi pengelolaan keuangan desa yang dapat
                            diakses oleh seluruh warga sebagai bentuk akuntabilitas pemerintah desa.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="sid-data-card h-100"
                        style="background: linear-gradient(135deg, #2d8659, #1e6b44); color: white;">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-file-earmark-pdf-fill me-2"></i>
                            Dokumen Lengkap
                        </h5>
                        <p style="line-height: 1.9; opacity: 0.9;">
                            Untuk informasi detail mengenai APBDes tahun {{ date('Y') }} termasuk
                            rincian kegiatan dan laporan realisasi per triwulan, silakan menghubungi
                            Kantor Desa atau unduh dokumen resmi di bawah ini.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button class="btn btn-light" disabled>
                                <i class="bi bi-download me-2"></i>Unduh APBDes {{ date('Y') }} (PDF)
                            </button>
                        </div>
                        <small class="d-block mt-3" style="opacity: 0.8;">
                            <i class="bi bi-info-circle me-1"></i>
                            Fitur unduh dokumen dalam tahap pengembangan.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── DISCLAIMER ───────────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="sid-data-card text-center" style="max-width: 800px; margin: 0 auto;">
                <i class="bi bi-shield-exclamation fs-1 text-warning"></i>
                <h5 class="fw-bold mt-3">Catatan Penting</h5>
                <p class="text-muted mb-0">
                    Data yang ditampilkan di halaman ini merupakan <strong>data contoh</strong>
                    untuk keperluan demonstrasi sistem. Nominal dan alokasi anggaran yang sebenarnya
                    akan diperbarui setelah pengesahan APBDes resmi oleh pemerintah desa.
                </p>
            </div>
        </div>
    </section>

@endsection
