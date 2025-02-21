<?php
/**
 * Newsletter Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Newsletter_Analytics {
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

        // Add dashboard widget
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widget']);

        // AJAX handlers for analytics data
        add_action('wp_ajax_saxon_get_subscriber_stats', [$this, 'get_subscriber_stats']);
        add_action('wp_ajax_saxon_get_growth_data', [$this, 'get_growth_data']);
        add_action('wp_ajax_saxon_get_category_stats', [$this, 'get_category_stats']);
    }

    /**
     * Add analytics menu
     */
    public function add_analytics_menu() {
        add_submenu_page(
            'saxon-newsletter',
            __('Analytics', 'saxon'),
            __('Analytics', 'saxon'),
            'manage_options',
            'saxon-newsletter-analytics',
            'saxon_analytics_page'
        );
    }

    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'saxon_newsletter_stats',
            __('Newsletter Stats', 'saxon'),
            [$this, 'render_dashboard_widget']
        );
    }

    /**
     * Get subscriber statistics
     */
    public function get_subscriber_stats() {
        check_ajax_referer('saxon-analytics');

        global $wpdb;

        $stats = [
            'total' => $wpdb->get_var(
                "SELECT COUNT(*) FROM {$this->table_name} WHERE verified = 1"
            ),
            'unverified' => $wpdb->get_var(
                "SELECT COUNT(*) FROM {$this->table_name} WHERE verified = 0"
            ),
            'this_month' => $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table_name} 
                WHERE verified = 1 
                AND MONTH(subscription_date) = MONTH(CURRENT_DATE())
                AND YEAR(subscription_date) = YEAR(CURRENT_DATE())"
            )),
            'frequency' => $wpdb->get_results(
                "SELECT frequency, COUNT(*) as count 
                FROM {$this->table_name} 
                WHERE verified = 1 
                GROUP BY frequency",
                ARRAY_A
            )
        ];

        wp_send_json_success($stats);
    }

    /**
     * Get growth data
     */
    public function get_growth_data() {
        check_ajax_referer('saxon-analytics');

        global $wpdb;

        // Get last 12 months of data
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT 
                DATE_FORMAT(subscription_date, '%Y-%m') as month,
                COUNT(*) as new_subscribers,
                SUM(verified = 1) as verified_subscribers
            FROM {$this->table_name}
            WHERE subscription_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(subscription_date, '%Y-%m')
            ORDER BY month ASC"
        ), ARRAY_A);

        wp_send_json_success($results);
    }

    /**
     * Get category statistics
     */
    public function get_category_stats() {
        check_ajax_referer('saxon-analytics');

        global $wpdb;

        $results = $wpdb->get_results(
            "SELECT 
                categories,
                COUNT(*) as subscriber_count
            FROM {$this->table_name}
            WHERE verified = 1 
            AND categories IS NOT NULL
            GROUP BY categories",
            ARRAY_A
        );

        // Process category data
        $category_stats = [];
        foreach ($results as $row) {
            $categories = explode(',', $row['categories']);
            foreach ($categories as $category_id) {
                $category = get_category($category_id);
                if ($category) {
                    $name = $category->name;
                    if (!isset($category_stats[$name])) {
                        $category_stats[$name] = 0;
                    }
                    $category_stats[$name] += $row['subscriber_count'];
                }
            }
        }

        wp_send_json_success($category_stats);
    }

    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        ?>
        <div class="saxon-dashboard-widget">
            <h2><?php esc_html_e('Newsletter Stats', 'saxon'); ?></h2>
            <ul>
                <li>
                    <span class="dashicons dashicons-chart-bar"></span>
                    <span class="label"><?php esc_html_e('Total Subscribers', 'saxon'); ?></span>
                    <span class="value">...</span>
                </li>
                <li>
                    <span class="dashicons dashicons-chart-pie"></span>
                    <span class="label"><?php esc_html_e('Active Subscribers', 'saxon'); ?></span>
                    <span class="value">...</span>
                </li>
                <li>
                    <span class="dashicons dashicons-chart-line"></span>
                    <span class="label"><?php esc_html_e('This Month', 'saxon'); ?></span>
                    <span class="value">...</span>
                </li>
            </ul>
        </div>
        <?php
    }
}

function saxon_analytics_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Newsletter Analytics', 'saxon'); ?></h1>
        
        <div class="analytics-dashboard">
            <!-- Subscriber Growth Chart -->
            <div class="analytics-card">
                <h2><?php esc_html_e('Subscriber Growth', 'saxon'); ?></h2>
                <div id="subscriber-growth-chart"></div>
            </div>

            <!-- Engagement Stats -->
            <div class="analytics-card">
                <h2><?php esc_html_e('Engagement Stats', 'saxon'); ?></h2>
                <div id="engagement-stats">
                    <div class="stat-item">
                        <span class="stat-label"><?php esc_html_e('Open Rate', 'saxon'); ?></span>
                        <span class="stat-value" id="open-rate">-</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label"><?php esc_html_e('Click Rate', 'saxon'); ?></span>
                        <span class="stat-value" id="click-rate">-</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label"><?php esc_html_e('Bounce Rate', 'saxon'); ?></span>
                        <span class="stat-value" id="bounce-rate">-</span>
                    </div>
                </div>
            </div>

            <!-- Popular Content -->
            <div class="analytics-card">
                <h2><?php esc_html_e('Most Popular Content', 'saxon'); ?></h2>
                <div id="popular-content"></div>
            </div>

            <!-- Geographic Distribution -->
            <div class="analytics-card">
                <h2><?php esc_html_e('Geographic Distribution', 'saxon'); ?></h2>
                <div id="geo-distribution"></div>
            </div>
        </div>
    </div>

    <style>
        .analytics-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .analytics-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
        }

        .analytics-card h2 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        #engagement-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .stat-label {
            display: block;
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .stat-value {
            display: block;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        #subscriber-growth-chart,
        #popular-content,
        #geo-distribution {
            height: 300px;
            margin-top: 15px;
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Load analytics data
        $.post(ajaxurl, {
            action: 'saxon_get_subscriber_stats',
            nonce: '<?php echo wp_create_nonce('saxon_analytics'); ?>'
        }, function(response) {
            if (response.success) {
                $('#open-rate').text(response.data.open_rate + '%');
                $('#click-rate').text(response.data.click_rate + '%');
                $('#bounce-rate').text(response.data.bounce_rate + '%');
            }
        });
    });
    </script>
    <?php
}
