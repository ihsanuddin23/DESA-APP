@extends('layouts.public')

@section('title', 'Posyandu')

@section('content')

    {{-- ── HERO SECTION ─────────────────────────────────────────────────────────── --}}
    <section class="sid-hero">
        <div class="container">
            <div class="sid-hero-inner text-center">
                <span class="sid-hero-badge">
                    <i class="bi bi-heart-pulse-fill me-1"></i>
                    Pos Pelayanan Terpadu
                </span>
                <h1 class="sid-hero-title">
                    Posyandu <em>Sehat</em>
                </h1>
                <p class="sid-hero-lead">
                    Layanan kesehatan dasar untuk ibu hamil, balita, dan lansia di {{ config('sid.nama_desa') }}.
                    Gratis, terjadwal, dan dilaksanakan oleh kader terlatih bersama bidan desa.
                </p>
                <div class="mt-4">
                    <a href="#jadwal" class="btn btn-light fw-semibold">
                        <i class="bi bi-calendar-event me-2"></i>Lihat Jadwal Mendatang
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── RINGKASAN ────────────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Ringkasan <span>Posyandu</span></h2>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <div class="sid-data-card" style="background: linear-gradient(135deg, #2d8659, #1e6b44); color: white;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <div class="sid-data-label mt-3" style="color: rgba(255,255,255,0.9);">Jumlah Posyandu</div>
                        <div class="sid-data-num" style="color: white;">{{ $totalPosyandu }} Pos</div>
                        <div class="sid-data-sub" style="color: rgba(255,255,255,0.8);">Aktif di desa</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sid-data-card" style="background: linear-gradient(135deg, #d97706, #b45309); color: white;">
                        <i class="bi bi-emoji-smile-fill" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <div class="sid-data-label mt-3" style="color: rgba(255,255,255,0.9);">Balita Terdaftar</div>
                        <div class="sid-data-num" style="color: white;">{{ number_format($totalBalita) }}</div>
                        <div class="sid-data-sub" style="color: rgba(255,255,255,0.8);">Total di semua posyandu</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sid-data-card" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white;">
                        <i class="bi bi-person-hearts" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <div class="sid-data-label mt-3" style="color: rgba(255,255,255,0.9);">Kader Aktif</div>
                        <div class="sid-data-num" style="color: white;">{{ $totalKader }} Orang</div>
                        <div class="sid-data-sub" style="color: rgba(255,255,255,0.8);">Relawan terlatih</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sid-data-card" style="background: linear-gradient(135deg, #7c3aed, #5b21b6); color: white;">
                        <i class="bi bi-calendar-check-fill" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <div class="sid-data-label mt-3" style="color: rgba(255,255,255,0.9);">Jadwal Bulan Ini</div>
                        <div class="sid-data-num" style="color: white;">{{ $jadwalBulanIni }}</div>
                        <div class="sid-data-sub" style="color: rgba(255,255,255,0.8);">Kegiatan direncanakan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── DAFTAR POSYANDU ──────────────────────────────────────────────────────── --}}
    <section class="sid-section">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Daftar <span>Posyandu</span></h2>
            </div>

            @php
                // Mapping warna rotasi berdasarkan urutan
                $palet = [
                    ['bg' => '#E8F5EE', 'fg' => '#2d8659', 'icon' => 'bi-flower1'],
                    ['bg' => '#FEF9E7', 'fg' => '#d97706', 'icon' => 'bi-flower1'],
                    ['bg' => '#FDECEA', 'fg' => '#dc2626', 'icon' => 'bi-flower2'],
                    ['bg' => '#EEEDFE', 'fg' => '#7c3aed', 'icon' => 'bi-flower3'],
                    ['bg' => '#E6F1FB', 'fg' => '#2563eb', 'icon' => 'bi-flower2'],
                    ['bg' => '#EAF3DE', 'fg' => '#65a30d', 'icon' => 'bi-flower1'],
                ];
            @endphp

            <div class="row g-3">
                @forelse ($posyandu as $i => $pos)
                    @php
                        $p = $palet[$i % count($palet)];
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="sid-layanan-card h-100">
                            <div class="sid-layanan-icon" style="background: {{ $p['bg'] }};">
                                <i class="bi {{ $p['icon'] }}" style="color: {{ $p['fg'] }};"></i>
                            </div>
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                <h3 class="sid-layanan-judul mb-0">{{ $pos->nama }}</h3>
                                <span class="badge"
                                    style="background: {{ $p['bg'] }}; color: {{ $p['fg'] }}; font-family: monospace; font-weight: 700; flex-shrink: 0;">
                                    {{ $pos->rw ? 'RW ' . $pos->rw : 'Desa' }}
                                </span>
                            </div>

                            @if ($pos->jenis === 'lansia')
                                <span class="badge mb-2" style="background: #fef3c7; color: #92400e;">
                                    <i class="bi bi-person-wheelchair me-1"></i>Khusus Lansia
                                </span>
                            @elseif($pos->jenis === 'terpadu')
                                <span class="badge mb-2" style="background: #e0e7ff; color: #3730a3;">
                                    <i class="bi bi-people-fill me-1"></i>Balita & Lansia
                                </span>
                            @endif

                            <p class="sid-layanan-desc mb-3">
                                <i class="bi bi-geo-alt text-muted me-1"></i> {{ $pos->lokasi }}
                            </p>

                            <div class="small">
                                @if ($pos->ketua_kader)
                                    <div class="d-flex justify-content-between py-1" style="border-top: 1px solid #f1f5f9;">
                                        <span class="text-muted"><i class="bi bi-person-badge me-1"></i>Ketua Kader</span>
                                        <span class="fw-semibold">{{ $pos->ketua_kader }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between py-1" style="border-top: 1px solid #f1f5f9;">
                                    <span class="text-muted"><i class="bi bi-people me-1"></i>Kader</span>
                                    <span class="fw-semibold">{{ $pos->jumlah_kader }} orang</span>
                                </div>
                                <div class="d-flex justify-content-between py-1" style="border-top: 1px solid #f1f5f9;">
                                    <span class="text-muted">
                                        <i
                                            class="bi bi-{{ $pos->jenis === 'lansia' ? 'person-heart' : 'emoji-smile' }} me-1"></i>
                                        {{ $pos->jenis === 'lansia' ? 'Lansia' : 'Balita' }}
                                    </span>
                                    <span class="fw-semibold">{{ $pos->jumlah_balita }} orang</span>
                                </div>
                                @if ($pos->kontak)
                                    <div class="d-flex justify-content-between py-1"
                                        style="border-top: 1px solid #f1f5f9;">
                                        <span class="text-muted"><i class="bi bi-whatsapp me-1"></i>Kontak</span>
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pos->kontak) }}"
                                            target="_blank" class="fw-semibold text-decoration-none"
                                            style="color: {{ $p['fg'] }};">
                                            {{ $pos->kontak }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="sid-data-card text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <h5 class="fw-bold mt-3">Belum Ada Posyandu Aktif</h5>
                            <p class="text-muted mb-0">
                                Saat ini belum ada data posyandu yang terdaftar.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ── LAYANAN YANG TERSEDIA ────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Layanan <span>Tersedia</span></h2>
            </div>

            <div class="row g-3">
                @php
                    $layanan = [
                        [
                            'icon' => 'bi-speedometer2',
                            'warna' => '#E8F5EE',
                            'color' => '#2d8659',
                            'label' => 'Penimbangan Balita',
                            'desc' => 'Pemantauan pertumbuhan & gizi balita setiap bulan',
                        ],
                        [
                            'icon' => 'bi-bandaid-fill',
                            'warna' => '#FEF9E7',
                            'color' => '#d97706',
                            'label' => 'Imunisasi Dasar',
                            'desc' => 'BCG, DPT, Polio, Campak, dan Hepatitis B',
                        ],
                        [
                            'icon' => 'bi-basket3-fill',
                            'warna' => '#E6F1FB',
                            'color' => '#2563eb',
                            'label' => 'Makanan Tambahan',
                            'desc' => 'PMT untuk balita dan ibu hamil kurang gizi',
                        ],
                        [
                            'icon' => 'bi-heart-fill',
                            'warna' => '#FDECEA',
                            'color' => '#dc2626',
                            'label' => 'Pemeriksaan Ibu Hamil',
                            'desc' => 'ANC, tekanan darah, berat badan, tinggi fundus',
                        ],
                        [
                            'icon' => 'bi-capsule',
                            'warna' => '#EEEDFE',
                            'color' => '#7c3aed',
                            'label' => 'Vitamin A & Obat Cacing',
                            'desc' => 'Distribusi kapsul Vitamin A Februari & Agustus',
                        ],
                        [
                            'icon' => 'bi-chat-heart-fill',
                            'warna' => '#EAF3DE',
                            'color' => '#65a30d',
                            'label' => 'Konsultasi Gizi',
                            'desc' => 'Konseling makanan sehat, MPASI, ASI eksklusif',
                        ],
                    ];
                @endphp

                @foreach ($layanan as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="sid-layanan-card h-100">
                            <div class="sid-layanan-icon" style="background: {{ $item['warna'] }};">
                                <i class="bi {{ $item['icon'] }}" style="color: {{ $item['color'] }};"></i>
                            </div>
                            <h3 class="sid-layanan-judul">{{ $item['label'] }}</h3>
                            <p class="sid-layanan-desc mb-0">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── JADWAL MENDATANG (dari DB) ──────────────────────────────────────────── --}}
    <section class="sid-section" id="jadwal">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Jadwal <span>Mendatang</span></h2>
                <small class="text-muted">30 hari ke depan</small>
            </div>

            <div class="sid-data-card p-0" style="overflow: hidden;">
                <div class="table-responsive">
                    <table class="table mb-0" style="min-width: 700px;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th class="py-3 px-4" style="color: #475569; font-size: .85rem;">Tanggal</th>
                                <th class="py-3 px-4" style="color: #475569; font-size: .85rem;">Waktu</th>
                                <th class="py-3 px-4" style="color: #475569; font-size: .85rem;">Posyandu</th>
                                <th class="py-3 px-4" style="color: #475569; font-size: .85rem;">Kegiatan</th>
                                <th class="py-3 px-4" style="color: #475569; font-size: .85rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jadwalMendatang as $j)
                                <tr style="border-top: 1px solid #f1f5f9;">
                                    <td class="py-3 px-4">
                                        <div class="fw-semibold" style="font-size: .88rem;">
                                            {{ $j->tanggal->isoFormat('D MMM YYYY') }}
                                        </div>
                                        <div class="small text-muted">{{ $j->tanggal->isoFormat('dddd') }}</div>
                                    </td>
                                    <td class="py-3 px-4 small" style="font-family: monospace;">
                                        <i class="bi bi-clock me-1 text-muted"></i>{{ $j->waktu_format }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="fw-semibold" style="font-size: .88rem;">{{ $j->posyandu->nama }}
                                        </div>
                                        <div class="small text-muted">{{ $j->posyandu->lokasi }}</div>
                                    </td>
                                    <td class="py-3 px-4 small">{{ $j->kegiatan }}</td>
                                    <td class="py-3 px-4">
                                        @if ($j->status === 'terjadwal')
                                            <span class="badge"
                                                style="background:#dbeafe;color:#1e40af;">Terjadwal</span>
                                        @elseif($j->status === 'berlangsung')
                                            <span class="badge"
                                                style="background:#fef3c7;color:#92400e;">Berlangsung</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                                        Belum ada jadwal kegiatan dalam 30 hari ke depan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3 p-3 rounded" style="background: #eff6ff; border-left: 3px solid #2563eb;">
                <div class="small d-flex gap-2">
                    <i class="bi bi-info-circle-fill" style="color: #2563eb;"></i>
                    <div>
                        <strong>Catatan:</strong> Jadwal dapat berubah pada hari libur nasional atau jika bertepatan
                        dengan acara desa. Informasi perubahan akan diumumkan melalui RT/RW setempat atau di halaman
                        <a href="{{ route('pengumuman') }}" class="text-primary">Pengumuman</a>.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SYARAT & TIPS ────────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="sid-data-card h-100">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-check2-square text-primary me-2"></i>
                            Yang Harus Dibawa
                        </h5>
                        <ul class="ps-3 mb-0" style="line-height: 2;">
                            <li><strong>KMS (Kartu Menuju Sehat)</strong> balita</li>
                            <li><strong>Buku KIA</strong> untuk ibu hamil</li>
                            <li>Fotokopi KTP ibu/pengasuh</li>
                            <li>Fotokopi Kartu Keluarga (KK)</li>
                            <li>Catatan keluhan kesehatan (jika ada)</li>
                            <li>Balita sudah makan pagi & tidak dalam kondisi sakit berat</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="sid-data-card h-100">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-lightbulb-fill text-primary me-2"></i>
                            Tips Kunjungan Posyandu
                        </h5>
                        <div class="d-flex flex-column gap-3">
                            @php
                                $tips = [
                                    [
                                        'no' => 1,
                                        'warna' => '#2d8659',
                                        'judul' => 'Datang Tepat Waktu',
                                        'desc' => 'Antrian panjang di akhir, lebih nyaman datang pagi.',
                                    ],
                                    [
                                        'no' => 2,
                                        'warna' => '#d97706',
                                        'judul' => 'Kenakan Pakaian Longgar',
                                        'desc' => 'Memudahkan pemeriksaan dan penimbangan balita.',
                                    ],
                                    [
                                        'no' => 3,
                                        'warna' => '#2563eb',
                                        'judul' => 'Catat Riwayat',
                                        'desc' => 'Simpan KMS dengan baik, catat perkembangan bulanan.',
                                    ],
                                    [
                                        'no' => 4,
                                        'warna' => '#7c3aed',
                                        'judul' => 'Aktif Bertanya',
                                        'desc' => 'Konsultasikan masalah tumbuh kembang dengan kader.',
                                    ],
                                ];
                            @endphp
                            @foreach ($tips as $tip)
                                <div class="d-flex gap-3 align-items-start">
                                    <div class="flex-shrink-0"
                                        style="width: 36px; height: 36px; border-radius: 50%; background: {{ $tip['warna'] }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                        {{ $tip['no'] }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="color: #0f172a;">{{ $tip['judul'] }}</div>
                                        <div class="text-muted small">{{ $tip['desc'] }}</div>
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
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>
                            Tentang Posyandu
                        </h5>
                        <p class="text-muted" style="line-height: 1.9;">
                            Posyandu (Pos Pelayanan Terpadu) adalah wadah pemeliharaan kesehatan dasar masyarakat
                            yang dikelola dan diselenggarakan dari, oleh, untuk, dan bersama masyarakat.
                        </p>
                        <p class="text-muted mb-0" style="line-height: 1.9;">
                            Kegiatan utama posyandu meliputi KIA (Kesehatan Ibu dan Anak), KB, Imunisasi, Gizi,
                            serta Pencegahan dan Penanggulangan Diare. Semua layanan ini diberikan
                            <strong>gratis</strong> bagi warga {{ config('sid.nama_desa') }}.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="sid-data-card h-100"
                        style="background: linear-gradient(135deg, #2d8659, #1e6b44); color: white;">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-telephone-fill me-2"></i>
                            Butuh Informasi?
                        </h5>
                        <p style="line-height: 1.9; opacity: 0.9;">
                            Untuk informasi jadwal, konsultasi, atau pendaftaran balita baru, silakan menghubungi
                            kader posyandu di RW masing-masing (nomor tertera di kartu posyandu di atas),
                            atau datang langsung ke Kantor Desa.
                        </p>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('kontak') }}" class="btn btn-light">
                                <i class="bi bi-envelope-fill me-2"></i>Kantor Desa
                            </a>
                            <a href="{{ route('aduan') }}" class="btn btn-outline-light">
                                <i class="bi bi-megaphone-fill me-2"></i>Aduan Layanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
