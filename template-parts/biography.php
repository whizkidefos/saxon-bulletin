<div class="author-bio card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-auto">
                <?php echo get_avatar(get_the_author_meta('ID'), 96, '', '', array('class' => 'rounded-circle')); ?>
            </div>
            <div class="col">
                <h3 class="author-title">
                    <?php echo sprintf(__('About %s', 'saxon'), get_the_author()); ?>
                </h3>
                <?php if (get_the_author_meta('description')) : ?>
                    <div class="author-description">
                        <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                    </div>
                <?php endif; ?>
                <div class="author-links mt-3">
                    <?php
                    $author_website = get_the_author_meta('url');
                    if ($author_website) : ?>
                        <a href="<?php echo esc_url($author_website); ?>" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-globe"></i> Website
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="btn btn-outline-secondary btn-sm">
                        View all posts
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>