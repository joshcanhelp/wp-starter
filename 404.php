<?php get_header() ?>

<section id="main-content" class="main-content">
	<div class="inner-wrapper">

		<div class="col-single-wide">

			<?php if ( $fourohfour = get_page_by_path( '404-page' ) ) : ?>

				<h1 class="main-title">
					<?php echo apply_filters( 'the_title', $fourohfour->post_title ) ?>
				</h1>

				<div class="the-content">
					<?php echo apply_filters( 'the_content', $fourohfour->post_content ) ?>
				</div>

			<?php else : ?>

				<h1 class="main-title">
					<?php _e( 'Page Not Found (404)', 'proper-start' ); ?>
				</h1>

			<?php endif; ?>

		</div>

	</div>
</section>
<?php get_footer() ?>