@extends('layouts.public')

@section('title', 'Profil Desa')

@push('styles')
    <style>
        .profil-hero {
            background: linear-gradient(135deg, #2d8659 0%, #1e5f3d 100%);
            color: white;
            padding: 4rem 0 5rem;
            position: relative;
            overflow: hidden;
        }

        .profil-hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, .06), transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .profil-hero h1 {
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: .75rem;
        }

        .profil-hero .lead {
            opacity: .92;
            font-size: 1.05rem;
            max-width: 680px;
            margin: 0 auto;
        }

        .profil-hero .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255, 255, 255, .15);
            padding: .5rem 1rem;
            border-radius: 99px;
            font-size: .82rem;
            font-weight: 500;
            margin-bottom: 1rem;
            backdrop-filter: blur(8px);
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

        .section-profil {
            padding: 3.5rem 0;
            background: white;
        }

        .section-profil.alt {
            background: #f8fafc;
        }

        .section-title {
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 2rem;
            color: #0f172a;
            margin-bottom: .4rem;
            text-align: center;
        }

        .section-title em {
            color: #2d8659;
            font-style: normal;
        }

        .section-sub {
            color: #64748b;
            font-size: 1rem;
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .visi-card,
        .misi-card,
        .info-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 1px 10px rgba(15, 23, 42, .05);
            height: 100%;
            border-left: 4px solid #2d8659;
        }

        .visi-card .icon-header,
        .misi-card .icon-header,
        .info-card .icon-header {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.25rem;
        }

        .visi-card .icon-header i,
        .misi-card .icon-header i,
        .info-card .icon-header i {
            font-size: 1.75rem;
            color: #2d8659;
        }

        .visi-card h3,
        .misi-card h3,
        .info-card h3 {
            font-family: 'Lora', serif;
            font-weight: 700;
            color: #0f172a;
            font-size: 1.35rem;
            margin: 0;
        }

        .visi-card .content,
        .misi-card .content,
        .info-card .content {
            color: #475569;
            line-height: 1.85;
            font-size: .95rem;
            white-space: pre-line;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .stat-box {
            background: white;
            border-radius: .85rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            transition: transform .2s, box-shadow .2s;
        }

        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(45, 134, 89, .15);
        }

        .stat-box i {
            font-size: 2rem;
            color: #2d8659;
            margin-bottom: .5rem;
        }

        .stat-box .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            font-family: 'JetBrains Mono', monospace;
            line-height: 1;
        }

        .stat-box .stat-label {
            font-size: .8rem;
            color: #64748b;
            margin-top: .4rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 500;
        }

        .kantor-wrap {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(15, 23, 42, .08);
        }

        .kantor-foto {
            width: 100%;
            height: 360px;
            object-fit: cover;
        }

        .kantor-info {
            padding: 2rem;
        }

        .kantor-info h3 {
            font-family: 'Lora', serif;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
        }

        .info-row {
            display: flex;
            gap: 1rem;
            padding: .7rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row i {
            font-size: 1.1rem;
            color: #2d8659;
            flex-shrink: 0;
            width: 20px;
        }

        .info-row .info-text {
            color: #334155;
            font-size: .92rem;
            line-height: 1.5;
        }

        .info-row .info-text strong {
            color: #0f172a;
        }

        .struktur-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.25rem;
        }

        .struktur-card {
            background: white;
            border-radius: .85rem;
            padding: 1.5rem 1.25rem;
            text-align: center;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            transition: transform .2s;
        }

        .struktur-card:hover {
            transform: translateY(-3px);
        }

        .struktur-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2d8659, #1e5f3d);
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
            color: #0f172a;
            font-size: 1rem;
            margin-bottom: .2rem;
        }

        .struktur-card .jabatan {
            color: #2d8659;
            font-size: .82rem;
            font-weight: 500;
            margin-bottom: .5rem;
        }

        .struktur-card .kontak {
            font-size: .75rem;
            color: #94a3b8;
        }

        .sejarah-content {
            color: #334155;
            line-height: 1.95;
            font-size: 1rem;
            white-space: pre-line;
        }
    </style>
@endpush

@section('content')

    {{-- ═══════ HERO ═══════ --}}
    <section class="profil-hero">
        <div class="container" style="position:relative;z-index:1;text-align:center;">
            @if ($profil->logo)
                <img src="{{ Storage::url($profil->logo) }}" class="logo-profil" alt="Logo {{ $profil->nama_desa }}">
            @endif

            <div class="hero-badge">
                <i class="bi bi-info-circle-fill"></i>
                Mengenal Lebih Dekat
            </div>
            <h1>Profil {{ $profil->nama_desa }}</h1>
            <p class="lead">
                Sejarah, visi misi, dan gambaran umum wilayah
                {{ $profil->nama_desa }}{{ $profil->kepala_desa ? ' di bawah kepemimpinan ' . $profil->kepala_desa : '' }}.
            </p>
        </div>
    </section>

    {{-- ═══════ VISI & MISI ═══════ --}}
    <section class="section-profil">
        <div class="container">
            <h2 class="section-title">Visi &amp; <em>Misi</em></h2>
            <p class="section-sub">Arah dan tujuan pembangunan desa</p>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="visi-card">
                        <div class="icon-header">
                            <i class="bi bi-eye-fill"></i>
                            <h3>Visi</h3>
                        </div>
                        <div class="content">{{ $profil->visi ?? 'Visi belum diisi.' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="misi-card">
                        <div class="icon-header">
                            <i class="bi bi-bullseye"></i>
                            <h3>Misi</h3>
                        </div>
                        <div class="content">{{ $profil->misi ?? 'Misi belum diisi.' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════ SEJARAH ═══════ --}}
    @if ($profil->sejarah)
        <section class="section-profil alt">
            <div class="container">
                <h2 class="section-title">Sejarah <em>Desa</em></h2>
                <p class="section-sub">Jejak perjalanan {{ $profil->nama_desa }}</p>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="info-card">
                            <div class="icon-header">
                                <i class="bi bi-book-fill"></i>
                                <h3>Asal Usul &amp; Perkembangan</h3>
                            </div>
                            <div class="sejarah-content">{{ $profil->sejarah }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════ STATISTIK WILAYAH ═══════ --}}
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

    {{-- ═══════ GEOGRAFIS & DEMOGRAFI ═══════ --}}
    @if ($profil->geografis || $profil->demografi)
        <section class="section-profil alt">
            <div class="container">
                <div class="row g-4">
                    @if ($profil->geografis)
                        <div class="col-lg-6">
                            <div class="info-card">
                                <div class="icon-header">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <h3>Kondisi Geografis</h3>
                                </div>
                                <div class="sejarah-content">{{ $profil->geografis }}</div>
                            </div>
                        </div>
                    @endif
                    @if ($profil->demografi)
                        <div class="col-lg-6">
                            <div class="info-card">
                                <div class="icon-header">
                                    <i class="bi bi-diagram-3-fill"></i>
                                    <h3>Gambaran Demografi</h3>
                                </div>
                                <div class="sejarah-content">{{ $profil->demografi }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════ STRUKTUR PEMERINTAHAN ═══════ --}}
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

    {{-- ═══════ KANTOR & KONTAK ═══════ --}}
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
                                <a href="mailto:{{ $profil->email }}" style="color:#2d8659;">{{ $profil->email }}</a>
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
