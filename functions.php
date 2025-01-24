<?php
if (!defined('ABSPATH')) exit;

function saxon_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'saxon'),
        'footer' => __('Footer Menu', 'saxon')
    ));
}
add_action('after_setup_theme', 'saxon_theme_setup');

function saxon_enqueue_scripts() {
    // Enqueue styles
    wp_enqueue_style('saxon-style', get_stylesheet_uri());
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
    
    // Enqueue scripts
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', array('jquery'), '', true);
    wp_enqueue_script('saxon-theme', get_template_directory_uri() . '/js/theme.js', array('jquery'), '', true);
    
    // Localize script for theme mode
    wp_localize_script('saxon-theme', 'saxonTheme', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'saxon_enqueue_scripts');

function saxon_register_widgets() {
    register_widget('Saxon_Social_Media_Widget');
}
add_action('widgets_init', 'saxon_register_widgets');