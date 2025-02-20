<?php
/**
 * Post Card Component with Animations
 */

$args = wp_parse_args($args, [
    'view' => 'list',
    'meta' => false
]);

$card_classes = [
    'post-card',
    'reveal', // For intersection observer
    'bg-white',
    'dark:bg-gray-800',
    'rounded-lg',
    'shadow-sm',
    'overflow-hidden'
];

if ($args['view'] === 'list') {
    $card_classes[] = 'flex flex-col md:flex-row';
}
?>

<article <?php post_class(implode(' ', $card_classes)); ?>>
    <?php if (has_post_thumbnail()): ?>
        <a href="<?php the_permalink(); ?>" 
           class="post-thumbnail <?php echo $args['view'] === 'list' ? 'md:w-1/3' : 'block aspect-w-16 aspect-h-9'; ?> overflow-hidden">
            <?php the_post_thumbnail('large', [
                'class' => 'w-full h-full object-cover',
            ]); ?>
        </a>
    <?php endif; ?>

    <div class="<?php echo $args['view'] === 'list' ? 'md:w-2/3' : ''; ?> p-6">
        <?php
        $categories = get_the_category();
        if ($categories): ?>
            <div class="flex flex-wrap gap-2 mb-3 animate-fade-in">
                <?php foreach ($categories as $category): ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                       class="category-link text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 px-2.5 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800">
                        <?php echo esc_html($category->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h2 class="entry-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
            <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 dark:hover:text-blue-400">
                <?php the_title(); ?>
            </a>
        </h2>

        <div class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
            <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
        </div>

        <?php if ($args['meta']): ?>
            <div class="flex items-center text-sm">
                <?php if ($avatar = get_avatar(get_the_author_meta('ID'), 32)): ?>
                    <div class="flex-shrink-0 mr-3">
                        <?php echo str_replace('avatar', 'rounded-full transition-transform hover:scale-110', $avatar); ?>
                    </div>
                <?php endif; ?>
                
                <div class="text-gray-500 dark:text-gray-400">
                    <?php echo get_the_date(); ?>
                    <?php if (comments_open()): ?>
                        <span class="mx-2">•</span>
                        <a href="<?php comments_link(); ?>" class="hover:text-blue-600 dark:hover:text-blue-400">
                            <?php comments_number('0 comments', '1 comment', '% comments'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</article>

<?php
// Add Intersection Observer script if it hasn't been added yet
if (!wp_script_is('saxon-post-animations', 'enqueued')): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.post-card.reveal');
        
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });

            cards.forEach(card => observer.observe(card));
        } else {
            // Fallback for browsers that don't support Intersection Observer
            cards.forEach(card => card.classList.add('visible'));
        }
    });
    </script>
    <?php wp_enqueue_script('saxon-post-animations'); ?>
<?php endif; ?>