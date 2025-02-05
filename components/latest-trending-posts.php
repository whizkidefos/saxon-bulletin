<?php
// Latest Posts Section
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

if ($latest_posts->have_posts()): ?>
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
                while ($latest_posts->have_posts()): 
                    $latest_posts->the_post();
                    get_template_part('components/post/card');
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Trending Posts Section -->
<section class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                <?php esc_html_e('Trending Now', 'saxon'); ?>
            </h2>
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-300">
                <?php esc_html_e('Most popular articles this week', 'saxon'); ?>
            </p>
        </div>

        <?php
        $trending_posts = new WP_Query([
            'posts_per_page' => 4,
            'meta_key'       => 'post_views_count',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC'
        ]);

        if ($trending_posts->have_posts()): ?>
            <div class="grid md:grid-cols-2 gap-8">
                <?php
                while ($trending_posts->have_posts()): 
                    $trending_posts->the_post(); ?>
                    <article class="relative bg-white dark:bg-gray-700 rounded-lg shadow-sm overflow-hidden">
                        <div class="md:flex h-full">
                            <?php if (has_post_thumbnail()): ?>
                                <div class="md:flex-shrink-0 md:w-48">
                                    <?php the_post_thumbnail('medium', [
                                        'class' => 'h-48 w-full md:h-full object-cover',
                                    ]); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6">
                                <?php 
                                $categories = get_the_category();
                                if ($categories): ?>
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        <?php foreach ($categories as $category): ?>
                                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                               class="text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 px-2.5 py-1 rounded-full">
                                                <?php echo esc_html($category->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 dark:hover:text-blue-400">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>

                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <time datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                    <span class="mx-2">&middot;</span>
                                    <span><?php echo get_reading_time(); ?> min read</span>
                                    <span class="mx-2">&middot;</span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <?php echo get_post_views(get_the_ID()); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>