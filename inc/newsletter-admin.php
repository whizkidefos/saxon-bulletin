<?php
/**
 * Newsletter Admin Interface
 */

if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Newsletter_Admin {
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

        // Add admin menu
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        // Add admin scripts
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        
        // Handle AJAX actions
        add_action('wp_ajax_saxon_delete_subscriber', [$this, 'delete_subscriber']);
        add_action('wp_ajax_saxon_export_subscribers', [$this, 'export_subscribers']);
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Newsletter', 'saxon'),
            __('Newsletter', 'saxon'),
            'manage_options',
            'saxon-newsletter',
            [$this, 'render_admin_page'],
            'dashicons-email',
            30
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_saxon-newsletter') {
            return;
        }

        wp_enqueue_style(
            'saxon-newsletter-admin',
            get_template_directory_uri() . '/assets/css/newsletter-admin.css',
            [],
            SAXON_VERSION
        );

        wp_enqueue_script(
            'saxon-newsletter-admin',
            get_template_directory_uri() . '/assets/js/newsletter-admin.js',
            ['jquery'],
            SAXON_VERSION,
            true
        );

        wp_localize_script('saxon-newsletter-admin', 'saxonNewsletter', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('saxon-newsletter-admin'),
            'confirmDelete' => __('Are you sure you want to delete this subscriber?', 'saxon'),
        ]);
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        // Handle bulk actions
        $this->handle_bulk_actions();

        // Get subscribers with pagination
        $per_page = 20;
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $offset = ($current_page - 1) * $per_page;

        global $wpdb;
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
        $total_pages = ceil($total_items / $per_page);

        $subscribers = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->table_name} 
            ORDER BY subscription_date DESC 
            LIMIT %d OFFSET %d",
            $per_page,
            $offset
        ));
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e('Newsletter Subscribers', 'saxon'); ?></h1>
            
            <!-- Export Button -->
            <a href="<?php echo wp_nonce_url(admin_url('admin-ajax.php?action=saxon_export_subscribers'), 'saxon_export_subscribers'); ?>" 
               class="page-title-action">
                <?php esc_html_e('Export Subscribers', 'saxon'); ?>
            </a>

            <!-- Stats -->
            <div class="saxon-newsletter-stats">
                <div class="stat-box">
                    <h3><?php esc_html_e('Total Subscribers', 'saxon'); ?></h3>
                    <span class="stat-number"><?php echo number_format_i18n($total_items); ?></span>
                </div>
                <div class="stat-box">
                    <h3><?php esc_html_e('Verified Subscribers', 'saxon'); ?></h3>
                    <span class="stat-number">
                        <?php 
                        $verified = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name} WHERE verified = 1");
                        echo number_format_i18n($verified); 
                        ?>
                    </span>
                </div>
                <div class="stat-box">
                    <h3><?php esc_html_e('This Month', 'saxon'); ?></h3>
                    <span class="stat-number">
                        <?php 
                        $month_start = date('Y-m-01 00:00:00');
                        $this_month = $wpdb->get_var($wpdb->prepare(
                            "SELECT COUNT(*) FROM {$this->table_name} WHERE subscription_date >= %s",
                            $month_start
                        ));
                        echo number_format_i18n($this_month); 
                        ?>
                    </span>
                </div>
            </div>

            <!-- Subscribers Table -->
            <form method="post">
                <?php wp_nonce_field('saxon_bulk_subscribers', 'subscriber_nonce'); ?>
                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <select name="action">
                            <option value="-1"><?php esc_html_e('Bulk Actions', 'saxon'); ?></option>
                            <option value="delete"><?php esc_html_e('Delete', 'saxon'); ?></option>
                            <option value="resend-verification"><?php esc_html_e('Resend Verification', 'saxon'); ?></option>
                        </select>
                        <input type="submit" class="button action" value="<?php esc_attr_e('Apply', 'saxon'); ?>">
                    </div>
                    
                    <!-- Pagination -->
                    <div class="tablenav-pages">
                        <?php
                        echo paginate_links([
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => __('&laquo;'),
                            'next_text' => __('&raquo;'),
                            'total' => $total_pages,
                            'current' => $current_page
                        ]);
                        ?>
                    </div>
                </div>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <td class="manage-column column-cb check-column">
                                <input type="checkbox">
                            </td>
                            <th scope="col"><?php esc_html_e('Email', 'saxon'); ?></th>
                            <th scope="col"><?php esc_html_e('Name', 'saxon'); ?></th>
                            <th scope="col"><?php esc_html_e('Status', 'saxon'); ?></th>
                            <th scope="col"><?php esc_html_e('Categories', 'saxon'); ?></th>
                            <th scope="col"><?php esc_html_e('Frequency', 'saxon'); ?></th>
                            <th scope="col"><?php esc_html_e('Date', 'saxon'); ?></th>
                            <th scope="col"><?php esc_html_e('Actions', 'saxon'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subscribers as $subscriber): ?>
                            <tr>
                                <th scope="row" class="check-column">
                                    <input type="checkbox" name="subscribers[]" value="<?php echo esc_attr($subscriber->id); ?>">
                                </th>
                                <td><?php echo esc_html($subscriber->email); ?></td>
                                <td><?php echo esc_html($subscriber->first_name); ?></td>
                                <td>
                                    <?php if ($subscriber->verified): ?>
                                        <span class="status-verified"><?php esc_html_e('Verified', 'saxon'); ?></span>
                                    <?php else: ?>
                                        <span class="status-pending"><?php esc_html_e('Pending', 'saxon'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $categories = !empty($subscriber->categories) ? 
                                        explode(',', $subscriber->categories) : [];
                                    if ($categories) {
                                        foreach ($categories as $cat_id) {
                                            $category = get_category($cat_id);
                                            if ($category) {
                                                echo '<span class="category-tag">' . 
                                                     esc_html($category->name) . '</span>';
                                            }
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?php echo esc_html(ucfirst($subscriber->frequency)); ?></td>
                                <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($subscriber->subscription_date))); ?></td>
                                <td>
                                    <div class="row-actions">
                                        <?php if (!$subscriber->verified): ?>
                                            <span class="resend">
                                                <a href="#" class="resend-verification" data-id="<?php echo esc_attr($subscriber->id); ?>">
                                                    <?php esc_html_e('Resend Verification', 'saxon'); ?>
                                                </a> |
                                            </span>
                                        <?php endif; ?>
                                        <span class="delete">
                                            <a href="#" class="delete-subscriber" data-id="<?php echo esc_attr($subscriber->id); ?>">
                                                <?php esc_html_e('Delete', 'saxon'); ?>
                                            </a>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>
        <?php
    }

    /**
     * Handle bulk actions
     */
    private function handle_bulk_actions() {
        if (!isset($_POST['subscriber_nonce']) || 
            !wp_verify_nonce($_POST['subscriber_nonce'], 'saxon_bulk_subscribers')) {
            return;
        }

        if (!isset($_POST['action']) || !isset($_POST['subscribers'])) {
            return;
        }

        $action = $_POST['action'];
        $subscribers = array_map('intval', $_POST['subscribers']);

        switch ($action) {
            case 'delete':
                $this->bulk_delete_subscribers($subscribers);
                break;
            case 'resend-verification':
                $this->bulk_resend_verification($subscribers);
                break;
        }
    }

    /**
     * Bulk delete subscribers
     */
    private function bulk_delete_subscribers($subscriber_ids) {
        global $wpdb;
        $subscriber_ids = implode(',', array_map('intval', $subscriber_ids));
        $wpdb->query("DELETE FROM {$this->table_name} WHERE id IN ({$subscriber_ids})");
        
        add_settings_error(
            'saxon_newsletter',
            'subscribers_deleted',
            __('Selected subscribers have been deleted.', 'saxon'),
            'success'
        );
    }

    /**
     * Bulk resend verification
     */
    private function bulk_resend_verification($subscriber_ids) {
        global $wpdb;
        $subscribers = $wpdb->get_results(
            "SELECT * FROM {$this->table_name} 
            WHERE id IN (" . implode(',', array_map('intval', $subscriber_ids)) . ")
            AND verified = 0"
        );

        foreach ($subscribers as $subscriber) {
            // Generate new verification token
            $token = wp_generate_password(32, false);
            $wpdb->update(
                $this->table_name,
                [
                    'verification_token' => $token,
                    'verification_expiry' => date('Y-m-d H:i:s', strtotime('+24 hours'))
                ],
                ['id' => $subscriber->id],
                ['%s', '%s'],
                ['%d']
            );

            // Send verification email
            $this->send_verification_email($subscriber->email, $token);
        }

        add_settings_error(
            'saxon_newsletter',
            'verification_resent',
            __('Verification emails have been resent.', 'saxon'),
            'success'
        );
    }

    /**
     * AJAX handler for deleting subscriber
     */
    public function delete_subscriber() {
        check_ajax_referer('saxon-newsletter-admin');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if (!$id) {
            wp_send_json_error('Invalid subscriber ID');
        }

        global $wpdb;
        $wpdb->delete($this->table_name, ['id' => $id], ['%d']);
        wp_send_json_success();
    }

    /**
     * Export subscribers
     */
    public function export_subscribers() {
        check_admin_referer('saxon_export_subscribers');

        if (!current_user_can('manage_options')) {
            wp_die('Permission denied');
        }

        global $wpdb;
        $subscribers = $wpdb->get_results(
            "SELECT * FROM {$this->table_name} 
            WHERE verified = 1 
            ORDER BY subscription_date DESC",
            ARRAY_A
        );

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=newsletter-subscribers-' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, array_keys($subscribers[0]));
        
        // Add subscriber data
        foreach ($subscribers as $subscriber) {
            fputcsv($output, $subscriber);
        }
        
        fclose($output);
        exit;
    }
}

// Initialize the admin interface
Saxon_Newsletter_Admin::get_instance();