<?php
/**
 * Plugin Name: Mihdan: Pageviews
 */

namespace Mihdan\WP_Digest;

add_action(
	'kadence_after_entry_meta',
	function () {
		?>
		<span>Просмотров: <?php do_action( 'pageviews' ); ?></span>
		<?php
	}
);

add_filter(
	'pageviews_placeholder_preload',
	function () {
		return 99;
	}
);