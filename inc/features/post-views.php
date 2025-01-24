<?php

function saxon_set_post_views() {
    if (!is_single()) return;
    
    global $post;
    $post_id = $post->ID;
    
    $count = get_post_meta($post_id, 'post_views_count', true);
    
    if ($count === '') {
        delete_post_meta($post_id, 'post_views_count');
        add_post_meta($post_id, 'post_views_count', 1);
    } else {
        $count++;
        update_post_meta($post_id, 'post_views_count', $count);
    }
}
add_action('wp', 'saxon_set_post_views');

// Add views column to posts list
function saxon_add_views_column($columns) {
    $columns['views'] = __('Views', 'saxon');
    return $columns;
}
add_filter('manage_posts_columns', 'saxon_add_views_column');

// Display views in posts list
function saxon_views_column_content($column, $post_id) {
    if ($column === 'views') {
        $views = get_post_meta($post_id, 'post_views_count', true);
        echo $views ? number_format($views) : '0';
    }
}
add_action('manage_posts_custom_column', 'saxon_views_column_content', 10, 2);

// Add these to functions.php
require_once get_template_directory() . '/inc/features/featured-posts.php';
require_once get_template_directory() . '/inc/features/newsletter.php';
require_once get_template_directory() . '/inc/features/post-views.php';