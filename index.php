<?php
/**
 * Main index template
 */

get_header(); ?>

<!-- Hero Section -->
<div class="bg-gradient-to-br from-blue-600 to-indigo-700 dark:from-blue-900 dark:to-indigo-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white sm:text-5xl">
                <?php esc_html_e('The Blog', 'saxon'); ?>
            </h1>
            <p class="mt-4 text-xl text-blue-100">
                <?php 
                $total_posts = wp_count_posts()->publish;
                printf(
                    _n('Discover %s article and counting', 'Discover %s articles and counting', $total_posts, 'saxon'),
                    number_format_i18n($total_posts)
                );
                ?>
            </p>
        </div>
    </div>
</div>

<!-- Filters and Search Section -->
<div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="relative">
                    <input type="search" 
                           id="posts-search"
                           class="block w-full rounded-md border-gray-300 dark:border-gray-600 pl-10 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm py-2"
                           placeholder="<?php esc_attr_e('Search articles...', 'saxon'); ?>">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="relative">
                    <select id="category-filter" 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm py-2">
                        <option value=""><?php esc_html_e('All Categories', 'saxon'); ?></option>
                        <?php
                        $categories = get_categories([
                            'orderby' => 'count',
                            'order'   => 'DESC'
                        ]);
                        foreach ($categories as $category) {
                            printf(
                                '<option value="%s">%s (%s)</option>',
                                esc_attr($category->slug),
                                esc_html($category->name),
                                number_format_i18n($category->count)
                            );
                        }
                        ?>
                    </select>
                </div>

                <!-- Sort Options -->
                <div class="relative">
                    <select id="sort-options" 
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm py-2">
                        <option value="date-desc"><?php esc_html_e('Newest First', 'saxon'); ?></option>
                        <option value="date-asc"><?php esc_html_e('Oldest First', 'saxon'); ?></option>
                        <option value="title-asc"><?php esc_html_e('Title A-Z', 'saxon'); ?></option>
                        <option value="title-desc"><?php esc_html_e('Title Z-A', 'saxon'); ?></option>
                        <option value="comments"><?php esc_html_e('Most Discussed', 'saxon'); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Popular Categories -->
<div class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-wrap gap-3 justify-center">
            <?php foreach (array_slice($categories, 0, 8) as $category): ?>
                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                   class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <?php echo esc_html($category->name); ?>
                    <span class="ml-2 text-gray-400 dark:text-gray-500">
                        <?php echo number_format_i18n($category->count); ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Posts by Category -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php
    foreach ($categories as $category):
        $posts = get_posts([
            'category'       => $category->term_id,
            'posts_per_page' => 6,
        ]);

        if ($posts): ?>
            <section class="mb-16 last:mb-0 category-section" data-category="<?php echo esc_attr($category->slug); ?>">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <?php echo esc_html($category->name); ?>
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <?php echo wp_trim_words($category->description, 20); ?>
                        </p>
                    </div>
                    
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <?php 
                        printf(
                            esc_html__('View all %s posts', 'saxon'),
                            number_format_i18n($category->count)
                        ); 
                        ?>
                        <svg class="ml-2 -mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    foreach ($posts as $post):
                        setup_postdata($post);
                        get_template_part('components/posts/card');
                    endforeach;
                    wp_reset_postdata();
                    ?>
                </div>
            </section>
        <?php endif;
    endforeach; ?>
</div>

<!-- No Results Message -->
<div id="no-results" class="hidden max-w-lg mx-auto text-center py-12">
    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">
        <?php esc_html_e('No posts found', 'saxon'); ?>
    </h3>
    <p class="mt-1 text-gray-500 dark:text-gray-400">
        <?php esc_html_e('Try adjusting your search or filter to find what you\'re looking for.', 'saxon'); ?>
    </p>
    <button type="button" 
            onclick="resetFilters()"
            class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <?php esc_html_e('Reset Filters', 'saxon'); ?>
    </button>
</div>

<?php get_footer(); ?>