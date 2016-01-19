<?php


/**
 * Declare the custom post type and custom taxonomy
 */
function allonsy_hook_allonsy_cpt () {

	$labels = array(
		'name'               => _x( 'Tests', 'post type general name', 'allons-y' ),
		'singular_name'      => _x( 'Test', 'post type singular name', 'allons-y' ),
		'add_new'            => _x( 'Add Test', 'test_content', 'allons-y' ),
		'add_new_item'       => __( 'Add New Test', 'allons-y' ),
		'edit_item'          => __( 'Edit Test', 'allons-y' ),
		'new_item'           => __( 'New Test', 'allons-y' ),
		'all_items'          => __( 'All Tests', 'allons-y' ),
		'view_item'          => __( 'View Test', 'allons-y' ),
		'not_found'          => __( 'No Tests found', 'allons-y' ),
		'not_found_in_trash' => __( 'No Tests found in Trash', 'allons-y' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'TEST content'

	);

	$args = array(

		// CPT description
		'description' => 'Stuff about this content type',

		// From above
		'labels'              => $labels,

		// Should this content type have a public presence?
		// If set to false, viewing it should show a 404
		'public'              => TRUE,

		// Keep this out of search results?
		'exclude_from_search' => TRUE,
		
		// Create an admin UI to manage this post type
		'show_ui'             => TRUE,
		
		// Show in the wp-admin menu?
		// Second option creates a submenu
		'show_in_menu'        => TRUE,
		// 'show_in_menu'  => 'edit.php?post_type=page',
		
		// Create archive pages
		'has_archive'         => TRUE,
		
		// Allows a parent?
		'hierarchical'        => FALSE,

		// Should the permalink be changed?
		// Rebuild permalinks after changing/adding!
		'rewrite'             => array(
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
		'menu_position'       => 27,

		// Path to an icon or dashicon font
		'menu_icon'           => 'dashicons-universal-access-alt',
		
		// What capabilities should users have?
		'capability_type' => 'post',
		
		// Creates an automatically managed CPT, no way to add/edit
		//'capabilities' => array(
		//	'create_posts' => FALSE,
		//	'delete_posts' => FALSE
		//),
		//'map_meta_cap' => TRUE,

		// Remove all unneeded
		'supports'            => array(
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
		),

	);

	register_post_type( 'cpt-1', $args );

}

 add_action( 'init', 'allonsy_hook_allonsy_cpt', 20 );

/**
 * Add custom post columns for products
 *
 * @param $defaults
 *
 * @return mixed
 */
function allonsy_product_post_columns ( $defaults ) {

	$defaults['allonsy_meta_field'] = 'Meta Field';
	$defaults['thumbnail']          = 'Image';

	return $defaults;

}
 add_filter( 'manage_cpt-1_posts_columns', 'allonsy_product_post_columns' );


/**
 * Populate the post edit column with the custom post meta data
 *
 * @param $col
 * @param $pid
 */
function allonsy_product_custom_columns ( $col, $prod_id ) {

	switch ( $col ) :

		case 'allonsy_meta_field':
			echo allonsy_tpl_meta( 'allonsy_meta_field', $prod_id );
			break;

		case 'thumbnail':
			echo has_post_thumbnail() ?
				get_the_post_thumbnail( $prod_id, array(80, 80) ) :
				'<em>' . __( 'No featured image', 'allons-y' ) . '</em>';
			break;

	endswitch;

}

 add_action( 'manage_posts_custom_column', 'allonsy_product_custom_columns', 10, 2 );
