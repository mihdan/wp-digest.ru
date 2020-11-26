<?php
/**
 * Plugin Name: Custom Taxonomes.
 * Description: Register Custom Taxonomes.
 */
namespace Mihdan\WP_Digest;

class Taxonomy {
	const CATEGORY = 'job_category';
	const TAG = 'job_tag';

	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_action( 'init', [ $this, 'register_taxonomy' ] );
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
	}
}

new Taxonomy();
