<?php
/**
 * Plugin Name: Custom Taxonomes.
 * Description: Register Custom Taxonomes.
 */
namespace Mihdan\Kadence_Child;

class Taxonomy {
	const CATEGORY = 'job_category';
	const TAG = 'job_tag';
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
			self::CATEGORY,
			[ CPT::NAME_VACANCY, CPT::NAME_RESUME ],
			[
				'labels'                => [
					'name'              => __( 'Categories', 'wp-digest' ),
					'singular_name'     => __( 'Category', 'wp-digest' ),
					'search_items'      => __( 'Search', 'wp-digest' ),
					'all_items'         => __( 'All categories', 'wp-digest' ),
					'view_item '        => __( 'View category', 'wp-digest' ),
					'edit_item'         => __( 'Edit category', 'wp-digest' ),
					'update_item'       => __( 'Update category', 'wp-digest' ),
					'add_new_item'      => __( 'Add new category', 'wp-digest' ),
					'new_item_name'     => __( 'Categories', 'wp-digest' ),
					'menu_name'         => __( 'Categories', 'wp-digest' ),
				],
				'hierarchical'      => true,
				'public'            => true,
				'rewrite'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rest_base'         => 'job_categories'
			]
		);

		register_taxonomy(
			self::TAG,
			[ CPT::NAME_VACANCY, CPT::NAME_RESUME ],
			[
				'labels'                => [
					'name'              => __( 'Tags', 'wp-digest' ),
					'singular_name'     => __( 'Tag', 'wp-digest' ),
					'search_items'      => __( 'Search', 'wp-digest' ),
					'all_items'         => __( 'All tags', 'wp-digest' ),
					'view_item '        => __( 'View tag', 'wp-digest' ),
					'edit_item'         => __( 'Edit tag', 'wp-digest' ),
					'update_item'       => __( 'Update tag', 'wp-digest' ),
					'add_new_item'      => __( 'Add new tag', 'wp-digest' ),
					'new_item_name'     => __( 'Tags', 'wp-digest' ),
					'menu_name'         => __( 'Tags', 'wp-digest' ),
				],
				'public'            => true,
				'rewrite'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rest_base'         => 'job_tags'
			]
		);

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
