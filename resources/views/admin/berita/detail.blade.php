@extends('layouts.public')

@section('title', $berita->judul)

@php
    // Query tambahan untuk sidebar & bagian bawah
    $beritaPopuler = \App\Models\Berita::where('status', 'published')
        ->where('id', '!=', $berita->id)
        ->orderByDesc('views')
        ->take(4)
        ->get();

    $beritaTerbaru = \App\Models\Berita::where('status', 'published')
        ->where('id', '!=', $berita->id)
        ->latest('published_at')
        ->take(4)
        ->get();

    $semuaKategori = \App\Models\Berita::where('status', 'published')
        ->select('kategori')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('kategori')
        ->get();

    // Navigasi berita sebelumnya & selanjutnya
    $beritaSebelumnya = \App\Models\Berita::where('status', 'published')
        ->where('published_at', '<', $berita->published_at)
        ->latest('published_at')
        ->first();

    $beritaSelanjutnya = \App\Models\Berita::where('status', 'published')
        ->where('published_at', '>', $berita->published_at)
        ->oldest('published_at')
        ->first();

    // Berita lainnya (full width di bawah)
    $beritaLainnya = \App\Models\Berita::where('status', 'published')
        ->where('id', '!=', $berita->id)
        ->when($beritaTerkait->isNotEmpty(), function ($q) use ($beritaTerkait) {
            $q->whereNotIn('id', $beritaTerkait->pluck('id'));
        })
        ->latest('published_at')
        ->take(3)
        ->get();
@endphp

