@extends('layouts.public')

@section('title', 'Pengumuman')

@section('content')

    {{-- ── HERO SECTION ─────────────────────────────────────────────────────────── --}}
    <section class="sid-hero">
        <div class="container">
            <div class="sid-hero-inner text-center">
                <span class="sid-hero-badge">
                    <i class="bi bi-megaphone me-1"></i>
                    Informasi Resmi
                </span>
                <h1 class="sid-hero-title">
                    Pengumuman <em>Desa</em>
                </h1>
                <p class="sid-hero-lead">
                    Informasi resmi, pemberitahuan penting, dan pengumuman terbaru dari
                    {{ config('sid.nama_desa') }}.
                </p>
            </div>
        </div>
    </section>

    {{-- ── DAFTAR PENGUMUMAN ────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">

            @if ($pengumumanList->count() > 0)

                <div class="row g-3">
                    @foreach ($pengumumanList as $item)
                        @php
                            $isExpired =
                                $item->berlaku_hingga && \Carbon\Carbon::parse($item->berlaku_hingga)->isPast();
                            $isNonaktif = $item->status === 'nonaktif';
                            $isDim = $isExpired || $isNonaktif;

                            $colors = match ($item->prioritas) {
                                'penting' => [
                                    'border' => '#dc3545',
                                    'bg' => 'rgba(220,53,69,0.07)',
                                    'icon_bg' => 'rgba(220,53,69,0.10)',
                                    'text' => '#dc3545',
                                    'badge_bg' => 'rgba(220,53,69,0.12)',
                                    'icon' => 'bi-exclamation-triangle-fill',
                                ],
                                'info' => [
                                    'border' => '#2d8659',
                                    'bg' => 'rgba(45,134,89,0.07)',
                                    'icon_bg' => 'rgba(45,134,89,0.10)',
                                    'text' => '#2d8659',
                                    'badge_bg' => 'rgba(45,134,89,0.12)',
                                    'icon' => 'bi-info-circle-fill',
                                ],
                                default => [
                                    'border' => '#6c757d',
                                    'bg' => 'rgba(108,117,125,0.05)',
                                    'icon_bg' => 'rgba(108,117,125,0.09)',
                                    'text' => '#6c757d',
                                    'badge_bg' => 'rgba(108,117,125,0.12)',
                                    'icon' => 'bi-megaphone-fill',
                                ],
                            };
                        @endphp

                        <div class="col-12">
                            <div class="sid-pengumuman-card {{ $isDim ? 'sid-pengumuman-dim' : '' }}"
                                style="background: white;
                                       border-radius: 14px;
                                       overflow: hidden;
                                       box-shadow: 0 2px 10px rgba(0,0,0,0.06);
                                       transition: all 0.3s;
                                       display: flex;
                                       border-left: 5px solid {{ $colors['border'] }};
                                       opacity: {{ $isDim ? '0.65' : '1' }};">

                                {{-- Ikon Prioritas --}}
                                <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width: 76px; background: {{ $colors['icon_bg'] }};">
                                    <i class="bi {{ $colors['icon'] }}"
                                        style="font-size: 1.75rem; color: {{ $colors['text'] }};"></i>
                                </div>

                                {{-- Konten --}}
                                <div style="padding: 1.1rem 1.4rem; flex-grow: 1; min-width: 0;">

                                    {{-- Baris atas: judul + badge prioritas --}}
                                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-1">
                                        <h3
                                            style="font-size: 1.05rem; font-weight: 700; color: #1a1a1a; margin: 0; line-height: 1.45;">
                                            {{ $item->judul }}
                                        </h3>
                                        <span
                                            style="background: {{ $colors['badge_bg'] }};
                                                     color: {{ $colors['text'] }};
                                                     padding: 3px 11px;
                                                     border-radius: 99px;
                                                     font-size: 0.7rem;
                                                     font-weight: 700;
                                                     text-transform: uppercase;
                                                     white-space: nowrap;
                                                     flex-shrink: 0;">
                                            {{ ucfirst($item->prioritas) }}
                                        </span>
                                    </div>

                                    {{-- Isi singkat --}}
                                    @if ($item->isi)
                                        <p class="text-muted small mb-2" style="line-height: 1.65; margin: 0;">
                                            {{ Str::limit(strip_tags($item->isi), 220) }}
                                        </p>
                                    @endif

                                    {{-- Lampiran --}}
                                    @if ($item->file_lampiran)
                                        <div class="mb-2">
                                            <a href="{{ Storage::url($item->file_lampiran) }}" target="_blank"
                                                rel="noopener"
                                                class="d-inline-flex align-items-center gap-1 small text-decoration-none"
                                                style="color: #2d8659; font-weight: 600;">
                                                <i class="bi bi-paperclip"></i>
                                                Unduh Lampiran
                                            </a>
                                        </div>
                                    @endif

                                    {{-- Meta bawah --}}
                                    <div class="d-flex flex-wrap align-items-center gap-3 small text-muted mt-2">

                                        {{-- Tanggal dibuat --}}
                                        <span>
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $item->created_at->isoFormat('D MMM YYYY') }}
                                        </span>

                                        {{-- Berlaku hingga --}}
                                        @if ($item->berlaku_hingga)
                                            <span>
                                                <i class="bi bi-hourglass-split me-1"></i>
                                                s.d.
                                                {{ \Carbon\Carbon::parse($item->berlaku_hingga)->isoFormat('D MMM YYYY') }}
                                            </span>
                                        @else
                                            <span>
                                                <i class="bi bi-infinity me-1"></i>
                                                Tidak ada batas waktu
                                            </span>
                                        @endif

                                        {{-- Penulis (dari relasi user) --}}
                                        @if ($item->user)
                                            <span>
                                                <i class="bi bi-person me-1"></i>
                                                {{ $item->user->name }}
                                            </span>
                                        @endif

                                        {{-- Badge status --}}
                                        <span class="ms-auto"
                                            style="padding: 2px 10px;
                                                   border-radius: 99px;
                                                   font-size: 0.68rem;
                                                   font-weight: 700;
                                                   background: {{ $isExpired ? 'rgba(220,53,69,0.10)' : ($isNonaktif ? 'rgba(108,117,125,0.10)' : 'rgba(45,134,89,0.10)') }};
                                                   color: {{ $isExpired ? '#dc3545' : ($isNonaktif ? '#6c757d' : '#2d8659') }};">
                                            <i
                                                class="bi {{ $isExpired ? 'bi-x-circle-fill' : ($isNonaktif ? 'bi-dash-circle-fill' : 'bi-check-circle-fill') }} me-1"></i>
                                            {{ $isExpired ? 'Kedaluwarsa' : ($isNonaktif ? 'Nonaktif' : 'Aktif') }}
                                        </span>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($pengumumanList->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $pengumumanList->links() }}
                    </div>
                @endif
            @else
                {{-- Empty state --}}
                <div class="text-center py-5">
                    <i class="bi bi-megaphone" style="font-size: 5rem; color: #cbd5e1;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Pengumuman</h4>
                    <p class="text-muted">Pengumuman desa akan segera dipublikasikan. Silakan kunjungi halaman ini kembali
                        nanti.</p>
                </div>
            @endif

        </div>
    </section>

@endsection

@push('styles')
    <style>
        .sid-pengumuman-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.11) !important;
        }

        .sid-pengumuman-dim:hover {
            transform: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06) !important;
        }
    </style>
@endpush
