<?php
/**
 * Plugin Name:  Comments
 */

namespace Mihdan\WP_Digest;

add_action(
	'kadence_before_comments',
	function () {
		if ( ! is_singular( [ 'post', 'page' ] ) ) {
			return;
		}

		echo '<h3 id="reply-title" class="comment-reply-title">Добавить комментарий</h3>';
		echo '<script async src="https://telegram.org/js/telegram-widget.js?14" data-telegram-discussion="wordpress_digest" data-comments-limit="20" data-colorful="1"></script>';

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