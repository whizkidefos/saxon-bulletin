<?php
/**
 * Affiliate Link Tracking
 */

// Track affiliate link clicks
function saxon_track_affiliate_click() {
    check_ajax_referer('affiliate_click_nonce', 'nonce');

    $link_id = isset($_POST['link_id']) ? intval($_POST['link_id']) : 0;
    if (!$link_id) {
        wp_send_json_error('Invalid link ID');
    }

    // Increment click count
    $clicks = get_post_meta($link_id, '_affiliate_clicks', true) ?: 0;
    update_post_meta($link_id, '_affiliate_clicks', $clicks + 1);

    // Log click details
    $log_entry = [
        'timestamp' => current_time('mysql'),
        'ip' => saxon_get_client_ip(),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        'referer' => wp_get_referer(),
    ];
    
    $click_log = get_post_meta($link_id, '_affiliate_click_log', true) ?: [];
    array_unshift($click_log, $log_entry);
    
    // Keep only last 1000 entries
    $click_log = array_slice($click_log, 0, 1000);
    update_post_meta($link_id, '_affiliate_click_log', $click_log);

    // Get the redirect URL
    $redirect_url = get_post_meta($link_id, '_affiliate_url', true);
    
    wp_send_json_success([
        'url' => $redirect_url,
        'clicks' => $clicks + 1
    ]);
}
add_action('wp_ajax_saxon_track_affiliate_click', 'saxon_track_affiliate_click');
add_action('wp_ajax_nopriv_saxon_track_affiliate_click', 'saxon_track_affiliate_click');

// Get client IP address
function saxon_get_client_ip() {
    $ip_headers = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    foreach ($ip_headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ip = explode(',', $_SERVER[$header]);
            return trim($ip[0]);
        }
    }

    return '127.0.0.1';
}

// Add tracking script
function saxon_enqueue_affiliate_scripts() {
    wp_enqueue_script(
        'saxon-affiliate-tracking',
        get_template_directory_uri() . '/assets/js/affiliate-tracking.js',
        ['jquery'],
        '1.0.0',
        true
    );

    wp_localize_script('saxon-affiliate-tracking', 'saxonAffiliateData', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('affiliate_click_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'saxon_enqueue_affiliate_scripts');
