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
            <label for="post_title" class="form-section-title">
                <?php esc_html_e('Post Title', 'saxon'); ?> *
            </label>
            <div class="relative">
                <input type="text" 
                       name="post_title" 
                       id="post_title" 
                       required 
                       minlength="10"
                       class="pr-12"
                       placeholder="<?php esc_attr_e('Enter a descriptive title', 'saxon'); ?>">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <span id="title-length" class="text-sm text-gray-400"></span>
                </div>
            </div>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <?php esc_html_e('Make it specific and engaging. Minimum 10 characters.', 'saxon'); ?>
            </p>
        </div>

        <!-- Category Section -->
        <div class="form-section">
            <label for="post_category" class="form-section-title">
                <?php esc_html_e('Category', 'saxon'); ?> *
            </label>
            <select name="post_category" 
                    id="post_category" 
                    required 
                    class="pr-10">
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
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <?php esc_html_e('Choose the most relevant category for your content.', 'saxon'); ?>
            </p>
        </div>

        <!-- Content Section -->
        <div class="form-section">
            <label for="post_content" class="form-section-title">
                <?php esc_html_e('Post Content', 'saxon'); ?> *
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
                <span id="content-length" class="text-sm text-gray-400"></span>
            </div>
        </div>

        <!-- Featured Image Section -->
        <div class="form-section">
            <label class="form-section-title">
                <?php esc_html_e('Featured Image', 'saxon'); ?>
            </label>
            <div class="file-upload-area" id="featured-image-upload">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                              stroke-width="2" 
                              stroke-linecap="round" 
                              stroke-linejoin="round"/>
                    </svg>
                    <div class="mt-4 flex text-sm text-gray-600 dark:text-gray-400">
                        <label for="featured_image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                            <span><?php esc_html_e('Upload a file', 'saxon'); ?></span>
                            <input id="featured_image" 
                                   name="featured_image" 
                                   type="file" 
                                   class="sr-only"
                                   accept="image/*">
                        </label>
                        <p class="pl-1"><?php esc_html_e('or drag and drop', 'saxon'); ?></p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        <?php esc_html_e('PNG, JPG, GIF up to 5MB', 'saxon'); ?>
                    </p>
                </div>
                <div id="image-preview" class="hidden mt-4">
                    <img src="" alt="" class="max-h-48 mx-auto rounded">
                    <button type="button" 
                            id="remove-image" 
                            class="mt-2 text-sm text-red-600 dark:text-red-400 hover:text-red-500">
                        <?php esc_html_e('Remove image', 'saxon'); ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Author Info Section -->
        <div class="form-section">
            <label for="author_name" class="form-section-title">
                <?php esc_html_e('Your Name', 'saxon'); ?> *
            </label>
            <input type="text" 
                   name="author_name" 
                   id="author_name" 
                   required
                   placeholder="<?php esc_attr_e('Enter your full name', 'saxon'); ?>">
        </div>

        <div class="form-section">
            <label for="email" class="form-section-title">
                <?php esc_html_e('Your Email', 'saxon'); ?> *
            </label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   required
                   placeholder="<?php esc_attr_e('Enter your email address', 'saxon'); ?>">
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <?php esc_html_e('We\'ll notify you when your post is published.', 'saxon'); ?>
            </p>
        </div>

        <!-- Terms Acceptance -->
        <div class="form-section">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input type="checkbox" 
                           id="terms" 
                           name="terms" 
                           required
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms" class="font-medium text-gray-700 dark:text-gray-200">
                        <?php esc_html_e('I agree to the terms and conditions', 'saxon'); ?> *
                    </label>
                    <p class="text-gray-500 dark:text-gray-400">
                        <?php 
                        printf(
                            esc_html__('By submitting this post, you agree to our %1$s and %2$s.', 'saxon'),
                            '<a href="' . esc_url(get_privacy_policy_url()) . '" class="text-blue-600 dark:text-blue-400 hover:underline">' . esc_html__('Privacy Policy', 'saxon') . '</a>',
                            '<a href="' . esc_url(home_url('/terms')) . '" class="text-blue-600 dark:text-blue-400 hover:underline">' . esc_html__('Terms of Service', 'saxon') . '</a>'
                        );
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-4">
            <span class="text-sm text-gray-500 dark:text-gray-400" id="form-status"></span>
            <button type="submit" class="submit-button" disabled>
                <span class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" id="loading-spinner" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="submit-text">
                        <?php esc_html_e('Submit Post', 'saxon'); ?>
                    </span>
                </span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#post-submission-form');
    const titleInput = document.querySelector('#post_title');
    const titleLength = document.querySelector('#title-length');
    const contentLength = document.querySelector('#content-length');
    const submitButton = form.querySelector('button[type="submit"]');
    const formStatus = document.querySelector('#form-status');
    const loadingSpinner = document.querySelector('#loading-spinner');
    const submitText = document.querySelector('#submit-text');
    const imagePreview = document.querySelector('#image-preview');
    const imageInput = document.querySelector('#featured_image');
    const removeImageBtn = document.querySelector('#remove-image');
    const uploadArea = document.querySelector('#featured-image-upload');

    // Title character count
    titleInput.addEventListener('input', function() {
        const length = this.value.length;
        titleLength.textContent = `${length}/100`;
        validateForm();
    });

    // Content character count
    if (typeof tinyMCE !== 'undefined') {
        tinyMCE.on('AddEditor', function(e) {
            e.editor.on('input keyup', function() {
                const content = e.editor.getContent().replace(/<[^>]*>/g, '');
                const length = content.length;
                contentLength.textContent = `${length}/1000`;
                validateForm();
            });
        });
    }

    // Image preview
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('<?php esc_html_e('File size must be less than 5MB', 'saxon'); ?>');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.querySelector('img').src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadArea.classList.add('border-blue-500', 'dark:border-blue-400');
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove image
    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        imagePreview.classList.add('hidden');
        uploadArea.classList.remove('border-blue-500', 'dark:border-blue-400');
    });

    // Drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        uploadArea.classList.add('border-blue-500', 'dark:border-blue-400');
    }

    function unhighlight(e) {
        uploadArea.classList.remove('border-blue-500', 'dark:border-blue-400');
    }

    uploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const file = dt.files[0];

        if (file && file.type.startsWith('image/')) {
            imageInput.files = dt.files;
            const event = new Event('change');
            imageInput.dispatchEvent(event);
        }
    }

    // Form validation
    function validateForm() {
        const title = titleInput.value;
        const content = tinyMCE.activeEditor ? tinyMCE.activeEditor.getContent().replace(/<[^>]*>/g, '') : '';
        const category = document.querySelector('#post_category').value;
        const authorName = document.querySelector('#author_name').value;
        const email = document.querySelector('#email').value;
        const terms = document.querySelector('#terms').checked;

        const isValid = title.length >= 10 && 
                       content.length >= 100 && 
                       category && 
                       authorName && 
                       email && 
                       terms;

        submitButton.disabled = !isValid;
        formStatus.textContent = isValid ? '' : '<?php esc_html_e('Please fill in all required fields', 'saxon'); ?>';
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        loadingSpinner.classList.remove('hidden');
        submitText.textContent = '<?php esc_html_e('Submitting...', 'saxon'); ?>';
        submitButton.disabled = true;
    });

    // Listen to all form field changes
    form.querySelectorAll('input, select, textarea').forEach(element => {
        element.addEventListener('change', validateForm);
    });

    // Initial validation
    validateForm();
});
</script>