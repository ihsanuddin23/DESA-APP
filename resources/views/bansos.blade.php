@extends('layouts.public')

@section('title', 'Bantuan Sosial')

@push('styles')
    <style>
        /* ── Fix section cek status agar tidak overlap dengan section lain ── */
        .bansos-cek-section {
            position: relative;
            clear: both;
            width: 100%;
            display: block;
            z-index: 1;
        }

        .bansos-cek-section .container {
            position: relative;
            z-index: 2;
        }

        .bansos-cek-form-card {
            background: #fff;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
        }

        .bansos-hasil-card {
            background: #fff;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .bansos-hasil-header {
            color: white;
            padding: 1.5rem;
        }

        .bansos-hasil-body {
            padding: 1.5rem;
            background: #fff;
        }

        .bansos-record {
            background: #fafafa;
            padding: 1rem;
            border-radius: .6rem;
            border: 1px solid #e2e8f0;
        }

        .bansos-btn-cari {
            background: #2d8659;
            color: white;
            font-weight: 600;
            padding: .75rem 1.5rem;
            border: none;
            border-radius: .5rem;
            font-size: 1rem;
        }

        .bansos-btn-cari:hover {
            background: #1e6b44;
            color: white;
        }

        .bansos-input {
            font-size: 1rem;
            padding: .7rem .9rem;
            border: 1.5px solid #e2e8f0;
            border-radius: .5rem;
            width: 100%;
        }

        .bansos-input:focus {
            outline: none;
            border-color: #2d8659;
            box-shadow: 0 0 0 3px rgba(45, 134, 89, 0.15);
        }

        .bansos-input.is-invalid {
            border-color: #dc2626;
        }
    </style>
@endpush

