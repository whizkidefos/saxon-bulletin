<?php
/**
 * Post Card Component
 */
?>

<article <?php post_class('post-card bg-white dark:bg-gray-800 rounded-lg shadow-sm transition duration-300 hover:shadow-lg'); ?> 
         data-date="<?php echo get_the_date('c'); ?>"
         data-comments="<?php echo get_comments_number(); ?>">
    <?php if (has_post_thumbnail()): ?>
        <a href="<?php the_permalink(); ?>" class="block aspect-w-16 aspect-h-9 overflow-hidden rounded-t-lg group">
            <?php the_post_thumbnail('large', [
                'class' => 'w-full h-full object-cover transition duration-700 ease-out group-hover:scale-105',
            ]); ?>
        </a>
    <?php endif; ?>

    <div class="p-6">
        <!-- Categories -->
        <?php
        $categories = get_the_category();
        if ($categories): ?>
            <div class="flex flex-wrap gap-2 mb-3">
                <?php foreach ($categories as $category): ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                       class="text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 px-2.5 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Title -->
        <h2 class="post-title text-xl font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2 group">
            <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <?php the_title(); ?>
            </a>
        </h2>

        <!-- Excerpt -->
        <div class="post-excerpt text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
            <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
        </div>

        <!-- Post Meta -->
        <div class="flex items-center justify-between text-sm">
            <div class="flex items-center space-x-4">
                <?php if ($avatar = get_avatar(get_the_author_meta('ID'), 32)): ?>
                    <div class="flex-shrink-0">
                        <?php echo str_replace('avatar', 'rounded-full', $avatar); ?>
                    </div>
                <?php endif; ?>
                
                <div>
                    <span class="font-medium text-gray-900 dark:text-white">
                        <?php the_author(); ?>
                    </span>
                    <div class="text-gray-500 dark:text-gray-400 text-sm">
                        <time datetime="<?php echo get_the_date('c'); ?>">
                            <?php echo get_the_date(); ?>
                        </time>
                        <span class="mx-1">·</span>
                        <span><?php echo saxon_reading_time(); ?></span>
                    </div>
                </div>
            </div>

            <?php if (comments_open()): ?>
                <a href="<?php comments_link(); ?>" class="flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <?php comments_number('0', '1', '%'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article>