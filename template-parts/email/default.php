<?php
/**
 * Default email template
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
    <title><?php echo esc_html($email_subject); ?></title>
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
        .footer {
            text-align: center;
            padding: 20px 0;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #0056b3;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" style="max-width: 200px;">
        </div>
        
        <div class="content">
            <?php echo wp_kses_post($email_content); ?>
        </div>
        
        <div class="footer">
            <p>
                <?php echo esc_html(get_bloginfo('name')); ?><br>
                <?php echo esc_html(get_bloginfo('description')); ?>
            </p>
            <p>
                <?php 
                printf(
                    __('You received this email because you subscribed to our newsletter. <a href="%s">Unsubscribe</a>', 'saxon'),
                    esc_url($unsubscribe_url)
                ); 
                ?>
            </p>
        </div>
    </div>
</body>
</html>
