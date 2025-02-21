<?php
/**
 * Mini Newsletter CTA Component
 * 
 * A compact version of the newsletter CTA for sidebars and footers
 * 
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type string $style        Style variant ('sidebar', 'footer')
 *     @type string $heading      Custom heading text
 *     @type string $text         Custom description text
 *     @type string $button_text  Custom button text
 *     @type string $bg_class     Background class override
 * }
 */

$args = wp_parse_args($args, [
    'style' => 'sidebar',
    'heading' => __('Newsletter', 'saxon'),
    'text' => __('Get the latest updates', 'saxon'),
    'button_text' => __('Subscribe', 'saxon'),
    'bg_class' => 'bg-white dark:bg-gray-800'
]);

// Get newsletter status from URL
$newsletter_status = isset($_GET['newsletter']) ? $_GET['newsletter'] : '';
$status_message = '';
$status_type = '';

switch ($newsletter_status) {
    case 'success':
        $status_message = __('Thanks for subscribing!', 'saxon');
        $status_type = 'success';
        break;
    case 'invalid_email':
        $status_message = __('Invalid email', 'saxon');
        $status_type = 'error';
        break;
    case 'already_subscribed':
        $status_message = __('Already subscribed', 'saxon');
        $status_type = 'info';
        break;
    case 'error':
        $status_message = __('Error occurred', 'saxon');
        $status_type = 'error';
        break;
}

$container_class = $args['style'] === 'footer' 
    ? 'border-t border-gray-200 dark:border-gray-700' 
    : 'rounded-xl shadow-sm';
?>

<div class="newsletter-mini <?php echo esc_attr($args['bg_class'] . ' ' . $container_class); ?>">
    <div class="p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                <?php echo esc_html($args['heading']); ?>
            </h3>
            <!-- Decorative envelope icon -->
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <?php if ($status_message): ?>
            <div class="mb-3 text-sm <?php echo $status_type === 'success' ? 'text-green-600' : ($status_type === 'error' ? 'text-red-600' : 'text-blue-600'); ?>">
                <?php echo esc_html($status_message); ?>
            </div>
        <?php else: ?>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                <?php echo esc_html($args['text']); ?>
            </p>
        <?php endif; ?>

        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" 
              method="post" 
              class="flex flex-col gap-2">
            <input type="hidden" name="action" value="saxon_newsletter_subscribe">
            <?php wp_nonce_field('saxon_newsletter_subscription', 'saxon_newsletter_nonce'); ?>
            
            <input type="email" 
                   name="email"
                   required
                   placeholder="<?php esc_attr_e('Your email', 'saxon'); ?>" 
                   class="w-full px-3 py-1.5 text-sm rounded-md text-gray-900 placeholder-gray-500 bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            
            <button type="submit" 
                    class="w-full px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <?php echo esc_html($args['button_text']); ?>
            </button>
        </form>
    </div>
</div>

<?php if (!wp_style_is('newsletter-mini-styles')): ?>
<style id="newsletter-mini-styles">
.newsletter-mini input:focus {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
}

.newsletter-mini button {
    transition: transform 0.2s ease;
}

.newsletter-mini button:hover {
    transform: translateY(-1px);
}

.newsletter-mini button:active {
    transform: translateY(0);
}
</style>
<?php endif; ?>
