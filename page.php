<?php get_header() ?>

<section id="main-content" class="main-content">
	<div class="inner-wrapper">

		<div class="col-with-sidebar">

			<?php get_template_part( 'partials/loop', 'page' ); ?>

			<?php get_template_part( 'partials/block', 'subpages' ); ?>

		</div>

		<?php get_sidebar() ?>

	</div>
</section>

<?php get_footer() ?>