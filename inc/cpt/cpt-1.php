<?php


/**
 * Declare the custom post type and custom taxonomy
 *
 * @see https://codex.wordpress.org/Function_Reference/register_post_type
 * @see https://codex.wordpress.org/Function_Reference/register_taxonomy
 */
function allonsy_hook_allonsy_cpt () {

	register_post_type(

		// CPT machine name, I typically use dashes only for this

		'cpt-1',

		// Args!

		[
			// Description
			'description' => 'Stuff about this content type',

			// wp-admin text throughout
			'labels' => [
				'name' => _x( 'CPTs', 'post type general name', 'allons-y' ),
				'singular_name' => _x( 'CPT', 'post type singular name', 'allons-y' ),
				'add_new' => _x( 'Add CPT', 'cpt-1', 'allons-y' ),
				'add_new_item' => __( 'Add New CPT', 'allons-y' ),
				'edit_item' => __( 'Edit CPT', 'allons-y' ),
				'new_item' => __( 'New CPT', 'allons-y' ),
				'view_item' => __( 'View CPT', 'allons-y' ),
				'search_items' => __( 'Search CPTs', 'allons-y' ),
				'not_found' => __( 'No CPTs found', 'allons-y' ),
				'not_found_in_trash' => __( 'No CPTs found in Trash', 'allons-y' ),
				'all_items' => __( 'All CPTs', 'allons-y' ),
				'archives' => __( 'CPT archives', 'allons-y' ),
				'insert_into_item' => __( 'Insert into CPT', 'allons-y' ),
				'uploaded_to_this_item' => __( 'Uploaded to this CPT', 'allons-y' ),
				'featured_image' => __( 'CPT Image', 'allons-y' ),
				'set_featured_image' => __( 'Set CPT Image', 'allons-y' ),
				'remove_featured_image' => __( 'Remove CPT Image', 'allons-y' ),
				'use_featured_image' => __( 'Use CPT Image', 'allons-y' ),
				'menu_name' => __( 'Manage CPTs', 'allons-y' ),

				// Hierarchical post types only
				'parent_item_colon' => 'Parent CPT 1:',
			],

			// Should this content type have a public presence?
			// If set to false, viewing it should show a 404
			'public' => TRUE,

			// Keep this out of search results?
			'exclude_from_search' => TRUE,

			// Create an admin UI to manage this post type
			'show_ui' => TRUE,

			// Show in the wp-admin menu?
			// Second option creates a submenu
			'show_in_menu' => TRUE,
			// 'show_in_menu'  => 'edit.php?post_type=page',

			// Create archive pages
			'has_archive' => TRUE,

			// Allows a parent?
			'hierarchical' => FALSE,

			// Should the permalink be changed?
			// Rebuild permalinks after changing/adding!
			'rewrite' => array(
				'slug' => 'this/thing'
			),

			/*
			5 - below Posts
			10 - below Media
			15 - below Links
			20 - below Pages
			25 - below comments
			60 - below first separator
			65 - below Plugins
			70 - below Users
			75 - below Tools
			80 - below Settings
			100 - below second separator
			*/
			'menu_position' => 27,

			// Path to an icon or dashicon font
			'menu_icon' => 'dashicons-star-filled',

			// What capabilities should users have?
			'capability_type' => 'post',

			// Creates an automatically managed CPT, no way to add/edit
			//'capabilities' => array(
			//	'create_posts' => FALSE,
			//	'delete_posts' => FALSE
			//),
			//'map_meta_cap' => TRUE,

			// Remove all unneeded
			'supports' => [
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'trackbacks',
				'custom-fields',
				'comments',
				'revisions',
				'page-attributes',
				'post-formats'
			],
		]
	);

	register_taxonomy(
		'cpt-1-type',
		[ 'cpt-1' ],
		[
			'description' => __( 'A quick description of this taxonomy', 'allons-y' ),
			'labels' => [
				'name' => _x( 'CPT 1 Types', 'taxonomy general name', 'allons-y' ),
				'singular_name' => _x( 'CPT 1 Type', 'taxonomy singular name', 'allons-y' ),
				'menu_name' => __( 'CPT 1 Types', 'allons-y' ),
				'all_items' => __( 'CPT 1 Types', 'allons-y' ),
				'edit_item' => __( 'Edit CPT 1 Type', 'allons-y' ),
				'view_item' => __( 'View CPT 1 Type', 'allons-y' ),
				'update_item' => __( 'Update CPT 1 Type', 'allons-y' ),
				'add_new_item' => __( 'Add New CPT 1 Type', 'allons-y' ),
				'new_item_name' => __( 'New CPT 1 Type', 'allons-y' ),
				'search_items' => __( 'Search CPT 1 Types', 'allons-y' ),
				'popular_items' => __( 'Popular CPT 1 Types', 'allons-y' ),
				'add_or_remove_items' => __( 'Add or remove CPT 1 Types', 'allons-y' ),
				'not_found' => __( 'No CPT 1 Types found', 'allons-y' ),

				// Hierarchical taxonomies only
				'parent_item' => __( 'Parent CPT 1 Type', 'allons-y' ),
				'parent_item_colon' => __( 'Parent CPT 1 Type:', 'allons-y' ),

				// Non-hierarchical taxonomies only
				'separate_items_with_commas' => __( 'Separate Types with commas', 'allons-y' ),
				'choose_from_most_used' => __( 'Choose from most-used Types', 'allons-y' ),
			],

			// Tag-style or category-style
			'hierarchical' => TRUE,

			'public' => FALSE,
			'show_ui' => TRUE,
			'show_in_menu' => TRUE,
			'show_in_nav_menus' => FALSE,
			'show_tagcloud' => FALSE,
			'show_in_quick_edit' => TRUE,
			'show_admin_column' => TRUE,

			// Handle permalinks
			'rewrite' => [
				'slug' => 'document-type'
			],

			// Capabilities
			'capabilities' => [
				'manage_terms' => 'manage_categories',
				'edit_terms' => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'edit_posts',
			]

			// Hierarchical default
			//'meta_box_cb' => 'post_categories_meta_box',

			// Non-hierarchical default
			//'meta_box_cb' => 'post_tags_meta_box',
		]
	);

}

 add_action( 'init', 'allonsy_hook_allonsy_cpt', 20 );


