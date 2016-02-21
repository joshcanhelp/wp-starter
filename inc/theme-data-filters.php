<?php
/**
 * All functions here should be associated to filters that modify data, not output content or HTML
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
 * Change the login form header URL to the homepage
 * If there is a redirect_to URL param, it will use that instead
 *
 * @return string|void
 */

function allonsy_login_logo_url() {

	if ( ! empty( $_GET['redirect_to'] ) ) {

		$redirect_url = $_GET['redirect_to'];
		$redirect_url = urldecode( $redirect_url );
		$redirect_url = esc_url( $redirect_url );

		return $redirect_url;
	}

	return home_url();
}

add_filter( 'login_headerurl', 'allonsy_login_logo_url' );


/**
 * Adjust the length of all excerpts
 *
 * @param int $length
 *
 * @return int
 */

function allonsy_filter_excerpt_length( $length ){
	return 20;
}

add_filter( 'excerpt_length', 'allonsy_filter_excerpt_length', 999 );


/**
 * Change what comes after excerpts
 *
 * @param string $more
 *
 * @return int
 */

function allonsy_filter_excerpt_more( $more ){
	return $more . ' &rsaquo;';
}

add_filter( 'excerpt_more', 'allonsy_filter_excerpt_more', 999 );