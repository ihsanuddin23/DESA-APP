@extends('layouts.public')
@section('title', 'Lacak Pengaduan')

@push('styles')
    <style>
        .lacak-hero {
            background: linear-gradient(135deg, #1e40af 0%, #1a56db 100%);
            color: white;
            padding: 2.5rem 0 3.5rem;
        }

        .lacak-hero h1 {
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 1.85rem;
        }

        .lacak-form-card {
            background: white;
            border-radius: 1rem;
            padding: 1.75rem;
            box-shadow: 0 4px 16px rgba(15, 23, 42, .08);
            margin-top: -2rem;
            position: relative;
            z-index: 2;
        }

        .form-control-aduan {
            border: 1.5px solid #e2e8f0;
            border-radius: .55rem;
            padding: .75rem .85rem;
            font-size: .95rem;
            width: 100%;
            font-family: 'JetBrains Mono', monospace;
        }

        .form-control-aduan:focus {
            outline: none;
            border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, .1);
        }

        .btn-search {
            background: linear-gradient(135deg, #1a56db, #1e40af);
            color: white;
            border: none;
            padding: .75rem 1.75rem;
            font-size: .9rem;
            font-weight: 600;
            border-radius: .55rem;
            cursor: pointer;
        }

        .detail-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            margin-top: 1.5rem;
        }

        .status-timeline {
            display: flex;
            gap: 0;
            margin: 1.5rem 0 2rem;
            position: relative;
        }

        .timeline-step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .timeline-step::after {
            content: '';
            position: absolute;
            top: 18px;
            left: 50%;
            right: -50%;
            height: 3px;
            background: #e2e8f0;
            z-index: 0;
        }

        .timeline-step:last-child::after {
            display: none;
        }

        .timeline-step.active::after {
            background: #10b981;
        }

        .timeline-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            border: 3px solid white;
            font-size: .95rem;
        }

        .timeline-step.active .timeline-icon {
            background: #10b981;
            color: white;
        }

        .timeline-step.current .timeline-icon {
            background: #1a56db;
            color: white;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(26, 86, 219, .4);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(26, 86, 219, 0);
            }
        }

        .timeline-label {
            font-size: .75rem;
            font-weight: 600;
            color: #64748b;
            margin-top: .5rem;
        }

        .timeline-step.active .timeline-label {
            color: #065f46;
        }

        .timeline-step.current .timeline-label {
            color: #1a56db;
        }

        .badge-kat {
            display: inline-block;
            padding: .25rem .7rem;
            background: #eff6ff;
            color: #1e40af;
            border-radius: 99px;
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .badge-status {
            display: inline-block;
            padding: .3rem .8rem;
            border-radius: 99px;
            font-size: .75rem;
            font-weight: 600;
        }

        .badge-status.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-status.info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-status.success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-status.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .tanggapan-box {
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            border-radius: .5rem;
            padding: 1.25rem 1.5rem;
            margin-top: 1.5rem;
        }

        .tanggapan-box h6 {
            color: #0c4a6e;
            font-weight: 700;
            margin-bottom: .5rem;
        }

        .tanggapan-box .meta {
            font-size: .75rem;
            color: #64748b;
            margin-top: .75rem;
        }

        .foto-bukti {
            max-width: 100%;
            border-radius: .55rem;
            border: 1.5px solid #e2e8f0;
            margin-top: .5rem;
        }

        .kode-display {
            background: #f1f5f9;
            border: 1.5px dashed #cbd5e1;
            border-radius: .55rem;
            padding: .75rem 1rem;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1rem;
            color: #1e40af;
            display: inline-block;
        }

        .empty-lacak {
            text-align: center;
            padding: 2.5rem 1rem;
            color: #94a3b8;
        }

        .empty-lacak i {
            font-size: 3rem;
            display: block;
            margin-bottom: .5rem;
            color: #cbd5e1;
        }
    </style>
@endpush

@section('content')

    <div class="lacak-hero">
        <div class="container">
            <h1><i class="bi bi-search me-2"></i>Lacak Status Pengaduan</h1>
            <p style="opacity:.9;margin:0;font-size:.95rem;">Masukkan kode tiket Anda untuk melihat status tindak lanjut.</p>
        </div>
    </div>

    <div class="container" style="margin-bottom: 3rem;">

        <div class="lacak-form-card">
            <form method="GET" action="{{ route('aduan.lacak') }}">
                <div class="row g-2">
                    <div class="col-md-9">
                        <input type="text" name="kode_tiket" class="form-control-aduan"
                            placeholder="Masukkan kode tiket (contoh: PGD-20260419-A3F7)"
                            value="{{ request('kode_tiket') }}" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn-search" style="width:100%;">
                            <i class="bi bi-search"></i> Lacak
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ── Hasil Pencarian ── --}}
        @if (request('kode_tiket'))
            @if ($pengaduan)
                <div class="detail-card">
                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2 mb-3">
                        <div>
                            <div class="kode-display">{{ $pengaduan->kode_tiket }}</div>
                            <div style="margin-top:.65rem;">
                                <span class="badge-kat">{{ $pengaduan->kategori_label }}</span>
                                <span class="badge-status {{ $pengaduan->status_color }}">
                                    {{ $pengaduan->status_label }}
                                </span>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:.75rem;color:#94a3b8;">Dikirim</div>
                            <div style="font-weight:600;color:#334155;font-size:.88rem;">
                                {{ $pengaduan->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Status --}}
                    @php
                        $steps = ['baru' => 1, 'diproses' => 2, 'selesai' => 3];
                        $currentStep = $steps[$pengaduan->status] ?? 1;
                        $isDitolak = $pengaduan->status === 'ditolak';
                    @endphp
                    @if (!$isDitolak)
                        <div class="status-timeline">
                            <div
                                class="timeline-step {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep === 1 ? 'current' : '' }}">
                                <div class="timeline-icon"><i class="bi bi-inbox-fill"></i></div>
                                <div class="timeline-label">Diterima</div>
                            </div>
                            <div
                                class="timeline-step {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep === 2 ? 'current' : '' }}">
                                <div class="timeline-icon"><i class="bi bi-arrow-repeat"></i></div>
                                <div class="timeline-label">Diproses</div>
                            </div>
                            <div
                                class="timeline-step {{ $currentStep >= 3 ? 'active' : '' }} {{ $currentStep === 3 ? 'current' : '' }}">
                                <div class="timeline-icon"><i class="bi bi-check2-circle"></i></div>
                                <div class="timeline-label">Selesai</div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger" style="border-radius:.55rem;">
                            <i class="bi bi-x-circle-fill me-1"></i> <strong>Aduan ini ditolak.</strong> Silakan cek
                            tanggapan di bawah untuk alasannya.
                        </div>
                    @endif

                    <hr style="margin:1.5rem 0; border-color:#e2e8f0;">

                    <h5 style="font-family:'Lora',serif;font-weight:700;color:#0f172a;">{{ $pengaduan->judul }}</h5>
                    <div style="color:#475569;font-size:.9rem;line-height:1.6;white-space:pre-line;margin:1rem 0;">
                        {{ $pengaduan->isi }}</div>

                    @if ($pengaduan->lokasi)
                        <div style="font-size:.85rem;color:#64748b;margin-top:.5rem;">
                            <i class="bi bi-geo-alt-fill me-1" style="color:#dc2626;"></i>
                            <strong>Lokasi:</strong> {{ $pengaduan->lokasi }}
                        </div>
                    @endif

                    @if ($pengaduan->foto_bukti)
                        <div style="margin-top:1rem;">
                            <div style="font-size:.78rem;color:#94a3b8;margin-bottom:.3rem;">Foto bukti:</div>
                            <img src="{{ Storage::url($pengaduan->foto_bukti) }}" class="foto-bukti"
                                style="max-height:320px;" alt="Bukti pengaduan">
                        </div>
                    @endif

                    {{-- Tanggapan Admin --}}
                    @if ($pengaduan->tanggapan)
                        <div class="tanggapan-box">
                            <h6><i class="bi bi-chat-square-text-fill me-1"></i>Tanggapan Pemerintah Desa</h6>
                            <div style="color:#1e3a8a;font-size:.88rem;line-height:1.6;white-space:pre-line;">
                                {{ $pengaduan->tanggapan }}</div>
                            @if ($pengaduan->penanganan)
                                <div class="meta">
                                    Ditanggapi oleh <strong>{{ $pengaduan->penanganan->name }}</strong>
                                    · {{ $pengaduan->ditanggapi_pada?->isoFormat('D MMM YYYY, HH:mm') }}
                                </div>
                            @endif
                        </div>
                    @elseif($pengaduan->status === 'baru')
                        <div class="alert alert-warning" style="border-radius:.55rem;margin-top:1.5rem;">
                            <i class="bi bi-clock-history me-1"></i>
                            Aduan Anda sudah kami terima dan sedang menunggu untuk ditindaklanjuti. Mohon bersabar.
                        </div>
                    @endif
                </div>
            @else
                <div class="detail-card">
                    <div class="empty-lacak">
                        <i class="bi bi-search"></i>
                        <h5 style="color:#475569;font-weight:700;">Kode tiket tidak ditemukan</h5>
                        <div style="font-size:.88rem;">Pastikan Anda memasukkan kode dengan benar.</div>
                    </div>
                </div>
            @endif
        @endif

        <div style="text-align:center; margin-top: 2rem;">
            <a href="{{ route('aduan') }}" style="color:#1a56db;font-size:.9rem;text-decoration:none;">
                <i class="bi bi-arrow-left"></i> Kembali ke Form Pengaduan
            </a>
        </div>
    </div>

@endsection
