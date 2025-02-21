<?php
/**
 * Newsletter Form Template
 */
?>

<div class="newsletter-form bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
    <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            <?php esc_html_e('Stay Updated', 'saxon'); ?>
        </h3>
        <p class="text-gray-600 dark:text-gray-300">
            <?php esc_html_e('Subscribe to our newsletter for the latest updates and exclusive content.', 'saxon'); ?>
        </p>
    </div>

    <form id="newsletter-form" class="space-y-4">
        <?php wp_nonce_field('saxon_newsletter', 'newsletter_nonce'); ?>

        <!-- Name -->
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                <?php esc_html_e('First Name', 'saxon'); ?>
            </label>
            <input type="text" 
                   name="first_name" 
                   id="first_name"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                   placeholder="<?php esc_attr_e('John', 'saxon'); ?>">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                <?php esc_html_e('Email Address', 'saxon'); ?> *
            </label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   required
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                   placeholder="<?php esc_attr_e('you@example.com', 'saxon'); ?>">
        </div>

        <?php if ($atts['categories']): ?>
            <!-- Categories -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <?php esc_html_e('Interested Topics', 'saxon'); ?>
                </label>
                <div class="space-y-2">
                    <?php
                    $categories = get_categories(['hide_empty' => false]);
                    foreach ($categories as $category): ?>
                        <label class="inline-flex items-center mr-4">
                            <input type="checkbox" 
                                   name="categories[]" 
                                   value="<?php echo esc_attr($category->term_id); ?>"
                                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                <?php echo esc_html($category->name); ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($atts['frequency']): ?>
            <!-- Frequency -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    <?php esc_html_e('Email Frequency', 'saxon'); ?>
                </label>
                <div class="space-y-2">
                    <label class="inline-flex items-center mr-4">
                        <input type="radio" 
                               name="frequency" 
                               value="daily"
                               class="border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            <?php esc_html_e('Daily', 'saxon'); ?>
                        </span>
                    </label>
                    <label class="inline-flex items-center mr-4">
                        <input type="radio" 
                               name="frequency" 
                               value="weekly"
                               checked
                               class="border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            <?php esc_html_e('Weekly', 'saxon'); ?>
                        </span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" 
                               name="frequency" 
                               value="monthly"
                               class="border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            <?php esc_html_e('Monthly', 'saxon'); ?>
                        </span>
                    </label>
                </div>
            </div>
        <?php endif; ?>

        <!-- Terms -->
        <div class="relative flex items-start">
            <div class="flex h-5 items-center">
                <input type="checkbox" 
                       name="terms" 
                       id="terms" 
                       required
                       class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
            </div>
            <div class="ml-3 text-sm">
                <label for="terms" class="font-medium text-gray-700 dark:text-gray-300">
                    <?php esc_html_e('I agree to receive newsletters', 'saxon'); ?> *
                </label>
                <p class="text-gray-500 dark:text-gray-400">
                    <?php esc_html_e('You can unsubscribe at any time. View our Privacy Policy.', 'saxon'); ?>
                </p>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                    class="w-full flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <span class="submit-text">
                    <?php esc_html_e('Subscribe Now', 'saxon'); ?>
                </span>
                <span class="loading-spinner hidden">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>

        <!-- Response Messages -->
        <div class="newsletter-response hidden mt-4 p-4 rounded-md">
            <p class="text-sm"></p>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const submitText = submitBtn.querySelector('.submit-text');
    const loadingSpinner = submitBtn.querySelector('.loading-spinner');
    const responseDiv = form.querySelector('.newsletter-response');
    const responseText = responseDiv.querySelector('p');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingSpinner.classList.remove('hidden');
        responseDiv.classList.add('hidden');

        try {
            const formData = new FormData(form);
            formData.append('action', 'saxon_newsletter_subscribe');
            formData.append('nonce', form.querySelector('#newsletter_nonce').value);

            const response = await fetch(ajaxurl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (data.success) {
                responseDiv.classList.remove('bg-red-50', 'text-red-700', 'dark:bg-red-900', 'dark:text-red-200');
                responseDiv.classList.add('bg-green-50', 'text-green-700', 'dark:bg-green-900', 'dark:text-green-200');
                form.reset();
            } else {
                responseDiv.classList.remove('bg-green-50', 'text-green-700', 'dark:bg-green-900', 'dark:text-green-200');
                responseDiv.classList.add('bg-red-50', 'text-red-700', 'dark:bg-red-900', 'dark:text-red-200');
            }

            responseText.textContent = data.data;
            responseDiv.classList.remove('hidden');

        } catch (error) {
            responseDiv.classList.remove('bg-green-50', 'text-green-700', 'dark:bg-green-900', 'dark:text-green-200');
            responseDiv.classList.add('bg-red-50', 'text-red-700', 'dark:bg-red-900', 'dark:text-red-200');
            responseText.textContent = 'An error occurred. Please try again.';
            responseDiv.classList.remove('hidden');
        }

        // Reset button state
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        loadingSpinner.classList.add('hidden');
    });
});
</script>