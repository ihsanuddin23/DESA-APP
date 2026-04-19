/**
 * Dashboard Warga - Interactive Scripts
 */

document.addEventListener('DOMContentLoaded', function() {

    // Animate status cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all status cards
    document.querySelectorAll('.status-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    // Add ripple effect to status cards
    document.querySelectorAll('.status-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on a link
            if (e.target.closest('a')) return;

            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: radial-gradient(circle, rgba(0,0,0,0.05) 0%, transparent 70%);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
            `;

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Add CSS animation for ripple
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);

    // Refresh activity timestamps every minute
    function updateTimestamps() {
        document.querySelectorAll('.activity-time').forEach(el => {
            // If using relative time (diffForHumans), reload page or fetch new data
            // For now, just a placeholder for future enhancement
        });
    }

    // Optional: Auto-refresh activity log every 5 minutes
    const ACTIVITY_REFRESH_INTERVAL = 5 * 60 * 1000; // 5 minutes

    setInterval(() => {
        // Dispatch custom event for potential Livewire/AJAX refresh
        window.dispatchEvent(new CustomEvent('refresh-activity'));
    }, ACTIVITY_REFRESH_INTERVAL);

    console.log('Dashboard Warga initialized successfully');
});
