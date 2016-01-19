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