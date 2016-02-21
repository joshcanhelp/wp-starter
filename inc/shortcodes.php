<?php
/**
 * Shortcodes, all of them.
 *
 * Look, I know a lot of developers out there hate shortcodes and, trust me, I get that.
 *
 * But if you're a good enough dev to understand why they're annoying, you're also good enough to deal with
 * sites that already have them.
 *
 * Declare the shortcodes you're seeing to ditch their output (hacky) or WP-CLI search-replace
 *
 * @package    WordPress
 * @subpackage AllonsYFramework
 */

add_shortcode( 'email', 'allonsy_sc_email_link' );
add_shortcode( 'share_links', 'allonsy_sc_share_links' );
add_shortcode( 'home_url', 'allonsy_sc_home_url' );
add_shortcode( 'columns', 'allonsy_sc_columns' );

/**
 * Output a linked email address
 *
 * @param array  $atts
 * @param string $content
 *
 * @return string
 */
function allonsy_sc_email_link ( $atts = array() , $content = '' ) {

	// No email to link? You must want the admin email then ...

	if ( empty( $content ) ) {
		$content = get_option('admin_email');
	}

	return sprintf(
		'<a href="mailto:%s">%s</a>',
		filter_var( $content, FILTER_SANITIZE_EMAIL ),
		$content
	);
}


/**
 * Display post sharing
 *
 * @return string
 */
function allonsy_sc_share_links() {

	ob_start();
	allonsy_share_links( get_the_ID(), get_the_title() );

	return ob_get_clean();

}


/**
 * Returns the home_url() output in the content
 *
 * @return string|void
 */

function allonsy_sc_home_url () {
    return home_url();
}


function allonsy_sc_columns( $atts = array(), $content ) {


	// "[split]" tells us where the content is split by column

	$col_content = explode( '[split]', $content );

	// Only one content block means that there is no column

	if ( count( $col_content ) <= 1 ) {
		return str_replace( '[split]', '', $content );
	}

	switch ( count( $col_content ) ) {

		case 0:
		case 1:
			return str_replace( '[split]', '', $content );

		case 2:
		case 3:
		case 4:
		case 6:
			$col_class = 'col-' . count( $col_content );
			break;

		case 5:
			$col_class = 'col-4';
			break;

		default:
			$col_class = 'col-6';

	}

	$output = '';

	foreach ( $col_content as $col ) {

		if ( substr( $col, 0, 5 ) === '</p>' . PHP_EOL ) {
			$col = substr( $col, 5 );
		}

		if ( substr( $col, strlen( $col ) - 4 ) === PHP_EOL . '<p>' ) {
			$col = substr( $col, 0, strlen( $col ) - 4 );
		}

		$output .= sprintf(
			'<div class="%s">%s</div>',
			esc_attr( $col_class ),
			$col
		);
	}

	return '<div class="allonsy-sc-cols">' . $output . '</div>';

}