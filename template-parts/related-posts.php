<?php
$categories = get_the_category();
if ($categories) {
    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    $related_query = new WP_Query(array(
        'category__in' => $category_ids,
        'post__not_in' => array(get_the_ID()),
        'posts_per_page' => 3,
        'orderby' => 'rand'
    ));
    
    if ($related_query->have_posts()) : ?>
        <div class="related-posts card mb-4">
            <div class="card-header">
                <h3 class="card-title h5 mb-0">Related Posts</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                        <div class="col-md-4">
                            <article class="related-post">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="d-block mb-2">
                                        <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                    </a>
                                <?php endif; ?>
                                <h4 class="h6">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <div class="post-meta small">
                                    <?php echo get_the_date(); ?>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    <?php endif;
    wp_reset_postdata();
}
?>