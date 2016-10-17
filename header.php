<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>

	<script>document.documentElement.className = document.documentElement.className.replace("no-js", "js")</script>

	<meta charset="<?php bloginfo( 'charset' ) ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<!-- start of wp_head() -->
	<?php wp_head() ?>
	<!-- end of wp_head() -->

</head>
<body <?php body_class() ?>>

<header id="top" class="site-header" id="js-site-header">
	<div class="inner">

		<a class="site-logo" href="<?php echo home_url(); ?>" title="<?php _e( 'Home', 'allons-y' ) ?>">
			<?php if ( get_header_image() ) : ?>
				<img alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> logo" src="<?php
				echo esc_url( get_header_image() ); ?>">
			<?php else : ?>
				<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>
			<?php endif; ?>
		</a>

		<?php if ( $tagline = get_bloginfo( 'description' ) ) : ?>
		<p><?php echo sanitize_text_field( $tagline ) ?></p>
		<?php endif; ?>

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

<?php
wp_nav_menu(
	[
		// Declared in functions.php
		'theme_location' => 'main',

		// Class and id attributes added to navigation element
		'menu_class' => 'site-nav-main inner',

		// Wrapper HTML
		'container' => 'nav',
		'container_class' => 'site-nav-main-wrap',
		'container_id' => 'js-site-nav-main',

		// Number of levels deep to show items
		'depth' => 2,

		// This is the default wrap but allows additional markup to be added, if needed
		'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',

		// Do not display if there are no menu items
		'fallback_cb' => FALSE,
	]
);
?>

<?php get_template_part( 'partials/block', 'breadcrumbs' ); ?>

<div id="content">