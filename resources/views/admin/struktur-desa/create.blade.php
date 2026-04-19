@extends('layouts.app')
@section('title', 'Tambah Perangkat Desa')
@section('page-title', 'Tambah Perangkat Desa')

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
        }

        .form-label-custom {
            font-size: .82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: .35rem;
            display: block;
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

        .preview-foto {
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            border-radius: .65rem;
            border: 1.5px solid #e2e8f0;
            display: none;
            margin-top: .75rem;
        }

        .checkbox-custom {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .55rem .9rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
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
            <h5>Tambah Perangkat Desa</h5>
            <div class="sub">Tambahkan data perangkat pemerintahan desa</div>
        </div>
        <a href="{{ route('admin.struktur-desa.index') }}" class="btn-primary-sm" style="background:#64748b;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="error-alert">
            <h6><i class="bi bi-exclamation-triangle-fill"></i> Ada {{ $errors->count() }} kesalahan:</h6>
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.struktur-desa.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="form-card">
                    <h6><i class="bi bi-person-badge" style="color:#1a56db;"></i>Data Perangkat</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control-custom" value="{{ old('nama') }}"
                                placeholder="H. Abdul Rahman, S.Sos" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="jabatan" class="form-control-custom" value="{{ old('jabatan') }}"
                                placeholder="Kepala Desa" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Telepon</label>
                            <input type="text" name="telepon" class="form-control-custom" value="{{ old('telepon') }}"
                                placeholder="081234567890">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Urutan Tampil</label>
                            <input type="number" name="urutan" class="form-control-custom" value="{{ old('urutan', 0) }}"
                                min="0" max="999" placeholder="0">
                            <small style="font-size:.7rem;color:#94a3b8;">Angka kecil tampil duluan (contoh: Kepala Desa =
                                1)</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Keterangan</label>
                            <textarea name="keterangan" class="form-control-custom" rows="2"
                                placeholder="Periode 2022-2028 / Kepala Tata Usaha / dll">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-card">
                    <h6><i class="bi bi-gear" style="color:#1a56db;"></i>Pengaturan</h6>

                    <div class="mb-3">
                        <label class="form-label-custom">Foto</label>
                        <input type="file" name="foto" class="form-control-custom"
                            accept="image/jpeg,image/jpg,image/png,image/webp" onchange="previewImage(this,'previewFoto')">
                        <small style="font-size:.7rem;color:#94a3b8;display:block;margin-top:.25rem;">
                            Format: JPG, PNG, WEBP. Maksimal 2 MB.
                        </small>
                        <img id="previewFoto" class="preview-foto" alt="Preview">
                    </div>

                    <div class="mb-3">
                        <label class="checkbox-custom">
                            <input type="checkbox" name="tampil_publik" value="1"
                                {{ old('tampil_publik', true) ? 'checked' : '' }}>
                            <span style="font-size:.85rem;">Tampilkan di halaman publik</span>
                        </label>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn-primary-sm" style="justify-content:center;padding:.7rem;">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                    <a href="{{ route('admin.struktur-desa.index') }}" class="btn-primary-sm"
                        style="background:#f1f5f9;color:#64748b;justify-content:center;padding:.7rem;text-decoration:none;">Batal</a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            function previewImage(input, previewId) {
                const preview = document.getElementById(previewId);
                if (input.files && input.files[0]) {
                    if (input.files[0].size > 2 * 1024 * 1024) {
                        alert('Ukuran file melebihi 2 MB.');
                        input.value = '';
                        preview.style.display = 'none';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush

@endsection
