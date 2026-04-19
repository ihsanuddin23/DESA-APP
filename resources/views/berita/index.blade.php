@extends('layouts.public')

@section('title', 'Berita')

@section('content')

    {{-- ── HERO SECTION ─────────────────────────────────────────────────────────── --}}
    <section class="sid-hero">
        <div class="container">
            <div class="sid-hero-inner text-center">
                <span class="sid-hero-badge">
                    <i class="bi bi-newspaper me-1"></i>
                    Informasi Terkini
                </span>
                <h1 class="sid-hero-title">
                    Berita <em>Desa</em>
                </h1>
                <p class="sid-hero-lead">
                    Kabar terbaru, kegiatan, dan kisah inspiratif dari warga
                    {{ config('sid.nama_desa') }}.
                </p>
            </div>
        </div>
    </section>

    {{-- ── DAFTAR BERITA ────────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">

            @if ($beritaList->count() > 0)

                {{-- Filter kategori (summary) --}}
                @php
                    $kategoriList = $beritaList->pluck('kategori')->unique()->filter();
                @endphp

                @if ($kategoriList->count() > 1)
                    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
                        @foreach ($kategoriList as $kategori)
                            <span class="sid-badge sid-badge-info">
                                <i class="bi bi-tag-fill me-1"></i>{{ ucfirst($kategori) }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="row g-4">
                    @foreach ($beritaList as $berita)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ route('berita.detail', $berita->slug) }}"
                                class="sid-berita-card text-decoration-none d-block h-100"
                                style="background: white; border-radius: 14px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.06); transition: all 0.3s; display: flex; flex-direction: column;">

                                {{-- Gambar --}}
                                <div
                                    style="position: relative; aspect-ratio: 16/10; overflow: hidden; background: #f5f5f5;">
                                    @if ($berita->foto && file_exists(public_path('storage/' . $berita->foto)))
                                        <img src="{{ Storage::url($berita->foto) }}" alt="{{ $berita->judul }}"
                                            style="width:100%; height:100%; object-fit:cover; transition: transform 0.4s;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100"
                                            style="background: linear-gradient(135deg, #e8f5ee, #d4ebe0); color: #2d8659;">
                                            <i class="bi bi-newspaper" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif

                                    {{-- Tag kategori --}}
                                    <span
                                        style="position: absolute; top: 12px; left: 12px; background: rgba(45, 134, 89, 0.95); color: white; padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                        {{ $berita->kategori }}
                                    </span>
                                </div>

                                {{-- Body --}}
                                <div style="padding: 1.25rem; flex-grow: 1; display: flex; flex-direction: column;">
                                    <h3
                                        style="font-size: 1.1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 0.5rem; line-height: 1.4;">
                                        {{ Str::limit($berita->judul, 80) }}
                                    </h3>

                                    <p class="text-muted small mb-3" style="line-height: 1.6; flex-grow: 1;">
                                        {{ Str::limit(strip_tags($berita->ringkasan ?? $berita->konten), 120) }}
                                    </p>

                                    {{-- Meta --}}
                                    <div class="d-flex flex-wrap align-items-center gap-2 small text-muted mt-auto">
                                        <span>
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $berita->published_at ? $berita->published_at->isoFormat('D MMM YYYY') : '-' }}
                                        </span>
                                        <span class="sid-meta-dot"></span>
                                        <span>
                                            <i class="bi bi-person me-1"></i>
                                            {{ $berita->penulis ?? 'Admin Desa' }}
                                        </span>
                                        @if ($berita->views > 0)
                                            <span class="sid-meta-dot"></span>
                                            <span>
                                                <i class="bi bi-eye me-1"></i>{{ number_format($berita->views) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($beritaList->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $beritaList->links() }}
                    </div>
                @endif
            @else
                {{-- Empty state --}}
                <div class="text-center py-5">
                    <i class="bi bi-newspaper" style="font-size: 5rem; color: #cbd5e1;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Berita</h4>
                    <p class="text-muted">Berita desa akan segera dipublikasikan. Silakan kunjungi halaman ini kembali
                        nanti.</p>
                </div>

            @endif
        </div>
    </section>

@endsection

@push('styles')
    <style>
        .sid-berita-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12) !important;
        }

        .sid-berita-card:hover img {
            transform: scale(1.05);
        }
    </style>
@endpush
