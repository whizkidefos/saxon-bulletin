<?php
/**
 * Featured Posts Carousel Component
 */

$featured_posts = saxon_get_featured_posts(5); 

if ($featured_posts->have_posts()): ?>
    <div class="relative featured-carousel">
        <!-- Progress Bar -->
        <div class="absolute top-0 left-0 right-0 z-10 h-1 bg-gray-200/20">
            <div class="swiper-progress-bar">
                <span class="slide-progress-bar"></span>
            </div>
        </div>

        <!-- Main Carousel -->
        <div class="swiper h-[70vh] min-h-[600px]">
            <div class="swiper-wrapper">
                <?php while ($featured_posts->have_posts()): $featured_posts->the_post(); ?>
                    <div class="swiper-slide">
                        <!-- Background Image -->
                        <div class="absolute inset-0">
                            <?php if (has_post_thumbnail()): ?>
                                <?php the_post_thumbnail('full', [
                                    'class' => 'w-full h-full object-cover',
                                ]); ?>
                            <?php endif; ?>
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                        </div>

                        <!-- Post Content -->
                        <div class="relative h-full flex items-end">
                            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
                                <div class="max-w-3xl">
                                    <!-- Categories -->
                                    <?php
                                    $categories = get_the_category();
                                    if ($categories): ?>
                                        <div class="flex flex-wrap gap-2 mb-6">
                                            <?php foreach ($categories as $category): ?>
                                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                                   class="text-sm font-medium px-4 py-1.5 bg-blue-600/90 text-white rounded-full hover:bg-blue-700 transition-colors">
                                                    <?php echo esc_html($category->name); ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Title -->
                                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-blue-100 transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>

                                    <!-- Excerpt -->
                                    <div class="text-lg text-white/90 mb-8">
                                        <?php echo wp_trim_words(get_the_excerpt(), 25); ?>
                                    </div>

                                    <!-- Meta -->
                                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4">
                                        <div class="flex items-center space-x-4">
                                            <?php if ($avatar = get_avatar(get_the_author_meta('ID'), 40)): ?>
                                                <div class="flex-shrink-0">
                                                    <?php echo str_replace('avatar', 'rounded-full ring-2 ring-white/20', $avatar); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div>
                                                <div class="font-medium text-white">
                                                    <?php the_author(); ?>
                                                </div>
                                                <div class="text-sm text-white/80">
                                                    <?php echo get_the_date(); ?> · <?php echo saxon_reading_time(); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <a href="<?php the_permalink(); ?>" 
                                           class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-full hover:bg-blue-700 transition-colors">
                                            <?php esc_html_e('Read Article', 'saxon'); ?>
                                            <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <!-- Navigation -->
            <div class="absolute inset-y-0 left-0 right-0 flex items-center justify-between px-4 md:px-8 pointer-events-none z-20">
                <button class="swiper-button-prev !flex !items-center !justify-center !w-12 !h-12 !bg-white/10 !backdrop-blur !rounded-full !text-white hover:!bg-white/20 !transition-all pointer-events-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button class="swiper-button-next !flex !items-center !justify-center !w-12 !h-12 !bg-white/10 !backdrop-blur !rounded-full !text-white hover:!bg-white/20 !transition-all pointer-events-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            <!-- Pagination -->
            <div class="swiper-pagination !bottom-8"></div>
        </div>
    </div>
<?php endif; ?>