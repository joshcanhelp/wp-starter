<?php

if ( defined( 'ABSPATH' ) ) {
	ProperSettings::init();
}

class ProperSettings
{

	private static $__instance = NULL;

	private $settings = array();
	private $default_settings = array();
	private $settings_texts = array();

	private $theme_prefix = 'proper-start';
	private $settings_page_name = null;

	public function __construct() {
		
		// Default settings page name
		$this->settings_page_name = __( 'Theme Options', 'proper-start' );

		// Settings fields with filter
		$this->settings_texts = (array) apply_filters( $this->theme_prefix . '_settings_texts', array(

			/*
			 * Global settings
			 */

			// TODO: Adjust for this site

			'section_global' => array(
				'label' => __( 'Global settings', 'proper-start' ),
				'type'  => 'section',
				'desc' => 'Settings used throughout the site'
			),
			'phone_number' => array(
				'label' => __( 'Phone number', 'proper-start' ),
				'type'  => 'text',
				'default' => '',
				'desc' => ''
			),
			'address' => array(
				'label' => __( 'Physical address', 'proper-start' ),
				'type'  => 'textarea',
				'default' => '',
				'desc' => ''
			),
			'sales_email' => array(
				'label' => __( 'Sales team email address', 'proper-start' ),
				'type'  => 'email',
				'default' => '',
				'desc' => ''
			),
			'service_email' => array(
				'label' => __( 'Customer Service email address', 'proper-start' ),
				'type'  => 'email',
				'default' => '',
				'desc' => ''
			),
			'hours' => array(
				'label' => __( 'Hours of operation', 'proper-start' ),
				'type'  => 'text',
				'default' => '',
				'desc' => ''
			),
			'error_message' => array(
				'label' => __( 'General error message', 'proper-start' ),
				'type'  => 'text',
				'default' => '',
				'desc' => ''
			),
			'nothing_found' => array(
				'label' => __( 'Text used when no content was found in a particular listing', 'proper-start' ),
				'type'  => 'textarea',
				'default' => '',
				'desc' => ''
			)
		) );

		// Default settings with filter
		foreach ( $this->settings_texts as $id => $setting ) {
			$this->default_settings[$id] = isset( $setting['default'] ) ? $setting['default'] : '';
		}

		// Current settings
		$user_settings = get_option( $this->theme_prefix . '_settings', array() );

		// after getting default settings make sure to parse the arguments together with the user settings
		$this->settings = wp_parse_args( $user_settings, $this->default_settings );

		// Init hooks
		add_action( 'admin_init', array( &$this, 'register_setting' ) );
		add_action( 'admin_menu', array( &$this, 'register_settings_page' ) );
		add_action( 'admin_notices', array( $this, 'settings_notice' ) );
	}

	public static function init() {
		self::instance();
	}

	/*
	 * Use this singleton to address methods
	 */
	public static function instance() {
		if ( is_null( self::$__instance ) ) {
			self::$__instance = new ProperSettings();
		}
		return self::$__instance;
	}

	/**
	 * Get a setting, default to empty text
	 *
	 * @param      $opt
	 * @param bool $format
	 *
	 * @return string
	 */
	public static function get( $opt, $format = FALSE ) {

		$output = isset( self::instance()->settings[ $opt ] ) ? self::instance()->settings[ $opt ] : '';

		if ( ! $format ) {
			return $output;
		} else {

			switch ( $opt ) {

				// TODO: add formatting for settings

				default:
					return $output;
			}
		}
	}

	/**
	 * Register the settings page
	 */
	public function register_settings_page() {
		add_theme_page(
			$this->settings_page_name,
			__( 'Theme Options', 'proper-start' ),
			'manage_options',
			$this->theme_prefix .'-settings',
			array( &$this, 'settings_page' )
		);
	}

	/**
	 * Register the settings group
	 */
	public function register_setting() {
		register_setting(
			$this->theme_prefix . '_settings',
			$this->theme_prefix . '_settings',
			array( &$this, 'validate_settings' )
		);
	}

