/**
 * Auth Layout - Shared Scripts
 */

document.addEventListener('DOMContentLoaded', function() {

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-desa');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.parentElement) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    });

    // Password visibility toggle (shared functionality)
    document.querySelectorAll('[data-toggle-password]').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.togglePassword;
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
                this.title = 'Sembunyikan password';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
                this.title = 'Tampilkan password';
            }

            input.focus();
        });
    });

    // CSRF token helper for AJAX
    window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // ❌ HAPUS - Timer tidak perlu di halaman auth
    // const sessionTimer = document.getElementById('session-timer');
    // if (sessionTimer) { ... }

    console.log('Auth layout initialized - SID Desa App');
});
