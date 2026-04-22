@extends('layouts.app')
@section('title', 'Tambah Agenda')
@section('page-title', 'Tambah Agenda')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Tambah Agenda</h5>
            <div class="sub">Buat jadwal kegiatan baru</div>
        </div>
        <a href="{{ route('admin.agenda.index') }}" class="btn-secondary-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="data-card">
        <div class="data-card-body">
            <form method="POST" action="{{ route('admin.agenda.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul') }}" placeholder="Contoh: Musyawarah Desa Tahun 2024" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_mulai"
                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                            value="{{ old('tanggal_mulai') }}" required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai"
                            class="form-control @error('tanggal_selesai') is-invalid @enderror"
                            value="{{ old('tanggal_selesai') }}">
                        <small class="text-muted">Kosongkan jika kegiatan hanya 1 hari</small>
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai"
                            class="form-control @error('waktu_mulai') is-invalid @enderror"
                            value="{{ old('waktu_mulai') }}">
                        @error('waktu_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai"
                            class="form-control @error('waktu_selesai') is-invalid @enderror"
                            value="{{ old('waktu_selesai') }}">
                        @error('waktu_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach (\App\Models\Agenda::kategoriOptions() as $key => $label)
                                <option value="{{ $key }}" {{ old('kategori') === $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="publikasi" {{ old('status') === 'publikasi' ? 'selected' : '' }}>Publikasi
                            </option>
                            <option value="selesai" {{ old('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ old('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                            value="{{ old('lokasi') }}" placeholder="Contoh: Balai Desa, Lapangan Desa, dll">
                        @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Penyelenggara</label>
                        <input type="text" name="penyelenggara"
                            class="form-control @error('penyelenggara') is-invalid @enderror"
                            value="{{ old('penyelenggara') }}" placeholder="Contoh: Pemerintah Desa, PKK, Karang Taruna">
                        @error('penyelenggara')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kontak Person</label>
                        <input type="text" name="kontak_person"
                            class="form-control @error('kontak_person') is-invalid @enderror"
                            value="{{ old('kontak_person') }}" placeholder="Nama PIC">
                        @error('kontak_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Telepon</label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                            value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx">
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="5"
                            placeholder="Jelaskan detail kegiatan...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Gambar</label>
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted">Format: JPG, PNG, WebP. Maks: 2MB</small>
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Opsi</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="is_highlight" value="1" class="form-check-input"
                                id="is_highlight" {{ old('is_highlight') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_highlight">
                                <i class="bi bi-star-fill text-warning me-1"></i> Tampilkan sebagai Highlight
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary-sm">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                    <a href="{{ route('admin.agenda.index') }}" class="btn-secondary-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>

@endsection
