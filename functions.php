<?php
/**
 * Saxon Bulletin Theme Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('SAXON_VERSION', '1.0.0');
define('SAXON_DIR', get_template_directory());
define('SAXON_URI', get_template_directory_uri());

// Include required files
require_once SAXON_DIR . '/inc/featured-posts.php';
require_once SAXON_DIR . '/inc/post-views.php';

// Load newsletter system
require_once SAXON_DIR . '/inc/newsletter/loader.php';

// Load login customizer
require_once get_template_directory() . '/inc/login-customizer.php';

// Initialize post submission functionality
require_once get_template_directory() . '/inc/post-submission.php';
add_action('init', 'saxon_init_post_submission');

function saxon_init_post_submission() {
    Saxon_Post_Submission::get_instance();
}

/**
 * Include Affiliate Management System
 */
require get_template_directory() . '/inc/affiliate/post-types.php';
require get_template_directory() . '/inc/affiliate/tracking.php';

/**
 * Theme Setup
 */
function saxon_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'saxon'),
        'footer'  => __('Footer Menu', 'saxon'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
}
add_action('after_setup_theme', 'saxon_theme_setup');

/**
 * Enqueue scripts and styles
 */
function saxon_scripts() {
    // Styles
    wp_enqueue_style(
        'saxon-main',
        SAXON_URI . '/assets/css/saxon.css',
        [],
        SAXON_VERSION
    );

    wp_enqueue_style(
        'saxon-custom',
        SAXON_URI . '/assets/css/theme.css',
        [],
        SAXON_VERSION
    );

    // Post animations
    wp_enqueue_style(
        'saxon-post-animations',
        get_template_directory_uri() . '/assets/css/post-animations.css',
        array(),
        SAXON_VERSION
    );

    // Scripts
    wp_enqueue_script(
        'saxon-theme-toggle',
        SAXON_URI . '/assets/js/theme-toggle.js',
        [],
        SAXON_VERSION,
        true
    );

    wp_enqueue_script(
        'saxon-mobile-menu',
        SAXON_URI . '/assets/js/mobile-menu.js',
        [],
        SAXON_VERSION,
        true
    );

    wp_enqueue_script(
        'saxon-search',
        SAXON_URI . '/assets/js/scripts.js',
        [],
        SAXON_VERSION,
        true
    );

    // Swiper
    if (is_front_page()) {
        wp_enqueue_style(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css',
            array(),
            '8.0.0'
        );

        // Custom Carousel CSS
        wp_enqueue_style(
            'saxon-carousel',
            get_template_directory_uri() . '/assets/css/carousel.css',
            array('swiper'),
            SAXON_VERSION
        );

        wp_enqueue_script(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js',
            array(),
            '8.0.0',
            true
        );

        wp_enqueue_script(
            'saxon-carousel',
            get_template_directory_uri() . '/assets/js/carousel.js',
            array('swiper'),
            SAXON_VERSION,
            true
        );
    }

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'saxon_scripts');

/**
 * Enqueue archive scripts and styles
 */
