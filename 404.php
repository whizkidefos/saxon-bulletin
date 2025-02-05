<?php get_header(); ?>

<div class="container">
    <main class="site-main error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'saxon'); ?></h1>
        </header>

        <div class="page-content">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'saxon'); ?></p>

            <?php get_search_form(); ?>

            <div class="widget-area">
                <div class="widget">
                    <h2 class="widget-title"><?php esc_html_e('Recent Posts', 'saxon'); ?></h2>
                    <ul>
                        <?php
                        wp_get_archives(array(
                            'type' => 'postbypost',
                            'limit' => 5,
                        ));
                        ?>
                    </ul>
                </div>

                <div class="widget">
                    <h2 class="widget-title"><?php esc_html_e('Most Used Categories', 'saxon'); ?></h2>
                    <ul>
                        <?php
                        wp_list_categories(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'show_count' => 1,
                            'title_li' => '',
                            'number' => 5,
                        ));
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>

<?php get_footer(); ?>