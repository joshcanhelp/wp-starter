/* globals jQuery, console, alert, wp */

(function ($) {
	'use strict';

	/**
	 * Each theme mod setting that changes styling should have a wp.customize component below
	 * The JS here should be replicating exactly what the setting does when saved
	 * Best thing to do is to implement in the theme then replicate here in JS (if needed)
	 * If it requires many HTML/attr changes, leave 'transport' in $wp_customize->add_setting as 'refresh'
	 */

	// Base font color

	wp.customize('site_base_color', function (value) {
		value.bind(function (to) {
			$('body').css({color: to});
		});
	});

	// Base link color

	wp.customize('site_link_color', function (value) {
		value.bind(function (to) {
			$('body a').css({color: to});
		});
	});

})(jQuery);