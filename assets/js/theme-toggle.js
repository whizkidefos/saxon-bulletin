/**
 * Theme Toggle Functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    const html = document.documentElement;

    // Function to set theme
    function setTheme(theme) {
        // Remove any existing theme
        html.removeAttribute('data-theme');
        
        // Set new theme
        html.setAttribute('data-theme', theme);

        // Store preference
        localStorage.setItem('theme', theme);

        // Update icons visibility (they're controlled by CSS now)
        darkIcon.style.display = theme === 'dark' ? 'block' : 'none';
        lightIcon.style.display = theme === 'light' ? 'block' : 'none';

        // Dispatch event for other scripts
        const event = new CustomEvent('themeChanged', { detail: { theme } });
        document.dispatchEvent(event);
    }

    // Handle initial theme
    function initializeTheme() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            setTheme(savedTheme);
        } else {
            setTheme(prefersDarkScheme.matches ? 'dark' : 'light');
        }
    }

    // Initialize theme when DOM loads
    initializeTheme();

    // Toggle theme on button click
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme') || 'light';
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });
    }

    // Listen for system theme changes
    prefersDarkScheme.addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });
});