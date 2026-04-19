@extends('layouts.public')
@section('title', 'Pengaduan Warga')

@push('styles')
    <style>
        .hero-aduan {
            background: linear-gradient(135deg, #1e40af 0%, #1a56db 100%);
            color: white;
            padding: 3rem 0 4rem;
            position: relative;
            overflow: hidden;
        }

        .hero-aduan::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, .08), transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .hero-aduan h1 {
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 2.25rem;
            margin-bottom: .5rem;
        }

        .hero-aduan .lead {
            opacity: .9;
            font-size: 1rem;
            max-width: 640px;
        }

        .form-aduan-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 24px rgba(15, 23, 42, .08);
            margin-top: -2.5rem;
            position: relative;
            z-index: 2;
        }

        .form-aduan-card h4 {
            font-family: 'Lora', serif;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: .25rem;
        }

        .form-aduan-card .sub {
            font-size: .88rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .form-label-aduan {
            font-size: .85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: .4rem;
        }

        .form-label-aduan .required {
            color: #dc2626;
        }

        .form-control-aduan {
            border: 1.5px solid #e2e8f0;
            border-radius: .55rem;
            padding: .65rem .85rem;
            font-size: .9rem;
            transition: border-color .15s, box-shadow .15s;
            width: 100%;
        }

        .form-control-aduan:focus {
            outline: none;
            border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, .1);
        }

        .form-control-aduan[disabled] {
            background: #f8fafc;
            cursor: not-allowed;
        }

        .form-hint {
            font-size: .75rem;
            color: #94a3b8;
            margin-top: .35rem;
        }

        .btn-submit-aduan {
            background: linear-gradient(135deg, #1a56db, #1e40af);
            color: white;
            border: none;
            padding: .85rem 2rem;
            font-size: .95rem;
            font-weight: 600;
            border-radius: .55rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: all .2s;
        }

        .btn-submit-aduan:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 86, 219, .3);
        }

        .alert-kode-tiket {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border: 2px solid #10b981;
            border-radius: .85rem;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-kode-tiket h5 {
            color: #065f46;
            font-weight: 700;
            margin: 0 0 .3rem 0;
            font-size: 1rem;
        }

        .alert-kode-tiket .kode {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.35rem;
            font-weight: 700;
            color: #047857;
            background: white;
            display: inline-block;
            padding: .4rem 1rem;
            border-radius: .45rem;
            margin-top: .5rem;
        }

        .lacak-card {
            background: white;
            border-radius: 1rem;
            padding: 1.75rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
        }

        .info-card {
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            border-radius: .5rem;
            padding: 1rem 1.25rem;
            font-size: .85rem;
            color: #075985;
            margin-bottom: 1.5rem;
        }

        .info-card strong {
            color: #0c4a6e;
        }

        .history-item {
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            gap: .75rem;
            align-items: flex-start;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-icon {
            width: 36px;
            height: 36px;
            border-radius: .5rem;
            background: #d1fae5;
            color: #065f46;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
        }

        .history-title {
            font-weight: 600;
            font-size: .88rem;
            color: #0f172a;
        }

        .history-meta {
            font-size: .75rem;
            color: #64748b;
            margin-top: .2rem;
        }

        .cat-icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .cat-icon-option {
            border: 1.5px solid #e2e8f0;
            border-radius: .55rem;
            padding: .75rem .5rem;
            text-align: center;
            cursor: pointer;
            transition: all .15s;
            background: white;
        }

        .cat-icon-option:hover {
            border-color: #1a56db;
            background: #eff6ff;
        }

        .cat-icon-option.active {
            border-color: #1a56db;
            background: #dbeafe;
        }

        .cat-icon-option i {
            font-size: 1.25rem;
            color: #1a56db;
            display: block;
            margin-bottom: .25rem;
        }

        .cat-icon-option span {
            font-size: .72rem;
            font-weight: 500;
            color: #334155;
        }
    </style>
@endpush

@section('content')

    <div class="hero-aduan">
        <div class="container" style="position:relative;z-index:1;">
            <h1><i class="bi bi-megaphone-fill me-2"></i>Pengaduan Warga</h1>
            <p class="lead">Sampaikan aspirasi, keluhan, atau saran Anda kepada pemerintah desa. Setiap aduan akan
                ditanggapi dengan serius.</p>
        </div>
    </div>

    <div class="container" style="margin-bottom: 3rem;">
        <div class="row g-4">
            {{-- ═══════ KOLOM KIRI: FORM PENGADUAN ═══════ --}}
            <div class="col-lg-8">
                <div class="form-aduan-card">

                    @if (session('success_kode'))
                        <div class="alert-kode-tiket">
                            <h5><i class="bi bi-check-circle-fill me-1"></i> Aduan Berhasil Dikirim!</h5>
                            <div style="font-size:.85rem; color:#065f46; margin-bottom:.3rem;">
                                Simpan kode tiket berikut untuk melacak status aduan Anda:
                            </div>
                            <div class="kode">{{ session('success_kode') }}</div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Mohon perbaiki kesalahan berikut:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h4>Formulir Pengaduan</h4>
                    <div class="sub">Isi form di bawah ini dengan lengkap agar aduan Anda dapat ditangani dengan cepat.
                    </div>

                    <div class="info-card">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>Privasi Anda Aman:</strong> Data pribadi Anda hanya digunakan untuk keperluan tindak lanjut
                        aduan dan tidak dipublikasikan.
                    </div>

                    <form method="POST" action="{{ route('aduan.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- ── Kategori ── --}}
                        <div class="mb-4">
                            <label class="form-label-aduan">Kategori Pengaduan <span class="required">*</span></label>
                            <div class="cat-icon-grid" id="catGrid">
                                @php
                                    $catIcons = [
                                        'infrastruktur' => 'bi-tools',
                                        'kebersihan' => 'bi-trash',
                                        'keamanan' => 'bi-shield-fill-exclamation',
                                        'pelayanan' => 'bi-building',
                                        'sosial' => 'bi-people-fill',
                                        'lingkungan' => 'bi-tree-fill',
                                        'lainnya' => 'bi-three-dots',
                                    ];
                                @endphp
                                @foreach (\App\Models\Pengaduan::KATEGORI as $key => $label)
                                    <label class="cat-icon-option {{ old('kategori') === $key ? 'active' : '' }}"
                                        data-kat="{{ $key }}">
                                        <input type="radio" name="kategori" value="{{ $key }}"
                                            {{ old('kategori') === $key ? 'checked' : '' }} style="display:none;" required>
                                        <i class="bi {{ $catIcons[$key] ?? 'bi-tag' }}"></i>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- ── Identitas ── --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label-aduan">Nama Lengkap <span class="required">*</span></label>
                                <input type="text" name="nama_pengadu" class="form-control-aduan"
                                    value="{{ old('nama_pengadu') }}" placeholder="Nama lengkap Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-aduan">No. HP / Email <span class="required">*</span></label>
                                <input type="text" name="kontak" class="form-control-aduan" value="{{ old('kontak') }}"
                                    placeholder="0812... atau email@..." required>
                                <div class="form-hint">Untuk kami hubungi jika perlu tindak lanjut</div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label-aduan">NIK <span
                                        style="font-weight:400;color:#94a3b8;">(opsional)</span></label>
                                <input type="text" name="nik" class="form-control-aduan" value="{{ old('nik') }}"
                                    maxlength="16" placeholder="16 digit NIK">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-aduan">RT</label>
                                <input type="text" name="rt" class="form-control-aduan" value="{{ old('rt') }}"
                                    maxlength="3" placeholder="001">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-aduan">RW</label>
                                <input type="text" name="rw" class="form-control-aduan" value="{{ old('rw') }}"
                                    maxlength="3" placeholder="001">
                            </div>
                        </div>

                        {{-- ── Isi Aduan ── --}}
                        <div class="mb-3">
                            <label class="form-label-aduan">Judul Aduan <span class="required">*</span></label>
                            <input type="text" name="judul" class="form-control-aduan" value="{{ old('judul') }}"
                                placeholder="Contoh: Jalan berlubang di depan gang masjid" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-aduan">Deskripsi / Isi Aduan <span class="required">*</span></label>
                            <textarea name="isi" class="form-control-aduan" rows="5"
                                placeholder="Jelaskan aduan Anda secara detail: kapan kejadiannya, di mana, dampaknya apa..." required>{{ old('isi') }}</textarea>
                            <div class="form-hint">Minimal 20 karakter</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-aduan">Lokasi Kejadian</label>
                            <input type="text" name="lokasi" class="form-control-aduan" value="{{ old('lokasi') }}"
                                placeholder="Contoh: Jl. Mawar No. 12 RT 001/RW 002">
                        </div>

                        <div class="mb-4">
                            <label class="form-label-aduan">Foto Bukti <span
                                    style="font-weight:400;color:#94a3b8;">(opsional)</span></label>
                            <input type="file" name="foto_bukti" class="form-control-aduan"
                                accept="image/jpeg,image/png,image/webp">
                            <div class="form-hint">Format: JPG, PNG, WEBP. Maksimal 4MB.</div>
                        </div>

                        <button type="submit" class="btn-submit-aduan">
                            <i class="bi bi-send-fill"></i> Kirim Pengaduan
                        </button>
                    </form>
                </div>
            </div>

            {{-- ═══════ KOLOM KANAN: LACAK + TRANSPARANSI ═══════ --}}
            <div class="col-lg-4">

                {{-- Lacak Status --}}
                <div class="lacak-card mb-4">
                    <h5 style="font-family:'Lora',serif;font-weight:700;color:#0f172a;">
                        <i class="bi bi-search" style="color:#1a56db;"></i> Lacak Pengaduan
                    </h5>
                    <p style="font-size:.82rem;color:#64748b;margin-bottom:1rem;">
                        Sudah pernah kirim aduan? Cek status tindak lanjutnya di sini.
                    </p>

                    <form method="GET" action="{{ route('aduan.lacak') }}">
                        <div class="mb-3">
                            <input type="text" name="kode_tiket" class="form-control-aduan"
                                placeholder="Contoh: PGD-20260419-A3F7" required>
                        </div>
                        <button type="submit" class="btn-submit-aduan" style="width:100%;justify-content:center;">
                            <i class="bi bi-arrow-right"></i> Lacak
                        </button>
                    </form>
                </div>

                {{-- Transparansi - 10 Pengaduan Selesai --}}
                @if ($pengaduanSelesai->count() > 0)
                    <div class="lacak-card">
                        <h5 style="font-family:'Lora',serif;font-weight:700;color:#0f172a;">
                            <i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Telah Ditangani
                        </h5>
                        <p style="font-size:.8rem;color:#64748b;margin-bottom:1rem;">
                            Pengaduan yang telah selesai ditindaklanjuti.
                        </p>

                        @foreach ($pengaduanSelesai as $p)
                            <div class="history-item">
                                <div class="history-icon">
                                    <i class="bi bi-check2"></i>
                                </div>
                                <div>
                                    <div class="history-title">{{ $p->judul }}</div>
                                    <div class="history-meta">
                                        {{ $p->kategori_label }} · Ditanggapi {{ $p->ditanggapi_pada?->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Visual feedback untuk pilihan kategori
        document.querySelectorAll('.cat-icon-option').forEach(function(el) {
            el.addEventListener('click', function() {
                document.querySelectorAll('.cat-icon-option').forEach(function(e) {
                    e.classList.remove('active');
                });
                el.classList.add('active');
            });
        });
    </script>
@endpush
