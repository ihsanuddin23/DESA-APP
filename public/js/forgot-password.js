/**
 * Forgot Password Page - Interactive Scripts
 */

document.addEventListener('DOMContentLoaded', function() {

    // Form submission with loading state
    const fpForm = document.getElementById('fpForm');
    const submitBtn = document.getElementById('submitBtn');

    if (fpForm && submitBtn) {
        fpForm.addEventListener('submit', function(e) {
            // Validate email
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput?.value.trim();

            if (!email) {
                e.preventDefault();
                emailInput?.focus();
                return;
            }

            // Simple email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                emailInput?.classList.add('is-invalid');

                // Show error message
                let errorDiv = this.querySelector('.email-error');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback email-error';
                    emailInput.parentElement.appendChild(errorDiv);
                }
                errorDiv.innerHTML = '<i class="bi bi-exclamation-circle"></i>Format email tidak valid';

                emailInput?.focus();
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            const originalContent = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';

            // Add ripple effect
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255,255,255,0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
                left: 50%;
                top: 50%;
                width: 20px;
                height: 20px;
                margin-left: -10px;
                margin-top: -10px;
            `;
            submitBtn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);

            // Re-enable after 15 seconds (fallback)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            }, 15000);
        });
    }

    // Remove invalid state on input
    const emailInput = document.querySelector('input[type="email"]');
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const errorDiv = document.querySelector('.email-error');
            if (errorDiv) errorDiv.remove();
        });

        // Focus on load
        emailInput.focus();
    }

    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    console.log('Forgot password page initialized - SID Desa App');
});
