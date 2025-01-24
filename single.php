<?php get_header(); ?>

<main class="site-main single-post">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-img-top post-thumbnail">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <header class="entry-header mb-4">
                                <h1 class="entry-title"><?php the_title(); ?></h1>
                                
                                <div class="entry-meta">
                                    <span class="posted-on">
                                        <i class="far fa-calendar-alt"></i>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="byline ms-3">
                                        <i class="far fa-user"></i>
                                        <?php the_author_posts_link(); ?>
                                    </span>
                                    <?php if (has_category()) : ?>
                                        <span class="categories ms-3">
                                            <i class="far fa-folder-open"></i>
                                            <?php the_category(', '); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </header>

                            <div class="entry-content">
                                <?php 
                                the_content();
                                
                                wp_link_pages(array(
                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'saxon'),
                                    'after'  => '</div>',
                                ));
                                ?>
                            </div>

                            <?php if (has_tag()) : ?>
                                <footer class="entry-footer mt-4">
                                    <div class="tags-links">
                                        <i class="fas fa-tags"></i>
                                        <?php the_tags('', ', '); ?>
                                    </div>
                                </footer>
                            <?php endif; ?>
                        </div>
                    </article>

                    <?php 
                    // Author bio
                    get_template_part('template-parts/biography');
                    
                    // Related posts
                    get_template_part('template-parts/related-posts');
                    
                    // Comments
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>

                <?php endwhile; ?>
            </div>
            
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>