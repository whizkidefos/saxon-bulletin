<?php
/**
 * Pagination Component
 */

if ($GLOBALS['wp_query']->max_num_pages <= 1) {
    return;
}

$current = get_query_var('paged') ? get_query_var('paged') : 1;
$total = $GLOBALS['wp_query']->max_num_pages;
?>

<nav class="mt-12" aria-label="<?php esc_attr_e('Pagination', 'saxon'); ?>">
    <ul class="flex justify-center items-center space-x-2">
        <?php if ($current > 1): ?>
            <li>
                <a href="<?php echo get_pagenum_link($current - 1); ?>" 
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                   aria-label="<?php esc_attr_e('Previous page', 'saxon'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            </li>
        <?php endif; ?>

        <?php
        $start = max(1, $current - 2);
        $end = min($total, $current + 2);

        if ($start > 1) {
            echo '<li><a href="' . get_pagenum_link(1) . '" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">1</a></li>';
            if ($start > 2) {
                echo '<li><span class="px-2 text-gray-500 dark:text-gray-400">&hellip;</span></li>';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            if ($i == $current) {
                echo '<li><span class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-md">' . $i . '</span></li>';
            } else {
                echo '<li><a href="' . get_pagenum_link($i) . '" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">' . $i . '</a></li>';
            }
        }

        if ($end < $total) {
            if ($end < $total - 1) {
                echo '<li><span class="px-2 text-gray-500 dark:text-gray-400">&hellip;</span></li>';
            }
            echo '<li><a href="' . get_pagenum_link($total) . '" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">' . $total . '</a></li>';
        }
        ?>

        <?php if ($current < $total): ?>
            <li>
                <a href="<?php echo get_pagenum_link($current + 1); ?>" 
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                   aria-label="<?php esc_attr_e('Next page', 'saxon'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>