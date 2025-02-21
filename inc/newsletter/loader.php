<?php
/**
 * Newsletter System Loader
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define newsletter system constants
define('SAXON_NEWSLETTER_VERSION', '1.0.0');
define('SAXON_NEWSLETTER_PATH', get_template_directory() . '/inc/newsletter');
define('SAXON_NEWSLETTER_URL', get_template_directory_uri() . '/inc/newsletter');

// Load core files
require_once SAXON_NEWSLETTER_PATH . '/database.php';
require_once SAXON_NEWSLETTER_PATH . '/templates.php';
require_once SAXON_NEWSLETTER_PATH . '/settings.php';
require_once SAXON_NEWSLETTER_PATH . '/analytics.php';

class Saxon_Newsletter {
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->setup_actions();
    }
    
    private function setup_actions() {
        add_action('admin_post_nopriv_saxon_newsletter_subscribe', [$this, 'handle_subscription']);
        add_action('admin_post_saxon_newsletter_subscribe', [$this, 'handle_subscription']);
        add_action('init', [$this, 'register_post_type']);
    }
    
    public function register_post_type() {
        register_post_type('newsletter_sub', [
            'labels' => [
                'name' => __('Newsletter Subscribers', 'saxon'),
                'singular_name' => __('Subscriber', 'saxon')
            ],
            'public' => false,
            'show_ui' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => false,
            'supports' => ['title', 'custom-fields']
        ]);
    }
    
    public function handle_subscription() {
        if (!isset($_POST['saxon_newsletter_nonce']) || 
            !wp_verify_nonce($_POST['saxon_newsletter_nonce'], 'saxon_newsletter_subscription')) {
            wp_die(__('Invalid request', 'saxon'));
        }
        
        $email = sanitize_email($_POST['email']);
        if (!is_email($email)) {
            wp_safe_redirect(add_query_arg('newsletter', 'invalid_email', wp_get_referer()));
            exit;
        }
        
        // Check if already subscribed
        $existing = get_posts([
            'post_type' => 'newsletter_sub',
            'meta_key' => '_subscriber_email',
            'meta_value' => $email,
            'posts_per_page' => 1
        ]);
        
        if (!empty($existing)) {
            wp_safe_redirect(add_query_arg('newsletter', 'already_subscribed', wp_get_referer()));
            exit;
        }
        
        // Add new subscriber
        $subscriber_id = wp_insert_post([
            'post_title' => $email,
            'post_type' => 'newsletter_sub',
            'post_status' => 'publish'
        ]);
        
        if ($subscriber_id) {
            update_post_meta($subscriber_id, '_subscriber_email', $email);
            update_post_meta($subscriber_id, '_subscription_date', current_time('mysql'));
            
            // Send confirmation email
            $this->send_confirmation_email($email);
            
            wp_safe_redirect(add_query_arg('newsletter', 'success', wp_get_referer()));
            exit;
        }
        
        wp_safe_redirect(add_query_arg('newsletter', 'error', wp_get_referer()));
        exit;
    }
    
    private function send_confirmation_email($email) {
        $subject = sprintf(__('Welcome to %s Newsletter!', 'saxon'), get_bloginfo('name'));
        
        $message = sprintf(
            __('Thank you for subscribing to our newsletter! We\'re excited to have you join our community at %s.', 'saxon'),
            get_bloginfo('name')
        );
        
        $message .= "\n\n";
        $message .= __('You\'ll start receiving our updates soon.', 'saxon');
        
        wp_mail($email, $subject, $message);
    }
}

// Initialize the newsletter system
Saxon_Newsletter::get_instance();

// Initialize core classes
Saxon_Newsletter_DB::get_instance();
Saxon_Newsletter_Templates::get_instance();
Saxon_Newsletter_Settings::get_instance();
Saxon_Newsletter_Analytics::get_instance();
