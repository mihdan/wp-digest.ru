<?php
/**
 * Plugin Name:  Comments
 */

namespace Mihdan\Kadence_Child;

class Comments {
    public function setup_hooks() {
	    add_action(
		    //'get_template_part_' . 'template-parts/content/entry_related',
		    //'kadence_before_comments',
            //'kadence_single_before_entry_navigation',
            'kadence_after_main_content',
		    function () {
			    if ( ! is_singular( [ 'post', 'page', 'vacancy', 'resume', 'recommendations', 'event' ] ) ) {
				    return;
			    }

			    if ( ! comments_open() ) {
			        return;
                }

			    $channel = is_singular( [ 'post', 'page', 'recommendations', 'event' ] )
				    ? 'wordpress_digest'
				    : 'wordpress_jobs';

			    $telegram_url = get_post_meta( get_the_ID(), 'post_telegram_url', true );

			    if ( ! empty( $telegram_url ) ) {
				    $channel = str_replace( 'https://t.me/', '', $telegram_url );
			    }

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

	    add_action(
		    'kadence_after_entry_meta',
		    function () {
			    if ( ! is_singular( [ 'post', 'vacancy', 'resume', 'recommendations', 'event' ] ) ) {
				    return;
			    }
			    ?>
                <span><svg style="margin-right: 5px" width="1.1em" height="1.1em" viewBox="0 0 32 32"><path fill="#2d3748" d="M28.8 9.6v11.2c0 1.76-1.44 3.2-3.2 3.2h-6.4v4.8l-6.4-4.8h-6.4c-1.762 0-3.2-1.44-3.2-3.2v-11.2c0-1.76 1.438-3.2 3.2-3.2h19.2c1.76 0 3.2 1.44 3.2 3.2z"></path></svg><a href="#reply-title">Комментарии</a></span>
			    <?php
		    }
	    );
    }
}
