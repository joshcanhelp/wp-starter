jQuery(document).ready(function ($) {

	"use strict";

	//
	// Meta image selection
	// See /includes/classes/ProperCustomFields.php for PHP implementation
	//

	// Instantiates the variable that holds the media library frame.
	var meta_image_frame;
	var metaFieldId;

	// Runs when the image button is clicked.
	$('.meta-image-button').click(function (e) {

		e.preventDefault();

		var target = $(e.target);
		metaFieldId = target.attr('data-meta-id');

		// If the frame already exists, re-open it.
		if (meta_image_frame) {
			meta_image_frame.open();
			return;
		}

		// Sets up the media library frame
		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
			title  : 'Select image',
			button : {text: 'Select image'},
			library: {type: 'image'}
		});

		// Runs when an image is selected.
		meta_image_frame.on('select', function () {

			// Grabs the attachment selection and creates a JSON representation of the model.
			var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

			console.log(media_attachment);

			// Sends the attachment URL to our custom image input field.
			$('#' + metaFieldId).val(media_attachment.id);
			$('#' + metaFieldId + '_img').attr('src', media_attachment.url).show();
		});

		// Opens the media library frame.
		meta_image_frame.open();
	});


});
