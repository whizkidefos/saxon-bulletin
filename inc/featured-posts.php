<?php
/**
 * Featured Posts Functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Featured_Posts {
    private static $instance = null;
    private $post_types = ['post'];
    private $meta_key = '_saxon_featured';

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Add the featured column to post listing
        add_filter('manage_posts_columns', [$this, 'add_featured_column']);
        add_action('manage_posts_custom_column', [$this, 'featured_column_content'], 10, 2);
        
        // Make the featured column sortable
        add_filter('manage_edit-post_sortable_columns', [$this, 'featured_column_sortable']);
        
        // Add quick edit checkbox
        add_action('quick_edit_custom_box', [$this, 'quick_edit_featured'], 10, 2);
        add_action('bulk_edit_custom_box', [$this, 'bulk_edit_featured'], 10, 2);
        
        // Save quick edit and bulk edit
        add_action('save_post', [$this, 'save_featured_status']);
        add_action('wp_ajax_save_bulk_featured', [$this, 'save_bulk_featured']);
        
        // Add featured meta box
        add_action('add_meta_boxes', [$this, 'add_featured_meta_box']);
        
        // Add admin scripts
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    public function add_featured_column($columns) {
        $new_columns = [];
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

    public function featured_column_content($column_name, $post_id) {
        if ($column_name === 'featured') {
            $featured = get_post_meta($post_id, $this->meta_key, true);
            $icon_class = $featured ? 'dashicons-star-filled text-yellow-400' : 'dashicons-star-empty';
            printf(
                '<span class="dashicons %s featured-toggle" data-post-id="%d" data-featured="%s"></span>',
                esc_attr($icon_class),
                esc_attr($post_id),
                esc_attr($featured)
            );
        }
    }

    public function featured_column_sortable($columns) {
        $columns['featured'] = 'featured';
        return $columns;
    }

    public function add_featured_meta_box() {
        foreach ($this->post_types as $post_type) {
            add_meta_box(
                'saxon_featured',
                __('Featured Post', 'saxon'),
                [$this, 'featured_meta_box_content'],
                $post_type,
                'side',
                'high'
            );
        }
    }

    public function featured_meta_box_content($post) {
        wp_nonce_field('saxon_featured_nonce', 'featured_nonce');
        $featured = get_post_meta($post->ID, $this->meta_key, true);
        ?>
        <label class="flex items-center space-x-2">
            <input type="checkbox" name="saxon_featured" value="1" <?php checked($featured, '1'); ?>>
            <span><?php _e('Mark as Featured Post', 'saxon'); ?></span>
        </label>
        <?php
    }

    public function quick_edit_featured($column_name, $post_type) {
        if ($column_name !== 'featured') return;
        ?>
        <fieldset class="inline-edit-col-right">
            <div class="inline-edit-col">
                <label class="inline-edit-featured">
                    <span class="title"><?php _e('Featured', 'saxon'); ?></span>
                    <input type="checkbox" name="saxon_featured" value="1">
                </label>
            </div>
        </fieldset>
        <?php
    }

    public function bulk_edit_featured($column_name, $post_type) {
        if ($column_name !== 'featured') return;
        ?>
        <fieldset class="inline-edit-col-right">
            <div class="inline-edit-col">
                <label class="inline-edit-featured">
                    <span class="title"><?php _e('Featured', 'saxon'); ?></span>
                    <select name="saxon_featured">
                        <option value="-1"><?php _e('— No Change —', 'saxon'); ?></option>
                        <option value="1"><?php _e('Featured', 'saxon'); ?></option>
                        <option value="0"><?php _e('Not Featured', 'saxon'); ?></option>
                    </select>
                </label>
            </div>
        </fieldset>
        <?php
    }

    public function save_featured_status($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!isset($_POST['featured_nonce']) || !wp_verify_nonce($_POST['featured_nonce'], 'saxon_featured_nonce')) return;
        if (!current_user_can('edit_post', $post_id)) return;

        $featured = isset($_POST['saxon_featured']) ? '1' : '0';
        update_post_meta($post_id, $this->meta_key, $featured);
    }

    public function enqueue_admin_scripts($hook) {
        if (!in_array($hook, ['edit.php', 'post.php', 'post-new.php'])) return;

        wp_enqueue_script(
            'saxon-admin',
            get_template_directory_uri() . '/assets/js/admin.js',
            ['jquery'],
            SAXON_VERSION,
            true
        );

        wp_localize_script('saxon-admin', 'saxonAdmin', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('saxon-featured-nonce'),
        ]);
    }
}

// Initialize the class
Saxon_Featured_Posts::get_instance();