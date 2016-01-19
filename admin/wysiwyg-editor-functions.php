<?php

/**
 * Load custom editor CSS style sheet
 *
 * @param $mce_css
 *
 * @return string
 */
function allonsy_hook_mce_css ( $mce_css ) {
	if ( ! empty( $mce_css ) ) {
		$mce_css .= ',';
	}
	$mce_css .= get_stylesheet_directory_uri() . '/assets/css/editor-styles.css,';
	$mce_css .= 'fonts.googleapis.com/css?family=Lato:300,400,700';

	return $mce_css;
}

add_action( 'mce_css', 'allonsy_hook_mce_css' );


/**
 * Add a custom style dropdown
 *
 * @param $buttons
 *
 * @return mixed
 */
function allonsy_hook_mce_buttons_2 ( $buttons ) {

	if ( in_array( 'formatselect', $buttons ) ) {
		unset( $buttons[ array_search( 'formatselect', $buttons )] );
	}
	array_unshift( $buttons, 'styleselect' );

	return $buttons;
}

add_filter( 'mce_buttons_2', 'allonsy_hook_mce_buttons_2' );



function allonsy_hook_tiny_mce_before_init ( $settings ) {

	// Add formats
	// From http://tinymce.moxiecode.com/examples/example_24.php
	$style_formats = array(
		array(
			'title' => 'Regular',
			'block' => 'p'
		),
		array(
			'title' => 'Large',
			'block' => 'p',
			'classes' => 'body-text-large'
		),
		array(
			'title' => 'Header 2',
			'block' => 'h2'
		),
		array(
			'title' => 'Header 3',
			'block' => 'h3'
		),
		array(
			'title' => 'Header 4',
			'block' => 'h4'
		),
		array(
			'title' => 'Header 5',
			'block' => 'h5'
		),
		array(
			'title' => 'Header 6',
			'block' => 'h6'
		)
	);

	$settings['style_formats'] = json_encode( $style_formats );

	// Add colors

	$settings['textcolor_map'] = '[
		"4981bc", "Blue",
		"8b252a", "Maroon"
	]';

	return $settings;
}

add_filter( 'tiny_mce_before_init', 'allonsy_hook_tiny_mce_before_init' );