function saxon_enqueue_archive_assets() {
    if (is_archive() || is_home() || is_search()) {
        wp_enqueue_style(
            'saxon-archive',
            get_template_directory_uri() . '/assets/css/archive.css',
            array(),
            SAXON_VERSION
        );

        wp_enqueue_script(
            'saxon-archive',
            get_template_directory_uri() . '/assets/js/archive.js',
            array('jquery'),
            SAXON_VERSION,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'saxon_enqueue_archive_assets');

/**
 * Register widget areas
 */
function saxon_widgets_init() {
    register_sidebar([
        'name'          => __('Main Sidebar', 'saxon'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'saxon'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);

    register_sidebar([
        'name'          => __('Footer Widget Area', 'saxon'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'saxon'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
}
add_action('widgets_init', 'saxon_widgets_init');

/**
 * Get featured posts
 */
function saxon_get_featured_posts($count = 6) {
    return new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => $count,
        'meta_key' => '_saxon_featured',
        'meta_value' => '1',
        'ignore_sticky_posts' => true
    ]);
}

/**
 * Calculate reading time
 */
function saxon_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Assuming 200 words per minute reading speed
    
    return sprintf(
        _n('%d min read', '%d min read', $reading_time, 'saxon'),
        $reading_time
    );
}

/**
 * Custom template tags
 */
function saxon_posted_on() {
    $time_string = '<time class="text-gray-600 dark:text-gray-400" datetime="%1$s">%2$s</time>';
    
    $time_string = sprintf($time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date())
    );

    printf(
        '<span class="posted-on">%s</span>',
        $time_string
    );
}

function saxon_posted_by() {
    printf(
        '<span class="text-gray-600 dark:text-gray-400">by <a href="%s" class="font-medium hover:text-primary">%s</a></span>',
        esc_url(get_author_posts_url(get_the_author_meta('ID'))),
        esc_html(get_the_author())
    );
}

// Newsletter functions
function saxon_add_admin_menu() {
    add_menu_page(
        'Newsletter', 
        'Newsletter', 
        'manage_options',
        'saxon-newsletter',
        'saxon_newsletter_dashboard',
        'dashicons-email',
        30
    );

    add_submenu_page(
        'saxon-newsletter',
        'Subscribers',
        'Subscribers',
        'manage_options',
        'saxon-newsletter-subscribers',
        'saxon_subscribers_page'
    );

    add_submenu_page(
        'saxon-newsletter',
        'Templates',
        'Templates',
        'manage_options',
        'saxon-newsletter-templates',
        'saxon_templates_page'
    );

    add_submenu_page(
        'saxon-newsletter',
        'Analytics',
        'Analytics',
        'manage_options',
        'saxon-newsletter-analytics',
        'saxon_analytics_page'
    );
}
add_action('admin_menu', 'saxon_add_admin_menu');

// Cron jobs for newsletter
function saxon_schedule_newsletter_cron() {
    if (!wp_next_scheduled('saxon_send_newsletter')) {
        wp_schedule_event(time(), 'weekly', 'saxon_send_newsletter');
    }
}
add_action('wp_loaded', 'saxon_schedule_newsletter_cron');

function saxon_send_newsletter() {
    do_action('saxon_process_newsletter');
}
add_action('saxon_send_newsletter', 'saxon_send_newsletter');

// Newsletter settings
function saxon_newsletter_settings() {
    return [
        'from_name' => get_option('saxon_newsletter_from_name', get_bloginfo('name')),
        'from_email' => get_option('saxon_newsletter_from_email', get_bloginfo('admin_email')),
        'reply_to' => get_option('saxon_newsletter_reply_to'),
        'frequency_options' => ['daily', 'weekly', 'monthly'],
        'max_sends_per_hour' => get_option('saxon_newsletter_max_sends_per_hour', 100),
    ];
}

function saxon_register_settings() {
    register_setting('saxon_newsletter', 'saxon_newsletter_from_name');
    register_setting('saxon_newsletter', 'saxon_newsletter_from_email');
    register_setting('saxon_newsletter', 'saxon_newsletter_reply_to');
    register_setting('saxon_newsletter', 'saxon_newsletter_max_sends_per_hour');
}
add_action('admin_init', 'saxon_register_settings');

// Queue System for Large Lists
function saxon_queue_newsletter($template_id, $subscriber_ids) {
    global $wpdb;
    
    foreach ($subscriber_ids as $subscriber_id) {
        $wpdb->insert(
            $wpdb->prefix . 'newsletter_queue',
            [
                'subscriber_id' => $subscriber_id,
                'template_id' => $template_id,
                'scheduled_time' => current_time('mysql'),
                'status' => 'pending'
            ]
        );
    }
}

/**
 * Register Affiliate Links Custom Post Type
 */
function saxon_register_affiliate_links() {
    $labels = array(
        'name'               => __('Affiliate Links', 'saxon'),
        'singular_name'      => __('Affiliate Link', 'saxon'),
        'add_new'           => __('Add New', 'saxon'),
        'add_new_item'      => __('Add New Affiliate Link', 'saxon'),
        'edit_item'         => __('Edit Affiliate Link', 'saxon'),
        'new_item'          => __('New Affiliate Link', 'saxon'),
        'view_item'         => __('View Affiliate Link', 'saxon'),
        'search_items'      => __('Search Affiliate Links', 'saxon'),
        'not_found'         => __('No affiliate links found', 'saxon'),
        'not_found_in_trash'=> __('No affiliate links found in trash', 'saxon'),
        'parent_item_colon' => '',
        'menu_name'         => __('Affiliate Links', 'saxon')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'affiliate'),
        'capability_type'   => 'post',
        'has_archive'       => false,
        'hierarchical'      => false,
        'menu_position'     => 5,
        'supports'          => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'),
        'menu_icon'         => 'dashicons-money-alt'
    );

    register_post_type('affiliate_link', $args);

    // Register Affiliate Category Taxonomy
    $tax_labels = array(
        'name'              => __('Categories', 'saxon'),
        'singular_name'     => __('Category', 'saxon'),
        'search_items'      => __('Search Categories', 'saxon'),
        'all_items'         => __('All Categories', 'saxon'),
        'parent_item'       => __('Parent Category', 'saxon'),
        'parent_item_colon' => __('Parent Category:', 'saxon'),
        'edit_item'        => __('Edit Category', 'saxon'),
        'update_item'      => __('Update Category', 'saxon'),
        'add_new_item'     => __('Add New Category', 'saxon'),
        'new_item_name'    => __('New Category Name', 'saxon'),
        'menu_name'        => __('Categories', 'saxon')
    );

    register_taxonomy('affiliate_category', 'affiliate_link', array(
        'hierarchical'      => true,
        'labels'           => $tax_labels,
        'show_ui'          => true,
        'show_admin_column'=> true,
        'query_var'        => true,
        'rewrite'          => array('slug' => 'affiliate-category')
    ));
}
add_action('init', 'saxon_register_affiliate_links');

/**
 * Add custom favicon
 */
function saxon_favicon() {
    $favicon_url = get_template_directory_uri() . '/assets/images/favicon.svg';
    
    echo '<link rel="icon" type="image/svg+xml" href="' . esc_url($favicon_url) . '">';
    
    // Fallback for browsers that don't support SVG favicons
    echo '<link rel="alternate icon" type="image/png" href="' . esc_url(get_template_directory_uri() . '/assets/images/favicon.png') . '">';
    
    // For iOS/macOS
    echo '<link rel="apple-touch-icon" href="' . esc_url(get_template_directory_uri() . '/assets/images/apple-touch-icon.png') . '">';
}
add_action('wp_head', 'saxon_favicon');