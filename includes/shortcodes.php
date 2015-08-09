<?php
/*
 *
 */

/**
 * Output a linked email address
 *
 * @param array  $atts
 * @param string $content
 *
 * @return string
 */
function proper_sc_pcc_email ( $atts = array() , $content = '' ) {
	return sprintf(
		'<a href="mailto:%s">%s</a>',
		filter_var( $content, FILTER_SANITIZE_EMAIL ),
		$content
	);
}

add_shortcode( 'email', 'proper_sc_pcc_email' );
