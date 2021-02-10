<?php
/**
 * Template part for displaying a post's footer
 *
 * @package kadence
 */

namespace Kadence;

?>
<footer class="entry-footer">
	<?php
	if ( kadence()->option( 'post_tags' ) ) {
		get_template_part( 'template-parts/content/entry_tags', get_post_type() );
	}
	?>
</footer><!-- .entry-footer -->
