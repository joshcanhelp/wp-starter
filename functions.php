<?php
/**
 * Main bootstrap file for this theme. Notes for usage:
 *
 * - This file is use for theme setup and file includes
 * - All functions defined here should be tied to the init, after_theme_setup, or activation hook
 * - Additional function definitions should go in a required file
 * - All functions and constants should be namespaced the same, "allonsy_" is used throughout
 * - All relative path mentions in comments are relative to the theme root (where this file is)
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
 * Define constants here.
 * Constants are values used throughout the theme but do not change.
 */

/*
 * Theme version used for:
 *
 * - Asset cache breaking
 * - Upgrade processing
 *
 * If this changes, make sure to also change:
 *
 * - style.css
 * - package.json
 */

define( 'ALLONSY_THEME_VERSION', '0.0.1' );
define( 'ALLONSY_THEME_VERSION_OPT_NAME', 'allonsy_theme_version' );

/*
 * Easy domain check, often used for determining:
 *
 * - Whether or not analytics should show
 * - Setting a different favicon for staging/local/production
 */

define( 'ALLONSY_CURRENT_DOMAIN', ! empty( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '' );

/**
 * Definitive theme root path
 */

define( 'ALLONSY_THEME_ROOT', dirname( __FILE__ ) );

/*
 * Required files. Best practices here are:
 *
 * - Require main files here, subsets should be required in those main files (like widgets, admin functions, etc)
 * - File description should be at the top of the required file to reduce document duplication
 */

// Admin functionality

require_once( 'admin/admin-functions.php' );

// Base custom theme functionality

require_once( 'inc/data-lookup.php' );
require_once( 'inc/data-transformation.php' );
require_once( 'inc/shortcodes.php' );
require_once( 'inc/theme-display-hooks.php' );
require_once( 'inc/theme-data-filters.php' );
require_once( 'inc/theme-display-functions.php' );
require_once( 'inc/wp-enqueue.php' );
require_once( 'inc/wp-ajax.php' );
require_once( 'inc/wp-redirects.php' );

require_once( 'inc/header-footer.php' );
require_once( 'inc/classes/class-allonsy-log-it.php' );

// Custom post type includes

require_once( 'inc/cpt/cpt-1.php' );

// Widgets!

require_once( 'inc/widgets/widget-master.php' );


/**
 * Run activation process
 *
 * This sets a flag when the theme is activated that can be used anywhere to note that the theme was just activated.
 * Make sure that when processing is complete, this option is deleted or set to 0.
 *
 * Used for things like:
 *
 * - Force rewrite rebuilding
 * - Setup new DB tables
 *
 * @see: https://developer.wordpress.org/reference/functions/register_activation_hook/
 */

function allonsy_plugin_activation() {

	add_option( 'allonsy_plugin_activated', 1, '', 'no' );
}

register_activation_hook( __FILE__, 'allonsy_plugin_activation' );


/**
 * Add theme-specific functionality.
 *
 * "This is the first action hook available to themes, triggered immediately after the active theme's
 * functions.php file is loaded."
 *
 * @see http://codex.wordpress.org/Plugin_API/Action_Reference
 *
 * "This hook is called during each page load, after the theme is initialized.
 * It is generally used to perform basic setup and registration actions for a theme."
 *
 * @see http://codex.wordpress.org/Plugin_API/Action_Reference/after_setup_theme
 *
 * @see https://codex.wordpress.org/Function_Reference/add_theme_support#Addable_Features
 */

function allonsy_hook_after_setup_theme() {


	/**
	 * Allow WordPress to manage the <title> tag output.
	 * This should be enabled for all sites.
	 * Omit a <title> tag in header.php and make sure that wp_head() is being called.
	 * Output can still be filtered using wp_title.
	 *
	 * @see https://codex.wordpress.org/Title_Tag
	 *
	 * @since 4.1
	 */

	add_theme_support( 'title-tag' );


	/**
	 * Enable Featured Image upload and assignment for specific post types.
	 * You can leave the second argument blank if you register custom post types with a post thumbnail.
	 * This function allows overall control over the post types that allow thumbnails.
	 *
	 * @see https://codex.wordpress.org/Post_Thumbnails
	 *
	 * @since 2.9
	 */

	add_theme_support( 'post-thumbnails' );


	/**
	 * Enable support for logo/header upload in Customizer.
	 * This should be enabled to allow users to upload their own logo or header image.
	 * Set default-image to a static image URL included in the theme to ensure there is always an image.
	 * Set header-text as FALSE to disable text output.
	 * Set width and height to enforce a specific size.
	 *
	 * @see https://codex.wordpress.org/Custom_Headers
	 *
	 * @since 3.4
	 */

	add_theme_support(
		'custom-header',
		array(
			'header-text'   => FALSE,
			'default-image' => FALSE,
		)
	);


	/**
	 * Enable support for background color and background image upload in Customizer.
	 * This will output color and/or a background image on the <body> tag automatically.
	 * Set default-color to a hex code without a hash sign, if desired
	 * Set default-image to a valid URL of an image, if desired
	 *
	 * @see https://codex.wordpress.org/Custom_Backgrounds
	 *
	 * @since 3.4
	 */

	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'ffffff',
			'default-image' => FALSE,
		)
	);


	/**
	 * Enable specific post formats.
	 * If post formats have not be designed or will not be templated differently, remove this.
	 *
	 * @see   https://codex.wordpress.org/Post_Formats
	 *
	 * @since 3.1
	 */

	add_theme_support(
		'post-formats',
		array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'status',
			'audio',
		)
	);


	/**
	 * Automatically outputs RSS feed links in the header for posts and comments
	 *
	 * @since 3.0
	 */

	add_theme_support( 'automatic-feed-links' );


	/**
	 * Enforce HTML5 output in specific generated HTML.
	 * Best to leave this on.
	 *
	 * @since 3.6
	 *
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);


	/**
	 * Register a new image size used by this theme.
	 * Use namespaced slugs for these image sizes so as not to collide with a plugin.
	 * Use these sparingly and, when possible, rely on core sizes set as wp-admin > Settings > Media.
	 * Each new size will create another image for each upload, eating up server space and slowing down the upload.
	 * Make sure to add a default image for each one added to /assets/img/default-img-size-SIZE-ID.png
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_image_size/
	 * @see allonsy_get_post_img_url()
	 *      
	 * @since 2.9
	 */

	//add_image_size( 'smsframe-thumb-small', 100, 100, TRUE );
	//add_image_size( 'smsframe-thumb-large', 200, 200, TRUE );


	/**
	 * Declare navigation menu areas.
	 * These are nav areas built into the theme and output somewhere.
	 * Widget menus do not need to be declared here.
	 *
	 * @since 3.0.0
	 */

	register_nav_menus(
		array(
			'main'   => __( 'Main Menu', 'allons-y' ),
			'footer' => __( 'Footer Menu', 'allons-y' ),
		)
	);


	/**
	 * Removes the meta tag indicating the generator of the page.
	 * We do this for security in case a WordPress site is behind on an update.
	 */

	remove_action( 'wp_head', 'wp_generator' );


	/**
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on thie one, use a find and replace
	 * to change 'smsframe' to the name of your theme in all the template files
	 *
	 * @see https://codex.wordpress.org/Function_Reference/load_theme_textdomain
	 */
	load_theme_textdomain( 'smsframe', get_template_directory() . '/languages' );


	/**
	 * This section is used to determine if any repair processing should happen.
	 * If a theme is one specific version and, say, settings structure changed in the database, it's good
	 * to already have this in place and running.
	 * If adding to a new theme, just check for an empty version stored in the DB.
	 */

	$version = floatval( get_option( ALLONSY_THEME_VERSION_OPT_NAME, '' ) );

	/**
	 * If stored version is less than the version of the current theme, continue
	 *
	 * @see http://php.net/manual/en/function.version-compare.php
	 */

	if ( empty( $version ) || version_compare( $version, ALLONSY_THEME_VERSION ) < 0 ) {

		/*
		 * Best thing to do here is include a repair file like, say,  /admin/upgrade-repairs.php that checks for
		 * inconsistencies and makes changes.
		 */
	}

	/*
	 * Update the stored version if different from the current one
	 */

	if ( $version != ALLONSY_THEME_VERSION ) {
		update_option( ALLONSY_THEME_VERSION_OPT_NAME, ALLONSY_THEME_VERSION );
	}

}

add_action( 'after_setup_theme', 'allonsy_hook_after_setup_theme' );


/**
 * Init hook actions for the theme.
 * Actions/filters that go here instead of the after_theme_setup hook might be:
 *
 * - Actions that override ones in a particular plugin
 * - Actions that need to run after plugin init actions
 */

function allonsy_hook_init() {

	/**
	 * Adds the excerpt field UI to pages, which do not have this field by default.
	 * Good for pages that link to child pages and need to include a description.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/add_post_type_support
	 *
	 * @since 3.0
	 */

	add_post_type_support( 'page', array( 'excerpt', 'thumbnail' ) );

	/**
	 * Remove custom field UI on pages and posts.
	 * Custom field output needs to be built into the theme and ACF is often used for creating field UI.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/remove_post_type_support
	 *
	 * @since 3.0
	 */

	remove_post_type_support( 'post', 'custom-fields' );
	remove_post_type_support( 'page', 'custom-fields' );

}

add_action( 'init', 'allonsy_hook_init', 100 );



