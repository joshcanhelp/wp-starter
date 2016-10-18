<?php
/**
 * Custom data lookup from the database or elsewhere. Notes for usage:
 *
 * - Functions here should not be tied to hooks or filters
 * - Transformation of data should be done using filters or in /inc/data-transformations.php
 *
 * @package WordPress
 * @subpackage AllonsYFramework
 */


/**
 * Do not allow this file to be loaded directly.
 */

if ( ! function_exists( 'add_action' ) ) {
	die( 'Nothing to do...' );
}


/**
 * Get a single meta field from the database or cache.
 * Basic wrapper function around get_post_meta() to make quick meta values available in templates.
 *
 * @see get_post_meta()
 *
 * @param string $meta_key - meta_key value from postmeta table
 * @param int    $pid      - post ID
 *
 * @return string
 */

function allonsy_tpl_meta( $meta_key, $pid = 0 ) {

	// If no post ID passed in, get the global value

	if ( empty( $pid ) ) {
		$pid = get_the_ID();
	}

	return get_post_meta( $pid, $meta_key, TRUE );
}


/**
 * Get the URL to an image in the theme's assets directory.
 * This file does not check for the validity of the URL.
 *
 * @param string $file_name - exact file name for the image needed
 *
 * @return string
 */

function allonsy_theme_img( $file_name ) {

	return get_stylesheet_directory_uri() . '/assets/img/' . $file_name;
}


/**
 * Gets the featured image URL or the default image if that doesn't exist.
 *
 * Used for:
 *
 * - Displaying the featured image as a background
 * - Passing the image back to client when doing an AJAX call
 *
 * @see wp_get_attachment_image_src()
 *
 * @param int    $pid  - post ID
 * @param string $size - valid image size, either core or created in the theme
 *
 * @return string
 */

function allonsy_get_post_img_url( $pid = 0, $size = 'thumbnail' ) {

	// If no post ID passed in, get the global value

	if ( empty( $pid ) ) {
		$pid = get_the_ID();
	}

	// Featured image post ID

	$image_pid = get_post_thumbnail_id( $pid );

	$img_url = allonsy_get_attached_img_src( $image_pid, $size );

	return $img_url ?
		$img_url :
		get_template_directory_uri() . '/assets/img/default-img-size-' . $size . '.png';
}


/**
 * Get the img URL using an attachment ID
 *
 * @param int    $attach_id
 * @param string $size
 *
 * @return string
 */

function allonsy_get_attached_img_src( $attach_id, $size = 'thumbnail' ) {

	// Will return an array of data about the image, if one exists
	// https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/

	$img_url = wp_get_attachment_image_src( $attach_id, $size );

	return ! empty( $img_url[0] ) ? $img_url[0] : '';
}


/**
 * Get all attachments with a document-like mime type
 *
 * @param int $pid
 *
 * @return array
 */

function allonsy_get_attached_docs( $pid = 0 ) {

	if ( empty( $pid ) ) {
		$pid = get_the_ID();
	}

	$docs = get_children( array(
		'post_type' => 'attachment',
		'post_parent' => $pid,
		'posts_per_page' => - 1
	) );

	return ! empty( $docs ) ? $docs : array();
}


/**
 * Get a single term for a piece of content
 *
 * @param bool   $link_html
 * @param string $tax
 *
 * @return string
 */

function allonsy_get_single_cat( $link_html = FALSE, $tax = 'category' ) {

	if ( $terms = get_the_terms( get_the_ID(), $tax ) ) {

		if ( $link_html ) {
			return sprintf( $link_html, get_term_link( $terms[0]->term_id, $tax ), $terms[0]->name );
		} else {
			return $terms[0]->name;
		}
	}

	return '';
}


/**
 * Allowed HTML array for wp_kses on basic WYSIWYG fields
 *
 * @see wp_kses()
 *
 * @return array
 */

function allonsy_kses_wysiwyg_filtering() {
	return array(
		'a' => array(
			'href' => array(),
			'title' => array(),
		),
		'p' => array(),
		'img' => array(
			'src' => array(),
			'alt' => array(),
			'class' => array(),
			'width' => array(),
			'height' => array(),
		),
		'span' => array(
			'class' => array(),
		),
		'cite' => array(
			'class' => array(),
		),
		'br' => array(),
		'sup' => array(),
		'hr' => array(),
		'em' => array(),
		'strong' => array(),
		'b' => array(),
		'i' => array(
			'class' => array(),
		),
	);
}


/**
 * Allowed basic HTML array for wp_kses on textareas
 *
 * @see wp_kses()
 *
 * @return array
 */

function allonsy_kses_textarea_filtering() {
	return array(
		'span' => array(
			'class' => array(),
		),
		'br' => array(),
		'strong' => array(),
		'b' => array(),
		'em' => array(),
		'i' => array(),
	);
}


/**
 * Allowed HTML array for wp_kses in embed code
 *
 * @see wp_kses()
 *
 * @return array
 */

function allonsy_kses_embed_filtering() {
	return array(
		'iframe' => array(
			'frameborder' => array(),
			'allowfullscreen' => array(),
			'width' => array(),
			'height' => array(),
			'src' => array(),
		),
	);
}


/**
 * Allowed HTML array for wp_kses in SVG code
 *
 * @see wp_kses()
 *
 * @return array
 */

function allonsy_kses_svg_filtering() {
	return array(
		'svg' => array(
			'xml:space' => array(),
			'xmlns' => array(),
			'xmlns:xlink' => array(),
			'version' => array(),
			'x' => array(),
			'y' => array(),
			'viewbox' => array(),
			'width' => array(),
			'height' => array(),
			'class' => array(),
		),
		'path' => array(
			'd' => array(),
			'class' => array(),
		),
		'g' => array(),
	);
}


/**
 * Output an SVG
 *
 * @param $filename
 *
 * @return string
 */


function allonsy_display_svg( $filename ) {
	//echo file_get_contents( locate_template( 'svg/' . $filename ) );
	echo wp_kses(
		file_get_contents( locate_template( 'svg/' . $filename ) ),
		allonsy_kses_svg_filtering()
	);
}