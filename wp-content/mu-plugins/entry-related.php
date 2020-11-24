<?php

add_action(
	'kadence_single_after_inner_content',
	function () {
		if ( is_singular( [ 'post', 'job' ] ) ) {
			get_template_part( 'template-parts/content/entry_related', get_post_type() );
		}
	},
	11
);

add_filter(
	'kadence_css_files',
	function ( $css_files ) {

		if ( isset( $css_files['kadence-related-posts'] ) ) {
			$css_files['kadence-related-posts']['preload_callback'] = function () {
				return is_singular( [ 'post', 'job' ] );
			};
		}

		return $css_files;
	}
);
