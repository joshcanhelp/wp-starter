<?php
/**
 * Main functions file for wp-admin. Notes for usage:
 *
 * - Use this file like /functions.php; this file should require other files for pleasant architecture
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

/*
 * Required files. Best practices here are:
 *
 * - Require admin-specific files here
 * - File description should be at the top of the required file to reduce document duplication
 */

require_once( 'admin-access.php' );
require_once( 'admin-display-filters.php' );
require_once( 'admin-enqueue.php' );
require_once( 'admin-profile.php' );
require_once( 'theme-customizer.php' );
require_once( 'admin-data-lookup.php' );

// TODO: Needs work in general
// require_once( 'wysiwyg-editor-functions.php' );

// Uncomment to remove comments completely
// require_once( 'disable-comments.php' );

/*
 * WP-CLI add-ons
 *
 * This is not typically necessary but nice to have a template if you do.
 * Comment out the require_once() statement if not being used.
 *
 * Custom WP-CLI scripts are typically used for:
 *
 * - Migrations
 * - Repeated maintenance tasks like stage refreshing or dummy content
 */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once( 'wp-cli.php' );
}

/**
 * Output all non-system meta fields
 * TODO: Where to add this?
 */
function allonsy_output_meta() {
	foreach ( get_post_meta( get_the_ID() ) as $meta_key => $meta_vals ) {
		if ( '_' !== $meta_key[0] ) {
			printf(
				'<p><strong>%s</strong><br>%s</p>',
				$meta_key,
				implode( '<br>', $meta_vals )
			);
		}
	}
}