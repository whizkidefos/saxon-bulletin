<?php
/**
 * Newsletter Database Setup
 */

if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Newsletter_DB {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Run database setup on theme activation
        add_action('after_switch_theme', [$this, 'setup_database']);
        
        // Check and update database version when needed
        add_action('after_setup_theme', [$this, 'check_database_version']);
    }

    /**
     * Set up database tables
     */
    public function setup_database() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();

        // Subscribers table
        $table_subscribers = $wpdb->prefix . 'newsletter_subscribers';
        $sql_subscribers = "CREATE TABLE IF NOT EXISTS $table_subscribers (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            email varchar(100) NOT NULL,
            first_name varchar(50),
            last_name varchar(50),
            status varchar(20) NOT NULL DEFAULT 'pending',
            subscription_date datetime DEFAULT CURRENT_TIMESTAMP,
            verified tinyint(1) DEFAULT 0,
            verification_token varchar(64),
            verification_expiry datetime,
            unsubscribe_token varchar(64),
            categories varchar(255),
            frequency varchar(20) DEFAULT 'weekly',
            ip_address varchar(45),
            last_email_sent datetime,
            preferences text,
            metadata text,
            subscriber_hash varchar(32),
            gdpr_consent tinyint(1) DEFAULT 0,
            gdpr_consent_date datetime,
            PRIMARY KEY  (id),
            UNIQUE KEY email (email),
            KEY status (status),
            KEY verified (verified),
            KEY subscriber_hash (subscriber_hash)
        ) $charset_collate;";

        // Queue table
        $table_queue = $wpdb->prefix . 'newsletter_queue';
        $sql_queue = "CREATE TABLE IF NOT EXISTS $table_queue (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            subscriber_id bigint(20) NOT NULL,
            template_id varchar(50) NOT NULL,
            subject varchar(255),
            content longtext,
            status varchar(20) NOT NULL DEFAULT 'pending',
            scheduled_time datetime,
            sent_time datetime,
            attempts int(11) DEFAULT 0,
            last_attempt datetime,
            error_message text,
            metadata text,
            PRIMARY KEY  (id),
            KEY subscriber_id (subscriber_id),
            KEY status (status),
            KEY scheduled_time (scheduled_time)
        ) $charset_collate;";

        // Campaign/Issues table
        $table_campaigns = $wpdb->prefix . 'newsletter_campaigns';
        $sql_campaigns = "CREATE TABLE IF NOT EXISTS $table_campaigns (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            template_id varchar(50),
            subject varchar(255),
            content longtext,
            status varchar(20) NOT NULL DEFAULT 'draft',
            created_date datetime DEFAULT CURRENT_TIMESTAMP,
            scheduled_date datetime,
            sent_date datetime,
            sent_count int(11) DEFAULT 0,
            open_count int(11) DEFAULT 0,
            click_count int(11) DEFAULT 0,
            metadata text,
            PRIMARY KEY  (id),
            KEY status (status)
        ) $charset_collate;";

        // Stats table
        $table_stats = $wpdb->prefix . 'newsletter_stats';
        $sql_stats = "CREATE TABLE IF NOT EXISTS $table_stats (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            subscriber_id bigint(20) NOT NULL,
            campaign_id bigint(20),
            type varchar(20) NOT NULL,
            created_date datetime DEFAULT CURRENT_TIMESTAMP,
            ip_address varchar(45),
            user_agent text,
            metadata text,
            PRIMARY KEY  (id),
            KEY subscriber_id (subscriber_id),
            KEY campaign_id (campaign_id),
            KEY type (type)
        ) $charset_collate;";

        // Links table for tracking
        $table_links = $wpdb->prefix . 'newsletter_links';
        $sql_links = "CREATE TABLE IF NOT EXISTS $table_links (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            campaign_id bigint(20) NOT NULL,
            url text NOT NULL,
            short_code varchar(32) NOT NULL,
            click_count int(11) DEFAULT 0,
            created_date datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY campaign_id (campaign_id),
            UNIQUE KEY short_code (short_code)
        ) $charset_collate;";

        // Execute table creation
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_subscribers);
        dbDelta($sql_queue);
        dbDelta($sql_campaigns);
        dbDelta($sql_stats);
        dbDelta($sql_links);

        // Store database version
        update_option('saxon_newsletter_db_version', '1.0');
    }

    /**
     * Check and update database version if needed
     */
    public function check_database_version() {
        $current_version = get_option('saxon_newsletter_db_version', '0');
        
        if (version_compare($current_version, '1.0', '<')) {
            $this->setup_database();
        }
    }

    /**
     * Get subscriber by email
     */
    public function get_subscriber_by_email($email) {
        global $wpdb;
        $table = $wpdb->prefix . 'newsletter_subscribers';
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE email = %s",
            $email
        ));
    }

    /**
     * Get subscriber by hash
     */
    public function get_subscriber_by_hash($hash) {
        global $wpdb;
        $table = $wpdb->prefix . 'newsletter_subscribers';
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE subscriber_hash = %s",
            $hash
        ));
    }

    /**
     * Add subscriber
     */
    public function add_subscriber($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'newsletter_subscribers';
        
        // Generate tokens and hash
        $data['verification_token'] = wp_generate_password(32, false);
        $data['unsubscribe_token'] = wp_generate_password(32, false);
        $data['subscriber_hash'] = md5($data['email']);
        
        return $wpdb->insert($table, $data);
    }

    /**
     * Update subscriber
     */
    public function update_subscriber($id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'newsletter_subscribers';
        return $wpdb->update($table, $data, ['id' => $id]);
    }

    /**
     * Delete subscriber
     */
    public function delete_subscriber($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'newsletter_subscribers';
        return $wpdb->delete($table, ['id' => $id]);
    }

    /**
     * Add to queue
     */
    public function add_to_queue($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'newsletter_queue';
        return $wpdb->insert($table, $data);
    }

    /**
     * Get queue items
     */
    public function get_queue_items($limit = 50, $status = 'pending') {
        global $wpdb;
        $table = $wpdb->prefix . 'newsletter_queue';
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE status = %s AND scheduled_time <= NOW() ORDER BY scheduled_time ASC LIMIT %d",
            $status,
            $limit
        ));
    }

    /**
     * Update queue item
     */
    public function update_queue_item($id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'newsletter_queue';
        return $wpdb->update($table, $data, ['id' => $id]);
    }

    /**
     * Add campaign
     */
    public function add_campaign($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'newsletter_campaigns';
        return $wpdb->insert($table, $data);
    }

    /**
     * Add stat
     */
    public function add_stat($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'newsletter_stats';
        return $wpdb->insert($table, $data);
    }

    /**
     * Clean up old data
     */
    public function cleanup() {
        global $wpdb;
        
        // Delete unverified subscribers after 48 hours
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}newsletter_subscribers 
            WHERE verified = 0 
            AND subscription_date < DATE_SUB(NOW(), INTERVAL 48 HOUR)"
        ));

        // Delete old queue items
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->prefix}newsletter_queue 
            WHERE status = 'sent' 
            AND sent_time < DATE_SUB(NOW(), INTERVAL 30 DAY)"
        ));
    }
}

// Initialize the database
Saxon_Newsletter_DB::get_instance();

/**
 * Render the subscribers admin page
 */
function saxon_subscribers_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'newsletter_subscribers';

    // Get current page number
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $per_page = 20;
    $offset = ($current_page - 1) * $per_page;

    // Get total subscribers
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $total_pages = ceil($total_items / $per_page);

    // Get subscribers for current page
    $subscribers = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY subscription_date DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        )
    );

    include get_template_directory() . '/template-parts/admin/newsletter-subscribers.php';
}