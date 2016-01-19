<?php
/**
 * AJAX handlers for this theme. Notes for usage:
 *
 * - This file is for front-end AJAX calls; admin AJAX should be in its own file in the /admin directory
 *
 * @package    WordPress
 * @subpackage AllonsYFramework
 */

/*
 * Do not allow this file to be loaded directly
 */

if ( ! function_exists( 'add_action' ) ) {
	die( 'Nothing to do...' );
}

/**
 * AJAX handler for to get the latest post's title.
 * Function is attached to an action starting with "wp_ajax_" for authenticated users or "wp_ajax_nopriv_" for
 * none-authenticated ones. If the action should apply to both, add both actions.
 * After that prefix is the name of the action, sent in the global POST variable with a key of "action".
 * For this example:
 *
 * - Both auth and non-auth users can call this handler so both actions are set
 * - The action used in JS is "smsframe_get_latest_post"
 *
 * @see /assets/js/main.js
 */
function allonsy_ajax_get_latest_post() {

	// Checks that the request came from the current site and has the correct nonce

	check_ajax_referer( 'allonsy-get-latest-post', 'nonce' );

	// All data will come via the global $_POST object with the same keys as were set in JS
	// These variables should not be trusted, must be sanitized

	if ( ! empty( $_POST['post_type'] ) ) {
		$post_type = sanitize_text_field( $_POST['postType'] );
	} else {
		$post_type = 'post';
	}

	// After this, run all queries/checks/etc and output JSON to the page

	$latest_post = new WP_Query(
		array(
			'post_type'      => $post_type,
			'posts_per_page' => 1,
			'no_found_rows'  => TRUE,
			'post_status'  => 'publish',
		)
	);

	$output = '';

	if ( $latest_post->have_posts() ) {
		while ( $latest_post->have_posts() ) {
			$latest_post->the_post();
			$output = __( 'Title', 'allons-y' ) . ': ' . get_the_title();
		}
	} else {
		$output = __( 'No', 'allons-y' ) . ' ' . $post_type . ' ' . __( 'found', 'allons-y' );
	}

	wp_reset_postdata();

	/*
	 * At this point, you'll need to determine how to return the data to JavaScript
	 * It will need to be outputted as text but the options are:
	 *
	 * - Plain text, just handle `data` as such in JS
	 * - HTML, `data` in JS would be appended/prepended to an element
	 * - JSON, `data` would need to be parsed appropriately with JSON.parse(data)
	 *
	 */

	echo sanitize_text_field( $output );

	// All AJAX handlers must end with die() or exit

	die();
}

add_action( 'wp_ajax_allonsy_get_latest_post', 'allonsy_ajax_get_latest_post' );
add_action( 'wp_ajax_nopriv_allonsy_get_latest_post', 'allonsy_ajax_get_latest_post' );