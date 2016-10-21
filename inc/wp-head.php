<?php

/**
 * Alter meta title in some cases
 *
 * @param $title
 * @param $sep
 *
 * @return string
 */
function allonsy_custom_meta_title( $title ) {

	if ( is_singular() && $meta_title = pitts_tpl_meta( 'meta_title' ) ) {

		// If we're on a single page, there might be a meta field to use

		$title = $meta_title;

	} else if ( is_search() ) {

		// Better default search title

		$title = 'Search results for "' . get_query_var( 's' ) . '" on ' . get_bloginfo( 'name' );
	}

	return $title;
}

add_filter( 'pre_get_document_title', 'allonsy_custom_meta_title', 10, 2 );


/**
 * Meta tags on all pages
 */
function allonsy_head_meta_tags () {

	$meta_title = player_custom_meta_title( wp_title( '|', FALSE, 'right' ) );

	?>
	<meta property="og:title" content="<?php echo $meta_title ?>">
	<meta property="og:locale" content="en_US">
	<meta property="og:site_name" content="<?php echo get_bloginfo( 'name' ) ?>">

	<meta name="twitter:card" content="summary">
	<meta name="twitter:title" content="<?php echo $meta_title ?>">
	<meta name="twitter:domain" content="<?php echo get_bloginfo( 'name' ) ?>">
	<?php
	if ( get_option( 'site_twitter_name' ) ) :
	?><meta name="twitter:site" content="@<?php
			echo allonsy_sanitize_twitter( get_option( 'site_twitter_name' ) ) ?>">

	<?php
	endif;

	$desc = $url = '';
	$og_type = 'website';
	if ( is_singular() ) {

		// Custom description for this page or post

		$desc = pitts_tpl_meta( 'meta_description' );

		if ( empty( $desc ) ) {
			$desc = get_the_excerpt();
		}

		$url = get_permalink();

	} else if ( is_tax() || is_tag() || is_category() ) {

		// Current term data
		$term = get_queried_object();

		$desc = $term->description;
		$url = get_term_link( $term->term_id, $term->taxonomy );

	} else if ( is_search() ) {

		$desc = __( 'Search results for', 'allons-y' ) . ' "' .
			get_query_var( 's' ) . '" ' . __( 'on', 'allons-y' ) . ' ' . get_bloginfo( 'name' );

		$url = home_url( '?s=' . urlencode( get_query_var( 's' ) ) );

	}
	?><meta property="og:type" content="<?php echo $og_type ?>">

	<?php
	if ( get_option( 'site_facebook_url' ) ) :
	?><meta property="<?php
		echo $og_type ?>:publisher" content="<?php echo esc_url( get_option( 'site_facebook_url' ) ) ?>">

	<?php
	endif;


	if ( $desc ) :
	?><meta name="description" content="<?php echo esc_attr( strip_tags( $desc ) ) ?>">
	<meta property="og:description" content="<?php echo esc_attr( $desc ) ?>">

	<?php
	endif;

	if ( $url && ! is_wp_error( $url ) ) :
	?><meta property="og:url" content="<?php echo esc_attr( $url ) ?>">
	<link rel="canonical" href="<?php echo esc_attr( $url ) ?>">

	<?php
	endif;

	if ( is_singular() ) {

		$feat_img = wp_get_attachment_image_src( pitts_tpl_meta( '_thumbnail_id' ), 'medium' );
		$feat_img_url = ! empty( $feat_img[0] ) ? $feat_img[0] : '';

	}

	if ( ! empty( $feat_img_url ) ) :
	?><meta property="og:image" content="<?php echo esc_url( $feat_img_url ) ?>">
	<?php
	endif;

	// TODO: Fill out and submit to the below
	// http://www.alexa.com/siteowners/claim
	// http://www.bing.com/toolbox/webmaster/#/Dashboard/?url=localhost%3A8888%2Fwp-pcc
	// https://www.google.com/webmasters/verification/home?hl=en&siteUrl=http://localhost:8888/wp-pcc/
	// http://help.yandex.com/webmaster/service/rights.xml#how-to
	// https://developers.google.com/structured-data/customize/overview

}

add_action( 'wp_head', 'allonsy_head_meta_tags', 1 );
