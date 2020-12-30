<?php
/**
 * WPScan informer.
 */

namespace Mihdan\Kadence_Child;

class WPScan {
	private const TRANSIENT = 'wpscan';
	private const BASE      = 'https://wpscan.com/api/v3/wordpresses/55';

	/**
	 * WPScan API token.
	 *
	 * @var string $token
	 */
	private $token;

	/**
	 * WPScan constructor.
	 *
	 * @param string $token WPScan API token.
	 */
	public function __construct( string $token ) {
		$this->token = $token;
	}

	public function setup_hooks() {
		add_shortcode( 'wpscan', [ $this, 'render_shortcode' ] );
	}

	private function get_token() {
		return $this->token;
	}

	public function render_shortcode() {

		$transient = self::TRANSIENT . '_' . md5( self::BASE );

		$cached = get_transient( $transient );

		if ( $cached !== false ) {
			return $cached;
		}

		$args = [
			'headers' => [
				'Authorization' => 'Token token=' . $this->get_token(),
			],
		];

		$request = wp_remote_get( self::BASE, $args );

		if ( is_wp_error( $request ) ){
			return $request->get_error_message();
		}

		$body = wp_remote_retrieve_body( $request );
		$body = json_decode( $body, true );

		$html = [];

		foreach ( $body['5.5']['vulnerabilities'] as $vulnerability ) {
			$references = $vulnerability['references'];

			$html[] = '<h2>' . esc_html( $vulnerability['title'] ) . '</h2>';
			$html[] = '<ul>';
			$html[] = '<li>Дата создания: ' . date( 'd.m.Y',  strtotime( $vulnerability['created_at'] ) ) . '</li>';
			$html[] = '<li>Дата обновления: ' . date( 'd.m.Y',  strtotime( $vulnerability['updated_at'] ) ) . '</li>';
			$html[] = '<li>Дата публикации: ' . date( 'd.m.Y',  strtotime( $vulnerability['published_date'] ) ) . '</li>';
			$html[] = '<li>Тип уязвимости: ' . esc_html( $vulnerability['vuln_type'] ) . '</li>';
			$html[] = '<li>Исправлено в версии: ' . esc_html( $vulnerability['fixed_in'] ) . '</li>';
			$html[] = '<li>CVE: ' . esc_html( implode( ', ', $references['cve'] ) ) . '</li>';
			$html[] = '<li>Ссылки:';
			$html[] = '<ol>';

			foreach ( $references['url'] as $url ) {
				$html[] = '<li><a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( $url ) . '</a></li>';
			}

			$html[] = '</ol>';
			$html[] = '</li>';
			$html[] = '</ul>';
		}

		$html = implode( ' ', $html );
		set_transient( $transient, $html, HOUR_IN_SECONDS );

		return $html;
	}

}
