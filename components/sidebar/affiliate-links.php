<?php
/**
 * Affiliate Links Component
 * 
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type string $title    Section title
 *     @type string $category Filter by specific category slug
 *     @type int    $count    Number of links to display
 * }
 */

$args = wp_parse_args($args, [
    'title' => __('Recommended', 'saxon'),
    'category' => '',
    'count' => 3
]);

// Query affiliate links
$query_args = [
    'post_type' => 'affiliate_link',
    'posts_per_page' => $args['count'],
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'post_status' => 'publish'
];

if (!empty($args['category'])) {
    $query_args['tax_query'] = [
        [
            'taxonomy' => 'affiliate_category',
            'field' => 'slug',
            'terms' => $args['category']
        ]
    ];
}

$affiliate_links = new WP_Query($query_args);

if (!$affiliate_links->have_posts()) {
    return;
}
?>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
            <?php echo esc_html($args['title']); ?>
            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400"><?php esc_html_e('Sponsored', 'saxon'); ?></span>
        </h3>

        <div class="space-y-6">
            <?php while ($affiliate_links->have_posts()): $affiliate_links->the_post(); 
                $url = get_post_meta(get_the_ID(), '_affiliate_url', true);
                $price = get_post_meta(get_the_ID(), '_affiliate_price', true);
                $discount = get_post_meta(get_the_ID(), '_affiliate_discount', true);
                $description = get_post_meta(get_the_ID(), '_affiliate_description', true);
            ?>
                <a href="<?php echo esc_url($url); ?>" 
                   data-affiliate-id="<?php echo get_the_ID(); ?>"
                   class="affiliate-link group block transition-transform hover:-translate-y-1 duration-200"
                   target="_blank" 
                   rel="nofollow sponsored">
                    <div class="relative rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700">
                        <?php if (has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('medium', [
                                'class' => 'w-full h-40 object-cover'
                            ]); ?>
                        <?php else: ?>
                            <div class="w-full h-40 flex items-center justify-center text-gray-400 dark:text-gray-500">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($discount): ?>
                            <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                <?php echo esc_html($discount); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">
                            <?php the_title(); ?>
                        </h4>
                        <?php if ($description): ?>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                <?php echo esc_html($description); ?>
                            </p>
                        <?php endif; ?>
                        <div class="mt-2 flex items-center justify-between">
                            <?php if ($price): ?>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    <?php echo esc_html($price); ?>
                                </span>
                            <?php endif; ?>
                            <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                <?php esc_html_e('Learn More →', 'saxon'); ?>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</div>

<?php if (!wp_style_is('affiliate-links-styles')): ?>
<style id="affiliate-links-styles">
.affiliate-link {
    transition: transform 0.2s ease;
}

.affiliate-link:hover {
    transform: translateY(-2px);
}
</style>
<?php endif; ?>
