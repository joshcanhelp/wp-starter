/* globals jQuery, console, alert, allonsyLocalVars */

(function ($) {

	'use strict';

	$(document).ready(function () {

		// Little easier to type over and over

		var localVars = allonsyLocalVars;
		var translate = localVars.i18n;

		// Testing out variables

		console.log(localVars.ajaxUrl);
		console.log(localVars.homeUrl);

		// Simple framework for AJAX calls

		$('#js-allonsy-get-latest-post').click(function (e) {

			// Don't do the default link click behavior

			e.preventDefault();

			// Cache variables

			var thisLink = $(this);

			// Start setting up the POST object to be sent to the backend via AJAX

			var postData = {

				// This is how to tell the backend what to do when this request is received
				// See /inc/wp-ajax.php, smsframe_ajax_get_latest_post()
				// This var should match the action name after wp_ajax or wp_ajax_nopriv

				action: 'allonsy_get_latest_post',

				// Must have a valid nonce for the action being checked in the handler with check_ajax_referer
				// Second argument in check_ajax_referer should be the key used here, "nonce"
				// In this case, we're setting a data attribute with the nonce we're checking

				nonce: thisLink.attr('data-nonce'),

				// The remaining keys here are data that the handler needs, if required

				postType: 'post'

			};


			// AJAX call is a POST to the URL set in the localized variables

			$.post(
				localVars.ajaxUrl,
				postData
				)
				.done(function (data) {

					// Call succeeded

					if ('-1' === data || -1 === data) {

						// check_ajax_referer() sends -1 if a nonce fails

						alert(translate.unknownError);

					} else {

						// Success!

						alert(data);

					}
				})

				.fail(function (data) {

					// Call failed

					alert(translate.unknownError);
					console.log(data);
				});

		});


	});

})(jQuery);


(function (sr) {

	'use strict';

	// debouncing function from John Hann
	// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
	var debounce = function (func, threshold, execAsap) {
		var timeout;

		return function debounced() {
			var obj = this, args = arguments;

			function delayed() {
				if (!execAsap) {
					func.apply(obj, args);
				}
				timeout = null;
			}

			if (timeout) {
				clearTimeout(timeout);
			}
			else if (execAsap) {
				func.apply(obj, args);
			}

			timeout = setTimeout(delayed, threshold || 100);
		};
	};
	// smartresize
	jQuery.fn[sr] = function (fn) {
		return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr);
	};

})('smartresize');
