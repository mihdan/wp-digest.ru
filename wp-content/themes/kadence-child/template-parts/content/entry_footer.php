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
	if ( 'post' === get_post_type() && kadence()->option( 'post_tags' ) ) {
		get_template_part( 'template-parts/content/entry_tags', get_post_type() );
	}
	?>
    <?php if ( is_singular( 'post' ) ) : ?>
        <div class="entry-comments"><a href="#reply-title" title="Перейти к списку комментариев">Комментарии</a></div>
    <?php endif; ?>
</footer><!-- .entry-footer -->
