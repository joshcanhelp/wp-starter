<?php

final class ProperCliAddOns extends WP_CLI_Command {

	public function test ( $args = array(), $assoc_args = array() ) {
		WP_CLI::line( 'Starting...' );
		WP_CLI::success( 'Ending!' );
	}

}

WP_CLI::add_command( 'proper-start', 'ProperCliAddOns' );