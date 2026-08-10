jQuery(document).ready(function ($) {
	var frame;
	var $imageId = $('#bike-image-id');
	var $image = $('#bike-image');
	var defaultSrc = $('#bikepress-default-image').data('default-src') || '';

	$('#select_image_button').on('click', function (e) {
		e.preventDefault();

		if ( typeof wp === 'undefined' || ! wp.media ) {
			return;
		}

		if (frame) {
			frame.open();
			return;
		}

		frame = wp.media({
			title: 'Select or Upload Bike Image',
			button: { text: 'Use this image' },
			multiple: false
		});

		frame.on('select', function () {
			var attachment = frame.state().get('selection').first().toJSON();
			$imageId.val(attachment.id);
			$image.attr('src', attachment.url);
		});

		frame.open();
	});

	$('#clear_image_button').on('click', function (e) {
		e.preventDefault();
		$imageId.val('0');
		if (defaultSrc) {
			$image.attr('src', defaultSrc);
		}
	});
});
