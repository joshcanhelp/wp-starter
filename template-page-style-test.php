<?php get_header(); ?>

<?php
// Template Name: Styling test
?>

	<section class="single-page">
		<div class="inner">

			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					?>

					<article id="post-id-<?php the_ID(); ?>" <?php post_class(); ?>>

						<h1><?php the_title(); ?></h1>

						<div class="has-wysiwyg">
							<?php the_content(); ?>
						</div>

					</article>

					<aside class="sidebar">
						<?php dynamic_sidebar( 'sidebar-1' ); ?>
					</aside>

					<?php
				endwhile;
			endif;
			?>

		</div>
	</section>

	<section class="allons-test-color-grid">
		<div class="inner">

			<div class="color-col darker">
				<span class="name">Darker</span>
			</div>
			<div class="color-col dark">
				<span class="name">Dark</span>
			</div>
			<div class="color-col med-darkest">
				<span class="name">Med Darkest</span>
			</div>
			<div class="color-col med-darker">
				<span class="name">Med Darker</span>
			</div>
			<div class="color-col med-dark">
				<span class="name">Med Dark</span>
			</div>
			<div class="color-col med">
				<span class="name">Medium</span>
			</div>
			<div class="color-col med-light">
				<span class="name">Med Light</span>
			</div>
			<div class="color-col med-lighter">
				<span class="name">Med Lighter</span>
			</div>
			<div class="color-col med-lightest">
				<span class="name">Med Lightest</span>
			</div>
			<div class="color-col light">
				<span class="name">Light</span>
			</div>
			<div class="color-col lighter">
				<span class="name">Lighter</span>
			</div>
			<div class="color-col lightest">
				<span class="name">Lightest</span>
			</div>

		</div>
	</section>

<?php get_footer(); ?>