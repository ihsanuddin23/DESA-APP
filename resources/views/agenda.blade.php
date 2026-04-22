@extends('layouts.public')

@section('title', 'Agenda Kegiatan Desa')

@push('styles')
    <style>
        /* ── HERO menggunakan class sid-hero yang sama dengan bansos ── */
        .agenda-hero {
            background: linear-gradient(135deg, #2d8659 0%, #1e6b44 100%);
            padding: 4rem 0 3rem;
            color: #fff;
        }

        .agenda-hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: .75rem;
        }

        .agenda-hero-lead {
            font-size: 1.1rem;
            opacity: .9;
            max-width: 600px;
        }

        /* Stats Cards */
        .agenda-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: -2rem;
            position: relative;
            z-index: 10;
        }

        .agenda-stat-card {
            background: #fff;
            border-radius: .75rem;
            padding: 1.25rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
        }

        .agenda-stat-card .value {
            font-size: 2rem;
            font-weight: 800;
            color: #2d8659;
        }

        .agenda-stat-card .label {
            font-size: .85rem;
            color: #64748b;
            margin-top: .25rem;
        }

        /* Calendar */
        .agenda-calendar {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .calendar-header {
            background: #f8fafc;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }

        .calendar-nav {
            display: flex;
            gap: .5rem;
        }

        .calendar-nav-btn {
            width: 32px;
            height: 32px;
            border-radius: .5rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all .15s;
        }

        .calendar-nav-btn:hover {
            background: #f1f5f9;
            color: #2d8659;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .calendar-day-header {
            padding: .75rem .5rem;
            text-align: center;
            font-size: .75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .calendar-day {
            padding: .5rem;
            min-height: 80px;
            border-right: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            position: relative;
        }

        .calendar-day:nth-child(7n) {
            border-right: none;
        }

        .calendar-day.other-month {
            background: #fafafa;
        }

        .calendar-day.today {
            background: #ecfdf5;
        }

        .calendar-day .day-number {
            font-size: .85rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: .25rem;
        }

        .calendar-day.other-month .day-number {
            color: #cbd5e1;
        }

        .calendar-day.today .day-number {
            color: #2d8659;
        }

        .calendar-event {
            font-size: .7rem;
            padding: 2px 6px;
            border-radius: 4px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
            transition: all .15s;
        }

        .calendar-event:hover {
            transform: translateX(2px);
        }

        /* Agenda Card */
        .agenda-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: all .2s;
        }

        .agenda-card:hover {
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.1);
            transform: translateY(-2px);
        }

        .agenda-card-img {
            height: 160px;
            object-fit: cover;
            width: 100%;
        }

        .agenda-card-body {
            padding: 1.25rem;
        }

        .agenda-card-date {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .8rem;
            color: #64748b;
            margin-bottom: .5rem;
        }

        .agenda-card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: .5rem;
            line-height: 1.4;
        }

        .agenda-card-title a {
            color: inherit;
            text-decoration: none;
        }

        .agenda-card-title a:hover {
            color: #2d8659;
        }

        .agenda-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            font-size: .8rem;
            color: #64748b;
        }

        .agenda-card-meta span {
            display: flex;
            align-items: center;
            gap: .25rem;
        }

        /* Highlight Card - Warna hijau seperti bansos */
        .agenda-highlight {
            background: linear-gradient(135deg, #2d8659 0%, #1e6b44 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .agenda-highlight::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        }

        .agenda-highlight .badge-highlight {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: .7rem;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .agenda-highlight-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: .5rem;
        }

        .agenda-highlight-title a {
            color: #fff;
            text-decoration: none;
        }

        .agenda-highlight-date {
            font-size: .9rem;
            opacity: .9;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        /* Filter */
        .agenda-filter {
            background: #fff;
            border-radius: .75rem;
            padding: 1rem 1.25rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            align-items: center;
        }

        .filter-chip {
            padding: .4rem .85rem;
            border-radius: 20px;
            font-size: .8rem;
            font-weight: 500;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            text-decoration: none;
            transition: all .15s;
        }

        .filter-chip:hover,
        .filter-chip.active {
            background: #2d8659;
            border-color: #2d8659;
            color: #fff;
        }

        /* Section */
        .agenda-section {
            padding: 3rem 0;
        }

        .agenda-section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .agenda-section-title i {
            color: #2d8659;
        }

        /* Empty State */
        .agenda-empty {
            text-align: center;
            padding: 3rem;
            color: #94a3b8;
        }

        .agenda-empty i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: .5;
        }

        @media (max-width: 768px) {
            .agenda-stats {
                grid-template-columns: 1fr;
            }

            .agenda-hero-title {
                font-size: 1.75rem;
            }

            .calendar-day {
                min-height: 60px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Hero --}}
    <section class="agenda-hero">
        <div class="container">
            <div class="text-center">
                <h1 class="agenda-hero-title">Agenda Kegiatan Desa</h1>
                <p class="agenda-hero-lead mx-auto">
                    Jadwal kegiatan dan acara di {{ config('sid.nama_desa', 'Desa') }}.
                    Ikuti berbagai kegiatan untuk membangun desa bersama.
                </p>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="container">
        <div class="agenda-stats">
            <div class="agenda-stat-card">
                <div class="value">{{ $stats['total_bulan_ini'] }}</div>
                <div class="label">Kegiatan Bulan Ini</div>
            </div>
            <div class="agenda-stat-card">
                <div class="value">{{ $stats['mendatang'] }}</div>
                <div class="label">Akan Datang</div>
            </div>
            <div class="agenda-stat-card">
                <div class="value">{{ $stats['berlangsung'] }}</div>
                <div class="label">Sedang Berlangsung</div>
            </div>
        </div>
    </section>

    {{-- Highlight --}}
    @if ($agendaHighlight->count() > 0)
        <section class="agenda-section" style="background:#f8fafc;">
            <div class="container">
                <h2 class="agenda-section-title">
                    <i class="bi bi-star-fill"></i> Kegiatan Unggulan
                </h2>
                <div class="row g-4">
                    @foreach ($agendaHighlight as $item)
                        <div class="col-md-4">
                            <div class="agenda-highlight">
                                <span class="badge-highlight">
                                    <i class="bi bi-star-fill me-1"></i> Highlight
                                </span>
                                <h3 class="agenda-highlight-title">
                                    <a href="{{ route('agenda.detail', $item) }}">{{ $item->judul }}</a>
                                </h3>
                                <div class="agenda-highlight-date">
                                    <i class="bi bi-calendar-event"></i> {{ $item->tanggal_format }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Filter & Calendar --}}
    <section class="agenda-section">
        <div class="container">
            <div class="row g-4">
                {{-- Kalender --}}
                <div class="col-lg-8">
                    <div class="agenda-calendar">
                        <div class="calendar-header">
                            <h5 class="mb-0 fw-bold">
                                {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
                            </h5>
                            <div class="calendar-nav">
                                @php
                                    $prevMonth = \Carbon\Carbon::create($tahun, $bulan)->subMonth();
                                    $nextMonth = \Carbon\Carbon::create($tahun, $bulan)->addMonth();
                                @endphp
                                <a href="{{ route('agenda', ['bulan' => $prevMonth->month, 'tahun' => $prevMonth->year, 'kategori' => $kategori]) }}"
                                    class="calendar-nav-btn">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                                <a href="{{ route('agenda', ['bulan' => now()->month, 'tahun' => now()->year, 'kategori' => $kategori]) }}"
                                    class="calendar-nav-btn" title="Hari Ini">
                                    <i class="bi bi-circle-fill" style="font-size:.5rem;"></i>
                                </a>
                                <a href="{{ route('agenda', ['bulan' => $nextMonth->month, 'tahun' => $nextMonth->year, 'kategori' => $kategori]) }}"
                                    class="calendar-nav-btn">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="calendar-grid">
                            @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                                <div class="calendar-day-header">{{ $day }}</div>
                            @endforeach

                            @php
                                $startOfMonth = \Carbon\Carbon::create($tahun, $bulan, 1);
                                $endOfMonth = $startOfMonth->copy()->endOfMonth();
                                $startOfCalendar = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                                $endOfCalendar = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                                $today = now()->toDateString();
                            @endphp

                            @for ($date = $startOfCalendar->copy(); $date->lte($endOfCalendar); $date->addDay())
                                @php
                                    $isOtherMonth = $date->month !== (int) $bulan;
                                    $isToday = $date->toDateString() === $today;
                                    $dayAgenda = $agendaBulan->filter(function ($a) use ($date) {
                                        return $a->tanggal_mulai->toDateString() === $date->toDateString();
                                    });
                                @endphp
                                <div
                                    class="calendar-day {{ $isOtherMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }}">
                                    <div class="day-number">{{ $date->day }}</div>
                                    @foreach ($dayAgenda->take(2) as $event)
                                        <a href="{{ route('agenda.detail', $event) }}"
                                            class="calendar-event d-block text-decoration-none"
                                            style="background:{{ $event->kategori_bg }};color:{{ $event->kategori_color }};"
                                            title="{{ $event->judul }}">
                                            {{ Str::limit($event->judul, 15) }}
                                        </a>
                                    @endforeach
                                    @if ($dayAgenda->count() > 2)
                                        <div class="calendar-event" style="background:#f1f5f9;color:#64748b;">
                                            +{{ $dayAgenda->count() - 2 }} lagi
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- Filter & List --}}
                <div class="col-lg-4">
                    <div class="agenda-filter mb-4">
                        <span style="font-size:.85rem;color:#64748b;font-weight:600;">Filter:</span>
                        <a href="{{ route('agenda', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                            class="filter-chip {{ !$kategori ? 'active' : '' }}">Semua</a>
                        @foreach (\App\Models\Agenda::kategoriOptions() as $key => $label)
                            <a href="{{ route('agenda', ['bulan' => $bulan, 'tahun' => $tahun, 'kategori' => $key]) }}"
                                class="filter-chip {{ $kategori === $key ? 'active' : '' }}">{{ $label }}</a>
                        @endforeach
                    </div>

                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-calendar-check me-2" style="color: #2d8659;"></i>Kegiatan Mendatang
                    </h5>

                    @forelse($agendaMendatang as $item)
                        <div class="agenda-card mb-3">
                            <div class="agenda-card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge"
                                        style="background:{{ $item->kategori_bg }};color:{{ $item->kategori_color }};">
                                        {{ $item->kategori_label }}
                                    </span>
                                    @if ($item->is_berlangsung)
                                        <span class="badge bg-success">Berlangsung</span>
                                    @endif
                                </div>
                                <h6 class="agenda-card-title">
                                    <a href="{{ route('agenda.detail', $item) }}">{{ $item->judul }}</a>
                                </h6>
                                <div class="agenda-card-meta">
                                    <span><i class="bi bi-calendar"></i> {{ $item->tanggal_format }}</span>
                                    @if ($item->lokasi)
                                        <span><i class="bi bi-geo-alt"></i> {{ Str::limit($item->lokasi, 20) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="agenda-empty">
                            <i class="bi bi-calendar-x"></i>
                            <p>Belum ada kegiatan mendatang</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

@endsection
