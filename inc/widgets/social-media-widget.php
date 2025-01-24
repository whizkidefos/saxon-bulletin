<?php
class Saxon_Social_Media_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'saxon_social_media',
            __('Saxon Social Media', 'saxon'),
            array('description' => __('Display social media links with icons', 'saxon'))
        );
    }
    
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
?>
        <div class="social-media-links">
            <?php if (!empty($instance['facebook_url'])) : ?>
                <a href="<?php echo esc_url($instance['facebook_url']); ?>" class="social-link facebook" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
            <?php endif; ?>
            
            <?php if (!empty($instance['twitter_url'])) : ?>
                <a href="<?php echo esc_url($instance['twitter_url']); ?>" class="social-link twitter" target="_blank">
                    <i class="fab fa-twitter"></i>
                </a>
            <?php endif; ?>
            
            <?php if (!empty($instance['instagram_url'])) : ?>
                <a href="<?php echo esc_url($instance['instagram_url']); ?>" class="social-link instagram" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
            <?php endif; ?>
            
            <?php if (!empty($instance['linkedin_url'])) : ?>
                <a href="<?php echo esc_url($instance['linkedin_url']); ?>" class="social-link linkedin" target="_blank">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            <?php endif; ?>
        </div>
<?php
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Follow Us', 'saxon');
        $facebook_url = isset($instance['facebook_url']) ? $instance['facebook_url'] : '';
        $twitter_url = isset($instance['twitter_url']) ? $instance['twitter_url'] : '';
        $instagram_url = isset($instance['instagram_url']) ? $instance['instagram_url'] : '';
        $linkedin_url = isset($instance['linkedin_url']) ? $instance['linkedin_url'] : '';
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'saxon'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('facebook_url'); ?>"><?php _e('Facebook URL:', 'saxon'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('facebook_url'); ?>" name="<?php echo $this->get_field_name('facebook_url'); ?>" type="url" value="<?php echo esc_attr($facebook_url); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('twitter_url'); ?>"><?php _e('Twitter URL:', 'saxon'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter_url'); ?>" name="<?php echo $this->get_field_name('twitter_url'); ?>" type="url" value="<?php echo esc_attr($twitter_url); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('instagram_url'); ?>"><?php _e('Instagram URL:', 'saxon'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('instagram_url'); ?>" name="<?php echo $this->get_field_name('instagram_url'); ?>" type="url" value="<?php echo esc_attr($instagram_url); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('linkedin_url'); ?>"><?php _e('LinkedIn URL:', 'saxon'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('linkedin_url'); ?>" name="<?php echo $this->get_field_name('linkedin_url'); ?>" type="url" value="<?php echo esc_attr($linkedin_url); ?>">
        </p>
<?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['facebook_url'] = (!empty($new_instance['facebook_url'])) ? esc_url_raw($new_instance['facebook_url']) : '';
        $instance['twitter_url'] = (!empty($new_instance['twitter_url'])) ? esc_url_raw($new_instance['twitter_url']) : '';
        $instance['instagram_url'] = (!empty($new_instance['instagram_url'])) ? esc_url_raw($new_instance['instagram_url']) : '';
        $instance['linkedin_url'] = (!empty($new_instance['linkedin_url'])) ? esc_url_raw($new_instance['linkedin_url']) : '';
        return $instance;
    }
}