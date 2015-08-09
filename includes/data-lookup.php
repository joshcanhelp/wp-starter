<?php

/**
 * Get a user meta field
 *
 * @param bool $key
 * @param int  $uid
 *
 * @return mixed
 */
function proper_user_meta ( $key = '', $uid = 0 ) {

	if ( empty( $uid ) ) {
		$uid = get_current_user_id();
	}

	if ( $key === 'email' ) {
		$user = get_userdata( $uid );

		return isset( $user->data->user_email ) ? $user->data->user_email : '';
	} else if ( $key === 'display_name' ) {
		$user = get_userdata( $uid );

		return isset( $user->data->display_name ) ? $user->data->display_name : '';
	} else {
		return get_user_meta( $uid, $key, TRUE );
	}
}

/**
 * Get a single meta field
 *
 * @param string  $meta_key
 * @param int $pid
 *
 * @return mixed
 */
function proper_meta ( $meta_key, $pid = 0 ) {
	return get_post_meta( $pid ? $pid : get_the_ID(), $meta_key, TRUE );
}

/**
 * Get the URL to an image in the theme
 *
 * @param $file_name
 *
 * @return string
 */
function proper_theme_img ( $file_name ) {
	return get_stylesheet_directory_uri() . '/assets/images/' . $file_name;
}

/**
 * Gets the featured image URL or the default image if that doesn't exist
 *
 * @param string $pid
 * @param string $size
 *
 * @return string
 */
function proper_get_post_img_url ( $pid = '', $size = 'product-thumb' ) {

	if ( empty( $pid ) ) {
		$pid = get_the_ID();
	}

	$img_url = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), $size );

	if ( empty( $img_url[0] ) ) {
		return proper_theme_img( 'default-image-' . $size . '.png' );
	} else {
		return $img_url[0];
	}

}

/**
 * Return the current page URL
 *
 * @return string
 */
function proper_get_curr_url () {
	return ( $_SERVER['SERVER_PORT'] === 443 ? 'https' : 'http' ) . '://' .
		$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}


/**
 * Get a meta field for a particular term
 *
 * @param        $tid
 * @param string $field
 *
 * @return mixed|string|void
 */
function proper_get_term_meta ( $tid, $field = '' ) {
	$term_meta = get_option( 'taxonomy_meta_'. $tid, array() );

	if ( $field ) {
		if ( isset( $term_meta[ $field ] ) ) {
			return $term_meta[ $field ];
		} else {
			return '';
		}
	} else {
		return $term_meta;
	}
}


/**
 * Get all attachments with a document-like mime type
 *
 * @param int $pid
 *
 * @return array
 */
function proper_get_attached_docs ( $pid = 0 ) {

	if ( empty( $pid ) ) {
		$pid = get_the_ID();
	}

	$docs = get_children( array(
		'post_type' => 'attachment',
		'post_parent' => $pid,
		'posts_per_page' => -1
	) );

	return ! empty( $docs ) ? $docs : array();

}

/**
 * Get a single category for a post
 *
 * @param bool $link_html
 *
 * @return string
 */
function proper_post_single_cat ( $link_html = FALSE ) {
	if ( $terms = get_the_terms( get_the_ID(), 'category' ) ) {
		if ( $link_html ) {
			return sprintf( $link_html, get_term_link( $terms[0]->term_id, 'category' ), $terms[0]->name );
		} else {
			return $terms[0]->name;
		}

	}

	return '';
}

/**
 * Get icon font class depending on document type
 *
 * @param string $type
 *
 * @return string
 */

function proper_doc_icon ( $type = '' ) {

	$icon_class = 'icon-doc-text';

	if ( strpos( $type, 'pdf' ) !== FALSE ) {
		$icon_class = 'icon-file-pdf';
	} else if ( strpos( $type, 'video' ) !== FALSE ) {
		$icon_class = 'icon-file-video';
	} else if ( strpos( $type, 'image' ) !== FALSE ) {
		$icon_class = 'icon-file-image';
	} else if ( strpos( $type, 'excel' ) !== FALSE ) {
		$icon_class = 'icon-file-excel';
	} else if ( strpos( $type, 'spreadsheet' ) !== FALSE ) {
		$icon_class = 'icon-file-excel';
	}

	return $icon_class;
}
