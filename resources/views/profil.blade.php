@extends('layouts.public')

@section('title', 'Profil Desa')

@push('styles')
    <style>
        /* ── Reuse variabel warna SID ───────────────────────────────────────────── */
        :root {
            --sid-green: #2d8659;
            --sid-green-dark: #1e5f3d;
            --sid-text: #1a1a1a;
            --sid-muted: #64748b;
            --sid-surface: #f8fafc;
            --sid-border: #f1f5f9;
            --sid-shadow-sm: 0 2px 10px rgba(0, 0, 0, .06);
            --sid-shadow-md: 0 8px 25px rgba(0, 0, 0, .12);
            --sid-radius: 14px;
        }

        .logo-profil {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: white;
            padding: 10px;
            object-fit: cover;
            margin-bottom: 1.25rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .15);
        }

        /* ── Section wrapper — identik dengan .sid-section ──────────────────────── */
        .section-profil {
            padding: 3.5rem 0;
            background: white;
        }

        .section-profil.alt {
            background: var(--sid-surface);
        }

        /* ── Section heading — identik dengan pola di halaman lain ─────────────── */
        .section-title {
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 2rem;
            color: var(--sid-text);
            margin-bottom: .4rem;
            text-align: center;
        }

        .section-title em {
            color: var(--sid-green);
            font-style: normal;
        }

        .section-sub {
            color: var(--sid-muted);
            font-size: 1rem;
            text-align: center;
            margin-bottom: 2.5rem;
        }

        /* ── Card generik — sama dengan sid-berita-card / sid-pengumuman-card ───── */
        .profil-card {
            background: white;
            border-radius: var(--sid-radius);
            padding: 2rem;
            box-shadow: var(--sid-shadow-sm);
            height: 100%;
            border-left: 4px solid var(--sid-green);
            transition: transform .3s, box-shadow .3s;
        }

        .profil-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--sid-shadow-md);
        }

        .profil-card .card-icon-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.25rem;
        }

        .profil-card .card-icon-header i {
            font-size: 1.75rem;
            color: var(--sid-green);
        }

        .profil-card h3 {
            font-family: 'Lora', serif;
            font-weight: 700;
            color: var(--sid-text);
            font-size: 1.25rem;
            margin: 0;
        }

        .profil-card .card-body-text {
            color: #475569;
            line-height: 1.85;
            font-size: .95rem;
            white-space: pre-line;
        }

        /* ── Statistik — sama hover dengan sid-berita-card ──────────────────────── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .stat-box {
            background: white;
            border-radius: var(--sid-radius);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--sid-shadow-sm);
            transition: transform .3s, box-shadow .3s;
        }

        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: var(--sid-shadow-md);
        }

        .stat-box i {
            font-size: 2rem;
            color: var(--sid-green);
            margin-bottom: .5rem;
        }

        .stat-box .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--sid-text);
            font-family: 'JetBrains Mono', monospace;
            line-height: 1;
        }

        .stat-box .stat-label {
            font-size: .78rem;
            color: var(--sid-muted);
            margin-top: .4rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 600;
        }

        /* ── Struktur pemerintahan ───────────────────────────────────────────────── */
        .struktur-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
            gap: 1.25rem;
        }

        .struktur-card {
            background: white;
            border-radius: var(--sid-radius);
            padding: 1.5rem 1.25rem;
            text-align: center;
            box-shadow: var(--sid-shadow-sm);
            transition: transform .3s, box-shadow .3s;
        }

        .struktur-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--sid-shadow-md);
        }

        .struktur-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--sid-green), var(--sid-green-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.25rem;
            font-weight: 700;
            margin: 0 auto 1rem;
            overflow: hidden;
        }

        .struktur-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .struktur-card h5 {
            font-weight: 700;
            color: var(--sid-text);
            font-size: 1rem;
            margin-bottom: .2rem;
        }

        /* badge jabatan — sama dengan sid-badge di pengumuman */
        .struktur-card .jabatan {
            display: inline-block;
            background: rgba(45, 134, 89, .12);
            color: var(--sid-green);
            font-size: .75rem;
            font-weight: 700;
            padding: 3px 11px;
            border-radius: 99px;
            margin-bottom: .5rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .struktur-card .kontak {
            font-size: .75rem;
            color: #94a3b8;
        }

        /* ── Kantor & kontak ─────────────────────────────────────────────────────── */
        .kantor-wrap {
            background: white;
            border-radius: var(--sid-radius);
            overflow: hidden;
            box-shadow: var(--sid-shadow-sm);
            transition: box-shadow .3s;
        }

        .kantor-wrap:hover {
            box-shadow: var(--sid-shadow-md);
        }

        .kantor-foto {
            width: 100%;
            height: 340px;
            object-fit: cover;
        }

        .kantor-info {
            padding: 2rem;
        }

        .kantor-info h3 {
            font-family: 'Lora', serif;
            font-weight: 700;
            color: var(--sid-text);
            margin-bottom: 1rem;
        }

        .info-row {
            display: flex;
            gap: 1rem;
            padding: .7rem 0;
            border-bottom: 1px solid var(--sid-border);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row i {
            font-size: 1.1rem;
            color: var(--sid-green);
            flex-shrink: 0;
            width: 20px;
            margin-top: 2px;
        }

        .info-row .info-text {
            color: #334155;
            font-size: .92rem;
            line-height: 1.5;
        }

        .info-row .info-text strong {
            color: var(--sid-text);
        }

        .info-row .info-text a {
            color: var(--sid-green);
            text-decoration: none;
        }

        .info-row .info-text a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')

    {{-- ══ HERO ════════════════════════════════════════════════════════════════════ --}}
    <section class="sid-hero">
        <div class="container">
            <div class="sid-hero-inner text-center">
                @if ($profil->logo)
                    <img src="{{ Storage::url($profil->logo) }}" class="logo-profil" alt="Logo {{ $profil->nama_desa }}">
                @endif

                <span class="sid-hero-badge">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Mengenal Lebih Dekat
                </span>

                <h1 class="sid-hero-title">
                    Profil <em>Desa</em>
                </h1>

                <p class="sid-hero-lead">
                    Sejarah, visi misi, dan gambaran umum wilayah
                    {{ $profil->nama_desa }}{{ $profil->kepala_desa ? ' di bawah kepemimpinan ' . $profil->kepala_desa : '' }}.
                </p>
            </div>
        </div>
    </section>

    {{-- ══ VISI & MISI ═════════════════════════════════════════════════════════════ --}}
    <section class="section-profil">
        <div class="container">
            <h2 class="section-title">Visi &amp; <em>Misi</em></h2>
            <p class="section-sub">Arah dan tujuan pembangunan desa</p>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="profil-card">
                        <div class="card-icon-header">
                            <i class="bi bi-eye-fill"></i>
                            <h3>Visi</h3>
                        </div>
                        <div class="card-body-text">{{ $profil->visi ?? 'Visi belum diisi.' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="profil-card">
                        <div class="card-icon-header">
                            <i class="bi bi-bullseye"></i>
                            <h3>Misi</h3>
                        </div>
                        <div class="card-body-text">{{ $profil->misi ?? 'Misi belum diisi.' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ SEJARAH ══════════════════════════════════════════════════════════════════ --}}
    @if ($profil->sejarah)
        <section class="section-profil alt">
            <div class="container">
                <h2 class="section-title">Sejarah <em>Desa</em></h2>
                <p class="section-sub">Jejak perjalanan {{ $profil->nama_desa }}</p>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="profil-card">
                            <div class="card-icon-header">
                                <i class="bi bi-book-fill"></i>
                                <h3>Asal Usul &amp; Perkembangan</h3>
                            </div>
                            <div class="card-body-text">{{ $profil->sejarah }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ══ STATISTIK WILAYAH ════════════════════════════════════════════════════════ --}}
    <section class="section-profil">
        <div class="container">
            <h2 class="section-title"><em>Data</em> Wilayah &amp; Penduduk</h2>
            <p class="section-sub">Gambaran umum desa dalam angka</p>

            <div class="stat-grid">
                <div class="stat-box">
                    <i class="bi bi-people-fill"></i>
                    <div class="stat-value">{{ number_format($statistik['total_penduduk']) }}</div>
                    <div class="stat-label">Jiwa</div>
                </div>
                <div class="stat-box">
                    <i class="bi bi-house-fill"></i>
                    <div class="stat-value">{{ number_format($statistik['total_kk']) }}</div>
                    <div class="stat-label">Kepala Keluarga</div>
                </div>
                <div class="stat-box">
                    <i class="bi bi-gender-male"></i>
                    <div class="stat-value">{{ number_format($statistik['laki_laki']) }}</div>
                    <div class="stat-label">Laki-laki</div>
                </div>
                <div class="stat-box">
                    <i class="bi bi-gender-female"></i>
                    <div class="stat-value">{{ number_format($statistik['perempuan']) }}</div>
                    <div class="stat-label">Perempuan</div>
                </div>
                @if ($profil->luas_wilayah_km2)
                    <div class="stat-box">
                        <i class="bi bi-map-fill"></i>
                        <div class="stat-value">{{ number_format($profil->luas_wilayah_km2) }}</div>
                        <div class="stat-label">km² Luas</div>
                    </div>
                @endif
                @if ($profil->jumlah_rt)
                    <div class="stat-box">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        <div class="stat-value">{{ $profil->jumlah_rt }}</div>
                        <div class="stat-label">RT</div>
                    </div>
                @endif
                @if ($profil->jumlah_rw)
                    <div class="stat-box">
                        <i class="bi bi-building"></i>
                        <div class="stat-value">{{ $profil->jumlah_rw }}</div>
                        <div class="stat-label">RW</div>
                    </div>
                @endif
                @if ($profil->jumlah_dusun)
                    <div class="stat-box">
                        <i class="bi bi-house-door-fill"></i>
                        <div class="stat-value">{{ $profil->jumlah_dusun }}</div>
                        <div class="stat-label">Dusun</div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ══ GEOGRAFIS & DEMOGRAFI ════════════════════════════════════════════════════ --}}
    @if ($profil->geografis || $profil->demografi)
        <section class="section-profil alt">
            <div class="container">
                <h2 class="section-title">Kondisi <em>Wilayah</em></h2>
                <p class="section-sub">Geografi dan demografi {{ $profil->nama_desa }}</p>

                <div class="row g-4">
                    @if ($profil->geografis)
                        <div class="{{ $profil->demografi ? 'col-lg-6' : 'col-12' }}">
                            <div class="profil-card">
                                <div class="card-icon-header">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <h3>Kondisi Geografis</h3>
                                </div>
                                <div class="card-body-text">{{ $profil->geografis }}</div>
                            </div>
                        </div>
                    @endif
                    @if ($profil->demografi)
                        <div class="{{ $profil->geografis ? 'col-lg-6' : 'col-12' }}">
                            <div class="profil-card">
                                <div class="card-icon-header">
                                    <i class="bi bi-diagram-3-fill"></i>
                                    <h3>Gambaran Demografi</h3>
                                </div>
                                <div class="card-body-text">{{ $profil->demografi }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ══ STRUKTUR PEMERINTAHAN ════════════════════════════════════════════════════ --}}
    @if ($strukturDesa->count() > 0)
        <section class="section-profil">
            <div class="container">
                <h2 class="section-title">Struktur <em>Pemerintahan</em></h2>
                <p class="section-sub">Perangkat desa yang bertugas melayani masyarakat</p>

                <div class="struktur-grid">
                    @foreach ($strukturDesa as $struktur)
                        <div class="struktur-card">
                            <div class="struktur-avatar">
                                @if ($struktur->foto)
                                    <img src="{{ Storage::url($struktur->foto) }}" alt="{{ $struktur->nama }}">
                                @else
                                    {{ strtoupper(substr($struktur->nama, 0, 1)) }}
                                @endif
                            </div>
                            <h5>{{ $struktur->nama }}</h5>
                            <div class="jabatan">{{ $struktur->jabatan }}</div>
                            @if ($struktur->telepon)
                                <div class="kontak">
                                    <i class="bi bi-telephone me-1"></i>{{ $struktur->telepon }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══ KANTOR & KONTAK ══════════════════════════════════════════════════════════ --}}
    <section class="section-profil alt">
        <div class="container">
            <h2 class="section-title"><em>Kantor</em> Desa &amp; Kontak</h2>
            <p class="section-sub">Informasi untuk menghubungi kami</p>

            <div class="kantor-wrap">
                @if ($profil->foto_kantor)
                    <img src="{{ Storage::url($profil->foto_kantor) }}" class="kantor-foto" alt="Kantor Desa">
                @endif

                <div class="kantor-info">
                    <h3>{{ $profil->nama_desa }}</h3>

                    @if ($profil->alamat_kantor)
                        <div class="info-row">
                            <i class="bi bi-geo-alt-fill"></i>
                            <div class="info-text">
                                <strong>Alamat:</strong> {{ $profil->alamat_kantor }}
                            </div>
                        </div>
                    @endif

                    @if ($profil->telepon)
                        <div class="info-row">
                            <i class="bi bi-telephone-fill"></i>
                            <div class="info-text">
                                <strong>Telepon:</strong> {{ $profil->telepon }}
                            </div>
                        </div>
                    @endif

                    @if ($profil->email)
                        <div class="info-row">
                            <i class="bi bi-envelope-fill"></i>
                            <div class="info-text">
                                <strong>Email:</strong>
                                <a href="mailto:{{ $profil->email }}">{{ $profil->email }}</a>
                            </div>
                        </div>
                    @endif

                    @if ($profil->jam_pelayanan)
                        <div class="info-row">
                            <i class="bi bi-clock-fill"></i>
                            <div class="info-text">
                                <strong>Jam Pelayanan:</strong> {{ $profil->jam_pelayanan }}
                            </div>
                        </div>
                    @endif

                    @if ($profil->kepala_desa)
                        <div class="info-row">
                            <i class="bi bi-person-badge-fill"></i>
                            <div class="info-text">
                                <strong>Kepala Desa:</strong> {{ $profil->kepala_desa }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
