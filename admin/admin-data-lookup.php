<?php
/**
 * Lookup functions in wp-admin only
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
 * Are we editing a page of a certain type?
 *
 * @param $type
 *
 * @return bool
 */

function allonsy_is_editing_type( $type ) {

	if ( ! empty( $_GET['post_type'] ) && $type == $_GET['post_type'] ) {
		return TRUE;
	}
	if ( ! empty( $_GET['post'] ) && $type == get_post_type( $_GET['post'] ) ) {
		return TRUE;
	}
	if ( ! empty( $_REQUEST['post_id'] ) && $type == get_post_type( $_REQUEST['post_id'] ) ) {
		return TRUE;
	}

	return FALSE;
}