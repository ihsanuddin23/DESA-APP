@extends('layouts.app')
@section('title', 'Detail Pengaduan')
@section('page-title', 'Detail Pengaduan')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .detail-wrapper {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 1.5rem;
        }

        @media (max-width: 991px) {
            .detail-wrapper {
                grid-template-columns: 1fr;
            }
        }

        .detail-card {
            background: white;
            border-radius: .85rem;
            padding: 1.75rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
        }

        .detail-card h6 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            padding-bottom: .75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .kode-hero {
            background: linear-gradient(135deg, #1e40af, #1a56db);
            color: white;
            padding: 1.25rem 1.5rem;
            border-radius: .75rem;
            margin-bottom: 1.25rem;
        }

        .kode-hero .kode {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .kode-hero .judul {
            font-size: 1.25rem;
            font-weight: 700;
            margin-top: .3rem;
            font-family: 'Lora', serif;
        }

        .kode-hero .meta {
            opacity: .85;
            font-size: .82rem;
            margin-top: .35rem;
        }

        .info-row {
            display: flex;
            padding: .6rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: .85rem;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .label {
            width: 140px;
            color: #64748b;
            font-weight: 500;
        }

        .info-row .value {
            color: #0f172a;
            font-weight: 600;
            flex: 1;
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

        .status-badge {
            display: inline-block;
            padding: .3rem .8rem;
            border-radius: 99px;
            font-size: .78rem;
            font-weight: 600;
        }

        .status-badge.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.info {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .foto-bukti {
            max-width: 100%;
            border-radius: .55rem;
            border: 1.5px solid #e2e8f0;
            margin-top: .5rem;
        }

        .form-label-custom {
            font-size: .85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: .4rem;
        }

        .form-control-custom {
            border: 1.5px solid #e2e8f0;
            border-radius: .55rem;
            padding: .65rem .85rem;
            font-size: .9rem;
            width: 100%;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, .1);
        }
    </style>
@endpush

@section('content')

    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('admin.pengaduan.index') }}" style="color:#64748b;text-decoration:none;font-size:.9rem;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="detail-wrapper">
        {{-- ═══════ KOLOM KIRI: DETAIL ADUAN ═══════ --}}
        <div>
            <div class="kode-hero">
                <div class="kode">{{ $pengaduan->kode_tiket }}</div>
                <div class="judul">{{ $pengaduan->judul }}</div>
                <div class="meta">
                    <span class="badge" style="background:rgba(255,255,255,.2);">{{ $pengaduan->kategori_label }}</span>
                    · Prioritas: <strong style="text-transform:capitalize;">{{ $pengaduan->prioritas }}</strong>
                    · <i class="bi bi-clock"></i> {{ $pengaduan->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                </div>
            </div>

            <div class="detail-card mb-3">
                <h6><i class="bi bi-person-circle" style="color:#1a56db;"></i>Identitas Pengadu</h6>
                <div class="info-row">
                    <div class="label">Nama</div>
                    <div class="value">{{ $pengaduan->nama_pengadu }}</div>
                </div>
                <div class="info-row">
                    <div class="label">Kontak</div>
                    <div class="value">{{ $pengaduan->kontak ?? '—' }}</div>
                </div>
                @if ($pengaduan->nik)
                    <div class="info-row">
                        <div class="label">NIK</div>
                        <div class="value" style="font-family:'JetBrains Mono',monospace;">{{ $pengaduan->nik }}</div>
                    </div>
                @endif
                @if ($pengaduan->rt || $pengaduan->rw)
                    <div class="info-row">
                        <div class="label">RT / RW</div>
                        <div class="value">RT {{ $pengaduan->rt ?? '-' }} / RW {{ $pengaduan->rw ?? '-' }}</div>
                    </div>
                @endif
            </div>

            <div class="detail-card mb-3">
                <h6><i class="bi bi-chat-left-text-fill" style="color:#1a56db;"></i>Isi Aduan</h6>
                <div style="color:#334155;font-size:.92rem;line-height:1.7;white-space:pre-line;">{{ $pengaduan->isi }}
                </div>

                @if ($pengaduan->lokasi)
                    <div
                        style="margin-top:1rem;padding:.75rem 1rem;background:#fef2f2;border-left:3px solid #dc2626;border-radius:.4rem;">
                        <i class="bi bi-geo-alt-fill me-1" style="color:#dc2626;"></i>
                        <strong style="font-size:.82rem;color:#991b1b;">Lokasi Kejadian:</strong>
                        <span style="color:#7f1d1d;font-size:.88rem;">{{ $pengaduan->lokasi }}</span>
                    </div>
                @endif

                @if ($pengaduan->foto_bukti)
                    <div style="margin-top:1rem;">
                        <div style="font-size:.8rem;color:#64748b;margin-bottom:.4rem;font-weight:600;">
                            <i class="bi bi-image me-1"></i> Foto Bukti:
                        </div>
                        <a href="{{ Storage::url($pengaduan->foto_bukti) }}" target="_blank" rel="noopener">
                            <img src="{{ Storage::url($pengaduan->foto_bukti) }}" class="foto-bukti" alt="Bukti pengaduan"
                                style="max-height:400px;">
                        </a>
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:.25rem;">Klik foto untuk lihat ukuran penuh
                        </div>
                    </div>
                @endif
            </div>

            @if ($pengaduan->tanggapan)
                <div class="detail-card" style="background:#f0f9ff;border-left:4px solid #0ea5e9;">
                    <h6 style="color:#0c4a6e;"><i class="bi bi-chat-square-text-fill"></i>Tanggapan Resmi</h6>
                    <div style="color:#1e3a8a;font-size:.92rem;line-height:1.7;white-space:pre-line;">
                        {{ $pengaduan->tanggapan }}</div>
                    @if ($pengaduan->penanganan)
                        <div
                            style="margin-top:1rem;padding-top:1rem;border-top:1px solid #bae6fd;font-size:.8rem;color:#475569;">
                            <i class="bi bi-person-check-fill me-1" style="color:#0ea5e9;"></i>
                            Ditanggapi oleh <strong>{{ $pengaduan->penanganan->name }}</strong>
                            ({{ $pengaduan->penanganan->role_label }}) ·
                            {{ $pengaduan->ditanggapi_pada?->diffForHumans() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- ═══════ KOLOM KANAN: FORM TANGGAPAN ═══════ --}}
        <div>
            <div class="detail-card" style="position:sticky;top:1rem;">
                <h6>
                    @if ($pengaduan->status === 'baru')
                        <i class="bi bi-reply-fill" style="color:#1a56db;"></i>Respons Aduan
                    @else
                        <i class="bi bi-pencil-square" style="color:#1a56db;"></i>Update Tanggapan
                    @endif
                </h6>

                <div
                    style="text-align:center;padding:.75rem 1rem;background:#f8fafc;border-radius:.55rem;margin-bottom:1rem;">
                    <div style="font-size:.72rem;color:#64748b;margin-bottom:.25rem;">Status Saat Ini</div>
                    <span class="status-badge {{ $pengaduan->status_color }}"
                        style="font-size:.85rem;padding:.35rem 1rem;">
                        {{ $pengaduan->status_label }}
                    </span>
                </div>

                @if (session('success'))
                    <div class="alert alert-success" style="font-size:.82rem;">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" style="font-size:.82rem;">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.pengaduan.update', $pengaduan) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label-custom">Ubah Status</label>
                        <select name="status" class="form-control-custom" required>
                            @foreach (\App\Models\Pengaduan::STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ $pengaduan->status === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Prioritas</label>
                        <select name="prioritas" class="form-control-custom" required>
                            @foreach (\App\Models\Pengaduan::PRIORITAS as $key => $label)
                                <option value="{{ $key }}"
                                    {{ $pengaduan->prioritas === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Tanggapan / Respons</label>
                        <textarea name="tanggapan" class="form-control-custom" rows="6"
                            placeholder="Tulis tanggapan resmi untuk warga...">{{ old('tanggapan', $pengaduan->tanggapan) }}</textarea>
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:.3rem;">
                            Tanggapan ini akan terlihat oleh warga saat mereka melacak aduan.
                        </div>
                    </div>

                    <button type="submit" class="btn-primary-sm" style="width:100%;justify-content:center;padding:.7rem;">
                        <i class="bi bi-save"></i> Simpan Tanggapan
                    </button>
                </form>

                <hr style="margin:1.25rem 0;border-color:#e2e8f0;">

                <div style="font-size:.75rem;color:#64748b;">
                    <div style="margin-bottom:.3rem;"><i class="bi bi-info-circle"></i> <strong>Info:</strong></div>
                    <ul style="padding-left:1rem;margin:0;line-height:1.7;">
                        <li>Status <strong>Selesai</strong>: aduan sudah ditindaklanjuti</li>
                        <li>Status <strong>Ditolak</strong>: aduan tidak valid / tidak relevan</li>
                        <li>Warga akan lihat tanggapan via kode tiket</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
