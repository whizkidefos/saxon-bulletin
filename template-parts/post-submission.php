<?php
/**
 * Post Submission Form Template
 */
?>

<div class="post-submission-form">
    <?php if (isset($_GET['submission']) && $_GET['submission'] === 'success'): ?>
        <div class="mb-8 p-4 bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-100 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-medium">
                        <?php esc_html_e('Thank you for your submission!', 'saxon'); ?>
                    </p>
                    <p class="text-sm mt-1">
                        <?php esc_html_e('Our team will review it shortly. You will receive an email notification once it\'s published.', 'saxon'); ?>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" 
          method="post" 
          enctype="multipart/form-data"
          class="space-y-8"
          id="post-submission-form">
        
        <input type="hidden" name="action" value="saxon_submit_post">
        <?php wp_nonce_field('saxon_post_submission', 'saxon_post_nonce'); ?>
        
        <!-- Honeypot field -->
        <div class="hidden">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <!-- Title Section -->
        <div class="form-section">
            <label for="post_title" class="form-section-title flex items-center mb-2">
                <?php esc_html_e('Post Title', 'saxon'); ?> 
                <span class="text-red-500 ml-1">*</span>
                <span id="title-length" class="ml-auto text-sm text-gray-500 dark:text-gray-400"></span>
            </label>
            <div class="relative group">
                <input type="text" 
                       name="post_title" 
                       id="post_title" 
                       required 
                       minlength="10"
                       maxlength="100"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 
                              bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                              placeholder-gray-400 dark:placeholder-gray-500
                              transition duration-150 ease-in-out"
                       placeholder="<?php esc_attr_e('Enter a descriptive title for your post', 'saxon'); ?>">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <?php esc_html_e('Create a clear, descriptive title that accurately represents your content.', 'saxon'); ?>
            </p>
        </div>

        <!-- Category Section -->
        <div class="form-section">
            <label for="post_category" class="form-section-title flex items-center mb-2">
                <?php esc_html_e('Category', 'saxon'); ?>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="relative group">
                <select name="post_category" 
                        id="post_category" 
                        required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 
                               bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent
                               transition duration-150 ease-in-out
                               appearance-none">
                    <option value=""><?php esc_html_e('Select a category', 'saxon'); ?></option>
                    <?php
                    $categories = get_categories([
                        'hide_empty' => false,
                        'orderby'    => 'name',
                        'order'      => 'ASC'
                    ]);
                    foreach ($categories as $category) {
                        printf(
                            '<option value="%s">%s</option>',
                            esc_attr($category->term_id),
                            esc_html($category->name)
                        );
                    }
                    ?>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <?php esc_html_e('Choose the most relevant category for your content.', 'saxon'); ?>
            </p>
        </div>

        <!-- Content Section -->
        <div class="form-section">
            <label for="post_content" class="form-section-title flex items-center mb-2">
                <?php esc_html_e('Post Content', 'saxon'); ?> 
                <span class="text-red-500 ml-1">*</span>
                <span id="content-length" class="ml-auto text-sm text-gray-500 dark:text-gray-400"></span>
            </label>
            <?php
            wp_editor('', 'post_content', [
                'media_buttons' => false,
                'textarea_rows' => 12,
                'teeny'        => true,
                'quicktags'    => false,
                'editor_class' => 'mb-2',
                'editor_css'   => '<style>.wp-editor-area { min-height: 200px; }</style>'
            ]);
            ?>
            <div class="flex items-center justify-between mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <?php esc_html_e('Write your post content here. Minimum 100 characters required.', 'saxon'); ?>
                </p>
            </div>
        </div>

        <!-- Featured Image Section -->
        <div class="form-section">
            <label class="form-section-title flex items-center mb-2">
                <?php esc_html_e('Featured Image', 'saxon'); ?>
                <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">(<?php esc_html_e('Recommended', 'saxon'); ?>)</span>
            </label>
            <div class="file-upload-area group" id="featured-image-upload">
                <div class="relative">
                    <input type="file" 
                           name="featured_image" 
                           id="featured_image" 
                           accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           aria-label="<?php esc_attr_e('Upload featured image', 'saxon'); ?>">
                    
                    <div class="upload-placeholder flex flex-col items-center justify-center p-8 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800/50 group-hover:border-blue-500 transition-colors duration-200">
                        <div class="mb-4">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                <?php esc_html_e('Click to upload', 'saxon'); ?>
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <?php esc_html_e('PNG, JPG, GIF up to 5MB', 'saxon'); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Image Preview Container -->
                    <div id="image-preview" class="hidden relative mt-4">
                        <img src="" alt="<?php esc_attr_e('Preview', 'saxon'); ?>" class="max-w-full h-auto rounded-lg shadow-lg">
                        <button type="button" 
                                id="remove-image" 
                                class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full shadow-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <?php esc_html_e('Add a high-quality image to make your post more engaging.', 'saxon'); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Author Info Section -->
        <div class="form-section">
            <label for="author_name" class="form-section-title flex items-center mb-2">
                <?php esc_html_e('Your Name', 'saxon'); ?> 
                <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="relative group">
                <input type="text" 
                       name="author_name" 
                       id="author_name" 
                       required 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 
                              bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                              placeholder-gray-400 dark:placeholder-gray-500
                              transition duration-150 ease-in-out"
                       placeholder="<?php esc_attr_e('Enter your full name', 'saxon'); ?>">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <?php esc_html_e('Enter your full name as you would like it to appear on the post.', 'saxon'); ?>
            </p>
        </div>

        <div class="form-section">
            <label for="email" class="form-section-title flex items-center mb-2">
                <?php esc_html_e('Your Email', 'saxon'); ?> 
                <span class="text-red-500 ml-1">*</span>
            </label>
            <div class="relative group">
                <input type="email" 
                       name="email" 
                       id="email" 
                       required 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 
                              bg-white dark:bg-gray-800 text-gray-900 dark:text-white
                              focus:ring-2 focus:ring-blue-500 focus:border-transparent
                              placeholder-gray-400 dark:placeholder-gray-500
                              transition duration-150 ease-in-out"
                       placeholder="<?php esc_attr_e('Enter your email address', 'saxon'); ?>">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <?php esc_html_e('We\'ll use this email address to notify you when your post is published.', 'saxon'); ?>
            </p>
        </div>

        <!-- Terms and Conditions -->
        <div class="form-section">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" 
                           id="terms" 
                           name="terms" 
                           required
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-blue-600">
                </div>
                <label for="terms" class="ml-3 text-sm">
                    <span class="text-gray-700 dark:text-gray-300">
                        <?php esc_html_e('I agree to the', 'saxon'); ?>
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                            <?php esc_html_e('Terms and Conditions', 'saxon'); ?>
                        </a>
                        <?php esc_html_e('and', 'saxon'); ?>
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                            <?php esc_html_e('Content Guidelines', 'saxon'); ?>
                        </a>
                    </span>
                </label>
            </div>
        </div>

        <!-- reCAPTCHA -->
        <div class="form-section">
            <?php if ($site_key = get_option('saxon_recaptcha_site_key')): ?>
                <div class="g-recaptcha mb-4" data-sitekey="<?php echo esc_attr($site_key); ?>"></div>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <?php endif; ?>
        </div>

        <!-- Submit Button -->
        <div class="form-section">
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-lg
                           hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                           dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-offset-gray-900
                           disabled:opacity-50 disabled:cursor-not-allowed
                           transition duration-150 ease-in-out
                           flex items-center justify-center space-x-2">
                <span><?php esc_html_e('Submit Post', 'saxon'); ?></span>
                <svg class="w-5 h-5 hidden" id="submit-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('post-submission-form');
    const fileInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('image-preview');
    const previewImage = imagePreview.querySelector('img');
    const removeButton = document.getElementById('remove-image');
    const submitButton = form.querySelector('button[type="submit"]');
    const submitSpinner = document.getElementById('submit-spinner');

    // File Upload Preview
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) { // 5MB limit
                alert('<?php esc_html_e('File size must be less than 5MB', 'saxon'); ?>');
                fileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove Image
    removeButton.addEventListener('click', function() {
        fileInput.value = '';
        imagePreview.classList.add('hidden');
        previewImage.src = '';
    });

    // Form Submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!form.checkValidity()) {
            return;
        }

        submitButton.disabled = true;
        submitSpinner.classList.remove('hidden');

        // Simulate form submission (replace with actual submission logic)
        setTimeout(() => {
            form.submit();
        }, 1000);
    });

    // Character Counter for Title
    const titleInput = document.getElementById('post_title');
    const titleLength = document.getElementById('title-length');
    
    titleInput.addEventListener('input', function() {
        const remaining = 100 - this.value.length;
        titleLength.textContent = `${remaining} ${remaining === 1 ? 'character' : 'characters'} remaining`;
        
        if (remaining <= 10) {
            titleLength.classList.add('text-red-500');
        } else {
            titleLength.classList.remove('text-red-500');
        }
    });
});
</script>

