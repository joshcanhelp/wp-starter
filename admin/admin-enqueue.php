<?php

/**
 * Queuing up JS and CSS in wp-admin
 */
function proper_hook_admin_enqueue_scripts () {

	global $pagenow;

	/*
	 * CSS
	 */

	wp_enqueue_style(
		'proper-admin',
		get_template_directory_uri() . '/assets/css/admin.css',
		FALSE,
		PROPER_THEME_VERSION
	);

	/*
	 * JS
	 */

	wp_enqueue_script(
		'proper-admin',
		get_template_directory_uri() . '/assets/js/admin.js',
		array( 'jquery' ),
		PROPER_THEME_VERSION,
		TRUE
	);

}

add_action( 'admin_enqueue_scripts', 'proper_hook_admin_enqueue_scripts' );
