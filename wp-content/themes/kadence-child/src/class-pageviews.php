<?php
/**
 * Plugin Name: Mihdan: Pageviews
 */

namespace Mihdan\Kadence_Child;

class Pageviews {
    private $rating_value;
    private $rating_count;

    public function __construct() {
        $this->rating_value = 4.9;
        $this->rating_count = wp_rand( 9, 999 );
    }

	public function setup_hooks() {
        add_action( 'kadence_after_loop_entry_meta', [ $this, 'show_counter' ] );
        add_action( 'kadence_after_entry_meta', [ $this, 'show_counter'] );
	    add_filter( 'pageviews_placeholder_preload', [ $this, 'show_placeholder' ] );
	    add_filter( 'post_thumbnail_html', array( $this, 'post_thumbnail_html' ), 10, 5 );
	    add_filter( 'wp_get_attachment_image_attributes', array( $this, 'set_itemprop_for_post_thumbnail' ), 10, 3 );
    }

	/**
	 * Lazy Load для миниатюр.
	 *
	 * @param string       $html              The post thumbnail HTML.
	 * @param int          $post_id           The post ID.
	 * @param string       $post_thumbnail_id The post thumbnail ID.
	 * @param string|array $size              The post thumbnail size. Image size or array of width and height
	 *                                        values (in that order). Default 'post-thumbnail'.
	 * @param string       $attr              Query string of attributes.
	 *
	 * @return string
	 */
	public function post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		$upload_dir = wp_upload_dir();
		$meta_data  = wp_get_attachment_metadata( $post_thumbnail_id );

		if ( ! is_array( $meta_data ) || ! isset( $meta_data['sizes'] ) || ! isset( $meta_data['file'] ) ) {
			return $html;
		}

		$base_url = trailingslashit( $upload_dir['baseurl'] ) . dirname( $meta_data['file'] ) . '/';
		do_action( 'qm/debug', $meta_data );
		ob_start();
		?>
        <span itemscope itemtype="https://schema.org/ImageObject">
			<?php echo $html; ?>
            <meta itemprop="width" content="<?php echo (int) $meta_data['width']; ?>px" />
            <meta itemprop="height" content="<?php echo (int) $meta_data['height']; ?>px" />
			<?php foreach ( $meta_data['sizes'] as $size ) : ?>
                <span itemscope itemtype="http://schema.org/ImageObject" itemprop="thumbnail" style="display:none;">
                    <link itemprop="contentUrl" href="<?php echo esc_url( $base_url . $size['file'] ); ?>">
                    <meta itemprop="width" content="<?php echo (int) $size['width']; ?>px" />
                    <meta itemprop="height" content="<?php echo (int) $size['height']; ?>px" />
                    <meta itemprop="name" content="<?php the_title(); ?>. Изображение <?php echo (int) $size['width']; ?>px на <?php echo (int) $size['height']; ?>px." />
                 </span>
			<?php endforeach; ?>
            <meta itemprop="name" content="<?php the_title(); ?>">
            <span itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                <span itemprop="ratingValue"><?php echo esc_attr( $this->rating_value ); ?></span>
                <span itemprop="ratingCount"><?php echo esc_attr( $this->rating_count ); ?></span>
            </span>
		</span>
		<?php
		return ob_get_clean();
	}

	/**
	 * Добавить атрибут itemprop="contentUrl url" к тумбочкам постов
	 * при включенной микроразметке.
	 *
	 * @param $attr
	 * @param $attachment
	 * @param $size
	 *
	 * @return mixed
	 */
	public function set_itemprop_for_post_thumbnail( $attr, $attachment, $size ) {
		if ( did_action( 'begin_fetch_post_thumbnail_html' ) && in_array( $size, array( 'post-thumbnail', 'full' ), true ) ) {
			$attr['itemprop'] = 'contentUrl url';
		}

		return $attr;
	}

	public function show_counter() {
		?>
        <span class="pageviews" title="Количество просмотров записи"><svg style="margin-right: 5px" width="1.1em" height="1.1em" viewBox="0 0 32 32"><path fill="#2d3748" d="M16 7.040c-10.498 0-16 7.731-16 8.96 0 1.226 5.502 8.96 16 8.96 10.496 0 16-7.734 16-8.96 0-1.229-5.504-8.96-16-8.96zM16 22.891c-3.928 0-7.112-3.085-7.112-6.891s3.184-6.894 7.112-6.894c3.928 0 7.11 3.088 7.11 6.894s-3.182 6.891-7.11 6.891zM16 16c-0.651-0.715 1.061-3.446 0-3.446-1.965 0-3.557 1.544-3.557 3.446s1.592 3.446 3.557 3.446c1.963 0 3.557-1.544 3.557-3.446 0-0.875-3.003 0.606-3.557 0z"></path></svg> <?php do_action( 'pageviews' ); ?></span>
		<?php
	}

    public function show_placeholder() {
	    return '&hellip;';
    }
}
