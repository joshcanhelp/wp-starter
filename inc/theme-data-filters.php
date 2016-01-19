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