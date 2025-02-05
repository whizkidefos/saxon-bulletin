<article <?php post_class(); ?>>
    <header class="entry-header">
        <?php if (has_post_thumbnail()): ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>

        <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
        
        <div class="entry-meta">
            <?php
            saxon_posted_on();
            saxon_posted_by();
            ?>
        </div>
    </header>

    <div class="entry-content">
        <?php the_excerpt(); ?>
    </div>
</article>
