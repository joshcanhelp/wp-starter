<?php
$sub_items = get_children( array(
	'post_type' => 'page',
	'posts_per_page' => -1,
	'post_parent' => get_the_ID(),
	'orderby' => 'menu_order',
	'order' => 'ASC'
) );

if ( ! empty( $sub_items ) ) :
	?>

	<div class="subpage-listing">

		<?php
		$count = 1;
		foreach ( $sub_items as $doc ) :
			?>
			<div class="single-subpage">
				<h2><a href="<?php echo get_permalink( $doc->ID ) ?>"><?php
						echo apply_filters( 'the_title', $doc->post_title ) ?></a></h2>

				<p><?php
					echo apply_filters(
						'the_excerpt',
						$doc->post_content
					) ?></p>
			</div>
			<?php
			echo $count % 2 ? '' : '<br>';
			$count ++;
		endforeach;
		?>

	</div>

<?php endif; ?>