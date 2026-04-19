@extends('layouts.app')
@section('title', 'Profil Desa')
@section('page-title', 'Profil Desa')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .form-card {
            background: white;
            border-radius: .85rem;
            padding: 1.75rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            margin-bottom: 1.25rem;
        }

        .form-card h6 {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            padding-bottom: .75rem;
            border-bottom: 1px solid #f1f5f9;
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
            font-family: inherit;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, .1);
        }

        .form-hint {
            font-size: .75rem;
            color: #94a3b8;
            margin-top: .3rem;
        }

        .required {
            color: #dc2626;
        }

        .preview-image {
            max-height: 180px;
            max-width: 100%;
            border-radius: .55rem;
            border: 1.5px solid #e2e8f0;
            margin-top: .5rem;
        }

        textarea.form-control-custom {
            font-family: inherit;
            line-height: 1.5;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Kelola Profil Desa</h5>
            <div class="sub">Data profil, visi-misi, sejarah, dan kontak yang tampil di halaman publik</div>
        </div>
        <a href="{{ route('profil') }}" target="_blank" class="btn-primary-sm" style="background:#10b981;border-color:#10b981;">
            <i class="bi bi-eye"></i> Lihat Halaman Publik
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <strong>Mohon perbaiki:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profil-desa.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- ── IDENTITAS DESA ── --}}
        <div class="form-card">
            <h6><i class="bi bi-house-door-fill" style="color:#1a56db;"></i>Identitas Desa</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Nama Desa <span class="required">*</span></label>
                    <input type="text" name="nama_desa" class="form-control-custom"
                        value="{{ old('nama_desa', $profil->nama_desa) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Kode Desa</label>
                    <input type="text" name="kode_desa" class="form-control-custom"
                        value="{{ old('kode_desa', $profil->kode_desa) }}" placeholder="Contoh: 3216082011">
                    <div class="form-hint">Kode Kemendagri</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Tahun Berdiri</label>
                    <input type="text" name="tahun_berdiri" class="form-control-custom"
                        value="{{ old('tahun_berdiri', $profil->tahun_berdiri) }}" maxlength="4" placeholder="1980">
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Nama Kepala Desa</label>
                    <input type="text" name="kepala_desa" class="form-control-custom"
                        value="{{ old('kepala_desa', $profil->kepala_desa) }}" placeholder="H. Ahmad Sulaiman, S.Sos">
                </div>
            </div>
        </div>

        {{-- ── VISI & MISI ── --}}
        <div class="form-card">
            <h6><i class="bi bi-stars" style="color:#1a56db;"></i>Visi &amp; Misi</h6>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label-custom">Visi</label>
                    <textarea name="visi" class="form-control-custom" rows="3"
                        placeholder="Mewujudkan desa yang maju, mandiri, dan sejahtera...">{{ old('visi', $profil->visi) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Misi</label>
                    <textarea name="misi" class="form-control-custom" rows="6"
                        placeholder="1. Meningkatkan kualitas pelayanan publik&#10;2. Memberdayakan ekonomi masyarakat&#10;3. Membangun infrastruktur berkelanjutan">{{ old('misi', $profil->misi) }}</textarea>
                    <div class="form-hint">Tulis tiap poin di baris baru (akan otomatis diformat)</div>
                </div>
            </div>
        </div>

        {{-- ── SEJARAH & GEOGRAFIS ── --}}
        <div class="form-card">
            <h6><i class="bi bi-book-fill" style="color:#1a56db;"></i>Sejarah &amp; Geografis</h6>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label-custom">Sejarah Desa</label>
                    <textarea name="sejarah" class="form-control-custom" rows="8"
                        placeholder="Desa ... didirikan pada tahun ... oleh ...">{{ old('sejarah', $profil->sejarah) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Kondisi Geografis</label>
                    <textarea name="geografis" class="form-control-custom" rows="5"
                        placeholder="Batas wilayah, topografi, iklim, dll.">{{ old('geografis', $profil->geografis) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Gambaran Demografi</label>
                    <textarea name="demografi" class="form-control-custom" rows="4"
                        placeholder="Komposisi penduduk, budaya, bahasa, agama mayoritas, dll.">{{ old('demografi', $profil->demografi) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── STATISTIK WILAYAH ── --}}
        <div class="form-card">
            <h6><i class="bi bi-grid-3x3-gap-fill" style="color:#1a56db;"></i>Statistik Wilayah</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label-custom">Luas Wilayah (km²)</label>
                    <input type="number" name="luas_wilayah_km2" class="form-control-custom"
                        value="{{ old('luas_wilayah_km2', $profil->luas_wilayah_km2) }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Jumlah Dusun</label>
                    <input type="number" name="jumlah_dusun" class="form-control-custom"
                        value="{{ old('jumlah_dusun', $profil->jumlah_dusun) }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Jumlah RW</label>
                    <input type="number" name="jumlah_rw" class="form-control-custom"
                        value="{{ old('jumlah_rw', $profil->jumlah_rw) }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label-custom">Jumlah RT</label>
                    <input type="number" name="jumlah_rt" class="form-control-custom"
                        value="{{ old('jumlah_rt', $profil->jumlah_rt) }}" min="0">
                </div>
            </div>
        </div>

        {{-- ── KONTAK & PELAYANAN ── --}}
        <div class="form-card">
            <h6><i class="bi bi-telephone-fill" style="color:#1a56db;"></i>Kontak &amp; Jam Pelayanan</h6>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label-custom">Alamat Kantor</label>
                    <input type="text" name="alamat_kantor" class="form-control-custom"
                        value="{{ old('alamat_kantor', $profil->alamat_kantor) }}"
                        placeholder="Jl. Raya Cikedokan No. 1">
                </div>
                <div class="col-md-4">
                    <label class="form-label-custom">Telepon</label>
                    <input type="text" name="telepon" class="form-control-custom"
                        value="{{ old('telepon', $profil->telepon) }}" placeholder="(021) 1234-5678">
                </div>
                <div class="col-md-4">
                    <label class="form-label-custom">Email</label>
                    <input type="email" name="email" class="form-control-custom"
                        value="{{ old('email', $profil->email) }}" placeholder="desa@cikedokan.id">
                </div>
                <div class="col-md-4">
                    <label class="form-label-custom">Jam Pelayanan</label>
                    <input type="text" name="jam_pelayanan" class="form-control-custom"
                        value="{{ old('jam_pelayanan', $profil->jam_pelayanan) }}"
                        placeholder="Senin - Jumat, 08.00 - 16.00 WIB">
                </div>
            </div>
        </div>

        {{-- ── MEDIA ── --}}
        <div class="form-card">
            <h6><i class="bi bi-image-fill" style="color:#1a56db;"></i>Media</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Logo Desa</label>
                    <input type="file" name="logo" class="form-control-custom"
                        accept="image/jpeg,image/png,image/webp">
                    <div class="form-hint">Format JPG/PNG/WEBP, maks 2MB. Rekomendasi: rasio 1:1</div>
                    @if ($profil->logo)
                        <img src="{{ Storage::url($profil->logo) }}" class="preview-image" alt="Logo">
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Foto Kantor Desa</label>
                    <input type="file" name="foto_kantor" class="form-control-custom"
                        accept="image/jpeg,image/png,image/webp">
                    <div class="form-hint">Format JPG/PNG/WEBP, maks 4MB. Rekomendasi: landscape</div>
                    @if ($profil->foto_kantor)
                        <img src="{{ Storage::url($profil->foto_kantor) }}" class="preview-image" alt="Kantor">
                    @endif
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('dashboard.admin') }}" class="btn-primary-sm"
                style="background:#f1f5f9;color:#64748b;text-decoration:none;">
                Batal
            </a>
            <button type="submit" class="btn-primary-sm">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
        </div>
    </form>

@endsection
