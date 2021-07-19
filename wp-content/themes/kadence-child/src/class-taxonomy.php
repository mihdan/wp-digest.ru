<?php
/**
 * Plugin Name: Custom Taxonomes.
 * Description: Register Custom Taxonomes.
 */
namespace Mihdan\Kadence_Child;

class Taxonomy {
	const RESUME_STATUS = 'resume_status';

	public function __construct() {
	}

	public function setup_hooks() {
		add_action( 'init', [ $this, 'register_taxonomy' ] );
		add_action( 'admin_init', [ $this, 'add_settings_for_default_term' ] );
	}

	public function add_settings_for_default_term() {
		register_setting( 'writing', 'default_' . self::RESUME_STATUS );
		add_settings_field(
			'default_' . self::RESUME_STATUS,
			__( 'Default resume status', 'wp-digest' ),
			'wp_dropdown_categories',
			'writing',
			'default',
			array(
				'taxonomy'   => self::RESUME_STATUS,
				'hide_empty' => false,
			)
		);
	}

	public function register_taxonomy() {
		register_taxonomy(
			self::RESUME_STATUS,
			[ CPT::NAME_RESUME ],
			[
				'labels'                => [
					'name'              => __( 'Statuses', 'wp-digest' ),
					'singular_name'     => __( 'Status', 'wp-digest' ),
					'search_items'      => __( 'Search', 'wp-digest' ),
					'all_items'         => __( 'All statuses', 'wp-digest' ),
					'view_item '        => __( 'View status', 'wp-digest' ),
					'edit_item'         => __( 'Edit status', 'wp-digest' ),
					'update_item'       => __( 'Update status', 'wp-digest' ),
					'add_new_item'      => __( 'Add new status', 'wp-digest' ),
					'new_item_name'     => __( 'Add new status', 'wp-digest' ),
					'menu_name'         => __( 'Statuses', 'wp-digest' ),
				],
				'public'            => true,
				'rewrite'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'hierarchical'      => true,
				'default_term'      => 'new',
			]
		);
	}
}
