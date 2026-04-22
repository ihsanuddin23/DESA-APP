@extends('layouts.public')

@section('title', $agenda->judul)

@push('styles')
    <style>
        .agenda-detail-hero {
            background: linear-gradient(135deg, #2d8659 0%, #1e6b44 100%);
            padding: 3rem 0;
            color: #fff;
        }

        .agenda-detail-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            margin-top: -3rem;
            position: relative;
            z-index: 10;
            overflow: hidden;
        }

        .agenda-detail-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .agenda-detail-body {
            padding: 2rem;
        }

        .agenda-detail-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .agenda-meta-list {
            list-style: none;
            padding: 0;
            margin: 0 0 1.5rem;
        }

        .agenda-meta-list li {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            padding: .75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .agenda-meta-list li:last-child {
            border-bottom: none;
        }

        .agenda-meta-list .meta-icon {
            width: 40px;
            height: 40px;
            border-radius: .5rem;
            background: #ecfdf5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d8659;
            flex-shrink: 0;
        }

        .agenda-meta-list .meta-label {
            font-size: .75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .agenda-meta-list .meta-value {
            font-weight: 600;
            color: #0f172a;
        }

        .agenda-description {
            color: #475569;
            line-height: 1.8;
        }

        .agenda-sidebar-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .agenda-sidebar-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .agenda-sidebar-title i {
            color: #2d8659;
        }

        .related-item {
            display: flex;
            gap: 1rem;
            padding: .75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .related-item:last-child {
            border-bottom: none;
        }

        .related-item-date {
            width: 50px;
            height: 50px;
            background: #ecfdf5;
            border-radius: .5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .related-item-date .day {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d8659;
            line-height: 1;
        }

        .related-item-date .month {
            font-size: .65rem;
            color: #64748b;
            text-transform: uppercase;
        }

        .related-item-title {
            font-size: .9rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: .25rem;
        }

        .related-item-title a {
            color: inherit;
            text-decoration: none;
        }

        .related-item-title a:hover {
            color: #2d8659;
        }

        .btn-outline-success {
            border-color: #2d8659;
            color: #2d8659;
        }

        .btn-outline-success:hover {
            background: #2d8659;
            border-color: #2d8659;
            color: #fff;
        }
    </style>
@endpush

@section('content')

    <section class="agenda-detail-hero">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background:none;padding:0;margin-bottom:1rem;">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:rgba(255,255,255,.8);">Beranda</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('agenda') }}" style="color:rgba(255,255,255,.8);">Agenda</a>
                    </li>
                    <li class="breadcrumb-item active" style="color:#fff;">Detail</li>
                </ol>
            </nav>
            <span class="badge" style="background:{{ $agenda->kategori_bg }};color:{{ $agenda->kategori_color }};">
                {{ $agenda->kategori_label }}
            </span>
        </div>
    </section>

    <section class="container pb-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="agenda-detail-card">
                    @if ($agenda->gambar_url)
                        <img src="{{ $agenda->gambar_url }}" alt="{{ $agenda->judul }}" class="agenda-detail-img">
                    @endif
                    <div class="agenda-detail-body">
                        <h1 class="agenda-detail-title">{{ $agenda->judul }}</h1>

                        <ul class="agenda-meta-list">
                            <li>
                                <div class="meta-icon"><i class="bi bi-calendar-event"></i></div>
                                <div>
                                    <div class="meta-label">Tanggal</div>
                                    <div class="meta-value">{{ $agenda->tanggal_format }}</div>
                                </div>
                            </li>
                            @if ($agenda->waktu_format)
                                <li>
                                    <div class="meta-icon"><i class="bi bi-clock"></i></div>
                                    <div>
                                        <div class="meta-label">Waktu</div>
                                        <div class="meta-value">{{ $agenda->waktu_format }}</div>
                                    </div>
                                </li>
                            @endif
                            @if ($agenda->lokasi)
                                <li>
                                    <div class="meta-icon"><i class="bi bi-geo-alt"></i></div>
                                    <div>
                                        <div class="meta-label">Lokasi</div>
                                        <div class="meta-value">{{ $agenda->lokasi }}</div>
                                    </div>
                                </li>
                            @endif
                            @if ($agenda->penyelenggara)
                                <li>
                                    <div class="meta-icon"><i class="bi bi-building"></i></div>
                                    <div>
                                        <div class="meta-label">Penyelenggara</div>
                                        <div class="meta-value">{{ $agenda->penyelenggara }}</div>
                                    </div>
                                </li>
                            @endif
                            @if ($agenda->kontak_person)
                                <li>
                                    <div class="meta-icon"><i class="bi bi-person"></i></div>
                                    <div>
                                        <div class="meta-label">Kontak Person</div>
                                        <div class="meta-value">
                                            {{ $agenda->kontak_person }}
                                            @if ($agenda->telepon)
                                                <br><a href="tel:{{ $agenda->telepon }}"
                                                    style="color:#2d8659;">{{ $agenda->telepon }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>

                        @if ($agenda->deskripsi)
                            <h5 class="fw-bold mb-3">Deskripsi Kegiatan</h5>
                            <div class="agenda-description">
                                {!! nl2br(e($agenda->deskripsi)) !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- Status --}}
                <div class="agenda-sidebar-card">
                    <div class="agenda-sidebar-title"><i class="bi bi-info-circle"></i> Status Kegiatan</div>
                    @if ($agenda->is_berlangsung)
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-play-circle-fill me-2"></i> Kegiatan sedang berlangsung
                        </div>
                    @elseif($agenda->is_mendatang)
                        <div class="alert mb-0" style="background: #ecfdf5; color: #166534; border: 1px solid #bbf7d0;">
                            <i class="bi bi-calendar-check me-2"></i> Kegiatan akan datang
                        </div>
                    @else
                        <div class="alert alert-secondary mb-0">
                            <i class="bi bi-check-circle me-2"></i> Kegiatan telah selesai
                        </div>
                    @endif
                </div>

                {{-- Share --}}
                <div class="agenda-sidebar-card">
                    <div class="agenda-sidebar-title"><i class="bi bi-share"></i> Bagikan</div>
                    <div class="d-flex gap-2">
                        <a href="https://wa.me/?text={{ urlencode($agenda->judul . ' - ' . url()->current()) }}"
                            target="_blank" class="btn btn-success btn-sm">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank" class="btn btn-sm" style="background: #1877f2; color: #fff;">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                    </div>
                </div>

                {{-- Related --}}
                @if ($agendaTerkait->count() > 0)
                    <div class="agenda-sidebar-card">
                        <div class="agenda-sidebar-title"><i class="bi bi-calendar2-week"></i> Kegiatan Terkait
                        </div>
                        @foreach ($agendaTerkait as $related)
                            <div class="related-item">
                                <div class="related-item-date">
                                    <span class="day">{{ $related->tanggal_mulai->format('d') }}</span>
                                    <span class="month">{{ $related->tanggal_mulai->translatedFormat('M') }}</span>
                                </div>
                                <div>
                                    <div class="related-item-title">
                                        <a href="{{ route('agenda.detail', $related) }}">{{ $related->judul }}</a>
                                    </div>
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt"></i>
                                        {{ Str::limit($related->lokasi ?? 'Lokasi belum ditentukan', 25) }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Back --}}
                <a href="{{ route('agenda') }}" class="btn btn-outline-success w-100">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Agenda
                </a>
            </div>
        </div>
    </section>

@endsection
