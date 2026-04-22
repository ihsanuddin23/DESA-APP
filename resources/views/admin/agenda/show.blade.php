@extends('layouts.app')
@section('title', 'Detail Agenda')
@section('page-title', 'Detail Agenda')

@push('styles')
    @include('admin._admin-styles')
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Detail Agenda</h5>
            <div class="sub">{{ Str::limit($agenda->judul, 50) }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.agenda.edit', $agenda) }}" class="btn-primary-sm">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.agenda.index') }}" class="btn-secondary-sm">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="data-card">
                @if ($agenda->gambar_url)
                    <img src="{{ $agenda->gambar_url }}" alt="{{ $agenda->judul }}"
                        style="width:100%;height:250px;object-fit:cover;">
                @endif
                <div class="data-card-body">
                    <div class="d-flex gap-2 mb-3">
                        <span class="badge"
                            style="background:{{ $agenda->kategori_bg }};color:{{ $agenda->kategori_color }};">
                            {{ $agenda->kategori_label }}
                        </span>
                        <span class="status-badge {{ $agenda->status_badge }}">
                            <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>{{ $agenda->status_label }}
                        </span>
                        @if ($agenda->is_highlight)
                            <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Highlight</span>
                        @endif
                    </div>

                    <h4 class="fw-bold mb-3">{{ $agenda->judul }}</h4>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex gap-2 align-items-start">
                                <div
                                    style="width:36px;height:36px;background:#eff6ff;border-radius:.5rem;display:flex;align-items:center;justify-content:center;color:#1a56db;">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div>
                                    <div style="font-size:.75rem;color:#64748b;">Tanggal</div>
                                    <div style="font-weight:600;">{{ $agenda->tanggal_format }}</div>
                                </div>
                            </div>
                        </div>
                        @if ($agenda->waktu_format)
                            <div class="col-md-6">
                                <div class="d-flex gap-2 align-items-start">
                                    <div
                                        style="width:36px;height:36px;background:#eff6ff;border-radius:.5rem;display:flex;align-items:center;justify-content:center;color:#1a56db;">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.75rem;color:#64748b;">Waktu</div>
                                        <div style="font-weight:600;">{{ $agenda->waktu_format }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($agenda->lokasi)
                            <div class="col-md-6">
                                <div class="d-flex gap-2 align-items-start">
                                    <div
                                        style="width:36px;height:36px;background:#eff6ff;border-radius:.5rem;display:flex;align-items:center;justify-content:center;color:#1a56db;">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.75rem;color:#64748b;">Lokasi</div>
                                        <div style="font-weight:600;">{{ $agenda->lokasi }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($agenda->penyelenggara)
                            <div class="col-md-6">
                                <div class="d-flex gap-2 align-items-start">
                                    <div
                                        style="width:36px;height:36px;background:#eff6ff;border-radius:.5rem;display:flex;align-items:center;justify-content:center;color:#1a56db;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:.75rem;color:#64748b;">Penyelenggara</div>
                                        <div style="font-weight:600;">{{ $agenda->penyelenggara }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($agenda->deskripsi)
                        <h6 class="fw-bold mb-2">Deskripsi</h6>
                        <div style="color:#475569;line-height:1.8;">
                            {!! nl2br(e($agenda->deskripsi)) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Kontak --}}
            @if ($agenda->kontak_person || $agenda->telepon)
                <div class="data-card mb-3">
                    <div class="data-card-header">
                        <h6 class="mb-0"><i class="bi bi-person-lines-fill text-primary me-2"></i>Kontak Person</h6>
                    </div>
                    <div class="data-card-body">
                        @if ($agenda->kontak_person)
                            <div class="mb-2">
                                <div style="font-size:.75rem;color:#64748b;">Nama</div>
                                <div style="font-weight:600;">{{ $agenda->kontak_person }}</div>
                            </div>
                        @endif
                        @if ($agenda->telepon)
                            <div>
                                <div style="font-size:.75rem;color:#64748b;">Telepon</div>
                                <div style="font-weight:600;">
                                    <a href="tel:{{ $agenda->telepon }}">{{ $agenda->telepon }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Info --}}
            <div class="data-card">
                <div class="data-card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle text-primary me-2"></i>Informasi</h6>
                </div>
                <div class="data-card-body">
                    <div class="mb-2">
                        <div style="font-size:.75rem;color:#64748b;">Dibuat oleh</div>
                        <div style="font-weight:500;">{{ $agenda->creator?->name ?? '-' }}</div>
                    </div>
                    <div class="mb-2">
                        <div style="font-size:.75rem;color:#64748b;">Dibuat pada</div>
                        <div style="font-weight:500;">{{ $agenda->created_at->translatedFormat('d F Y, H:i') }}</div>
                    </div>
                    <div>
                        <div style="font-size:.75rem;color:#64748b;">Terakhir diupdate</div>
                        <div style="font-weight:500;">{{ $agenda->updated_at->translatedFormat('d F Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
