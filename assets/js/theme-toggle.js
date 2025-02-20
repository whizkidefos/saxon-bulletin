/**
 * Theme Toggle Functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    const html = document.documentElement;

    function setTheme(theme) {
        // Set class on html element
        html.classList.remove('theme-light', 'theme-dark');
        html.classList.add(`theme-${theme}`);

        // Update data attribute (for CSS selectors)
        html.setAttribute('data-theme', theme);

        // Store preference
        localStorage.setItem('theme', theme);

        // Update icons
        if (theme === 'dark') {
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
        } else {
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
        }

        // Dispatch event for other scripts
        const event = new CustomEvent('themeChanged', { detail: { theme } });
        html.dispatchEvent(event);
    }

    // Handle initial theme
    function initializeTheme() {
        const savedTheme = localStorage.getItem('theme');
        const systemTheme = prefersDarkScheme.matches ? 'dark' : 'light';
        setTheme(savedTheme || systemTheme);
    }

    // Initialize theme when DOM loads
    initializeTheme();

    // Toggle theme on button click
    themeToggleBtn?.addEventListener('click', () => {
        const currentTheme = html.getAttribute('data-theme');
        setTheme(currentTheme === 'dark' ? 'light' : 'dark');
    });

    // Watch for system theme changes
    prefersDarkScheme.addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });
});