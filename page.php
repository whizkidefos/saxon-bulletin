<?php get_header(); ?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-4xl mx-auto">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden'); ?>>
                <?php if (has_post_thumbnail()): ?>
                    <div class="aspect-w-16 aspect-h-9">
                        <?php the_post_thumbnail('full', [
                            'class' => 'w-full h-full object-cover'
                        ]); ?>
                    </div>
                <?php endif; ?>

                <div class="p-6 sm:p-8">
                    <header class="mb-8">
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                            <?php the_title(); ?>
                        </h1>
                        <?php if (get_edit_post_link()): ?>
                            <div class="mt-4">
                                <?php edit_post_link(
                                    __('Edit this page', 'saxon'),
                                    '<span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition-colors">',
                                    '</span>'
                                ); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <div class="prose prose-lg dark:prose-invert max-w-none">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    wp_link_pages([
                        'before' => '<div class="page-links mt-8 py-4 border-t border-gray-200 dark:border-gray-700">' . esc_html__('Pages:', 'saxon'),
                        'after'  => '</div>',
                        'link_before' => '<span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700">',
                        'link_after'  => '</span>',
                    ]);
                    ?>
                </div>

                <?php if (comments_open() || get_comments_number()): ?>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="p-6 sm:p-8">
                            <?php comments_template(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
