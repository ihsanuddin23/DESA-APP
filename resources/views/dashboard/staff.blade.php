@extends('layouts.app')
@section('title', 'Dashboard Staff')
@section('page-title', 'Dashboard Staff Desa')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .welcome-card {
            background: linear-gradient(135deg, #1e40af, #1a56db);
            color: white;
            border-radius: .85rem;
            padding: 1.75rem 2rem;
            box-shadow: 0 4px 16px rgba(26, 86, 219, .15);
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
            opacity: .85;
            font-size: .9rem;
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: .85rem;
            padding: 1.25rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: .65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-label {
            font-size: .75rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            font-family: 'JetBrains Mono', monospace;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: .85rem;
        }

        .action-card {
            background: #fff;
            border: 1.5px solid #f1f5f9;
            border-radius: .75rem;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: .5rem;
            text-decoration: none;
            color: #334155;
            transition: all .2s;
            cursor: pointer;
        }

        .action-card:hover {
            border-color: #1a56db;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 86, 219, .12);
            color: #1a56db;
        }

        .action-card i {
            font-size: 1.75rem;
            color: #1a56db;
        }

        .action-card span {
            font-weight: 600;
            font-size: .88rem;
        }
    </style>
@endpush

@section('content')

    <div class="welcome-card">
        <h4>Selamat Datang, {{ auth()->user()->name }}! 👋</h4>
        <p>Dashboard staff desa untuk kelola konten, pengumuman, dan data kependudukan.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff; color:#1e40af;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Penduduk</div>
                <div class="stat-value">{{ number_format($stats['total_penduduk']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#ecfdf5; color:#065f46;">
                <i class="bi bi-newspaper"></i>
            </div>
            <div>
                <div class="stat-label">Berita Terbit</div>
                <div class="stat-value">{{ number_format($stats['total_berita']) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7; color:#92400e;">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <div>
                <div class="stat-label">Pengumuman Aktif</div>
                <div class="stat-value">{{ number_format($stats['total_pengumuman']) }}</div>
            </div>
        </div>
    </div>

    <div
        style="background:#fff; border:1px solid #f1f5f9; border-radius:.85rem; padding:1.5rem; box-shadow:0 1px 6px rgba(15,23,42,.05);">
        <h6 style="font-weight:700; color:#0f172a; margin-bottom:1rem;">
            <i class="bi bi-lightning-charge-fill" style="color:#1a56db;"></i> Aksi Cepat
        </h6>
        <div class="quick-actions">
            <a href="{{ route('admin.berita.create') }}" class="action-card">
                <i class="bi bi-newspaper"></i>
                <span>Tulis Berita Baru</span>
            </a>
            <a href="{{ route('admin.pengumuman.create') }}" class="action-card">
                <i class="bi bi-megaphone-fill"></i>
                <span>Buat Pengumuman</span>
            </a>
            <a href="{{ route('admin.galeri.create') }}" class="action-card">
                <i class="bi bi-image-fill"></i>
                <span>Upload Foto Galeri</span>
            </a>
            <a href="{{ route('admin.penduduk.index') }}" class="action-card">
                <i class="bi bi-person-lines-fill"></i>
                <span>Data Penduduk</span>
            </a>
        </div>
    </div>

@endsection
