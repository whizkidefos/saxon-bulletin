<?php
/**
 * Single Post Template
 */

get_header(); ?>

<article <?php post_class('bg-white dark:bg-gray-900'); ?>>
    <?php if (has_post_thumbnail()): ?>
        <div class="relative h-[60vh] overflow-hidden bg-gray-900">
            <?php the_post_thumbnail('full', [
                'class' => 'w-full h-full object-cover opacity-75',
            ]); ?>
            <!-- Strong gradient overlay for better contrast -->
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/50 to-transparent"></div>
            
            <!-- Post meta container -->
            <div class="absolute bottom-0 left-0 right-0">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
                    <?php
                    $categories = get_the_category();
                    if ($categories): ?>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <?php foreach ($categories as $category): ?>
                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                   class="text-sm font-medium px-4 py-1.5 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                        <?php the_title(); ?>
                    </h1>

                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-4">
                            <?php if ($avatar = get_avatar(get_the_author_meta('ID'), 48)): ?>
                                <div class="flex-shrink-0">
                                    <?php echo str_replace('avatar', 'rounded-full ring-2 ring-white/20', $avatar); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div>
                                <div class="font-medium text-white">
                                    <?php the_author_posts_link(); ?>
                                </div>
                                <div class="text-sm text-gray-300">
                                    <?php echo get_the_date(); ?> · <?php echo saxon_reading_time(); ?> min read
                                </div>
                            </div>
                        </div>

                        <?php if (comments_open() || get_comments_number()): ?>
                            <div class="flex items-center text-gray-300">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <?php comments_number('0 comments', '1 comment', '% comments'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- No featured image layout -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <?php
            $categories = get_the_category();
            if ($categories): ?>
                <div class="flex flex-wrap gap-2 mb-6">
                    <?php foreach ($categories as $category): ?>
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                           class="text-sm font-medium px-4 py-1.5 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                            <?php echo esc_html($category->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                <?php the_title(); ?>
            </h1>

            <div class="flex items-center space-x-6 border-b border-gray-200 dark:border-gray-700 pb-6 mb-8">
                <div class="flex items-center space-x-4">
                    <?php if ($avatar = get_avatar(get_the_author_meta('ID'), 48)): ?>
                        <div class="flex-shrink-0">
                            <?php echo str_replace('avatar', 'rounded-full', $avatar); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">
                            <?php the_author_posts_link(); ?>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <?php echo get_the_date(); ?> · <?php echo saxon_reading_time(); ?> min read
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Post Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="prose prose-lg dark:prose-invert mx-auto">
            <?php 
            the_content();
            
            wp_link_pages([
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'saxon'),
                'after'  => '</div>',
            ]);
            ?>
        </div>

        <?php get_template_part('components/post/social-share'); ?>

        <!-- Tags -->
        <?php
        $tags = get_the_tags();
        if ($tags): ?>
            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($tags as $tag): ?>
                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                           class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                            #<?php echo esc_html($tag->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Author Bio -->
        <?php if (get_the_author_meta('description')): ?>
            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                    <div class="flex items-center space-x-4">
                        <?php if ($avatar = get_avatar(get_the_author_meta('ID'), 64)): ?>
                            <div class="flex-shrink-0">
                                <?php echo str_replace('avatar', 'rounded-full', $avatar); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                <?php echo sprintf(__('About %s', 'saxon'), get_the_author()); ?>
                            </h3>
                            <div class="mt-2 text-gray-600 dark:text-gray-300">
                                <?php echo wpautop(get_the_author_meta('description')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Comments -->
        <?php 
        if (comments_open() || get_comments_number()):
            comments_template();
        endif;
        ?>
    </div>
</article>

<?php get_footer(); ?>