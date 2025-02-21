<?php
/**
 * Newsletter Settings Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Newsletter_Settings {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * Add settings page
     */
    public function add_settings_page() {
        add_submenu_page(
            'saxon-newsletter',
            __('Newsletter Settings', 'saxon'),
            __('Settings', 'saxon'),
            'manage_options',
            'saxon-newsletter-settings',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // General Settings
        register_setting('saxon_newsletter_general', 'saxon_newsletter_from_name');
        register_setting('saxon_newsletter_general', 'saxon_newsletter_from_email', [
            'sanitize_callback' => 'sanitize_email'
        ]);
        register_setting('saxon_newsletter_general', 'saxon_newsletter_reply_to', [
            'sanitize_callback' => 'sanitize_email'
        ]);

        // Sending Settings
        register_setting('saxon_newsletter_sending', 'saxon_newsletter_max_sends_per_hour', [
            'sanitize_callback' => 'absint'
        ]);
        register_setting('saxon_newsletter_sending', 'saxon_newsletter_batch_size', [
            'sanitize_callback' => 'absint'
        ]);
        register_setting('saxon_newsletter_sending', 'saxon_newsletter_sending_method');

        // Form Settings
        register_setting('saxon_newsletter_form', 'saxon_newsletter_form_title');
        register_setting('saxon_newsletter_form', 'saxon_newsletter_form_description');
        register_setting('saxon_newsletter_form', 'saxon_newsletter_success_message');
        register_setting('saxon_newsletter_form', 'saxon_newsletter_gdpr_message');
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <h2 class="nav-tab-wrapper">
                <a href="?page=saxon-newsletter-settings&tab=general" 
                   class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('General', 'saxon'); ?>
                </a>
                <a href="?page=saxon-newsletter-settings&tab=sending" 
                   class="nav-tab <?php echo $active_tab == 'sending' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Sending', 'saxon'); ?>
                </a>
                <a href="?page=saxon-newsletter-settings&tab=form" 
                   class="nav-tab <?php echo $active_tab == 'form' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Form', 'saxon'); ?>
                </a>
                <a href="?page=saxon-newsletter-settings&tab=integrations" 
                   class="nav-tab <?php echo $active_tab == 'integrations' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Integrations', 'saxon'); ?>
                </a>
            </h2>

            <form method="post" action="options.php">
                <?php
                switch ($active_tab) {
                    case 'sending':
                        settings_fields('saxon_newsletter_sending');
                        $this->render_sending_settings();
                        break;
                    case 'form':
                        settings_fields('saxon_newsletter_form');
                        $this->render_form_settings();
                        break;
                    case 'integrations':
                        settings_fields('saxon_newsletter_integrations');
                        $this->render_integration_settings();
                        break;
                    default:
                        settings_fields('saxon_newsletter_general');
                        $this->render_general_settings();
                }
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render general settings
     */
    private function render_general_settings() {
        ?>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">
                    <label for="saxon_newsletter_from_name">
                        <?php esc_html_e('From Name', 'saxon'); ?>
                    </label>
                </th>
                <td>
                    <input type="text" id="saxon_newsletter_from_name" name="saxon_newsletter_from_name" 
                           value="<?php echo esc_attr(get_option('saxon_newsletter_from_name')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="saxon_newsletter_from_email">
                        <?php esc_html_e('From Email', 'saxon'); ?>
                    </label>
                </th>
                <td>
                    <input type="email" id="saxon_newsletter_from_email" name="saxon_newsletter_from_email" 
                           value="<?php echo esc_attr(get_option('saxon_newsletter_from_email')); ?>" 
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="saxon_newsletter_reply_to">
                        <?php esc_html_e('Reply-To Email', 'saxon'); ?>
                    </label>
                </th>
                <td>
                    <input type="email" id="saxon_newsletter_reply_to" name="saxon_newsletter_reply_to" 
                           value="<?php echo esc_attr(get_option('saxon_newsletter_reply_to')); ?>" 
                           class="regular-text">
                </td>
            </tr>
        </table>
        <?php
    }
}