<?php
/**
 * Popular Posts Component
 */

$popular_posts = new WP_Query([
    'posts_per_page' => 5,
    'meta_key'       => 'post_views_count',
    'orderby'        => 'meta_value_num',
    'order'          => 'DESC',
]);

if ($popular_posts->have_posts()): ?>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            <?php esc_html_e('Popular Posts', 'saxon'); ?>
        </h2>

        <div class="space-y-4">
            <?php
            while ($popular_posts->have_posts()): 
                $popular_posts->the_post(); ?>
                <article class="flex space-x-4">
                    <?php if (has_post_thumbnail()): ?>
                        <a href="<?php the_permalink(); ?>" class="flex-shrink-0">
                            <?php the_post_thumbnail('thumbnail', [
                                'class' => 'w-16 h-16 rounded-md object-cover',
                            ]); ?>
                        </a>
                    <?php endif; ?>

                    <div class="min-w-0 flex-1">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 dark:hover:text-blue-400">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <?php echo get_the_date(); ?>
                        </p>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>
<?php
endif;
wp_reset_postdata(); ?>