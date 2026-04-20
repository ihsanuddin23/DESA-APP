@extends('layouts.app')
@section('title', $jadwal ? 'Edit Jadwal' : 'Tambah Jadwal')
@section('page-title', $jadwal ? 'Edit Jadwal' : 'Tambah Jadwal')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.posyandu.jadwal.index') }}"
                style="width:36px;height:36px;border-radius:.5rem;border:1.5px solid #e2e8f0;display:inline-flex;align-items:center;justify-content:center;color:#64748b;text-decoration:none;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5>{{ $jadwal ? 'Edit Jadwal' : 'Tambah Jadwal' }}</h5>
                <div class="sub">{{ $jadwal ? 'Ubah detail jadwal kunjungan' : 'Atur jadwal kegiatan posyandu baru' }}
                </div>
            </div>
        </div>
    </div>

    <form method="POST"
        action="{{ $jadwal ? route('admin.posyandu.jadwal.update', $jadwal) : route('admin.posyandu.jadwal.store') }}">
        @csrf
        @if ($jadwal)
            @method('PUT')
        @endif

        <div class="data-card mb-3">
            <div class="data-card-header">
                <h6><i class="bi bi-calendar-event me-2" style="color:#1a56db;"></i>Detail Jadwal</h6>
            </div>
            <div style="padding:1.5rem;">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            Posyandu <span class="text-danger">*</span>
                        </label>
                        <select name="posyandu_id" class="form-select @error('posyandu_id') is-invalid @enderror" required>
                            <option value="">— Pilih Posyandu —</option>
                            @foreach ($posyandu as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('posyandu_id', $jadwal?->posyandu_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} ({{ $p->kode }}) — RW {{ $p->rw }}
                                </option>
                            @endforeach
                        </select>
                        @error('posyandu_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            Tanggal <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal', $jadwal?->tanggal?->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">Waktu
                            Mulai</label>
                        <input type="time" name="waktu_mulai"
                            class="form-control @error('waktu_mulai') is-invalid @enderror"
                            value="{{ old('waktu_mulai', $jadwal?->waktu_mulai ? substr($jadwal->waktu_mulai, 0, 5) : '08:00') }}">
                        @error('waktu_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">Waktu
                            Selesai</label>
                        <input type="time" name="waktu_selesai"
                            class="form-control @error('waktu_selesai') is-invalid @enderror"
                            value="{{ old('waktu_selesai', $jadwal?->waktu_selesai ? substr($jadwal->waktu_selesai, 0, 5) : '11:00') }}">
                        @error('waktu_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            Kegiatan <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="kegiatan" class="form-control @error('kegiatan') is-invalid @enderror"
                            value="{{ old('kegiatan', $jadwal?->kegiatan) }}"
                            placeholder="Contoh: Penimbangan rutin, Imunisasi Campak" required>
                        @error('kegiatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach ($status as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('status', $jadwal?->status ?? 'terjadwal') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label" style="font-weight:600;font-size:.85rem;color:#334155;">Catatan</label>
                        <textarea name="catatan" rows="3" class="form-control @error('catatan') is-invalid @enderror"
                            placeholder="Catatan tambahan (opsional)...">{{ old('catatan', $jadwal?->catatan) }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-end">
            <a href="{{ route('admin.posyandu.jadwal.index') }}"
                style="padding:.55rem 1.1rem;border:1.5px solid #e2e8f0;border-radius:.5rem;color:#64748b;text-decoration:none;font-size:.85rem;font-weight:600;">
                Batal
            </a>
            <button type="submit" class="btn-primary-sm">
                <i class="bi bi-check-lg"></i>
                {{ $jadwal ? 'Simpan Perubahan' : 'Tambah Jadwal' }}
            </button>
        </div>
    </form>

@endsection
