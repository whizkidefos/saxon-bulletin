<?php
/**
 * Newsletter Functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Newsletter {
    private static $instance = null;
    private $table_name;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'newsletter_subscribers';

        // Create database table on activation
        register_activation_hook(__FILE__, [$this, 'create_table']);

        // Register AJAX handlers
        add_action('wp_ajax_saxon_newsletter_subscribe', [$this, 'handle_subscription']);
        add_action('wp_ajax_nopriv_saxon_newsletter_subscribe', [$this, 'handle_subscription']);

        // Add subscription form shortcode
        add_shortcode('saxon_newsletter', [$this, 'newsletter_shortcode']);

        // Schedule cleanup of unverified subscriptions
        if (!wp_next_scheduled('saxon_cleanup_unverified_subscribers')) {
            wp_schedule_event(time(), 'daily', 'saxon_cleanup_unverified_subscribers');
        }
        add_action('saxon_cleanup_unverified_subscribers', [$this, 'cleanup_unverified']);

        // Add admin menu
        add_action('admin_menu', [$this, 'add_admin_menu']);

        // Queue system implementation
        add_action('saxon_process_newsletter_batch', [$this, 'process_newsletter_batch']);
    }

    /**
     * Create subscribers table
     */
    public function create_table() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            email varchar(100) NOT NULL,
            first_name varchar(50),
            subscription_date datetime DEFAULT CURRENT_TIMESTAMP,
            verified tinyint(1) DEFAULT 0,
            verification_token varchar(64),
            verification_expiry datetime,
            unsubscribe_token varchar(64),
            categories varchar(255),
            frequency varchar(20) DEFAULT 'weekly',
            ip_address varchar(45),
            last_email_sent datetime,
            UNIQUE KEY id (id),
            UNIQUE KEY email (email)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Handle subscription request
     */
    public function handle_subscription() {
        check_ajax_referer('saxon_newsletter', 'nonce');

        $email = sanitize_email($_POST['email']);
        if (!is_email($email)) {
            wp_send_json_error('Invalid email address');
        }

        // Rate limiting
        $this->check_rate_limit();

        // Check if email is already subscribed
        global $wpdb;
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE email = %s",
            $email
        ));

        if ($existing) {
            if ($existing->verified) {
                wp_send_json_error('Email already subscribed');
            } else {
                // Resend verification email
                $this->send_verification_email($email, $existing->verification_token);
                wp_send_json_success('Verification email resent');
            }
            return;
        }

        // Create new subscription
        $verification_token = $this->generate_token();
        $unsubscribe_token = $this->generate_token();

        $result = $wpdb->insert(
            $this->table_name,
            [
                'email' => $email,
                'first_name' => sanitize_text_field($_POST['first_name'] ?? ''),
                'verification_token' => $verification_token,
                'verification_expiry' => date('Y-m-d H:i:s', strtotime('+24 hours')),
                'unsubscribe_token' => $unsubscribe_token,
                'categories' => sanitize_text_field($_POST['categories'] ?? ''),
                'frequency' => sanitize_text_field($_POST['frequency'] ?? 'weekly'),
                'ip_address' => $this->get_client_ip()
            ],
            ['%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );

        if ($result === false) {
            wp_send_json_error('Database error');
        }

        // Send verification email
        $this->send_verification_email($email, $verification_token);

        wp_send_json_success('Please check your email to confirm subscription');
    }

    /**
     * Check rate limit for subscriptions
     */
    private function check_rate_limit() {
        $ip = $this->get_client_ip();
        $transient_key = 'newsletter_rate_' . md5($ip);
        $attempts = get_transient($transient_key);

        if ($attempts === false) {
            set_transient($transient_key, 1, HOUR_IN_SECONDS);
        } else {
            if ($attempts >= 5) {
                wp_send_json_error('Too many attempts. Please try again later.');
            }
            set_transient($transient_key, $attempts + 1, HOUR_IN_SECONDS);
        }
    }

    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    /**
     * Generate secure random token
     */
    private function generate_token() {
        return bin2hex(random_bytes(32));
    }

    /**
     * Send verification email
     */
    private function send_verification_email($email, $token) {
        $verify_url = add_query_arg([
            'action' => 'verify_subscription',
            'email' => urlencode($email),
            'token' => $token
        ], home_url());

        $message = sprintf(
            __("Please confirm your subscription by clicking this link:\n\n%s\n\nThis link will expire in 24 hours.", 'saxon'),
            esc_url($verify_url)
        );

        wp_mail(
            $email,
            __('Confirm your newsletter subscription', 'saxon'),
            $message,
            ['Content-Type: text/plain; charset=UTF-8']
        );
    }

    /**
     * Cleanup unverified subscriptions
     */
    public function cleanup_unverified() {
        global $wpdb;
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$this->table_name} WHERE verified = 0 AND verification_expiry < %s",
            current_time('mysql')
        ));
    }

    /**
     * Newsletter form shortcode
     */
    public function newsletter_shortcode($atts) {
        $atts = shortcode_atts([
            'categories' => true,
            'frequency' => true
        ], $atts);

        ob_start();
        include get_template_directory() . '/template-parts/forms/newsletter.php';
        return ob_get_clean();
    }

    /**
     * Queue newsletter for sending
     */
    public function queue_newsletter($template_id, $subscriber_ids = [], $batch_size = 50) {
        if (empty($subscriber_ids)) {
            global $wpdb;
            $subscriber_ids = $wpdb->get_col("SELECT id FROM {$this->table_name} WHERE verified = 1");
        }

        $batches = array_chunk($subscriber_ids, $batch_size);
        $queue_key = 'newsletter_queue_' . uniqid();

        foreach ($batches as $index => $batch) {
            wp_schedule_single_event(
                time() + ($index * 300), // 5 minutes between batches
                'saxon_process_newsletter_batch',
                [$template_id, $batch, $queue_key]
            );
        }

        update_option($queue_key . '_total', count($batches));
        update_option($queue_key . '_completed', 0);
        update_option($queue_key . '_failed', []);

        return $queue_key;
    }

    /**
     * Process newsletter batch
     */
    public function process_newsletter_batch($template_id, $subscriber_batch, $queue_key) {
        $template = get_post($template_id);
        if (!$template || $template->post_type !== 'newsletter_template') {
            $this->log_error($queue_key, 'Invalid template ID: ' . $template_id);
            return;
        }

        foreach ($subscriber_batch as $subscriber_id) {
            try {
                $this->send_newsletter_to_subscriber($template, $subscriber_id);
            } catch (Exception $e) {
                $failed = get_option($queue_key . '_failed', []);
                $failed[] = [
                    'subscriber_id' => $subscriber_id,
                    'error' => $e->getMessage()
                ];
                update_option($queue_key . '_failed', $failed);
            }
        }

        $completed = get_option($queue_key . '_completed', 0) + 1;
        update_option($queue_key . '_completed', $completed);

        if ($completed >= get_option($queue_key . '_total')) {
            do_action('saxon_newsletter_queue_completed', $queue_key);
        }
    }

    /**
     * Log error
     */
    private function log_error($queue_key, $message) {
        $errors = get_option($queue_key . '_errors', []);
        $errors[] = [
            'time' => current_time('mysql'),
            'message' => $message
        ];
        update_option($queue_key . '_errors', $errors);
    }
}

// Initialize the newsletter system
Saxon_Newsletter::get_instance();