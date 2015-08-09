<?php



/**
 * Truncate a string at a particular length
 *
 * @param string  $string
 * @param integer $limit
 * @param string  $break
 *
 * @return string $string
 */
function proper_truncate ( $string, $limit, $break = " " ) {

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
 * @param bool   $incl_blank
 * @param string $active
 *
 * @return string
 */
function proper_build_options ( $values, $incl_blank = TRUE, $active = '' ) {

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
			__( 'Select one...', 'wpstartmeup' ),
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
function proper_get_textarea_options ( $txt ) {

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
 * Unit conversion for output
 *
 * @param string $to
 * @param string|int $value
 * @param int $mod
 *
 * @return float
 */
function proper_unit_convert ( $to, $value, $mod = 1 ) {

	$final = 0;

	switch ( $to ) {

		// Convert to cm, assuming inches
		case 'cm':
			$final = intval( $value ) * $mod * 2.54;
			break;

		// Convert to m, assuming yards
		case 'm':
			$final = intval( $value ) * $mod * 0.9144;
			break;

		// Convert to C, assuming F
		case 'C':
			$final = ( intval( $value ) - 32 ) / 1.8;
			break;

	}

	return round( $final, 2, PHP_ROUND_HALF_DOWN);

}