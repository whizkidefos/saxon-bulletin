<?php
/**
 * Single Post Template
 */

get_header(); ?>

<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <main class="flex-1">
                <article <?php post_class('bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden'); ?>>
                    <?php if (has_post_thumbnail()): ?>
                        <div class="relative h-[60vh] overflow-hidden bg-gray-900">
                            <?php the_post_thumbnail('full', [
                                'class' => 'w-full h-full object-cover opacity-75',
                            ]); ?>
                            <!-- Strong gradient overlay for better contrast -->
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent"></div>
                            
                            <!-- Post meta container -->
                            <div class="absolute bottom-0 left-0 right-0">
                                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
                                    <?php
                                    $categories = get_the_category();
                                    if ($categories): ?>
                                        <div class="flex flex-wrap gap-2 mb-6">
                                            <?php foreach ($categories as $category): ?>
                                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                                   class="text-sm font-medium px-4 py-1.5 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                                                    <?php echo esc_html($category->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                                        <?php the_title(); ?>
                                    </h1>

                                    <div class="flex items-center space-x-6">
                                        <div class="flex items-center space-x-4">
                                            <!-- </?php if ($avatar = get_avatar(get_the_author_meta('ID'), 48)): ?>
                                                <div class="flex-shrink-0">
                                                    </?php echo str_replace('avatar', 'rounded-full ring-2 ring-white/20', $avatar); ?>
                                                </div>
                                            </?php endif; ?> -->
                                            
                                            <div>
                                                <!-- <div class="font-medium text-white">
                                                    </?php the_author_posts_link(); ?>
                                                </div> -->
                                                <div class="text-sm text-gray-300">
                                                    <!-- </?php echo get_the_date(); ?> • --> <?php echo saxon_reading_time(); ?> min read
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- No featured image layout -->
                        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                            <?php
                            $categories = get_the_category();
                            if ($categories): ?>
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <?php foreach ($categories as $category): ?>
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                           class="text-sm font-medium px-4 py-1.5 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                                            <?php echo esc_html($category->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                                <?php the_title(); ?>
                            </h1>

                            <div class="flex items-center space-x-6 text-gray-600 dark:text-gray-400">
                                <div class="flex items-center space-x-4">
                                    <?php if ($avatar = get_avatar(get_the_author_meta('ID'), 48)): ?>
                                        <div class="flex-shrink-0">
                                            <?php echo str_replace('avatar', 'rounded-full', $avatar); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <div class="font-medium">
                                            <?php the_author_posts_link(); ?>
                                        </div>
                                        <div class="text-sm">
                                            <?php echo get_the_date(); ?> • <?php echo saxon_reading_time(); ?> min read
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                        <div class="prose dark:prose-invert max-w-none">
                            <?php the_content(); ?>
                        </div>

                        <?php
                        // Post tags
                        $tags = get_the_tags();
                        if ($tags): ?>
                            <div class="mt-12 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tags</h3>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($tags as $tag): ?>
                                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                                           class="inline-flex items-center px-3 py-1 rounded-md text-sm bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                            #<?php echo esc_html($tag->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            </main>

            <!-- Sidebar -->
            <aside class="w-full lg:w-80 space-y-8">
                <!-- Newsletter CTA -->
                <?php get_template_part('components/newsletter/mini-cta', null, [
                    'style' => 'sidebar',
                    'heading' => __('Stay Updated', 'saxon'),
                    'text' => __('Get our latest stories in your inbox', 'saxon'),
                    'button_text' => __('Subscribe Now', 'saxon')
                ]); ?>

                <!-- Affiliate Links -->
                <?php get_template_part('components/sidebar/affiliate-links', null, [
                    'title' => __('Recommended Tools', 'saxon'),
                    'count' => 3,
                    'category' => 'featured' // Optional: Filter by category
                ]); ?>

                <!-- Popular Posts -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Popular Posts</h3>
                    <?php
                    $popular_posts = new WP_Query([
                        'posts_per_page' => 4,
                        'orderby' => 'comment_count',
                        'order' => 'DESC'
                    ]);

                    if ($popular_posts->have_posts()): ?>
                        <div class="space-y-6">
                            <?php while ($popular_posts->have_posts()): $popular_posts->the_post(); ?>
                                <div class="flex items-start space-x-4">
                                    <?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>" class="flex-shrink-0">
                                            <?php the_post_thumbnail('thumbnail', [
                                                'class' => 'w-16 h-16 rounded-lg object-cover',
                                            ]); ?>
                                        </a>
                                    <?php endif; ?>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white leading-snug mb-1">
                                            <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <!-- <span class="text-sm text-gray-500 dark:text-gray-400">
                                            </?php echo get_the_date(); ?>
                                        </span> -->
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; wp_reset_postdata(); ?>
                </div>

                <!-- Categories -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Categories</h3>
                    <?php
                    $categories = get_categories([
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 6
                    ]);

                    if ($categories): ?>
                        <div class="space-y-3">
                            <?php foreach ($categories as $category): ?>
                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                   class="flex items-center justify-between py-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                    <span><?php echo esc_html($category->name); ?></span>
                                    <span class="text-sm text-gray-500"><?php echo $category->count; ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php get_footer(); ?>