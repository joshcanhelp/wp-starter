<?php

/*
 * Functions tied to hooks related ot user management
 * TODO: Review these and delete unnecessary ones
 */

/**
 * Adding user meta fields
 *
 * @param $contact_fields
 *
 * @return mixed
 */
function proper_hook_user_contactmethods ( $contact_fields ) {

	// Adding new fields
	$contact_fields['user_phone']    = 'Phone Number (with country code)';
	$contact_fields['user_company']   = 'Company Name';
	$contact_fields['user_location'] = 'Location, State or Country';

	return $contact_fields;
}

add_filter( 'user_contactmethods', 'proper_hook_user_contactmethods' );

/**
 * Add special PCC profile fields
 *
 * @param $user
 */
function proper_extra_profile_fields ( $user ) {
	?>
	<h3>Extra Profile Information</h3>
	<table class="form-table">
		<tr>
			<th><label for="pcc-referral">How did you hear about us?</label></th>
			<td>
				<select name="pcc_referral" id="pcc-referral">
					<?php
					echo proper_build_options(
						proper_get_textarea_options( ProperSettings::get( 'referral_source' ) ),
						TRUE,
						proper_user_meta( 'pcc_referral' )
					);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="pcc-industry">Industry</label></th>
			<td>
				<select name="pcc_industry" id="pcc-industry">
					<?php
					echo proper_build_options(
						proper_get_textarea_options( ProperSettings::get( 'customer_industry' ) ),
						TRUE,
						proper_user_meta( 'pcc_industry' )
					);
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="pcc-email-updates">Receive email updates?</label></th>
			<td>
				<input type="checkbox" name="pcc_email_updates" id="pcc-email-updates" value="1" <?php
					checked( 1, get_the_author_meta( 'pcc_email_updates', $user->ID ) ); ?>>
			</td>
		</tr>
	</table>
<?php
}

add_action( 'show_user_profile', 'proper_extra_profile_fields', 1 );
add_action( 'edit_user_profile', 'proper_extra_profile_fields', 1 );

/**
 * Save additional PCC profile fields
 *
 * @param $user_id
 */
function pcc_save_extra_profile_fields ( $user_id ) {

	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	update_user_meta( $user_id, 'pcc_referral', sanitize_text_field( $_POST['pcc_referral'] ) );
	update_user_meta( $user_id, 'pcc_industry', sanitize_text_field( $_POST['pcc_industry'] ) );
	update_user_meta( $user_id, 'pcc_end_user', ! empty( $_POST['pcc_end_user'] ) ? 1 : '' );
	update_user_meta( $user_id, 'pcc_email_updates', ! empty( $_POST['pcc_email_updates'] ) ? 1 : '' );
}

add_action( 'personal_options_update', 'pcc_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'pcc_save_extra_profile_fields' );

/**
 * Keep subscribers out of the back-end
 */
function proper_hook_force_front_end () {

    if ( ! current_user_can( 'edit_posts' ) ) {
		wp_redirect( home_url( 'account' ) );
		exit;
    }
}

add_action( 'admin_init', 'proper_hook_force_front_end' );

/**
 * Hide the admin bar for subscribers
 */
function proper_hook_hide_admin_bar () {

	// Hide admin bar for subscribers
	if ( ! current_user_can( 'edit_posts' ) ) {
		add_filter( 'show_admin_bar', '__return_false' );
	}
}

add_action( 'init', 'proper_hook_hide_admin_bar' );

/**
 * Login page logo points back to homepage
 *
 * @return string|void
 */
function proper_filter_login_headerurl () {
	return home_url();
}

add_filter( 'login_headerurl', 'proper_filter_login_headerurl' );



/**
 * Allow login by email address
 *
 * @param  obj    $user
 * @param  string $username [description]
 * @param  string $password [description]
 *
 * @return boolean
 */
function proper_allow_email_login ( $user, $username, $password ) {

	if ( is_email( $username ) ) {
		$user = get_user_by( 'email', $username );
		if ( $user ) {
			$username = $user->user_login;
		}
	}

	return wp_authenticate_username_password( NULL, $username, $password );
}

add_filter( 'authenticate', 'proper_allow_email_login', 20, 3 );


/**
 * Change username label on wp-login.php
 *
 * @param $translated_text
 * @param $text
 * @param $domain
 *
 * @return string
 */
function proper_username_field ( $translated_text, $text, $domain ) {

	// Login page only
	if ( 'wp-login.php' != basename( $_SERVER['SCRIPT_NAME'] ) ) {
		return $translated_text;
	}

	if ( "Username" == $translated_text ) {
		$translated_text = __( 'Email Address' );
	}

	return $translated_text;
}

add_filter( 'gettext', 'proper_username_field', 20, 3 );

/**
 * View account link on User list
 *
 * @param $actions
 * @param $user_object
 *
 * @return mixed
 */
function proper_admin_view_user_link ( $actions, $user_object ) {

	if ( isset( $actions['delete'] ) ) {
		$delete_link = $actions['delete'];
		unset( $actions['delete'] );
	}

	$actions['view profile'] = '<a href="' . home_url( '/account/' . $user_object->ID ) . '">View</a>';

	if ( ! empty( $delete_link ) ) {
		$actions['delete'] = $delete_link;
	}

	return $actions;

}

add_filter( 'user_row_actions', 'proper_admin_view_user_link', 1, 2 );

