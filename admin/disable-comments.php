<?php

/**
 * Completely nuke comments support
 * Need to also also turn off comments/pingbacks at wp-admin > Settings > Discussion
 * Need to also turn off comments for posts individually (Bulk Edit or DB find/replace)
 *
 * Adapted from: https://www.dfactory.eu/wordpress-how-to/turn-off-disable-comments/
 */

/**
 * Remove post type support for comments for all post types
 */

function proper_disable_comment_support () {
	foreach ( get_post_types() as $type ) {
		if ( post_type_supports( $type, 'comments' ) ) {
			remove_post_type_support( $type, 'comments' );
			remove_post_type_support( $type, 'trackbacks' );
		}
	}
}

add_action( 'admin_init', 'proper_disable_comment_support', 1000 );

/**
 * Always show comments status as closed
 */

add_filter( 'comments_open', '__return_false', 1000, 2 );
add_filter( 'pings_open', '__return_false', 1000, 2 );

/**
 * Never show any existing comments
 */

add_filter( 'comments_array', '__return_empty_array', 1000, 2 );

// Remove comments page in menu
function df_disable_comments_admin_menu () {
	remove_menu_page( 'edit-comments.php' );
}

add_action( 'admin_menu', 'df_disable_comments_admin_menu' );

/**
 * Redirect the comments page in wp-admin
 */
function proper_redirect_comments_page () {
	global $pagenow;
	if ( 'edit-comments.php' === $pagenow ) {
		wp_redirect( admin_url() );
		exit;
	}
}

add_action( 'admin_init', 'proper_redirect_comments_page' );

/**
 * Remove comments meta box from wp-admin
 */

function proper_remove_comments_meta () {
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}

add_action( 'admin_init', 'proper_remove_comments_meta' );

/**
 * Remove the comments link from the admin bar
 */

function proper_remove_comments_admin_link () {
	if ( is_admin_bar_showing() ) {
		remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
	}
}

add_action( 'init', 'proper_remove_comments_admin_link' );