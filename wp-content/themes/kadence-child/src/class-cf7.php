<?php
/**
 *
 */

namespace Mihdan\Kadence_Child;

/**
 * Class CF7
 * @package Mihdan\Kadence_Child
 */
class CF7 {
	private $z_cf7_to_tlg_args;

	/**
	 * CF7 constructor.
	 */
	public function __construct() {
		$this->z_cf7_to_tlg_args = [
			'bot_token' => TELEGRAM_BOT_TOKEN,
			'receivers' => [
				TELEGRAM_RECEIVERS,
			],
		];
	}

	public function setup_hooks() {
		add_filter( 'wpcf7_mail_components', [ $this, 'z_cf7_to_tlg_action' ], 10, 3 );
	}

	public function z_cf7_to_tlg_send_message( $message, $receiver ) {

		$params['text']    = wp_strip_all_tags( $message );
		$params['chat_id'] = (int) $receiver;

		$api_url = sprintf(
			'https://api.telegram.org/bot%s/sendMessage?%s',
			$this->z_cf7_to_tlg_args['bot_token'],
			http_build_query( $params )
		);

		$response = wp_remote_get( $api_url );

		if ( is_wp_error( $response ) ) {
			error_log( 'Error in cf7-to-tlg: ' . $response );
		} else {
			$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( ! $response_body['ok'] ) {
				error_log( 'Error in cf7-to-tlg: ' . json_encode( $response_body ) );
			}
		}
	}

	public function z_cf7_to_tlg_action( $components, $wpcf7_get_current_contact_form, $instance ) {

		foreach ( $this->z_cf7_to_tlg_args['receivers'] as $receiver ) {
			$this->z_cf7_to_tlg_send_message( $components['body'], $receiver );
		}

		return $components;
	}
}