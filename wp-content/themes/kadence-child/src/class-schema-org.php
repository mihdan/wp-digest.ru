<?php
/**
 * Class Schema_Org
 * @package Mihdan\Kadence_Child
 */

namespace Mihdan\Kadence_Child;

/**
 * Class Schema_Org
 * @package Mihdan\Kadence_Child
 */
class Schema_Org {
	/**
	 * Schema_Org constructor.
	 */
	public function __construct() {
	}

	/**
	 * Setup hooks.
	 */
	public function setup_hooks() {
		add_action( 'wp_footer', array( $this, 'media_object' ) );
	}

	/**
	 * Get MediaObject scheme for Post.
	 */
	public function media_object() {
		if ( ! is_singular( array( 'post', 'vacancy', 'resume' ) ) ) {
			return;
		}

		$post  = get_post();
		$image = get_the_post_thumbnail_url( $post );

		$scheme = array(
			'@context'        => 'http://schema.org',
			'@type'           => 'MediaObject',
			'aggregateRating' => array(
				'@type'       => 'AggregateRating',
				'bestRating'  => '5',
				'ratingCount' => round( $post->ID / 100 ),
				'ratingValue' => '4.9',
			),
			'image'           => $image,
			'name'            => $post->post_title,
			'description'     => get_the_excerpt( $post ),
		);

		$this->render( $scheme );
	}

	/**
	 * Render given scheme.
	 *
	 * @param array $scheme Scheme array.
	 * @return void
	 */
	public function render( $scheme ) {
		?>
		<script type="application/ld+json"><?php echo wp_json_encode( $scheme, JSON_UNESCAPED_UNICODE ); ?></script>
		<?php
	}
}