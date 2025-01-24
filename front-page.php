<?php get_header(); ?>

<main class="site-main home-page">
    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 mb-4"><?php echo get_theme_mod('hero_title', 'Welcome to Saxon Bulletin'); ?></h1>
                    <p class="lead mb-4"><?php echo get_theme_mod('hero_subtitle', 'Your source for insightful articles and thoughtful discussions.'); ?></p>
                    <a href="<?php echo get_theme_mod('hero_button_url', '/about'); ?>" class="btn btn-light btn-lg">
                        <?php echo get_theme_mod('hero_button_text', 'Learn More'); ?>
                    </a>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <?php if (get_theme_mod('hero_image')): ?>
                        <img src="<?php echo esc_url(get_theme_mod('hero_image')); ?>" alt="Hero Image" class="img-fluid rounded-3 shadow">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Posts -->
    <section class="featured-posts py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Featured Stories</h2>
            <div class="row">
                <?php
                $featured_query = new WP_Query(array(
                    'posts_per_page' => 3,
                    'meta_key' => 'featured_post',
                    'meta_value' => 'yes'
                ));

                if ($featured_query->have_posts()) :
                    while ($featured_query->have_posts()) : $featured_query->the_post();
                ?>
                    <div class="col-md-4 mb-4">
                        <article <?php post_class('card h-100 hover-effect'); ?>>
                            <?php if (has_post_thumbnail()): ?>
                                <div class="card-img-container">
                                    <?php the_post_thumbnail('large', array('class' => 'card-img-top')); ?>
                                    <div class="card-img-overlay d-flex align-items-end">
                                        <div class="category-badge">
                                            <?php the_category(' '); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h3 class="card-title h4">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="card-text mb-3"><?php the_excerpt(); ?></div>
                                <div class="post-meta small text-muted">
                                    <span class="post-date">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="post-author ms-3">
                                        <i class="far fa-user me-1"></i>
                                        <?php the_author(); ?>
                                    </span>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Latest Posts Grid -->
    <section class="latest-posts py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h2 class="section-title mb-4">Latest Articles</h2>
                    <div class="row">
                        <?php
                        $latest_query = new WP_Query(array(
                            'posts_per_page' => 6,
                            'ignore_sticky_posts' => true
                        ));

                        if ($latest_query->have_posts()) :
                            while ($latest_query->have_posts()) : $latest_query->the_post();
                        ?>
                            <div class="col-md-6 mb-4">
                                <article <?php post_class('card h-100 hover-effect'); ?>>
                                    <?php if (has_post_thumbnail()): ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium_large', array('class' => 'card-img-top')); ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h3 class="card-title h5">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        <div class="card-text small mb-3"><?php the_excerpt(); ?></div>
                                        <div class="post-meta small text-muted">
                                            <?php echo get_the_date(); ?> · <?php comments_number('0', '1', '%'); ?> Comments
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                    <div class="text-center mt-4">
                        <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="btn btn-primary">
                            View All Articles
                        </a>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <?php get_sidebar('home'); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h2 class="section-title mb-4">Stay Updated</h2>
                    <p class="lead mb-4">Subscribe to our newsletter for the latest updates and exclusive content.</p>
                    <form class="newsletter-form" action="#" method="post">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Enter your email" required>
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>