@extends('layouts.public')

@section('title', 'Kontak')

@section('content')

    {{-- ── HERO SECTION ─────────────────────────────────────────────────────────── --}}
    <section class="sid-hero">
        <div class="container">
            <div class="sid-hero-inner text-center">
                <span class="sid-hero-badge">
                    <i class="bi bi-telephone-fill me-1"></i>
                    Hubungi Kami
                </span>
                <h1 class="sid-hero-title">
                    Kontak <em>{{ config('sid.nama_desa', 'Desa Sukamaju') }}</em>
                </h1>
                <p class="sid-hero-lead">
                    Punya pertanyaan, saran, atau keluhan? Silakan hubungi kami melalui
                    saluran komunikasi resmi di bawah ini.
                </p>
            </div>
        </div>
    </section>

    {{-- ── INFO KONTAK ──────────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Saluran <span>Komunikasi</span></h2>
            </div>

            <div class="row g-3">
                {{-- Telepon --}}
                <div class="col-md-6 col-lg-3">
                    <div class="sid-layanan-card h-100 text-center">
                        <div class="sid-layanan-icon mx-auto" style="background: #E6F1FB;">
                            <i class="bi bi-telephone-fill" style="color: #2563eb;"></i>
                        </div>
                        <h3 class="sid-layanan-judul">Telepon</h3>
                        <p class="sid-layanan-desc">{{ config('sid.telepon') }}</p>
                        <small class="text-muted">Senin–Jumat, 08.00–15.00 WIB</small>
                    </div>
                </div>

                {{-- Email --}}
                <div class="col-md-6 col-lg-3">
                    <div class="sid-layanan-card h-100 text-center">
                        <div class="sid-layanan-icon mx-auto" style="background: #FDECEA;">
                            <i class="bi bi-envelope-fill" style="color: #dc2626;"></i>
                        </div>
                        <h3 class="sid-layanan-judul">Email</h3>
                        <p class="sid-layanan-desc">{{ config('sid.email') }}</p>
                        <small class="text-muted">Balasan 1–2 hari kerja</small>
                    </div>
                </div>

                {{-- WhatsApp --}}
                <div class="col-md-6 col-lg-3">
                    <div class="sid-layanan-card h-100 text-center">
                        <div class="sid-layanan-icon mx-auto" style="background: #E8F5EE;">
                            <i class="bi bi-whatsapp" style="color: #25D366;"></i>
                        </div>
                        <h3 class="sid-layanan-judul">WhatsApp</h3>
                        <p class="sid-layanan-desc">{{ config('sid.telepon') }}</p>
                        <small class="text-muted">Respon cepat di jam kerja</small>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="col-md-6 col-lg-3">
                    <div class="sid-layanan-card h-100 text-center">
                        <div class="sid-layanan-icon mx-auto" style="background: #FEF9E7;">
                            <i class="bi bi-geo-alt-fill" style="color: #d97706;"></i>
                        </div>
                        <h3 class="sid-layanan-judul">Alamat</h3>
                        <p class="sid-layanan-desc">{{ config('sid.alamat') }}</p>
                        <small class="text-muted">Kantor Desa</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── FORM KONTAK ──────────────────────────────────────────────────────────── --}}
    <section class="sid-section">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Kirim <span>Pesan</span></h2>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="sid-data-card">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-pencil-square text-success me-2"></i>
                            Formulir Pesan
                        </h5>
                        <p class="text-muted mb-4">
                            Isi formulir di bawah ini dan kami akan menghubungi Anda kembali
                            melalui email atau telepon yang Anda berikan.
                        </p>

                        {{-- Form statis — belum terhubung ke backend --}}
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg" placeholder="Nama Anda"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-control-lg"
                                        placeholder="email@contoh.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor Telepon</label>
                                    <input type="tel" class="form-control form-control-lg" placeholder="08xx-xxxx-xxxx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kategori <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option>Pertanyaan Umum</option>
                                        <option>Layanan Administrasi</option>
                                        <option>Saran & Masukan</option>
                                        <option>Keluhan</option>
                                        <option>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Subjek <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg"
                                        placeholder="Judul pesan Anda" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" rows="5" placeholder="Tulis pesan Anda di sini..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="sid-btn-primer">
                                        <i class="bi bi-send-fill me-2"></i>Kirim Pesan
                                    </button>
                                    <small class="text-muted d-block mt-2">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Fitur pengiriman pesan dalam tahap pengembangan.
                                    </small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Info & Jam Pelayanan --}}
                <div class="col-lg-5">
                    <div class="sid-data-card mb-3"
                        style="background: linear-gradient(135deg, #2d8659, #1e6b44); color: white;">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-clock-fill me-2"></i>Jam Pelayanan
                        </h5>
                        <div class="d-flex justify-content-between py-2"
                            style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                            <span>Senin – Kamis</span>
                            <span class="fw-semibold">08.00 – 15.00</span>
                        </div>
                        <div class="d-flex justify-content-between py-2"
                            style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                            <span>Jumat</span>
                            <span class="fw-semibold">08.00 – 11.00</span>
                        </div>
                        <div class="d-flex justify-content-between py-2"
                            style="border-bottom: 1px solid rgba(255,255,255,0.2);">
                            <span>Sabtu</span>
                            <span class="fw-semibold">Tutup</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span>Minggu & Hari Libur</span>
                            <span class="fw-semibold">Tutup</span>
                        </div>
                    </div>

                    <div class="sid-data-card">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Darurat
                        </h5>
                        <p class="text-muted mb-3">
                            Untuk situasi darurat di luar jam kerja, silakan hubungi:
                        </p>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-telephone-fill text-success me-2"></i>
                            <span>Kepala Desa: <strong>{{ config('sid.telepon') }}</strong></span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shield-fill-check text-primary me-2"></i>
                            <span>Polsek: <strong>110</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── PETA LOKASI ──────────────────────────────────────────────────────────── --}}
    <section class="sid-section sid-section-putih">
        <div class="container">
            <div class="sid-section-header">
                <h2 class="sid-section-title">Lokasi <span>Kantor Desa</span></h2>
            </div>

            <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                <iframe
                    src="https://maps.google.com/maps?q={{ config('sid.lat') }},{{ config('sid.lng') }}&z=15&output=embed"
                    width="100%" height="450" style="border:0;" allowfullscreen loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

@endsection
