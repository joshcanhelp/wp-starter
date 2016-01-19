<?php
/**
 * Shortcodes, all of them
 * Look, I know a lot of developers out there hate shortcodes and, trust me, I get that.
 * But if you're a good enough dev to understand why they're annoying, you're also good enough to deal with
 * sites that already have them.
 * Declare the shortcodes you're seeing to ditch their output (hacky) or WP-CLI search-replace
 *
 * @package    WordPress
 * @subpackage AllonsYFramework
 */

add_shortcode( 'email', 'allonsy_sc_email_link' );
add_shortcode( 'share_links', 'allonsy_sc_share_links' );
add_shortcode( 'home_url', 'allonsy_sc_home_url' );

/**
 * Output a linked email address
 *
 * @param array  $atts
 * @param string $content
 *
 * @return string
 */
function allonsy_sc_email_link ( $atts = array() , $content = '' ) {
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

	return ob_get_clean() ;

}


/**
 * Returns the home_url() output in the content
 *
 * @return string|void
 */

function allonsy_sc_home_url () {
    return home_url();
}