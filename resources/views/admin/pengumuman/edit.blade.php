@extends('layouts.app')
@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')

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

        .lampiran-info {
            background: #f0f9ff;
            border: 1.5px solid #bae6fd;
            border-radius: .55rem;
            padding: .6rem .9rem;
            font-size: .8rem;
            color: #075985;
            margin-bottom: .5rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Edit Pengumuman</h5>
            <div class="sub">Perbarui informasi pengumuman</div>
        </div>
        <a href="{{ route('admin.pengumuman.index') }}" class="btn-primary-sm" style="background:#64748b;">
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

    <form method="POST" action="{{ route('admin.pengumuman.update', $pengumuman) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="form-card">
                    <h6><i class="bi bi-pencil-square" style="color:#1a56db;"></i>Isi Pengumuman</h6>

                    <div class="mb-3">
                        <label class="form-label-custom">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control-custom"
                            value="{{ old('judul', $pengumuman->judul) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Isi Pengumuman</label>
                        <textarea name="isi" class="form-control-custom" rows="8">{{ old('isi', $pengumuman->isi) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-card">
                    <h6><i class="bi bi-gear" style="color:#1a56db;"></i>Pengaturan</h6>

                    <div class="mb-3">
                        <label class="form-label-custom">Prioritas <span class="text-danger">*</span></label>
                        <select name="prioritas" class="form-control-custom" required>
                            <option value="umum"
                                {{ old('prioritas', $pengumuman->prioritas) === 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="info"
                                {{ old('prioritas', $pengumuman->prioritas) === 'info' ? 'selected' : '' }}>Info</option>
                            <option value="penting"
                                {{ old('prioritas', $pengumuman->prioritas) === 'penting' ? 'selected' : '' }}>Penting
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control-custom" required>
                            <option value="aktif" {{ old('status', $pengumuman->status) === 'aktif' ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="nonaktif"
                                {{ old('status', $pengumuman->status) === 'nonaktif' ? 'selected' : '' }}>Non-aktif
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Berlaku Hingga</label>
                        <input type="datetime-local" name="berlaku_hingga" class="form-control-custom"
                            value="{{ old('berlaku_hingga', $pengumuman->berlaku_hingga?->format('Y-m-d\TH:i')) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Lampiran</label>
                        @if ($pengumuman->file_lampiran)
                            <div class="lampiran-info">
                                <i class="bi bi-paperclip"></i>
                                <a href="{{ Storage::url($pengumuman->file_lampiran) }}" target="_blank"
                                    style="color:#075985;flex-grow:1;text-decoration:underline;">
                                    Lihat lampiran saat ini
                                </a>
                            </div>
                        @endif
                        <input type="file" name="file_lampiran" class="form-control-custom"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small style="font-size:.7rem;color:#94a3b8;display:block;margin-top:.25rem;">
                            {{ $pengumuman->file_lampiran ? 'Unggah file baru untuk mengganti. ' : '' }}Format: PDF, DOC,
                            JPG, PNG. Maksimal 5 MB.
                        </small>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn-primary-sm" style="justify-content:center;padding:.7rem;">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.pengumuman.index') }}" class="btn-primary-sm"
                        style="background:#f1f5f9;color:#64748b;justify-content:center;padding:.7rem;text-decoration:none;">Batal</a>
                </div>
            </div>
        </div>
    </form>

@endsection
