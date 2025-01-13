jQuery(document).ready(function($) {
    // Basic postal code validation
    $('#postal_code').on('change', function() {
        var code = $(this).val();
        $.ajax({
            url: gobacWizard.ajaxurl,
            type: 'POST',
            data: {
                action: 'validate_postal_code',
                nonce: gobacWizard.nonce,
                postal_code: code
            },
            success: function(response) {
                console.log('Postal code validation:', response);
            }
        });
    });
}); 