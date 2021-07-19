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
		add_filter( 'wp_schema_pro_schema_job_posting', [ $this, 'job_posting' ], 10, 3 );
	}

	/**
	 * @param $schema
	 * @param $data
	 * @param $post
	 *
	 * @return mixed
     *
     * @link https://developers.google.com/search/docs/data-types/job-posting?hl=ru#structured-data-type-definitions
	 */
	public function job_posting( $schema, $data, $post ) {
		if ( isset( $data['description'] ) && ! empty( $data['description'] ) ) {
		    // Заменяем теги заголовков на параграфы.
		    $description           = preg_replace( '/<(\/?)h[0-9]{1}>/si', '<$1p>', $data['description'] );
			$description           = str_replace( PHP_EOL, '', $description );
			$schema['description'] = $this->strip_tags( $description, ['p', 'br', 'ul', 'ol', 'li'] );
		}

		return $schema;
    }

    public function strip_tags( $string, $allowed_tags = null ) {
	    $string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
	    $string = strip_tags( $string, $allowed_tags );

	    return trim( $string );
    }

	/**
	 * Get MediaObject scheme for Post.
	 */
	public function media_object() {
		if ( ! is_singular( array( 'post', 'vacancy', 'resume' ) ) ) {
			return;
		}

		$_post  = get_post();
		$image = get_the_post_thumbnail_url( $_post );

		$scheme = array(
			'@context'        => 'http://schema.org',
			'@type'           => 'MediaObject',
			'aggregateRating' => array(
				'@type'       => 'AggregateRating',
				'bestRating'  => '5',
				'ratingCount' => round( $_post->ID / 100 ),
				'ratingValue' => '4.9',
			),
			'image'           => $image,
			'name'            => $_post->post_title,
			'description'     => get_the_excerpt( $_post ),
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