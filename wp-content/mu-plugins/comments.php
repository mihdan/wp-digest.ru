<?php
/**
 * Plugin Name:  Comments
 */

namespace Mihdan\WP_Digest;

add_action(
	'kadence_before_comments',
	function () {
		if ( ! is_singular( [ 'post', 'page', 'vacancy', 'resume' ] ) ) {
			return;
		}

		$channel = is_singular( [ 'post', 'page' ] )
			? 'wordpress_digest'
			: 'wordpress_jobs';

		?>
        <div class="comments comments--telegram">
            <h2 id="reply-title" class="comment-reply-title">Добавить комментарий</h2>
            <script async
                    src="https://telegram.org/js/telegram-widget.js?14"
                    data-telegram-discussion="<?php echo esc_attr( $channel ); ?>"
                    data-comments-limit="20"
                    data-colorful="1"></script>
        </div>
		<?php
	},
	12
);

add_action(
	'kadence_comments',
	function () {
		remove_action( 'kadence_comments', 'Kadence\comments_form', 15 );
		remove_action( 'kadence_comments', 'Kadence\comments_list', 10 );
	},
	2
);