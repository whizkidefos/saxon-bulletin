<?php
/**
 * The sidebar containing the main widget area
 */
?>

<div class="space-y-8">
    <?php get_template_part('components/sidebar/about'); ?>

    <?php if (is_active_sidebar('sidebar-1')): ?>
        <div class="space-y-8">
            <?php dynamic_sidebar('sidebar-1'); ?>
        </div>
    <?php endif; ?>

    <?php get_template_part('components/sidebar/popular-posts'); ?>
    
    <?php get_template_part('components/sidebar/categories'); ?>
</div>