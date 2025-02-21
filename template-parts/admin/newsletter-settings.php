<?php
/**
 * Newsletter settings admin page template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h1><?php _e('Newsletter Settings', 'saxon'); ?></h1>

    <?php settings_errors('saxon_newsletter'); ?>

    <form method="post" action="">
        <?php wp_nonce_field('saxon_newsletter_settings'); ?>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="from_name"><?php _e('From Name', 'saxon'); ?></label>
                </th>
                <td>
                    <input type="text" name="saxon_newsletter_settings[from_name]" id="from_name" 
                           value="<?php echo esc_attr($settings['from_name'] ?? get_bloginfo('name')); ?>" 
                           class="regular-text">
                    <p class="description">
                        <?php _e('The name that will appear in the From field of newsletter emails.', 'saxon'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="from_email"><?php _e('From Email', 'saxon'); ?></label>
                </th>
                <td>
                    <input type="email" name="saxon_newsletter_settings[from_email]" id="from_email" 
                           value="<?php echo esc_attr($settings['from_email'] ?? get_bloginfo('admin_email')); ?>" 
                           class="regular-text">
                    <p class="description">
                        <?php _e('The email address that newsletters will be sent from.', 'saxon'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="reply_to"><?php _e('Reply-To Email', 'saxon'); ?></label>
                </th>
                <td>
                    <input type="email" name="saxon_newsletter_settings[reply_to]" id="reply_to" 
                           value="<?php echo esc_attr($settings['reply_to'] ?? get_bloginfo('admin_email')); ?>" 
                           class="regular-text">
                    <p class="description">
                        <?php _e('The email address that subscribers can reply to.', 'saxon'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="success_message"><?php _e('Success Message', 'saxon'); ?></label>
                </th>
                <td>
                    <?php
                    wp_editor(
                        $settings['success_message'] ?? __('Thank you for subscribing! Please check your email to confirm your subscription.', 'saxon'),
                        'success_message',
                        [
                            'textarea_name' => 'saxon_newsletter_settings[success_message]',
                            'textarea_rows' => 5,
                            'media_buttons' => false,
                            'teeny' => true,
                        ]
                    );
                    ?>
                    <p class="description">
                        <?php _e('Message shown after successful subscription.', 'saxon'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="verification_message"><?php _e('Verification Message', 'saxon'); ?></label>
                </th>
                <td>
                    <?php
                    wp_editor(
                        $settings['verification_message'] ?? __('Your subscription has been confirmed. Thank you for subscribing!', 'saxon'),
                        'verification_message',
                        [
                            'textarea_name' => 'saxon_newsletter_settings[verification_message]',
                            'textarea_rows' => 5,
                            'media_buttons' => false,
                            'teeny' => true,
                        ]
                    );
                    ?>
                    <p class="description">
                        <?php _e('Message shown after email verification.', 'saxon'); ?>
                    </p>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Settings', 'saxon')); ?>
    </form>
</div>
