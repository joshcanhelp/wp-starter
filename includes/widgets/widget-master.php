<?php

/*
 * Bring in the widgets!
 */

require_once( 'widget-template.php' );

/**
 * Register widget areas and update sidebar with default widgets.
 */
function proper_hook_widgets_init() {

	register_sidebar( array(
		'name'          => __( 'Sidebar - Page', 'proper-start' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-page">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h6 class="widget-title">',
		'after_title'   => '</h6>',
	) );

	register_sidebar( array(
		'name'          => __( 'Sidebar - Blog', 'proper-start' ),
		'id' => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-blog">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h6 class="widget-title">',
		'after_title'   => '</h6>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer', 'proper-start' ),
		'id' => 'sidebar-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s widget-footer">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h6 class="widget-title">',
		'after_title'   => '</h6>',
	) );

	// Shortcodes in the Text widget
	add_filter( 'widget_text', 'shortcode_unautop' );
	add_filter( 'widget_text', 'do_shortcode' );
}

add_action( 'widgets_init', 'proper_hook_widgets_init' );


/**
 * Builds the widget admin fields
 *
 * @param $fields
 * @param $instance
 */
function proper_display_widget_fields ( $fields, $instance ) {

	if ( ! class_exists( 'PhpFormBuilder' ) ) {
		require_once( get_stylesheet_directory() . '/includes/classes/PhpFormBuilder.php' );
	}

	$widget_form = new PhpFormBuilder();
	$widget_form->set_att( 'add_honeypot', FALSE );
	$widget_form->set_att( 'form_element', FALSE );
	$widget_form->set_att( 'add_submit', FALSE );
	$widget_form->set_att( 'markup', 'html' );

	foreach ( $fields as $field ) {

		$input_args = array(
			'type'        => $field['type'],
			'name'        => $field['field_name'],
			'class'       => array( 'widefat' ),
			'wrap_tag'    => '',
			'before_html' => '<p class="widget-field field_type_' . $field['type'] . '">',
			'after_html'  => '</p>',
		);

		if ( ! empty( $field['description'] ) ) {
			$input_args['after_html'] = '<span class="description">' . $field['description'] .
				'</span>' . $input_args['after_html'];
		}

		$field['default'] = isset( $field['default'] ) ? $field['default'] : '';

		switch ( $field['type'] ) {

			case 'text':
			case 'email':
			case 'url':
			case 'number':
			case 'password':
			case 'textarea':
				$input_args['value'] = isset( $instance[ $field['id'] ] ) ? $instance[ $field['id'] ] : $field['default'];
				break;

			case 'checkbox':
				$input_args['value']   = 1;
				$input_args['checked'] = isset( $instance[ $field['id'] ] ) ?
					(bool) $instance[ $field['id'] ] :
					(bool) $field['default'];
				$input_args['class']   = array( 'checkbox' );
				break;

			case 'select':
				$input_args['type']     = 'select';
				$input_args['selected'] = isset( $instance[ $field['id'] ] ) ?
					$instance[ $field['id'] ] :
					$field['default'];
				if ( is_array( $field['options'] ) ) {
					$input_args['options'] = $field['options'];
				} else if ( function_exists( $field['options'] ) ) {
					$input_args['options'] = $field['options']();
				}
				break;

			case 'title':
				$input_args['before_html'] = sprintf(
					'<div class="widget-field-header field-type-%s" id="widget-%s">',
					$field['type'],
					$field['id']
				);
				$input_args['after_html']  = '</div>';
				unset( $input_args['class'] );
				break;

		}

		$widget_form->add_input( $field['label'], $input_args, esc_attr( $field['field_id'] ) );

	}

	$widget_form->build_form();

}