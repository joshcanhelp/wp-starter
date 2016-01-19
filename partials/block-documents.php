<?php
$sub_items = allonsy_get_attached_docs();
if ( !empty( $sub_items ) ) :
?>

	<div class="document-listing">

	<?php
	$count = 1;
	foreach ( $sub_items as $doc ) :
		$file_url = wp_get_attachment_url( $doc->ID );
		?>
		<div class="single-document">
			<div class="doc-icon-col">
				<a href="<?php echo $file_url ?>" target="_blank">
					<i class="<?php echo proper_doc_icon( $doc->post_mime_type ); ?> doc-icon"></i>
				</a>
			</div>
			<div class="doc-content-col">
				<h2><a href="<?php echo $file_url ?>" target="_blank"><?php echo $doc->post_title ?></a></h2>
				<p><?php echo $doc->post_content ?></p>
			</div>
		</div>
		<?php
		echo $count % 2 ? '' : '<br>';
		$count++;
	endforeach;
	?>

	</div>

<?php endif; ?>