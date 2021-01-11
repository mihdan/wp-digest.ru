<?php
/**
 * RSS Feed Generator.
 *
 * @package Mihdan\Kadence_Child
 */

namespace Mihdan\Kadence_Child;

/**
 * Class Feed
 * @package Mihdan\Kadence_Child
 */
class Feed {

	public function setup_hooks() {
		add_filter( 'the_excerpt_rss', [ $this, 'add_tags' ], 99 );
	}

	public function add_tags( $content ) {

		$tags = wp_get_post_terms( get_the_ID(), 'post_tag', [ 'fields' => 'names' ] );
		$tags = array_map(
			function ( $tag ) {
				return $this->convert_text_to_hashtag( $tag );
			},
			$tags
		);

		if ( ! $tags ) {
			return $content;
		}

		$content .= '<p>' . esc_attr( '#' . implode( ' #', $tags ) ) . '</p>';

		return $content;
	}

	public function convert_text_to_hashtag( $text ) {
		return strtolower( preg_replace( '/[^a-zа-я0-9]+/iu', '', $text ) );
	}
}
