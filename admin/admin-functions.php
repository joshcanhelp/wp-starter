<?php

// Admin-only styles and scripts
require_once( 'admin-enqueue.php' );

// Adjust user experience
require_once( 'user-hooks-filters.php' );

// Editor styling
require_once( 'wysiwyg-editor-functions.php' );

// Removes comment support in WP
// require_once( 'disable-comments.php' );

if ( WP_DEBUG ) {
	require_once( 'debug.php' );
}



