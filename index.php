<?php get_header(); ?>

<main class="site-main">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if (have_posts()) : ?>
                    <div class="post-grid row">
                        <?php while (have_posts()) : the_post(); ?>
                            <div class="col-md-6 mb-4">
                                <article <?php post_class('card h-100'); ?>>
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="card-img-top">
                                            <?php the_post_thumbnail('medium_large', ['class' => 'img-fluid']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <h2 class="card-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="card-meta mb-3">
                                            <span class="post-date">
                                                <i class="far fa-calendar-alt"></i>
                                                <?php echo get_the_date(); ?>
                                            </span>
                                            <span class="post-author ms-3">
                                                <i class="far fa-user"></i>
                                                <?php the_author_posts_link(); ?>
                                            </span>
                                        </div>
                                        
                                        <div class="card-text">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-transparent">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
                                            Read More
                                        </a>
                                        <?php if (has_category()) : ?>
                                            <div class="post-categories float-end">
                                                <?php the_category(', '); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="pagination-wrapper my-4">
                        <?php
                        echo paginate_links(array(
                            'prev_text' => '&laquo; Previous',
                            'next_text' => 'Next &raquo;',
                            'type' => 'list',
                            'class' => 'pagination'
                        ));
                        ?>
                    </div>
                    
                <?php else : ?>
                    <div class="no-posts">
                        <h2>No posts found</h2>
                        <p>Try searching for something else or browse our categories.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>