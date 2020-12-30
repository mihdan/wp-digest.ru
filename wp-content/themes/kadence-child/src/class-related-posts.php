<?php
/**
 *
 */

namespace Mihdan\Kadence_Child;

/**
 * Class Related_Posts
 * @package Mihdan\Kadence_Child
 */
class Related_Posts {
	/**
	 * Related_Posts constructor.
	 */
	public function __construct() {
	}

	public function setup_hooks() {
		add_filter( 'kadence_related_posts_use_tags', '__return_false' );

		add_filter(
			'kadence_related_posts_carousel_args',
			function ( $args ) {

				$args['orderby']   = 'date';
				$args['post_type'] = [ 'post', 'vacancy', 'resume' ];

				return $args;
			}
		);
	}
}
