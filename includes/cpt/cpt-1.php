<?php


/**
 * Declare the custom post type and custom taxonomy
 */
function proper_hook_proper_cpt () {

	$labels = array(
		'name'               => _x( 'Tests', 'post type general name', 'wpstartmeup' ),
		'singular_name'      => _x( 'Test', 'post type singular name', 'wpstartmeup' ),
		'add_new'            => _x( 'Add Test', 'test_content', 'wpstartmeup' ),
		'add_new_item'       => __( 'Add New Test', 'wpstartmeup' ),
		'edit_item'          => __( 'Edit Test', 'wpstartmeup' ),
		'new_item'           => __( 'New Test', 'wpstartmeup' ),
		'all_items'          => __( 'All Tests', 'wpstartmeup' ),
		'view_item'          => __( 'View Test', 'wpstartmeup' ),
		'not_found'          => __( 'No Tests found', 'wpstartmeup' ),
		'not_found_in_trash' => __( 'No Tests found in Trash', 'wpstartmeup' ),
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

add_action( 'init', 'proper_hook_proper_cpt', 20 );

/**
 * Add custom post columns for products
 * TODO: Needed?
 *
 * @param $defaults
 *
 * @return mixed
 */
function proper_product_post_columns ( $defaults ) {

	$defaults['pcc_product_sku']                 = 'SKU';
	$defaults['thumbnail']                 = 'Image';
	$defaults['pcc_product_stat_requested_qty']    = 'Qty Req.';
	$defaults['pcc_product_stat_requested']    = 'Total Req.';

	return $defaults;

}
// add_filter( 'manage_pcc-product_posts_columns', 'proper_product_post_columns' );


/**
 * Populate the post edit column with the custom post meta data
 * TODO: Needed?
 *
 * @param $col
 * @param $pid
 */
function proper_product_custom_columns ( $col, $prod_id ) {

	switch ( $col ) :

		case 'pcc_product_sku':
			echo proper_meta( 'pcc_prod_sku', $prod_id );
			break;

		case 'thumbnail':
			echo has_post_thumbnail() ?
				get_the_post_thumbnail( $prod_id, array(80, 80) ) :
				'<img src="' . proper_get_post_img_url( $prod_id ) . '" width="80">';
			break;

		case 'pcc_product_stat_cart_add':
			echo (int) proper_meta( 'pcc_product_stat_cart_add', $prod_id );
			break;

		case 'pcc_product_stat_requested_qty':
			echo (int) proper_meta( 'pcc_product_stat_requested_qty', $prod_id );
			break;

		case 'pcc_product_stat_requested':
			echo (int) proper_meta( 'pcc_product_stat_requested', $prod_id );
			break;

	endswitch;

}

// add_action( 'manage_posts_custom_column', 'proper_product_custom_columns', 10, 2 );
