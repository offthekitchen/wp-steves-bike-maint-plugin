jQuery(document).ready(function($) {
    var frame;

    $('#select_image_button').on('click', function(e) {
        e.preventDefault();

        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({
            title: 'Select or Upload Media File',
            button: {
                text: 'Use this media'
            },
            multiple: false // Set to true for multiple file selection
        });

        // When an image is selected, run a callback
        frame.on('select', function() {
            console.log('Chose Image');
            // Get media attachment details from the selection
            var attachment = frame.state().get('selection').first().toJSON();
            console.log('Image ID ' +attachment.id );

            // Do something with the attachment data (e.g., display a preview, save the ID)
            $('#bike-image-id').val(attachment.id);
            console.log('Changing IMAGE URL TO ' + attachment.url);
            $('#bike-image').attr('src', attachment.url);
        });

        // Open the media frame
        frame.open();
    });
});