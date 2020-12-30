<?php
/**
 * Lazy Load via Lozad.js
 */

namespace Mihdan\Kadence_Child;

class Lazy_Load {
    public function setup_hooks() {
	    /**
	     * Добавить атрибут srcset="placeholder...." к тумбочкам постов
	     * при включенной ленивой загрузке.
	     *
	     * @param array    $attr       Атрибуты по умолчанию.
	     * @param \WP_Post $attachment Объект вложения.
	     * @param string   $size       Размер.
	     *
	     * @return mixed
	     */
	    add_filter(
		    'wp_get_attachment_image_attributes',
		    function ( $attr, \WP_Post $attachment, $size ) {
			    if ( ! did_action( 'begin_fetch_post_thumbnail_html' ) ) {
				    return $attr;
			    }
			    $attr['data-srcset'] = $attr['srcset'];

			    $attr['srcset'] = $this->get_srcset_placeholder();
			    $attr['class'] = $attr['class'] . ' lozad';

			    return $attr;
		    },
		    10,
		    3
	    );

	    add_filter(
		    'get_custom_logo_image_attributes',
		    function ( $attr ) {
			    return $attr;
		    }
	    );

	    add_action(
		    'wp_enqueue_scripts',
		    function () {
			    wp_enqueue_script( 'mihdan-lozad', get_theme_file_uri( 'assets/js/lozad.js' ), [ 'jquery' ], 'ver', true );
		    }
	    );

	    add_action(
		    'wp_footer',
		    function () {
			    ?>
                <script>
                    jQuery( function ( $ ) {

                        let mihdanObserver = lozad();
                        mihdanObserver.observe();

                        $( '#archive-container').on( 'append.infiniteScroll', function() {
                            mihdanObserver.observe();
                        } );
                    } );
                </script>
			    <?php
		    },
		    999
	    );
    }

	public function get_srcset_placeholder() {
		return sprintf(
			'%1$s 370w, %1$s 600w, %1$s 1920w',
			get_theme_file_uri( 'assets/images/img-placeholder.svg' )
		);
	}
}
