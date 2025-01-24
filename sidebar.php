<aside class="site-sidebar">
    <!-- Search Widget -->
    <div class="widget widget-search card mb-4">
        <div class="card-body">
            <form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Search..." value="<?php echo get_search_query(); ?>" name="s">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- About Widget -->
    <div class="widget widget-about card mb-4">
        <div class="card-body text-center">
            <?php 
            $about_image = get_theme_mod('sidebar_about_image');
            if ($about_image): ?>
                <img src="<?php echo esc_url($about_image); ?>" alt="About Us" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
            <?php endif; ?>
            <h3 class="widget-title h5 mb-3"><?php echo get_theme_mod('sidebar_about_title', 'About Us'); ?></h3>
            <p class="mb-3"><?php echo get_theme_mod('sidebar_about_text', 'Welcome to Saxon Bulletin, your source for insightful content.'); ?></p>
            <a href="<?php echo get_theme_mod('sidebar_about_link', '/about'); ?>" class="btn btn-outline-primary btn-sm">Read More</a>
        </div>
    </div>

    <!-- Categories Widget -->
    <div class="widget widget-categories card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="widget-title h5 mb-0">Categories</h3>
        </div>
        <div class="card-body">
            <ul class="categories-list list-unstyled mb-0">
                <?php
                $categories = get_categories(array(
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 6
                ));

                foreach($categories as $category) :
                ?>
                    <li class="category-item d-flex justify-content-between align-items-center mb-2">
                        <a href="<?php echo get_category_link($category->term_id); ?>">
                            <?php echo $category->name; ?>
                        </a>
                        <span class="badge bg-primary rounded-pill">
                            <?php echo $category->count; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Popular Posts Widget -->
    <div class="widget widget-popular-posts card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="widget-title h5 mb-0">Popular Posts</h3>
        </div>
        <div class="card-body">
            <?php
            $popular_posts = new WP_Query(array(
                'posts_per_page' => 4,
                'meta_key' => 'post_views_count',
                'orderby' => 'meta_value_num',
                'order' => 'DESC'
            ));

            if ($popular_posts->have_posts()) :
                while ($popular_posts->have_posts()) : $popular_posts->the_post();
            ?>
                <div class="popular-post mb-3">
                    <div class="row g-0">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="col-3">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid rounded')); ?>
                                </a>
                            </div>
                            <div class="col-9 ps-3">
                        <?php else : ?>
                            <div class="col-12">
                        <?php endif; ?>
                                <h4 class="post-title h6 mb-1">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>
                                <div class="post-meta small text-muted">
                                    <?php echo get_the_date(); ?>
                                </div>
                            </div>
                    </div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>

    <!-- Tags Widget -->
    <div class="widget widget-tags card">
        <div class="card-header bg-primary text-white">
            <h3 class="widget-title h5 mb-0">Popular Tags</h3>
        </div>
        <div class="card-body">
            <div class="tags-cloud">
                <?php
                $tags = get_tags(array(
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 15
                ));

                if ($tags) :
                    foreach ($tags as $tag) :
                ?>
                    <a href="<?php echo get_tag_link($tag->term_id); ?>" class="tag-link btn btn-outline-secondary btn-sm mb-2">
                        <?php echo $tag->name; ?>
                    </a>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</aside>