<?php
/**
 * Plugin Name: Custom Post Types.
 * Description: Register Custom Post Types.
 */
namespace Mihdan\Kadence_Child;

class CPT {
	const NAME_RESUME         = 'resume';
	const NAME_RECOMMENDATION = 'recommendations';

	public function __construct() {}

	public function setup_hooks() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_filter( 'post_type_link', [ $this, 'post_type_link' ], 1, 2 );
	}

	public function register_post_type() {

		add_rewrite_rule( 'resume/([0-9]+)?$', 'index.php?post_type=resume&p=$matches[1]', 'top' );

		register_post_type(
			self::NAME_RESUME,
			array(
				'labels'             => array(
					'name'               => __( 'Резюме', 'wp-digest' ),
					'singular_name'      => __( 'Резюме', 'wp-digest' ),
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
				'supports'           => array( 'title','editor', 'author', 'thumbnail', 'comments' ),
				'yarpp_support'      => true,
			)
		);

		register_post_type(
			'event',
			array(
				'labels'             => array(
					'name'               => __( 'События', 'wp-digest' ),
					'singular_name'      => __( 'Событие', 'wp-digest' ),
					'add_new'            => __( 'Add new', 'wp-digest' ),
					'add_new_item'       => __( 'Add new event', 'wp-digest' ),
					'edit_item'          => __( 'Edit event', 'wp-digest' ),
					'new_item'           => __( 'New event', 'wp-digest' ),
					'view_item'          => __( 'Смотреть событие', 'wp-digest' ),
					'search_items'       => __( 'Search', 'wp-digest' ),
					'not_found'          => __( 'Event not found', 'wp-digest' ),
					'not_found_in_trash' => __( 'Event not found', 'wp-digest' ),
					'menu_name'          => __( 'События', 'wp-digest' ),

				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'capability_type'    => 'post',
				'has_archive'        => 'events',
				'rest_base'          => 'events',
				'rewrite'            => [
					'slug' => 'events',
				],
				'feeds'              => true,
				'hierarchical'       => false,
				'menu_position'      => 7,
				'menu_icon'          => 'dashicons-calendar-alt',
				'supports'           => array( 'title','editor', 'author', 'thumbnail', 'comments' ),
				'yarpp_support'      => true,
			)
		);

		register_post_type(
			self::NAME_RECOMMENDATION,
			array(
				'labels'             => array(
					'name'               => __( 'Рекомендации', 'wp-digest' ),
					'singular_name'      => __( 'Recommendation', 'wp-digest' ),
					'add_new'            => __( 'Add recommendation', 'wp-digest' ),
					'add_new_item'       => __( 'Add new recommendation', 'wp-digest' ),
					'edit_item'          => __( 'Edit recommendation', 'wp-digest' ),
					'new_item'           => __( 'New recommendation', 'wp-digest' ),
					'view_item'          => __( 'View recommendation', 'wp-digest' ),
					'search_items'       => __( 'Search', 'wp-digest' ),
					'not_found'          => __( 'Recommendations not found', 'wp-digest' ),
					'not_found_in_trash' => __( 'Recommendations not found', 'wp-digest' ),
					'menu_name'          => __( 'Links', 'wp-digest' ),

				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_rest'       => true,
				'query_var'          => true,
				'rewrite'            => true,
				'capability_type'    => 'post',
				'has_archive'        => 'recommendations',
				'feeds'              => true,
				'hierarchical'       => false,
				'menu_position'      => 8,
				'menu_icon'          => 'dashicons-heart',
				'supports'           => array( 'title','editor', 'author', 'thumbnail', 'comments' ),
				'yarpp_support'      => true,
			)
		);
	}

	public function post_type_link( $post_link, \WP_Post $post ) {
		if ( is_object( $post ) && self::NAME_RESUME === $post->post_type ){
			return home_url( 'resume/' . $post->ID );
		}

		return $post_link;
	}
}
