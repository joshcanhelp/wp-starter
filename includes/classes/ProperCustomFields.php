<?php

if ( defined( 'ABSPATH' ) ) {
	ProperCustomFields::init();
}

class ProperCustomFields {

	private static $__instance = NULL;
	private static $pid = NULL;
	private static $post_type = NULL;
	private static $meta_fields = array();

	/**
	 * Kick off the class
	 */
	public static function init () {

		global $pagenow;

		if ( ! empty( $_REQUEST['post'] ) ) {
			self::$pid = $_REQUEST['post'];
		} else if ( ! empty( $_POST['post_ID'] ) ) {
			self::$pid = $_POST['post_ID'];
		} else {
			self::$pid = get_the_ID();
		}


		// Get the post type from POSTED data or current global post type
		if ( $pagenow === 'post-new.php' ) {
			self::$post_type = ! empty( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'post';
		} else {
			self::$post_type = ! empty( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : get_post_type( self::$pid );
		}

		if ( ! empty( self::$post_type ) ) {
			$func_name = str_replace( '-', '_', self::$post_type ) . '_meta_array';
			self::$meta_fields = function_exists( $func_name ) ? $func_name() : null;
		}

		// Generate single instance to activate hooks once
		self::instance();
	}

	/**
	 * Creates an instance of this class if none exists
	 *
	 * @return null|ProperCustomFields
	 */
	public static function instance () {

		if ( self::$__instance == NULL ) {
			self::$__instance = new ProperCustomFields();
		}

		return self::$__instance;
	}

	/**
	 * Hooks for site actions
	 */
	public function __construct () {

		add_action( 'admin_menu', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
		add_filter( 'is_protected_meta', array( $this, 'protected_meta' ), 10, 2 );
		add_filter( 'wp_insert_post_empty_content', '__return_false', 1000 );

	}

	/**
	 * Adds the meta field box, if necessary
	 */
	public function add_meta_box () {

		if ( empty( self::$meta_fields ) ) {
			return;
		}

		// Should the box be displayed
		$display_meta_box = FALSE;
		foreach ( self::$meta_fields as $meta ) {

			if (
				( isset( $meta['display'] ) && $meta['display'] ) &&
				( ! isset( $meta['pid'] ) || get_the_ID() === $meta['pid'] )
			) {
				$display_meta_box = TRUE;
				break;
			}
		}

		if ( $display_meta_box ) {
			add_meta_box(
				'proper-content-meta-boxes',
				'PCC ' . __( 'Custom Fields', 'proper-start' ),
				array( $this, 'display_fields' ),
				self::$post_type,
				'normal',
				'high'
			);
		}
	}

	/**
	 * save_post hooked function to save custom meta fields
	 *
	 * @param $pid
	 */
	public function save_fields ( $pid ) {

		if (

			// Not authorized
			! current_user_can( 'edit_post', $pid ) ||

			// No fields to save
			empty( self::$meta_fields )

		) {
			return;
		}

		// Verify meta nonce
		if (
			empty( $_POST[ '__wpnonce_pcc_meta_fields' ] )  ||
			! wp_verify_nonce( $_POST[ '__wpnonce_pcc_meta_fields' ], 'pcc_post_meta_nonce' )
		) {
			return;
		}

		/*
		 * Iterate through, check, and save/delete
		 */

		foreach ( self::$meta_fields as $meta_key => $meta ) :

			// Field is disabled so do not save
			if ( ! empty( $meta['disabled'] ) ) {
				continue;
			}

			// Field is not displayed normally, saving happens elsewhere
			if ( isset( $meta['display'] ) && ! $meta['display'] ) {
				continue;
			}

			// Don't do anything if this does not match the path for this meta
			if ( ! empty( $meta['path'] ) ) {
				if ( is_array( $meta['path'] ) ) {
					if ( ! in_array( $_POST['post_name'], $meta['path'] ) ) {
						continue;
					}
				} elseif ( $_POST['post_name'] != $meta['path'] ) {
					continue;
				}
			}

			// Don't do anything if this does not match the template for this meta
			if (
				! empty( $meta['template'] ) &&
				( empty( $_POST['page_template'] ) || $_POST['page_template'] != $meta['template'] )
			) {
				continue;
			}


			if (
				$meta['type'] != 'html' &&
				$meta['type'] != 'post_title' &&
				$meta['type'] != 'post_excerpt' &&
				isset( $_POST[ $meta_key . '_value' ]
				)
			) {
				$new_data = $_POST[ $meta_key . '_value' ];

				if ( $meta['type'] === 'timecode' && ! empty( $new_data ) ) {
					$new_data = strtotime( $new_data );

					if ( empty( $new_data ) ) {
						continue;
					}
				}

				$curr_data = get_post_meta( $pid, $meta_key, TRUE );

				if ( empty( $new_data ) ) {
					delete_post_meta( $pid, $meta_key );
				} elseif ( $new_data != $curr_data ) {
					update_post_meta( $pid, $meta_key, $new_data );
				}
			} else {
				delete_post_meta( $pid, $meta_key );
			}

		endforeach;

		if ( 'pcc-product' === self::$post_type ) {

			/*
			 * Coherent post_excerpt
			 */

			$excerpt = isset( $_POST['post_excerpt'] ) ? trim( $_POST['post_excerpt'] ) : '';
			if ( empty( $excerpt ) ) {
				$excerpt = strip_tags( str_replace( PHP_EOL, '', $_POST['pcc_prod_long_text_value'] ) );
				$excerpt = implode( ' ', array_slice( explode( ' ', $excerpt ), 0, 19 ) );
			} else {
				$excerpt = strip_tags( str_replace( PHP_EOL, '', $_POST['post_excerpt'] ) );
			}

			remove_action( 'save_post', array( $this, 'save_fields' ) );
			wp_update_post( array(
				'ID'           => $pid,
				'post_excerpt' => $excerpt
			) );
			add_action( 'save_post', array( $this, 'save_fields' ) );

			/*
			 * Set the image for the product cat as the current product featured image
			 */

			$feat_img = wp_get_attachment_image_src( proper_meta( '_thumbnail_id', $pid ), 'medium' );
			if ( $feat_img[0] ) {
				$terms = get_the_terms( get_the_ID(), 'pcc-product-cat' );
				if ( ! empty( $terms[0] ) ) {
					$term_meta          = proper_get_term_meta( $terms[0]->term_id );
					$term_meta['image'] = $feat_img[0];
					update_option( 'taxonomy_meta_' . $terms[0]->term_id, $term_meta );
				}
			}

			/*
			 * Default stats
			 */

			if ( proper_meta( 'pcc_product_stat_requested_qty', $pid ) === '' ) {
				update_post_meta( $pid, 'pcc_product_stat_requested_qty', 0 );
			}

			if ( proper_meta( 'pcc_product_stat_requested', $pid ) === '' ) {
				update_post_meta( $pid, 'pcc_product_stat_requested', 0 );
			}
		}

	}

	/**
	 * Display the meta fields
	 */
	public static function display_fields () {

		global $post, $pagenow;

		wp_nonce_field( 'pcc_post_meta_nonce', '__wpnonce_pcc_meta_fields' );

		echo '<table class="form-table pcc-meta-fields">';

		foreach ( self::$meta_fields as $meta_key => $meta ) {

			// Should the field be displayed?
			if ( isset( $meta['display'] ) && ! $meta['display'] ) {
				continue;
			}

			// Are we on the right post ID?
			if ( isset( $meta['pid'] ) && get_the_ID() !== $meta['pid'] ) {
				continue;
			}

			// Creating path-specific meta fields
			if ( ! empty( $meta['path'] ) ) {
				if ( is_array( $meta['path'] ) ) {
					if ( ! in_array( $post->post_name, $meta['path'] ) ) {
						continue;
					}
				} elseif ( $post->post_name != $meta['path'] ) {
					continue;
				}
			}

			// Are we on the right page template?
			if ( ! empty( $meta['template'] ) ) {
				$template = get_post_meta( get_the_ID(), '_wp_page_template', TRUE );
				if ( $template !== $meta['template'] ) {
					continue;
				}
			}

			// Show disabled fields as uneditable
			$field_insert = '';
			if ( ! empty( $meta['disabled'] ) ) {
				$field_insert .= ' style="color: #999"';
			}

			// Get the current data stored for this field, if any
			$curr_value = get_post_meta( get_the_ID(), $meta_key, TRUE );
			if ( $pagenow === 'post-new.php' ) {
				$curr_value = isset( $meta['default'] ) ? $meta['default'] : '';
			}

			if ( in_array( $meta['type'], array( 'textarea', 'post_excerpt' ) ) ) {
				printf(
					'<tr><td colspan="2" class="meta-type-textarea"><label for="%s">%s</label>',
					$meta_key,
					$meta['title']
				);
			} else {
				printf(
					'<tr><th scope="row"><label for="%s">%s</label></th><td>',
					$meta_key,
					$meta['title']
				);
			}

			switch ( $meta['type'] ) :

				/*
				 * Standard and HTML5 text fields
				 */
				case 'text':
				case 'number':
				case 'email':
				case 'url':
				case 'password':
					printf(
						'<input type="%s" name="%s_value" id="%s" value="%s" class="large-text">',
						$meta['type'],
						$meta_key,
						$meta_key,
						esc_attr( $curr_value )
					);
					break;

				/*
				 * Basic textarea
				 */
				case 'textarea':
					printf(
						'<textarea name="%s_value" id="%s" cols="40" rows="6" class="large-text" %s>%s</textarea>',
						$meta_key,
						$meta_key,
						isset( $meta['disabled'] ) && $meta['disabled'] ? ' disabled' : '',
						$curr_value
					);
					break;


				/*
				 * Single and multiple checkboxes
				 */
				case 'checkbox':

					// Allows for a callback for checkbox options
					if ( isset( $meta['options'] ) && is_string( $meta['options'] ) && function_exists( $meta['options'] ) ) {
						$meta['options'] = $meta['options']();
					}

					// Single checkbox, yes/no
					if ( empty( $meta['options'] ) || ! is_array( $meta['options'] ) ) {
						printf(
							'<input type="checkbox" name="%s_value" id="%s" value="1"%s>',
							$meta_key,
							$meta_key,
							$curr_value == 1 ? 'checked="checked"' : ''
						);

						// Multiple checkboxes
					} else {
						$count = 0;
						foreach ( $meta['options'] as $opk => $opv ) {
							printf(
								'<label class="%s">
									<input type="checkbox" name="%s_value[]" value="%s" id="%s"%s> %s
								</label> &nbsp;&nbsp;&nbsp;',
								esc_attr( $meta_key . '_' . $count ),
								$meta_key,
								$opk,
								esc_attr( $meta_key . '_' . $count ),
								! empty( $curr_value ) && in_array( $opk, $curr_value ) ? ' checked' : '',
								$opv
							);
							$count++;
						}
					}
					break;

				/*
				 * Drop-down menu
				 */
				case 'select':
					printf(
						'<select name="%s_value" id="%s">%s</select>',
						$meta_key,
						$meta_key,
						proper_build_options( $meta['options'], TRUE, $curr_value )
					);
					break;

				/*
				 * Single and multiple radio buttons
				 */
				case 'radio':

					// Allows for a callback for select options
					if ( ! is_array( $meta['options'] ) && function_exists( $meta['options'] ) ) {
						$meta['options'] = $meta['options']();
					}

					if ( empty( $meta['options'] ) || ! is_array( $meta['options'] ) ) {
						continue;
					}

					$count = 0;
					foreach ( $meta['options'] as $opk => $opv ) {

						printf(
							'<label for="%s">
								<input type="radio" name="%s_value" id="%s" value="%s"%s> %s
							</label> &nbsp;&nbsp;&nbsp;',
							esc_attr( $meta_key . '_' . $count ),
							esc_attr( $meta_key ),
							esc_attr( $meta_key . '_' . $count ),
							esc_attr( $opk ),
							( $opk == $curr_value ? ' checked' : '' ),
							$opv
						);
						$count ++;
					}
					break;

				/*
				 * Converts timecode to date on output
				 */
				case 'timecode':
					printf(
						'<input type="text" name="%s_value" id="%s" value="%s" class="large-text" %s>',
						$meta_key,
						$meta_key,
						( ! empty( $curr_value ) ? date( 'n/j/Y', $curr_value ) : '' ),
						$field_insert
					);
					break;

				/*
				 * Alternate post title field
				 */
				case 'post_title':
					printf(
						'<input type="text" name="post_title" id="title" value="%s" class="large-text">',
						$post->post_title
					);
					break;

				/*
				 * Alternate post except field
				 */
				case 'post_excerpt':
					printf(
						'<textarea name="post_excerpt" id="post_excerpt_alt" rows="4" class="large-text">%s</textarea>',
						$post->post_excerpt
					);
					break;

				/*
				 * Image upload and display on-page
				 */
				case 'image':

					$curr_img = wp_get_attachment_image_src( $curr_value, 'mobile-home' );

					printf(
						'<input type="hidden" name="%s_value" id="%s" value="%s">
						<input type="button" class="button meta-image-button" value="Select Image" data-meta-id="%s">',
						$meta_key,
						$meta_key,
						$curr_value,
						$meta_key
					);

					printf(
						'<img src="%s" class="meta-field-img-preview alignright %s" width="200" id="%s_img">',
						isset( $curr_img[0] ) ? esc_url( $curr_img[0] ) : '',
						empty( $curr_value ) ? 'hidden' : '',
						$meta_key
					);

					break;

				/*
				 * Function to display the meta field
				 * Can be a callback name in the meta or a standard one based on the meta key
				 */
				case 'callback':
					$method_name = 'cb_' . $meta_key;
					$function_name = 'pcc_meta_cb_' . $meta_key;

					if ( isset( $meta['callback'] ) && function_exists( $meta['callback'] ) ) {

						$meta['callback']( $meta_key, $curr_value );

					} else if ( method_exists( 'ProperCustomFields', $method_name ) ) {

						ProperCustomFields::$method_name( $meta_key, $curr_value );

					} elseif ( function_exists( $function_name ) ) {

						$function_name( $meta_key, $curr_value );

					} else {
						echo 'Function "' . $function_name . '" could not be found ...';
					}

					break;

				/*
				 * A drop-down menu of pages on the site
				 */
				case 'page_select':
					wp_dropdown_pages( array(
						'echo' => TRUE,
						'id'   => $meta_key,
						'name' => $meta_key . '_value',
						'selected' => $curr_value,
						'exclude' => '5'
					) );
					break;

			endswitch;

			if ( ! empty( $meta['description'] ) ) {
				if ( $meta['type'] === 'text_only' ) {
					echo '<p>';
				} else {
					echo '<p class="description">';
				}
				echo $meta['description'] . '</p>';
			}

			echo '</td></tr>';

		}
		echo '</table>';
	}

	/**
	 * Hides core UC meta fields from the custom fields UI
	 *
	 * @param $protected
	 * @param $meta_key
	 *
	 * @return bool
	 */
	public function protected_meta ( $protected, $meta_key ) {

		if ( ! is_array( self::$meta_fields ) ) {
			return $protected;
		}

		switch ( $meta_key ) {
			case in_array( $meta_key, array_keys( self::$meta_fields ) ):
				return TRUE;
				break;
		}

		return $protected;
	}
}


/**
 * Meta fields for pages
 *
 * @return array
 */
function page_meta_array () {
	return array(

		/*
		 * Home page
		 */

		'home_meta_key'      => array(
			'title'       => __( 'Title', 'proper-start' ),
			'description' => __( 'Description', 'proper-start' ),
			'type'        => 'text',
			'display'     => TRUE,
			'path'        => 'home'
		),

		/*
		 * All pages
		 */

		'page_meta_title' => array(
			'title'       => __( 'Page meta title', 'proper-start' ),
			'description' => __( 'No more than 70 characters' ),
			'type'        => 'text',
			'display'     => TRUE,
		),
		'page_meta_desc'  => array(
			'title'       => __( 'Page meta description', 'proper-start' ),
			'description' => __( 'No more than 160 characters' ),
			'type'        => 'textarea',
			'display'     => TRUE,
		),
	);
}

/**
 * Meta fields for posts
 *
 * @return array
 */
function post_meta_array () {
	return array(
		'pcc_page_meta_title' => array(
			'title'       => __( 'Post meta title', 'proper-start' ),
			'description' => __( 'No more than 70 characters' ),
			'type'        => 'text',
			'display'     => TRUE,
		),
		'pcc_page_meta_desc'  => array(
			'title'       => __( 'Post meta description', 'proper-start' ),
			'description' => __( 'No more than 160 characters' ),
			'type'        => 'textarea',
			'display'     => TRUE,
		),
	);
}

