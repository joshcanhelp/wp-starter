<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>

	<script>
		document.documentElement.className = document.documentElement.className.replace("no-js", "js");
	</script>

	<meta charset="<?php bloginfo( 'charset' ) ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!-- start of wp_head() -->
	<?php wp_head() ?>
	<!-- end of wp_head() -->

</head>
<body <?php body_class() ?>>

<header id="top" class="site-header">
	<div class="inner">
		<p>
			<?php echo get_header_image() ?>
			<a class="site-logo" href="<?php echo home_url(); ?>" title="<?php _e( 'Home', 'allons-y' ) ?>">
				<img alt="<?php
				echo esc_attr( get_bloginfo( 'name' ) ); ?> logo" src="<?php

				if ( get_header_image() ) {
					echo esc_url( get_header_image() );
				} else {
					echo esc_url( allonsy_theme_img( 'default-img-logo.png' ) );
				}

				?>">
			</a>
		</p>

		<p>
			<a href="#" id="js-allonsy-get-latest-post" data-nonce="<?php
			echo sanitize_text_field( wp_create_nonce( 'allonsy_get_latest_post' ) ) ?>"><?php
				_e( 'Get latest post!', 'allons-y' ); ?></a>
		</p>

		<?php if ( get_option( 'site_twitter_name' ) ) : ?>

			<p>
				<a href="https://twitter.com/<?php
				echo allonsy_sanitize_twitter( get_option( 'site_twitter_name' ) ) ?>"><?php
					_e( 'Follow us!', 'allons-y' ); ?></a>
			</p>

		<?php endif; ?>

		<?php if ( get_option( 'site_facebook_url' ) ) : ?>

			<p>
				<a href="<?php
				echo esc_url( get_option( 'site_facebook_url' ) ) ?>"><?php
					_e( 'Like us!', 'allons-y' ); ?></a>
			</p>

		<?php endif; ?>
	</div>
</header>

<?php get_template_part( 'partials/block', 'breadcrumbs' ); ?>

<div id="content">