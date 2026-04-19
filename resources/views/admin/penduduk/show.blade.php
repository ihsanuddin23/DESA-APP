@extends('layouts.app')
@section('title', 'Detail Penduduk')
@section('page-title', 'Detail Penduduk')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .detail-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: .85rem;
            padding: 1.75rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            margin-bottom: 1.25rem;
        }

        .detail-card h6 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.25rem;
            font-size: .92rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            padding-bottom: .85rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: .85rem 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: .15rem;
        }

        .info-label {
            font-size: .72rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .03em;
            font-weight: 500;
        }

        .info-value {
            font-size: .9rem;
            color: #0f172a;
            font-weight: 500;
        }

        .info-value.mono {
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: .02em;
        }

        .identity-card {
            background: linear-gradient(135deg, #1e3a8a, #1e40af);
            color: white;
            border-radius: .85rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .identity-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, .08), transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .identity-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            font-weight: 700;
            border: 3px solid rgba(255, 255, 255, .25);
            flex-shrink: 0;
        }

        .identity-nama {
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0 0 .25rem 0;
            font-family: 'Lora', serif;
        }

        .identity-nik {
            font-family: 'JetBrains Mono', monospace;
            font-size: .85rem;
            opacity: .85;
            letter-spacing: .05em;
        }

        .identity-label {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            opacity: .8;
            font-weight: 500;
            margin-bottom: .1rem;
        }

        .anggota-kk-row {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem;
            border-radius: .55rem;
            background: #f8fafc;
            margin-bottom: .5rem;
            transition: background .15s;
        }

        .anggota-kk-row:hover {
            background: #eff6ff;
        }

        .anggota-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .75rem;
            flex-shrink: 0;
        }

        .anggota-avatar.female {
            background: linear-gradient(135deg, #fce7f3, #fbcfe8);
            color: #9d174d;
        }

        .badge-hubungan {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: .68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Detail Penduduk</h5>
            <div class="sub">Informasi lengkap warga</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.penduduk.edit', $penduduk) }}" class="btn-primary-sm">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.penduduk.index') }}" class="btn-primary-sm" style="background:#64748b;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- ── IDENTITY CARD (Header Hero) ────────────────────────────────────────── --}}
    <div class="identity-card">
        <div class="d-flex align-items-center gap-3 mb-3" style="position:relative; z-index:1;">
            <div class="identity-avatar">{{ strtoupper(substr($penduduk->nama, 0, 2)) }}</div>
            <div style="flex-grow:1;">
                <div class="identity-label">
                    <i class="bi bi-{{ $penduduk->jenis_kelamin === 'L' ? 'gender-male' : 'gender-female' }}"></i>
                    {{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }} · {{ $penduduk->usia }} tahun
                </div>
                <h2 class="identity-nama">{{ $penduduk->nama }}</h2>
                <div class="identity-nik">
                    <i class="bi bi-credit-card-2-front-fill me-1"></i>NIK: {{ $penduduk->nik }}
                </div>
            </div>
            @if ($penduduk->status_aktif)
                <span class="status-badge"
                    style="background:rgba(34, 197, 94, .25); color:white; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,.3);">
                    <i class="bi bi-check-circle-fill"></i>Aktif
                </span>
            @else
                <span class="status-badge"
                    style="background:rgba(239, 68, 68, .25); color:white; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,.3);">
                    <i class="bi bi-x-circle-fill"></i>Non-aktif
                </span>
            @endif
        </div>

        <div
            style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:1rem; padding-top:1rem; border-top:1px solid rgba(255,255,255,.15); position:relative; z-index:1;">
            <div>
                <div class="identity-label">No. KK</div>
                <div style="font-family:'JetBrains Mono', monospace; font-size:.88rem;">{{ $penduduk->no_kk }}</div>
            </div>
            <div>
                <div class="identity-label">Hubungan</div>
                <div style="font-size:.88rem; font-weight:600;">{{ $penduduk->status_hubungan_keluarga }}</div>
            </div>
            <div>
                <div class="identity-label">Alamat</div>
                <div style="font-size:.88rem; font-weight:600;">RT
                    {{ $penduduk->rt }}{{ $penduduk->rw ? ' / RW ' . $penduduk->rw : '' }}</div>
            </div>
            <div>
                <div class="identity-label">Kewarganegaraan</div>
                <div style="font-size:.88rem; font-weight:600;">{{ $penduduk->kewarganegaraan }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- ── KOLOM KIRI: DATA PRIBADI ───────────────────────────────────────── --}}
        <div class="col-lg-8">
            <div class="detail-card">
                <h6><i class="bi bi-person-vcard" style="color:#1a56db;"></i>Identitas Pribadi</h6>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $penduduk->nama }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">NIK</div>
                        <div class="info-value mono">{{ $penduduk->nik }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">No. Kartu Keluarga</div>
                        <div class="info-value mono">{{ $penduduk->no_kk }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Jenis Kelamin</div>
                        <div class="info-value">{{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tempat Lahir</div>
                        <div class="info-value">{{ $penduduk->tempat_lahir ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Lahir</div>
                        <div class="info-value">
                            {{ $penduduk->tanggal_lahir->isoFormat('D MMMM YYYY') }}
                            <span style="color:#94a3b8; font-size:.8rem;">({{ $penduduk->usia }} tahun)</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Agama</div>
                        <div class="info-value">{{ $penduduk->agama }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Kewarganegaraan</div>
                        <div class="info-value">{{ $penduduk->kewarganegaraan }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <h6><i class="bi bi-briefcase-fill" style="color:#1a56db;"></i>Sosial & Pekerjaan</h6>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Status Perkawinan</div>
                        <div class="info-value">{{ $penduduk->status_perkawinan }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status Hubungan Keluarga</div>
                        <div class="info-value">{{ $penduduk->status_hubungan_keluarga }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Pekerjaan</div>
                        <div class="info-value">{{ $penduduk->pekerjaan ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Pendidikan Terakhir</div>
                        <div class="info-value">{{ $penduduk->pendidikan ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <h6><i class="bi bi-geo-alt-fill" style="color:#1a56db;"></i>Alamat</h6>
                <div class="info-grid">
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <div class="info-label">Alamat Lengkap</div>
                        <div class="info-value">{{ $penduduk->alamat ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">RT</div>
                        <div class="info-value">{{ $penduduk->rt }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">RW</div>
                        <div class="info-value">{{ $penduduk->rw ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <h6><i class="bi bi-map-fill" style="color:#1a56db;"></i>Wilayah Administratif</h6>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Provinsi</div>
                        <div class="info-value">{{ $penduduk->provinsi?->nama ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Kabupaten / Kota</div>
                        <div class="info-value">{{ $penduduk->kabkota?->nama ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Kecamatan</div>
                        <div class="info-value">{{ $penduduk->kecamatan?->nama ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Kelurahan / Desa</div>
                        <div class="info-value">{{ $penduduk->kelurahan?->nama ?? '—' }}</div>
                    </div>
                </div>
                @if ($penduduk->provinsi_id)
                    <div
                        style="margin-top:1rem; padding:.75rem 1rem; background:#eff6ff; border-left:3px solid #1a56db; border-radius:.4rem; font-size:.82rem; color:#1e40af;">
                        <i class="bi bi-geo-alt-fill me-1"></i>
                        <strong>Alamat Lengkap:</strong> {{ $penduduk->alamat_lengkap }}
                    </div>
                @endif
            </div>
        </div>

        {{-- ── KOLOM KANAN: ANGGOTA KELUARGA & META ───────────────────────────── --}}
        <div class="col-lg-4">
            <div class="detail-card">
                <h6><i class="bi bi-house-heart-fill" style="color:#1a56db;"></i>Anggota Keluarga (1 KK)</h6>

                @if ($anggotaKeluarga->count() > 0)
                    <div>
                        @foreach ($anggotaKeluarga as $anggota)
                            <a href="{{ route('admin.penduduk.show', $anggota) }}"
                                class="anggota-kk-row text-decoration-none">
                                <div class="anggota-avatar {{ $anggota->jenis_kelamin === 'P' ? 'female' : '' }}">
                                    {{ strtoupper(substr($anggota->nama, 0, 2)) }}
                                </div>
                                <div style="flex-grow:1; min-width:0;">
                                    <div style="font-weight:600; color:#0f172a; font-size:.85rem;">{{ $anggota->nama }}
                                    </div>
                                    <div style="color:#64748b; font-size:.72rem;">
                                        {{ $anggota->usia }} thn ·
                                        {{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </div>
                                </div>
                                @php
                                    $hubWarna = match ($anggota->status_hubungan_keluarga) {
                                        'Kepala Keluarga' => 'background:#dbeafe;color:#1e40af;',
                                        'Istri' => 'background:#fce7f3;color:#9d174d;',
                                        'Anak' => 'background:#ecfdf5;color:#065f46;',
                                        'Orang Tua' => 'background:#fef3c7;color:#92400e;',
                                        default => 'background:#f1f5f9;color:#475569;',
                                    };
                                @endphp
                                <span class="badge-hubungan" style="{{ $hubWarna }}">
                                    {{ $anggota->status_hubungan_keluarga }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state" style="padding:1.5rem 1rem;">
                        <i class="bi bi-person" style="font-size:2rem; color:#cbd5e1;"></i>
                        <div style="color:#94a3b8; font-size:.82rem; margin-top:.5rem;">
                            Tidak ada anggota lain dalam KK ini
                        </div>
                    </div>
                @endif
            </div>

            <div class="detail-card">
                <h6><i class="bi bi-info-circle-fill" style="color:#1a56db;"></i>Informasi Sistem</h6>
                <div style="display:flex; flex-direction:column; gap:.85rem;">
                    <div class="info-item">
                        <div class="info-label">ID Database</div>
                        <div class="info-value mono">#{{ $penduduk->id }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Ditambahkan</div>
                        <div class="info-value" style="font-size:.82rem;">
                            {{ $penduduk->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Terakhir Diperbarui</div>
                        <div class="info-value" style="font-size:.82rem;">
                            {{ $penduduk->updated_at->isoFormat('D MMM YYYY, HH:mm') }}
                            <span style="color:#94a3b8;">({{ $penduduk->updated_at->diffForHumans() }})</span>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.penduduk.destroy', $penduduk) }}"
                onsubmit="return confirm('Yakin ingin menghapus data {{ $penduduk->nama }}? Data ini akan di-soft-delete dan bisa dipulihkan.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-primary-sm w-100"
                    style="background:#fff5f5;color:#dc2626;border:1.5px solid #fca5a5;justify-content:center;padding:.75rem;">
                    <i class="bi bi-trash"></i> Hapus Data Penduduk
                </button>
            </form>
        </div>
    </div>

@endsection
