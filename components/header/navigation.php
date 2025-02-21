<?php
/**
 * Navigation component
 */
?>

<nav class="relative flex items-center justify-between h-16">
    <div class="flex-shrink-0 flex items-center">
        <?php if (has_custom_logo()): ?>
            <?php the_custom_logo(); ?>
        <?php else: ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" 
               class="text-xl font-bold text-gray-900 dark:text-white">
                <?php bloginfo('name'); ?>
            </a>
        <?php endif; ?>
    </div>

    <!-- Desktop Navigation -->
    <div class="hidden md:flex md:items-center md:space-x-8">
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'flex space-x-8',
            'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
            'fallback_cb'    => false,
        ]);
        ?>
        
        <?php
        // Submit Post Button
        $submit_page = get_page_by_path('submit-post');
        if ($submit_page): ?>
            <a href="<?php echo get_permalink($submit_page); ?>" 
               class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-5 w-5 mr-1.5" 
                     fill="none" 
                     viewBox="0 0 24 24" 
                     stroke="currentColor">
                    <path stroke-linecap="round" 
                          stroke-linejoin="round" 
                          stroke-width="2" 
                          d="M12 4v16m8-8H4" />
                </svg>
                <?php _e('Submit Post', 'saxon'); ?>
            </a>
        <?php endif; ?>
        
        <!-- </?php get_template_part('components/header/theme-toggle'); ?> -->
    </div>

    <!-- Mobile menu button -->
    <div class="flex md:hidden">
        <button type="button" 
                class="mobile-menu-toggle inline-flex items-center justify-center p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                aria-controls="mobile-menu"
                aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <!-- Icon when menu is closed -->
            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Icon when menu is open -->
            <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</nav>

<!-- Mobile menu -->
<div class="hidden md:hidden" id="mobile-menu">
    <div class="px-2 pt-2 pb-3 space-y-1">
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'space-y-1',
            'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
            'fallback_cb'    => false,
        ]);
        ?>
        
        <?php if ($submit_page): ?>
            <div class="px-2 pt-2 pb-3">
                <a href="<?php echo get_permalink($submit_page); ?>" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <?php _e('Submit Post', 'saxon'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>