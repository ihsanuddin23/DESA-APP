@extends('layouts.app')
@section('title', 'Dashboard RW')
@section('page-title', 'Dashboard Ketua RW')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .welcome-card {
            background: linear-gradient(135deg, #065f46, #047857);
            color: white;
            border-radius: .85rem;
            padding: 1.75rem 2rem;
            box-shadow: 0 4px 16px rgba(6, 95, 70, .15);
            margin-bottom: 1.25rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(255, 255, 255, .08), transparent 70%);
            border-radius: 50%;
            transform: translate(25%, -25%);
        }

        .welcome-card h4 {
            font-family: 'Lora', serif;
            font-weight: 700;
            margin: 0 0 .35rem 0;
        }

        .welcome-card p {
            opacity: .9;
            font-size: .9rem;
            margin: 0;
        }

        .stat-hero {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: .85rem;
            padding: 1.5rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .stat-hero-icon {
            width: 64px;
            height: 64px;
            border-radius: .75rem;
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .stat-hero-value {
            font-size: 2.25rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1;
            font-family: 'JetBrains Mono', monospace;
        }

        .stat-hero-label {
            font-size: .82rem;
            color: #64748b;
            margin-top: .25rem;
        }

        .rt-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1rem;
        }

        .rt-card {
            background: #fff;
            border: 1.5px solid #f1f5f9;
            border-radius: .75rem;
            padding: 1.25rem;
            transition: all .2s;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .rt-card:hover {
            border-color: #047857;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(6, 95, 70, .1);
        }

        .rt-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .rt-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #065f46;
            font-family: 'JetBrains Mono', monospace;
        }

        .rt-badge {
            background: #d1fae5;
            color: #065f46;
            padding: .2rem .6rem;
            border-radius: 99px;
            font-size: .68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .02em;
        }

        .rt-stats {
            display: flex;
            gap: 1rem;
            padding-top: .5rem;
            border-top: 1px solid #f1f5f9;
        }

        .rt-stat {
            flex: 1;
        }

        .rt-stat-value {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
        }

        .rt-stat-label {
            font-size: .7rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .rt-ketua {
            font-size: .82rem;
            color: #475569;
            display: flex;
            align-items: center;
            gap: .35rem;
        }

        .rt-ketua-empty {
            color: #cbd5e1;
            font-style: italic;
            font-size: .78rem;
        }
    </style>
@endpush

@section('content')

    <div class="welcome-card">
        <h4>Selamat Datang, {{ $user->name }}! 👋</h4>
        <p>Anda adalah Ketua RW {{ $user->rw }} · {{ config('sid.nama_desa', 'Desa') }}</p>
    </div>

    <div class="stat-hero">
        <div class="stat-hero-icon"><i class="bi bi-people-fill"></i></div>
        <div style="flex-grow:1;">
            <div class="stat-hero-value">{{ number_format($totalWarga) }}</div>
            <div class="stat-hero-label">Total warga di RW {{ $user->rw }}, tersebar dalam {{ $rtList->count() }} RT
            </div>
        </div>
        <a href="{{ route('admin.penduduk.index') }}" class="btn-primary-sm">
            <i class="bi bi-arrow-right"></i> Lihat Semua
        </a>
    </div>

    <div
        style="background:#fff; border:1px solid #f1f5f9; border-radius:.85rem; padding:1.5rem; box-shadow:0 1px 6px rgba(15,23,42,.05);">
        <h6 style="font-weight:700; color:#0f172a; margin-bottom:1rem;">
            <i class="bi bi-grid-3x3-gap-fill" style="color:#047857;"></i>
            Daftar RT di RW {{ $user->rw }}
        </h6>

        @if ($rtList->count() > 0)
            <div class="rt-grid">
                @foreach ($rtList as $rt)
                    <a href="{{ route('admin.penduduk.index', ['rt' => $rt->rt]) }}" class="rt-card">
                        <div class="rt-header">
                            <div class="rt-number">RT {{ $rt->rt }}</div>
                            <span class="rt-badge">{{ $rt->jumlah_warga }} Warga</span>
                        </div>

                        <div class="rt-ketua">
                            @if (isset($ketuaRt[$rt->rt]))
                                <i class="bi bi-person-check-fill" style="color:#047857;"></i>
                                <span>Ketua: <strong>{{ $ketuaRt[$rt->rt]->name }}</strong></span>
                            @else
                                <span class="rt-ketua-empty"><i class="bi bi-person-exclamation"></i> Belum ada ketua
                                    RT</span>
                            @endif
                        </div>

                        <div class="rt-stats">
                            <div class="rt-stat">
                                <div class="rt-stat-value">{{ $rt->jumlah_warga }}</div>
                                <div class="rt-stat-label">Warga</div>
                            </div>
                            <div class="rt-stat">
                                <div class="rt-stat-value">{{ $rt->jumlah_kk }}</div>
                                <div class="rt-stat-label">Kartu Keluarga</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                <div class="empty-title">Belum ada RT di wilayah Anda</div>
                <div class="empty-sub">Minta admin untuk menambahkan data penduduk</div>
            </div>
        @endif
    </div>

@endsection
