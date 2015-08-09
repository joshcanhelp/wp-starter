<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<h1 class="main-title">
			<?php the_title(); ?>
		</h1>

		<div class="the-content">
			<?php the_content(); ?>
		</div>

	</section>

	<?php get_template_part( 'partials/block', get_query_var( 'pagename' ) ); ?>

<?php endwhile; endif; ?>