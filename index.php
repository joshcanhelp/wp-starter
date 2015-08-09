<?php get_header(); ?>

	<section id="main-content" class="main-content post-listing">
		<div class="inner-wrapper">

			<div class="col-with-sidebar">

				<h1 class="main-title"><?php proper_archive_title(); ?></h1>

				<?php if ( have_posts() ) : ?>

					<?php while ( have_posts() ) :
						the_post(); ?>
						<section id="post-<?php the_ID(); ?>" <?php post_class( 'post-listing-wrap' ); ?>>

							<h2><a href="<?php echo get_permalink() ?>"><?php the_title() ?></a></h2>

							<div class="the-content cf">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'thumbnail' ); ?>
								</a>
								<?php the_excerpt(); ?>
								<p><a class="read-more" href="<?php
									the_permalink(); ?>">Read More <i class="icon-angle-double-right"></i></a></p>
							</div>

						</section>
					<?php endwhile; ?>

					<?php proper_pagination(); ?>
				<?php else : ?>
					<?php get_template_part( 'partials/block', 'nothing-found' ); ?>
				<?php endif; ?>

			</div>


			<?php get_sidebar( 'blog' ) ?>

		</div>
	</section>

<?php get_footer(); ?>