/**
 * Takalo - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {

    // ================================
    // Mobile Menu Toggle
    // ================================
    const navbarToggle = document.querySelector('.navbar-toggle');
    const navbarMenu = document.querySelector('.navbar-menu');

    if (navbarToggle && navbarMenu) {
        navbarToggle.addEventListener('click', function() {
            navbarMenu.classList.toggle('open');
            const icon = navbarToggle.querySelector('.icon-open');
            const iconClose = navbarToggle.querySelector('.icon-close');
            if (icon && iconClose) {
                icon.style.display = navbarMenu.classList.contains('open') ? 'none' : 'block';
                iconClose.style.display = navbarMenu.classList.contains('open') ? 'block' : 'none';
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!navbarToggle.contains(e.target) && !navbarMenu.contains(e.target)) {
                navbarMenu.classList.remove('open');
            }
        });
    }

    // ================================
    // Back to Top Button
    // ================================
    const backToTop = document.querySelector('.back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ================================
    // Auto-dismiss alerts
    // ================================
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // ================================
    // Confirm delete actions
    // ================================
    const deleteForms = document.querySelectorAll('form[data-confirm]');
    deleteForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const message = form.getAttribute('data-confirm') || 'Êtes-vous sûr ?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // ================================
    // Active nav link detection
    // ================================
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.navbar-menu li a');
    navLinks.forEach(function(link) {
        const href = link.getAttribute('href');
        if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
            link.classList.add('active');
        }
    });

    // ================================
    // Image gallery for product detail
    // ================================
    const mainImage = document.querySelector('.product-gallery-main img');
    const thumbs = document.querySelectorAll('.product-gallery-thumbs img');

    thumbs.forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            if (mainImage) {
                mainImage.src = this.src;
                mainImage.alt = this.alt;
                thumbs.forEach(function(t) { t.classList.remove('active'); });
                this.classList.add('active');
            }
        });
    });

    // ================================
    // Smooth scroll for anchor links
    // ================================
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ================================
    // Radio card selection highlight
    // ================================
    const radioCards = document.querySelectorAll('.radio-card');
    radioCards.forEach(function(card) {
        card.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                radioCards.forEach(function(c) {
                    c.style.borderColor = '';
                    c.style.background = '';
                });
                this.style.borderColor = 'var(--primary)';
                this.style.background = 'var(--primary-light)';
            }
        });
    });
});
