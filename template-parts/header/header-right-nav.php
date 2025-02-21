<?php
/**
 * Header Right Navigation Template Part
 */
?>
<div class="header-right-nav">
    <nav class="header-menu">
        <?php
        // Theme toggle
        // get_template_part('components/header/theme-toggle');
        ?>
        
        <!-- Submit Post CTA -->
        <?php
        $submit_page = get_page_by_path('submit-post');
        if ($submit_page): ?>
            <a href="<?php echo get_permalink($submit_page); ?>" class="submit-post-btn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <?php _e('Submit Post', 'saxon'); ?>
            </a>
        <?php endif; ?>
        
        <?php if (has_nav_menu('header-right')): ?>
            <?php
            wp_nav_menu([
                'theme_location' => 'header-right',
                'menu_class' => 'header-menu-list',
                'container' => false,
                'depth' => 1,
            ]);
            ?>
        <?php endif; ?>
    </nav>
</div>

<style>
.header-right-nav {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.submit-post-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: var(--primary-color, #2563eb);
    color: white;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: background-color 200ms;
}

.submit-post-btn:hover {
    background-color: var(--primary-color-dark, #1d4ed8);
    color: white;
}

.submit-post-btn svg {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.5rem;
}

[data-theme="dark"] .submit-post-btn {
    background-color: var(--primary-color-dark, #1d4ed8);
}

[data-theme="dark"] .submit-post-btn:hover {
    background-color: var(--primary-color, #2563eb);
}
</style>
