@extends('layouts.app')
@section('title', 'Tambah Item APBDes')
@section('page-title', 'Tambah Item APBDes')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Tambah Item {{ ucfirst($jenis) }}</h5>
            <div class="sub">APBDes {{ $apbdes->tahun }}</div>
        </div>
        <a href="{{ route('admin.apbdes.items.index', ['apbdes' => $apbdes, 'jenis' => $jenis]) }}" class="btn-secondary-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="data-card">
        <div class="data-card-body">
            <form method="POST" action="{{ route('admin.apbdes.items.store', $apbdes) }}">
                @csrf
                <input type="hidden" name="jenis" value="{{ $jenis }}">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kode Rekening</label>
                        <input type="text" name="kode_rekening"
                            class="form-control @error('kode_rekening') is-invalid @enderror"
                            value="{{ old('kode_rekening') }}" placeholder="Contoh: 4.1.1.01">
                        @error('kode_rekening')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kategori</label>
                        <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                            value="{{ old('kategori') }}" placeholder="Contoh: Dana Desa">
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Urutan</label>
                        <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
                            value="{{ old('urutan', 0) }}" min="0">
                        @error('urutan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Uraian <span class="text-danger">*</span></label>
                        <input type="text" name="uraian" class="form-control @error('uraian') is-invalid @enderror"
                            value="{{ old('uraian') }}" placeholder="Deskripsi item anggaran" required>
                        @error('uraian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Anggaran (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="anggaran" class="form-control @error('anggaran') is-invalid @enderror"
                            value="{{ old('anggaran', 0) }}" min="0" step="1" required>
                        @error('anggaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Realisasi (Rp)</label>
                        <input type="number" name="realisasi" class="form-control @error('realisasi') is-invalid @enderror"
                            value="{{ old('realisasi', 0) }}" min="0" step="1">
                        @error('realisasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary-sm">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                    <a href="{{ route('admin.apbdes.items.index', ['apbdes' => $apbdes, 'jenis' => $jenis]) }}"
                        class="btn-secondary-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>

@endsection
