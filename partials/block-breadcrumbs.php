<section class="breadcrumb-wrapper">
	<div class="inner-wrapper">

		<?php get_search_form(); ?>

		<ol id="breadcrumbs" class="breadcrumbs u-list-inline" itemscope>

		<?php
		// Breadcrumb link printf template
		$link_html = '<li itemtype="http://data-vocabulary.org/Breadcrumb"><a href="%s" itemprop="url">
			<span itemprop="title">%s</span></a></li> <li class="separator">&rsaquo;</li> ';

		$title_html = '<li><span itemprop="title"><strong>%s</strong></span></li>';

		printf( $link_html, home_url(), 'Home' );


		if ( is_home() ) {

			// Blog title
			printf( $title_html, get_the_title( get_option( 'page_for_posts' ) ) );

		} else if ( is_page() ) {

			// Check for parents and show
			if ( $anc = get_post_ancestors( get_the_ID() ) ) {

				// Parent page loop
				foreach ( array_reverse( $anc ) as $ancestor ) {
					printf( $link_html, get_permalink( $ancestor ), get_the_title( $ancestor ) );
				}

			}

			// Single page title
			printf( $title_html, get_the_title() );

		} else if ( is_single() ) {

			// Link to blog, if there is one
			if ( $blog_page_id = get_option( 'page_for_posts', '' ) ) {
				printf( $link_html, get_permalink( $blog_page_id ), get_the_title( $blog_page_id ) );
			}

			// Show linked post category, if one exists
			if ( $category = get_the_category() ) {
				printf( $link_html, get_term_link( $category[0]->term_id, 'category' ), $category[0]->name );
			}

			// Post title
			printf( $title_html, get_the_title() );

		} else if ( is_category() || is_tag() ) {

			// Link to blog, if there is one
			if ( $blog_page_id = get_option( 'page_for_posts', '' ) ) {
				printf( $link_html, get_permalink( $blog_page_id ), get_the_title( $blog_page_id ) );
			}

			// Category name
			printf( $title_html, get_queried_object()->name );

		} elseif ( is_date() ) {

			// Link to blog, if there is one
			if ( $blog_page_id = get_option( 'page_for_posts', '' ) ) {
				printf( $link_html, get_permalink( $blog_page_id ), get_the_title( $blog_page_id ) );
			}

			// Year link
			if ( is_day() || is_month() ) {
				printf( $link_html, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) );
			}

			// Month link
			if ( is_day() ) {
				printf( $link_html, get_year_link( get_the_time( 'M' ) ), get_the_time( 'M' ) );
				$page_title = get_the_time( 'jS' );
			} else if ( is_month() ) {
				$page_title = get_the_time( 'M' );
			} else {
				$page_title = get_the_time( 'Y' );
			}

			// Date archive name
			printf( $title_html, $page_title );

		} else if ( is_search() ) {

			// Category name
			printf( $title_html, 'Results for "' . urldecode( $_GET['s'] ) . '"' );

		}
		?>
		</ol>

	</div>
</section>