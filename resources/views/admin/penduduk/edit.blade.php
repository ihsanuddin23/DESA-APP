@extends('layouts.app')
@section('title', 'Edit Penduduk')
@section('page-title', 'Edit Penduduk')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .form-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: .85rem;
            padding: 1.75rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            margin-bottom: 1.25rem;
        }

        .form-card h6 {
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

        .form-label-custom {
            font-size: .82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: .35rem;
            display: block;
        }

        .form-label-custom .required {
            color: #dc2626;
        }

        .form-control-custom {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: .55rem;
            padding: .55rem .9rem;
            font-size: .875rem;
            color: #334155;
            background: #f8fafc;
            transition: border-color .2s;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #1a56db;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, .08);
        }

        .form-control-custom.mono {
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: .03em;
        }

        .form-hint {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: .25rem;
        }

        .radio-group {
            display: flex;
            gap: .5rem;
        }

        .radio-item {
            flex: 1;
            border: 1.5px solid #e2e8f0;
            border-radius: .55rem;
            padding: .55rem .75rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            background: #f8fafc;
            font-size: .85rem;
            font-weight: 500;
            color: #64748b;
            transition: all .2s;
        }

        .radio-item input {
            display: none;
        }

        .radio-item:has(input:checked) {
            border-color: #1a56db;
            background: #eff6ff;
            color: #1e40af;
            font-weight: 600;
        }

        .radio-item:hover {
            border-color: #1a56db;
        }

        .checkbox-custom {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem .9rem;
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: .55rem;
            cursor: pointer;
        }

        .error-alert {
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            border-radius: .65rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            color: #991b1b;
        }

        .error-alert h6 {
            margin: 0 0 .5rem 0;
            font-weight: 700;
            font-size: .9rem;
            border: none;
            padding: 0;
        }

        .error-alert ul {
            margin: 0;
            padding-left: 1.25rem;
            font-size: .82rem;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Edit Penduduk</h5>
            <div class="sub">Perbarui data {{ $penduduk->nama }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.penduduk.show', $penduduk) }}" class="btn-primary-sm" style="background:#64748b;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="error-alert">
            <h6><i class="bi bi-exclamation-triangle-fill"></i> Ada {{ $errors->count() }} kesalahan di form:</h6>
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.penduduk.update', $penduduk) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            {{-- ═══════ KOLOM KIRI ═══════ --}}
            <div class="col-lg-8">

                {{-- ── IDENTITAS ─────────────────────────────────────────── --}}
                <div class="form-card">
                    <h6><i class="bi bi-person-vcard" style="color:#1a56db;"></i>Identitas Pribadi</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">NIK <span class="required">*</span></label>
                            <input type="text" name="nik" class="form-control-custom mono"
                                value="{{ old('nik', $penduduk->nik) }}" maxlength="16" minlength="16" required
                                pattern="\d{16}" inputmode="numeric">
                            <div class="form-hint">Harus tepat 16 digit angka</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Nomor KK <span class="required">*</span></label>
                            <input type="text" name="no_kk" class="form-control-custom mono"
                                value="{{ old('no_kk', $penduduk->no_kk) }}" maxlength="16" minlength="16" required
                                pattern="\d{16}" inputmode="numeric">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label-custom">Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="nama" class="form-control-custom"
                                value="{{ old('nama', $penduduk->nama) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Kelamin <span class="required">*</span></label>
                            <div class="radio-group">
                                <label class="radio-item">
                                    <input type="radio" name="jenis_kelamin" value="L"
                                        {{ old('jenis_kelamin', $penduduk->jenis_kelamin) === 'L' ? 'checked' : '' }}
                                        required>
                                    <i class="bi bi-gender-male"></i> Laki-laki
                                </label>
                                <label class="radio-item">
                                    <input type="radio" name="jenis_kelamin" value="P"
                                        {{ old('jenis_kelamin', $penduduk->jenis_kelamin) === 'P' ? 'checked' : '' }}>
                                    <i class="bi bi-gender-female"></i> Perempuan
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Kewarganegaraan</label>
                            <select name="kewarganegaraan" class="form-control-custom">
                                <option value="WNI"
                                    {{ old('kewarganegaraan', $penduduk->kewarganegaraan) === 'WNI' ? 'selected' : '' }}>
                                    WNI (Indonesia)</option>
                                <option value="WNA"
                                    {{ old('kewarganegaraan', $penduduk->kewarganegaraan) === 'WNA' ? 'selected' : '' }}>
                                    WNA (Asing)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control-custom"
                                value="{{ old('tempat_lahir', $penduduk->tempat_lahir) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Lahir <span class="required">*</span></label>
                            <input type="date" name="tanggal_lahir" class="form-control-custom"
                                value="{{ old('tanggal_lahir', $penduduk->tanggal_lahir?->format('Y-m-d')) }}"
                                max="{{ date('Y-m-d') }}" required>
                            <div class="form-hint">Usia saat ini: {{ $penduduk->usia }} tahun (akan diupdate otomatis)
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── SOSIAL & PEKERJAAN ────────────────────────────────── --}}
                <div class="form-card">
                    <h6><i class="bi bi-briefcase-fill" style="color:#1a56db;"></i>Sosial & Pekerjaan</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Agama <span class="required">*</span></label>
                            <select name="agama" class="form-control-custom" required>
                                @foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'] as $a)
                                    <option value="{{ $a }}"
                                        {{ old('agama', $penduduk->agama) === $a ? 'selected' : '' }}>{{ $a }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Status Perkawinan <span class="required">*</span></label>
                            <select name="status_perkawinan" class="form-control-custom" required>
                                @foreach (['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $s)
                                    <option value="{{ $s }}"
                                        {{ old('status_perkawinan', $penduduk->status_perkawinan) === $s ? 'selected' : '' }}>
                                        {{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control-custom"
                                value="{{ old('pekerjaan', $penduduk->pekerjaan) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Pendidikan Terakhir</label>
                            <select name="pendidikan" class="form-control-custom">
                                <option value="">-- Pilih --</option>
                                @foreach (['Belum Sekolah', 'TK', 'SD', 'SMP', 'SMA', 'SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3', 'Lainnya'] as $p)
                                    <option value="{{ $p }}"
                                        {{ old('pendidikan', $penduduk->pendidikan) === $p ? 'selected' : '' }}>
                                        {{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ── ALAMAT LENGKAP (digabung dengan wilayah administratif) ── --}}
                <div class="form-card">
                    <h6><i class="bi bi-geo-alt-fill" style="color:#1a56db;"></i>Alamat Lengkap</h6>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Alamat Jalan / RT-RW</label>
                            <textarea name="alamat" class="form-control-custom" rows="2">{{ old('alamat', $penduduk->alamat) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">RT <span class="required">*</span></label>
                            <input type="text" name="rt" class="form-control-custom"
                                value="{{ old('rt', $penduduk->rt) }}" maxlength="3" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">RW</label>
                            <input type="text" name="rw" class="form-control-custom"
                                value="{{ old('rw', $penduduk->rw) }}" maxlength="3">
                        </div>

                        {{-- ── Wilayah Administratif ── --}}
                        <div class="col-12">
                            <hr style="margin:.5rem 0; border-color:#e2e8f0;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Provinsi</label>
                            <select name="provinsi_id" id="provinsi_id" class="form-control-custom">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach ($provinsi as $p)
                                    <option value="{{ $p->id }}"
                                        {{ old('provinsi_id', $penduduk->provinsi_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Kabupaten / Kota</label>
                            <select name="kabkota_id" id="kabkota_id" class="form-control-custom"
                                {{ $kabkota->isEmpty() ? 'disabled' : '' }}>
                                <option value="">-- Pilih Kab/Kota --</option>
                                @foreach ($kabkota as $kk)
                                    <option value="{{ $kk->id }}"
                                        {{ old('kabkota_id', $penduduk->kabkota_id) == $kk->id ? 'selected' : '' }}>
                                        {{ $kk->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Kecamatan</label>
                            <select name="kecamatan_id" id="kecamatan_id" class="form-control-custom"
                                {{ $kecamatan->isEmpty() ? 'disabled' : '' }}>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($kecamatan as $kec)
                                    <option value="{{ $kec->id }}"
                                        {{ old('kecamatan_id', $penduduk->kecamatan_id) == $kec->id ? 'selected' : '' }}>
                                        {{ $kec->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Kelurahan / Desa</label>
                            <select name="kelurahan_id" id="kelurahan_id" class="form-control-custom"
                                {{ $kelurahan->isEmpty() ? 'disabled' : '' }}>
                                <option value="">-- Pilih Kelurahan/Desa --</option>
                                @foreach ($kelurahan as $kel)
                                    <option value="{{ $kel->id }}"
                                        {{ old('kelurahan_id', $penduduk->kelurahan_id) == $kel->id ? 'selected' : '' }}>
                                        {{ $kel->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ═══════ KOLOM KANAN ═══════ --}}
            <div class="col-lg-4">

                {{-- ── KELUARGA ──────────────────────────────────────────── --}}
                <div class="form-card">
                    <h6><i class="bi bi-house-heart-fill" style="color:#1a56db;"></i>Hubungan Keluarga</h6>

                    <div class="mb-3">
                        <label class="form-label-custom">Status dalam Keluarga <span class="required">*</span></label>
                        <select name="status_hubungan_keluarga" class="form-control-custom" required>
                            @foreach (['Kepala Keluarga', 'Istri', 'Anak', 'Orang Tua', 'Famili Lain', 'Lainnya'] as $s)
                                <option value="{{ $s }}"
                                    {{ old('status_hubungan_keluarga', $penduduk->status_hubungan_keluarga) === $s ? 'selected' : '' }}>
                                    {{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- ── STATUS ────────────────────────────────────────────── --}}
                <div class="form-card">
                    <h6><i class="bi bi-toggle-on" style="color:#1a56db;"></i>Status Data</h6>

                    <label class="checkbox-custom">
                        <input type="checkbox" name="status_aktif" value="1"
                            {{ old('status_aktif', $penduduk->status_aktif) ? 'checked' : '' }}>
                        <div>
                            <div style="font-size:.88rem; font-weight:600; color:#065f46;">Penduduk Aktif</div>
                            <div style="font-size:.72rem; color:#047857;">Masih berdomisili di desa ini</div>
                        </div>
                    </label>
                </div>

                {{-- ── TOMBOL AKSI ───────────────────────────────────────── --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn-primary-sm"
                        style="justify-content:center;padding:.8rem;font-weight:600;">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.penduduk.show', $penduduk) }}" class="btn-primary-sm"
                        style="background:#f1f5f9;color:#64748b;justify-content:center;padding:.8rem;text-decoration:none;">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            (function() {
                const ENDPOINTS = {
                    kabkota: "{{ route('admin.api.wilayah.kabkota') }}",
                    kecamatan: "{{ route('admin.api.wilayah.kecamatan') }}",
                    kelurahan: "{{ route('admin.api.wilayah.kelurahan') }}",
                };

                const $prov = document.getElementById('provinsi_id');
                const $kab = document.getElementById('kabkota_id');
                const $kec = document.getElementById('kecamatan_id');
                const $kel = document.getElementById('kelurahan_id');

                async function loadOptions(endpoint, params, target, placeholder) {
                    target.disabled = true;
                    target.innerHTML = `<option value="">Memuat...</option>`;
                    try {
                        const qs = new URLSearchParams(params).toString();
                        const res = await fetch(`${endpoint}?${qs}`, {
                            headers: {
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin',
                        });
                        if (!res.ok) throw new Error(`HTTP ${res.status}`);
                        const data = await res.json();
                        target.innerHTML = `<option value="">${placeholder}</option>`;
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.nama;
                            target.appendChild(opt);
                        });
                        target.disabled = false;
                    } catch (err) {
                        console.error('Gagal memuat wilayah:', err);
                        target.innerHTML = `<option value="">Gagal memuat data</option>`;
                    }
                }

                function resetDropdown(target, placeholder) {
                    target.innerHTML = `<option value="">${placeholder}</option>`;
                    target.disabled = true;
                }

                $prov.addEventListener('change', () => {
                    resetDropdown($kec, '-- Pilih kab/kota dulu --');
                    resetDropdown($kel, '-- Pilih kecamatan dulu --');
                    if ($prov.value) {
                        loadOptions(ENDPOINTS.kabkota, {
                            provinsi_id: $prov.value
                        }, $kab, '-- Pilih Kab/Kota --');
                    } else {
                        resetDropdown($kab, '-- Pilih provinsi dulu --');
                    }
                });

                $kab.addEventListener('change', () => {
                    resetDropdown($kel, '-- Pilih kecamatan dulu --');
                    if ($kab.value) {
                        loadOptions(ENDPOINTS.kecamatan, {
                            kabkota_id: $kab.value
                        }, $kec, '-- Pilih Kecamatan --');
                    } else {
                        resetDropdown($kec, '-- Pilih kab/kota dulu --');
                    }
                });

                $kec.addEventListener('change', () => {
                    if ($kec.value) {
                        loadOptions(ENDPOINTS.kelurahan, {
                            kecamatan_id: $kec.value
                        }, $kel, '-- Pilih Kelurahan/Desa --');
                    } else {
                        resetDropdown($kel, '-- Pilih kecamatan dulu --');
                    }
                });
            })();
        </script>
    @endpush

@endsection
