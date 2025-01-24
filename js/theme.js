document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    
    // Check saved theme or system preference
    const savedTheme = localStorage.getItem('theme') || 
                      (prefersDarkScheme.matches ? 'dark' : 'light');
    
    document.body.classList.add(`${savedTheme}-mode`);
    
    themeToggle.addEventListener('click', function() {
        const currentTheme = document.body.classList.contains('dark-mode') ? 
                           'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.body.classList.remove(`${currentTheme}-mode`);
        document.body.classList.add(`${newTheme}-mode`);
        localStorage.setItem('theme', newTheme);
    });
});