	/**
	 * Validation for saving settings
	 *
	 * @param $settings
	 *
	 * @return array
	 */
	public function validate_settings( $settings ) {

		foreach ( $this->settings_texts as $id => $data ) {

			if ( $data['type'] === 'checkbox' && ! isset( $settings[ $id ] ) ) {
				$settings[ $id ] = '';
			}

			if ( $data['type'] === 'email' ) {
				$settings[ $id ] = filter_var( $settings[ $id ], FILTER_SANITIZE_EMAIL );
			}
		}

		$_REQUEST['_wp_http_referer'] = remove_query_arg( 'defaults', $_REQUEST['_wp_http_referer'] );

		return $settings;
	}

	/**
	 * Output the settings page
	 */
	public function settings_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not permission to access this page', 'proper-start' ) );
		}

		?>
		<div class="wrap">
			<h2><?php echo $this->settings_page_name; ?></h2>

			<form method="post" action="options.php">

				<?php settings_fields( $this->theme_prefix . '_settings' ); ?>

				<table class="form-table pcc-settings-table">

					<?php
					foreach ( $this->settings as $setting => $value ):

						$field_type = $this->settings_texts[ $setting ]['type'];
						$field_label = isset( $this->settings_texts[ $setting ]['label'] ) ?
							$this->settings_texts[ $setting ]['label'] : '';
						$field_name_attr = $this->theme_prefix . '_settings[' . $setting . ']';
						$field_id_attr   = $this->theme_prefix . '-' . $setting;

						$is_section = $field_type === 'section' ? TRUE : FALSE;
						?>

					<tr>
					<th scope="row">
						<label for="<?php echo $this->theme_prefix . '-' . $setting; ?>">
							<?php
							if ( $is_section ) {
								echo '<h3>' . $field_label . '</h3>';
							} else {
								echo $field_label;
							}
							?>
						</label>
					</th>

					<td>

					<?php

					switch ( $field_type ) :

						case 'textarea':
							printf(
								'<textarea name="%s" id="%s" class="widefat" rows="4">%s</textarea>',
								$field_name_attr,
								$field_id_attr,
								esc_html( $value )
							);
							break;

						case 'checkbox':
							printf(
								'<input type="checkbox" name="%s" id="%s" value="1"%s>
								<strong>%s</strong>',
								$field_name_attr,
								$field_id_attr,
								$value == 1 ? ' checked' : '',
								__( 'Yes', 'proper-start' )
							);
							break;

						case 'select':

							$select_options = '';

							foreach ( $this->settings_texts[ $setting ]['options'] as $val => $text ) {
								$select_options .= sprintf(
									'<option value="%s"%s>%s</option>',
									$val,
									$val === $value ? ' selected' : '',
									$text
								);
							}

							printf(
								'<select name="%s" id="%s">%s</select>',
								$field_name_attr,
								$field_id_attr,
								$select_options
							);
							break;

						case 'text':
						case 'url':
						case 'email':
						case 'number':
						case 'password':

							printf(
								'<input type="%s" name="%s" id="%s" class="%s" value="%s" min="0">',
								$field_type,
								$field_name_attr,
								$field_id_attr,
								$field_type === 'number' ? 'postform' : 'widefat',
								esc_attr( $value )
							);
							break;

						default:
							printf(
								'<p class="description">%s</p>',
								$this->settings_texts[ $setting ]['desc']
							);


					endswitch;
					?>

					<?php
					if ( ! $is_section ) {
						printf(
							'<p class="description">%s</p>',
							$this->settings_texts[ $setting ]['desc']
						);
					}
					?>

					</td>
					</tr>
					<?php endforeach; ?>
				</table>

				<?php
				printf(
					'<p class="submit"><input type="submit" name="%s-submit" class="button-primary" value="%s"></p>',
					$this->theme_prefix,
					__( 'Save Changes', 'proper-start' )
				);
				?>

			</form>
		</div>

	<?php
	}

	/**
	 * Admin notice for saving or resetting options
	 */
	public function settings_notice () {
		if (
			! empty( $_GET['settings-updated'] ) && ! empty( $_GET['page'] ) &&
			$_GET['page'] === $this->theme_prefix . '-settings' &&
			$_GET['settings-updated'] == 'true'

		) {
			?>
			<div class="updated">
				<?php if ( isset( $_GET['defaults'] ) && $_GET['defaults'] == 'true' ) : ?>
					<p><?php _e( 'Restored to defaults!', 'proper-start' ); ?></p>
				<?php else : ?>
					<p><?php _e( 'Settings updated!', 'proper-start' ); ?></p>
				<?php endif ?>
			</div>
		<?php
		}
	}
}