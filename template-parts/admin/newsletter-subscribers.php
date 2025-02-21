<?php
/**
 * Newsletter subscribers admin page template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Newsletter Subscribers', 'saxon'); ?></h1>
    
    <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=saxon_export_subscribers&_wpnonce=' . wp_create_nonce('saxon-newsletter-admin'))); ?>" class="page-title-action">
        <?php _e('Export All', 'saxon'); ?>
    </a>

    <?php settings_errors('saxon_newsletter'); ?>

    <form method="post">
        <?php wp_nonce_field('bulk-subscribers'); ?>
        
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-top" class="screen-reader-text"><?php _e('Select bulk action', 'saxon'); ?></label>
                <select name="action" id="bulk-action-selector-top">
                    <option value="-1"><?php _e('Bulk Actions', 'saxon'); ?></option>
                    <option value="delete"><?php _e('Delete', 'saxon'); ?></option>
                    <option value="export"><?php _e('Export', 'saxon'); ?></option>
                </select>
                <input type="submit" class="button action" value="<?php esc_attr_e('Apply', 'saxon'); ?>">
            </div>

            <div class="tablenav-pages">
                <?php
                echo paginate_links([
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'total' => $total_pages,
                    'current' => $current_page
                ]);
                ?>
            </div>
        </div>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <input type="checkbox">
                    </td>
                    <th scope="col" class="manage-column column-email"><?php _e('Email', 'saxon'); ?></th>
                    <th scope="col" class="manage-column column-name"><?php _e('Name', 'saxon'); ?></th>
                    <th scope="col" class="manage-column column-date"><?php _e('Date', 'saxon'); ?></th>
                    <th scope="col" class="manage-column column-status"><?php _e('Status', 'saxon'); ?></th>
                    <th scope="col" class="manage-column column-frequency"><?php _e('Frequency', 'saxon'); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($subscribers)) : ?>
                    <tr>
                        <td colspan="6"><?php _e('No subscribers found.', 'saxon'); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($subscribers as $subscriber) : ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input type="checkbox" name="subscriber[]" value="<?php echo esc_attr($subscriber->id); ?>">
                            </th>
                            <td class="column-email">
                                <?php echo esc_html($subscriber->email); ?>
                                <div class="row-actions">
                                    <span class="delete">
                                        <a href="#" class="delete-subscriber" data-id="<?php echo esc_attr($subscriber->id); ?>">
                                            <?php _e('Delete', 'saxon'); ?>
                                        </a>
                                    </span>
                                </div>
                            </td>
                            <td class="column-name"><?php echo esc_html($subscriber->first_name); ?></td>
                            <td class="column-date">
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($subscriber->subscription_date))); ?>
                            </td>
                            <td class="column-status">
                                <?php echo $subscriber->verified ? 
                                    '<span class="status-verified">' . __('Verified', 'saxon') . '</span>' : 
                                    '<span class="status-pending">' . __('Pending', 'saxon') . '</span>'; 
                                ?>
                            </td>
                            <td class="column-frequency"><?php echo esc_html($subscriber->frequency); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td class="manage-column column-cb check-column">
                        <input type="checkbox">
                    </td>
                    <th scope="col" class="manage-column column-email"><?php _e('Email', 'saxon'); ?></th>
                    <th scope="col" class="manage-column column-name"><?php _e('Name', 'saxon'); ?></th>
                    <th scope="col" class="manage-column column-date"><?php _e('Date', 'saxon'); ?></th>
                    <th scope="col" class="manage-column column-status"><?php _e('Status', 'saxon'); ?></th>
                    <th scope="col" class="manage-column column-frequency"><?php _e('Frequency', 'saxon'); ?></th>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
