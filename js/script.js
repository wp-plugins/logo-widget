jQuery(document).ready(function ($) {
    $(document).on('click', '.c1-logo-widget-upload-media', function (e) {
        e.preventDefault();
        var t =  $(e.target).parents('.widget');
        var custom_uploader = wp.media({
            title: 'Widget Logo',
            button: {
                text: 'Choose logo'
            },
            multiple: false
        }).on('select', function () {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            t.find('.c1-logo-widget-image-preview').attr('src', attachment.url);
            t.find('.c1-logo-widget-image').val(attachment.url);
        }).open();
    });
});