/**
 * Register Page - Interactive Scripts
 */

document.addEventListener('DOMContentLoaded', function() {

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    // Password Strength Meter
    const pwdInput = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');

    if (pwdInput && strengthBar) {
        pwdInput.addEventListener('input', function() {
            const val = this.value;
            let score = 0;

            const checks = [
                val.length >= 8,
                /[A-Z]/.test(val),
                /[a-z]/.test(val),
                /[0-9]/.test(val),
                /[^A-Za-z0-9]/.test(val),
                val.length >= 12
            ];

            score = checks.filter(Boolean).length;

            const levels = [
                { cls: '', label: '', width: '0%' },
                { cls: 'weak', label: '⚠ Sangat Lemah', width: '17%' },
                { cls: 'weak', label: '⚠ Lemah', width: '34%' },
                { cls: 'good', label: '◎ Cukup', width: '51%' },
                { cls: 'strong', label: '✓ Baik', width: '68%' },
                { cls: 'strong', label: '✓ Kuat', width: '84%' },
                { cls: 'very-strong', label: '✓✓ Sangat Kuat', width: '100%' }
            ];

            const lvl = levels[score] || levels[0];

            strengthBar.className = 'strength-bar ' + lvl.cls;
            strengthBar.style.width = lvl.width;

            if (strengthText) {
                strengthText.textContent = lvl.label;
                strengthText.className = 'strength-text ' + lvl.cls;
            }
        });
    }

    // Password Match Checker
    const confirmPwd = document.getElementById('password_confirmation');
    const matchFeedback = document.getElementById('matchFeedback');

    if (confirmPwd && matchFeedback) {
        confirmPwd.addEventListener('input', function() {
            const match = this.value === pwdInput.value;

            if (this.value === '') {
                matchFeedback.textContent = '';
                matchFeedback.className = 'match-feedback';
            } else if (match) {
                matchFeedback.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Password cocok';
                matchFeedback.className = 'match-feedback match';
            } else {
                matchFeedback.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i>Password tidak cocok';
                matchFeedback.className = 'match-feedback mismatch';
            }
        });
    }

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
                const response = await fetch('/captcha/refresh-register', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                if (data.question && captchaBox) {
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

    // NIK input - only allow numbers, max 16 digits
    const nikInput = document.getElementById('nik');
    if (nikInput) {
        nikInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 16);
        });
    }

    // Phone input - format and validate
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');

            // Ensure starts with 0
            if (value.length > 0 && !value.startsWith('0')) {
                value = '0' + value;
            }

            // Limit to 15 digits
            value = value.substring(0, 15);

            this.value = value;
        });
    }

    // Form submission with loading state
    const registerForm = document.getElementById('registerForm');
    const submitBtn = document.getElementById('submitBtn');

    if (registerForm && submitBtn) {
        registerForm.addEventListener('submit', function(e) {
            // Validate password match
            if (pwdInput && confirmPwd && pwdInput.value !== confirmPwd.value) {
                e.preventDefault();
                if (matchFeedback) {
                    matchFeedback.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i>Password tidak cocok!';
                    matchFeedback.className = 'match-feedback mismatch';
                }
                confirmPwd.focus();
                return;
            }

            // Validate terms
            const terms = document.getElementById('terms');
            if (terms && !terms.checked) {
                e.preventDefault();
                terms.focus();
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

    // Auto-focus first empty field
    const firstEmpty = document.querySelector('input[value=""]:not([type="hidden"])');
    if (firstEmpty) {
        firstEmpty.focus();
    }

    console.log('Register page initialized - SID Desa App');
});