/**
 * Are we on the CPT 1 edit page?
 *
 * @return bool
 */
function allonsy_is_editing_cpt1() {

	if ( ! empty( $_GET['post_type'] ) && 'cpt-1' == $_GET['post_type'] ) {
		return TRUE;
	}
	if ( ! empty( $_GET['post'] ) && 'cpt-1' == get_post_type( $_GET['post'] ) ) {
		return TRUE;
	}
	if ( ! empty( $_REQUEST['post_id'] ) && 'cpt-1' == get_post_type( $_REQUEST['post_id'] ) ) {
		return TRUE;
	}

	return FALSE;
}

/**
 * Add custom post columns for cpt-1
 * Make sure to change the filter name to match the CPT
 *
 * @param $defaults
 *
 * @return mixed
 */

function allonsy_product_post_columns ( $defaults ) {

	$defaults['allonsy_yes_no'] = 'Meta Field';
	$defaults['thumbnail']          = 'Image';

	// Remove standard publish date

	//unset( $defaults['date'] );

	return $defaults;

}
 add_filter( 'manage_cpt-1_posts_columns', 'allonsy_product_post_columns' );


/**
 * Populate the post edit column with the custom post meta data
 *
 * @param $col
 * @param $pid
 */

function allonsy_product_custom_columns ( $col, $pid ) {

	switch ( $col ) :

		case 'allonsy_yes_no':
			echo allonsy_tpl_meta( 'class_seats_available', $pid ) ?
				'<span class="dashicons dashicons-yes" style="color: green"></span>' :
				'<span class="dashicons dashicons-no-alt" style="color: red"></span>';
			break;

		case 'thumbnail':
			echo has_post_thumbnail() ?
				get_the_post_thumbnail( $pid, [ 100, 100 ] ) :
				'<em>' . __( 'No featured image', 'allons-y' ) . '</em>';
			break;

	endswitch;

}

add_action( 'manage_posts_custom_column', 'allonsy_product_custom_columns', 10, 2 );


/**
 * Change the title placeholder on class edit screens
 *
 * @param $title
 *
 * @return string
 */

function allonsy_cpt1_enter_title_here( $title ) {

	if ( allonsy_is_editing_cpt1() ) {
		$title = 'CPT Name';
	}

	return $title;
}

add_filter( 'enter_title_here', 'allonsy_cpt1_enter_title_here' );
