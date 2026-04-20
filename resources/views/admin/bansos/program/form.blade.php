@extends('layouts.app')
@section('title', $program ? 'Edit Program Bansos' : 'Tambah Program Bansos')
@section('page-title', $program ? 'Edit Program Bansos' : 'Tambah Program Bansos')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.bansos.program.index') }}"
                style="width:36px;height:36px;border-radius:.5rem;border:1.5px solid #e2e8f0;display:inline-flex;align-items:center;justify-content:center;color:#64748b;text-decoration:none;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5>{{ $program ? 'Edit Program' : 'Tambah Program Bansos' }}</h5>
                <div class="sub">{{ $program ? $program->nama : 'Isi data program bantuan sosial baru' }}</div>
            </div>
        </div>
    </div>

    <div class="data-card">
        <div class="data-card-header">
            <h6><i class="bi bi-grid me-2" style="color:#1a56db;"></i>Informasi Program</h6>
        </div>

        <form method="POST"
            action="{{ $program ? route('admin.bansos.program.update', $program) : route('admin.bansos.program.store') }}"
            style="padding:1.5rem;">
            @csrf
            @if ($program)
                @method('PUT')
            @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                        Nama Program <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $program?->nama) }}" placeholder="Contoh: Program Keluarga Harapan (PKH)"
                        required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                        Kode <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
                        value="{{ old('kode', $program?->kode) }}" placeholder="PKH, BPNT, BLT" maxlength="20" required
                        style="text-transform:uppercase;font-family:monospace;">
                    @error('kode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                        Jenis Program <span class="text-danger">*</span>
                    </label>
                    <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                        <option value="">— Pilih Jenis —</option>
                        @foreach ($jenis as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('jenis', $program?->jenis) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                        Nominal per Bulan (Rp)
                    </label>
                    <input type="number" name="nominal_per_bulan"
                        class="form-control @error('nominal_per_bulan') is-invalid @enderror"
                        value="{{ old('nominal_per_bulan', $program?->nominal_per_bulan) }}" placeholder="0" min="0"
                        step="1000">
                    <div class="form-text" style="font-size:.75rem;">Nominal default per penerima per bulan</div>
                    @error('nominal_per_bulan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror"
                        placeholder="Keterangan singkat tentang program bansos...">{{ old('deskripsi', $program?->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                        Status <span class="text-danger">*</span>
                    </label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="aktif"
                            {{ old('status', $program?->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $program?->status) === 'nonaktif' ? 'selected' : '' }}>
                            Nonaktif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr style="margin:1.75rem 0;border-color:#f1f5f9;">

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('admin.bansos.program.index') }}"
                    style="padding:.55rem 1.1rem;border:1.5px solid #e2e8f0;border-radius:.5rem;color:#64748b;text-decoration:none;font-size:.85rem;font-weight:600;">
                    Batal
                </a>
                <button type="submit" class="btn-primary-sm">
                    <i class="bi bi-check-lg"></i>
                    {{ $program ? 'Simpan Perubahan' : 'Tambah Program' }}
                </button>
            </div>
        </form>
    </div>

@endsection
