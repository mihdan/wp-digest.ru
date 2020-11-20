<?php
/**
 * Plugin Name: ACF.
 * Description: Save custom fields in json files.
 */
namespace Mihdan\WP_Digest;

class ACF {

	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_filter( 'acf/settings/save_json', array( $this, 'save_json' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'load_json' ) );
	}

	public function save_json( $path ) {
		$path = __DIR__ . '/assets/json';

		return $path;
	}

	public function load_json( $paths ) {
		// Отключить путь по умолчанию.
		unset( $paths[0] );

		// Добавить свой путь.
		$paths[] = __DIR__ . '/assets/json';

		return $paths;
	}
}

new ACF();
