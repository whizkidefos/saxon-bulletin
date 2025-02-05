document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const gridViewBtn = document.querySelector('.grid-view-btn');
    const listViewBtn = document.querySelector('.list-view-btn');
    const postsGrid = document.querySelector('.posts-grid');
    const sortSelect = document.querySelector('.post-sort');

    // View Toggle
    function setView(view) {
        const postCards = document.querySelectorAll('.post-card');
        
        if (view === 'grid') {
            postsGrid.classList.remove('mode-list');
            postsGrid.classList.add('mode-grid');
            gridViewBtn.classList.add('bg-blue-50', 'text-blue-600', 'dark:bg-blue-900', 'dark:text-blue-400');
            listViewBtn.classList.remove('bg-blue-50', 'text-blue-600', 'dark:bg-blue-900', 'dark:text-blue-400');
            
            // Update post card layouts
            postCards.forEach(card => {
                card.classList.remove('md:flex');
                card.querySelector('.post-thumbnail')?.classList.remove('md:w-1/3');
                card.querySelector('.post-content')?.classList.remove('md:w-2/3');
            });
        } else {
            postsGrid.classList.remove('mode-grid');
            postsGrid.classList.add('mode-list');
            listViewBtn.classList.add('bg-blue-50', 'text-blue-600', 'dark:bg-blue-900', 'dark:text-blue-400');
            gridViewBtn.classList.remove('bg-blue-50', 'text-blue-600', 'dark:bg-blue-900', 'dark:text-blue-400');
            
            // Update post card layouts
            postCards.forEach(card => {
                card.classList.add('md:flex');
                card.querySelector('.post-thumbnail')?.classList.add('md:w-1/3');
                card.querySelector('.post-content')?.classList.add('md:w-2/3');
            });
        }
        localStorage.setItem('saxon-view-mode', view);
    }

    // Initialize view from localStorage
    if (gridViewBtn && listViewBtn && postsGrid) {
        const savedView = localStorage.getItem('saxon-view-mode') || 'grid';
        setView(savedView);

        // View toggle event listeners
        gridViewBtn.addEventListener('click', () => setView('grid'));
        listViewBtn.addEventListener('click', () => setView('list'));
    }

    // Sort functionality
    function sortPosts(sortOption) {
        const posts = Array.from(document.querySelectorAll('.post-card'));
        const sortedPosts = posts.sort((a, b) => {
            switch (sortOption) {
                case 'date-desc':
                    return new Date(b.dataset.date) - new Date(a.dataset.date);
                case 'date-asc':
                    return new Date(a.dataset.date) - new Date(b.dataset.date);
                case 'title-asc':
                    return a.querySelector('.post-title').textContent
                        .localeCompare(b.querySelector('.post-title').textContent);
                case 'title-desc':
                    return b.querySelector('.post-title').textContent
                        .localeCompare(a.querySelector('.post-title').textContent);
                default:
                    return 0;
            }
        });

        const container = postsGrid;
        sortedPosts.forEach(post => container.appendChild(post));

        // Add fade-in animation
        posts.forEach((post, index) => {
            post.style.opacity = '0';
            post.style.transform = 'translateY(20px)';
            setTimeout(() => {
                post.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                post.style.opacity = '1';
                post.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }

    // Sort change event listener
    sortSelect?.addEventListener('change', (e) => {
        sortPosts(e.target.value);
        
        // Update URL
        const url = new URL(window.location);
        url.searchParams.set('sort', e.target.value);
        window.history.replaceState({}, '', url);
    });

    // Initialize sort from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const sortParam = urlParams.get('sort');
    if (sortParam && sortSelect) {
        sortSelect.value = sortParam;
        sortPosts(sortParam);
    }
});