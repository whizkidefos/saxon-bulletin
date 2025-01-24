jQuery(document).ready(function($) {
    $('.newsletter-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitButton = $form.find('button[type="submit"]');
        const $email = $form.find('input[type="email"]');
        
        $submitButton.prop('disabled', true);
        
        $.ajax({
            url: saxonNewsletter.ajaxurl,
            type: 'POST',
            data: {
                action: 'subscribe_newsletter',
                email: $email.val(),
                nonce: saxonNewsletter.nonce
            },
            success: function(response) {
                if (response.success) {
                    $form.html('<div class="alert alert-success">' + response.data + '</div>');
                } else {
                    $form.find('.newsletter-error').remove();
                    $form.append('<div class="alert alert-danger newsletter-error">' + response.data + '</div>');
                    $submitButton.prop('disabled', false);
                }
            },
            error: function() {
                $form.find('.newsletter-error').remove();
                $form.append('<div class="alert alert-danger newsletter-error">An error occurred. Please try again.</div>');
                $submitButton.prop('disabled', false);
            }
        });
    });
});