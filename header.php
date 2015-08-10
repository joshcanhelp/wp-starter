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

<a class="skip-link screen-reader-text" href="#main-content">
	<?php _e( 'Skip to content', 'proper-start' ); ?>
</a>

<header id="site-top" class="site-top-header">
	<div class="inner-wrapper">


	</div>
</header>

<header id="site-header" class="site-main-header">
	<div class="inner-wrapper">

		<a class="site-logo" href="<?php echo home_url(); ?>" title="<?php _e( 'Home', 'proper-start' ) ?>">
			<img src="<?php echo get_header_image() ? get_header_image() : proper_theme_img( 'logo.png' ); ?>" alt="<?php
				echo esc_attr( get_bloginfo( 'name' ) ); ?> Logo">
		</a>

	</div>
</header>

<?php get_template_part( 'partials/block', 'breadcrumbs' ); ?>