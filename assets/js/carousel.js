document.addEventListener('DOMContentLoaded', function() {
    const progressBar = document.querySelector('.slide-progress-bar');
    let progressBarAnimation;

    // Animation handler
    function handleSlideAnimation(slide) {
        const elements = slide.querySelectorAll('[data-swiper-animation]');
        elements.forEach(element => {
            const animation = element.dataset.swiperAnimation;
            const duration = element.dataset.duration || '0.6s';
            const delay = element.dataset.delay || '0s';
            
            element.style.opacity = '0';
            element.style.animation = 'none';
            
            setTimeout(() => {
                element.style.animation = `${animation} ${duration} ${delay} forwards`;
            }, 100);
        });
    }

    // Progress bar animation
    function startProgressBar() {
        if (progressBar) {
            progressBar.style.transform = 'scaleX(0)';
            progressBar.style.transition = 'none';
            
            requestAnimationFrame(() => {
                progressBar.style.transform = 'scaleX(1)';
                progressBar.style.transition = 'transform 5000ms linear';
            });
        }
    }

    // Initialize Swiper
    const featuredCarousel = new Swiper('.featured-carousel .swiper', {
        loop: true,
        speed: 1000,
        grabCursor: true,
        watchSlidesProgress: true,
        mousewheelControl: true,
        keyboardControl: true,
        
        // Autoplay with progress bar
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        
        // Fancy fade effect
        effect: "creative",
        creativeEffect: {
            prev: {
                translate: [0, 0, -400],
                opacity: 0
            },
            next: {
                translate: [0, 0, -400],
                opacity: 0
            },
        },
        
        // Navigation
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        
        // Pagination
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '"></span>';
            },
        },
        
        // Events
        on: {
            init: function() {
                handleSlideAnimation(this.slides[this.activeIndex]);
                startProgressBar();
            },
            slideChange: function() {
                handleSlideAnimation(this.slides[this.activeIndex]);
                startProgressBar();
            },
            autoplayStart: function() {
                startProgressBar();
            },
            autoplayStop: function() {
                if (progressBar) {
                    progressBar.style.transform = 'scaleX(0)';
                }
            }
        },
    });

    // Handle hover effects
    const swiperContainer = document.querySelector('.featured-carousel');
    
    if (swiperContainer) {
        swiperContainer.addEventListener('mouseenter', function() {
            featuredCarousel.autoplay.stop();
            if (progressBar) {
                progressBar.style.transform = 'scaleX(0)';
            }
        });
        
        swiperContainer.addEventListener('mouseleave', function() {
            featuredCarousel.autoplay.start();
            startProgressBar();
        });
    }

    // Keyboard Navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            featuredCarousel.slidePrev();
        } else if (e.key === 'ArrowRight') {
            featuredCarousel.slideNext();
        }
    });

    // Handle touch events for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    swiperContainer.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    });

    swiperContainer.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        const swipeThreshold = 50;
        const difference = touchStartX - touchEndX;

        if (Math.abs(difference) > swipeThreshold) {
            if (difference > 0) {
                featuredCarousel.slideNext();
            } else {
                featuredCarousel.slidePrev();
            }
        }
    }
});