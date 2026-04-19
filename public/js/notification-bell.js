/* ════════════════════════════════════════════════════════════════════════
   NOTIFICATION BELL - JavaScript
   Mengelola dropdown bell icon: fetch data, render list, polling,
   positioning, dan event handling.
   ════════════════════════════════════════════════════════════════════════ */

(function () {
    'use strict';

    // ── DOM ready check ──
    function init() {
        const $wrapper = document.getElementById('notifWrapper');
        if (!$wrapper) return; // partial tidak di-render

        const DROPDOWN_URL = $wrapper.dataset.dropdownUrl;
        const SHOW_URL     = $wrapper.dataset.showUrl;
        const POLL_MS      = 60000; // cek setiap 60 detik

        const $btn      = document.getElementById('notifBellBtn');
        const $dropdown = document.getElementById('notifDropdown');
        const $badge    = document.getElementById('notifBadge');
        const $body     = document.getElementById('notifDropdownBody');
        const $unread   = document.getElementById('notifUnreadText');

        if (!$btn || !$dropdown || !$badge || !$body) {
            console.warn('[Notif Bell] Element tidak ditemukan.');
            return;
        }

        let notifData = { notifications: [], unread_count: 0 };

        /**
         * Posisikan dropdown tepat di bawah bell icon.
         * Karena pakai position:fixed, harus re-calculate tiap open.
         */
        function positionDropdown() {
            const btnRect = $btn.getBoundingClientRect();
            const viewportWidth = window.innerWidth;

            // Top = bawah bell + 8px margin
            const top = btnRect.bottom + 8;

            // Right = jarak dari tepi kanan viewport ke kanan bell
            let right = viewportWidth - btnRect.right;
            if (right < 16) right = 16; // minimal 16px dari tepi

            $dropdown.style.top   = top + 'px';
            $dropdown.style.right = right + 'px';
            $dropdown.style.left  = 'auto';
        }

        /**
         * Fetch data notifikasi dari server.
         */
        async function fetchNotifications() {
            try {
                const res = await fetch(DROPDOWN_URL, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                if (!res.ok) return;
                notifData = await res.json();
                renderBadge();
                if ($dropdown.classList.contains('show')) renderList();
            } catch (err) {
                console.warn('[Notif Bell] Gagal load:', err);
            }
        }

        function renderBadge() {
            const count = notifData.unread_count || 0;
            if (count > 0) {
                $badge.textContent = count > 99 ? '99+' : count;
                $badge.style.display = 'flex';
                $unread.textContent = '(' + count + ' belum dibaca)';
            } else {
                $badge.style.display = 'none';
                $unread.textContent = '';
            }
        }

        function renderList() {
            const items = notifData.notifications || [];

            if (items.length === 0) {
                $body.innerHTML =
                    '<div class="notif-empty">' +
                        '<i class="bi bi-bell-slash"></i>' +
                        '<div class="notif-empty-text">Belum ada notifikasi</div>' +
                    '</div>';
                return;
            }

            $body.innerHTML = items.map(function (n) {
                const unreadClass = n.read ? '' : 'unread';
                const color = escapeHtml(n.color);
                const icon  = escapeHtml(n.icon);
                const title = escapeHtml(n.title);
                const msg   = escapeHtml(n.message);
                const time  = escapeHtml(n.created_at);
                const url   = SHOW_URL + '/' + encodeURIComponent(n.id);

                return (
                    '<a href="' + url + '" class="notif-item ' + unreadClass + '">' +
                        '<div class="notif-item-icon ' + color + '">' +
                            '<i class="bi ' + icon + '"></i>' +
                        '</div>' +
                        '<div class="notif-item-body">' +
                            '<div class="notif-item-title">' + title + '</div>' +
                            '<div class="notif-item-message">' + msg + '</div>' +
                            '<div class="notif-item-time">' + time + '</div>' +
                        '</div>' +
                        '<div class="notif-item-dot"></div>' +
                    '</a>'
                );
            }).join('');
        }

        function escapeHtml(s) {
            return String(s == null ? '' : s).replace(/[&<>"']/g, function (ch) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[ch];
            });
        }

        // ── Toggle dropdown saat bell diklik ──
        $btn.addEventListener('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            const isOpen = $dropdown.classList.toggle('show');
            $btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (isOpen) {
                positionDropdown();
                renderList();
            }
        });

        // ── Close dropdown saat klik di luar ──
        document.addEventListener('click', function (e) {
            if (!$wrapper.contains(e.target) && !$dropdown.contains(e.target)) {
                $dropdown.classList.remove('show');
                $btn.setAttribute('aria-expanded', 'false');
            }
        });

        // ── Re-position saat resize/scroll ──
        window.addEventListener('resize', function () {
            if ($dropdown.classList.contains('show')) positionDropdown();
        });
        window.addEventListener('scroll', function () {
            if ($dropdown.classList.contains('show')) positionDropdown();
        }, true);

        // ── Initial load + polling ──
        fetchNotifications();
        setInterval(fetchNotifications, POLL_MS);
    }

    // Run after DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
