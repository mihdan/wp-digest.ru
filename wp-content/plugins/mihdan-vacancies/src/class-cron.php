<?php
namespace Mihdan\Vacancies;

class Cron {
	protected $event    = 'remoteok';
	protected $group    = 'mihdan-vacancies';
	protected $endpoint = 'https://remoteok.io/api?ref=producthunt&tag=wordpress';

	public function setup_hooks() {
		add_action( 'init', array( $this, 'schedule_event' ) );
		add_action( $this->get_event_name(), array( $this, 'refresh' ) );
	}

	public function get_event_name() {
		return $this->event;
	}

	public function get_event_group() {
		return $this->group;
	}

	public function get_endpoint() {
		return $this->endpoint;
	}

	public function schedule_event() {
		if ( ! as_next_scheduled_action( $this->get_event_name(), [], $this->get_event_group() ) ) {
			as_schedule_recurring_action( time(), HOUR_IN_SECONDS, $this->get_event_name(), [], $this->get_event_group() );
		}
	}

	public function refresh() {
		// Запоминаем текущее состояние кэша.
		$was_suspended = wp_suspend_cache_addition();

		// Отключаем кэширование.
		wp_suspend_cache_addition( true );

		if ( 1==2 && is_user_logged_in() ) {
			$request = wp_remote_get( $this->get_endpoint() );
			$response = wp_remote_retrieve_body( $request );
			$response = json_decode( $response );

			foreach ( $response as $i => $item ) {
				if ( $i === 0 ) {
					continue;
				}

				$args = [
					'post_title' => ''
				];
			}

			print_r($response);
			die;

		}

		wp_suspend_cache_addition( $was_suspended );
	}
}