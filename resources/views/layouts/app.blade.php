<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">

    {{-- PRE-LOAD THEME (cegah FOUC: flash-of-unstyled-content saat switch tema) --}}
    <script>
        (function() {
            try {
                var t = localStorage.getItem('sid_theme_preference') || 'light';
                document.documentElement.setAttribute('data-theme', t);
            } catch (e) {}
        })();
    </script>

    <title>@yield('title', 'Dashboard') — SID App</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Custom App CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- ═══════ NOTIFICATION BELL CSS (inline agar pasti ter-load) ═══════ --}}
    <style>
        /* ── Bell Button Container ── */
        .notif-wrapper {
            position: relative !important;
            display: inline-block !important;
            vertical-align: middle;
            margin-right: .5rem;
        }

        .notif-bell-btn {
            position: relative !important;
            background: transparent;
            border: 1.5px solid #e2e8f0;
            border-radius: .55rem;
            width: 40px;
            height: 40px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #475569;
            transition: all .15s;
            padding: 0 !important;
            line-height: 1;
        }

        .notif-bell-btn:hover {
            background: #f1f5f9;
            color: #1a56db;
            border-color: #cbd5e1;
        }

        .notif-bell-btn i {
            font-size: 1.1rem;
            line-height: 1;
        }

        .notif-badge {
            position: absolute !important;
            top: -4px !important;
            right: -4px !important;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            background: #dc2626;
            color: white !important;
            font-size: .65rem;
            font-weight: 700;
            border-radius: 99px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            line-height: 1;
            border: 2px solid white;
            z-index: 2;
        }

        .notif-dropdown {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 360px;
            max-width: calc(100vw - 2rem);
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: .75rem;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, .15), 0 4px 10px -3px rgba(15, 23, 42, .08);
            display: none !important;
            z-index: 9999 !important;
            overflow: hidden;
        }

        .notif-dropdown.show {
            display: block !important;
        }

        .notif-dropdown-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .85rem 1.1rem;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
        }

        .notif-dropdown-header strong {
            font-size: .92rem;
            color: #0f172a;
        }

        .notif-unread-text {
            font-size: .72rem;
            color: #64748b;
            margin-left: .35rem;
        }

        .notif-actions {
            display: flex;
            gap: .3rem;
        }

        .notif-action-btn {
            background: transparent;
            border: none;
            color: #64748b;
            font-size: .9rem;
            cursor: pointer;
            padding: .3rem .5rem;
            border-radius: .35rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
        }

        .notif-action-btn:hover {
            background: #e2e8f0;
            color: #1a56db;
        }

        .notif-dropdown-body {
            max-height: 420px;
            overflow-y: auto;
        }

        .notif-loading {
            padding: 2rem 1rem;
            text-align: center;
            color: #94a3b8;
            font-size: .85rem;
        }

        .notif-spin {
            animation: notifSpin 1s linear infinite;
            display: inline-block;
        }

        @keyframes notifSpin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .notif-empty {
            padding: 2.5rem 1rem;
            text-align: center;
            color: #cbd5e1;
        }

        .notif-empty i {
            font-size: 2.25rem;
            display: block;
            margin-bottom: .5rem;
        }

        .notif-empty-text {
            font-size: .82rem;
            color: #94a3b8;
        }

        .notif-item {
            display: flex;
            gap: .75rem;
            padding: .85rem 1.1rem;
            border-bottom: 1px solid #f1f5f9;
            text-decoration: none;
            color: inherit;
            transition: background .15s;
            cursor: pointer;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .notif-item.unread {
            background: #eff6ff;
        }

        .notif-item.unread:hover {
            background: #dbeafe;
        }

        .notif-item-icon {
            width: 36px;
            height: 36px;
            border-radius: .5rem;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .notif-item-icon.success {
            background: #d1fae5;
            color: #065f46;
        }

        .notif-item-icon.info {
            background: #dbeafe;
            color: #1e40af;
        }

        .notif-item-icon.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .notif-item-icon.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .notif-item-body {
            flex-grow: 1;
            min-width: 0;
        }

        .notif-item-title {
            font-weight: 600;
            font-size: .82rem;
            color: #0f172a;
            margin-bottom: .15rem;
        }

        .notif-item-message {
            font-size: .78rem;
            color: #64748b;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notif-item-time {
            font-size: .7rem;
            color: #94a3b8;
            margin-top: .3rem;
        }

        .notif-item-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #1a56db;
            flex-shrink: 0;
            margin-top: .4rem;
        }

        .notif-item:not(.unread) .notif-item-dot {
            visibility: hidden;
        }

        .notif-dropdown-footer {
            padding: .65rem;
            text-align: center;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
        }

        .notif-dropdown-footer a {
            font-size: .78rem;
            color: #1a56db;
            text-decoration: none;
            font-weight: 500;
        }

        .notif-dropdown-footer a:hover {
            text-decoration: underline;
        }
    </style>

    {{-- ═══════ DARK MODE CSS (CSS variables + overrides) ═══════ --}}
    <style>
        /* ── Theme Toggle Button ── */
        .theme-toggle-btn {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            border-radius: .55rem;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #475569;
            transition: all .2s;
            margin-right: .5rem;
            padding: 0;
        }

        .theme-toggle-btn:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .theme-toggle-btn i {
            font-size: 1.05rem;
            line-height: 1;
        }

        .theme-toggle-btn .theme-icon-dark {
            display: none;
        }

        html[data-theme="dark"] .theme-toggle-btn .theme-icon-light {
            display: none;
        }

        html[data-theme="dark"] .theme-toggle-btn .theme-icon-dark {
            display: inline;
        }

        /* ═══════════════════════════════════════════════════════════════
           DARK MODE OVERRIDES — activated via html[data-theme="dark"]
           ═══════════════════════════════════════════════════════════════ */
        html[data-theme="dark"] body {
            background: #0f172a !important;
            color: #e2e8f0 !important;
        }

        /* Main content area */
        html[data-theme="dark"] .main-content,
        html[data-theme="dark"] .content-area {
            background: #0f172a !important;
        }

        /* Topbar */
        html[data-theme="dark"] .topbar {
            background: #1e293b !important;
            border-bottom-color: #334155 !important;
        }

        html[data-theme="dark"] .topbar .page-title {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .last-login {
            color: #94a3b8 !important;
            background: #334155 !important;
        }

        /* Toggle button dark mode */
        html[data-theme="dark"] .theme-toggle-btn {
            background: #334155;
            border-color: #475569;
            color: #fbbf24;
        }

        html[data-theme="dark"] .theme-toggle-btn:hover {
            background: #475569;
            border-color: #64748b;
        }

        /* Bell button */
        html[data-theme="dark"] .notif-bell-btn {
            background: #334155;
            border-color: #475569;
            color: #cbd5e1;
        }

        html[data-theme="dark"] .notif-bell-btn:hover {
            background: #475569;
            color: #60a5fa;
        }

        /* Notification dropdown */
        html[data-theme="dark"] .notif-dropdown {
            background: #1e293b !important;
            border-color: #334155 !important;
        }

        html[data-theme="dark"] .notif-dropdown-header,
        html[data-theme="dark"] .notif-dropdown-footer {
            background: #0f172a !important;
            border-color: #334155 !important;
        }

        html[data-theme="dark"] .notif-dropdown-header strong {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .notif-item {
            border-color: #334155 !important;
        }

        html[data-theme="dark"] .notif-item:hover {
            background: #334155 !important;
        }

        html[data-theme="dark"] .notif-item.unread {
            background: #1e3a5f !important;
        }

        html[data-theme="dark"] .notif-item-title {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .notif-item-message {
            color: #cbd5e1 !important;
        }

        /* Cards - generic */
        html[data-theme="dark"] .stat-card,
        html[data-theme="dark"] .data-card,
        html[data-theme="dark"] .form-card,
        html[data-theme="dark"] .detail-card,
        html[data-theme="dark"] .lacak-card,
        html[data-theme="dark"] .filter-bar,
        html[data-theme="dark"] .welcome-card,
        html[data-theme="dark"] .kode-hero,
        html[data-theme="dark"] .stat-hero {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }

        html[data-theme="dark"] .data-card-header {
            background: #0f172a !important;
            border-color: #334155 !important;
        }

        html[data-theme="dark"] .data-card-header h6,
        html[data-theme="dark"] .stat-value,
        html[data-theme="dark"] .info-value,
        html[data-theme="dark"] .page-header h5 {
            color: #f1f5f9 !important;
        }

        html[data-theme="dark"] .stat-label,
        html[data-theme="dark"] .info-label,
        html[data-theme="dark"] .page-header .sub {
            color: #94a3b8 !important;
        }

        /* Tables */
        html[data-theme="dark"] .data-table,
        html[data-theme="dark"] .table {
            color: #e2e8f0 !important;
        }

        html[data-theme="dark"] .data-table thead th,
        html[data-theme="dark"] .table thead th {
            background: #0f172a !important;
            color: #94a3b8 !important;
            border-color: #334155 !important;
        }

        html[data-theme="dark"] .data-table tbody tr,
        html[data-theme="dark"] .table tbody tr {
            border-color: #334155 !important;
        }

        html[data-theme="dark"] .data-table tbody tr:hover,
        html[data-theme="dark"] .table tbody tr:hover {
            background: #334155 !important;
        }

        html[data-theme="dark"] .data-table td,
        html[data-theme="dark"] .table td {
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }

        /* Forms */
        html[data-theme="dark"] .form-control-custom,
        html[data-theme="dark"] .form-control-aduan,
        html[data-theme="dark"] .search-input,
        html[data-theme="dark"] .filter-select,
        html[data-theme="dark"] input.form-control,
        html[data-theme="dark"] textarea.form-control,
        html[data-theme="dark"] select.form-control {
            background: #0f172a !important;
            border-color: #475569 !important;
            color: #e2e8f0 !important;
        }

        html[data-theme="dark"] .form-control-custom:focus,
        html[data-theme="dark"] .form-control-aduan:focus,
        html[data-theme="dark"] input.form-control:focus {
            background: #1e293b !important;
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, .1) !important;
        }

        html[data-theme="dark"] .form-label-custom,
        html[data-theme="dark"] .form-label-aduan,
        html[data-theme="dark"] label {
            color: #cbd5e1 !important;
        }

        html[data-theme="dark"] .form-hint {
            color: #64748b !important;
        }

        html[data-theme="dark"] ::placeholder {
            color: #64748b !important;
            opacity: .8;
        }

        /* Alerts stays same colors but darker bg */
        html[data-theme="dark"] .alert-desa.success {
            background: #064e3b !important;
            color: #a7f3d0 !important;
            border-color: #059669 !important;
        }

        html[data-theme="dark"] .alert-desa.danger {
            background: #7f1d1d !important;
            color: #fecaca !important;
            border-color: #dc2626 !important;
        }

        html[data-theme="dark"] .alert-desa.warning {
            background: #78350f !important;
            color: #fde68a !important;
            border-color: #d97706 !important;
        }

        /* Dropdown menus */
        html[data-theme="dark"] .dropdown-menu {
            background: #1e293b !important;
            border-color: #334155 !important;
        }

        html[data-theme="dark"] .dropdown-item {
            color: #e2e8f0 !important;
        }

        html[data-theme="dark"] .dropdown-item:hover {
            background: #334155 !important;
            color: #f1f5f9 !important;
        }

        /* Pagination */
        html[data-theme="dark"] .pagination .page-link {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }

        html[data-theme="dark"] .pagination .page-item.active .page-link {
            background: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: white !important;
        }

        /* Modals */
        html[data-theme="dark"] .modal-content {
            background: #1e293b !important;
            color: #e2e8f0 !important;
        }

        html[data-theme="dark"] .modal-header,
        html[data-theme="dark"] .modal-footer {
            border-color: #334155 !important;
        }

        /* Page header & separators */
        html[data-theme="dark"] .page-header {
            border-color: #334155 !important;
        }

        html[data-theme="dark"] hr {
            border-color: #334155 !important;
            opacity: 1;
        }

        /* Empty states */
        html[data-theme="dark"] .empty-state {
            background: transparent !important;
        }

        html[data-theme="dark"] .empty-title {
            color: #cbd5e1 !important;
        }

        html[data-theme="dark"] .empty-sub {
            color: #64748b !important;
        }

        /* Badges */
        html[data-theme="dark"] .status-badge,
        html[data-theme="dark"] .badge {
            filter: brightness(.9);
        }

        /* Bootstrap utility classes override */
        html[data-theme="dark"] .text-dark,
        html[data-theme="dark"] .text-black {
            color: #e2e8f0 !important;
        }

        html[data-theme="dark"] .bg-white,
        html[data-theme="dark"] .bg-light {
            background: #1e293b !important;
        }

        html[data-theme="dark"] .border {
            border-color: #334155 !important;
        }

        /* Smooth transition saat switch theme */
        html {
            transition: background-color .3s ease;
        }

        body,
        .topbar,
        .sidebar,
        .data-card,
        .stat-card,
        .form-card,
        .detail-card,
        .filter-bar,
        .main-content {
            transition: background-color .3s ease, color .3s ease, border-color .3s ease;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- Session timeout warning --}}
    <div id="session-warn">
        <i class="bi bi-clock-history me-2"></i>
        Sesi Anda akan berakhir dalam <span id="warn-countdown" class="text-danger fw-bold">5:00</span>.
        <a href="#" onclick="document.getElementById('keepAliveForm').submit();">
            Perpanjang Sesi <i class="bi bi-arrow-clockwise"></i>
        </a>
    </div>

    {{-- Keep-alive form --}}
    <form id="keepAliveForm" method="POST" action="{{ route('session.extend') }}" style="display:none">
        @csrf
    </form>

    {{-- Mobile Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- ── Sidebar ──────────────────────────────────────────────────────────── -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-house-fill"></i></div>
            <span>SID Desa</span>
        </div>

        <div class="sidebar-user">
            <div class="d-flex align-items-center gap-3">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">
                        {{ auth()->user()->role_label }}
                        @if (auth()->user()->isRt() && auth()->user()->rt)
                            <span style="opacity:.75;">· RT {{ auth()->user()->rt }}</span>
                        @elseif(auth()->user()->isRw() && auth()->user()->rw)
                            <span style="opacity:.75;">· RW {{ auth()->user()->rw }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar-nav">
            <a class="sidebar-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}"
                href="{{ route(auth()->user()->getDashboardRoute()) }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>

            {{-- ═══════ MANAJEMEN SISTEM — Admin Only ═══════ --}}
            @if (auth()->user()->isAdmin())
                <div class="nav-section-label">Manajemen</div>
                <a class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                    href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people-fill"></i> Kelola Pengguna
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.audit*') ? 'active' : '' }}"
                    href="{{ route('admin.audit.index') }}">
                    <i class="bi bi-journal-text"></i> Audit Log
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.blocked*') ? 'active' : '' }}"
                    href="{{ route('admin.blocked-ips.index') }}">
                    <i class="bi bi-ban"></i> IP Terblokir
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.login-attempts*') ? 'active' : '' }}"
                    href="{{ route('admin.login-attempts.index') }}">
                    <i class="bi bi-shield-exclamation"></i> Percobaan Login
                </a>
            @endif

            {{-- ═══════ KONTEN DESA — Admin + Staff Desa ═══════ --}}
            {{-- Section ini berisi konten publikasi yang dibaca warga --}}
            @if (auth()->user()->canManageContent())
                <div class="nav-section-label">Konten Desa</div>
                <a class="sidebar-link {{ request()->routeIs('admin.berita*') ? 'active' : '' }}"
                    href="{{ route('admin.berita.index') }}">
                    <i class="bi bi-newspaper"></i> Kelola Berita
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.pengumuman*') ? 'active' : '' }}"
                    href="{{ route('admin.pengumuman.index') }}">
                    <i class="bi bi-megaphone-fill"></i> Kelola Pengumuman
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.galeri*') ? 'active' : '' }}"
                    href="{{ route('admin.galeri.index') }}">
                    <i class="bi bi-images"></i> Kelola Galeri
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.struktur-desa*') ? 'active' : '' }}"
                    href="{{ route('admin.struktur-desa.index') }}">
                    <i class="bi bi-diagram-3-fill"></i> Struktur Desa
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.profil-desa*') ? 'active' : '' }}"
                    href="{{ route('admin.profil-desa.edit') }}">
                    <i class="bi bi-house-heart-fill"></i> Profil Desa
                </a>

                {{-- ═══════ LAYANAN & PROGRAM — Admin + Staff Desa ═══════ --}}
                {{-- Section ini berisi program/layanan nyata yang melibatkan data warga --}}
                <div class="nav-section-label">Layanan & Program</div>
                <a class="sidebar-link {{ request()->routeIs('admin.pengaduan*') ? 'active' : '' }}"
                    href="{{ route('admin.pengaduan.index') }}">
                    <i class="bi bi-chat-dots-fill"></i> Pengaduan Warga
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.bansos.program*') ? 'active' : '' }}"
                    href="{{ route('admin.bansos.program.index') }}">
                    <i class="bi bi-grid-fill"></i> Program Bansos
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.bansos.penerima*') ? 'active' : '' }}"
                    href="{{ route('admin.bansos.penerima.index') }}">
                    <i class="bi bi-heart-fill"></i> Penerima Bansos
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.posyandu.index') || request()->routeIs('admin.posyandu.create') || request()->routeIs('admin.posyandu.edit') || request()->routeIs('admin.posyandu.store') || request()->routeIs('admin.posyandu.update') || request()->routeIs('admin.posyandu.destroy') ? 'active' : '' }}"
                    href="{{ route('admin.posyandu.index') }}">
                    <i class="bi bi-heart-pulse-fill"></i> Kelola Posyandu
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.posyandu.jadwal*') ? 'active' : '' }}"
                    href="{{ route('admin.posyandu.jadwal.index') }}">
                    <i class="bi bi-calendar-event-fill"></i> Jadwal Posyandu
                </a>
                {{-- Di dalam section "Layanan & Program", setelah Jadwal Posyandu (sekitar baris 784) --}}
                <a class="sidebar-link {{ request()->routeIs('admin.apbdes*') ? 'active' : '' }}"
                    href="{{ route('admin.apbdes.index') }}">
                    <i class="bi bi-wallet2"></i> Kelola APBDes
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.agenda*') ? 'active' : '' }}"
                    href="{{ route('admin.agenda.index') }}">
                    <i class="bi bi-calendar-event-fill"></i> Kelola Agenda
                </a>
            @endif

            {{-- ═══════ DATA WARGA — Admin + Staff Desa + RW + RT ═══════ --}}
            @if (auth()->user()->canViewPenduduk())
                <div class="nav-section-label">
                    Data Warga
                    @if (auth()->user()->isRt())
                        <span style="color:#94a3b8; font-weight:400;">· RT {{ auth()->user()->rt }}</span>
                    @elseif(auth()->user()->isRw())
                        <span style="color:#94a3b8; font-weight:400;">· RW {{ auth()->user()->rw }}</span>
                    @endif
                </div>
                <a class="sidebar-link {{ request()->routeIs('admin.penduduk*') ? 'active' : '' }}"
                    href="{{ route('admin.penduduk.index') }}">
                    <i class="bi bi-person-lines-fill"></i>
                    @if (auth()->user()->isRt())
                        Warga RT {{ auth()->user()->rt }}
                    @elseif(auth()->user()->isRw())
                        Warga RW {{ auth()->user()->rw }}
                    @else
                        Kelola Penduduk
                    @endif
                </a>
            @endif

            <div class="nav-section-label">Akun</div>
            <a class="sidebar-link {{ request()->routeIs('profile.show') ? 'active' : '' }}"
                href="{{ route('profile.show') }}">
                <i class="bi bi-person-circle"></i> Profil Saya
            </a>
            <a class="sidebar-link {{ request()->routeIs('profile.security') ? 'active' : '' }}"
                href="{{ route('profile.security') }}">
                <i class="bi bi-shield-lock-fill"></i> Keamanan
            </a>

            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- ── Main Content ─────────────────────────────────────────────────────── -->
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <span class="page-title">@yield('page-title', 'Dashboard')</span>
            </div>
            <div class="topbar-right">
                {{-- ── Notifikasi Bell Icon (hanya admin & staff) ── --}}
                @if (auth()->user()->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_STAFF_DESA]))
                    @include('partials.notification-bell')
                @endif

                {{-- ── Dark Mode Toggle ── --}}
                <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Toggle dark mode"
                    title="Ganti tema">
                    <i class="bi bi-sun-fill theme-icon-light"></i>
                    <i class="bi bi-moon-stars-fill theme-icon-dark"></i>
                </button>

                <small class="last-login">
                    <i class="bi bi-clock-history"></i>
                    Login terakhir: {{ auth()->user()->last_login_at?->diffForHumans() ?? 'Baru saja' }}
                </small>
                @if (!auth()->user()->two_factor_enabled)
                    <a href="{{ route('profile.security') }}" class="badge-2fa">
                        <i class="bi bi-shield-exclamation"></i> Aktifkan 2FA
                    </a>
                @endif
            </div>
        </div>

        <div class="content-area">
            {{-- Flash Messages --}}
            @foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning', 'info' => 'info'] as $type => $class)
                @if (session($type))
                    <div class="alert alert-{{ $class }} alert-desa {{ $class }} alert-dismissible fade show mb-4"
                        role="alert"
                        style="padding:1rem 1.25rem;border-radius:.65rem;display:flex;align-items:center;
                                @if ($class === 'success') background:#d1fae5;color:#065f46;border:1.5px solid #6ee7b7;
                                @elseif($class === 'danger') background:#fee2e2;color:#991b1b;border:1.5px solid #fca5a5;
                                @elseif($class === 'warning') background:#fef3c7;color:#92400e;border:1.5px solid #fcd34d;
                                @else background:#dbeafe;color:#1e40af;border:1.5px solid #93c5fd; @endif
                                ">
                        <i class="bi bi-{{ $type === 'success' ? 'check-circle-fill' : ($type === 'error' ? 'x-octagon-fill' : 'exclamation-triangle-fill') }} me-2"
                            style="font-size:1.1rem;"></i>
                        <span style="flex-grow:1;">{{ session($type) }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
            @endforeach

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Define login route for JS --}}
    <script>
        window.loginRoute = '{{ route('login') }}';
    </script>

    {{-- Custom App JS --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- ═══════ NOTIFICATION BELL JS (inline agar pasti ter-load) ═══════ --}}
    @auth
        @if (auth()->user()->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_STAFF_DESA]))
            <script>
                (function() {
                    'use strict';

                    function init() {
                        var wrapper = document.getElementById('notifWrapper');
                        if (!wrapper) return;

                        var DROPDOWN_URL = wrapper.getAttribute('data-dropdown-url');
                        var SHOW_URL = wrapper.getAttribute('data-show-url');
                        var POLL_MS = 60000;

                        var btn = document.getElementById('notifBellBtn');
                        var dropdown = document.getElementById('notifDropdown');
                        var badge = document.getElementById('notifBadge');
                        var body = document.getElementById('notifDropdownBody');
                        var unread = document.getElementById('notifUnreadText');

                        if (!btn || !dropdown || !badge || !body) return;

                        var notifData = {
                            notifications: [],
                            unread_count: 0
                        };

                        function positionDropdown() {
                            var btnRect = btn.getBoundingClientRect();
                            var vw = window.innerWidth;
                            var top = btnRect.bottom + 8;
                            var right = vw - btnRect.right;
                            if (right < 16) right = 16;
                            dropdown.style.top = top + 'px';
                            dropdown.style.right = right + 'px';
                            dropdown.style.left = 'auto';
                        }

                        function fetchNotifications() {
                            fetch(DROPDOWN_URL, {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    credentials: 'same-origin'
                                })
                                .then(function(res) {
                                    return res.ok ? res.json() : null;
                                })
                                .then(function(data) {
                                    if (!data) return;
                                    notifData = data;
                                    renderBadge();
                                    if (dropdown.classList.contains('show')) renderList();
                                })
                                .catch(function(err) {
                                    console.warn('[Notif] ', err);
                                });
                        }

                        function renderBadge() {
                            var count = notifData.unread_count || 0;
                            if (count > 0) {
                                badge.textContent = count > 99 ? '99+' : count;
                                badge.style.display = 'flex';
                                unread.textContent = '(' + count + ' belum dibaca)';
                            } else {
                                badge.style.display = 'none';
                                unread.textContent = '';
                            }
                        }

                        function escapeHtml(s) {
                            return String(s == null ? '' : s).replace(/[&<>"']/g, function(ch) {
                                return {
                                    '&': '&amp;',
                                    '<': '&lt;',
                                    '>': '&gt;',
                                    '"': '&quot;',
                                    "'": '&#39;'
                                } [ch];
                            });
                        }

                        function renderList() {
                            var items = notifData.notifications || [];
                            if (items.length === 0) {
                                body.innerHTML =
                                    '<div class="notif-empty"><i class="bi bi-bell-slash"></i><div class="notif-empty-text">Belum ada notifikasi</div></div>';
                                return;
                            }
                            body.innerHTML = items.map(function(n) {
                                var unreadClass = n.read ? '' : 'unread';
                                var url = SHOW_URL + '/' + encodeURIComponent(n.id);
                                return '<a href="' + url + '" class="notif-item ' + unreadClass + '">' +
                                    '<div class="notif-item-icon ' + escapeHtml(n.color) + '">' +
                                    '<i class="bi ' + escapeHtml(n.icon) + '"></i></div>' +
                                    '<div class="notif-item-body">' +
                                    '<div class="notif-item-title">' + escapeHtml(n.title) + '</div>' +
                                    '<div class="notif-item-message">' + escapeHtml(n.message) + '</div>' +
                                    '<div class="notif-item-time">' + escapeHtml(n.created_at) + '</div>' +
                                    '</div><div class="notif-item-dot"></div></a>';
                            }).join('');
                        }

                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            e.preventDefault();
                            var isOpen = dropdown.classList.toggle('show');
                            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                            if (isOpen) {
                                positionDropdown();
                                renderList();
                            }
                        });

                        document.addEventListener('click', function(e) {
                            if (!wrapper.contains(e.target) && !dropdown.contains(e.target)) {
                                dropdown.classList.remove('show');
                                btn.setAttribute('aria-expanded', 'false');
                            }
                        });

                        window.addEventListener('resize', function() {
                            if (dropdown.classList.contains('show')) positionDropdown();
                        });
                        window.addEventListener('scroll', function() {
                            if (dropdown.classList.contains('show')) positionDropdown();
                        }, true);

                        fetchNotifications();
                        setInterval(fetchNotifications, POLL_MS);
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', init);
                    } else {
                        init();
                    }
                })
                ();
            </script>
        @endif
    @endauth

    {{-- ═══════ DARK MODE TOGGLE JS ═══════ --}}
    <script>
        (function() {
            'use strict';

            var STORAGE_KEY = 'sid_theme_preference';

            function applyTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                try {
                    localStorage.setItem(STORAGE_KEY, theme);
                } catch (e) {}
            }

            function getCurrentTheme() {
                try {
                    return localStorage.getItem(STORAGE_KEY) || 'light';
                } catch (e) {
                    return 'light';
                }
            }

            // Apply theme on page load (sudah ada di <head> inline, tapi re-apply untuk jaga)
            applyTheme(getCurrentTheme());

            // Attach toggle listener
            function initToggle() {
                var btn = document.getElementById('themeToggleBtn');
                if (!btn) return;

                btn.addEventListener('click', function() {
                    var current = getCurrentTheme();
                    var next = current === 'dark' ? 'light' : 'dark';
                    applyTheme(next);
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initToggle);
            } else {
                initToggle();
            }
        })();
    </script>

    @stack('scripts')
</body>

</html>
