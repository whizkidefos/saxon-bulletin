<?php
/**
 * Social Share Component
 */

$post_url = urlencode(get_permalink());
$post_title = urlencode(get_the_title());
$post_thumbnail = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(null, 'large')) : '';

$share_links = [
    'twitter' => [
        'url' => "https://twitter.com/intent/tweet?text={$post_title}&url={$post_url}",
        'label' => __('Share on Twitter', 'saxon'),
        'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>',
        'bg_color' => 'bg-[#1DA1F2]',
        'hover_color' => 'hover:bg-[#1a91da]'
    ],
    'facebook' => [
        'url' => "https://www.facebook.com/sharer/sharer.php?u={$post_url}",
        'label' => __('Share on Facebook', 'saxon'),
        'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>',
        'bg_color' => 'bg-[#4267B2]',
        'hover_color' => 'hover:bg-[#365899]'
    ],
    'linkedin' => [
        'url' => "https://www.linkedin.com/shareArticle?mini=true&url={$post_url}&title={$post_title}",
        'label' => __('Share on LinkedIn', 'saxon'),
        'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
        'bg_color' => 'bg-[#0077B5]',
        'hover_color' => 'hover:bg-[#006399]'
    ],
    'whatsapp' => [
        'url' => "https://api.whatsapp.com/send?text={$post_title}%20{$post_url}",
        'label' => __('Share on WhatsApp', 'saxon'),
        'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.4 3.6C18.2 1.3 15.2 0 12 0 5.4 0 0 5.4 0 12c0 2.1.5 4.2 1.5 6L0 24l6.2-1.4c1.8.9 3.8 1.4 5.8 1.4 6.6 0 12-5.4 12-12 0-3.2-1.3-6.2-3.6-8.4zM12 22c-1.8 0-3.5-.5-5-1.3l-.4-.2-3.7.8.9-3.6-.2-.4c-1-1.6-1.5-3.4-1.5-5.2C2.1 6.5 6.5 2.1 12 2.1c2.7 0 5.2 1 7 2.9s2.9 4.3 2.9 7c0 5.5-4.4 9.9-9.9 9.9z"/></svg>',
        'bg_color' => 'bg-[#25D366]',
        'hover_color' => 'hover:bg-[#128C7E]'
    ],
    'email' => [
        'url' => "mailto:?subject={$post_title}&body={$post_url}",
        'label' => __('Share via Email', 'saxon'),
        'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>',
        'bg_color' => 'bg-gray-600',
        'hover_color' => 'hover:bg-gray-700'
    ]
];
?>

<div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
        <?php esc_html_e('Share this article', 'saxon'); ?>
    </h3>
    
    <div class="flex flex-wrap gap-2">
        <?php foreach ($share_links as $platform => $data): ?>
            <a href="<?php echo esc_url($data['url']); ?>" 
               class="inline-flex items-center px-4 py-2 rounded-md text-white <?php echo esc_attr($data['bg_color'] . ' ' . $data['hover_color']); ?> transition-colors"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="<?php echo esc_attr($data['label']); ?>">
                <?php echo $data['icon']; ?>
                <span class="ml-2 hidden sm:inline">
                    <?php echo esc_html($data['label']); ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</div>