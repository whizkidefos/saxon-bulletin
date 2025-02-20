<?php
/**
 * Custom Login Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class Saxon_Login_Customizer {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Change login page logo URL
        add_filter('login_headerurl', [$this, 'login_logo_url']);
        
        // Change login page logo title
        add_filter('login_headertext', [$this, 'login_logo_title']);
        
        // Enqueue custom login styles
        add_action('login_enqueue_scripts', [$this, 'enqueue_login_styles']);
        
        // Add custom background image option to customizer
        add_action('customize_register', [$this, 'customize_login_page']);
    }

    /**
     * Change logo URL to site home
     */
    public function login_logo_url() {
        return home_url();
    }

    /**
     * Change logo title to site name
     */
    public function login_logo_title() {
        return get_bloginfo('name');
    }

    /**
     * Enqueue custom login styles
     */
    public function enqueue_login_styles() {
        wp_enqueue_style(
            'saxon-login',
            get_template_directory_uri() . '/assets/css/login.css',
            [],
            SAXON_VERSION
        );

        // Add inline styles for custom background and logo
        $bg_image = get_theme_mod('login_background_image');
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo_image = $custom_logo_id ? wp_get_attachment_image_url($custom_logo_id, 'full') : '';

        $custom_css = '';

        if ($bg_image) {
            $custom_css .= "
                body.login {
                    background-image: url('{$bg_image}');
                }
            ";
        }

        if ($logo_image) {
            $custom_css .= "
                .login h1 a {
                    background-image: url('{$logo_image}') !important;
                }
            ";
        }

        if ($custom_css) {
            wp_add_inline_style('saxon-login', $custom_css);
        }
    }

    /**
     * Add login customizer options
     */
    public function customize_login_page($wp_customize) {
        // Add login customization section
        $wp_customize->add_section('saxon_login', [
            'title' => __('Login Page', 'saxon'),
            'priority' => 200,
        ]);

        // Add background image setting
        $wp_customize->add_setting('login_background_image', [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'login_background_image', [
            'label' => __('Login Background Image', 'saxon'),
            'section' => 'saxon_login',
            'settings' => 'login_background_image',
        ]));
    }
}

// Initialize the login customizer
Saxon_Login_Customizer::get_instance();