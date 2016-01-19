<?php
/**
 * A custom widget template. Use this to create new widgets
 *
 * @package    WordPress
 * @subpackage AllonsYFramework
 */

/**
 * Template for new WordPress widget
 *
 * @see WP_Widget::widget()
 */
class AllonsyWidgetTpl extends WP_Widget {

	/**
	 *  Widget constructor
	 */
	function __construct() {
	
		// Create the Widget

		parent::__construct(
			'allonsy-widget-template',
			__( 'Widget Title', 'allons-y' ),
			array(
				'classname'   => 'allonsy-widget-template',
				'description' => __( 'Widget description goes here', 'allons-y' )
			) 
		);

		$this->widget_fields = array(
			array(
				'label'       => __( 'Text', 'allons-y' ),
				'type'        => 'text',
				'id'          => 'text',
				'description' => '',
				'default'     => ''
			),
			array(
				'label'       => __( 'Select', 'allons-y' ),
				'type'        => 'select',
				'id'          => 'select',
				'options'     => array(
					'1' => __( 'One', 'allons-y' ),
					'2' => __( 'Two', 'allons-y' ),
					'3' => __( 'Three', 'allons-y' ),
				),
				'description' => '',
				'default'     => ''
			),
			array(
				'label'       => __( 'Number', 'allons-y' ),
				'type'        => 'number',
				'id'          => 'number',
				'description' => '',
				'default'     => 0
			),
			array(
				'label'       => __( 'URL', 'allons-y' ),
				'type'        => 'url',
				'id'          => 'url',
				'description' => '',
				'default'     => ''
			),
			array(
				'label'       => __( 'Email', 'allons-y' ),
				'type'        => 'email',
				'id'          => 'email',
				'description' => '',
				'default'     => get_bloginfo( 'admin_email' )
			),
			array(
				'label'       => __( 'Checkbox', 'allons-y' ),
				'type'        => 'checkbox',
				'id'          => 'checkbox',
				'description' => '',
				'default'     => 1,
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

		allonsy_display_widget_form( $this->widget_fields, $instance );

	}
}

add_action( 'widgets_init', create_function( '', 'return register_widget("AllonsyWidgetTpl");' ) );
