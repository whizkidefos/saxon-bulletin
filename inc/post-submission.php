<?php
/**
 * Post Submission Functionality
 */

// Security: Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Post_Submission {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Create custom post status
        add_action('init', [$this, 'register_post_status']);
        
        // Add admin menu for submissions and settings
        add_action('admin_menu', [$this, 'add_admin_menus']);
        
        // Register settings
        add_action('admin_init', [$this, 'register_settings']);
        
        // Register submission form shortcode
        add_shortcode('saxon_submit_post', [$this, 'submission_form_shortcode']);
        
        // Handle form submission
        add_action('admin_post_nopriv_saxon_submit_post', [$this, 'handle_submission']);
        add_action('admin_post_saxon_submit_post', [$this, 'handle_submission']);
        
        // Add custom notification for pending submissions
        add_action('admin_notices', [$this, 'pending_submissions_notice']);
        
        // Add custom column to posts list
        add_filter('manage_posts_columns', [$this, 'add_submitted_by_column']);
        add_action('manage_posts_custom_column', [$this, 'submitted_by_column_content'], 10, 2);
    }

    /**
     * Add admin menus
     */
    public function add_admin_menus() {
        // Add main submissions menu
        add_menu_page(
            __('Post Submissions', 'saxon'),
            __('Submissions', 'saxon'),
            'edit_posts',
            'saxon-submissions',
            [$this, 'render_submissions_page'],
            'dashicons-welcome-write-blog',
            25
        );

        // Add settings submenu
        add_submenu_page(
            'saxon-submissions',
            __('Submission Settings', 'saxon'),
            __('Settings', 'saxon'),
            'manage_options',
            'saxon-submission-settings',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('saxon_submission_settings', 'saxon_recaptcha_site_key');
        register_setting('saxon_submission_settings', 'saxon_recaptcha_secret_key');
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Post Submission Settings', 'saxon'); ?></h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('saxon_submission_settings');
                do_settings_sections('saxon_submission_settings');
                ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="saxon_recaptcha_site_key">
                                <?php _e('reCAPTCHA Site Key', 'saxon'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="saxon_recaptcha_site_key" 
                                   name="saxon_recaptcha_site_key" 
                                   value="<?php echo esc_attr(get_option('saxon_recaptcha_site_key')); ?>" 
                                   class="regular-text">
                            <p class="description">
                                <?php _e('Enter your Google reCAPTCHA v2 site key', 'saxon'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="saxon_recaptcha_secret_key">
                                <?php _e('reCAPTCHA Secret Key', 'saxon'); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="saxon_recaptcha_secret_key" 
                                   name="saxon_recaptcha_secret_key" 
                                   value="<?php echo esc_attr(get_option('saxon_recaptcha_secret_key')); ?>" 
                                   class="regular-text">
                            <p class="description">
                                <?php _e('Enter your Google reCAPTCHA v2 secret key', 'saxon'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <div class="card">
                <h2><?php _e('How to get reCAPTCHA keys', 'saxon'); ?></h2>
                <ol>
                    <li><?php _e('Go to the <a href="https://www.google.com/recaptcha/admin" target="_blank">Google reCAPTCHA Admin Console</a>', 'saxon'); ?></li>
                    <li><?php _e('Click the "+" button to create a new site', 'saxon'); ?></li>
                    <li><?php _e('Choose "reCAPTCHA v2" and "I\'m not a robot Checkbox"', 'saxon'); ?></li>
                    <li><?php _e('Add your domain and complete the registration', 'saxon'); ?></li>
                    <li><?php _e('Copy the Site Key and Secret Key to the fields above', 'saxon'); ?></li>
                </ol>
            </div>
        </div>
        <?php
    }

    /**
     * Register custom post status for submissions
     */
    public function register_post_status() {
        register_post_status('awaiting_review', [
            'label'                     => _x('Awaiting Review', 'post status', 'saxon'),
            'public'                    => false,
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop('Awaiting Review <span class="count">(%s)</span>',
                                                 'Awaiting Review <span class="count">(%s)</span>', 'saxon'),
        ]);
    }

    /**
     * Add submissions menu to admin
     */
    public function add_submissions_menu() {
        add_menu_page(
            __('Post Submissions', 'saxon'),
            __('Submissions', 'saxon'),
            'edit_posts',
            'saxon-submissions',
            [$this, 'render_submissions_page'],
            'dashicons-welcome-write-blog',
            25
        );
    }

    /**
     * Render submissions page
     */
    public function render_submissions_page() {
        // Get submissions
        $submissions = get_posts([
            'post_type' => 'post',
            'post_status' => 'awaiting_review',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        ]);

        ?>
        <div class="wrap">
            <h1><?php _e('Post Submissions', 'saxon'); ?></h1>
            
            <?php if (!empty($submissions)): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Title', 'saxon'); ?></th>
                            <th><?php _e('Author', 'saxon'); ?></th>
                            <th><?php _e('Category', 'saxon'); ?></th>
                            <th><?php _e('Submitted', 'saxon'); ?></th>
                            <th><?php _e('Actions', 'saxon'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $post): ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html($post->post_title); ?></strong>
                                </td>
                                <td>
                                    <?php echo esc_html(get_post_meta($post->ID, '_submitted_by_email', true)); ?>
                                </td>
                                <td>
                                    <?php echo get_the_category_list(', ', '', $post->ID); ?>
                                </td>
                                <td>
                                    <?php echo get_the_date('', $post->ID); ?>
                                </td>
                                <td>
                                    <a href="<?php echo get_edit_post_link($post->ID); ?>" class="button button-small">
                                        <?php _e('Review', 'saxon'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php _e('No submissions found.', 'saxon'); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Handle form submission
     */
    public function handle_submission() {
        // Verify nonce
        if (!isset($_POST['saxon_post_nonce']) || 
            !wp_verify_nonce($_POST['saxon_post_nonce'], 'saxon_post_submission')) {
            wp_die(__('Security check failed', 'saxon'));
        }

        // Verify CAPTCHA
        if (!$this->verify_captcha()) {
            wp_die(__('CAPTCHA verification failed', 'saxon'));
        }

        // Basic anti-spam check
        if (!empty($_POST['website'])) { // Honeypot field
            wp_die(__('Nice try, bot!', 'saxon'));
        }

        // Rate limiting
        $this->check_rate_limit();

        // Sanitize and validate inputs
        $title = isset($_POST['post_title']) ? sanitize_text_field($_POST['post_title']) : '';
        $content = isset($_POST['post_content']) ? wp_kses_post($_POST['post_content']) : '';
        $category = isset($_POST['post_category']) ? (int)$_POST['post_category'] : 0;
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

        // Enhanced validation
        $errors = [];

        if (empty($title) || strlen($title) < 10) {
            $errors[] = __('Title must be at least 10 characters long', 'saxon');
        }

        if (empty($content) || strlen(strip_tags($content)) < 100) {
            $errors[] = __('Content must be at least 100 characters long', 'saxon');
        }

        if (!$category || !term_exists($category, 'category')) {
            $errors[] = __('Please select a valid category', 'saxon');
        }

        if (!is_email($email)) {
            $errors[] = __('Please enter a valid email address', 'saxon');
        }

        if (!empty($errors)) {
            wp_die(implode('<br>', $errors));
        }

        // Create post
        $post_data = [
            'post_title'     => $title,
            'post_content'   => $content,
            'post_status'    => 'awaiting_review',
            'post_type'      => 'post',
            'post_category'  => [$category],
            'meta_input'     => [
                '_submitted_by_ip'    => $_SERVER['REMOTE_ADDR'],
                '_submitted_by_email' => $email,
                '_submission_date'    => current_time('mysql'),
            ],
        ];

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            wp_die($post_id->get_error_message());
        }

        // Handle image upload if present
        if (!empty($_FILES['featured_image']['name'])) {
            $upload_result = $this->handle_image_upload($post_id);
            if (is_wp_error($upload_result)) {
                // Log error but don't stop the submission
                error_log($upload_result->get_error_message());
            }
        }

        // Send notifications
        $this->send_notifications($post_id);

        // Store draft in session
        if (isset($_SESSION['post_draft'])) {
            unset($_SESSION['post_draft']);
        }

        // Redirect to thank you page
        wp_safe_redirect(add_query_arg('submission', 'success', wp_get_referer()));
        exit;
    }

    /**
     * Verify CAPTCHA
     */
    private function verify_captcha() {
        if (!isset($_POST['g-recaptcha-response'])) {
            return false;
        }

        $secret_key = get_option('saxon_recaptcha_secret_key');
        if (empty($secret_key)) {
            return true; // Skip verification if key is not set
        }

        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $secret_key,
                'response' => $_POST['g-recaptcha-response']
            ]
        ]);

        if (is_wp_error($response)) {
            return true; // Skip verification on error
        }

        $result = json_decode(wp_remote_retrieve_body($response));
        return isset($result->success) && $result->success;
    }

    /**
     * Rate limiting check
     */
    private function check_rate_limit() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $transient_name = 'submission_count_' . md5($ip);
        $submission_count = get_transient($transient_name);

        if ($submission_count === false) {
            set_transient($transient_name, 1, HOUR_IN_SECONDS);
        } elseif ($submission_count >= 3) {
            wp_die(__('Too many submissions. Please try again later.', 'saxon'));
        } else {
            set_transient($transient_name, $submission_count + 1, HOUR_IN_SECONDS);
        }
    }

    /**
     * Handle image upload
     */
    private function handle_image_upload($post_id) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file = $_FILES['featured_image'];

        // Validate file type
        if (!in_array($file['type'], $allowed_types)) {
            return false;
        }

        // Upload and attach the image
        $attachment_id = media_handle_upload('featured_image', $post_id);

        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($post_id, $attachment_id);
        }
    }

    /**
     * Send notifications
     */
    private function send_notifications($post_id) {
        $admin_email = get_option('admin_email');
        $post = get_post($post_id);
        $subject = sprintf(__('New post submission: %s', 'saxon'), $post->post_title);
        
        $message = sprintf(
            __("A new post has been submitted:\n\nTitle: %s\nSubmitted by: %s\n\nView submission: %s", 'saxon'),
            $post->post_title,
            get_post_meta($post_id, '_submitted_by_email', true),
            admin_url('post.php?post=' . $post_id . '&action=edit')
        );

        wp_mail($admin_email, $subject, $message);
    }

    /**
     * Submission form shortcode
     */
    public function submission_form_shortcode() {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to submit a post.', 'saxon') . '</p>';
        }

        ob_start();
        include get_template_directory() . '/template-parts/forms/post-submission.php';
        return ob_get_clean();
    }

    /**
     * Add custom column to posts list
     */
    public function add_submitted_by_column($columns) {
        $columns['submitted_by'] = __('Submitted By', 'saxon');
        return $columns;
    }

    /**
     * Custom column content
     */
    public function submitted_by_column_content($column_name, $post_id) {
        if ($column_name === 'submitted_by') {
            echo esc_html(get_post_meta($post_id, '_submitted_by_email', true));
        }
    }

    /**
     * Add custom notification for pending submissions
     */
    public function pending_submissions_notice() {
        $pending_submissions = get_posts([
            'post_type' => 'post',
            'post_status' => 'awaiting_review',
            'posts_per_page' => -1,
        ]);

        if (!empty($pending_submissions)) {
            ?>
            <div class="notice notice-warning">
                <p>
                    <?php printf(__('You have %d pending post submissions.', 'saxon'), count($pending_submissions)); ?>
                    <a href="<?php echo admin_url('edit.php?post_status=awaiting_review'); ?>">
                        <?php _e('View pending submissions', 'saxon'); ?>
                    </a>
                </p>
            </div>
            <?php
        }
    }
}

// Initialize the class
Saxon_Post_Submission::get_instance();