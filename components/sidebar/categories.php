<?php
/**
 * Categories Component
 */

$categories = get_categories([
    'orderby'    => 'count',
    'order'      => 'DESC',
    'hide_empty' => true,
]);

if ($categories): ?>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            <?php esc_html_e('Categories', 'saxon'); ?>
        </h2>

        <div class="space-y-2">
            <?php foreach ($categories as $category): ?>
                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                   class="flex items-center justify-between py-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                    <span class="truncate"><?php echo esc_html($category->name); ?></span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                        <?php echo number_format_i18n($category->count); ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>