document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const searchInput = document.getElementById('posts-search');
    const categoryFilter = document.getElementById('category-filter');
    const sortOptions = document.getElementById('sort-options');
    const noResults = document.getElementById('no-results');
    const categorySections = document.querySelectorAll('.category-section');

    // Filter and Sort Posts
    function filterAndSortPosts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const sortOption = sortOptions.value;
        let hasVisiblePosts = false;

        categorySections.forEach(section => {
            const sectionCategory = section.dataset.category;
            const posts = section.querySelectorAll('.post-card');
            let visiblePosts = 0;

            // First, handle filtering
            posts.forEach(post => {
                const title = post.querySelector('.post-title').textContent.toLowerCase();
                const excerpt = post.querySelector('.post-excerpt')?.textContent.toLowerCase() || '';
                const shouldShow = (title.includes(searchTerm) || excerpt.includes(searchTerm)) &&
                                 (!selectedCategory || sectionCategory === selectedCategory);
                
                post.classList.toggle('hidden', !shouldShow);
                if (shouldShow) {
                    visiblePosts++;
                    hasVisiblePosts = true;
                }
            });

            // Show/hide section based on visible posts
            section.classList.toggle('hidden', visiblePosts === 0);

            // Handle sorting if there are visible posts
            if (visiblePosts > 0) {
                const postsArray = Array.from(posts).filter(post => !post.classList.contains('hidden'));
                const sortedPosts = sortPosts(postsArray, sortOption);
                const postsContainer = section.querySelector('.grid');
                sortedPosts.forEach(post => postsContainer.appendChild(post));
            }
        });

        // Show/hide no results message
        noResults.classList.toggle('hidden', hasVisiblePosts);
    }

    // Sort posts based on selected option
    function sortPosts(posts, sortOption) {
        return posts.sort((a, b) => {
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
                case 'comments':
                    return (parseInt(b.dataset.comments) || 0) - (parseInt(a.dataset.comments) || 0);
                default:
                    return 0;
            }
        });
    }

    // Reset filters
    window.resetFilters = function() {
        searchInput.value = '';
        categoryFilter.value = '';
        sortOptions.value = 'date-desc';
        filterAndSortPosts();
    };

    // Debounce search input
    let searchTimeout;
    searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterAndSortPosts, 300);
    });

    // Category filter change
    categoryFilter?.addEventListener('change', filterAndSortPosts);

    // Sort options change
    sortOptions?.addEventListener('change', filterAndSortPosts);

    // Handle URL parameters on page load
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('s')) searchInput.value = urlParams.get('s');
    if (urlParams.has('category')) categoryFilter.value = urlParams.get('category');
    if (urlParams.has('sort')) sortOptions.value = urlParams.get('sort');
    if (urlParams.has('s') || urlParams.has('category') || urlParams.has('sort')) {
        filterAndSortPosts();
    }

    // Update URL when filters change
    function updateURL() {
        const params = new URLSearchParams();
        if (searchInput.value) params.set('s', searchInput.value);
        if (categoryFilter.value) params.set('category', categoryFilter.value);
        if (sortOptions.value !== 'date-desc') params.set('sort', sortOptions.value);
        
        const newURL = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
        history.replaceState({}, '', newURL);
    }

    // Add URL updating to all filter changes
    searchInput.addEventListener('change', updateURL);
    categoryFilter.addEventListener('change', updateURL);
    sortOptions.addEventListener('change', updateURL);
});

// Add smooth scroll animation for category jumps
document.querySelectorAll('a[href^="#category-"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const target = document.querySelector(targetId);
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});