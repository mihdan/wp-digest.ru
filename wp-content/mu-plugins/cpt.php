<?php
/**
 * Plugin Name: Custom Post Types.
 * Description: Register Custom Post Types.
 */
namespace Mihdan\WP_Digest;

class CPT {
	const NAME = 'job';
	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_filter( 'post_type_link', [ $this, 'post_type_link' ], 1, 2 );
	}

	public function register_post_type() {
		register_post_type(
			self::NAME,
			array(
				'labels'             => array(
					'name'               => __( 'Jobs', 'wp-digest' ),
					'singular_name'      => __( 'Jobs', 'wp-digest' ),
					'add_new'            => __( 'Add new', 'wp-digest' ),
					'add_new_item'       => __( 'Add new job', 'wp-digest' ),
					'edit_item'          => __( 'Edit job', 'wp-digest' ),
					'new_item'           => __( 'New job', 'wp-digest' ),
					'view_item'          => __( 'View job', 'wp-digest' ),
					'search_items'       => __( 'Search', 'wp-digest' ),
					'not_found'          => __( 'Jobs not found', 'wp-digest' ),
					'not_found_in_trash' => __( 'Jobs not found', 'wp-digest' ),
					'menu_name'          => __( 'Jobs', 'wp-digest' ),

				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'rewrite'            => [
					'slug'       => 'jobs/%job_category%',
					'with_front' => false
				],
				'capability_type'    => 'post',
				'has_archive'        => 'jobs',
				'feeds'              => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'menu_icon'          => 'dashicons-businessman',
				'supports'           => array( 'title','editor', 'author', 'thumbnail', 'comments' )
			)
		);
	}

	public function post_type_link( $post_link, \WP_Post $post ) {
		if ( is_object( $post ) && self::NAME === $post->post_type ){
			$terms = wp_get_object_terms( $post->ID, Taxonomy::CATEGORY );
			if( $terms ){
				return str_replace( '%job_category%' , $terms[0]->slug , $post_link );
			}
		}
		return $post_link;
	}
}

new CPT();
