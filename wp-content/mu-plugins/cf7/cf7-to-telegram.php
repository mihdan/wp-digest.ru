<?php
$z_cf7_to_tlg_args = [
	'bot_token' => TELEGRAM_BOT_TOKEN,
	'receivers' => [
		TELEGRAM_RECEIVERS,
	],
];
function z_cf7_to_tlg_send_message( $message, $receiver ) {
	global $z_cf7_to_tlg_args;
	$params['text']    = wp_strip_all_tags( $message );
	$params['chat_id'] = intval( $receiver );

	$api_url = sprintf(
		'https://api.telegram.org/bot%s/sendMessage?%s',
		$z_cf7_to_tlg_args['bot_token'],
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

function z_cf7_to_tlg_action( $components, $wpcf7_get_current_contact_form, $instance ) {
	global $z_cf7_to_tlg_args;

	foreach ( $z_cf7_to_tlg_args['receivers'] as $receiver ) {
		z_cf7_to_tlg_send_message( $components['body'], $receiver );
	}

	return $components;
}

add_filter( 'wpcf7_mail_components', 'z_cf7_to_tlg_action', 10, 3 );

// eol.
