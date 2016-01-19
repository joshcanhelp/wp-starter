<?php
/**
 * WP-CLI add-on scripts. Notes for usage:
 *
 * - This will not be required for most installs
 * - See the wiki link below for information on writing WP-CLI commands
 *
 * @see https://github.com/wp-cli/wp-cli/wiki/Commands-Cookbook
 *
 * @package    WordPress
 * @subpackage AllonsYFramework
 */

final class AllonsY_WP_CLI extends WP_CLI_Command {

	public function test( $args = array(), $assoc_args = array() ) {

		WP_CLI::line( 'Processing ... ' );
		WP_CLI::line( print_r( $args, true ) );
		WP_CLI::line( print_r( $assoc_args, true ) );
		WP_CLI::success( 'Success!' );
	}
}

WP_CLI::add_command( 'allonsy', 'AllonsY_WP_CLI' );