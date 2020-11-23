<?php
/**
 * Plugin Name: Mihdan: Post Emoji.
 */

namespace Mihdan\WP_Digest;

add_action(
	'kadence_single_after_inner_content',
	function () {
		echo apply_shortcodes( '[emoji]' );
	}
);
