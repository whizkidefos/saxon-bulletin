<?php
/**
 * Newsletter Unsubscribe Email Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php _e('Unsubscribe Confirmation', 'saxon'); ?></title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #f8f9fa;
        }
        .content {
            padding: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #dc3545;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" style="max-width: 200px;">
        </div>
        
        <div class="content">
            <h1><?php _e('Unsubscribe Confirmation', 'saxon'); ?></h1>
            
            <p><?php _e('We\'re sorry to see you go!', 'saxon'); ?></p>
            
            <p><?php _e('Click the button below to unsubscribe from our newsletter:', 'saxon'); ?></p>
            
            <p style="text-align: center;">
                <a href="<?php echo esc_url($unsubscribe_url); ?>" class="button">
                    <?php _e('Confirm Unsubscribe', 'saxon'); ?>
                </a>
            </p>
            
            <p><?php _e('If you did not request to unsubscribe, please ignore this email.', 'saxon'); ?></p>
            
            <p><?php _e('If you change your mind, you can always subscribe again through our website.', 'saxon'); ?></p>
        </div>
        
        <div class="footer">
            <p>
                <?php echo esc_html(get_bloginfo('name')); ?><br>
                <?php echo esc_html(get_bloginfo('description')); ?>
            </p>
            <p>
                <?php _e('This email was sent to confirm your unsubscribe request.', 'saxon'); ?>
            </p>
        </div>
    </div>
</body>
</html>
