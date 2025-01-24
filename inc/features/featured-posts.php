<?php
// Add featured post meta box
function saxon_add_featured_post_meta_box() {
    add_meta_box(
        'saxon_featured_post',
        __('Featured Post', 'saxon'),
        'saxon_featured_post_meta_box_html',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'saxon_add_featured_post_meta_box');

// Meta box HTML
function saxon_featured_post_meta_box_html($post) {
    $value = get_post_meta($post->ID, 'featured_post', true);
    ?>
    <label for="saxon_featured_post">
        <input type="checkbox" id="saxon_featured_post" name="saxon_featured_post" value="yes" <?php checked($value, 'yes'); ?>>
        <?php _e('Mark as Featured Post', 'saxon'); ?>
    </label>
    <?php
}

// Save meta box data
function saxon_save_featured_post_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ($parent_id = wp_is_post_revision($post_id)) {
        $post_id = $parent_id;
    }
    
    if (isset($_POST['saxon_featured_post'])) {
        update_post_meta(
            $post_id,
            'featured_post',
            'yes'
        );
    } else {
        delete_post_meta(
            $post_id,
            'featured_post'
        );
    }
}
add_action('save_post', 'saxon_save_featured_post_meta_box');

// Add featured column to posts list
function saxon_add_featured_column($columns) {
    $columns['featured'] = __('Featured', 'saxon');
    return $columns;
}
add_filter('manage_posts_columns', 'saxon_add_featured_column');

// Display featured status in posts list
function saxon_featured_column_content($column, $post_id) {
    if ($column === 'featured') {
        $featured = get_post_meta($post_id, 'featured_post', true);
        if ($featured === 'yes') {
            echo '<span class="dashicons dashicons-star-filled" style="color: #FFB900;"></span>';
        }
    }
}
add_action('manage_posts_custom_column', 'saxon_featured_column_content', 10, 2);