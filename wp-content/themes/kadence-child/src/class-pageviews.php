<?php
/**
 * Plugin Name: Mihdan: Pageviews
 */

namespace Mihdan\Kadence_Child;

class Pageviews {
    private $rating_value;
    private $rating_count;

    public function __construct() {
    }

	public function setup_hooks() {
        add_action( 'kadence_after_loop_entry_meta', [ $this, 'show_counter' ] );
        add_action( 'kadence_after_entry_meta', [ $this, 'show_counter'] );
        add_action( 'kadence_single_after_entry_title', [ $this, 'show_rating'] );
	    add_filter( 'pageviews_placeholder_preload', [ $this, 'show_placeholder' ] );
	    add_filter( 'post_thumbnail_html', array( $this, 'post_thumbnail_html' ), 10, 5 );
	    add_filter( 'wp_get_attachment_image_attributes', array( $this, 'set_itemprop_for_post_thumbnail' ), 10, 3 );
    }

    public function get_rating_value() {
        return 4.9;
    }

	public function get_rating_count() {
		return round( get_the_ID() / 100 );
	}

	public function plural_form( $n, $form1, $form2, $form5 ) {
		$n = abs($n) % 100;
		$n1 = $n % 10;
		if ($n > 10 && $n < 20) return $form5;
		if ($n1 > 1 && $n1 < 5) return $form2;
		if ($n1 == 1) return $form1;
		return $form5;
	}

	public function show_rating() {
        ?>
        <div class="cover__rating">
            <div class="star_rate" style="cursor:pointer; width: 5.2rem; height:1rem; position: relative;  background-size: auto 100%; display:inline-block; margin-right:0.5rem;">
                <div class="start_zpol" style="position:absolute; width:96.6%; height:1rem;  background-size: auto 100%"></div>
            </div>
            <div id="start_result" style="display:inline-block;">
                Рейтинг: <span><?php echo esc_attr( $this->get_rating_value() ); ?></span> из <span><?php echo esc_attr( $this->get_rating_count() ); ?></span> <?php echo esc_html( $this->plural_form( $this->get_rating_count(), 'оценки', 'оценок', 'оценок' ) ); ?>
            </div>
        </div>
        <?php
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

	    if ( 'full' !== $size || is_feed() ) {
	        return $html;
        }

		$upload_dir = wp_upload_dir();
		$meta_data  = wp_get_attachment_metadata( $post_thumbnail_id );

		if ( ! is_array( $meta_data ) || ! isset( $meta_data['sizes'] ) || ! isset( $meta_data['file'] ) ) {
			return $html;
		}

		$base_url = trailingslashit( $upload_dir['baseurl'] ) . dirname( $meta_data['file'] ) . '/';

		ob_start();
		?>
        <div itemscope itemtype="https://schema.org/ImageObject" class="cover">
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
		</div>
        <script>
            jQuery(
                function ( $ ) {
                    $(document).on('mousemove click', '.star_rate', function(e) {
                        switch (e.type) {
                            case 'mousemove':
                                $('.start_zpol', this).css("width", e.offsetX + "px");
                                this.style.setProperty('--mwidth_hov', e.offsetX + "px");
                                break;
                            case 'click':
                                $('#start_result').html( 'Загрузка &hellip;' );
                                $.ajax({
                                    type: 'POST',
                                    url: document.location.href,
                                    data: {
                                        'val': e.offsetX / ($(this).width() / 100)
                                    }
                                }).done(function(data) {
                                    $('#start_result').html( 'Ваш голос учтён, спасибо!' );
                                });
                                break;
                        }
                    });
                }
            );
        </script>
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
		if ( is_feed() ) {
			return $attr;
		}

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
