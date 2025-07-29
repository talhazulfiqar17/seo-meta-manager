jQuery(document).ready(function($) {
    function updateCounter($field, $counter, $progressBar, optimalMin, optimalMax, warningMin, warningMax) {
        var length = $field.val().length;
        $counter.text(length);
        
        // Update progress bar
        var percentage = Math.min(100, (length / optimalMax) * 100);
        $progressBar.css('width', percentage + '%');
        
        // Update colors based on length
        if (length >= optimalMin && length <= optimalMax) {
            $progressBar.css('background-color', seoMetaManager.colors.success);
        } else if ((length >= warningMin && length < optimalMin) || (length > optimalMax && length <= warningMax)) {
            $progressBar.css('background-color', seoMetaManager.colors.warning);
        } else {
            $progressBar.css('background-color', seoMetaManager.colors.error);
        }
    }
    
    $('.seo-meta-title-field, .seo-meta-description-field').each(function() {
        var $field = $(this);
        var $counter = $field.siblings('.seo-meta-counter').find('.character-count');
        var $progressBar = $field.siblings('.seo-meta-counter').find('.progress-bar');
        var optimalMin = parseInt($field.data('optimal-min'));
        var optimalMax = parseInt($field.data('optimal-max'));
        var warningMin = parseInt($field.data('warning-min'));
        var warningMax = parseInt($field.data('warning-max'));
        
        // Set optimal max in the counter display
        $field.siblings('.seo-meta-counter').find('.optimal-max').text(optimalMax);
        
        // Initial update
        updateCounter($field, $counter, $progressBar, optimalMin, optimalMax, warningMin, warningMax);
        
        // Update on keyup
        $field.on('keyup', function() {
            updateCounter($field, $counter, $progressBar, optimalMin, optimalMax, warningMin, warningMax);
        });
    });
});