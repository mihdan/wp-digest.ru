<?php
/**
 * Plugin Name: Mihdan: Pageviews
 */

namespace Mihdan\WP_Digest;

class Pageviews {
    public function setup_hooks() {
        add_action( 'kadence_after_loop_entry_meta', [ $this, 'show_counter' ] );
        add_action( 'kadence_after_entry_meta', [ $this, 'show_counter'] );
	    add_filter( 'pageviews_placeholder_preload', [ $this, 'show_placeholder' ] );
    }

	public function show_counter() {
		?>
        <span class="pageviews" title="Количество просмотров записи"><svg style="margin-right: 5px" width="1.1em" height="1.1em" viewBox="0 0 32 32"><path fill="#2d3748" d="M16 7.040c-10.498 0-16 7.731-16 8.96 0 1.226 5.502 8.96 16 8.96 10.496 0 16-7.734 16-8.96 0-1.229-5.504-8.96-16-8.96zM16 22.891c-3.928 0-7.112-3.085-7.112-6.891s3.184-6.894 7.112-6.894c3.928 0 7.11 3.088 7.11 6.894s-3.182 6.891-7.11 6.891zM16 16c-0.651-0.715 1.061-3.446 0-3.446-1.965 0-3.557 1.544-3.557 3.446s1.592 3.446 3.557 3.446c1.963 0 3.557-1.544 3.557-3.446 0-0.875-3.003 0.606-3.557 0z"></path></svg> <?php do_action( 'pageviews' ); ?></span>
		<?php
	}

    public function show_placeholder() {
	    return 99;
    }
}

( new Pageviews() )->setup_hooks();




