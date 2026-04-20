/**
 * Authentication Pages - Desa Style Scripts
 */

document.addEventListener('DOMContentLoaded', function() {

    // CSRF Token helper
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Password visibility toggle
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

    // CAPTCHA Refresh
    const refreshBtn = document.getElementById('refreshCaptcha');
    const captchaBox = document.getElementById('captchaQuestion');
    const captchaInput = document.getElementById('captchaInput');

    if (refreshBtn) {
        refreshBtn.addEventListener('click', async function() {
            this.disabled = true;
            const originalContent = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            try {
                const response = await fetch('/captcha/refresh', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                if (data.question) {
                    captchaBox.textContent = data.question;
                    captchaBox.classList.add('shake');
                    setTimeout(() => captchaBox.classList.remove('shake'), 400);
                }

                if (captchaInput) {
                    captchaInput.value = '';
                    captchaInput.focus();
                }

            } catch (error) {
                console.error('Failed to refresh CAPTCHA:', error);
                // Fallback: just clear input
                if (captchaInput) {
                    captchaInput.value = '';
                    captchaInput.focus();
                }
            } finally {
                this.disabled = false;
                this.innerHTML = originalContent;
            }
        });
    }

    // Form submission handling
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');

    if (loginForm && submitBtn) {
        loginForm.addEventListener('submit', function(e) {
            // Basic validation
            const email = document.getElementById('email')?.value;
            const password = document.getElementById('password')?.value;
            const captcha = document.getElementById('captchaInput')?.value;

            if (!email || !password || !captcha) {
                e.preventDefault();

                // Shake the empty fields
                if (!email) document.getElementById('email')?.closest('.form-group-desa')?.classList.add('shake');
                if (!password) document.getElementById('password')?.closest('.form-group-desa')?.classList.add('shake');
                if (!captcha) document.getElementById('captchaInput')?.closest('.form-group-desa')?.classList.add('shake');

                setTimeout(() => {
                    document.querySelectorAll('.shake').forEach(el => el.classList.remove('shake'));
                }, 400);

                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

            // Re-enable after 10 seconds (fallback)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            }, 10000);
        });
    }

    // CAPTCHA input - only allow numbers
    if (captchaInput) {
        captchaInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');

            // Auto-submit if 4 digits entered (optional UX improvement)
            if (this.value.length >= 4) {
                // Could auto-focus to submit button or validate
            }
        });
    }

    // FIX: Focus logic dipindah ke luar blok if (captchaInput)
    // agar berfungsi di semua halaman (login maupun register)
    const emailInput = document.getElementById('email');
    if (emailInput && !emailInput.value) {
        emailInput.focus();
    } else if (document.getElementById('password')) {
        document.getElementById('password').focus();
    }

    // Add ripple effect to submit button
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255,255,255,0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
                left: ${x}px;
                top: ${y}px;
                width: 20px;
                height: 20px;
                margin-left: -10px;
                margin-top: -10px;
            `;

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    }

    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    console.log('Auth page initialized - Desa SID App');
});
