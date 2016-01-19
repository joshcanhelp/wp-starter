<?php get_header(); ?>

<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>

		<div id="post-id-<?php  the_ID(); ?>" <?php post_class( 'u-clear' ); ?>>

			<img src="<?php echo allonsy_get_post_img_url() ?>" class="alignleft">

			<?php if ( is_singular() ) : ?>
			<h1><?php the_title(); ?></h1>
			<?php else : ?>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php endif; ?>

			<div class="has-wysiwyg">

				<?php if ( is_singular() ) : ?>
					<?php the_content(); ?>
				<?php else : ?>
					<?php the_excerpt(); ?>
				<?php endif; ?>

			</div>
		</div>

	<?php
	endwhile;
endif;
?>


<?php get_footer(); ?>