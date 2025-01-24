<?php
function saxon_create_newsletter_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'newsletter_subscribers';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        status varchar(20) NOT NULL DEFAULT 'subscribed',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        UNIQUE KEY email (email)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'saxon_create_newsletter_table');

// Newsletter subscription handler
add_action('wp_ajax_subscribe_newsletter', 'saxon_handle_newsletter_subscription');
add_action('wp_ajax_nopriv_subscribe_newsletter', 'saxon_handle_newsletter_subscription');

function saxon_handle_newsletter_subscription() {
    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        wp_send_json_error('Invalid email address');
        return;
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'newsletter_subscribers';
    $email = sanitize_email($_POST['email']);
    
    try {
        $wpdb->insert(
            $table_name,
            array('email' => $email),
            array('%s')
        );
        wp_send_json_success('Successfully subscribed!');
    } catch (Exception $e) {
        wp_send_json_error('Subscription failed. Please try again.');
    }
}

// Newsletter JavaScript
function saxon_enqueue_newsletter_script() {
    wp_enqueue_script(
        'saxon-newsletter',
        get_template_directory_uri() . '/assets/js/newsletter.js',
        array('jquery'),
        '1.0.0',
        true
    );
    
    wp_localize_script('saxon-newsletter', 'saxonNewsletter', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('saxon-newsletter-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'saxon_enqueue_newsletter_script');