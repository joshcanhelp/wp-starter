<?php
/**
 * Adjusts the ways that users can get into the back end. User for:
 *
 * - Login and admin redirects
 * - Security changes/improvements
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
 * Allows login by email address or username
 *
 * @param  obj    $user
 * @param  string $username [description]
 * @param  string $password [description]
 *
 * @return boolean
 */

function allonsy_allow_email_login( $user, $username, $password ) {

	if ( is_email( $username ) ) {
		$user = get_user_by( 'email', $username );
		if ( $user ) {
			$username = $user->user_login;
		}
	}

	return wp_authenticate_username_password( NULL, $username, $password );
}

add_filter( 'authenticate', 'allonsy_allow_email_login', 20, 3 );


/**
 * Change username label on wp-login.php to support the above
 *
 * @param $translated_text
 * @param $text
 * @param $domain
 *
 * @return string
 */

function allonsy_gettext_login( $translated_text, $text, $domain ) {

	// Login page only
	if ( 'wp-login.php' != basename( $_SERVER['SCRIPT_NAME'] ) ) {
		return $translated_text;
	}

	if ( 'Username' == $translated_text ) {
		$translated_text = __( 'Email or Username', 'allonsy' );
	}

	return $translated_text;
}

add_filter( 'gettext', 'allonsy_gettext_login', 20, 3 );