/**
 * App Layout - Main Dashboard Scripts
 */

document.addEventListener('DOMContentLoaded', function() {

    // Mobile sidebar toggle
    window.toggleSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    };

    // Close sidebar when clicking on a link (mobile)
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                toggleSidebar();
            }
        });
    });

    // Session Timeout Warning (25-min warn before 30-min logout)
    const SESSION_TIMEOUT = 30 * 60; // seconds
    const WARN_AT = 5 * 60; // warn 5 minutes before expiry
    let remaining = SESSION_TIMEOUT;

    const warnDiv = document.getElementById('session-warn');
    const warnCounter = document.getElementById('warn-countdown');

    if (warnDiv && warnCounter) {
        const sessionTimer = setInterval(() => {
            remaining--;
            if (remaining <= 0) {
                clearInterval(sessionTimer);
                window.location.href = window.loginRoute || '/login';
            }
            if (remaining <= WARN_AT) {
                warnDiv.style.display = 'block';
                const m = String(Math.floor(remaining / 60)).padStart(2, '0');
                const s = String(remaining % 60).padStart(2, '0');
                warnCounter.textContent = `${m}:${s}`;
            }
        }, 1000);

        // Reset timer on activity
        ['click', 'keydown', 'mousemove', 'scroll'].forEach(evt => {
            document.addEventListener(evt, () => {
                remaining = SESSION_TIMEOUT;
                if (warnDiv) warnDiv.style.display = 'none';
            }, { passive: true });
        });
    }

    // Auto-dismiss alerts with animation
    document.querySelectorAll('.alert-desa.fade.show').forEach(el => {
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(el);
            if (alert) alert.close();
        }, 5000);
    });

    // Add loading state to buttons (after form submit, not on click)
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn && !btn.classList.contains('btn-logout')) {
                // Tunggu satu tick supaya browser sempat submit form dulu
                // baru kemudian disable button (mencegah double-submit)
                setTimeout(() => {
                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';

                    // Re-enable after 10 seconds (fallback safeguard)
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }, 10000);
                }, 0);
            }
        });
    });

    console.log('App layout initialized - SID Desa Dashboard');
});
