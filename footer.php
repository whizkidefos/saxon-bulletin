<?php
/**
 * The footer template
 */
?>
    </main><!-- #content from header.php -->
    
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <!-- Footer Widgets -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <?php if (is_active_sidebar('footer-1')): ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (is_active_sidebar('footer-2')): ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (is_active_sidebar('footer-3')): ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Footer Navigation -->
            <?php if (has_nav_menu('footer')): ?>
                <nav class="mb-8" aria-label="<?php esc_attr_e('Footer Navigation', 'saxon'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'flex flex-wrap justify-center space-x-6',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ]);
                    ?>
                </nav>
            <?php endif; ?>

            <!-- Footer Bottom -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <!-- Copyright -->
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                        <?php echo get_theme_mod('saxon_footer_copyright', __('All rights reserved.', 'saxon')); ?>
                    </div>

                    <!-- Social Links -->
                    <?php get_template_part('components/shared/social-links'); ?>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>