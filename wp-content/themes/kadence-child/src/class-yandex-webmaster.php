<?php
namespace Mihdan\Kadence_Child;

use WP_Post;

class Yandex_Mebmaster {
	/**
	 * Setup hooks.
	 */
	public function setup_hooks() {
		add_action( 'transition_post_status', array( $this, 'host_recrawl' ), 10, 3 );
		// Uncomment for debug.
		//add_action( 'after_setup_theme', function () {
			//if ( ! empty( $_GET['manda'] ) ) {
				//$this->host_recrawl( 'publish', 'draft', get_post( 3213 ) );
			//}
		//} );
	}

	/**
	 * Fires actions related to the transitioning of a post's status.
	 *
	 * @param string  $new_status Transition to this post status.
	 * @param string  $old_status Previous post status.
	 * @param WP_Post $post       Post data.
	 *
	 * @link https://yandex.ru/dev/webmaster/doc/dg/reference/host-recrawl-post.html
	 */
	public function host_recrawl( $new_status, $old_status, WP_Post $post ) {
		// Срабатывает только на статус publish.
		if ( 'publish' !== $new_status || 'publish' === $old_status || 'post' !== $post->post_type ) {
			return;
		}

		$user_id = YANDEX_WEBMASTER_USER_ID;
		$host_id = YANDEX_WEBMASTER_HOST_ID;
		$token   = YANDEX_WEBMASTER_TOKEN;

		$url = 'https://api.webmaster.yandex.net/v4/user/%s/hosts/%s/recrawl/queue';
		$url = sprintf( $url, $user_id, $host_id );
		$args = array(
			'timeout' => 30,
			'headers' => array(
				'Authorization' => 'OAuth ' . $token,
				'Content-Type'  => 'application/json',
			),
			'body' => json_encode(
				array(
					'url' => get_permalink( $post->ID )
				)
			),
		);

		$response = wp_remote_post( $url, $args );
	}
}