<?php


/**
 * Template for new WordPress widget
 *
 * @see WP_Widget::widget()
 */
class ProperWidgetTpl extends WP_Widget {

	/**
	 *  Widget constructor
	 */
	function __construct() {
	
		// Create the Widget

		parent::__construct(
			'proper-widget-template', 
			__( 'Widget Title', 'proper-start' ),
			array(
				'classname'   => 'proper-widget-template',
				'description' => __( 'Widget description goes here', 'proper-start' )
			) 
		);

		$this->widget_fields = array(
			array(
				'label'       => __( 'Text', 'proper-start' ),
				'type'        => 'text',
				'id'          => 'text',
				'description' => '',
				'default'     => ''
			),
			array(
				'label'       => __( 'Select', 'proper-start' ),
				'type'        => 'select',
				'id'          => 'select',
				'options'     => array(
					'1' => __( 'One', 'proper-start' ),
					'2' => __( 'Two', 'proper-start' ),
					'3' => __( 'Three', 'proper-start' ),
				),
				'description' => '',
				'default'     => ''
			),
			array(
				'label'       => __( 'Number', 'proper-start' ),
				'type'        => 'number',
				'id'          => 'number',
				'description' => '',
				'default'     => 0
			),
			array(
				'label'       => __( 'URL', 'proper-start' ),
				'type'        => 'url',
				'id'          => 'url',
				'description' => '',
				'default'     => ''
			),
			array(
				'label'       => __( 'Email', 'proper-start' ),
				'type'        => 'email',
				'id'          => 'email',
				'description' => '',
				'default'     => get_bloginfo( 'admin_email' )
			),
			array(
				'label'       => __( 'Checkbox', 'proper-start' ),
				'type'        => 'checkbox',
				'id'          => 'checkbox',
				'description' => '',
				'default'     => 'yes',
			),

		);

	}

	/**
	 * Widget logic and display
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {

		// Output all wrappers
		echo $args['before_widget'];

		if ( isset( $title ) && ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		/*
		 * Widget content here
		 */

		echo $args['after_widget'];

	}

	/**
	 * Used to update widget settings
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['text'] = sanitize_text_field( $new_instance['text'] );
		$instance['select'] = intval( $new_instance['select'] );
		$instance['number'] = intval( $new_instance['number'] );
		$instance['url']    = esc_url( $new_instance['url'] );
		$instance['email']  = filter_var( $new_instance['email'], FILTER_VALIDATE_EMAIL );
		$instance['checkbox'] = !empty( $new_instance['checkbox'] ) ? 1 : '';

		return $instance;

	}

	/**
	 * Used to generate the widget admin view
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	function form( $instance ) {

		for ( $i = 0; $i < count( $this->widget_fields ); $i ++ ) :
			$field_id                              = $this->widget_fields[$i]['id'];
			$this->widget_fields[$i]['field_id']   = $this->get_field_id( $field_id );
			$this->widget_fields[$i]['field_name'] = $this->get_field_name( $field_id );
		endfor;

		proper_display_widget_fields( $this->widget_fields, $instance );

	}
}

add_action( 'widgets_init', create_function( '', 'return register_widget("ProperWidgetTpl");' ) );
