@extends('layouts.app')
@section('title', 'Edit Item APBDes')
@section('page-title', 'Edit Item APBDes')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Edit Item {{ ucfirst($item->jenis) }}</h5>
            <div class="sub">APBDes {{ $apbdes->tahun }}</div>
        </div>
        <a href="{{ route('admin.apbdes.items.index', ['apbdes' => $apbdes, 'jenis' => $item->jenis]) }}"
            class="btn-secondary-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="data-card">
        <div class="data-card-body">
            <form method="POST" action="{{ route('admin.apbdes.items.update', ['apbdes' => $apbdes, 'item' => $item]) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select" required>
                            <option value="pendapatan" {{ old('jenis', $item->jenis) === 'pendapatan' ? 'selected' : '' }}>
                                Pendapatan</option>
                            <option value="belanja" {{ old('jenis', $item->jenis) === 'belanja' ? 'selected' : '' }}>Belanja
                            </option>
                            <option value="pembiayaan" {{ old('jenis', $item->jenis) === 'pembiayaan' ? 'selected' : '' }}>
                                Pembiayaan</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kode Rekening</label>
                        <input type="text" name="kode_rekening" class="form-control"
                            value="{{ old('kode_rekening', $item->kode_rekening) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <input type="text" name="kategori" class="form-control"
                            value="{{ old('kategori', $item->kategori) }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Urutan</label>
                        <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $item->urutan) }}"
                            min="0">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Uraian <span class="text-danger">*</span></label>
                        <input type="text" name="uraian" class="form-control @error('uraian') is-invalid @enderror"
                            value="{{ old('uraian', $item->uraian) }}" required>
                        @error('uraian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Anggaran (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="anggaran" class="form-control"
                            value="{{ old('anggaran', $item->anggaran) }}" min="0" step="1" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Realisasi (Rp)</label>
                        <input type="number" name="realisasi" class="form-control"
                            value="{{ old('realisasi', $item->realisasi) }}" min="0" step="1">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $item->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary-sm">
                        <i class="bi bi-check-lg"></i> Update
                    </button>
                    <a href="{{ route('admin.apbdes.items.index', ['apbdes' => $apbdes, 'jenis' => $item->jenis]) }}"
                        class="btn-secondary-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>

@endsection
