<?php
/**
 * The footer template
 */
?>
    </main><!-- #content from header.php -->
    
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Footer Widgets -->
            <div class="py-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- About Widget -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php bloginfo('name'); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        <?php echo get_theme_mod('footer_about_text', 'A comprehensive blogging platform built with WordPress.'); ?>
                    </p>
                    <!-- Social Links -->
                    <div class="flex space-x-4">
                        <?php
                        $social_links = [
                            'twitter' => get_theme_mod('social_twitter'),
                            'facebook' => get_theme_mod('social_facebook'),
                            'instagram' => get_theme_mod('social_instagram'),
                            'linkedin' => get_theme_mod('social_linkedin')
                        ];

                        foreach ($social_links as $platform => $url):
                            if ($url): ?>
                                <a href="<?php echo esc_url($url); ?>" 
                                   class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    <?php get_template_part('components/icons/' . $platform); ?>
                                    <span class="sr-only"><?php echo ucfirst($platform); ?></span>
                                </a>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Quick Links', 'saxon'); ?>
                    </h3>
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'container' => false,
                        'menu_class' => 'space-y-3',
                        'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                        'link_before' => '<span class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">',
                        'link_after' => '</span>'
                    ]);
                    ?>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Categories', 'saxon'); ?>
                    </h3>
                    <?php
                    $categories = get_categories([
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 6
                    ]);

                    if ($categories): ?>
                        <ul class="space-y-3">
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                       class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                                        <?php echo esc_html($category->name); ?>
                                        <span class="text-gray-400">(<?php echo $category->count; ?>)</span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Newsletter Mini CTA -->
                <div>
                    <?php get_template_part('components/newsletter/mini-cta', null, [
                        'style' => 'footer',
                        'heading' => __('Newsletter', 'saxon'),
                        'text' => __('Subscribe for weekly updates', 'saxon'),
                        'button_text' => __('Join', 'saxon'),
                        'bg_class' => 'bg-transparent'
                    ]); ?>
                </div>
            </div>

            <!-- Copyright -->
            <div class="py-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-center text-gray-600 dark:text-gray-400">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                    <?php esc_html_e('All rights reserved.', 'saxon'); ?>
                </p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>