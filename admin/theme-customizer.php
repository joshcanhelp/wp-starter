<?php
/**
 * Theme settings managed in the customizer
 *
 * @see        https://developer.wordpress.org/themes/advanced-topics/customizer-api/
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
 * Add and change Customizer panels, sections, settings, and mods
 *
 * @param $wp_customize
 */
function allonsy_customize_register( $wp_customize ) {

	/**
	 * Customizer sections. From the Codex:
	 *
	 * "Sections are UI containers for Customizer controls."
	 *
	 * @see https://developer.wordpress.org/themes/advanced-topics/customizer-api/
	 */

	$wp_customize->add_section(
		'allonsy_theme_mods',
		array(
			'title'       => __( 'Theme Mods', 'allons-y' ),
			'description' => __( 'Changes to the site branding, colors, and other styles', 'allons-y' ),
			'capability'  => 'edit_theme_options',
		)
	);

	$wp_customize->add_section(
		'allonsy_site_options',
		array(
			'title'       => __( 'Site Options', 'allons-y' ),
			'description' => __( 'Social media accounts, site text, and other non-style options', 'allons-y' ),
			'capability'  => 'manage_options',
		)
	);

	/**
	 * Options used for non-visual modifications. Types of things used here:
	 *
	 * - API codes
	 * - Account names
	 * - Social URLs
	 *
	 * From the Codex:
	 *
	 * "Options are stored directly in the wp_options table of the WordPress database
	 *  and apply to the site regardless of the active theme."
	 *
	 * @see https://developer.wordpress.org/themes/advanced-topics/customizer-api/#settings
	 */

	/**
	 * Google Analytics account number
	 */

	$wp_customize->add_setting(
		'site_google_analytics_account',
		array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'site_google_analytics_account',
		array(
			'type'        => 'text',
			'section'     => 'allonsy_site_options',
			'label'       => __( 'Google Analytics account number', 'allons-y' ),
			'description' => __( 'Account number directly from Google Analytics, should start with UA', 'allons-y' ),
		)
	);

	/**
	 * Twitter username
	 */

	$wp_customize->add_setting(
		'site_twitter_name',
		array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'transport'         => 'refresh',
			'sanitize_callback' => 'allonsy_sanitize_twitter',
		)
	);

	$wp_customize->add_control(
		'site_twitter_name',
		array(
			'type'        => 'text',
			'section'     => 'allonsy_site_options',
			'label'       => __( 'Twitter name', 'allons-y' ),
			'description' => __( 'Just the Twitter name, no @ sign', 'allons-y' ),
		)
	);

	/**
	 * Facebook page/profile URL
	 */

	$wp_customize->add_setting(
		'site_facebook_url',
		array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url',
		)
	);

	$wp_customize->add_control(
		'site_facebook_url',
		array(
			'type'        => 'text',
			'section'     => 'allonsy_site_options',
			'label'       => __( 'Facebook page or profile', 'allons-y' ),
			'description' => __( 'Direct URL to a Facebook page or profile', 'allons-y' ),
		)
	);

	/**
	 * Theme mods used for visual changes to the site. Types of things used here:
	 *
	 * - Colors
	 * - Typography
	 *
	 * From the Codex:
	 *
	 * "Theme mods, on the other hand, are specific to a particular theme. Most theme options should be theme_mods. "
	 *
	 * @see https://developer.wordpress.org/themes/advanced-topics/customizer-api/#settings
	 */

	/*
	 * Main font color
	 */

	$wp_customize->add_setting(
		'site_base_color',
		array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '#333333',
		)
	);

	$wp_customize->add_control(
		'site_base_color',
		array(
			'type'        => 'text',
			'section'     => 'allonsy_theme_mods',
			'label'       => __( 'Base color', 'allons-y' ),
			'description' => __( 'Main color for all text', 'allons-y' ),
		)
	);

	/*
	 * Main link color
	 */

	$wp_customize->add_setting(
		'site_link_color',
		array(
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 'blue',
		)
	);

	$wp_customize->add_control(
		'site_link_color',
		array(
			'type'        => 'text',
			'section'     => 'allonsy_theme_mods',
			'label'       => __( 'Link color', 'allons-y' ),
			'description' => __( 'Main color for all text links', 'allons-y' ),
		)
	);
}

add_action( 'customize_register', 'allonsy_customize_register' );

/**
 * Output theme mod CSS in the header
 */

function allonsy_hook_wp_head() {

	/*
	 * All theme mods saved in the customizer should output appropriate CSS here
	 */

	$theme_mods = get_option( 'theme_mods__allons-y', array() );

	if ( ! empty( $theme_mods ) ) :

		/*
		 * Simple CSS changes go below
		 * Can be mapped to theme-customizer.js
		 */

		?>

		<style type="text/css"><?php

		if ( ! empty( $theme_mods['site_base_color'] ) ) {
			printf(
				'body {color: %s}',
				sanitize_text_field( $theme_mods['site_base_color'] )
			);
		}

		if ( ! empty( $theme_mods['site_link_color'] ) ) {
			printf(
				'body a {color: %s}',
				sanitize_text_field( $theme_mods['site_link_color'] )
			);
		}

		?></style>

		<?php
	endif;

	/*
	 * Google Analytics universal tracking tag
	 * Updated 11/29/2015
	 */

	if ( get_option( 'site_google_analytics_account' ) ) :
		?>

		<script>
			(function (i, s, o, g, r, a, m) {
				i['GoogleAnalyticsObject'] = r;
				i[r] = i[r] || function () {
					(i[r].q = i[r].q || []).push(arguments)
				}, i[r].l = 1 * new Date();
				a = s.createElement(o),
					m = s.getElementsByTagName(o)[0];
				a.async = 1;
				a.src = g;
				m.parentNode.insertBefore(a, m)
			})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

			ga('create', '<?php
				echo esc_js( get_option( 'site_google_analytics_account' ) );
				?>', 'auto');
			ga('send', 'pageview');

		</script>

	<?php
	endif;
}

add_action( 'wp_head', 'allonsy_hook_wp_head', 1000 );