@section('content')

    {{-- ── HERO SECTION ─────────────────────────────────────────────────────────── --}}
    <section class="sid-hero">
        <div class="container">
            <div class="sid-hero-inner text-center">
                <span class="sid-hero-badge">
                    <i class="bi bi-heart-fill me-1"></i>
                    Program Kesejahteraan Warga
                </span>
                <h1 class="sid-hero-title">
                    Bantuan <em>Sosial</em>
                </h1>
                <p class="sid-hero-lead">
                    Program bantuan sosial untuk warga {{ config('sid.nama_desa') }} — dari pemerintah pusat,
                    provinsi, kabupaten, hingga desa. Transparan, tepat sasaran, dan akuntabel.
                </p>
                <div class="mt-4">
                    <a href="#cek-status" class="btn btn-light fw-semibold">
                        <i class="bi bi-search me-2"></i>Cek Status Penerima
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── RINGKASAN BANSOS ─────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Ringkasan <span>Program</span></h2>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="sid-data-card" style="background: linear-gradient(135deg, #2d8659, #1e6b44); color: white;">
                        <i class="bi bi-grid-fill" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <div class="sid-data-label mt-3" style="color: rgba(255,255,255,0.9);">Program Aktif</div>
                        <div class="sid-data-num" style="color: white;">{{ $totalProgram }} Program</div>
                        <div class="sid-data-sub" style="color: rgba(255,255,255,0.8);">Bansos berjalan {{ date('Y') }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sid-data-card" style="background: linear-gradient(135deg, #d97706, #b45309); color: white;">
                        <i class="bi bi-people-fill" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <div class="sid-data-label mt-3" style="color: rgba(255,255,255,0.9);">Total Penerima</div>
                        <div class="sid-data-num" style="color: white;">{{ number_format($totalPenerimaAktif) }} KPM</div>
                        <div class="sid-data-sub" style="color: rgba(255,255,255,0.8);">Terdata aktif {{ date('Y') }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sid-data-card" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white;">
                        <i class="bi bi-cash-coin" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <div class="sid-data-label mt-3" style="color: rgba(255,255,255,0.9);">Total Nominal</div>
                        <div class="sid-data-num" style="color: white;">
                            @php
                                $n = (float) $totalNominalTahun;
                                if ($n >= 1_000_000_000) {
                                    $nominalDisplay = 'Rp ' . number_format($n / 1_000_000_000, 1, ',', '.') . ' M';
                                } elseif ($n >= 1_000_000) {
                                    $nominalDisplay = 'Rp ' . number_format($n / 1_000_000, 0, ',', '.') . ' Jt';
                                } elseif ($n > 0) {
                                    $nominalDisplay = 'Rp ' . number_format($n, 0, ',', '.');
                                } else {
                                    $nominalDisplay = 'Rp 0';
                                }
                            @endphp
                            {{ $nominalDisplay }}
                        </div>
                        <div class="sid-data-sub" style="color: rgba(255,255,255,0.8);">Akumulasi penyaluran
                            {{ date('Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═════════════════════════════════════════════════════════════════════════
         CEK STATUS PENERIMA — pakai CSS custom supaya tidak tabrakan
         ═════════════════════════════════════════════════════════════════════════ --}}
    <section class="bansos-cek-section" id="cek-status" style="padding: 4rem 0; background: #f8fafc;">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="font-size: 2rem;">Cek Status <span style="color: #2d8659;">Penerima</span></h2>
                <p class="text-muted">Masukkan NIK dan nama lengkap untuk memeriksa status Anda</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">

                    {{-- ── HASIL PENCARIAN (muncul di atas form) ──────────────── --}}
                    @isset($cekHasil)
                        @php
                            $gradHeader = match ($cekHasil['status']) {
                                'aktif' => 'linear-gradient(135deg, #2d8659, #1e6b44)',
                                'riwayat' => 'linear-gradient(135deg, #d97706, #b45309)',
                                'tidak_ditemukan' => 'linear-gradient(135deg, #64748b, #475569)',
                                default => 'linear-gradient(135deg, #64748b, #475569)',
                            };
                            $iconHeader = match ($cekHasil['status']) {
                                'aktif' => 'bi-check-circle-fill',
                                'riwayat' => 'bi-info-circle-fill',
                                'tidak_ditemukan' => 'bi-x-circle-fill',
                                default => 'bi-question-circle-fill',
                            };
                        @endphp

                        <div class="bansos-hasil-card">
                            <div class="bansos-hasil-header" style="background: {{ $gradHeader }};">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="bi {{ $iconHeader }}" style="font-size: 2rem;"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-1" style="color: white;">
                                            {{ $cekHasil['pesan'] }}
                                        </h5>
                                        <div class="small" style="opacity: 0.9;">
                                            NIK: <code
                                                style="color: white; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 4px;">{{ $cekHasil['nik'] }}</code>
                                            @if (!empty($cekHasil['nama']))
                                                &nbsp;·&nbsp;Nama: <strong>{{ $cekHasil['nama'] }}</strong>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bansos-hasil-body">
                                @if ($cekHasil['status'] === 'tidak_ditemukan')
                                    <div class="d-flex gap-3 align-items-start">
                                        <i class="bi bi-info-circle text-primary" style="font-size: 1.5rem;"></i>
                                        <div>
                                            <p class="mb-2">Beberapa kemungkinan:</p>
                                            <ul class="mb-3 text-muted">
                                                <li>NIK atau nama yang dimasukkan tidak sesuai (periksa ejaan)</li>
                                                <li>Anda belum terdaftar sebagai penerima bansos di desa ini</li>
                                                <li>Data Anda belum diinput atau sedang dalam proses verifikasi</li>
                                            </ul>
                                            <p class="mb-0 small">
                                                <strong>Langkah selanjutnya:</strong> Silakan hubungi RT/RW
                                                setempat atau datang ke Kantor Desa untuk informasi lebih lanjut.
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <h6 class="fw-bold mb-3">Daftar Program Terdaftar:</h6>
                                        <div class="d-flex flex-column gap-3">
                                            @foreach ($cekHasil['records'] as $rec)
                                                @php
                                                    $bgStatus = match ($rec->status) {
                                                        'aktif' => [
                                                            'bg' => '#dcfce7',
                                                            'fg' => '#166534',
                                                            'label' => 'AKTIF',
                                                            'icon' => 'bi-check-circle-fill',
                                                        ],
                                                        'nonaktif' => [
                                                            'bg' => '#f1f5f9',
                                                            'fg' => '#475569',
                                                            'label' => 'NONAKTIF',
                                                            'icon' => 'bi-pause-circle-fill',
                                                        ],
                                                        'dicoret' => [
                                                            'bg' => '#fee2e2',
                                                            'fg' => '#991b1b',
                                                            'label' => 'DICORET',
                                                            'icon' => 'bi-x-circle-fill',
                                                        ],
                                                    };
                                                @endphp

                                                <div class="bansos-record"
                                                    style="border-left: 4px solid {{ $bgStatus['fg'] }};">
                                                    <div
                                                        class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                                        <div>
                                                            <div class="fw-bold" style="color: #0f172a; font-size: 1.05rem;">
                                                                {{ $rec->program->nama }}
                                                            </div>
                                                            <div class="small text-muted mt-1">
                                                                <span class="badge"
                                                                    style="background: #e0e7ff; color: #3730a3; font-family: monospace;">
                                                                    {{ $rec->program->kode }}
                                                                </span>
                                                                &nbsp;·&nbsp;{{ $rec->program->jenis_label }}
                                                            </div>
                                                        </div>
                                                        <span class="badge"
                                                            style="background: {{ $bgStatus['bg'] }}; color: {{ $bgStatus['fg'] }}; font-weight: 700; padding: .4rem .7rem;">
                                                            <i class="bi {{ $bgStatus['icon'] }} me-1"></i>
                                                            {{ $bgStatus['label'] }}
                                                        </span>
                                                    </div>

                                                    <div class="row g-2 small mt-2">
                                                        <div class="col-6 col-md-4">
                                                            <div class="text-muted">Tahun</div>
                                                            <div class="fw-semibold">{{ $rec->tahun }}</div>
                                                        </div>
                                                        <div class="col-6 col-md-4">
                                                            <div class="text-muted">Periode</div>
                                                            <div class="fw-semibold">{{ $rec->periode_label }}</div>
                                                        </div>
                                                    </div>

                                                    @if ($rec->keterangan)
                                                        <div class="mt-3 p-2 rounded"
                                                            style="background: #fef3c7; border-left: 3px solid #d97706;">
                                                            <div class="small text-muted fw-semibold mb-1">
                                                                <i class="bi bi-info-circle me-1"></i>Keterangan:
                                                            </div>
                                                            <div class="small">{{ $rec->keterangan }}</div>
                                                        </div>
                                                    @elseif($rec->status === 'dicoret')
                                                        <div class="mt-3 p-2 rounded small"
                                                            style="background: #fee2e2; color: #991b1b;">
                                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                                            Data dicoret dari daftar penerima. Hubungi Kantor Desa untuk
                                                            informasi lebih lanjut.
                                                        </div>
                                                    @elseif($rec->status === 'nonaktif')
                                                        <div class="mt-3 p-2 rounded small"
                                                            style="background: #f1f5f9; color: #475569;">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Program untuk periode ini telah berakhir.
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="p-3 rounded" style="background: #eff6ff; border-left: 3px solid #2563eb;">
                                        <div class="small">
                                            <i class="bi bi-shield-check text-primary me-1"></i>
                                            <strong>Catatan:</strong> Data nominal, alamat lengkap, dan detail
                                            pribadi lainnya tidak ditampilkan demi menjaga privasi.
                                            Untuk verifikasi dan pengambilan bantuan, silakan datang langsung
                                            ke Kantor Desa dengan membawa KTP.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endisset

                    {{-- ── FORM CEK STATUS ─────────────────────────────────────── --}}
                    <div class="bansos-cek-form-card">
                        <h5 class="fw-bold mb-2">
                            <i class="bi bi-search" style="color: #2d8659;"></i>
                            Cek Status Anda
                        </h5>
                        <p class="text-muted small mb-4">
                            Masukkan NIK dan Nama Lengkap sesuai KTP untuk mengecek apakah Anda terdaftar
                            sebagai penerima bansos di {{ config('sid.nama_desa') }}.
                        </p>

                        <form method="POST" action="{{ route('bansos.cek') }}#cek-status" autocomplete="off">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        NIK <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nik"
                                        class="bansos-input @error('nik') is-invalid @enderror"
                                        value="{{ old('nik') }}" maxlength="16" inputmode="numeric"
                                        pattern="[0-9]{16}" placeholder="16 digit NIK"
                                        style="font-family: monospace; letter-spacing: 1px;" required>
                                    @error('nik')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text small">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Cek di KTP Anda — 16 digit angka
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nama_penerima"
                                        class="bansos-input @error('nama_penerima') is-invalid @enderror"
                                        value="{{ old('nama_penerima') }}" maxlength="100" placeholder="Nama sesuai KTP"
                                        required>
                                    @error('nama_penerima')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text small">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Ketik persis seperti di KTP
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4">
                                <div class="small text-muted">
                                    <i class="bi bi-shield-lock me-1"></i>
                                    Data Anda aman dan dilindungi
                                </div>
                                <button type="submit" class="bansos-btn-cari">
                                    <i class="bi bi-search me-2"></i>
                                    Cari Data Saya
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Info privasi --}}
                    <div class="mt-3 p-3 rounded" style="background: #fff; border: 1px solid #e2e8f0;">
                        <div class="d-flex gap-2 align-items-start small text-muted">
                            <i class="bi bi-shield-check mt-1" style="color: #2d8659;"></i>
                            <div>
                                <strong class="text-dark">Kebijakan Privasi Pencarian:</strong>
                                Sistem ini hanya menampilkan status keanggotaan program bansos Anda.
                                Data sensitif seperti alamat lengkap, nomor KK, dan nominal bantuan
                                <strong>tidak ditampilkan</strong> di halaman publik. Setiap pencarian
                                dibatasi untuk mencegah penyalahgunaan.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- ── SUMBER PROGRAM (distribusi berdasarkan jenis) ────────────────────────── --}}
    @php
        $byJenis = $programs->groupBy('jenis')->map->count();
        $total = max($programs->count(), 1);

        $jenisInfo = [
            'pusat' => [
                'label' => 'Pemerintah Pusat (APBN)',
                'warna' => '#2d8659',
                'deskripsi' => 'PKH, BPNT, Kartu Sembako',
            ],
            'provinsi' => [
                'label' => 'Pemerintah Provinsi (APBD Prov.)',
                'warna' => '#d97706',
                'deskripsi' => 'Bansos Jaring Pengaman Sosial',
            ],
            'kabupaten' => [
                'label' => 'Pemerintah Kabupaten (APBD Kab.)',
                'warna' => '#2563eb',
                'deskripsi' => 'Bantuan Lansia & Disabilitas',
            ],
            'desa' => ['label' => 'Pemerintah Desa (APBDes)', 'warna' => '#7c3aed', 'deskripsi' => 'BLT Dana Desa'],
        ];
    @endphp

    @if ($programs->count())
        <section class="sid-section sid-section-putih">
            <div class="container">
                <div class="sid-section-header">
                    <h2 class="sid-section-title">Sumber <span>Program</span></h2>
                </div>

                <div class="sid-data-card">
                    <div class="row g-3">
                        @foreach ($jenisInfo as $key => $info)
                            @php
                                $jumlah = $byJenis[$key] ?? 0;
                                $pct = $total > 0 ? round(($jumlah / $total) * 100) : 0;
                            @endphp
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold">{{ $info['label'] }}</span>
                                    <span class="text-muted small">
                                        {{ $jumlah }} program · {{ $info['deskripsi'] }}
                                    </span>
                                </div>
                                <div class="sid-data-bar-bg" style="height: 24px;">
                                    <div
                                        style="width: {{ max($pct, 2) }}%; height: 100%; background: {{ $info['warna'] }}; border-radius: 99px; display: flex; align-items: center; padding-left: 10px; color: white; font-size: 0.8rem; font-weight: 600;">
                                        {{ $pct }}%
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ── DAFTAR PROGRAM ──────────────────────────────────────────────────────── --}}
    <section class="sid-section">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Program <span>Bansos</span></h2>
            </div>

            @php
                $visualMap = [
                    'PKH' => ['icon' => 'bi-heart-pulse-fill', 'bg' => '#E8F5EE', 'fg' => '#2d8659'],
                    'BPNT' => ['icon' => 'bi-basket-fill', 'bg' => '#FEF9E7', 'fg' => '#d97706'],
                    'BLT-DD' => ['icon' => 'bi-cash-stack', 'bg' => '#E6F1FB', 'fg' => '#2563eb'],
                    'KS' => ['icon' => 'bi-credit-card-2-front-fill', 'bg' => '#FDECEA', 'fg' => '#dc2626'],
                    'BLU' => ['icon' => 'bi-person-heart', 'bg' => '#EEEDFE', 'fg' => '#7c3aed'],
                    'ASPDB' => ['icon' => 'bi-universal-access-circle', 'bg' => '#EAF3DE', 'fg' => '#65a30d'],
                ];

                $defaultPalette = [
                    ['bg' => '#E8F5EE', 'fg' => '#2d8659'],
                    ['bg' => '#FEF9E7', 'fg' => '#d97706'],
                    ['bg' => '#E6F1FB', 'fg' => '#2563eb'],
                    ['bg' => '#FDECEA', 'fg' => '#dc2626'],
                    ['bg' => '#EEEDFE', 'fg' => '#7c3aed'],
                    ['bg' => '#EAF3DE', 'fg' => '#65a30d'],
                ];
            @endphp

            <div class="row g-3">
                @forelse ($programs as $i => $program)
                    @php
                        $v = $visualMap[$program->kode] ?? null;
                        if (!$v) {
                            $pal = $defaultPalette[$i % count($defaultPalette)];
                            $v = ['icon' => 'bi-cash-coin', 'bg' => $pal['bg'], 'fg' => $pal['fg']];
                        }
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="sid-layanan-card h-100">
                            <div class="sid-layanan-icon" style="background: {{ $v['bg'] }};">
                                <i class="bi {{ $v['icon'] }}" style="color: {{ $v['fg'] }};"></i>
                            </div>
                            <h3 class="sid-layanan-judul">{{ $program->nama }}</h3>
                            <span class="badge mb-2"
                                style="background: {{ $v['bg'] }}; color: {{ $v['fg'] }}; font-family: monospace; font-weight: 700;">
                                {{ $program->kode }}
                            </span>
                            <p class="sid-layanan-desc">
                                {{ $program->deskripsi ?? 'Program bantuan sosial ' . $program->jenis_label }}
                            </p>
                            <div class="fw-bold fs-5 mt-2" style="color: {{ $v['fg'] }};">
                                @if ($program->nominal_per_bulan)
                                    Rp {{ number_format($program->nominal_per_bulan, 0, ',', '.') }}
                                    <span class="text-muted fw-normal" style="font-size: 0.75rem;">/ bulan</span>
                                @else
                                    <span class="text-muted fw-normal" style="font-size: 0.9rem;">Nominal variatif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="sid-data-card text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <h5 class="fw-bold mt-3">Belum Ada Program Aktif</h5>
                            <p class="text-muted mb-0">
                                Saat ini belum ada program bantuan sosial yang aktif.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ── SYARAT & ALUR ──────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="sid-data-card h-100">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-check2-square text-primary me-2"></i>
                            Syarat Pengajuan
                        </h5>
                        <ol class="ps-3 mb-0" style="line-height: 2;">
                            <li>Warga desa yang terdaftar dalam KK setempat</li>
                            <li>Memiliki KTP yang masih berlaku</li>
                            <li>Termasuk kategori keluarga kurang mampu / rentan sesuai DTKS</li>
                            <li>Belum menerima bantuan serupa dari sumber lain</li>
                            <li>Surat Keterangan Tidak Mampu (SKTM) dari RT/RW</li>
                            <li>Mengisi formulir pengajuan di kantor desa</li>
                        </ol>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="sid-data-card h-100">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-diagram-3-fill text-primary me-2"></i>
                            Alur Pengajuan
                        </h5>

                        <div class="d-flex flex-column gap-3">
                            @php
                                $alur = [
                                    [
                                        'no' => 1,
                                        'warna' => '#2d8659',
                                        'judul' => 'Pengajuan ke RT/RW',
                                        'desc' => 'Warga mengajukan permohonan beserta dokumen persyaratan.',
                                    ],
                                    [
                                        'no' => 2,
                                        'warna' => '#d97706',
                                        'judul' => 'Verifikasi Lapangan',
                                        'desc' => 'RT/RW memverifikasi dan memberikan rekomendasi ke desa.',
                                    ],
                                    [
                                        'no' => 3,
                                        'warna' => '#2563eb',
                                        'judul' => 'Pendataan di Desa',
                                        'desc' => 'Data diinput & disinkronkan dengan DTKS Kemensos.',
                                    ],
                                    [
                                        'no' => 4,
                                        'warna' => '#7c3aed',
                                        'judul' => 'Musyawarah Desa',
                                        'desc' => 'Penetapan calon penerima dalam musdes.',
                                    ],
                                    [
                                        'no' => 5,
                                        'warna' => '#dc2626',
                                        'judul' => 'Penyaluran Bantuan',
                                        'desc' => 'Disalurkan melalui bank atau kantor pos.',
                                    ],
                                ];
                            @endphp

                            @foreach ($alur as $step)
                                <div class="d-flex gap-3 align-items-start">
                                    <div class="flex-shrink-0"
                                        style="width: 36px; height: 36px; border-radius: 50%; background: {{ $step['warna'] }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                        {{ $step['no'] }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="color: #0f172a;">{{ $step['judul'] }}</div>
                                        <div class="text-muted small">{{ $step['desc'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
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
                            <i class="bi bi-file-earmark-text-fill text-primary me-2"></i>
                            Dokumen yang Diperlukan
                        </h5>
                        <ul class="mb-0" style="line-height: 1.9;">
                            <li>Fotokopi KTP</li>
                            <li>Fotokopi Kartu Keluarga (KK)</li>
                            <li>Surat Keterangan Tidak Mampu (SKTM)</li>
                            <li>Surat pengantar RT/RW</li>
                            <li>Pas foto 3x4 (jika diperlukan)</li>
                        </ul>
                        <hr class="my-3">
                        <h6 class="fw-bold">
                            <i class="bi bi-clock-history text-primary me-1"></i>
                            Jam Pelayanan
                        </h6>
                        <div class="small">
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Senin – Kamis</span>
                                <span class="fw-600">08.00 – 15.00</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Jumat</span>
                                <span class="fw-600">08.00 – 11.30</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Sabtu – Minggu</span>
                                <span class="text-danger fw-600">Tutup</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="sid-data-card h-100"
                        style="background: linear-gradient(135deg, #2d8659, #1e6b44); color: white;">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-telephone-fill me-2"></i>
                            Butuh Bantuan?
                        </h5>
                        <p style="line-height: 1.9; opacity: 0.9;">
                            Untuk informasi lebih lanjut mengenai program bantuan sosial, silakan menghubungi
                            Kantor Desa atau RT/RW setempat. Tim kami siap membantu proses pengajuan dan
                            verifikasi dokumen Anda.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('kontak') }}" class="btn btn-light">
                                <i class="bi bi-envelope-fill me-2"></i>Hubungi Kantor Desa
                            </a>
                            <a href="{{ route('aduan') }}" class="btn btn-outline-light">
                                <i class="bi bi-megaphone-fill me-2"></i>Ajukan Aduan
                            </a>
                        </div>
                        <small class="d-block mt-3" style="opacity: 0.8;">
                            <i class="bi bi-info-circle me-1"></i>
                            Pelayanan gratis dan tanpa pungutan.
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
                    Data program dan penerima diambil langsung dari basis data Sistem Informasi Desa.
                    Kuota dan nominal aktual dapat berubah sesuai kebijakan pemerintah dan hasil verifikasi
                    DTKS terbaru. Informasi resmi terkini dapat dikonfirmasi langsung ke Kantor Desa.
                </p>
            </div>
        </div>
    </section>

@endsection
