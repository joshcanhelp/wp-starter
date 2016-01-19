<?php
/**
 * Functions in this file should be tied to add_action and output HTML
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
 * Add uploaded header logo to login form
 * This adds the image in a way that should work in most cases but is fit into a 84px square box
 * Tweak background-size, width, and height in the selector below
 */

function allonsy_hook_login_head() {

	if ( $header_img = get_header_image() ) {
		printf(
			'<style type="text/css">body.login h1 a {background-image: url("%s")}</style>',
			esc_url( $header_img )
		);
	} else {
		echo '<style type="text/css">body.login h1 {display: none}</style>';
	}
}

add_action( 'login_head', 'allonsy_hook_login_head' );