<?php
/**
 * Hooks, filters, and display functions to affect user profile fields and editing
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
 * The filter below adds simple text fields to the Contact Methods section of the user edit page
 * To add custom types or a new section, see allonsy_extra_profile_fields() below
 *
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/user_contactmethods
 *
 * @param $contact_fields
 *
 * @return mixed
 */
function allonsy_hook_user_contactmethods( $fields ) {

	// Adding new fields

	$fields['user_phone']    = __( 'Phone Number (with country code)', 'allons-y' );
	$fields['user_company']  = __( 'Company Name', 'allons-y' );
	$fields['user_location'] = __( 'Location, State or Country', 'allons-y' );
	$fields['user_twitter'] = __( 'Twitter username', 'allons-y' );

	// Removing existing ones

	unset( $fields['jabber'] );
	unset( $fields['yim'] );
	unset( $fields['aim'] );

	return $fields;
}

add_filter( 'user_contactmethods', 'allonsy_hook_user_contactmethods', 100 );


/**
 * This action adds a separate section of fields on the user profile
 *
 * @param $user
 */

function allonsy_extra_profile_fields( $user ) {
	?>
	<h3>Extra Profile Information</h3>
	<table class="form-table">
		<tr>
			<th><label for="allonsy-user-text-field"><?php _e( 'Text field', 'allons-y' ) ?></label></th>
			<td>
				<select name="allonsy_text_field" id="allonsy-user-text-field">
					<?php
					echo allonsy_build_options(
						array(
							'one' => __( 'One', 'allons-y' ),
							'two' => __( 'Two', 'allons-y' ),
							'three' => __( 'Three', 'allons-y' ),
						),
						'Select one ...',
						get_the_author_meta( 'allonsy_text_field', $user->ID )
					);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="allonsy-user-checkbox"><?php _e( 'Checkbox', 'allons-y' ) ?></label></th>
			<td>
				<input type="checkbox" name="allonsy_checkbox" id="allonsy-user-checkbox" value="1" <?php
				checked( 1, get_the_author_meta( 'allonsy_checkbox', $user->ID ) ); ?>>
			</td>
		</tr>
	</table>
<?php
}

add_action( 'show_user_profile', 'allonsy_extra_profile_fields', 1 );
add_action( 'edit_user_profile', 'allonsy_extra_profile_fields', 1 );

/**
 * Save the fields output above
 *
 * @param $user_id
 */
function allonsy_save_extra_profile_fields( $user_id ) {

	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	update_user_meta( $user_id, 'allonsy_text_field', sanitize_text_field( $_POST['allonsy_text_field'] ) );
	update_user_meta( $user_id, 'allonsy_checkbox', ! empty( $_POST['allonsy_checkbox'] ) ? 1 : '' );
}

add_action( 'personal_options_update', 'allonsy_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'allonsy_save_extra_profile_fields' );