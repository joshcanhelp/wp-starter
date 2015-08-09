<?php get_header() ?>

	<section id="main-content" class="main-content post-single-wrap">
		<div class="inner-wrapper">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<div class="col-with-sidebar">

						<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

							<h1 class="main-title">
								<?php the_title(); ?>
							</h1>

							<div class="post-meta-top">
								Posted on <?php the_date(); ?>
							</div>

							<div class="the-content">
								<?php the_content(); ?>
							</div>

							<div class="post-meta-bottom">

								<div class="post-categories">
									In Category: <?php the_category( ' ' ); ?>
								</div>

								<div class="post-sharing">
									<?php proper_share_links( get_the_ID(), get_the_title() ); ?>
								</div>

							</div>

						</section>


					</div>

				<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'partials/block', 'nothing-found' ); ?>
			<?php endif; ?>

			<?php get_sidebar( 'blog' ) ?>

		</div>
	</section>

<?php get_footer() ?>