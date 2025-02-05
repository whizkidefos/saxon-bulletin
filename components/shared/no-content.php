<?php
/**
 * No Content Component
 */
?>

<div class="text-center py-12">
    <div class="max-w-lg mx-auto">
        <svg class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
        </svg>
        
        <h2 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">
            <?php
            if (is_search()) {
                esc_html_e('No results found', 'saxon');
            } else {
                esc_html_e('No posts yet', 'saxon');
            }
            ?>
        </h2>
        
        <p class="mt-2 text-gray-600 dark:text-gray-400">
            <?php
            if (is_search()) {
                esc_html_e('Try adjusting your search terms or explore our categories below.', 'saxon');
            } else {
                esc_html_e('Check back later for updates or explore our categories below.', 'saxon');
            }
            ?>
        </p>

        <div class="mt-6">
            <?php if (is_search()): ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <?php esc_html_e('Back to Homepage', 'saxon'); ?>
                </a>
            <?php else: ?>
                <?php get_search_form(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>