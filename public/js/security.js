/**
 * Security Page - Interactive Scripts
 */

document.addEventListener('DOMContentLoaded', function() {

    // Password visibility toggle
    document.querySelectorAll('[data-toggle-password]').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.togglePassword;
            const input = document.getElementById(targetId) ||
                         this.closest('.input-group-custom').querySelector('input');
            const icon = this.querySelector('i');

            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
                this.setAttribute('aria-label', 'Sembunyikan password');
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
                this.setAttribute('aria-label', 'Tampilkan password');
            }

            // Return focus to input
            input.focus();
        });
    });

    // Password strength meter
    const newPwdInput = document.getElementById('newPwd');
    const strengthBar = document.getElementById('pwdStrengthBar');
    const strengthLabel = document.getElementById('pwdStrengthLabel');

    if (newPwdInput && strengthBar) {
        newPwdInput.addEventListener('input', function() {
            const password = this.value;
            let score = 0;
            let strength = '';
            let label = '';

            if (password.length > 0) {
                // Check criteria
                const hasLength = password.length >= 8;
                const hasUpper = /[A-Z]/.test(password);
                const hasLower = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[^A-Za-z0-9]/.test(password);

                score = [hasLength, hasUpper, hasLower, hasNumber, hasSpecial]
                    .filter(Boolean).length;

                // Determine strength
                const strengthMap = {
                    0: { class: '', label: '' },
                    1: { class: 'weak', label: 'Lemah' },
                    2: { class: 'fair', label: 'Cukup' },
                    3: { class: 'good', label: 'Baik' },
                    4: { class: 'strong', label: 'Kuat' },
                    5: { class: 'very-strong', label: 'Sangat Kuat' }
                };

                const result = strengthMap[score];
                strengthBar.className = 'strength-bar ' + result.class;

                if (strengthLabel) {
                    strengthLabel.textContent = result.label;
                    strengthLabel.className = 'strength-label ' + result.class;
                }
            } else {
                strengthBar.className = 'strength-bar';
                strengthBar.style.width = '0%';
                if (strengthLabel) {
                    strengthLabel.textContent = '';
                }
            }

            // Check match if confirm field has value
            const confirmPwd = document.getElementById('confirmPwd');
            if (confirmPwd && confirmPwd.value) {
                checkPasswordMatch();
            }
        });
    }

    // Password match checker
    const confirmPwdInput = document.getElementById('confirmPwd');
    const matchMsg = document.getElementById('pwdMatchMsg');

    function checkPasswordMatch() {
        if (!confirmPwdInput || !matchMsg) return;

        const password = newPwdInput ? newPwdInput.value : '';
        const confirm = confirmPwdInput.value;

        if (confirm === '') {
            matchMsg.textContent = '';
            matchMsg.className = 'match-indicator';
            return;
        }

        if (password === confirm) {
            matchMsg.innerHTML = '<i class="bi bi-check-circle-fill"></i> Password cocok';
            matchMsg.className = 'match-indicator match';
        } else {
            matchMsg.innerHTML = '<i class="bi bi-x-circle-fill"></i> Password tidak cocok';
            matchMsg.className = 'match-indicator mismatch';
        }
    }

    if (confirmPwdInput) {
        confirmPwdInput.addEventListener('input', checkPasswordMatch);
    }

    // Form submission with loading state
    const pwdForm = document.getElementById('pwdForm');
    if (pwdForm) {
        pwdForm.addEventListener('submit', function(e) {
            // Validate passwords match
            const password = newPwdInput ? newPwdInput.value : '';
            const confirm = confirmPwdInput ? confirmPwdInput.value : '';

            if (password !== confirm) {
                e.preventDefault();
                if (matchMsg) {
                    matchMsg.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Password tidak cocok!';
                    matchMsg.className = 'match-indicator mismatch';
                }
                confirmPwdInput?.focus();
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalContent = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

                // Re-enable after 5 seconds (fallback)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                }, 5000);
            }
        });
    }

    // 2FA disable confirmation
    const disable2faBtn = document.querySelector('button[onclick*="menonaktifkan 2FA"]');
    if (disable2faBtn) {
        disable2faBtn.addEventListener('click', function(e) {
            if (!confirm('Yakin ingin menonaktifkan 2FA?\n\nIni akan mengurangi keamanan akun Anda dan membuat akun lebih rentan terhadap akses tidak sah.')) {
                e.preventDefault();
            }
        });
    }

    // Auto-focus first input with error
    const firstError = document.querySelector('.is-invalid');
    if (firstError) {
        firstError.focus();
    }

    // Add ripple effect to buttons
    document.querySelectorAll('.btn-submit, .btn-2fa-enable, .btn-2fa-disable').forEach(btn => {
        btn.addEventListener('click', function(e) {
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
    });

    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    console.log('Security page initialized');
});
