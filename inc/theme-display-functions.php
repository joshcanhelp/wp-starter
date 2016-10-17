<?php
/**
 * Functions not tied to hooks that display HTML
 *
 * @package    WordPress
 * @subpackage AllonsYFramework
 */


if ( ! function_exists( 'allonsy_family_nav' ) ) {
	/**
	 * Sibling navigation for pages and posts with same-level items
	 *
	 * @param string $title
	 * @param string $before
	 * @param string $after
	 *
	 * @return string
	 */

	function allonsy_family_nav( $title = 'In this section', $before = '', $after = '' ) {

		global $post;

		$output = '';

		if ( $post->post_parent == 0 ) {
			$top_post = $post;
		} else {
			$top_post = get_post( $post->post_parent );
		}

		// Grab all pages, ordered by menu order
		$arr_pages = get_posts( array(
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'post_parent' => $top_post->ID,
			'post_type' => 'page',
			'posts_per_page' => - 1
		) );

		// No child pages to show
		if ( empty( $arr_pages ) ) {
			return $output;
		}

		$output .= sprintf(
			'<div class="family-nav"><h6 class="family-nav-header">%s</h6><ul class="family-nav-links">
		<li class="family-nav-parent %s"><a href="%s">%s</a></li>',
			$title,
			$top_post->ID === get_the_ID() ? 'current' : '',
			get_permalink( $top_post->ID ),
			$top_post->post_title
		);

		foreach ( $arr_pages as $a_page ) :

			$output .= sprintf(
				'<li class="family-nav-child %s"><a href="%s">%s</a></li>',
				$a_page->ID === get_the_ID() ? 'current' : '',
				get_permalink( $a_page->ID ),
				$a_page->post_title
			);

		endforeach;

		$output .= '</ul></div>';

		return $before . $output . $after;

	}
}

if ( ! function_exists( 'allonsy_pagination' ) ) {

	/**
	 * Pagination links
	 *
	 * @param null|object $query
	 */

	function allonsy_pagination( $query = NULL ) {

		if ( is_null( $query ) ) {
			global $wp_query;
			$total = $wp_query->max_num_pages;
		} else {
			$total = $query->max_num_pages;
		}

		// Only paginate if we have more than one page
		if ( $total > 1 ) {

			echo '<div class="pagination-links">';
			echo paginate_links( $args = array(
				'base' => get_pagenum_link( 1 ) . '%_%',
				'format' => is_search() ? '&paged=%#%' : 'page/%#%/',
				'current' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
				'total' => $total,
				'mid_size' => 2,
				'type' => 'plain',
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;'
			) );
			echo '</div>';

		}
	}
}


if ( ! function_exists( 'allonsy_archive_title' ) ) {

	/**
	 * Rational archive header everywhere
	 */
	function allonsy_archive_title() {

		if ( is_home()) :
			$title = get_the_title( get_option( 'page_for_posts' ) );
		elseif ( is_search() ) :
			$title = sprintf( __( 'Results for "%s"', 'allons-y' ), get_query_var( 's' ) );

		elseif ( is_category() ) :
			$title = single_cat_title( '', FALSE );


		elseif ( is_tag() ) :
			$title = single_tag_title( '', FALSE );

		elseif ( is_author() ) :
			$title = sprintf(
				__( 'By %s', 'allons-y' ),
				'<span class="vcard">' . get_the_author() . '</span>'
			);

		elseif ( is_day() ) :
			$title = sprintf(
				__( '%s', 'allons-y' ),
				'<span>' . get_the_date() . '</span>'
			);

		elseif ( is_month() ) :
			$title = sprintf(
				__( '%s', 'allons-y' ),
				'<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'allons-y' ) ) . '</span>'
			);

		elseif ( is_year() ) :
			$title = sprintf(
				__( '%s', 'allons-y' ),
				'<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'allons-y' ) ) . '</span>'
			);

		elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
			$title = __( 'Asides', 'allons-y' );

		elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
			$title = __( 'Portfolio', 'allons-y' );

		elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
			$title = __( 'Images', 'allons-y' );

		elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
			$title = __( 'Videos', 'allons-y' );

		elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
			$title = __( 'Quotes', 'allons-y' );

		elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
			$title = __( 'Links', 'allons-y' );

		elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
			$title = __( 'Statuses', 'allons-y' );

		elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
			$title = __( 'Audios', 'allons-y' );

		elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
			$title = __( 'Chats', 'allons-y' );

		else :
			$title = __( 'Posts', 'allons-y' );

		endif;

		if ( (int) get_query_var('paged') > 1 ) {
			$title .= ' - page ' . get_query_var( 'paged' );
		}

		return $title;
	}
}


if ( ! function_exists( 'allonsy_share_links' ) ) {

	/**
	 * Build hard-coded sharing icons
	 *
	 * @param int    $pid
	 * @param string $title
	 * @param string $img
	 */

	function allonsy_share_links( $pid = 0, $title = '', $img = '' ) {

		// Default to current post globals

		if ( empty( $pid ) ) {
			$pid = get_the_ID();
		}

		if ( empty( $title ) ) {
			$title = get_the_title( $pid );
		}

		if ( empty( $img ) ) {
			$img = allonsy_get_post_img_url( $pid );
		}

		$permalink = get_permalink( $pid );
		$sharing   = [ ];

		// Twitter sharing
		$sharing['twitter'] = sprintf(
			'https://twitter.com/intent/tweet?text=%s&url=%s',
			urlencode( $title ),
			urlencode( $permalink )
		);

		// LinkedIn sharing
		$sharing['linkedin'] = sprintf(
			'http://www.linkedin.com/shareArticle?mini=true&title=%s&url=%s',
			urlencode( $title ),
			urlencode( $permalink )
		);

		// Pinterest sharing
		$sharing['pinterest'] = sprintf(
			'http://pinterest.com/pin/create/button/?url=%s&image_url=%s&description=%s',
			urlencode( $permalink ),
			urlencode( $img ),
			urlencode( $title )
		);

		// Facebook sharing
		$sharing['facebook'] = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $permalink );

		// Google Plus sharing
		$sharing['gplus'] = 'https://plus.google.com/share?url=' . urlencode( $permalink );

		foreach ( [ 'twitter', 'facebook', 'pinterest' ] as $network ) {
			printf(
				'<a href="%s" target="_blank" rel="nofollow" class="bt-share bt-share__%s">
					<i class="icon icon-%s bt-sharing--icon"></i></a>',
				esc_url( $sharing[ $network ] ),
				$network,
				$network
			);
		}
	}
}
