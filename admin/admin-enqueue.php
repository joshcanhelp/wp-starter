<?php

/**
 * All admin style and script enqueing
 *
 * @package    WordPress
 * @subpackage AllonsYFramework
 */

/**
 * Queuing up JS and CSS for wp-admin. Notes:
 *
 * - Use get_template_directory_uri() to pull from the parent theme, child themes use get_stylesheet_directory_uri()
 * - Use get_current_screen() to get the current admin screen (usually the .php file you're on)
 *
 * @see https://codex.wordpress.org/Function_Reference/wp_enqueue_style
 * @see https://codex.wordpress.org/Function_Reference/wp_enqueue_script
 *
 */
function allonsy_admin_enqueue_scripts() {

	/*
	 * Main stylesheet file for all admin pages, should be final, pre-processed file
	 */

	wp_enqueue_style(
		'allonsy-admin',
		get_template_directory_uri() . '/assets/css/admin.css',
		FALSE,
		ALLONSY_THEME_VERSION
	);

	/*
	 * Main JavaScript file for all admin pages, should be final, pre-processed file
	 */

	wp_enqueue_script(
		'allonsy-admin',
		get_template_directory_uri() . '/assets/js/admin-main.js',
		FALSE,
		ALLONSY_THEME_VERSION
	);


}

add_action( 'admin_enqueue_scripts', 'allonsy_admin_enqueue_scripts' );