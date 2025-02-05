<?php
/**
 * No Posts Found Component
 */
?>

<div class="no-results text-center py-12">
    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
    </svg>

    <div class="mt-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            <?php 
            if (is_search()) {
                esc_html_e('No posts found for your search', 'saxon');
            } else {
                esc_html_e('No posts found', 'saxon');
            }
            ?>
        </h3>

        <p class="mt-2 text-gray-500 dark:text-gray-400">
            <?php
            if (is_search()) {
                esc_html_e('Try adjusting your search terms or browse through our categories.', 'saxon');
            } else {
                esc_html_e('Check back later for new posts or explore other categories.', 'saxon');
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
                <?php get_template_part('components/shared/categories-list'); ?>
            <?php endif; ?>
        </div>
    </div>
</div>