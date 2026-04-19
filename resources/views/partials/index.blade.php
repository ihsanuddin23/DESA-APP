@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@push('styles')
    @include('admin._admin-styles')
    <style>
        .notif-list-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: .85rem;
            padding: 0;
            box-shadow: 0 1px 6px rgba(15, 23, 42, .05);
            overflow: hidden;
        }

        .notif-list-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .notif-list-header h6 {
            margin: 0;
            font-weight: 700;
            color: #0f172a;
            font-size: .95rem;
        }

        .notif-list-header .subtitle {
            font-size: .78rem;
            color: #64748b;
            margin-top: .2rem;
        }

        .notif-big-item {
            display: flex;
            gap: 1rem;
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background .15s;
            position: relative;
        }

        .notif-big-item:hover {
            background: #f8fafc;
        }

        .notif-big-item.unread {
            background: #eff6ff;
        }

        .notif-big-item.unread:hover {
            background: #dbeafe;
        }

        .notif-big-item:last-child {
            border-bottom: none;
        }

        .notif-big-icon {
            width: 48px;
            height: 48px;
            border-radius: .6rem;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .notif-big-icon.success {
            background: #d1fae5;
            color: #065f46;
        }

        .notif-big-icon.info {
            background: #dbeafe;
            color: #1e40af;
        }

        .notif-big-icon.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .notif-big-icon.danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .notif-big-body {
            flex-grow: 1;
            min-width: 0;
        }

        .notif-big-title {
            font-weight: 600;
            color: #0f172a;
            font-size: .9rem;
            margin-bottom: .2rem;
        }

        .notif-big-message {
            color: #475569;
            font-size: .84rem;
            line-height: 1.5;
        }

        .notif-big-meta {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: .5rem;
            display: flex;
            gap: .85rem;
            align-items: center;
        }

        .notif-big-actions {
            display: flex;
            gap: .35rem;
            align-items: center;
            flex-shrink: 0;
        }

        .notif-big-actions a,
        .notif-big-actions button {
            padding: .4rem .6rem;
            font-size: .75rem;
            border-radius: .4rem;
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            text-decoration: none;
            border: 1.5px solid #e2e8f0;
            background: white;
            cursor: pointer;
        }

        .notif-big-actions .btn-view {
            color: #1a56db;
        }

        .notif-big-actions .btn-view:hover {
            background: #eff6ff;
        }

        .notif-big-actions .btn-delete {
            color: #dc2626;
            border-color: #fca5a5;
            background: #fff5f5;
        }

        .notif-big-actions .btn-delete:hover {
            background: #fee2e2;
        }

        .notif-unread-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #1a56db;
            position: absolute;
            left: .6rem;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
@endpush

@section('content')

    <div class="page-header">
        <div>
            <h5>Notifikasi</h5>
            <div class="sub">
                {{ $notifications->total() }} notifikasi ·
                {{ auth()->user()->unreadNotifications->count() }} belum dibaca
            </div>
        </div>
        <div class="d-flex gap-2">
            @if (auth()->user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="btn-primary-sm" style="background:#64748b;">
                        <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
                    </button>
                </form>
            @endif

            @if ($notifications->total() > 0)
                <form method="POST" action="{{ route('notifications.destroy-all') }}"
                    onsubmit="return confirm('Hapus SEMUA notifikasi? Aksi ini tidak bisa dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-primary-sm"
                        style="background:#fff5f5;color:#dc2626;border:1.5px solid #fca5a5;">
                        <i class="bi bi-trash"></i> Hapus Semua
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="notif-list-card">
        <div class="notif-list-header">
            <div>
                <h6><i class="bi bi-bell-fill me-2" style="color:#1a56db;"></i>Daftar Notifikasi</h6>
                <div class="subtitle">Klik notifikasi untuk lihat detail</div>
            </div>
        </div>

        @forelse($notifications as $n)
            <div class="notif-big-item {{ $n->read_at ? '' : 'unread' }}">
                @if (!$n->read_at)
                    <div class="notif-unread-dot" title="Belum dibaca"></div>
                @endif

                <div class="notif-big-icon {{ $n->data['color'] ?? 'info' }}">
                    <i class="bi {{ $n->data['icon'] ?? 'bi-bell' }}"></i>
                </div>

                <div class="notif-big-body">
                    <div class="notif-big-title">{{ $n->data['title'] ?? 'Notifikasi' }}</div>
                    <div class="notif-big-message">{{ $n->data['message'] ?? '' }}</div>
                    <div class="notif-big-meta">
                        <span><i class="bi bi-clock"></i> {{ $n->created_at->diffForHumans() }}</span>
                        <span>· {{ $n->created_at->isoFormat('D MMM YYYY, HH:mm') }}</span>
                        @if ($n->read_at)
                            <span style="color:#10b981;">· <i class="bi bi-check2"></i> Dibaca</span>
                        @endif
                    </div>
                </div>

                <div class="notif-big-actions">
                    @if (isset($n->data['url']))
                        <a href="{{ route('notifications.show', $n->id) }}" class="btn-view" title="Lihat detail">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('notifications.destroy', $n->id) }}"
                        onsubmit="return confirm('Hapus notifikasi ini?')" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state" style="padding:3rem 1rem;">
                <div class="empty-icon"><i class="bi bi-bell-slash"></i></div>
                <div class="empty-title">Belum ada notifikasi</div>
                <div class="empty-sub">Notifikasi akan muncul saat ada aktivitas penting di sistem</div>
            </div>
        @endforelse

        @if ($notifications->hasPages())
            <div
                style="padding:.85rem 1.25rem; border-top:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem;">
                <span style="font-size:.78rem; color:#94a3b8;">Menampilkan
                    {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} dari
                    {{ $notifications->total() }}</span>
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

@endsection
