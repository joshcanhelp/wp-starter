
	<footer id="site-footer" class="site-footer">
		<div class="inner-wrapper">

			<div class="footer-widgets">
				<?php dynamic_sidebar( 3 ); ?>
			</div>

		</div>
	</footer>

	<footer class="footer-bottom-text">
		<div class="inner-wrapper">

			<ul class="navigation" id="menu-footer-bottom">
				<li>
					Copyright <?php echo date( 'Y' ); ?> <?php echo get_bloginfo( 'name' ) ?>
					<span class="separator"></span>
				</li>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => FALSE,
					'items_wrap'     => '%3$s',
					'fallback_cb'    => FALSE,
				) );
				?>
			</ul>


		</div>
	</footer>

	<!-- start of wp_footer() -->
	<?php wp_footer() ?>
	<!-- end of wp_footer() -->

</body>
</html>