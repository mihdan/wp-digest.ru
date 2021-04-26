<?php
namespace Mihdan\Kadence_Child;

class Performance {
	public function setup_hooks() {
		add_action(
			'wp_print_styles',
			function () {
				wp_deregister_style( 'kadence-comments' );
				wp_deregister_style( 'kadence-fonts' );
			}
		);

		add_action(
			'wp_print_scripts',
			function () {
				wp_deregister_script( 'comment-reply' );
				wp_deregister_script( 'wp-embed' );
			}
		);

		add_action(
			'wp_head',
			function () {
				if ( is_singular( 'post' ) && has_post_thumbnail() ) {
					$thumbnail_id = get_post_thumbnail_id();
					printf(
						'<link rel="preload" as="image" imagesrcset="%s" imagesizes="%s" />',
						wp_get_attachment_image_srcset( $thumbnail_id ),
						wp_get_attachment_image_sizes( $thumbnail_id )
					);
					print( '<link rel="preconnect" href="https://newrrb.bid" />' );
				}
			}
		);
	}
}
