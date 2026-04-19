/**
 * Profile Page - Interactive Scripts
 */

document.addEventListener('DOMContentLoaded', function() {

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-custom, .alert');
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

    // Form input focus effects
    const formInputs = document.querySelectorAll('.form-control:not([readonly])');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.form-group')?.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.closest('.form-group')?.classList.remove('focused');
        });
    });

    // Character counter for name field
    const nameInput = document.querySelector('input[name="name"]');
    if (nameInput) {
        const maxLength = 100;
        const minLength = 3;

        // Create counter element
        const counter = document.createElement('small');
        counter.className = 'text-muted ms-auto';
        counter.style.fontSize = '0.75rem';

        const label = nameInput.closest('.form-group')?.querySelector('.form-label');
        if (label) {
            label.style.display = 'flex';
            label.style.justifyContent = 'space-between';
            label.appendChild(counter);
        }

        function updateCounter() {
            const length = nameInput.value.length;
            counter.textContent = `${length}/${maxLength}`;

            if (length < minLength) {
                counter.className = 'text-warning ms-auto';
            } else if (length > maxLength - 10) {
                counter.className = 'text-warning ms-auto';
            } else {
                counter.className = 'text-muted ms-auto';
            }
        }

        nameInput.addEventListener('input', updateCounter);
        updateCounter();
    }

    // Phone number formatter
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            let value = this.value.replace(/\D/g, '');

            // Ensure starts with 0
            if (value.length > 0 && !value.startsWith('0')) {
                value = '0' + value;
            }

            // Limit to 15 digits
            value = value.substring(0, 15);

            this.value = value;
        });
    }

    // Form submission loading state
    const form = document.querySelector('form[method="POST"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalContent = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

                // Re-enable after 3 seconds if form still not submitted (fallback)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                }, 3000);
            }
        });
    }

    // Animate history items on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -30px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.history-item').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(10px)';
        item.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        observer.observe(item);
    });

    // Copy IP to clipboard on click
    document.querySelectorAll('.history-value code').forEach(code => {
        code.style.cursor = 'pointer';
        code.title = 'Klik untuk menyalin';

        code.addEventListener('click', function() {
            navigator.clipboard.writeText(this.textContent).then(() => {
                const originalBg = this.style.background;
                this.style.background = '#dcfce7';
                this.style.color = '#166534';

                setTimeout(() => {
                    this.style.background = originalBg;
                    this.style.color = '';
                }, 1000);
            });
        });
    });

    console.log('Profile page initialized');
});
