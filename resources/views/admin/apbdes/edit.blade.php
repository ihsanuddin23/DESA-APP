@extends('layouts.app')
@section('title', 'Edit APBDes ' . $apbdes->tahun)
@section('page-title', 'Edit APBDes')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Edit APBDes {{ $apbdes->tahun }}</h5>
            <div class="sub">Perbarui informasi anggaran</div>
        </div>
        <a href="{{ route('admin.apbdes.index') }}" class="btn-secondary-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="data-card">
        <div class="data-card-body">
            <form method="POST" action="{{ route('admin.apbdes.update', $apbdes) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tahun Anggaran <span class="text-danger">*</span></label>
                        <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                            value="{{ old('tahun', $apbdes->tahun) }}" min="2020" max="2099" required>
                        @error('tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status', $apbdes->status) === 'draft' ? 'selected' : '' }}>Draft
                            </option>
                            <option value="aktif" {{ old('status', $apbdes->status) === 'aktif' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="selesai" {{ old('status', $apbdes->status) === 'selesai' ? 'selected' : '' }}>
                                Selesai</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $apbdes->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-primary-sm">
                        <i class="bi bi-check-lg"></i> Update
                    </button>
                    <a href="{{ route('admin.apbdes.index') }}" class="btn-secondary-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>

@endsection
