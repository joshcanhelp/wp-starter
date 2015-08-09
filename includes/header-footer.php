<?php

/**
 * Meta tags on all pages
 */
function proper_meta_tags () {

	$meta_title = wp_title( '|', FALSE, 'right' );

	?>
	<meta property="og:title" content="<?php echo $meta_title ?>">
	<meta property="og:locale" content="en_US">
	<meta property="og:site_name" content="<?php echo get_bloginfo( 'name' ) ?>">

	<meta name="twitter:card" content="summary">
	<meta name="twitter:title" content="<?php echo $meta_title ?>">
	<meta name="twitter:domain" content="<?php echo get_bloginfo( 'name' ) ?>">
	<?php
	// TODO: Add correct Twitter name
	?>
	<meta name="twitter:site" content="@joshcanhelp">

	<?php

	$desc = $url = $og_section = '';
	$og_type = 'website';
	if ( is_singular() ) {

		// Custom description for this page or post
		if ( proper_meta( 'pcc_page_meta_desc' ) ) {
			$desc = proper_meta( 'pcc_page_meta_desc' );
		} else {
			$desc = get_the_excerpt();
		}

		$url = get_permalink();

	} else if ( is_tax() || is_tag() || is_category() ) {

		// Current term data
		$term = get_queried_object();
		$term_meta = get_option( 'taxonomy_meta_' . $term->term_id, '' );

		if ( ! empty( $term_meta['meta_desc'] ) ) {
			$desc = $term_meta['meta_desc'];
		} else {
			$desc = $term->description;
		}

		$url = get_term_link( $term->term_id, $term->taxonomy );
		$og_section = $term->name;

	} else if ( is_search() ) {

		$desc = 'Product search results for "' . get_query_var( 's' ) . '" on ' . get_bloginfo( 'name' );
		$url = home_url( '?s=' . urlencode( get_query_var( 's' ) ) );

	}
	?><meta property="og:type" content="<?php echo $og_type ?>">

	<?php
	// TODO: Add correct Facebook URL
	?>
	<meta property="<?php
		echo $og_type ?>:publisher" content="https://www.facebook.com/JoshCanHelpPage">

	<?php

	if ( $og_section ) :
	?><meta property="<?php echo $og_type ?>:section" content="<?php echo $og_section ?>">

	<?php
	endif;

	if ( $desc ) :
	?><meta name="description" content="<?php echo esc_attr( $desc ) ?>">
	<meta property="og:description" content="<?php echo esc_attr( $desc ) ?>">

	<?php
	endif;

	if ( $url && ! is_wp_error( $url ) ) :
	?><meta property="og:url" content="<?php echo esc_attr( $url ) ?>">
	<link rel="canonical" href="<?php echo esc_attr( $url ) ?>">

	<?php
	endif;

	if ( is_singular() ) {

		$feat_img = wp_get_attachment_image_src( proper_meta( '_thumbnail_id' ), 'medium' );
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

add_action( 'wp_head', 'proper_meta_tags', 1 );



/**
 * Add classes to the body tag
 *
 * @param $classes
 *
 * @return array
 */
function proper_body_class ( $classes ) {

	if ( is_user_logged_in() ) {
		$user = get_userdata( get_current_user_id() );
		foreach ( $user->roles as $role ) {
			$add_class = 'user-role-' . $role;
			if ( is_array( $classes ) ) {
				$classes[] = $add_class;
			} else {
				$classes .= ' ' . $add_class;
			}
		}
	}

	if ( get_query_var( 'pagename' ) ) {
		$page_name = 'page-name-' . get_query_var( 'pagename' );
		if ( is_array( $classes ) ) {
			$classes[] = $page_name;
		} else {
			$classes .= ' ' . $page_name;
		}
	}

	return $classes;
}

add_action( 'body_class', 'proper_body_class' );
add_filter( 'admin_body_class', 'proper_body_class' );