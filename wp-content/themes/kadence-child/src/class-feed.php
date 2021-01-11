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

		$thumbnail = '';
		$post      = get_post();
		$extended  = get_extended( $post->post_content );

		// Обложка записи.
		if ( ! empty( $extended['main'] ) ) {
			$content = do_blocks( $extended['main'] );

			if ( has_post_thumbnail( $post->ID ) ) {
				$thumbnail = '<p>' . get_the_post_thumbnail( $post->ID, 'full' ) . '</p>';
			}
		}

		$tags = wp_get_post_terms( $post->ID, 'post_tag', [ 'fields' => 'names' ] );
		$tags = array_map(
			function ( $tag ) {
				return $this->convert_text_to_hashtag( $tag );
			},
			$tags
		);

		if ( ! $tags ) {
			return $thumbnail . $content;
		}

		$tags = '<p>' . esc_attr( '#' . implode( ' #', $tags ) ) . '</p>';

		// Для Твиттера постим только теги без описания.
		if ( ! empty( $_GET['provider'] ) && 'twitter' === $_GET['provider'] ) {
			return $thumbnail . $tags;
		}

		return $thumbnail . $content . $tags;
	}

	public function convert_text_to_hashtag( $text ) {
		return strtolower( preg_replace( '/[^a-zа-я0-9]+/iu', '', $text ) );
	}
}
