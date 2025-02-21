<?php
/**
 * Affiliate Links Component
 * 
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type string $title    Section title
 *     @type array  $links    Array of affiliate links
 * }
 */

$args = wp_parse_args($args, [
    'title' => __('Recommended', 'saxon'),
    'links' => []
]);

// If no links provided, use default demo links
if (empty($args['links'])) {
    $args['links'] = [
        [
            'title' => 'Start Your Blog',
            'description' => 'Get started with Bluehost hosting',
            'image' => 'https://via.placeholder.com/300x200',
            'url' => '#',
            'price' => '$2.95/mo',
            'discount' => '60% off'
        ]
    ];
}
?>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
            <?php echo esc_html($args['title']); ?>
            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400"><?php esc_html_e('Sponsored', 'saxon'); ?></span>
        </h3>

        <div class="space-y-6">
            <?php foreach ($args['links'] as $link): ?>
                <a href="<?php echo esc_url($link['url']); ?>" 
                   class="group block transition-transform hover:-translate-y-1 duration-200"
                   target="_blank" 
                   rel="nofollow sponsored">
                    <div class="relative rounded-xl overflow-hidden">
                        <?php if (!empty($link['image'])): ?>
                            <img src="<?php echo esc_url($link['image']); ?>" 
                                 alt="<?php echo esc_attr($link['title']); ?>"
                                 class="w-full h-40 object-cover">
                        <?php endif; ?>
                        
                        <?php if (!empty($link['discount'])): ?>
                            <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                <?php echo esc_html($link['discount']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4">
                        <h4 class="text-base font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">
                            <?php echo esc_html($link['title']); ?>
                        </h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            <?php echo esc_html($link['description']); ?>
                        </p>
                        <?php if (!empty($link['price'])): ?>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    <?php echo esc_html($link['price']); ?>
                                </span>
                                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                    <?php esc_html_e('Learn More →', 'saxon'); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
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
