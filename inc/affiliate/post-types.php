<?php
/**
 * Register Affiliate Links Custom Post Type and Taxonomies
 */

function saxon_register_affiliate_post_type() {
    $labels = [
        'name'                  => _x('Affiliate Links', 'Post type general name', 'saxon'),
        'singular_name'         => _x('Affiliate Link', 'Post type singular name', 'saxon'),
        'menu_name'            => _x('Affiliate Links', 'Admin Menu text', 'saxon'),
        'add_new'              => __('Add New', 'saxon'),
        'add_new_item'         => __('Add New Affiliate Link', 'saxon'),
        'edit_item'            => __('Edit Affiliate Link', 'saxon'),
        'new_item'             => __('New Affiliate Link', 'saxon'),
        'view_item'            => __('View Affiliate Link', 'saxon'),
        'search_items'         => __('Search Affiliate Links', 'saxon'),
        'not_found'            => __('No affiliate links found', 'saxon'),
        'not_found_in_trash'   => __('No affiliate links found in Trash', 'saxon'),
    ];

    $args = [
        'labels'              => $labels,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-money-alt',
        'supports'            => ['title', 'thumbnail', 'custom-fields'],
        'has_archive'         => false,
        'show_in_nav_menus'   => false,
        'menu_position'       => 25,
        'capability_type'     => 'post',
    ];

    register_post_type('affiliate_link', $args);

    // Register Category Taxonomy
    register_taxonomy('affiliate_category', 'affiliate_link', [
        'labels' => [
            'name'              => _x('Categories', 'taxonomy general name', 'saxon'),
            'singular_name'     => _x('Category', 'taxonomy singular name', 'saxon'),
            'menu_name'         => __('Categories', 'saxon'),
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
    ]);
}
add_action('init', 'saxon_register_affiliate_post_type');

// Add custom meta boxes
function saxon_add_affiliate_meta_boxes() {
    add_meta_box(
        'affiliate_link_details',
        __('Affiliate Link Details', 'saxon'),
        'saxon_affiliate_link_meta_box',
        'affiliate_link',
        'normal',
        'high'
    );

    add_meta_box(
        'affiliate_link_stats',
        __('Link Statistics', 'saxon'),
        'saxon_affiliate_stats_meta_box',
        'affiliate_link',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'saxon_add_affiliate_meta_boxes');

// Meta box callback for link details
function saxon_affiliate_link_meta_box($post) {
    wp_nonce_field('saxon_affiliate_link_nonce', 'affiliate_link_nonce');

    $url = get_post_meta($post->ID, '_affiliate_url', true);
    $price = get_post_meta($post->ID, '_affiliate_price', true);
    $discount = get_post_meta($post->ID, '_affiliate_discount', true);
    $description = get_post_meta($post->ID, '_affiliate_description', true);
    ?>
    <div class="affiliate-meta-box">
        <p>
            <label for="affiliate_url"><?php _e('Affiliate URL:', 'saxon'); ?></label>
            <input type="url" id="affiliate_url" name="affiliate_url" value="<?php echo esc_url($url); ?>" class="widefat">
        </p>
        <p>
            <label for="affiliate_price"><?php _e('Price:', 'saxon'); ?></label>
            <input type="text" id="affiliate_price" name="affiliate_price" value="<?php echo esc_attr($price); ?>" class="regular-text">
        </p>
        <p>
            <label for="affiliate_discount"><?php _e('Discount:', 'saxon'); ?></label>
            <input type="text" id="affiliate_discount" name="affiliate_discount" value="<?php echo esc_attr($discount); ?>" class="regular-text">
        </p>
        <p>
            <label for="affiliate_description"><?php _e('Description:', 'saxon'); ?></label>
            <textarea id="affiliate_description" name="affiliate_description" class="widefat" rows="3"><?php echo esc_textarea($description); ?></textarea>
        </p>
    </div>
    <?php
}

// Meta box callback for stats
function saxon_affiliate_stats_meta_box($post) {
    $clicks = get_post_meta($post->ID, '_affiliate_clicks', true) ?: 0;
    ?>
    <div class="affiliate-stats">
        <p>
            <strong><?php _e('Total Clicks:', 'saxon'); ?></strong>
            <span class="click-count"><?php echo intval($clicks); ?></span>
        </p>
    </div>
    <?php
}

// Save meta box data
function saxon_save_affiliate_meta_box_data($post_id) {
    if (!isset($_POST['affiliate_link_nonce']) || 
        !wp_verify_nonce($_POST['affiliate_link_nonce'], 'saxon_affiliate_link_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = [
        'affiliate_url' => 'url',
        'affiliate_price' => 'text',
        'affiliate_discount' => 'text',
        'affiliate_description' => 'textarea'
    ];

    foreach ($fields as $field => $type) {
        if (isset($_POST[$field])) {
            $value = $type === 'url' ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
        }
    }
}
add_action('save_post_affiliate_link', 'saxon_save_affiliate_meta_box_data');
