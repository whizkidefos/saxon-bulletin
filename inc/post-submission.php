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
        
        // Add admin menu for submissions
        add_action('admin_menu', [$this, 'add_submissions_menu']);
        
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
            __('Submissions', 'saxon'),
            __('Submissions', 'saxon'),
            'edit_posts',
            'submissions',
            [$this, 'submissions_page'],
            'dashicons-welcome-write-blog',
            25
        );
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

        // Validation
        if (empty($title) || empty($content)) {
            wp_die(__('Title and content are required', 'saxon'));
        }

        if (strlen($title) < 10 || strlen($content) < 100) {
            wp_die(__('Title must be at least 10 characters and content at least 100 characters', 'saxon'));
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
                '_submitted_by_email' => sanitize_email($_POST['email']),
                '_submission_date'    => current_time('mysql'),
            ],
        ];

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            wp_die($post_id->get_error_message());
        }

        // Handle image upload if present
        if (!empty($_FILES['featured_image']['name'])) {
            $this->handle_image_upload($post_id);
        }

        // Send notifications
        $this->send_notifications($post_id);

        // Redirect to thank you page
        wp_safe_redirect(add_query_arg('submission', 'success', wp_get_referer()));
        exit;
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
}

// Initialize the class
Saxon_Post_Submission::get_instance();