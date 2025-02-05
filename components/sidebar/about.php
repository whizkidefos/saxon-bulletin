<?php
/**
 * About Widget Component
 */
?>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
    <?php if ($avatar = get_avatar(get_the_author_meta('ID'), 80)): ?>
        <div class="flex justify-center mb-4">
            <?php echo str_replace('avatar', 'rounded-full', $avatar); ?>
        </div>
    <?php endif; ?>

    <h2 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-3">
        <?php echo esc_html(get_theme_mod('saxon_about_title', get_bloginfo('name'))); ?>
    </h2>

    <p class="text-gray-600 dark:text-gray-300 text-center mb-6">
        <?php echo wp_kses_post(get_theme_mod('saxon_about_text', '')); ?>
    </p>

    <div class="flex justify-center space-x-4">
        <?php
        $social_platforms = [
            'twitter'   => ['icon' => 'twitter', 'label' => __('Twitter', 'saxon')],
            'facebook'  => ['icon' => 'facebook', 'label' => __('Facebook', 'saxon')],
            'instagram' => ['icon' => 'instagram', 'label' => __('Instagram', 'saxon')],
            'linkedin'  => ['icon' => 'linkedin', 'label' => __('LinkedIn', 'saxon')],
        ];

        foreach ($social_platforms as $platform => $data):
            $url = get_theme_mod("saxon_social_{$platform}");
            if ($url): ?>
                <a href="<?php echo esc_url($url); ?>" 
                   class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                   target="_blank"
                   rel="noopener noreferrer">
                    <span class="sr-only"><?php echo esc_html($data['label']); ?></span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <?php get_template_part('components/icons/' . $data['icon']); ?>
                    </svg>
                </a>
            <?php endif;
        endforeach; ?>
    </div>
</div>