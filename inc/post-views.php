<?php
/**
 * Post Views Counter Functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Post_Views {
    private static $instance = null;
    private $meta_key = 'post_views_count';

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Add views column to posts table
        add_filter('manage_posts_columns', [$this, 'add_views_column']);
        add_action('manage_posts_custom_column', [$this, 'views_column_content'], 10, 2);
        add_filter('manage_edit-post_sortable_columns', [$this, 'views_column_sortable']);

        // Count post views
        add_action('wp_head', [$this, 'count_post_view']);

        // Add views to REST API
        add_action('rest_api_init', [$this, 'register_rest_field']);

        // Add views to admin dashboard widget
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widget']);
    }

    public function add_views_column($columns) {
        $columns['post_views'] = __('Views', 'saxon');
        return $columns;
    }

    public function views_column_content($column_name, $post_id) {
        if ($column_name === 'post_views') {
            echo $this->get_post_views($post_id);
        }
    }

    public function views_column_sortable($columns) {
        $columns['post_views'] = 'post_views';
        return $columns;
    }

    public function count_post_view() {
        if (is_single()) {
            $post_id = get_the_ID();
            $count = (int) get_post_meta($post_id, $this->meta_key, true);
            $count++;
            update_post_meta($post_id, $this->meta_key, $count);
        }
    }

    public function get_post_views($post_id) {
        $count = get_post_meta($post_id, $this->meta_key, true);
        return number_format_i18n($count ? $count : 0);
    }

    public function register_rest_field() {
        register_rest_field('post', 'post_views', [
            'get_callback' => function($post) {
                return $this->get_post_views($post['id']);
            },
            'schema' => [
                'description' => __('Post view count.', 'saxon'),
                'type'        => 'integer'
            ]
        ]);
    }

    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'saxon_popular_posts',
            __('Popular Posts', 'saxon'),
            [$this, 'dashboard_widget_content']
        );
    }

    public function dashboard_widget_content() {
        $posts = get_posts([
            'post_type'      => 'post',
            'posts_per_page' => 5,
            'meta_key'       => $this->meta_key,
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC'
        ]);

        if ($posts): ?>
            <ul class="saxon-popular-posts">
                <?php foreach ($posts as $post): ?>
                    <li>
                        <a href="<?php echo get_edit_post_link($post->ID); ?>">
                            <?php echo $post->post_title; ?>
                        </a>
                        <span class="post-views">
                            <?php echo $this->get_post_views($post->ID); ?> 
                            <?php _e('views', 'saxon'); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p><?php _e('No posts found.', 'saxon'); ?></p>
        <?php endif;
    }

    public static function get_popular_posts($limit = 5) {
        return get_posts([
            'post_type'      => 'post',
            'posts_per_page' => $limit,
            'meta_key'       => 'post_views_count',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC'
        ]);
    }
}

// Initialize the class
Saxon_Post_Views::get_instance();/**
 * Get post views
 */
function get_post_views($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $count = get_post_meta($post_id, 'post_views_count', true);
    
    if ($count === '') {
        delete_post_meta($post_id, 'post_views_count');
        add_post_meta($post_id, 'post_views_count', '0');
        return 0;
    }
    
    return number_format_i18n($count);
}

/**
 * Set post views
 */
function set_post_views() {
    if (!is_single()) return;
    
    $post_id = get_the_ID();
    $count = get_post_meta($post_id, 'post_views_count', true);
    
    if ($count === '') {
        delete_post_meta($post_id, 'post_views_count');
        add_post_meta($post_id, 'post_views_count', '1');
    } else {
        $count++;
        update_post_meta($post_id, 'post_views_count', $count);
    }
}
add_action('wp', 'set_post_views');