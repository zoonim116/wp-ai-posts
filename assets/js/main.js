function showAlert(message, type) {
    jQuery('.form-table').before(`<div class="alert ${type}">
        <span class="closebtn" onClick="this.parentElement.style.display='none';">×</span>
        <strong>${type === 'fail' ? 'Fail' : 'Success'}!</strong> ${message}.
    </div>`);
    setTimeout(function () {
        jQuery('div.alert').remove();
    }, 10000);
}

jQuery(document).on('click', '.generate-post', function (e) {
    e.preventDefault();
    jQuery(this).remove();
    jQuery('.prompt-wrapper').removeClass('hidden');
});

jQuery(document).on('click', '.validate-api-button', function (e) {
    e.preventDefault();
    let self = jQuery(this);
    const $_loader = jQuery('.submit .loader');
    jQuery.ajax({
        url: wpApiSettings.root +"wp-api/v1/hello",
        beforeSend: function ( xhr ) {
            self.attr('disabled', true);
            jQuery('.loader').css('display', 'inline-block');
            xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
        },
    })
    .error(function () {
        jQuery('.form-table .status').attr('class', 'status invalid').text('Invalid');
        jQuery('.form-table [name="wpai_key_valid"]').val(2);
        $_loader.css('display', 'none');
        self.attr('disabled', false);
    })
    .success(function () {
        jQuery('.form-table .status').attr('class', 'status valid').text('Valid');
        jQuery('.form-table [name="wpai_key_valid"]').val(1);
    })
    .done(function () {
        $_loader.css('display', 'none');
        self.attr('disabled', false);
    });
});

jQuery(document).on('click', '.generate', function (e) {
    e.preventDefault();
    const prompt = jQuery('[name="wpai_prompt"]').val();
    const $_loader = jQuery('.prompt-wrapper .loader');
    if (!prompt.length || prompt.length < 5) {
        jQuery('[name="wpai_prompt"]').after('<div id="prompt-error" class="invalid">Prompt should be more than 5 characters</div>')
    } else {
        jQuery('#prompt-error').remove();
        let self = jQuery(this);
        jQuery.ajax({
            url: wpApiSettings.root + "wp-api/v1/generate",
            data: {prompt},
            method: 'POST',
            beforeSend: function (xhr) {
                self.attr('disabled', true);
                $_loader.css('display', 'inline-block');
                xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
            }
        })
        .error(function (response) {
            $_loader.css('display', 'none');
            showAlert(response?.responseJSON?.data,'fail')
            self.attr('disabled', false);
        })
        .success(function (response) {
            showAlert(`New <a href="${response?.data?.url}" target="_blank">post</a> added`,'success')
            $_loader.css('display', 'none');
            self.attr('disabled', false);
        })
    }
});