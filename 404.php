<?php get_header(); ?>

<div class="min-h-[70vh] bg-gray-50 dark:bg-gray-900 py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center">
            <!-- 404 Illustration -->
            <div class="mb-8">
                <svg class="mx-auto h-32 w-32 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M34 6h-4v4h4V6zm2 0v4h4a2 2 0 00-2-2h-2zm-2 36h-4v4h4v-4zm2 4v-4h4a2 2 0 01-2 2h-2zM10 6h4v4h-4V6zM8 6v4H4a2 2 0 012-2h2zm2 36h4v4h-4v-4zM8 46v-4H4a2 2 0 002 2h2zm5-40h2v4h-2zm4 0h2v4h-2zm4 0h2v4h-2zm-8 36h2v4h-2zm4 0h2v4h-2zm4 0h2v4h-2zM6 14v2h4v-2zm0 4v2h4v-2zm0 4v2h4v-2zm36-8v2h4v-2zm0 4v2h4v-2zm0 4v2h4v-2zM6 26v2h4v-2zm0 4v2h4v-2zm0 4v2h4v-2zm36-8v2h4v-2zm0 4v2h4v-2zm0 4v2h4v-2z"/>
                </svg>
            </div>

            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl">
                <?php esc_html_e('Page Not Found', 'saxon'); ?>
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                <?php esc_html_e("Sorry, we couldn't find the page you're looking for.", 'saxon'); ?>
            </p>

            <!-- Search Form -->
            <div class="mt-8 max-w-xl mx-auto">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <label class="relative block">
                        <span class="sr-only"><?php esc_html_e('Search for:', 'saxon'); ?></span>
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="search" 
                               class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 pl-10 pr-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm"
                               placeholder="<?php esc_attr_e('Search our articles...', 'saxon'); ?>" 
                               value="<?php echo get_search_query(); ?>" 
                               name="s">
                    </label>
                </form>
            </div>

            <!-- Quick Links -->
            <div class="mt-12">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
                    <?php esc_html_e('Popular Categories', 'saxon'); ?>
                </h2>
                <div class="flex flex-wrap justify-center gap-3">
                    <?php
                    $categories = get_categories([
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 6
                    ]);

                    foreach ($categories as $category): ?>
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                           class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-100 dark:hover:bg-blue-800 transition-colors">
                            <?php echo esc_html($category->name); ?>
                            <span class="ml-1 text-blue-500 dark:text-blue-300">(<?php echo $category->count; ?>)</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recent Posts -->
            <div class="mt-12">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
                    <?php esc_html_e('Latest Articles', 'saxon'); ?>
                </h2>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 max-w-4xl mx-auto">
                    <?php
                    $recent_posts = new WP_Query([
                        'posts_per_page' => 3,
                        'post_status' => 'publish'
                    ]);

                    while ($recent_posts->have_posts()): $recent_posts->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" 
                           class="block bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <?php if (has_post_thumbnail()): ?>
                                <div class="aspect-w-16 aspect-h-9">
                                    <?php the_post_thumbnail('medium', [
                                        'class' => 'w-full h-full object-cover'
                                    ]); ?>
                                </div>
                            <?php endif; ?>
                            <div class="p-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <?php the_title(); ?>
                                </h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                </p>
                            </div>
                        </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>

            <!-- Back Home Button -->
            <div class="mt-12">
                <a href="<?php echo esc_url(home_url('/')); ?>" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <?php esc_html_e('Back to Homepage', 'saxon'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>