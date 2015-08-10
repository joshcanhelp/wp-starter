<?php

/*
 * Constants, to be consistent
 */

// Make sure to update package.json and font-face includes when this changes
define( 'PROPER_THEME_VERSION', '0.1' );

define( 'PROPER_CURRENT_DOMAIN', isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '' );

// TODO: Review domains
define( 'PROPER_LOCAL_DOMAIN', 'localhost' );
define( 'PROPER_STAGE_DOMAIN', 'stage.pccomposites.com' );
define( 'PROPER_PROD_DOMAIN', 'www.pccomposites.com' );


/*
 * Where are we?
 */

switch ( PROPER_CURRENT_DOMAIN ) {

	case PROPER_LOCAL_DOMAIN:
		define( 'PROPER_ENV', 'local' );
		break;

	case PROPER_STAGE_DOMAIN:
		define( 'PROPER_ENV', 'stage' );
		break;

	case PROPER_PROD_DOMAIN:
		define( 'PROPER_ENV', 'prod' );
		break;

	default:
		define( 'PROPER_ENV', 'unknown' );
}

/*
 * Admin modifications
 */

require_once( 'admin/admin-functions.php' );

/*
 * Other includes
 */

require_once( 'includes/data-lookup.php' );
require_once( 'includes/data-transformation.php' );
require_once( 'includes/wp-enqueue.php' );
require_once( 'includes/display-functions.php' );
require_once( 'includes/shortcodes.php' );
require_once( 'includes/widgets/widget-master.php' );

require_once( 'includes/classes/ProperCustomFields.php' );
require_once( 'includes/classes/ProperSettings.php' );

/*
 * Not always needed
 */

// require_once( 'includes/cpt/cpt-1.php' );
// require_once( 'includes/header-footer.php' );
// require_once( 'includes/redirects.php' );

/*
 * WP-CLI add-ons
 */

// if ( defined( 'WP_CLI' ) && WP_CLI ) require_once( 'admin/wp-cli.php' );

/**
 * Theme init
 * TODO: Review to see if these are necessary
 */

function proper_hook_init () {

	// TODO: Change option name to match theme slug
	$version = floatval( get_option( 'proper_start_current_version' ) );

	// Process any upgrade scripts
	if ( empty( $version ) || $version < FEAREY_GROUP_THEME_VERSION ) {
		// TODO: add repair functions or includes
	}

	if ( $version != FEAREY_GROUP_THEME_VERSION ) {
		// TODO: Change option name
		update_option( 'proper_start_current_version', FEAREY_GROUP_THEME_VERSION );
	}

	// Adding excerpts to Page content
	// https://codex.wordpress.org/Function_Reference/add_post_type_support
	add_post_type_support( 'page', array( 'excerpt' ) );

	// Remove support for customer fields on products
	// https://codex.wordpress.org/Function_Reference/remove_post_type_support
	remove_post_type_support( 'page', 'custom-fields' );

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on PROPER Start, use a find and replace
	 * to change 'proper-start' to the name of your theme in all the template files
	 *
	 * https://codex.wordpress.org/Function_Reference/load_theme_textdomain
	 */
	// TODO: Change text domain
	load_theme_textdomain( 'proper-start', get_template_directory() . '/languages' );
}

add_action( 'init', 'proper_hook_init' );


/**
 * Add features to the theme
 * TODO: Review added features
 */
function proper_hook_after_setup_theme () {

	// Add support for logo image upload
	add_theme_support( 'custom-header', array(
		'header-text'   => FALSE,
		'default-image' => FALSE
	) );

	// Background image and color support
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff',
	) );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails
	add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );

	// Output valid HTML5
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption'
	) );

	// Enable support for Post Formats
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio'
	) );

	// New image sizes
	add_image_size( 'product-thumb', '240', '140', TRUE );

	// Declare menu locations
	register_nav_menus( array(
		'main'   => __( 'Main Menu', 'proper-start' ),
		'footer' => __( 'Footer Menu', 'proper-start' ),
	) );

	// No generator tag
	remove_action( 'wp_head', 'wp_generator' );

}

add_action( 'after_setup_theme', 'proper_hook_after_setup_theme' );


