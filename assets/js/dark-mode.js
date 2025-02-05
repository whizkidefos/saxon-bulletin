document.addEventListener('DOMContentLoaded', function() {
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
    const themeToggleBtn = document.getElementById('theme-toggle');

    // Function to set theme
    function setTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('theme-dark');
            document.documentElement.classList.remove('theme-light');
            themeToggleLightIcon.classList.remove('hidden');
            themeToggleDarkIcon.classList.add('hidden');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.add('theme-light');
            document.documentElement.classList.remove('theme-dark');
            themeToggleLightIcon.classList.add('hidden');
            themeToggleDarkIcon.classList.remove('hidden');
            localStorage.setItem('theme', 'light');
        }
    }

    // Check initial theme
    if (localStorage.getItem('theme') === 'dark' || 
        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        setTheme('dark');
    } else {
        setTheme('light');
    }

    // Toggle theme on button click
    themeToggleBtn.addEventListener('click', function() {
        if (document.documentElement.classList.contains('theme-dark')) {
            setTheme('light');
        } else {
            setTheme('dark');
        }
    });

    // Watch for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        if (!localStorage.getItem('theme')) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });
});