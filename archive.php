<?php
/**
 * Archive template file
 */

get_header(); ?>

<!-- Archive Header -->
<div class="bg-gradient-to-br from-blue-600 to-indigo-700 dark:from-blue-900 dark:to-indigo-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
        <div class="text-center max-w-3xl mx-auto">
            <?php if (is_category()): ?>
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-500 bg-opacity-20 text-white mb-4">
                    <?php esc_html_e('Category', 'saxon'); ?>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    <?php single_cat_title(); ?>
                </h1>
                <?php if (category_description()): ?>
                    <div class="text-lg text-blue-100">
                        <?php echo category_description(); ?>
                    </div>
                <?php endif; ?>

            <?php elseif (is_tag()): ?>
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-500 bg-opacity-20 text-white mb-4">
                    <?php esc_html_e('Tag', 'saxon'); ?>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    #<?php single_tag_title(); ?>
                </h1>

            <?php elseif (is_author()): ?>
                <div class="flex justify-center mb-6">
                    <?php echo get_avatar(get_the_author_meta('ID'), 96, '', '', [
                        'class' => 'rounded-full ring-4 ring-white ring-opacity-20',
                    ]); ?>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    <?php echo get_the_author(); ?>
                </h1>
                <?php if (get_the_author_meta('description')): ?>
                    <div class="text-lg text-blue-100">
                        <?php echo get_the_author_meta('description'); ?>
                    </div>
                <?php endif; ?>

            <?php elseif (is_date()): ?>
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-500 bg-opacity-20 text-white mb-4">
                    <?php 
                    if (is_day()) {
                        esc_html_e('Daily Archives', 'saxon');
                    } elseif (is_month()) {
                        esc_html_e('Monthly Archives', 'saxon');
                    } elseif (is_year()) {
                        esc_html_e('Yearly Archives', 'saxon');
                    }
                    ?>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    <?php
                    if (is_day()) {
                        echo get_the_date();
                    } elseif (is_month()) {
                        echo get_the_date('F Y');
                    } elseif (is_year()) {
                        echo get_the_date('Y');
                    }
                    ?>
                </h1>
            <?php endif; ?>

            <!-- Post Count -->
            <div class="mt-6 text-blue-100">
                <?php
                $post_count = $wp_query->found_posts;
                printf(
                    _n('%s Post', '%s Posts', $post_count, 'saxon'),
                    number_format_i18n($post_count)
                );
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Sort Options -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        <?php esc_html_e('Sort by:', 'saxon'); ?>
                    </span>
                    <select class="post-sort form-select rounded-md border-gray-300 dark:border-gray-600 text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="date-desc"><?php esc_html_e('Newest', 'saxon'); ?></option>
                        <option value="date-asc"><?php esc_html_e('Oldest', 'saxon'); ?></option>
                        <option value="title-asc"><?php esc_html_e('Title A-Z', 'saxon'); ?></option>
                        <option value="title-desc"><?php esc_html_e('Title Z-A', 'saxon'); ?></option>
                    </select>
                </div>

                <!-- View Toggle -->
                <div class="flex items-center space-x-2">
                    <button type="button" 
                            class="grid-view-btn p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 active"
                            aria-label="<?php esc_attr_e('Grid view', 'saxon'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button type="button" 
                            class="list-view-btn p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                            aria-label="<?php esc_attr_e('List view', 'saxon'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Posts Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php if (have_posts()): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 posts-grid mode-grid">
            <?php
            while (have_posts()): 
                the_post();
                get_template_part('components/posts/card');
            endwhile;
            ?>
        </div>

        <?php get_template_part('components/shared/pagination'); ?>
        
    <?php else: ?>
        <?php get_template_part('components/shared/no-posts'); ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>