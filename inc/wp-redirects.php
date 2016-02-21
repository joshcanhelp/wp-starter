<?php
/**
 * All redirects for the front-end
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
 * Keep subscribers out of the back-end
 */
function allonsy_hook_force_front_end() {

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_redirect( home_url() );
		exit;
	}
}

// add_action( 'admin_init', 'allonsy_hook_force_front_end' );

/**
 * If a search only pulls up one page, redirect there
 */

function allonsy_redirect_single_post() {

	if ( ! is_search() ) {
		return;
	}

	global $wp_query;

	if ( $wp_query->post_count == 1 && $wp_query->max_num_pages == 1 ) {
		wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
		exit;
	}


}

add_action( 'template_redirect', 'allonsy_redirect_single_post' );