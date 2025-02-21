<?php
/**
 * Post Submission Form Template
 */
?>

<div class="post-submission-form">
    <?php if (isset($_GET['submission']) && $_GET['submission'] === 'success'): ?>
        <div class="mb-8 p-4 bg-green-50 dark:bg-green-900 text-green-800 dark:text-green-100 rounded-lg">
            <p class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <?php esc_html_e('Thank you for your submission! Our team will review it shortly.', 'saxon'); ?>
            </p>
        </div>
    <?php endif; ?>

    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" 
          method="post" 
          enctype="multipart/form-data"
          class="space-y-8">
        
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
            <input type="text" 
                   name="post_title" 
                   id="post_title" 
                   required 
                   minlength="10"
                   placeholder="<?php esc_attr_e('Enter a descriptive title', 'saxon'); ?>">
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <?php esc_html_e('Make it specific and engaging. Minimum 10 characters.', 'saxon'); ?>
            </p>
        </div>

        <!-- Category Section -->
        <div class="form-section">
            <label for="post_category" class="form-section-title">
                <?php esc_html_e('Category', 'saxon'); ?> *
            </label>
            <select name="post_category" id="post_category" required>
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
            ]);
            ?>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <?php esc_html_e('Write your post content here. Minimum 100 characters required.', 'saxon'); ?>
            </p>
        </div>

        <!-- Featured Image Section -->
        <div class="form-section">
            <label class="form-section-title">
                <?php esc_html_e('Featured Image', 'saxon'); ?>
            </label>
            <div class="file-upload-area">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                              stroke-width="2" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4 flex text-sm justify-center">
                        <label for="featured_image" class="relative cursor-pointer rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">
                            <span><?php esc_html_e('Upload a file', 'saxon'); ?></span>
                            <input id="featured_image" 
                                   name="featured_image" 
                                   type="file" 
                                   class="sr-only" 
                                   accept="image/*">
                        </label>
                        <p class="pl-1 text-gray-500 dark:text-gray-400">
                            <?php esc_html_e('or drag and drop', 'saxon'); ?>
                        </p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        <?php esc_html_e('PNG, JPG, GIF up to 10MB', 'saxon'); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="form-section">
            <label for="email" class="form-section-title">
                <?php esc_html_e('Your Email', 'saxon'); ?> *
            </label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   required
                   placeholder="<?php esc_attr_e('you@example.com', 'saxon'); ?>">
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <?php esc_html_e('We\'ll notify you when your post is reviewed.', 'saxon'); ?>
            </p>
        </div>

        <!-- Terms Section -->
        <div class="form-section">
            <div class="relative flex items-start">
                <div class="flex h-5 items-center">
                    <input type="checkbox" 
                           name="terms" 
                           id="terms" 
                           required
                           class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms" class="font-medium text-gray-900 dark:text-gray-100">
                        <?php esc_html_e('I agree to the terms', 'saxon'); ?> *
                    </label>
                    <p class="text-gray-500 dark:text-gray-400">
                        <?php esc_html_e('I confirm that this post is original content and I have the right to publish it.', 'saxon'); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-6">
            <button type="submit">
                <?php esc_html_e('Submit Post for Review', 'saxon'); ?>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const fileInput = document.getElementById('featured_image');
    const dropArea = document.querySelector('.file-upload-area');
    
    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropArea.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
    }

    function unhighlight(e) {
        dropArea.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/10');
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
    }

    // Form validation
    form.addEventListener('submit', function(e) {
        let errors = [];
        
        // Title validation
        const title = document.getElementById('post_title').value;
        if (title.length < 10) {
            errors.push('Title must be at least 10 characters long');
        }
        
        // Content validation
        const content = tinymce.get('post_content').getContent();
        if (content.length < 100) {
            errors.push('Content must be at least 100 characters long');
        }
        
        // Image validation
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (file.size > 10 * 1024 * 1024) {
                errors.push('Image must be less than 10MB');
            }
            if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
                errors.push('Only JPG, PNG, and GIF images are allowed');
            }
        }
        
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });
});