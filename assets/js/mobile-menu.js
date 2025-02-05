// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = mobileMenuBtn.querySelector('svg:first-child');
    const closeIcon = mobileMenuBtn.querySelector('svg:last-child');
    const html = document.documentElement;

    function toggleMenu() {
        const isMenuOpen = mobileMenu.classList.contains('block');
        
        // Toggle menu visibility
        if (isMenuOpen) {
            mobileMenu.classList.remove('block');
            mobileMenu.classList.add('hidden');
            menuIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            html.style.overflow = '';
        } else {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('block');
            menuIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            html.style.overflow = 'hidden';
        }

        // Update ARIA attributes
        mobileMenuBtn.setAttribute('aria-expanded', !isMenuOpen);
    }

    // Toggle menu on button click
    mobileMenuBtn.addEventListener('click', toggleMenu);

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const isClickInside = mobileMenuBtn.contains(event.target) || mobileMenu.contains(event.target);
        
        if (!isClickInside && mobileMenu.classList.contains('block')) {
            toggleMenu();
        }
    });

    // Close menu on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && mobileMenu.classList.contains('block')) {
            toggleMenu();
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768 && mobileMenu.classList.contains('block')) {
            toggleMenu();
        }
    });
});