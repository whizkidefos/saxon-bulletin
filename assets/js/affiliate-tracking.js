jQuery(document).ready(function($) {
    $('.affiliate-link').on('click', function(e) {
        e.preventDefault();
        
        const $link = $(this);
        const linkId = $link.data('affiliate-id');
        
        $.ajax({
            url: saxonAffiliateData.ajaxurl,
            type: 'POST',
            data: {
                action: 'saxon_track_affiliate_click',
                link_id: linkId,
                nonce: saxonAffiliateData.nonce
            },
            success: function(response) {
                if (response.success && response.data.url) {
                    window.open(response.data.url, '_blank');
                }
            }
        });
    });
});
