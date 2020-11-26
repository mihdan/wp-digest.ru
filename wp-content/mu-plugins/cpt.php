<?php
/**
 * Plugin Name: Custom Post Types.
 * Description: Register Custom Post Types.
 */
namespace Mihdan\WP_Digest;

class CPT {
	const NAME_VACANCY = 'vacancy';
	const NAME_RESUME  = 'resume';

	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_filter( 'post_type_link', [ $this, 'post_type_link' ], 1, 2 );
	}

	public function register_post_type() {

		add_rewrite_rule( 'vacancies/([0-9]+)?$', 'index.php?post_type=vacancy&p=$matches[1]', 'top' );
		add_rewrite_rule( 'resume/([0-9]+)?$', 'index.php?post_type=resume&p=$matches[1]', 'top' );

		register_post_type(
			self::NAME_VACANCY,
			array(
				'labels'             => array(
					'name'               => __( 'Vacancies', 'wp-digest' ),
					'singular_name'      => __( 'Vacancies', 'wp-digest' ),
					'add_new'            => __( 'Add new', 'wp-digest' ),
					'add_new_item'       => __( 'Add new vacancy', 'wp-digest' ),
					'edit_item'          => __( 'Edit vacancy', 'wp-digest' ),
					'new_item'           => __( 'New vacancy', 'wp-digest' ),
					'view_item'          => __( 'View vacancy', 'wp-digest' ),
					'search_items'       => __( 'Search', 'wp-digest' ),
					'not_found'          => __( 'Vacancies not found', 'wp-digest' ),
					'not_found_in_trash' => __( 'Vacancies not found', 'wp-digest' ),
					'menu_name'          => __( 'Vacancies', 'wp-digest' ),

				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'rewrite'            => true,
				'capability_type'    => 'post',
				'has_archive'        => 'vacancies',
				'feeds'              => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'menu_icon'          => 'dashicons-groups',
				'supports'           => array( 'title','editor', 'author', 'thumbnail', 'comments' )
			)
		);

		register_post_type(
			self::NAME_RESUME,
			array(
				'labels'             => array(
					'name'               => __( 'Resume', 'wp-digest' ),
					'singular_name'      => __( 'Resume', 'wp-digest' ),
					'add_new'            => __( 'Add new', 'wp-digest' ),
					'add_new_item'       => __( 'Add new resume', 'wp-digest' ),
					'edit_item'          => __( 'Edit resume', 'wp-digest' ),
					'new_item'           => __( 'New resume', 'wp-digest' ),
					'view_item'          => __( 'View resume', 'wp-digest' ),
					'search_items'       => __( 'Search', 'wp-digest' ),
					'not_found'          => __( 'Resume not found', 'wp-digest' ),
					'not_found_in_trash' => __( 'Resume not found', 'wp-digest' ),
					'menu_name'          => __( 'Resume', 'wp-digest' ),

				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'rewrite'            => true,
				'capability_type'    => 'post',
				'has_archive'        => 'resume',
				'feeds'              => true,
				'hierarchical'       => false,
				'menu_position'      => 6,
				'menu_icon'          => 'dashicons-id-alt',
				'supports'           => array( 'title','editor', 'author', 'thumbnail', 'comments' )
			)
		);
	}

	public function post_type_link( $post_link, \WP_Post $post ) {
		if ( is_object( $post ) && self::NAME_RESUME === $post->post_type ){
			return home_url( 'resume/' . $post->ID );
		}

		if ( is_object( $post ) && self::NAME_VACANCY === $post->post_type ){
			return home_url( 'vacancies/' . $post->ID );
		}

		return $post_link;
	}
}

new CPT();
