<?php

/**
 * Class AllonsyLogIt
 *
 * This class makes logging super-easy with automatic formatting, easy storage, and formatted output.
 * Use it if you need to, otherwise it will be here waiting for when you do.
 */

class AllonsyLogIt {

	private $log_text;

	/**
	 * Accept logging text as a starter
	 *
	 * @param string $log_text - logging text to start with
	 */

	public function __construct( $log_text = '' ) {
		$this->log_text = $log_text;
	}


	/**
	 * Append to running log text
	 *
	 * @param string $text
	 * @param string $val
	 * @param int    $line
	 */

	public function log ( $text = '', $val ='', $line = 0 ) {

		$this->log_text .= sprintf(
			'::: %s %s %s' . PHP_EOL,
			$text ? $text : '[NO LABEL]',
			is_object( $val ) || is_array( $val ) ? print_r( $val, TRUE ) : ' = ' . $val,
			$line ? '(L:' . $line . ')' : ''
		);
	}


	/**
	 * Save the current log text based on the type
	 *
	 * @param string $type
	 * @param int    $id
	 * @param string $val
	 */

	public function store ( $type = 'post', $id = 0, $val = '_mp_logging' ) {

		if ( $type === 'post' && $id ) {
			update_post_meta( $id, $val, $this->log_text );
		} else if ( $type === 'user' && $id ) {
			update_user_meta( $id, $val, $this->log_text );
		} else {
			update_option( $val, $this->log_text );
		}
	}


	/**
	 * Output formatted logging text
	 *
	 */

	public function output () {

		$log_text_pieces = explode( '::: ', $this->log_text );

		foreach ( $log_text_pieces as $log_text_piece ) {

			if ( empty( $log_text_piece ) ) {
				continue;
			}

			printf(
				'&rsaquo; %s<br>',
				wp_kses(
					$log_text_piece,
					[
						'pre' => [ ],
						'br' => [ ]
					]
				)
			);
		}
	}
}
