<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="site-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <?php the_custom_logo(); ?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#primaryMenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="primaryMenu">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'navbar-nav ms-auto',
                        'fallback_cb' => '__return_false',
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth' => 2,
                        'walker' => new bootstrap_5_wp_nav_menu_walker()
                    ));
                    ?>
                    <div class="theme-mode-toggle ms-3">
                        <button class="btn btn-outline-primary" id="themeToggle">
                            <i class="fas fa-moon dark-icon"></i>
                            <i class="fas fa-sun light-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </header>