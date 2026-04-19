@extends('layouts.app')
@section('title', 'Tambah Berita')
@section('page-title', 'Tambah Berita')

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
            max-height: 220px;
            object-fit: cover;
            border-radius: .65rem;
            border: 1.5px solid #e2e8f0;
            display: none;
            margin-top: .75rem;
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
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .error-alert ul {
            margin: 0;
            padding-left: 1.25rem;
            font-size: .82rem;
        }

        .error-alert li {
            margin-bottom: .25rem;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h5>Tambah Berita</h5>
            <div class="sub">Buat artikel berita baru untuk desa</div>
        </div>
        <a href="{{ route('admin.berita.index') }}" class="btn-primary-sm" style="background:#64748b;">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- ── Alert: Validation Errors ────────────────────────────────────────── --}}
    @if ($errors->any())
        <div class="error-alert">
            <h6><i class="bi bi-exclamation-triangle-fill"></i> Ada {{ $errors->count() }} kesalahan di form:</h6>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="form-card">
                    <h6><i class="bi bi-pencil-square" style="color:#1a56db;"></i>Informasi Berita</h6>

                    <div class="mb-3">
                        <label class="form-label-custom">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="judul"
                            class="form-control-custom @error('judul') border-danger @enderror" value="{{ old('judul') }}"
                            placeholder="Tulis judul berita..." required>
                        @error('judul')
                            <div class="text-danger" style="font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Ringkasan</label>
                        <textarea name="ringkasan" class="form-control-custom @error('ringkasan') border-danger @enderror" rows="2"
                            placeholder="Deskripsi singkat berita (opsional)...">{{ old('ringkasan') }}</textarea>
                        @error('ringkasan')
                            <div class="text-danger" style="font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Konten Berita <span class="text-danger">*</span></label>
                        <textarea name="konten" class="form-control-custom @error('konten') border-danger @enderror" rows="10"
                            placeholder="Tulis isi berita lengkap..." required>{{ old('konten') }}</textarea>
                        @error('konten')
                            <div class="text-danger" style="font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-card">
                    <h6><i class="bi bi-gear" style="color:#1a56db;"></i>Pengaturan</h6>

                    <div class="mb-3">
                        <label class="form-label-custom">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-control-custom @error('kategori') border-danger @enderror"
                            required>
                            @foreach (['umum', 'pemerintahan', 'kegiatan', 'pengumuman', 'pembangunan', 'kesehatan', 'ekonomi', 'sosial', 'pendidikan', 'pertanian'] as $kat)
                                <option value="{{ $kat }}" {{ old('kategori') === $kat ? 'selected' : '' }}>
                                    {{ ucfirst($kat) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Penulis</label>
                        <input type="text" name="penulis" class="form-control-custom"
                            value="{{ old('penulis', auth()->user()->name) }}" placeholder="Nama penulis">
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control-custom @error('status') border-danger @enderror"
                            required>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Tanggal Publish</label>
                        <input type="datetime-local" name="published_at" class="form-control-custom"
                            value="{{ old('published_at') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Foto Utama</label>
                        <input type="file" name="foto" class="form-control-custom"
                            accept="image/jpeg,image/jpg,image/png,image/webp" onchange="previewImage(this,'previewFoto')">
                        <small style="font-size:.7rem;color:#94a3b8;display:block;margin-top:.25rem;">
                            Format: JPG, PNG, WEBP. Maksimal 2 MB.
                        </small>
                        <img id="previewFoto" class="preview-foto" alt="Preview">
                        @error('foto')
                            <div class="text-danger" style="font-size:.78rem;margin-top:.3rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn-primary-sm" style="justify-content:center;padding:.7rem;">
                        <i class="bi bi-check-lg"></i> Simpan Berita
                    </button>
                    <a href="{{ route('admin.berita.index') }}" class="btn-primary-sm"
                        style="background:#f1f5f9;color:#64748b;justify-content:center;padding:.7rem;text-decoration:none;">
                        Batal
                    </a>
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
                        alert('Ukuran file melebihi 2 MB. Silakan pilih file yang lebih kecil.');
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

            document.addEventListener('DOMContentLoaded', function() {
                const errorAlert = document.querySelector('.error-alert');
                if (errorAlert) {
                    errorAlert.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });
        </script>
    @endpush
@endsection
