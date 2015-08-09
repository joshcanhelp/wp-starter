<?php global $post, $comments; ?>

<?php
// If post is password protected, don't show comments
if ( ! empty( $post->post_password ) ) {
	if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) {
		return;
	}
}
?>

<?php if ( $comments ) : ?>

	<p id="comments">
		<?php comments_number( 'No comments', 'One comment', '% comments' ); ?>
	</p>

	<ol class="comment-list">
		<?php wp_list_comments(); ?>
	</ol>

	<?php paginate_comments_links(); ?>

<?php endif; ?>

<?php comment_form(); ?>