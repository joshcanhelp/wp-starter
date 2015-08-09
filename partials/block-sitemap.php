<div class="the-content">
	<?php
	// TODO: Add post types and taxes
	if ( ! $sitemap_html = get_transient( 'sitemap_html' ) ) :
		ob_start();
		?>
		<h2>Post Tags</h2>

		<ul>
			<?php wp_list_categories( array( 'title_li' => '', 'taxonomy' => 'post_tag' ) ) ?>
		</ul>

		<h2>Pages</h2>

		<ul>
			<?php wp_list_pages( array( 'title_li' => '' ) ); ?>
		</ul>

		<h2>Posts</h2>

		<ul>
			<?php
			foreach (
				get_posts( array(
					'post_type'      => 'post',
					'posts_per_page' => - 1
				) ) as $item
			) {
				printf(
					'<li><a href="%s">%s</a></li>',
					get_permalink( $item->ID ),
					$item->post_title
				);

			}
			?>
		</ul>

		<h2>Post Categories</h2>

		<ul>
			<?php wp_list_categories( array( 'title_li' => '' ) ) ?>
		</ul>

		<h2>Post Tags</h2>

		<ul>
			<?php wp_list_categories( array( 'title_li' => '', 'taxonomy' => 'post_tag' ) ) ?>
		</ul>

		<?php
		$sitemap_html = ob_get_contents();
		ob_end_clean();
		set_transient( 'sitemap_html', $sitemap_html, DAY_IN_SECONDS );
	endif;
	echo $sitemap_html;
	?>
</div>