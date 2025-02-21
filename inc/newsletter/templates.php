<?php
/**
 * Newsletter Template Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Newsletter_Templates {
    private static $instance = null;
    private $template_path;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->template_path = get_template_directory() . '/templates/newsletter/';

        // Create template directory if it doesn't exist
        if (!file_exists($this->template_path)) {
            wp_mkdir_p($this->template_path);
        }

        // Add admin menu for templates
        add_action('admin_menu', [$this, 'add_templates_menu']);

        // Add AJAX handlers
        add_action('wp_ajax_saxon_save_template', [$this, 'save_template']);
        add_action('wp_ajax_saxon_delete_template', [$this, 'delete_template']);
        add_action('wp_ajax_saxon_preview_template', [$this, 'preview_template']);
    }

    /**
     * Add templates menu
     */
    public function add_templates_menu() {
        add_submenu_page(
            'saxon-newsletter',
            __('Email Templates', 'saxon'),
            __('Email Templates', 'saxon'),
            'manage_options',
            'saxon-newsletter-templates',
            'saxon_templates_page'
        );
    }

    /**
     * Get available templates
     */
    public function get_templates() {
        $templates = [];
        $default_templates = [
            'welcome' => [
                'name' => __('Welcome Email', 'saxon'),
                'description' => __('Sent when a subscriber confirms their subscription', 'saxon'),
                'subject' => __('Welcome to our newsletter!', 'saxon'),
            ],
            'weekly_digest' => [
                'name' => __('Weekly Digest', 'saxon'),
                'description' => __('Weekly roundup of latest posts', 'saxon'),
                'subject' => __('Your Weekly Update', 'saxon'),
            ],
            'monthly_digest' => [
                'name' => __('Monthly Digest', 'saxon'),
                'description' => __('Monthly roundup of best content', 'saxon'),
                'subject' => __('Monthly Newsletter', 'saxon'),
            ],
        ];

        // Load default templates
        foreach ($default_templates as $slug => $template) {
            $file_path = $this->template_path . $slug . '.html';
            if (!file_exists($file_path)) {
                file_put_contents($file_path, $this->get_default_template_content($slug));
            }
            $templates[$slug] = array_merge($template, [
                'content' => file_get_contents($file_path),
            ]);
        }

        // Load custom templates
        $custom_templates = get_option('saxon_newsletter_templates', []);
        foreach ($custom_templates as $slug => $template) {
            $file_path = $this->template_path . $slug . '.html';
            if (file_exists($file_path)) {
                $templates[$slug] = array_merge($template, [
                    'content' => file_get_contents($file_path),
                ]);
            }
        }

        return $templates;
    }

    /**
     * Get default template content
     */
    private function get_default_template_content($slug) {
        $common_styles = '
            body { font-family: -apple-system, system-ui, sans-serif; line-height: 1.5; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { text-align: center; padding: 20px 0; }
            .content { background: #fff; padding: 20px; border-radius: 8px; }
            .footer { text-align: center; padding: 20px 0; font-size: 12px; color: #666; }
            .button { display: inline-block; padding: 10px 20px; background: #3B82F6; color: #fff; text-decoration: none; border-radius: 4px; }
        ';

        switch ($slug) {
            case 'welcome':
                return '
                    <style>' . $common_styles . '</style>
                    <div class="container">
                        <div class="header">
                            <h1>Welcome to {{blog_name}}!</h1>
                        </div>
                        <div class="content">
                            <p>Hi {{subscriber_name}},</p>
                            <p>Thank you for subscribing to our newsletter. We\'re excited to share our latest updates and insights with you.</p>
                            <p><a href="{{blog_url}}" class="button">Visit Our Site</a></p>
                        </div>
                        <div class="footer">
                            <p>You\'re receiving this email because you subscribed to our newsletter.</p>
                            <p><a href="{{unsubscribe_url}}">Unsubscribe</a></p>
                        </div>
                    </div>
                ';

            case 'weekly_digest':
                return '
                    <style>' . $common_styles . '</style>
                    <div class="container">
                        <div class="header">
                            <h1>Your Weekly Update</h1>
                        </div>
                        <div class="content">
                            <p>Hello {{subscriber_name}},</p>
                            <p>Here are our latest posts from this week:</p>
                            {{latest_posts}}
                        </div>
                        <div class="footer">
                            <p>You\'re receiving this email because you subscribed to our newsletter.</p>
                            <p><a href="{{unsubscribe_url}}">Unsubscribe</a></p>
                        </div>
                    </div>
                ';

            case 'monthly_digest':
                return '
                    <style>' . $common_styles . '</style>
                    <div class="container">
                        <div class="header">
                            <h1>Monthly Newsletter</h1>
                        </div>
                        <div class="content">
                            <p>Hello {{subscriber_name}},</p>
                            <p>Here\'s what you might have missed this month:</p>
                            {{popular_posts}}
                            {{category_highlights}}
                        </div>
                        <div class="footer">
                            <p>You\'re receiving this email because you subscribed to our newsletter.</p>
                            <p><a href="{{unsubscribe_url}}">Unsubscribe</a></p>
                        </div>
                    </div>
                ';

            default:
                return '';
        }
    }

    /**
     * Get template variables
     */
    public function get_template_variables() {
        return [
            'blog_name' => get_bloginfo('name'),
            'blog_url' => home_url(),
            'subscriber_name' => '{{subscriber_name}}',
            'unsubscribe_url' => '{{unsubscribe_url}}',
            'latest_posts' => $this->get_latest_posts_html(),
            'popular_posts' => $this->get_popular_posts_html(),
            'category_highlights' => $this->get_category_highlights_html(),
        ];
    }

    /**
     * Process template
     */
    public function process_template($template_slug, $subscriber_data = []) {
        $templates = $this->get_templates();
        if (!isset($templates[$template_slug])) {
            return false;
        }

        $template = $templates[$template_slug];
        $content = $template['content'];

        // Replace variables
        $variables = $this->get_template_variables();
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        // Replace subscriber-specific variables
        if ($subscriber_data) {
            $content = str_replace(
                '{{subscriber_name}}',
                $subscriber_data['first_name'] ?: __('Subscriber', 'saxon'),
                $content
            );
            $content = str_replace(
                '{{unsubscribe_url}}',
                add_query_arg([
                    'action' => 'unsubscribe',
                    'email' => urlencode($subscriber_data['email']),
                    'token' => $subscriber_data['unsubscribe_token']
                ], home_url()),
                $content
            );
        }

        return [
            'subject' => $template['subject'],
            'content' => $content
        ];
    }

    /**
     * Get latest posts HTML
     */
    private function get_latest_posts_html() {
        $posts = get_posts([
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC'
        ]);

        $html = '<ul style="list-style: none; padding: 0;">';
        foreach ($posts as $post) {
            $html .= sprintf(
                '<li style="margin-bottom: 15px;">
                    <h3 style="margin: 0;"><a href="%s" style="color: #3B82F6; text-decoration: none;">%s</a></h3>
                    <p style="margin: 5px 0; color: #666;">%s</p>
                </li>',
                get_permalink($post),
                $post->post_title,
                wp_trim_words(get_the_excerpt($post), 20)
            );
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Get popular posts HTML
     */
    private function get_popular_posts_html() {
        $posts = get_posts([
            'posts_per_page' => 5,
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        ]);

        $html = '<h2 style="margin-top: 30px;">Popular Posts</h2>';
        $html .= '<ul style="list-style: none; padding: 0;">';
        foreach ($posts as $post) {
            $html .= sprintf(
                '<li style="margin-bottom: 15px;">
                    <h3 style="margin: 0;"><a href="%s" style="color: #3B82F6; text-decoration: none;">%s</a></h3>
                    <p style="margin: 5px 0; color: #666;">%s</p>
                </li>',
                get_permalink($post),
                $post->post_title,
                wp_trim_words(get_the_excerpt($post), 20)
            );
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Get category highlights HTML
     */
    private function get_category_highlights_html() {
        $categories = get_categories([
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 3
        ]);

        $html = '<h2 style="margin-top: 30px;">Category Highlights</h2>';
        
        foreach ($categories as $category) {
            $posts = get_posts([
                'category' => $category->term_id,
                'posts_per_page' => 3
            ]);

            if ($posts) {
                $html .= sprintf(
                    '<h3 style="margin: 20px 0 10px; color: #333;">%s</h3>',
                    $category->name
                );
                $html .= '<ul style="list-style: none; padding: 0;">';
                
                foreach ($posts as $post) {
                    $html .= sprintf(
                        '<li style="margin-bottom: 10px;">
                            <a href="%s" style="color: #3B82F6; text-decoration: none;">%s</a>
                        </li>',
                        get_permalink($post),
                        $post->post_title
                    );
                }
                
                $html .= '</ul>';
            }
        }

        return $html;
    }

    /**
     * Save template
     */
    public function save_template() {
        check_ajax_referer('saxon-template-editor');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        $slug = sanitize_title($_POST['template_slug']);
        $name = sanitize_text_field($_POST['template_name']);
        $description = sanitize_textarea_field($_POST['template_description']);
        $subject = sanitize_text_field($_POST['template_subject']);
        $content = wp_kses_post($_POST['template_content']);

        // Save template file
        $file_path = $this->template_path . $slug . '.html';
        file_put_contents($file_path, $content);

        // Save template metadata
        $templates = get_option('saxon_newsletter_templates', []);
        $templates[$slug] = [
            'name' => $name,
            'description' => $description,
            'subject' => $subject,
        ];
        update_option('saxon_newsletter_templates', $templates);

        wp_send_json_success();
    }

    /**
     * Delete template
     */
    public function delete_template() {
        check_ajax_referer('saxon-template-editor');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        $slug = sanitize_title($_POST['template_slug']);

        // Don't allow deletion of default templates
        if (in_array($slug, ['welcome', 'weekly_digest', 'monthly_digest'])) {
            wp_send_json_error('Cannot delete default templates');
        }

        // Delete template file
        $file_path = $this->template_path . $slug . '.html';
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Remove template metadata
        $templates = get_option('saxon_newsletter_templates', []);
        unset($templates[$slug]);
        update_option('saxon_newsletter_templates', $templates);

        wp_send_json_success();
    }

    /**
     * Preview template
     */
    public function preview_template() {
        check_ajax_referer('saxon-template-editor');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        $content = wp_kses_post($_POST['content']);
        $preview_data = [
            'subscriber_name' => 'John Doe',
            'email' => 'example@email.com',
            'unsubscribe_token' => 'preview_token'
        ];

        // Process template variables
        $variables = $this->get_template_variables();
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        // Replace subscriber data
        $content = str_replace('{{subscriber_name}}', $preview_data['subscriber_name'], $content);
        $content = str_replace('{{unsubscribe_url}}', '#preview-unsubscribe', $content);

        wp_send_json_success(['content' => $content]);
    }
}

// Initialize the template manager
Saxon_Newsletter_Templates::get_instance();

/**
 * Render the templates admin page
 */
function saxon_templates_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $templates = Saxon_Newsletter_Templates::get_instance()->get_templates();
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e('Email Templates', 'saxon'); ?></h1>
        <a href="#" class="page-title-action add-template"><?php esc_html_e('Add New Template', 'saxon'); ?></a>

        <div class="template-list">
            <?php foreach ($templates as $slug => $template): ?>
                <div class="template-card" data-slug="<?php echo esc_attr($slug); ?>">
                    <div class="template-header">
                        <h2><?php echo esc_html($template['name']); ?></h2>
                        <p class="description"><?php echo esc_html($template['description']); ?></p>
                    </div>
                    <div class="template-actions">
                        <button class="button edit-template"><?php esc_html_e('Edit', 'saxon'); ?></button>
                        <button class="button preview-template"><?php esc_html_e('Preview', 'saxon'); ?></button>
                        <?php if (!in_array($slug, ['welcome', 'weekly_digest', 'monthly_digest'])): ?>
                            <button class="button delete-template"><?php esc_html_e('Delete', 'saxon'); ?></button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Template Editor Modal -->
        <div id="template-editor" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><?php esc_html_e('Edit Template', 'saxon'); ?></h2>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="template-form">
                        <input type="hidden" name="template_slug" id="template_slug">
                        <div class="form-field">
                            <label for="template_name"><?php esc_html_e('Template Name', 'saxon'); ?></label>
                            <input type="text" name="template_name" id="template_name" required>
                        </div>
                        <div class="form-field">
                            <label for="template_description"><?php esc_html_e('Description', 'saxon'); ?></label>
                            <textarea name="template_description" id="template_description"></textarea>
                        </div>
                        <div class="form-field">
                            <label for="template_subject"><?php esc_html_e('Email Subject', 'saxon'); ?></label>
                            <input type="text" name="template_subject" id="template_subject" required>
                        </div>
                        <div class="form-field">
                            <label for="template_content"><?php esc_html_e('Template Content', 'saxon'); ?></label>
                            <?php 
                            wp_editor('', 'template_content', [
                                'media_buttons' => false,
                                'textarea_rows' => 20,
                                'textarea_name' => 'template_content',
                            ]);
                            ?>
                        </div>
                        <div class="form-field">
                            <h3><?php esc_html_e('Available Variables', 'saxon'); ?></h3>
                            <ul class="variables-list">
                                <li><code>{{blog_name}}</code> - <?php esc_html_e('Site name', 'saxon'); ?></li>
                                <li><code>{{blog_url}}</code> - <?php esc_html_e('Site URL', 'saxon'); ?></li>
                                <li><code>{{subscriber_name}}</code> - <?php esc_html_e('Subscriber\'s name', 'saxon'); ?></li>
                                <li><code>{{unsubscribe_url}}</code> - <?php esc_html_e('Unsubscribe link', 'saxon'); ?></li>
                                <li><code>{{latest_posts}}</code> - <?php esc_html_e('Latest posts list', 'saxon'); ?></li>
                                <li><code>{{popular_posts}}</code> - <?php esc_html_e('Popular posts list', 'saxon'); ?></li>
                                <li><code>{{category_highlights}}</code> - <?php esc_html_e('Category highlights', 'saxon'); ?></li>
                            </ul>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="button button-primary"><?php esc_html_e('Save Template', 'saxon'); ?></button>
                            <button type="button" class="button preview-template"><?php esc_html_e('Preview', 'saxon'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Modal -->
        <div id="preview-modal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2><?php esc_html_e('Template Preview', 'saxon'); ?></h2>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="preview-frame"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Template Card Styles */
        .template-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .template-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
        }

        .template-header h2 {
            margin: 0 0 10px;
        }

        .template-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 100000;
        }

        .modal-content {
            position: relative;
            background: #fff;
            margin: 50px auto;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 4px;
        }

        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 20px;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        /* Form Styles */
        .form-field {
            margin-bottom: 20px;
        }

        .form-field label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .variables-list {
            background: #f9f9f9;
            padding: 15px 20px;
            border-radius: 4px;
        }

        .variables-list code {
            background: #eee;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>
    <?php
}