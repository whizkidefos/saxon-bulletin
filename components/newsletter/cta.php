<?php
/**
 * Newsletter CTA Component
 * 
 * @param array $args {
 *     Optional. Array of arguments.
 *     @type string $style     Style variant ('default', 'compact', 'full-width')
 *     @type string $heading   Custom heading text
 *     @type string $text      Custom description text
 *     @type string $bg_class  Background class override
 * }
 */

$args = wp_parse_args($args, [
    'style' => 'default',
    'heading' => __('Stay in the Loop', 'saxon'),
    'text' => __('Subscribe to our newsletter and never miss our latest stories and updates.', 'saxon'),
    'bg_class' => 'bg-gray-50 dark:bg-gray-800'
]);

$container_class = 'newsletter-section py-16 ' . esc_attr($args['bg_class']);
if ($args['style'] === 'compact') {
    $container_class = 'newsletter-section py-8 ' . esc_attr($args['bg_class']);
}

// Get newsletter status from URL
$newsletter_status = isset($_GET['newsletter']) ? $_GET['newsletter'] : '';
$status_message = '';
$status_type = '';

switch ($newsletter_status) {
    case 'success':
        $status_message = __('Thank you for subscribing! Please check your email for confirmation.', 'saxon');
        $status_type = 'success';
        break;
    case 'invalid_email':
        $status_message = __('Please enter a valid email address.', 'saxon');
        $status_type = 'error';
        break;
    case 'already_subscribed':
        $status_message = __('This email is already subscribed to our newsletter.', 'saxon');
        $status_type = 'info';
        break;
    case 'error':
        $status_message = __('An error occurred. Please try again later.', 'saxon');
        $status_type = 'error';
        break;
}
?>

<section class="<?php echo $container_class; ?>">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 p-8 md:p-12 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <defs>
                        <pattern id="newsletter-dots" width="10" height="10" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1" fill="currentColor"/>
                        </pattern>
                    </defs>
                    <rect width="100" height="100" fill="url(#newsletter-dots)"/>
                </svg>
            </div>

            <div class="relative md:w-2/3">
                <h2 class="text-3xl font-bold text-white mb-4">
                    <?php echo esc_html($args['heading']); ?>
                </h2>
                <p class="text-xl text-blue-100 mb-8">
                    <?php echo esc_html($args['text']); ?>
                </p>

                <?php if ($status_message): ?>
                    <div class="mb-6 rounded-lg p-4 <?php echo $status_type === 'success' ? 'bg-green-100 text-green-800' : ($status_type === 'error' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'); ?>">
                        <p class="text-sm font-medium"><?php echo esc_html($status_message); ?></p>
                    </div>
                <?php endif; ?>

                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" 
                      method="post" 
                      class="flex flex-col sm:flex-row gap-4">
                    <input type="hidden" name="action" value="saxon_newsletter_subscribe">
                    <?php wp_nonce_field('saxon_newsletter_subscription', 'saxon_newsletter_nonce'); ?>
                    
                    <input type="email" 
                           name="email"
                           required
                           placeholder="<?php esc_attr_e('Enter your email', 'saxon'); ?>" 
                           class="flex-1 px-4 py-3 rounded-lg text-gray-900 placeholder-gray-500 bg-white border-transparent focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    
                    <button type="submit" 
                            class="px-6 py-3 bg-white text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <?php esc_html_e('Subscribe', 'saxon'); ?>
                    </button>
                </form>

                <?php if ($args['style'] !== 'compact'): ?>
                <p class="mt-4 text-sm text-blue-100 opacity-80">
                    <?php esc_html_e('By subscribing, you agree to our Privacy Policy and consent to receive updates from our newsletter.', 'saxon'); ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!wp_style_is('newsletter-cta-styles')): ?>
<style id="newsletter-cta-styles">
.newsletter-section {
    position: relative;
    z-index: 1;
}

@media (max-width: 640px) {
    .newsletter-section form {
        gap: 1rem;
    }
    
    .newsletter-section button {
        width: 100%;
    }
}

/* Dark mode enhancements */
[data-theme="dark"] .newsletter-section input {
    background-color: rgba(255, 255, 255, 0.9);
}

[data-theme="dark"] .newsletter-section button:hover {
    background-color: rgba(255, 255, 255, 0.9);
}

/* Animation */
.newsletter-section button {
    transition: transform 0.2s ease;
}

.newsletter-section button:hover {
    transform: translateY(-1px);
}

.newsletter-section button:active {
    transform: translateY(0);
}

/* Focus styles */
.newsletter-section input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
}

.newsletter-section button:focus {
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.5);
}
</style>
<?php endif; ?>