@section('content')

    {{-- ── READING PROGRESS BAR ─────────────────────────────────────────────────── --}}
    <div id="reading-progress"
        style="position: fixed; top: 0; left: 0; height: 3px; background: linear-gradient(90deg, #2d8659, #10b981); z-index: 9999; width: 0%; transition: width 0.1s;">
    </div>

    {{-- ── BREADCRUMB ──────────────────────────────────────────────────────────── --}}
    <section
        style="background: linear-gradient(135deg, #f8faf9 0%, #e8f5ee 100%); padding: 1.25rem 0; border-bottom: 1px solid rgba(0,0,0,0.05);">
        <div class="container">
            <nav>
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">
                            <i class="bi bi-house-fill"></i> Beranda
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('berita') }}" class="text-decoration-none">Berita</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="text-muted">{{ ucfirst($berita->kategori) }}</span>
                    </li>
                    <li class="breadcrumb-item active">{{ Str::limit($berita->judul, 40) }}</li>
                </ol>
            </nav>
        </div>
    </section>

    {{-- ── HERO HEADER ──────────────────────────────────────────────────────────── --}}
    <section class="sid-berita-hero" style="position: relative; min-height: 400px; overflow: hidden;">
        @if ($berita->foto && file_exists(public_path('storage/' . $berita->foto)))
            <div style="position: absolute; inset: 0; background: url('{{ Storage::url($berita->foto) }}') center/cover;">
            </div>
            <div
                style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.85) 100%);">
            </div>
        @else
            <div
                style="position: absolute; inset: 0; background: linear-gradient(135deg, #1e6b44 0%, #2d8659 50%, #0891b2 100%);">
            </div>
        @endif

        <div class="container"
            style="position: relative; z-index: 2; padding-top: 5rem; padding-bottom: 3rem; min-height: 400px; display: flex; align-items: flex-end;">
            <div style="max-width: 900px; color: white;">
                <span
                    style="display: inline-block; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); color: white; padding: 6px 16px; border-radius: 99px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,0.3);">
                    <i class="bi bi-tag-fill me-1"></i>{{ $berita->kategori }}
                </span>

                <h1
                    style="font-family: 'Lora', serif; font-size: clamp(1.75rem, 4.5vw, 3rem); font-weight: 700; line-height: 1.2; margin-bottom: 1.25rem; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                    {{ $berita->judul }}
                </h1>

                <div class="d-flex flex-wrap align-items-center gap-3" style="font-size: 0.9rem; opacity: 0.95;">
                    <span class="d-flex align-items-center gap-2">
                        <div
                            style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; font-weight: 700;">
                            {{ strtoupper(substr($berita->penulis ?? 'A', 0, 1)) }}
                        </div>
                        <strong>{{ $berita->penulis ?? 'Admin Desa' }}</strong>
                    </span>
                    <span style="opacity: 0.5;">•</span>
                    <span>
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $berita->published_at ? $berita->published_at->isoFormat('D MMMM YYYY') : '-' }}
                    </span>
                    <span style="opacity: 0.5;">•</span>
                    <span>
                        <i class="bi bi-clock me-1"></i>
                        {{ ceil(str_word_count(strip_tags($berita->konten)) / 200) }} menit baca
                    </span>
                    <span style="opacity: 0.5;">•</span>
                    <span>
                        <i class="bi bi-eye me-1"></i>
                        {{ number_format($berita->views) }} views
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- ── KONTEN BERITA ────────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih" style="padding-top: 3rem;">
        <div class="container">
            <div class="row g-4">

                {{-- ═══════════════════════════════ KOLOM UTAMA ═══════════════════════════════ --}}
                <div class="col-lg-8">
                    <article>
                        {{-- Ringkasan --}}
                        @if ($berita->ringkasan)
                            <div class="mb-4 p-4"
                                style="background: linear-gradient(135deg, #f8faf9, #e8f5ee); border-left: 4px solid #2d8659; border-radius: 10px;">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="bi bi-quote" style="font-size: 2rem; color: #2d8659; line-height: 1;"></i>
                                    <p class="mb-0 fst-italic" style="font-size: 1.1rem; line-height: 1.7; color: #374151;">
                                        {{ $berita->ringkasan }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Konten Utama --}}
                        <div class="sid-berita-konten" style="font-size: 1.05rem; line-height: 1.9; color: #374151;">
                            {!! $berita->konten !!}
                        </div>

                        {{-- Tag Kategori --}}
                        <div class="mt-4 pt-3" style="border-top: 1px solid rgba(0,0,0,0.08);">
                            <span class="me-2 text-muted small">Kategori:</span>
                            <span class="sid-badge sid-badge-info">
                                <i class="bi bi-tag-fill me-1"></i>{{ ucfirst($berita->kategori) }}
                            </span>
                        </div>

                        {{-- Share Sosial Media --}}
                        <div class="mt-4 p-4" style="background: #f8faf9; border-radius: 12px;">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-share-fill me-1 text-success"></i>Bagikan Berita Ini
                            </h6>
                            @php
                                $shareUrl = urlencode(request()->url());
                                $shareTitle = urlencode($berita->judul);
                            @endphp
                            <div class="d-flex flex-wrap gap-2">
                                <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank"
                                    rel="noopener" class="btn btn-sm"
                                    style="background: #25D366; color: white; padding: 8px 16px;">
                                    <i class="bi bi-whatsapp me-1"></i>WhatsApp
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank"
                                    rel="noopener" class="btn btn-sm"
                                    style="background: #1877F2; color: white; padding: 8px 16px;">
                                    <i class="bi bi-facebook me-1"></i>Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                                    target="_blank" rel="noopener" class="btn btn-sm"
                                    style="background: #000000; color: white; padding: 8px 16px;">
                                    <i class="bi bi-twitter-x me-1"></i>Twitter
                                </a>
                                <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}"
                                    target="_blank" rel="noopener" class="btn btn-sm"
                                    style="background: #0088cc; color: white; padding: 8px 16px;">
                                    <i class="bi bi-telegram me-1"></i>Telegram
                                </a>
                                <button onclick="copyLink(this)" class="btn btn-sm btn-outline-secondary"
                                    style="padding: 8px 16px;">
                                    <i class="bi bi-link-45deg me-1"></i>Salin Link
                                </button>
                            </div>
                        </div>

                        {{-- Navigasi Prev/Next --}}
                        @if ($beritaSebelumnya || $beritaSelanjutnya)
                            <div class="row g-3 mt-4">
                                @if ($beritaSebelumnya)
                                    <div class="col-md-6">
                                        <a href="{{ route('berita.detail', $beritaSebelumnya->slug) }}"
                                            class="sid-nav-prev-next d-block p-3 text-decoration-none h-100">
                                            <small class="text-muted">
                                                <i class="bi bi-arrow-left me-1"></i>Berita Sebelumnya
                                            </small>
                                            <div class="fw-bold mt-1" style="color: #1a1a1a; line-height: 1.4;">
                                                {{ Str::limit($beritaSebelumnya->judul, 70) }}
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if ($beritaSelanjutnya)
                                    <div class="col-md-6 {{ !$beritaSebelumnya ? 'offset-md-6' : '' }}">
                                        <a href="{{ route('berita.detail', $beritaSelanjutnya->slug) }}"
                                            class="sid-nav-prev-next d-block p-3 text-decoration-none h-100 text-end">
                                            <small class="text-muted">
                                                Berita Selanjutnya<i class="bi bi-arrow-right ms-1"></i>
                                            </small>
                                            <div class="fw-bold mt-1" style="color: #1a1a1a; line-height: 1.4;">
                                                {{ Str::limit($beritaSelanjutnya->judul, 70) }}
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </article>
                </div>

                {{-- ═══════════════════════════════ SIDEBAR ═══════════════════════════════ --}}
                <div class="col-lg-4">
                    <div class="sid-sidebar-sticky">

                        {{-- Berita Terkait --}}
                        @if ($beritaTerkait->count() > 0)
                            <div class="sid-sidebar-card mb-4">
                                <h5 class="sid-sidebar-title">
                                    <i class="bi bi-collection-fill"></i>
                                    Berita Terkait
                                </h5>
                                <div class="sid-sidebar-body">
                                    @foreach ($beritaTerkait as $related)
                                        <a href="{{ route('berita.detail', $related->slug) }}" class="sid-sidebar-item">
                                            <div class="sid-sidebar-thumb">
                                                @if ($related->foto && file_exists(public_path('storage/' . $related->foto)))
                                                    <img src="{{ Storage::url($related->foto) }}"
                                                        alt="{{ $related->judul }}">
                                                @else
                                                    <div class="sid-sidebar-placeholder">
                                                        <i class="bi bi-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="sid-sidebar-info">
                                                <h6>{{ Str::limit($related->judul, 60) }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $related->published_at ? $related->published_at->isoFormat('D MMM YYYY') : '-' }}
                                                </small>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Berita Populer --}}
                        @if ($beritaPopuler->count() > 0)
                            <div class="sid-sidebar-card mb-4">
                                <h5 class="sid-sidebar-title">
                                    <i class="bi bi-fire"></i>
                                    Berita Populer
                                </h5>
                                <div class="sid-sidebar-body">
                                    @foreach ($beritaPopuler as $i => $popular)
                                        <a href="{{ route('berita.detail', $popular->slug) }}"
                                            class="sid-sidebar-item sid-sidebar-popular">
                                            <div class="sid-sidebar-rank rank-{{ $i + 1 }}">{{ $i + 1 }}
                                            </div>
                                            <div class="sid-sidebar-info">
                                                <h6>{{ Str::limit($popular->judul, 65) }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-eye me-1"></i>{{ number_format($popular->views) }}
                                                    views
                                                </small>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Berita Terbaru --}}
                        @if ($beritaTerbaru->count() > 0)
                            <div class="sid-sidebar-card mb-4">
                                <h5 class="sid-sidebar-title">
                                    <i class="bi bi-clock-history"></i>
                                    Berita Terbaru
                                </h5>
                                <div class="sid-sidebar-body">
                                    @foreach ($beritaTerbaru as $recent)
                                        <a href="{{ route('berita.detail', $recent->slug) }}" class="sid-sidebar-item">
                                            <div class="sid-sidebar-thumb">
                                                @if ($recent->foto && file_exists(public_path('storage/' . $recent->foto)))
                                                    <img src="{{ Storage::url($recent->foto) }}"
                                                        alt="{{ $recent->judul }}">
                                                @else
                                                    <div class="sid-sidebar-placeholder">
                                                        <i class="bi bi-newspaper"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="sid-sidebar-info">
                                                <h6>{{ Str::limit($recent->judul, 60) }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ $recent->published_at ? $recent->published_at->diffForHumans() : '-' }}
                                                </small>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Kategori --}}
                        @if ($semuaKategori->count() > 0)
                            <div class="sid-sidebar-card mb-4">
                                <h5 class="sid-sidebar-title">
                                    <i class="bi bi-tags-fill"></i>
                                    Kategori
                                </h5>
                                <div class="p-3">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($semuaKategori as $kat)
                                            <span
                                                class="sid-kategori-chip {{ $kat->kategori === $berita->kategori ? 'active' : '' }}">
                                                {{ ucfirst($kat->kategori) }}
                                                <small>({{ $kat->total }})</small>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Newsletter CTA --}}
                        <div class="sid-sidebar-cta mb-4">
                            <i class="bi bi-envelope-paper-heart-fill" style="font-size: 2.5rem; opacity: 0.9;"></i>
                            <h5 class="mt-2 mb-2">Dapatkan Berita Terbaru</h5>
                            <p class="small mb-3" style="opacity: 0.9;">
                                Jadilah yang pertama tahu tentang berita dan kegiatan desa.
                            </p>
                            <a href="{{ route('register') }}" class="btn btn-light btn-sm w-100 fw-bold">
                                <i class="bi bi-person-plus-fill me-1"></i>Daftar Sekarang
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── BERITA LAINNYA (Full Width) ──────────────────────────────────────────── --}}
    @if ($beritaLainnya->count() > 0)
        <section class="sid-section" style="background: linear-gradient(180deg, #ffffff 0%, #f8faf9 100%);">
            <div class="container">
                <div class="sid-section-header">
                    <h2 class="sid-section-title">Baca <span>Lainnya</span></h2>
                    <a href="{{ route('berita') }}" class="sid-lihat-semua">
                        Semua berita <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="row g-4">
                    @foreach ($beritaLainnya as $lainnya)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ route('berita.detail', $lainnya->slug) }}"
                                class="sid-berita-card text-decoration-none d-block h-100"
                                style="background: white; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.06); transition: all 0.3s; display: flex; flex-direction: column;">
                                <div
                                    style="position: relative; aspect-ratio: 16/10; overflow: hidden; background: #f5f5f5;">
                                    @if ($lainnya->foto && file_exists(public_path('storage/' . $lainnya->foto)))
                                        <img src="{{ Storage::url($lainnya->foto) }}" alt="{{ $lainnya->judul }}"
                                            style="width:100%; height:100%; object-fit:cover; transition: transform 0.4s;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100"
                                            style="background: linear-gradient(135deg, #e8f5ee, #d4ebe0); color: #2d8659;">
                                            <i class="bi bi-newspaper" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                    <span
                                        style="position: absolute; top: 12px; left: 12px; background: rgba(45, 134, 89, 0.95); color: white; padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                        {{ $lainnya->kategori }}
                                    </span>
                                </div>
                                <div style="padding: 1.25rem; flex-grow: 1; display: flex; flex-direction: column;">
                                    <h3
                                        style="font-size: 1.05rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.5rem; line-height: 1.4;">
                                        {{ Str::limit($lainnya->judul, 70) }}
                                    </h3>
                                    <p class="text-muted small mb-3" style="line-height: 1.6; flex-grow: 1;">
                                        {{ Str::limit(strip_tags($lainnya->ringkasan ?? $lainnya->konten), 100) }}
                                    </p>
                                    <div class="d-flex flex-wrap align-items-center gap-2 small text-muted mt-auto">
                                        <span><i
                                                class="bi bi-calendar3 me-1"></i>{{ $lainnya->published_at ? $lainnya->published_at->isoFormat('D MMM YYYY') : '-' }}</span>
                                        <span class="sid-meta-dot"></span>
                                        <span><i class="bi bi-eye me-1"></i>{{ number_format($lainnya->views) }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── BACK TO TOP BUTTON ───────────────────────────────────────────────────── --}}
    <button id="btn-back-to-top" aria-label="Kembali ke atas"
        style="position: fixed; bottom: 30px; right: 30px; width: 48px; height: 48px; border-radius: 50%; background: #2d8659; color: white; border: none; box-shadow: 0 4px 15px rgba(45, 134, 89, 0.4); cursor: pointer; display: none; align-items: center; justify-content: center; z-index: 1000; transition: all 0.3s;">
        <i class="bi bi-arrow-up" style="font-size: 1.3rem;"></i>
    </button>

