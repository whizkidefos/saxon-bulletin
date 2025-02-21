<?php
/**
 * Newsletter Verification and Unsubscribe Handlers
 */

if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Newsletter_Handlers {
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

        // Add rewrite rules for verification and unsubscribe
        add_action('init', [$this, 'add_rewrite_rules']);
        
        // Handle verification and unsubscribe requests
        add_action('template_redirect', [$this, 'handle_requests']);
    }

    /**
     * Add rewrite rules
     */
    public function add_rewrite_rules() {
        add_rewrite_rule(
            'newsletter/verify/([^/]+)/([^/]+)/?$',
            'index.php?newsletter_action=verify&email=$matches[1]&token=$matches[2]',
            'top'
        );

        add_rewrite_rule(
            'newsletter/unsubscribe/([^/]+)/([^/]+)/?$',
            'index.php?newsletter_action=unsubscribe&email=$matches[1]&token=$matches[2]',
            'top'
        );

        add_rewrite_tag('%newsletter_action%', '([^&]+)');
    }

    /**
     * Handle verification and unsubscribe requests
     */
    public function handle_requests() {
        $action = get_query_var('newsletter_action');
        if (!$action) return;

        $email = urldecode(get_query_var('email'));
        $token = get_query_var('token');

        if (!$email || !$token) {
            wp_die(__('Invalid request', 'saxon'));
        }

        switch ($action) {
            case 'verify':
                $this->handle_verification($email, $token);
                break;
            case 'unsubscribe':
                $this->handle_unsubscribe($email, $token);
                break;
        }
    }

    /**
     * Handle verification
     */
    private function handle_verification($email, $token) {
        global $wpdb;

        $subscriber = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_name} 
            WHERE email = %s AND verification_token = %s AND verified = 0",
            $email,
            $token
        ));

        if (!$subscriber) {
            wp_die(__('Invalid or expired verification link', 'saxon'));
        }

        // Check if token is expired
        if (strtotime($subscriber->verification_expiry) < time()) {
            $wpdb->delete($this->table_name, ['email' => $email]);
            wp_die(__('Verification link has expired. Please subscribe again.', 'saxon'));
        }

        // Verify subscriber
        $wpdb->update(
            $this->table_name,
            [
                'verified' => 1,
                'verification_token' => null,
                'verification_expiry' => null
            ],
            ['email' => $email],
            ['%d', null, null],
            ['%s']
        );

        // Show success message
        get_header();
        ?>
        <div class="max-w-2xl mx-auto px-4 py-16">
            <div class="bg-green-50 dark:bg-green-900 p-6 rounded-lg text-center">
                <svg class="mx-auto h-12 w-12 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="mt-4 text-2xl font-bold text-green-800 dark:text-green-200">
                    <?php esc_html_e('Success!', 'saxon'); ?>
                </h2>
                <p class="mt-2 text-green-700 dark:text-green-300">
                    <?php esc_html_e('Your email subscription has been confirmed.', 'saxon'); ?>
                </p>
                <a href="<?php echo esc_url(home_url()); ?>" 
                   class="mt-6 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <?php esc_html_e('Return to Homepage', 'saxon'); ?>
                </a>
            </div>
        </div>
        <?php
        get_footer();
        exit;
    }

    /**
     * Handle unsubscribe
     */
    private function handle_unsubscribe($email, $token) {
        global $wpdb;

        $subscriber = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_name} 
            WHERE email = %s AND unsubscribe_token = %s AND verified = 1",
            $email,
            $token
        ));

        if (!$subscriber) {
            wp_die(__('Invalid unsubscribe link', 'saxon'));
        }

        // If POST request, process unsubscribe
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_unsubscribe'])) {
            check_admin_referer('saxon_unsubscribe');

            $wpdb->delete($this->table_name, ['email' => $email]);

            get_header();
            ?>
            <div class="max-w-2xl mx-auto px-4 py-16">
                <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg text-center">
                    <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-200">
                        <?php esc_html_e('Unsubscribed Successfully', 'saxon'); ?>
                    </h2>
                    <p class="mt-2 text-blue-700 dark:text-blue-300">
                        <?php esc_html_e('You have been unsubscribed from our newsletter.', 'saxon'); ?>
                    </p>
                    <a href="<?php echo esc_url(home_url()); ?>" 
                       class="mt-6 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <?php esc_html_e('Return to Homepage', 'saxon'); ?>
                    </a>
                </div>
            </div>
            <?php
            get_footer();
            exit;
        }

        // Show confirmation form
        get_header();
        ?>
        <div class="max-w-2xl mx-auto px-4 py-16">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    <?php esc_html_e('Confirm Unsubscribe', 'saxon'); ?>
                </h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    <?php printf(
                        __('Are you sure you want to unsubscribe %s from our newsletter?', 'saxon'),
                        '<strong>' . esc_html($email) . '</strong>'
                    ); ?>
                </p>
                <form method="post">
                    <?php wp_nonce_field('saxon_unsubscribe'); ?>
                    <div class="flex justify-end space-x-4">
                        <a href="<?php echo esc_url(home_url()); ?>" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <?php esc_html_e('Cancel', 'saxon'); ?>
                        </a>
                        <button type="submit" 
                                name="confirm_unsubscribe" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <?php esc_html_e('Confirm Unsubscribe', 'saxon'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        get_footer();
        exit;
    }
}

// Initialize the handlers
Saxon_Newsletter_Handlers::get_instance();