<?php
/*
 * TODO: Edit these down
 */


/**
 * Register the settings page
 */

function pcc_debug_page () {
	add_management_page( 'DEBUG', 'DEBUG', 'manage_options', 'pcc-debug', 'pcc_debug_page_display' );
}

add_action( 'admin_menu', 'pcc_debug_page' );

function pcc_debug_page_display () {

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not permission to access this page', 'pccomposites' ) );
	}
	?>
	<div class="wrap">
		<h2>Debug</h2>

		<hr>

		<h3>Dump content</h3>

		<form>
			<?php wp_nonce_field( 'pcc-dump-products', '__wpnonce_pcc_dump_products' )?>
			<input type="submit" value="Kill Products">
		</form>

		<form>
			<?php wp_nonce_field( 'pcc-dump-carts', '__wpnonce_pcc_dump_carts' )?>
			<input type="submit" value="Kill Carts">
		</form>

		<form>
			<?php wp_nonce_field( 'pcc-dump-stats', '__wpnonce_pcc_dump_stats' )?>
			<input type="submit" value="Reset Stats">
		</form>

		<form>
			<?php wp_nonce_field( 'pcc-search-refresh', '__wpnonce_pcc_search_refresh' )?>
			<input type="submit" value="Search Refresh">
		</form>
	</div>
	<?php

}

/**
 * Kill products action
 */
function pcc_debug_hook_admin_init (  ) {

	/*
	 * Delete all products and attachments
	 */
	if ( isset( $_REQUEST['__wpnonce_pcc_dump_products'] ) ) {
		if ( ! wp_verify_nonce( $_REQUEST['__wpnonce_pcc_dump_products'], 'pcc-dump-products' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not permission to access this page', 'pccomposites' ) );
		}

		foreach (
			get_posts( array(
				'post_type'      => 'pcc-product',
				'posts_per_page' => - 1,
				'post_status'    => 'any'
			) ) as $prod
		) {

			if ( ! empty( $prod->ID ) ) {
				foreach (
					get_posts( array(
						'post_type'      => 'attachment',
						'posts_per_page' => - 1,
						'post_status'    => 'any',
						'post_parent'    => $prod->ID
					) ) as $attach
				) {
					wp_delete_post( $attach->ID, TRUE );
				}
			}

			wp_delete_post( $prod->ID, TRUE );
		}

		wp_redirect( admin_url( 'tools.php?page=pcc-debug&prod-delete=1' ) );
		die();
	}

	/*
	 * Delete all carts
	 */
	if ( isset( $_REQUEST['__wpnonce_pcc_dump_carts'] ) ) {
		if ( ! wp_verify_nonce( $_REQUEST['__wpnonce_pcc_dump_carts'], 'pcc-dump-carts' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not permission to access this page', 'pccomposites' ) );
		}

		foreach (
			get_posts( array(
				'post_type'      => 'pcc-cart',
				'posts_per_page' => - 1,
				'post_status'    => 'any'
			) ) as $prod
		) {

			wp_delete_post( $prod->ID, TRUE );
		}

		wp_redirect( admin_url( 'tools.php?page=pcc-debug&cart-delete=1' ) );
		die();
	}

	/*
	 * Reset all stats
	 */
	if ( isset( $_REQUEST['__wpnonce_pcc_dump_stats'] ) ) {
		if ( ! wp_verify_nonce( $_REQUEST['__wpnonce_pcc_dump_stats'], 'pcc-dump-stats' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not permission to access this page', 'pccomposites' ) );
		}

		foreach (
			get_posts( array(
				'post_type'      => 'pcc-product',
				'posts_per_page' => - 1,
				'post_status'    => 'any'
			) ) as $prod
		) {

			delete_post_meta( $prod->ID, 'pcc_product_stat_cart_add' );
			delete_post_meta( $prod->ID, 'pcc_product_stat_requested_qty' );
			delete_post_meta( $prod->ID, 'pcc_product_stat_requested' );
		}

		wp_redirect( admin_url( 'tools.php?page=pcc-debug&stat-delete=1' ) );
		die();
	}

	/*
	 * Refresh search data
	 */
	if ( isset( $_REQUEST['__wpnonce_pcc_search_refresh'] ) ) {

		if ( ! wp_verify_nonce( $_REQUEST['__wpnonce_pcc_search_refresh'], 'pcc-search-refresh' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not permission to access this page', 'pccomposites' ) );
		}

		foreach ( get_posts( 'post_type=pcc-product&posts_per_page=-1' ) as $prod ) {
			pcc_save_search_terms( $prod->ID );
		}

		wp_redirect( admin_url( 'tools.php?page=pcc-debug&search-refresh=1' ) );
		die();
	}



}

add_action( 'admin_init', 'pcc_debug_hook_admin_init' );


/**
 * Messages
 */
function pcc_debug_admin_msg () {

	if ( ! empty( $_GET['prod-delete'] ) ) {
		?>
		<div class="updated">
			<p><?php _e( 'Products deleted!', 'pccomposites' ); ?></p>
		</div>
		<?php
	}

	if ( ! empty( $_GET['cart-delete'] ) ) {
		?>
		<div class="updated">
			<p><?php _e( 'Carts deleted!', 'pccomposites' ); ?></p>
		</div>
		<?php
	}

	if ( ! empty( $_GET['stat-delete'] ) ) {
		?>
		<div class="updated">
			<p><?php _e( 'Stats reset!', 'pccomposites' ); ?></p>
		</div>
		<?php
	}

	if ( ! empty( $_GET['search-refresh'] ) ) {
		?>
		<div class="updated">
			<p><?php _e( 'Search data refreshed!', 'pccomposites' ); ?></p>
		</div>
		<?php
	}

}

add_action( 'admin_notices', 'pcc_debug_admin_msg' );