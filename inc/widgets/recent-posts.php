<?php
class Saxon_Recent_Posts_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'saxon_recent_posts',
            __('Saxon Recent Posts', 'saxon'),
            array('description' => __('Display recent posts with thumbnails', 'saxon'))
        );
    }
    
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        
        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        $recent_posts = new WP_Query(array(
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true
        ));
        
        if ($recent_posts->have_posts()) : ?>
            <div class="saxon-recent-posts">
                <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                    <div class="recent-post-item mb-3">
                        <div class="row g-0">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="col-3">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid')); ?>
                                    </a>
                                </div>
                                <div class="col-9 ps-3">
                            <?php else : ?>
                                <div class="col-12">
                            <?php endif; ?>
                                    <h6 class="recent-post-title mb-1">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h6>
                                    <div class="post-meta small">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php
        endif;
        wp_reset_postdata();
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Recent Posts', 'saxon');
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'saxon'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'saxon'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        return $instance;
    }
}