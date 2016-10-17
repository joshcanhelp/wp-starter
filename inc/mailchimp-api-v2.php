<?php
/**
 * Example of subscribing to Mailchimp via API
 * This is using the v2 API and needs to get updated to the v3 version
 *
 * @package    WordPress
 * @subpackage AllonsYFramework
 */

/**
 * Do not allow this file to be loaded directly
 */

if ( ! function_exists( 'add_action' ) ) {
	die( 'Nothing to do...' );
}

/**
 * Exmaple function attached to a non-existent hook showing subscription to Mailchimp
 * This will need, from somewhere:
 *
 * - A list ID where the person is subscribing
 * - A Mailchimp API key that includes the datacenter after a dash
 * - An email address
 *
 * @param $pid
 * @param $post
 */

function allonsy_hook_mcapi_subscribe( $pid, $post ) {

	// Need a list ID from somewhere, this is found in the Mailchimp admin

	$list_id = '1234567890';

	// Need an API key from somewhere, probably from a site option

	$mc_api_key = get_option( 'mailchimp_api_key', '' );

	// Get the DC (datacenter) from the API key
	// Yes, 0 position or false

	$mc_api_dc = '';
	if ( strpos( $mc_api_key, '-' ) ) {
		$mc_api_dc = explode( '-', $mc_api_key )[1];
	}

	// Need an email address

	$email_address = ! empty( $_GET['email'] ) ? filter_var( $_GET['email'], FILTER_SANITIZE_EMAIL ) : '';

	// Do we have everything we need?

	if ( $list_id && $mc_api_key && $mc_api_dc && $email_address ) {

		// Sending to Mailchimp

		$data = array(

			// Required data from above
			'id' => $list_id,
			'apikey' => $mc_api_key,
			'email' => array(
				'email' => $email_address
			),

			// Add groups
			'merge_vars' => array(
				'groupings' => array(
					array(
						'name' => 'List Name',
						'groups' => array(
							'Group Name'
						)
					)
				)
			),

			// Options
			'email_type' => 'html',
			'double_optin' => TRUE,
			'update_existing' => FALSE,
			'replace_interests' => FALSE,
			'send_welcome' => FALSE,
		);

		// Try the call

		$mc_api_url = 'https://' . $mc_api_dc . '.api.mailchimp.com/2.0/lists/subscribe.json';

		$response = wp_remote_post(
			$mc_api_url,
			array(
				'body' => $data,
				'timeout' => 15,
				'headers' => array(
					'Accept-Encoding' => '',
				),
				'sslverify' => FALSE,
			)
		);


		// Log any errors we get

		if ( 200 != $response['response']['code'] ) {
			// Do something to log the error
		}

	}

}

add_action( 'some_super_cool_hook', 'allonsy_mcapi_hook_save_post', 10, 2 );