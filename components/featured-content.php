<article <?php post_class('featured-post'); ?>>
    <div class="featured-post-inner">
        <?php if (has_post_thumbnail()): ?>
            <div class="featured-thumbnail">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>

        <div class="featured-content">
            <?php
            $categories = get_the_category();
            if ($categories): ?>
                <div class="post-categories">
                    <?php
                    foreach ($categories as $category) {
                        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>
            
            <div class="entry-meta">
                <?php
                saxon_posted_on();
                saxon_posted_by();
                ?>
            </div>
            
            <div class="entry-excerpt">
                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
            </div>
        </div>
    </div>
</article>