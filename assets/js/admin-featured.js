jQuery(document).ready(function($) {
    $('.featured-toggle').on('click', function(e) {
        e.preventDefault();
        const button = $(this);
        const postId = button.data('post-id');
        const nonce = button.data('nonce');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'saxon_toggle_featured',
                post_id: postId,
                nonce: nonce
            },
            beforeSend: function() {
                button.css('opacity', '0.5');
            },
            success: function(response) {
                if (response.success) {
                    const icon = button.find('.dashicons');
                    if (response.data.featured) {
                        icon.removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
                        button.addClass('featured');
                    } else {
                        icon.removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
                        button.removeClass('featured');
                    }
                }
            },
            complete: function() {
                button.css('opacity', '1');
            }
        });
    });
});