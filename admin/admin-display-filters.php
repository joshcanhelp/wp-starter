<?php
/**
 * Filters to change how certain parts of the admin appear
 *
 * @package PackageName
 */

/*
 * Do not allow this file to be loaded directly
 */

if ( ! function_exists( 'add_action' ) ) {
	die( 'Nothing to do...' );
}


/**
 * View account link on User list
 *
 * @param $actions
 * @param $user_object
 *
 * @return mixed
 */
function allonsy_admin_view_user_link( $actions, $user_object ) {

	// Temporarily remove the delete link so we can add it to the end

	if ( isset( $actions['delete'] ) ) {
		$delete_link = $actions['delete'];
		unset( $actions['delete'] );
	}

	// Add a link to the author page

	$actions['view profile'] = sprintf(
		'<a href="%s">%s</a>',
		esc_url( get_author_posts_url( $user_object->ID ) ),
		__( 'View Posts', 'allons-y' )
	);

	// Add back the delete link

	if ( ! empty( $delete_link ) ) {
		$actions['delete'] = $delete_link;
	}

	return $actions;

}

add_filter( 'user_row_actions', 'allonsy_admin_view_user_link', 1, 2 );

/**
 * Change admin UI text
 *
 * @param $translated_text
 * @param $text
 * @param $domain
 *
 * @return string
 */

function allonsy_gettext_admin_misc( $translated_text, $text, $domain ) {

	// Beer edit page only

	if ( allonsy_is_editing_type( 'page' ) ) {

		if ( 'Featured Image' === $translated_text ) {
			$translated_text = __( 'Header image', 'allons-y' );
		}

		if ( 'Set featured image' === $translated_text ) {
			$translated_text = __( 'Set header image', 'allons-y' );
		}
	}

	return $translated_text;
}

add_filter( 'gettext', 'allonsy_gettext_admin_misc', 20, 3 );