@endsection

@push('styles')
    <style>
        /* ─── Konten Artikel ────────────────────────────────────────────────── */
        .sid-berita-konten p {
            margin-bottom: 1.25rem;
        }

        .sid-berita-konten h2,
        .sid-berita-konten h3,
        .sid-berita-konten h4 {
            font-family: 'Lora', serif;
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #1a1a1a;
            font-weight: 700;
        }

        .sid-berita-konten h2 {
            font-size: 1.6rem;
        }

        .sid-berita-konten h3 {
            font-size: 1.35rem;
        }

        .sid-berita-konten ul,
        .sid-berita-konten ol {
            padding-left: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .sid-berita-konten li {
            margin-bottom: 0.5rem;
        }

        .sid-berita-konten blockquote {
            border-left: 4px solid #2d8659;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            background: #f8faf9;
            color: #374151;
            font-style: italic;
            border-radius: 0 8px 8px 0;
        }

        .sid-berita-konten img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 1.5rem 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .sid-berita-konten a {
            color: #2d8659;
            text-decoration: underline;
        }

        .sid-berita-konten a:hover {
            color: #1e6b44;
        }

        /* ─── Sidebar Sticky ────────────────────────────────────────────────── */
        @media (min-width: 992px) {
            .sid-sidebar-sticky {
                position: sticky;
                top: 90px;
            }
        }

        /* ─── Sidebar Card ──────────────────────────────────────────────────── */
        .sid-sidebar-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .sid-sidebar-title {
            margin: 0;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a1a;
            background: linear-gradient(135deg, #f8faf9, #ffffff);
            border-bottom: 2px solid #2d8659;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sid-sidebar-title i {
            color: #2d8659;
        }

        .sid-sidebar-body {
            padding: 0.5rem 0;
        }

        /* ─── Sidebar Item ──────────────────────────────────────────────────── */
        .sid-sidebar-item {
            display: flex;
            gap: 12px;
            padding: 0.85rem 1.25rem;
            color: inherit;
            text-decoration: none !important;
            transition: background 0.2s;
            align-items: flex-start;
        }

        .sid-sidebar-item:hover {
            background: #f8faf9;
        }

        .sid-sidebar-item:not(:last-child) {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .sid-sidebar-thumb {
            flex-shrink: 0;
            width: 70px;
            height: 70px;
            border-radius: 8px;
            overflow: hidden;
            background: #f5f5f5;
        }

        .sid-sidebar-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sid-sidebar-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            background: linear-gradient(135deg, #e8f5ee, #d4ebe0);
            color: #2d8659;
            font-size: 1.5rem;
        }

        .sid-sidebar-info {
            flex-grow: 1;
            min-width: 0;
        }

        .sid-sidebar-info h6 {
            font-size: 0.88rem;
            color: #1a1a1a;
            margin-bottom: 0.25rem;
            line-height: 1.4;
            font-weight: 600;
        }

        .sid-sidebar-item:hover .sid-sidebar-info h6 {
            color: #2d8659;
        }

        /* ─── Popular Rank ──────────────────────────────────────────────────── */
        .sid-sidebar-rank {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            font-family: 'Lora', serif;
            background: linear-gradient(135deg, #6b7280, #9ca3af);
        }

        .sid-sidebar-rank.rank-1 {
            background: linear-gradient(135deg, #dc2626, #ef4444);
        }

        .sid-sidebar-rank.rank-2 {
            background: linear-gradient(135deg, #d97706, #f59e0b);
        }

        .sid-sidebar-rank.rank-3 {
            background: linear-gradient(135deg, #65a30d, #84cc16);
        }

        /* ─── Kategori Chip ─────────────────────────────────────────────────── */
        .sid-kategori-chip {
            display: inline-block;
            padding: 6px 14px;
            background: #f1f5f4;
            color: #374151;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sid-kategori-chip:hover {
            background: #e8f5ee;
            color: #2d8659;
        }

        .sid-kategori-chip.active {
            background: #2d8659;
            color: white;
        }

        .sid-kategori-chip small {
            opacity: 0.7;
            margin-left: 2px;
        }

        /* ─── Newsletter CTA ────────────────────────────────────────────────── */
        .sid-sidebar-cta {
            background: linear-gradient(135deg, #2d8659 0%, #1e6b44 100%);
            color: white;
            padding: 1.75rem 1.5rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(45, 134, 89, 0.25);
        }

        /* ─── Berita Card Hover ─────────────────────────────────────────────── */
        .sid-berita-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12) !important;
        }

        .sid-berita-card:hover img {
            transform: scale(1.05);
        }

        /* ─── Prev/Next Navigation ──────────────────────────────────────────── */
        .sid-nav-prev-next {
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: all 0.2s;
        }

        .sid-nav-prev-next:hover {
            border-color: #2d8659;
            background: #f8faf9;
            transform: translateY(-2px);
        }

        /* ─── Back to Top Button ────────────────────────────────────────────── */
        #btn-back-to-top:hover {
            transform: scale(1.1);
            background: #1e6b44;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Reading Progress Bar + Back to Top
        window.addEventListener('scroll', function() {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            document.getElementById('reading-progress').style.width = progress + '%';

            const btnTop = document.getElementById('btn-back-to-top');
            if (btnTop) {
                btnTop.style.display = scrollTop > 500 ? 'flex' : 'none';
            }
        });

        // Back to top click
        document.getElementById('btn-back-to-top').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Copy link function
        function copyLink(btn) {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check2"></i> Tersalin!';
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-success');
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-secondary');
                }, 2000);
            });
        }
    </script>
@endpush
