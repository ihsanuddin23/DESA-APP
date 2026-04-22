@extends('layouts.app')
@section('title', 'Edit Agenda')
@section('page-title', 'Edit Agenda')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Edit Agenda</h5>
            <div class="sub">{{ Str::limit($agenda->judul, 50) }}</div>
        </div>
        <a href="{{ route('admin.agenda.index') }}" class="btn-secondary-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="data-card">
        <div class="data-card-body">
            <form method="POST" action="{{ route('admin.agenda.update', $agenda) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Judul Kegiatan <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul', $agenda->judul) }}" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_mulai"
                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                            value="{{ old('tanggal_mulai', $agenda->tanggal_mulai->format('Y-m-d')) }}" required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai"
                            class="form-control @error('tanggal_selesai') is-invalid @enderror"
                            value="{{ old('tanggal_selesai', $agenda->tanggal_selesai?->format('Y-m-d')) }}">
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai"
                            class="form-control @error('waktu_mulai') is-invalid @enderror"
                            value="{{ old('waktu_mulai', $agenda->waktu_mulai ? \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H:i') : '') }}">
                        @error('waktu_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai"
                            class="form-control @error('waktu_selesai') is-invalid @enderror"
                            value="{{ old('waktu_selesai', $agenda->waktu_selesai ? \Carbon\Carbon::parse($agenda->waktu_selesai)->format('H:i') : '') }}">
                        @error('waktu_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                            @foreach (\App\Models\Agenda::kategoriOptions() as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('kategori', $agenda->kategori) === $key ? 'selected' : '' }}>
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
                            <option value="draft" {{ old('status', $agenda->status) === 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="publikasi"
                                {{ old('status', $agenda->status) === 'publikasi' ? 'selected' : '' }}>Publikasi</option>
                            <option value="selesai" {{ old('status', $agenda->status) === 'selesai' ? 'selected' : '' }}>
                                Selesai</option>
                            <option value="dibatalkan"
                                {{ old('status', $agenda->status) === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                            value="{{ old('lokasi', $agenda->lokasi) }}">
                        @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Penyelenggara</label>
                        <input type="text" name="penyelenggara"
                            class="form-control @error('penyelenggara') is-invalid @enderror"
                            value="{{ old('penyelenggara', $agenda->penyelenggara) }}">
                        @error('penyelenggara')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kontak Person</label>
                        <input type="text" name="kontak_person"
                            class="form-control @error('kontak_person') is-invalid @enderror"
                            value="{{ old('kontak_person', $agenda->kontak_person) }}">
                        @error('kontak_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Telepon</label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                            value="{{ old('telepon', $agenda->telepon) }}">
                        @error('telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="5">{{ old('deskripsi', $agenda->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Gambar</label>
                        @if ($agenda->gambar_url)
                            <div class="mb-2">
                                <img src="{{ $agenda->gambar_url }}" alt="Current"
                                    style="max-width:200px;border-radius:.5rem;">
                            </div>
                        @endif
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
                        @error('gambar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Opsi</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" name="is_highlight" value="1" class="form-check-input"
                                id="is_highlight" {{ old('is_highlight', $agenda->is_highlight) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_highlight">
                                <i class="bi bi-star-fill text-warning me-1"></i> Tampilkan sebagai Highlight
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary-sm">
                        <i class="bi bi-check-lg"></i> Update
                    </button>
                    <a href="{{ route('admin.agenda.index') }}" class="btn-secondary-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>

@endsection
