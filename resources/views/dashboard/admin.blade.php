@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@push('styles')
    @include('admin._admin-styles')
    <style>
        /* ═══════ Stats Cards ═══════ */
        .stats-grid-admin {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card-admin {
            background: white;
            border-radius: .85rem;
            padding: 1.25rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
            border-left: 4px solid var(--accent, #1a56db);
        }

        .stat-card-admin .icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: .6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .stat-card-admin .stat-label {
            font-size: .72rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .stat-card-admin .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            font-family: 'JetBrains Mono', monospace;
            line-height: 1.1;
        }

        /* ═══════ Chart Cards ═══════ */
        .chart-card {
            background: white;
            border-radius: .85rem;
            padding: 1.5rem;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            margin-bottom: 1.25rem;
        }

        .chart-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: .75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .chart-card-header h6 {
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .95rem;
        }

        .chart-card-header .meta {
            font-size: .75rem;
            color: #94a3b8;
        }

        .chart-card canvas {
            max-height: 280px;
        }

        .chart-card.tall canvas {
            max-height: 380px;
        }

        /* ═══════ Top RT List ═══════ */
        .rt-list {
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .rt-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: .75rem 1rem;
            background: #f8fafc;
            border-radius: .55rem;
            transition: background .15s;
        }

        .rt-item:hover {
            background: #f1f5f9;
        }

        .rt-rank {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #1a56db;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .82rem;
            flex-shrink: 0;
        }

        .rt-rank.rank-1 {
            background: #f59e0b;
        }

        .rt-rank.rank-2 {
            background: #94a3b8;
        }

        .rt-rank.rank-3 {
            background: #d97706;
        }

        .rt-info {
            flex: 1;
        }

        .rt-name {
            font-weight: 600;
            color: #0f172a;
            font-size: .9rem;
        }

        .rt-count {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.05rem;
            color: #1e40af;
        }

        /* ═══════ Recent Tables ═══════ */
        .compact-list {
            font-size: .82rem;
        }

        .compact-list-item {
            display: flex;
            gap: .75rem;
            padding: .65rem 0;
            border-bottom: 1px solid #f1f5f9;
            align-items: flex-start;
        }

        .compact-list-item:last-child {
            border-bottom: none;
        }

        .compact-icon {
            width: 28px;
            height: 28px;
            border-radius: .4rem;
            background: #f1f5f9;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .82rem;
        }

        .compact-list-item .meta {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: .15rem;
        }

        /* Dark mode support untuk chart cards */
        html[data-theme="dark"] .stat-card-admin,
        html[data-theme="dark"] .chart-card {
            background: #1e293b;
        }

        html[data-theme="dark"] .rt-item {
            background: #0f172a;
        }

        html[data-theme="dark"] .rt-item:hover {
            background: #334155;
        }
    </style>
@endpush

@section('content')

    {{-- ═══════ WELCOME ═══════ --}}
    <div class="page-header">
        <div>
            <h5>Selamat datang, {{ auth()->user()->name }}! 👋</h5>
            <div class="sub">Ringkasan kondisi desa dan aktivitas sistem</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span style="font-size:.82rem;color:#64748b;">
                <i class="bi bi-calendar3 me-1"></i>
                {{ now()->isoFormat('dddd, D MMMM YYYY') }}
            </span>
        </div>
    </div>

    {{-- ═══════ STATS CARDS ═══════ --}}
    <div class="stats-grid-admin">
        <div class="stat-card-admin" style="--accent:#1a56db;">
            <div class="icon-wrap" style="background:#dbeafe;color:#1e40af;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Warga</div>
                <div class="stat-value">{{ number_format($stats['total_warga']) }}</div>
            </div>
        </div>

        <div class="stat-card-admin" style="--accent:#10b981;">
            <div class="icon-wrap" style="background:#d1fae5;color:#065f46;">
                <i class="bi bi-house-fill"></i>
            </div>
            <div>
                <div class="stat-label">Kepala Keluarga</div>
                <div class="stat-value">{{ number_format($stats['total_kk']) }}</div>
            </div>
        </div>

        <div class="stat-card-admin" style="--accent:#8b5cf6;">
            <div class="icon-wrap" style="background:#ede9fe;color:#5b21b6;">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
                <div class="stat-label">Pengguna Sistem</div>
                <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            </div>
        </div>

        <div class="stat-card-admin" style="--accent:#06b6d4;">
            <div class="icon-wrap" style="background:#cffafe;color:#0e7490;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-label">Akun Aktif</div>
                <div class="stat-value">{{ number_format($stats['active_users']) }}</div>
            </div>
        </div>

        <div class="stat-card-admin" style="--accent:#f59e0b;">
            <div class="icon-wrap" style="background:#fef3c7;color:#92400e;">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <div>
                <div class="stat-label">Pengaduan Baru</div>
                <div class="stat-value">{{ number_format($stats['pengaduan_baru']) }}</div>
            </div>
        </div>

        <div class="stat-card-admin" style="--accent:#ef4444;">
            <div class="icon-wrap" style="background:#fee2e2;color:#991b1b;">
                <i class="bi bi-shield-exclamation"></i>
            </div>
            <div>
                <div class="stat-label">Login Gagal (24j)</div>
                <div class="stat-value">{{ number_format($stats['failed_logins']) }}</div>
            </div>
        </div>
    </div>

    {{-- ═══════ ROW 1: PIRAMIDA + TREN BULANAN ═══════ --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-7">
            <div class="chart-card tall">
                <div class="chart-card-header">
                    <h6><i class="bi bi-bar-chart-steps" style="color:#1a56db;"></i>Piramida Penduduk</h6>
                    <span class="meta">Distribusi usia × jenis kelamin</span>
                </div>
                <canvas id="chartPiramida"></canvas>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="chart-card tall">
                <div class="chart-card-header">
                    <h6><i class="bi bi-graph-up-arrow" style="color:#10b981;"></i>Tren Warga Baru</h6>
                    <span class="meta">12 bulan terakhir</span>
                </div>
                <canvas id="chartTren"></canvas>
            </div>
        </div>
    </div>

    {{-- ═══════ ROW 2: 3 PIE/DOUGHNUT CHARTS ═══════ --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h6><i class="bi bi-briefcase-fill" style="color:#8b5cf6;"></i>Pekerjaan</h6>
                    <span class="meta">Top 8</span>
                </div>
                <canvas id="chartPekerjaan"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h6><i class="bi bi-mortarboard-fill" style="color:#06b6d4;"></i>Pendidikan</h6>
                    <span class="meta">Jenjang</span>
                </div>
                <canvas id="chartPendidikan"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h6><i class="bi bi-heart-fill" style="color:#f59e0b;"></i>Agama</h6>
                    <span class="meta">Komposisi</span>
                </div>
                <canvas id="chartAgama"></canvas>
            </div>
        </div>
    </div>

    {{-- ═══════ ROW 3: TOP RT + PENGADUAN STATUS ═══════ --}}
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h6><i class="bi bi-trophy-fill" style="color:#f59e0b;"></i>Top 5 RT dengan Warga Terbanyak</h6>
                    <span class="meta">Peringkat</span>
                </div>
                <div class="rt-list">
                    @forelse($topRt as $i => $rt)
                        <div class="rt-item">
                            <div class="rt-rank rank-{{ $i + 1 }}">{{ $i + 1 }}</div>
                            <div class="rt-info">
                                <div class="rt-name">
                                    RT {{ $rt->rt }}{{ $rt->rw ? ' / RW ' . $rt->rw : '' }}
                                </div>
                            </div>
                            <div class="rt-count">{{ number_format($rt->total) }}</div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-sub">Belum ada data</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h6><i class="bi bi-pie-chart-fill" style="color:#ef4444;"></i>Status Pengaduan</h6>
                    <span class="meta">Breakdown</span>
                </div>
                <canvas id="chartPengaduan"></canvas>
            </div>
        </div>
    </div>

    {{-- ═══════ ROW 4: RECENT ACTIVITY + FAILED LOGIN ═══════ --}}
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h6><i class="bi bi-clock-history" style="color:#1a56db;"></i>Aktivitas Terkini</h6>
                    <a href="{{ route('admin.audit.index') }}"
                        style="font-size:.78rem;color:#1a56db;text-decoration:none;">Lihat semua →</a>
                </div>
                <div class="compact-list">
                    @forelse($recentActivities as $act)
                        <div class="compact-list-item">
                            <div class="compact-icon" style="background:#dbeafe;color:#1e40af;">
                                <i class="bi bi-activity"></i>
                            </div>
                            <div style="flex:1;">
                                <div style="color:#0f172a;font-weight:500;">
                                    {{ $act->user?->name ?? 'System' }}
                                </div>
                                <div class="meta">
                                    {{ $act->action ?? ($act->event ?? 'Aktivitas sistem') }}
                                    · {{ $act->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-sub">Belum ada aktivitas</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h6><i class="bi bi-shield-exclamation" style="color:#ef4444;"></i>Percobaan Login Gagal</h6>
                    <a href="{{ route('admin.login-attempts.index') }}"
                        style="font-size:.78rem;color:#1a56db;text-decoration:none;">Lihat semua →</a>
                </div>
                <div class="compact-list">
                    @forelse($recentFailedLogins as $login)
                        <div class="compact-list-item">
                            <div class="compact-icon" style="background:#fee2e2;color:#991b1b;">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                            <div style="flex:1;">
                                <div
                                    style="color:#0f172a;font-weight:500;font-family:'JetBrains Mono',monospace;font-size:.8rem;">
                                    {{ $login->email ?? ($login->username ?? 'Unknown') }}
                                </div>
                                <div class="meta">
                                    IP: {{ $login->ip_address ?? '—' }}
                                    · {{ $login->attempted_at?->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-sub">Tidak ada percobaan gagal 🎉</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            'use strict';

            // ── Detect theme untuk warna chart ──
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const textColor = isDark ? '#cbd5e1' : '#475569';
            const gridColor = isDark ? '#334155' : '#e2e8f0';

            // Default Chart.js config
            Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
            Chart.defaults.font.size = 11;
            Chart.defaults.color = textColor;

            // ═══════ 1. PIRAMIDA PENDUDUK ═══════
            new Chart(document.getElementById('chartPiramida'), {
                type: 'bar',
                data: {
                    labels: @json($piramida['labels'] ?? []),
                    datasets: [{
                            label: 'Laki-laki',
                            data: @json($piramida['laki'] ?? []),
                            backgroundColor: '#3b82f6',
                            borderRadius: 4,
                        },
                        {
                            label: 'Perempuan',
                            data: @json($piramida['perempuan'] ?? []),
                            backgroundColor: '#ec4899',
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.dataset.label + ': ' + Math.abs(ctx.parsed.x);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            ticks: {
                                callback: function(v) {
                                    return Math.abs(v);
                                },
                                color: textColor
                            },
                            grid: {
                                color: gridColor
                            }
                        },
                        y: {
                            stacked: true,
                            ticks: {
                                color: textColor
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // ═══════ 2. TREN BULANAN ═══════
            new Chart(document.getElementById('chartTren'), {
                type: 'line',
                data: {
                    labels: @json($trenBulanan['labels'] ?? []),
                    datasets: [{
                        label: 'Warga Baru',
                        data: @json($trenBulanan['data'] ?? []),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,.12)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#10b981',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2.5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: textColor
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            ticks: {
                                color: textColor
                            },
                            grid: {
                                color: gridColor
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            // ═══════ 3. PEKERJAAN (PIE) ═══════
            const palettePekerjaan = ['#1a56db', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899',
                '#64748b'
            ];
            new Chart(document.getElementById('chartPekerjaan'), {
                type: 'doughnut',
                data: {
                    labels: @json($pekerjaan->pluck('pekerjaan')),
                    datasets: [{
                        data: @json($pekerjaan->pluck('total')),
                        backgroundColor: palettePekerjaan,
                        borderWidth: 2,
                        borderColor: isDark ? '#1e293b' : '#fff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 8,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    cutout: '55%'
                }
            });

            // ═══════ 4. PENDIDIKAN (BAR) ═══════
            new Chart(document.getElementById('chartPendidikan'), {
                type: 'bar',
                data: {
                    labels: @json($pendidikan->pluck('pendidikan')),
                    datasets: [{
                        label: 'Jumlah',
                        data: @json($pendidikan->pluck('total')),
                        backgroundColor: '#06b6d4',
                        borderRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: textColor
                            },
                            grid: {
                                color: gridColor
                            },
                            beginAtZero: true
                        },
                        y: {
                            ticks: {
                                color: textColor,
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // ═══════ 5. AGAMA (DOUGHNUT) ═══════
            const paletteAgama = ['#10b981', '#1a56db', '#ef4444', '#f59e0b', '#8b5cf6', '#06b6d4'];
            new Chart(document.getElementById('chartAgama'), {
                type: 'doughnut',
                data: {
                    labels: @json($agama->pluck('agama')),
                    datasets: [{
                        data: @json($agama->pluck('total')),
                        backgroundColor: paletteAgama,
                        borderWidth: 2,
                        borderColor: isDark ? '#1e293b' : '#fff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 8,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    },
                    cutout: '55%'
                }
            });

            // ═══════ 6. STATUS PENGADUAN (DOUGHNUT) ═══════
            @if (!empty($pengaduanStats))
                new Chart(document.getElementById('chartPengaduan'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Baru', 'Diproses', 'Selesai', 'Ditolak'],
                        datasets: [{
                            data: [
                                {{ $pengaduanStats['baru'] ?? 0 }},
                                {{ $pengaduanStats['diproses'] ?? 0 }},
                                {{ $pengaduanStats['selesai'] ?? 0 }},
                                {{ $pengaduanStats['ditolak'] ?? 0 }},
                            ],
                            backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                            borderWidth: 2,
                            borderColor: isDark ? '#1e293b' : '#fff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 10
                                }
                            }
                        },
                        cutout: '60%'
                    }
                });
            @endif
        })();
    </script>
@endpush
