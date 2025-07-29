jQuery(document).ready(function($) {
    // Handle settings page interactions
    $('#seo-meta-manager-settings-form').on('submit', function(e) {
        // Add any additional validation here
    });

    // Preview functionality
    $('.seo-meta-preview-button').on('click', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var $previewContainer = $('.seo-meta-preview-container');
        var postType = $('select[name="preview_post_type"]').val();
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'seo_meta_manager_preview',
                post_type: postType,
                nonce: $button.data('nonce')
            },
            beforeSend: function() {
                $button.prop('disabled', true).text('Loading...');
                $previewContainer.html('<p class="loading">Loading preview...</p>');
            },
            success: function(response) {
                if (response.success) {
                    $previewContainer.html(response.data);
                } else {
                    $previewContainer.html('<p class="error">Error loading preview</p>');
                }
            },
            complete: function() {
                $button.prop('disabled', false).text('Update Preview');
            }
        });
    });
});