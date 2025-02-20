<?php get_header(); ?>

<!-- Hero Section with Featured Post -->
<!--<section class="relative bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-900 dark:to-indigo-900">
    <?php
    $featured_post = new WP_Query([
        'posts_per_page' => 1,
        'meta_key'       => '_saxon_featured',
        'meta_value'     => '1'
    ]);

    if ($featured_post->have_posts()):
        while ($featured_post->have_posts()): $featured_post->the_post(); ?>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="md:w-2/3">
                <?php
                $categories = get_the_category();
                if ($categories): ?>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php foreach ($categories as $category): ?>
                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 bg-opacity-20 text-white hover:bg-opacity-30 transition">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    <a href="<?php the_permalink(); ?>" class="hover:text-blue-100 transition">
                        <?php the_title(); ?>
                    </a>
                </h1>

                <div class="text-xl text-blue-100 mb-6">
                    <?php echo wp_trim_words(get_the_excerpt(), 30); ?>
                </div>

                <div class="flex items-center space-x-4 text-white">
                    <?php if ($avatar = get_avatar(get_the_author_meta('ID'), 40)): ?>
                        <div class="flex-shrink-0">
                            <?php echo str_replace('avatar', 'rounded-full', $avatar); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <div class="font-medium">
                            <?php the_author_posts_link(); ?>
                        </div>
                        <div class="text-sm text-blue-100">
                            <?php echo get_the_date(); ?> · <?php echo saxon_reading_time(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        endwhile;
        wp_reset_postdata();
    endif; ?>
</section> -->

<?php get_template_part('components/featured-carousel'); ?>

<!-- Popular Categories -->
<section class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                <?php esc_html_e('Explore Topics', 'saxon'); ?>
            </h2>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                <?php esc_html_e('Discover content across different categories', 'saxon'); ?>
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            $categories = get_categories([
                'orderby'    => 'count',
                'order'      => 'DESC',
                'number'     => 8,
                'hide_empty' => true,
            ]);

            foreach ($categories as $category): 
                $category_link = get_category_link($category->term_id);
                $category_image = get_field('category_image', 'category_' . $category->term_id); // If using ACF
                ?>
                <a href="<?php echo esc_url($category_link); ?>" 
                   class="group relative overflow-hidden rounded-lg shadow-md bg-white dark:bg-gray-700 p-6 hover:shadow-lg transition duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        <?php echo esc_html($category->name); ?>
                    </h3>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        <?php echo wp_trim_words($category->description, 10); ?>
                    </p>
                    
                    <span class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400">
                        <?php printf(_n('%s Post', '%s Posts', $category->count, 'saxon'), number_format_i18n($category->count)); ?>
                        <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Posts Grid -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <?php esc_html_e('Latest Posts', 'saxon'); ?>
                </h2>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
                    <?php esc_html_e('Stay updated with our newest stories', 'saxon'); ?>
                </p>
            </div>
            
            <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition">
                <?php esc_html_e('View All Posts', 'saxon'); ?>
                <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $args = array(
                'post_type'           => 'post',
                'posts_per_page'      => 6,
                'ignore_sticky_posts' => false,
                'post_status'         => 'publish',
                'meta_query'          => array(
                    array(
                        'key'     => '_saxon_featured',
                        'compare' => 'NOT EXISTS'
                    )
                )
            );

            $latest_posts = new WP_Query($args);

            if ($latest_posts->have_posts()):
                while ($latest_posts->have_posts()): 
                    $latest_posts->the_post();
                    get_template_part('components/posts/card', null, [
                        'view' => 'list', // Set default view to list
                        'meta' => false    // Disable meta information
                    ]);
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<!-- Trending Posts Section -->
<!--<//?php get_template_part('components/latest-trending-posts'); ?> -->

<!-- Newsletter Section -->
<section class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 p-8 md:p-12 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <pattern id="dots" width="10" height="10" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1" fill="currentColor"/>
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#dots)"/>
                </svg>
            </div>

            <div class="relative md:w-2/3">
                <h2 class="text-3xl font-bold text-white mb-4">
                    <?php esc_html_e('Stay in the Loop', 'saxon'); ?>
                </h2>
                <p class="text-xl text-blue-100 mb-8">
                    <?php esc_html_e('Subscribe to our newsletter and never miss our latest stories and updates.', 'saxon'); ?>
                </p>

                <form class="flex flex-col sm:flex-row gap-4">
                    <input type="email" 
                           placeholder="<?php esc_attr_e('Enter your email', 'saxon'); ?>" 
                           class="flex-1 px-4 py-3 rounded-lg text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit" 
                            class="px-6 py-3 bg-white text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition">
                        <?php esc_html_e('Subscribe', 'saxon'); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>