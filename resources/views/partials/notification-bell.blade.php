{{-- ═══════════════════════════════════════════════════════════════════════
     Notification Bell Icon — HTML Only
     CSS & JS sudah inline di layouts/app.blade.php
     ═══════════════════════════════════════════════════════════════════════ --}}

<div class="notif-wrapper" id="notifWrapper" data-dropdown-url="{{ route('notifications.dropdown') }}"
    data-show-url="{{ url('/notifications') }}">

    <button type="button" class="notif-bell-btn" id="notifBellBtn" aria-label="Notifikasi" aria-haspopup="true"
        aria-expanded="false">
        <i class="bi bi-bell-fill"></i>
        <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
    </button>

    <div class="notif-dropdown" id="notifDropdown" role="menu">
        <div class="notif-dropdown-header">
            <div>
                <strong>Notifikasi</strong>
                <span class="notif-unread-text" id="notifUnreadText"></span>
            </div>
            <div class="notif-actions">
                <form method="POST" action="{{ route('notifications.read-all') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="notif-action-btn" title="Tandai semua dibaca">
                        <i class="bi bi-check2-all"></i>
                    </button>
                </form>
                <a href="{{ route('notifications.index') }}" class="notif-action-btn" title="Lihat semua">
                    <i class="bi bi-box-arrow-up-right"></i>
                </a>
            </div>
        </div>

        <div class="notif-dropdown-body" id="notifDropdownBody">
            <div class="notif-loading">
                <i class="bi bi-arrow-clockwise notif-spin"></i> Memuat...
            </div>
        </div>

        <div class="notif-dropdown-footer">
            <a href="{{ route('notifications.index') }}">Lihat semua notifikasi</a>
        </div>
    </div>
</div>
