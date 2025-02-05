<?php
/**
 * The header for our theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="theme-light theme-transition" data-theme="light">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <?php wp_head(); ?>
</head>

<body <?php body_class('bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white theme-transition'); ?>>
<?php wp_body_open(); ?>

<header class="sticky top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 theme-transition">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php get_template_part('components/header/navigation'); ?>
    </div>
</header>

<main id="content" class="site-content">