<?php
/**
 * Template Name: Post Submission Page
 */

get_header();
?>

<div class="site-content bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                <?php the_title(); ?>
            </h1>
            <?php if (have_posts()): while (have_posts()): the_post(); ?>
                <div class="prose dark:prose-invert mx-auto">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; endif; ?>
        </div>

        <!-- Submission Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden">
            <div class="p-8">
                <?php get_template_part('template-parts/post-submission'); ?>
            </div>
        </div>
        
        <!-- Newsletter CTA -->
        <div class="mt-16">
            <?php 
            get_template_part('components/newsletter/cta', null, [
                'style' => 'compact',
                'heading' => __('Get Notified', 'saxon'),
                'text' => __('Subscribe to receive notifications when your posts are published and stay updated with our latest content.', 'saxon'),
                'bg_class' => 'bg-transparent'
            ]); 
            ?>
        </div>
    </div>
</div>

<style>
/* Enhance form fields */
.post-submission-form input[type="text"],
.post-submission-form input[type="email"],
.post-submission-form select,
.post-submission-form textarea {
    @apply w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 
           text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500;
}

/* Style the submit button */
.post-submission-form button[type="submit"] {
    @apply w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 
           text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-800 
           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 
           transition-all duration-200;
}

/* Enhance file upload area */
.post-submission-form .file-upload-area {
    @apply border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6
           hover:border-blue-500 dark:hover:border-blue-400 transition-colors duration-200;
}

/* Style form sections */
.post-submission-form .form-section {
    @apply mb-8 last:mb-0;
}

.post-submission-form .form-section-title {
    @apply text-lg font-semibold text-gray-900 dark:text-white mb-4;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .post-submission-form {
        @apply space-y-6;
    }
}
</style>

<?php get_footer(); ?>
