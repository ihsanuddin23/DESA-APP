@extends('layouts.app')
@section('title', 'Edit Foto Galeri')
@section('page-title', 'Edit Foto Galeri')

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

        .foto-current {
            width: 100%;
            max-height: 350px;
            object-fit: contain;
            border-radius: .65rem;
            border: 1.5px solid #e2e8f0;
            margin-bottom: .75rem;
            background: #f8fafc;
        }

        .preview-foto {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: .65rem;
            border: 1.5px solid #e2e8f0;
            display: none;
            margin-top: .75rem;
            background: #f8fafc;
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
            <h5>Edit Foto Galeri</h5>
            <div class="sub">Perbarui informasi foto</div>
        </div>
        <a href="{{ route('admin.galeri.index') }}" class="btn-primary-sm" style="background:#64748b;">
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

    <form method="POST" action="{{ route('admin.galeri.update', $galeri) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="form-card">
                    <h6><i class="bi bi-image" style="color:#1a56db;"></i>Foto</h6>

                    @if ($galeri->file)
                        <label class="form-label-custom">Foto Saat Ini</label>
                        <img src="{{ Storage::url($galeri->file) }}" class="foto-current" alt="{{ $galeri->judul }}"
                            onerror="this.style.display='none';">
                    @endif

                    <div class="mb-3">
                        <label class="form-label-custom">Ganti Foto (opsional)</label>
                        <input type="file" name="file" class="form-control-custom"
                            accept="image/jpeg,image/jpg,image/png,image/webp" onchange="previewImage(this,'previewFoto')">
                        <small style="font-size:.7rem;color:#94a3b8;display:block;margin-top:.25rem;">
                            Kosongkan jika tidak mau ganti foto. Format: JPG, PNG, WEBP. Maksimal 4 MB.
                        </small>
                        <img id="previewFoto" class="preview-foto" alt="Preview">
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-card">
                    <h6><i class="bi bi-gear" style="color:#1a56db;"></i>Informasi</h6>

                    <div class="mb-3">
                        <label class="form-label-custom">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control-custom"
                            value="{{ old('judul', $galeri->judul) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Keterangan</label>
                        <textarea name="keterangan" class="form-control-custom" rows="3">{{ old('keterangan', $galeri->keterangan) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control-custom" required>
                            <option value="published"
                                {{ old('status', $galeri->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $galeri->status) === 'draft' ? 'selected' : '' }}>
                                Draft</option>
                        </select>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn-primary-sm" style="justify-content:center;padding:.7rem;">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.galeri.index') }}" class="btn-primary-sm"
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
                    if (input.files[0].size > 4 * 1024 * 1024) {
                        alert('Ukuran file melebihi 4 MB. Silakan pilih file yang lebih kecil.');
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
