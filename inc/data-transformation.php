<?php
/**
 * Take passed-in data and change for output or more processing. Notes for usage:
 *
 * - Functions here should not be tied to hooks or filters
 * - Fucntions here should accept data and change it, not find it; declare a lookup function in /inc/data-lookup.php
 *
 * @package    WordPress
 * @subpackage AllonsYFramework
 */

/*
 * Do not allow this file to be loaded directly
 */

if ( ! function_exists( 'add_action' ) ) {
	die( 'Nothing to do...' );
}

/**
 * Truncate a string at a particular length
 *
 * @param string  $string
 * @param integer $limit
 * @param string  $break
 *
 * @return string $string
 */
function allonsy_truncate( $string, $limit, $break = ' ' ) {

	if ( strlen( $string ) >= $limit ) {
		$string = substr( $string, 0, $limit );
		while ( $string[ strlen( $string ) - 1 ] != $break && ! empty( $string ) ) {
			$string = substr( $string, 0, strlen( $string ) - 1 );
		}
	}

	return $string;
}

/**
 * Builds a group of select options
 *
 * @param        $values
 * @param string $incl_blank
 * @param string $active
 *
 * @return string
 */
function allonsy_build_options( $values, $incl_blank = 'Select one ...', $active = '' ) {

	$output = '';

	foreach ( $values as $val => $name ) {
		$output .= sprintf(
			'<option value="%s"%s>%s</option>',
			$val,
			( ! empty( $active ) && $val == $active ? ' selected' : '' ),
			$name
		);
	}

	if ( $incl_blank ) {
		$output = sprintf(
			'<option value="">%s</option>%s',
			$incl_blank,
			$output
		);
	}

	return $output;

}

/**
 * Get a textarea option separated by new lines and return an array
 *
 * @param $txt
 *
 * @return array
 */
function allonsy_get_textarea_options( $txt ) {

	$opts = array();

	if ( empty( $txt ) ) {
		return $opts;
	}

	$txt_arr = explode( PHP_EOL, $txt );

	foreach ( $txt_arr as $opt ) :

		$opt = trim( $opt );
		if ( empty( $opt ) ) {
			continue;
		}

		$opts[ $opt ] = $opt;

	endforeach;

	return $opts;

}


/**
 * Sanitize potential Twitter username inputs, including URLs and names starting with @
 *
 * @param $twit
 *
 * @return string
 */
function allonsy_sanitize_twitter( $twit ) {

	if ( filter_var( $twit, FILTER_VALIDATE_URL ) ) {

		// Despite our best documentation efforts, we have a URL

		$twit_parsed = parse_url( $twit );

		if (

			 // We have a path
			$twit_parsed['path'] &&

			 // The first character is a front slash
			$twit_parsed['path'][0] === '/' &&

			// There are no other front slashes
			substr_count( $twit_parsed['path'], '/' ) === 1
		) {
			return sanitize_title( substr( $twit_parsed['path'], 1 ) );
		}

	} elseif ( $twit[0] === '@' ) {

		// Despite our best documentation efforts, we have a username with an "@"

		return sanitize_title( substr( $twit, 1 ) );

	} elseif ( stripos( $twit, '-') === FALSE && $twit === sanitize_title( $twit ) ) {

		return sanitize_title( $twit );

	}

	return '';
}