<style>
/* Enhanced form field focus states */
.post-submission-form input:focus-visible,
.post-submission-form select:focus-visible,
.post-submission-form textarea:focus-visible {
    @apply outline-none ring-2 ring-blue-500 border-transparent;
}

/* Custom select styling */
.post-submission-form select {
    background-image: none;
}

/* Floating labels animation */
.post-submission-form .form-section-title {
    @apply transform transition-all duration-200;
}

.post-submission-form input:focus + .form-section-title,
.post-submission-form select:focus + .form-section-title,
.post-submission-form textarea:focus + .form-section-title {
    @apply text-blue-600 dark:text-blue-400;
}

/* Input field hover effects */
.post-submission-form input:hover,
.post-submission-form select:hover,
.post-submission-form textarea:hover {
    @apply border-gray-400 dark:border-gray-500;
}

/* Required field indicators */
.post-submission-form .required::after {
    content: "*";
    @apply text-red-500 ml-1;
}

/* Character counter styling */
.post-submission-form .char-counter {
    @apply text-sm text-gray-500 dark:text-gray-400 transition-all duration-200;
}

.post-submission-form .char-counter.near-limit {
    @apply text-yellow-600 dark:text-yellow-400;
}

.post-submission-form .char-counter.at-limit {
    @apply text-red-600 dark:text-red-400;
}

/* File upload area animations */
.file-upload-area .upload-placeholder {
    @apply transition-all duration-200 ease-in-out;
}

.file-upload-area:hover .upload-placeholder {
    @apply border-blue-500 bg-blue-50 dark:bg-blue-900/10;
}

/* Loading spinner animation */
@keyframes spin {
    to { transform: rotate(360deg); }
}

#submit-spinner {
    animation: spin 1s linear infinite;
}

/* Image preview transitions */
#image-preview {
    @apply transition-all duration-200 ease-in-out;
}

#image-preview img {
    @apply transition-transform duration-200 ease-in-out;
}

#image-preview:hover img {
    @apply transform scale-[1.02];
}

/* Remove button hover effects */
#remove-image {
    @apply transition-all duration-200 ease-in-out opacity-0;
}

#image-preview:hover #remove-image {
    @apply opacity-100;
}
</style>