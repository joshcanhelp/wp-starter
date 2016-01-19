<?php

/**
 * All non-admin style and script enqueing
 *
 * @package    WordPress
 * @subpackage AllonsYFramework
 */

/**
 * Queuing up JS and CSS for the front-end. Notes:
 *
 * - Use get_template_directory_uri() to pull from the parent theme, child themes use get_stylesheet_directory_uri()
 * - Use conditionals to restrict extra JS and CSS to specific templates and pages
 *
 * @see https://codex.wordpress.org/Function_Reference/wp_enqueue_style
 * @see https://codex.wordpress.org/Function_Reference/wp_enqueue_script
 *
 */
function allonsy_wp_enqueue_scripts() {

	global $wp_scripts;

	/*
	 * Main stylesheet file for all pages, should be final, pre-processed file
	 */

	wp_enqueue_style(
		'allonsy-main',
		get_template_directory_uri() . '/assets/css/main.css',
		FALSE,
		ALLONSY_THEME_VERSION
	);

	/*
	 * Conditionally load HTML5 shim for browsers less than or equal to IE 8
	 * Same $wp_scripts->add_data() technique can be used for conditional IE stylesheets
	 */

	wp_enqueue_script(
		'allonsy-lte-ie8',
		get_template_directory_uri() . '/assets/js/vendor/html5shiv.min.js',
		array(),
		'3.7.3',
		FALSE
	);

	$wp_scripts->add_data(
		'allonsy-lte-ie8',
		'conditional',
		'lte IE 8'
	);

	/*
	 * Main JavaScript file
	 * If using Browserify or other pre-processor, this should point to the final, compiled file
	 */

	wp_enqueue_script(
		'allonsy-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array( 'jquery' ),
		ALLONSY_THEME_VERSION,
		TRUE
	);

	/**
	 * Localize JS file.
	 * Creates a global variable that can be used in the smsframe-main JS file.
	 * Used for:
	 *
	 * - Variables only provided in PHP
	 * - Translation for any text output in JS
	 * - Settings from the Customizer
	 *
	 * @see https://codex.wordpress.org/Function_Reference/wp_localize_script
	 */

	wp_localize_script(
		'allonsy-main', // Same enqueue ID as what you want to localize
		'allonsyLocalVars', // Var name in JS
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'homeUrl' => home_url(),
			'wpDebug' => defined( 'WP_DEBUG' ) ? WP_DEBUG : FALSE,
			'i18n' => array(

				// All text translation strings used in this JS file

				'unknownError' => __( 'Something went wrong, pleas refresh the page and try again.', 'allons-y' )
			)
		)
	);

}

add_action( 'wp_enqueue_scripts', 'allonsy_wp_enqueue_scripts' );

/**
 * Stylesheet for wp-login.php
 * Also use this hook for JS loaded on the login page as well
 */
function allonsy_login_enqueue_scripts() {

	wp_enqueue_style(
		'allonsy-login',
		get_template_directory_uri() . '/assets/css/login.css',
		FALSE,
		ALLONSY_THEME_VERSION
	);

}

add_action( 'login_enqueue_scripts', 'allonsy_login_enqueue_scripts', 100 );

/**
 * Enqueue a controller script for Them Customizer mods
 */
function allonsy_hook_custom_css_preview() {

	wp_enqueue_script(
		'allonsy-theme-mods',
		get_template_directory_uri() . '/assets/js/theme-customizer.js',
		array( 'customize-preview', 'jquery' )
	);
}

add_action( 'customize_preview_init', 'allonsy_hook_custom_css_preview' );
