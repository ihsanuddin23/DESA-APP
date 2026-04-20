@extends('layouts.app')
@section('title', $penerima ? 'Edit Penerima Bansos' : 'Tambah Penerima Bansos')
@section('page-title', $penerima ? 'Edit Penerima Bansos' : 'Tambah Penerima Bansos')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.bansos.penerima.index') }}"
                style="width:36px;height:36px;border-radius:.5rem;border:1.5px solid #e2e8f0;display:inline-flex;align-items:center;justify-content:center;color:#64748b;text-decoration:none;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5>{{ $penerima ? 'Edit Penerima' : 'Tambah Penerima Bansos' }}</h5>
                <div class="sub">{{ $penerima ? $penerima->nama_penerima : 'Isi data penerima bantuan sosial baru' }}
                </div>
            </div>
        </div>
    </div>

    <form method="POST"
        action="{{ $penerima ? route('admin.bansos.penerima.update', $penerima) : route('admin.bansos.penerima.store') }}">
        @csrf
        @if ($penerima)
            @method('PUT')
        @endif

        {{-- Informasi Program --}}
        <div class="data-card mb-3">
            <div class="data-card-header">
                <h6><i class="bi bi-grid me-2" style="color:#1a56db;"></i>Informasi Program</h6>
            </div>
            <div style="padding:1.5rem;">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            Program Bansos <span class="text-danger">*</span>
                        </label>
                        <select name="program_id" class="form-select @error('program_id') is-invalid @enderror" required>
                            <option value="">— Pilih Program —</option>
                            @foreach ($programs as $prog)
                                <option value="{{ $prog->id }}"
                                    {{ old('program_id', $penerima?->program_id) == $prog->id ? 'selected' : '' }}>
                                    {{ $prog->nama }} ({{ $prog->kode }})
                                </option>
                            @endforeach
                        </select>
                        @error('program_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            Tahun <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="tahun" class="form-control @error('tahun') is-invalid @enderror"
                            value="{{ old('tahun', $penerima?->tahun ?? date('Y')) }}" min="2000" max="2100"
                            required>
                        @error('tahun')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            Periode <span class="text-danger">*</span>
                        </label>
                        <select name="periode" class="form-select @error('periode') is-invalid @enderror" required>
                            @foreach ($periode as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('periode', $penerima?->periode ?? 'tahunan') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('periode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            Nominal (Rp)
                        </label>
                        <input type="number" name="nominal" class="form-control @error('nominal') is-invalid @enderror"
                            value="{{ old('nominal', $penerima?->nominal) }}" placeholder="0" min="0"
                            step="1000">
                        <div class="form-text" style="font-size:.75rem;">Kosongkan jika mengikuti nominal program</div>
                        @error('nominal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Identitas Penerima --}}
        <div class="data-card mb-3">
            <div class="data-card-header">
                <h6><i class="bi bi-person-badge me-2" style="color:#1a56db;"></i>Identitas Penerima</h6>
            </div>
            <div style="padding:1.5rem;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            NIK <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                            value="{{ old('nik', $penerima?->nik) }}" maxlength="16" placeholder="16 digit NIK" required
                            style="font-family:monospace;">
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_penerima"
                            class="form-control @error('nama_penerima') is-invalid @enderror"
                            value="{{ old('nama_penerima', $penerima?->nama_penerima) }}" placeholder="Nama sesuai KTP"
                            required>
                        @error('nama_penerima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            No. Kartu Keluarga
                        </label>
                        <input type="text" name="no_kk" class="form-control @error('no_kk') is-invalid @enderror"
                            value="{{ old('no_kk', $penerima?->no_kk) }}" maxlength="16" placeholder="16 digit No. KK"
                            style="font-family:monospace;">
                        @error('no_kk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">RT</label>
                        <input type="text" name="rt" class="form-control @error('rt') is-invalid @enderror"
                            value="{{ old('rt', $penerima?->rt) }}" maxlength="3" placeholder="001">
                        @error('rt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">RW</label>
                        <input type="text" name="rw" class="form-control @error('rw') is-invalid @enderror"
                            value="{{ old('rw', $penerima?->rw) }}" maxlength="3" placeholder="001">
                        @error('rw')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">Alamat</label>
                        <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                            value="{{ old('alamat', $penerima?->alamat) }}" placeholder="Alamat lengkap penerima">
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="data-card mb-3">
            <div class="data-card-header">
                <h6><i class="bi bi-toggle-on me-2" style="color:#1a56db;"></i>Status & Keterangan</h6>
            </div>
            <div style="padding:1.5rem;">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach ($status as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('status', $penerima?->status ?? 'aktif') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label"
                            style="font-weight:600;font-size:.85rem;color:#334155;">Keterangan</label>
                        <input type="text" name="keterangan"
                            class="form-control @error('keterangan') is-invalid @enderror"
                            value="{{ old('keterangan', $penerima?->keterangan) }}"
                            placeholder="Catatan tambahan jika ada">
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('admin.bansos.penerima.index') }}"
                style="padding:.55rem 1.1rem;border:1.5px solid #e2e8f0;border-radius:.5rem;color:#64748b;text-decoration:none;font-size:.85rem;font-weight:600;">
                Batal
            </a>
            <button type="submit" class="btn-primary-sm">
                <i class="bi bi-check-lg"></i>
                {{ $penerima ? 'Simpan Perubahan' : 'Tambah Penerima' }}
            </button>
        </div>
    </form>

@endsection
