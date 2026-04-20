@extends('layouts.public')

@section('title', 'Galeri Foto')

@push('styles')
    <style>
        /* ── Main content area ───────────────────────────────────────────────── */
        .galeri-section {
            padding: 2.5rem 0 3.5rem;
        }

        .galeri-count-label {
            font-size: .85rem;
            color: #64748b;
            margin-bottom: 1.25rem;
        }

        .galeri-count-label strong {
            color: #1a1a1a;
        }

        /* ── Filter bar ──────────────────────────────────────────────────────── */
        .galeri-filter-wrap {
            background: #fff;
            border-bottom: 1px solid #e9eef5;
            padding: .9rem 0;
            position: sticky;
            top: 60px;
            z-index: 100;
        }

        .galeri-filter-inner {
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-wrap: wrap;
        }

        .galeri-search-wrap {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .galeri-search-wrap i {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: .9rem;
            pointer-events: none;
        }

        .galeri-search-input {
            width: 100%;
            padding: .5rem .75rem .5rem 2.1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: .5rem;
            font-size: .88rem;
            outline: none;
            transition: border .2s;
            background: #f8fafc;
        }

        .galeri-search-input:focus {
            border-color: #2d8659;
            background: #fff;
        }

        .galeri-sort-select {
            padding: .5rem .75rem;
            border: 1.5px solid #e2e8f0;
            border-radius: .5rem;
            font-size: .88rem;
            background: #f8fafc;
            outline: none;
            cursor: pointer;
            color: #374151;
        }

        .galeri-sort-select:focus {
            border-color: #2d8659;
        }

        .galeri-btn-filter {
            padding: .5rem 1rem;
            background: #2d8659;
            color: #fff;
            border: none;
            border-radius: .5rem;
            font-size: .85rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            transition: background .15s;
        }

        .galeri-btn-filter:hover {
            background: #1e5f3d;
        }

        .galeri-reset-link {
            font-size: .82rem;
            color: #94a3b8;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .2rem;
            white-space: nowrap;
        }

        .galeri-reset-link:hover {
            color: #dc3545;
        }

        /* ── Photo grid ──────────────────────────────────────────────────────── */
        .galeri-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.1rem;
        }

        .galeri-item {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            background: #e2e8f0;
            aspect-ratio: 4/3;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            transition: transform .3s, box-shadow .3s;
        }

        .galeri-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, .12);
        }

        .galeri-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .4s ease;
        }

        .galeri-item:hover img {
            transform: scale(1.05);
        }

        /* Overlay caption */
        .galeri-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, .82) 0%, transparent 55%);
            opacity: 0;
            transition: opacity .25s;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1rem;
            pointer-events: none;
        }

        .galeri-item:hover .galeri-overlay {
            opacity: 1;
        }

        .galeri-overlay-title {
            color: #fff;
            font-size: .88rem;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: .2rem;
        }

        .galeri-overlay-keterangan {
            color: rgba(255, 255, 255, .75);
            font-size: .76rem;
            line-height: 1.4;
            overflow: hidden;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .galeri-overlay-icon {
            position: absolute;
            top: .7rem;
            right: .7rem;
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, .2);
            backdrop-filter: blur(4px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .85rem;
            opacity: 0;
            transition: opacity .25s;
        }

        .galeri-item:hover .galeri-overlay-icon {
            opacity: 1;
        }

        .galeri-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            gap: .5rem;
        }

        .galeri-placeholder i {
            font-size: 2.2rem;
        }

        .galeri-placeholder span {
            font-size: .78rem;
        }

        /* ── Empty state ─────────────────────────────────────────────────────── */
        .galeri-empty {
            text-align: center;
            padding: 4rem 1rem;
            color: #64748b;
        }

        .galeri-empty i {
            font-size: 5rem;
            color: #cbd5e1;
            display: block;
            margin-bottom: .9rem;
        }

        .galeri-empty h5 {
            font-size: 1.05rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: .35rem;
        }

        /* ── Lightbox Modal ──────────────────────────────────────────────────── */
        #lightboxModal .modal-dialog {
            max-width: 860px;
        }

        #lightboxModal .modal-content {
            background: #0f172a;
            border: none;
            border-radius: 14px;
            overflow: hidden;
        }

        #lightboxModal .modal-header {
            background: #0f172a;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
            padding: .75rem 1.25rem;
        }

        #lightboxModal .modal-title {
            color: #f1f5f9;
            font-size: .95rem;
            font-weight: 600;
        }

        #lightboxModal .btn-close {
            filter: invert(1);
            opacity: .7;
        }

        #lightboxModal .modal-body {
            padding: 0;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            position: relative;
        }

        #lightboxModal .modal-body img {
            max-width: 100%;
            max-height: 65vh;
            object-fit: contain;
            display: block;
        }

        #lightboxModal .modal-footer {
            background: #0f172a;
            border-top: 1px solid rgba(255, 255, 255, .1);
            padding: .6rem 1.25rem;
        }

        #lightboxKeterangan {
            color: rgba(255, 255, 255, .65);
            font-size: .85rem;
        }

        #lightboxTanggal {
            color: rgba(255, 255, 255, .4);
            font-size: .78rem;
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, .1);
            border: none;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .15s;
            z-index: 10;
        }

        .lightbox-nav:hover {
            background: rgba(255, 255, 255, .25);
        }

        .lightbox-nav.prev {
            left: .75rem;
        }

        .lightbox-nav.next {
            right: .75rem;
        }

        @media (max-width: 576px) {
            .galeri-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: .65rem;
            }

            .galeri-item {
                aspect-ratio: 1/1;
            }

            .lightbox-nav {
                display: none;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ── HERO ─────────────────────────────────────────────────────────────────── --}}
    <section class="sid-hero">
        <div class="container">
            <div class="sid-hero-inner text-center">
                <span class="sid-hero-badge">
                    <i class="bi bi-images me-1"></i>
                    Dokumentasi Desa
                </span>
                <h1 class="sid-hero-title">
                    Galeri <em>Foto</em>
                </h1>
                <p class="sid-hero-lead">
                    Dokumentasi kegiatan, pembangunan, dan momen berharga
                    {{ config('sid.nama_desa') }}.
                    <span class="d-inline-flex align-items-center gap-1 ms-2"
                        style="background: rgba(255,255,255,.15); padding: .3rem .9rem; border-radius: 99px; font-size: .82rem; font-weight: 500; backdrop-filter: blur(8px);">
                        <i class="bi bi-camera-fill"></i> {{ $total }} foto tersedia
                    </span>
                </p>
            </div>
        </div>
    </section>

    {{-- ── FILTER BAR ───────────────────────────────────────────────────────────── --}}
    <div class="galeri-filter-wrap">
        <div class="container">
            <form method="GET" action="{{ route('galeri') }}" class="galeri-filter-inner">
                <div class="galeri-search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" name="cari" class="galeri-search-input" placeholder="Cari judul foto…"
                        value="{{ request('cari') }}" autocomplete="off">
                </div>

                <select name="urut" class="galeri-sort-select" onchange="this.form.submit()">
                    <option value="terbaru" {{ request('urut', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru
                    </option>
                    <option value="terlama" {{ request('urut') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                    <option value="az" {{ request('urut') === 'az' ? 'selected' : '' }}>A–Z</option>
                    <option value="za" {{ request('urut') === 'za' ? 'selected' : '' }}>Z–A</option>
                </select>

                <button type="submit" class="galeri-btn-filter">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>

                @if (request()->hasAny(['cari', 'urut']))
                    <a href="{{ route('galeri') }}" class="galeri-reset-link">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- ── GRID FOTO ────────────────────────────────────────────────────────────── --}}
    <section class="galeri-section">
        <div class="container">

            <p class="galeri-count-label">
                @if (request('cari'))
                    Hasil pencarian "<strong>{{ request('cari') }}</strong>" —
                    <strong>{{ $galeri->total() }}</strong> foto ditemukan
                @else
                    Menampilkan <strong>{{ $galeri->total() }}</strong> foto
                @endif
            </p>

            @if ($galeri->count())

                <div class="galeri-grid" id="galeriGrid">
                    @foreach ($galeri as $index => $item)
                        <div class="galeri-item" data-index="{{ $index }}"
                            data-src="{{ Storage::url($item->file) }}" data-judul="{{ $item->judul }}"
                            data-keterangan="{{ $item->keterangan ?? '' }}"
                            data-tanggal="{{ $item->created_at->translatedFormat('d F Y') }}" onclick="bukaLightbox(this)"
                            title="{{ $item->judul }}">

                            @if ($item->file && Storage::exists('public/' . $item->file))
                                <img src="{{ Storage::url($item->file) }}" alt="{{ $item->judul }}" loading="lazy">
                            @else
                                <div class="galeri-placeholder">
                                    <i class="bi bi-image-alt"></i>
                                    <span>Foto tidak tersedia</span>
                                </div>
                            @endif

                            <div class="galeri-overlay">
                                <div class="galeri-overlay-icon">
                                    <i class="bi bi-zoom-in"></i>
                                </div>
                                <div class="galeri-overlay-title">{{ $item->judul }}</div>
                                @if ($item->keterangan)
                                    <div class="galeri-overlay-keterangan">{{ $item->keterangan }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($galeri->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $galeri->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div class="galeri-empty">
                    <i class="bi bi-images"></i>
                    <h5>
                        @if (request('cari'))
                            Foto "{{ request('cari') }}" tidak ditemukan
                        @else
                            Belum ada foto di galeri
                        @endif
                    </h5>
                    <p class="mb-3">
                        @if (request('cari'))
                            Coba kata kunci lain atau <a href="{{ route('galeri') }}" style="color: #2d8659;">tampilkan
                                semua foto</a>.
                        @else
                            Foto kegiatan desa akan segera ditambahkan di sini.
                        @endif
                    </p>
                </div>
            @endif

        </div>
    </section>

    {{-- ── LIGHTBOX MODAL ───────────────────────────────────────────────────────── --}}
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-label="Lightbox galeri" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="lightboxJudul"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <button class="lightbox-nav prev" onclick="navigasiLightbox(-1)" title="Sebelumnya">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <img id="lightboxImg" src="" alt="">
                    <button class="lightbox-nav next" onclick="navigasiLightbox(1)" title="Berikutnya">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <div id="lightboxKeterangan"></div>
                    <div id="lightboxTanggal"></div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const galeriItems = Array.from(document.querySelectorAll('#galeriGrid .galeri-item'));
        let currentIndex = 0;
        let lightboxModal = null;

        document.addEventListener('DOMContentLoaded', function() {
            lightboxModal = new bootstrap.Modal(document.getElementById('lightboxModal'));

            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('lightboxModal');
                if (!modal.classList.contains('show')) return;
                if (e.key === 'ArrowRight') navigasiLightbox(1);
                if (e.key === 'ArrowLeft') navigasiLightbox(-1);
            });
        });

        function bukaLightbox(el) {
            currentIndex = parseInt(el.dataset.index);
            isiLightbox(el.dataset.src, el.dataset.judul, el.dataset.keterangan, el.dataset.tanggal);
            lightboxModal.show();
        }

        function isiLightbox(src, judul, keterangan, tanggal) {
            document.getElementById('lightboxImg').src = src;
            document.getElementById('lightboxImg').alt = judul;
            document.getElementById('lightboxJudul').textContent = judul;
            document.getElementById('lightboxKeterangan').textContent = keterangan || '';
            document.getElementById('lightboxTanggal').textContent = tanggal ? '📅 ' + tanggal : '';
        }

        function navigasiLightbox(arah) {
            currentIndex = (currentIndex + arah + galeriItems.length) % galeriItems.length;
            const el = galeriItems[currentIndex];
            isiLightbox(el.dataset.src, el.dataset.judul, el.dataset.keterangan, el.dataset.tanggal);
        }
    </script>
@endpush
