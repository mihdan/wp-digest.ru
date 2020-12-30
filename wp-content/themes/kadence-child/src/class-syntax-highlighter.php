<?php
/**
 *
 */

namespace Mihdan\Kadence_Child;

/**
 * Class Syntax_Highlighter
 * @package Mihdan\Kadence_Child
 */
class Syntax_Highlighter {
	public function setup_hooks() {
		add_action(
			'get_footer',
			function () {
				global $wp_version;

				if ( ! is_singular( 'post' ) ) {
					return;
				}

				//wp_enqueue_style( 'highlight-styles', get-the_i( 'assets/css/highlight.min.css', __FILE__ ), [], filemtime( plugin_dir_path( __FILE__ ) . 'assets/css/highlight.min.css' ) );
				wp_enqueue_script( 'highlight-scripts', get_theme_file_uri( 'assets/js/highlight.min.js' ), [], filemtime( get_theme_file_path( 'assets/js/highlight.min.js' ) ), true );
				wp_add_inline_script(
					'highlight-scripts',
					"
			document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.wp-block-code').forEach(function (item) {
                    hljs.highlightBlock(item)
                });
            });
			"
				);
			}
		);
	}
}
