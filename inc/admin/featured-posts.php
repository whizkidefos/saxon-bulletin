<?php
/**
 * Admin Featured Posts Functionality
 */

// Add featured column to posts table
function saxon_add_featured_column($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns[$key] = $value;
            $new_columns['featured'] = __('Featured', 'saxon');
        } else {
            $new_columns[$key] = $value;
        }
    }
    return $new_columns;
}
add_filter('manage_posts_columns', 'saxon_add_featured_column');

// Add featured toggle to quick edit
function saxon_quick_edit_featured($column_name, $post_type) {
    if ($column_name !== 'featured') return;
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="inline-edit-featured">
                <input type="checkbox" name="saxon_featured" value="1">
                <span class="checkbox-title"><?php _e('Featured Post', 'saxon'); ?></span>
            </label>
        </div>
    </fieldset>
    <?php
}
add_action('quick_edit_custom_box', 'saxon_quick_edit_featured', 10, 2);

// Display featured status in posts table
function saxon_featured_column_content($column_name, $post_id) {
    if ($column_name !== 'featured') return;
    
    $featured = get_post_meta($post_id, '_saxon_featured', true);
    $icon_class = $featured ? 'dashicons-star-filled' : 'dashicons-star-empty';
    $button_class = $featured ? 'featured' : '';
    ?>
    <button type="button" 
            class="featured-toggle <?php echo $button_class; ?>"
            data-post-id="<?php echo esc_attr($post_id); ?>"
            data-nonce="<?php echo wp_create_nonce('saxon_featured_toggle_' . $post_id); ?>">
        <span class="dashicons <?php echo $icon_class; ?>"></span>
        <span class="screen-reader-text">
            <?php echo $featured ? __('Remove from featured', 'saxon') : __('Mark as featured', 'saxon'); ?>
        </span>
    </button>
    <?php
}
add_action('manage_posts_custom_column', 'saxon_featured_column_content', 10, 2);

// Add JavaScript for featured toggle
function saxon_admin_featured_scripts() {
    $screen = get_current_screen();
    if ($screen->base !== 'edit') return;

    wp_enqueue_script(
        'saxon-admin-featured',
        get_template_directory_uri() . '/assets/js/admin-featured.js',
        array('jquery'),
        SAXON_VERSION,
        true
    );

    wp_add_inline_style('wp-admin', '
        .featured-toggle { 
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }
        .featured-toggle .dashicons-star-empty { 
            color: #ccc; 
        }
        .featured-toggle .dashicons-star-filled { 
            color: #f1c40f; 
        }
        .featured-toggle:hover .dashicons-star-empty { 
            color: #f1c40f; 
        }
    ');
}
add_action('admin_enqueue_scripts', 'saxon_admin_featured_scripts');

// AJAX handler for featured toggle
function saxon_ajax_toggle_featured() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if (!$post_id || !check_ajax_referer('saxon_featured_toggle_' . $post_id, 'nonce', false)) {
        wp_send_json_error('Invalid request');
    }

    if (!current_user_can('edit_post', $post_id)) {
        wp_send_json_error('Permission denied');
    }

    $featured = get_post_meta($post_id, '_saxon_featured', true);
    $new_status = $featured ? '' : '1';
    
    update_post_meta($post_id, '_saxon_featured', $new_status);
    
    wp_send_json_success([
        'featured' => (bool) $new_status,
        'message' => $new_status ? __('Post marked as featured', 'saxon') : __('Post removed from featured', 'saxon')
    ]);
}
add_action('wp_ajax_saxon_toggle_featured', 'saxon_ajax_toggle_featured');