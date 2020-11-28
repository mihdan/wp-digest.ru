<?php
add_filter( 'the_seo_framework_pre_get_document_title_', function( $title ) {

	if ( is_singular( 'post' ) ) {
		$categories = wp_get_post_categories(
			get_the_ID(),
			array(
				'fields' => 'names'
			)
		);
		//$title = str_replace( '&#x2d;', '-', $title );
		//$title = str_replace( 'WordPress Digest', $categories[0] . ' | WordPress Digest', $title );
	}

	return $title;
}, 10 );