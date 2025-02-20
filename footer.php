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
                        'menu_class' => 'space-y-2',
                        'fallback_cb' => false,
                    ]);
                    ?>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Categories', 'saxon'); ?>
                    </h3>
                    <ul class="space-y-2">
                        <?php
                        wp_list_categories([
                            'title_li' => '',
                            'number' => 5,
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'show_count' => true,
                        ]);
                        ?>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e('Newsletter', 'saxon'); ?>
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">
                        <?php esc_html_e('Subscribe to our newsletter for updates and exclusive content.', 'saxon'); ?>
                    </p>
                    <form class="newsletter-form">
                        <div class="flex">
                            <input type="email" 
                                   placeholder="<?php esc_attr_e('Your email', 'saxon'); ?>"
                                   class="flex-1 rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <?php esc_html_e('Subscribe', 'saxon'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="py-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                        <?php esc_html_e('All rights reserved.', 'saxon'); ?>
                    </div>
                    <div class="flex space-x-6 text-sm text-gray-500 dark:text-gray-400">
                        <a href="<?php echo get_privacy_policy_url(); ?>" class="hover:text-gray-900 dark:hover:text-gray-300">
                            <?php esc_html_e('Privacy Policy', 'saxon'); ?>
                        </a>
                        <a href="#" class="hover:text-gray-900 dark:hover:text-gray-300">
                            <?php esc_html_e('Terms of Service', 'saxon'); ?>
                        </a>
                        <a href="#" class="hover:text-gray-900 dark:hover:text-gray-300">
                            <?php esc_html_e('Contact', 'saxon'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>