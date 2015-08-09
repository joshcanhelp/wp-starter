<?php

/**
 * Queuing up JS and CSS
 */
function proper_hook_wp_enqueue_scripts () {

	global $wp_scripts;

	/*
	 * CSS
	 */

	wp_enqueue_style(
		'proper-main',
		get_template_directory_uri() . '/assets/css/main.css',
		FALSE,
		PROPER_THEME_VERSION
	);

	/*
	 * JS
	 */

	wp_enqueue_script(
		'proper-lte-ie8',
		get_stylesheet_directory_uri() . '/assets/js/src/libs/html5shiv.min.js',
		FALSE,
		'3.7.3-pre',
		FALSE
	);

	$wp_scripts->add_data( 'proper-lte-ie8', 'conditional', 'lte IE 8' );

	wp_enqueue_script(
		'proper-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array( 'jquery' ),
		PROPER_THEME_VERSION,
		TRUE
	);


	wp_localize_script(
		'proper-main',
		'properLocalVars',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' )
		)
	);

}

add_action( 'wp_enqueue_scripts', 'proper_hook_wp_enqueue_scripts' );

/**
 * Queuing up JS and CSS on wp-login
 */
function proper_hook_login_enqueue_scripts () {

	/*
	 * CSS
	 */

	wp_enqueue_style(
		'pcc-login',
		get_template_directory_uri() . '/assets/css/login.css',
		FALSE,
		PROPER_THEME_VERSION
	);

}

add_action( 'login_enqueue_scripts', 'proper_hook_login_enqueue_scripts', 100 );
