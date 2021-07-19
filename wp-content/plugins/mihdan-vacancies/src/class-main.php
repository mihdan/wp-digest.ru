<?php
namespace Mihdan\Vacancies;
class Main {
	const CPT      = 'vacancy';
	const CATEGORY = 'job_category';
	const TAG      = 'job_tag';

	public function __construct() {
        ( new Cron() )->setup_hooks();
	}

	public function setup_hooks() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_filter( 'post_type_link', [ $this, 'post_type_link' ], 1, 2 );
		add_action( 'kadence_after_main_content', [ $this, 'add_buttons' ] );
		add_action( 'kadence_single_after_entry_title', [ $this, 'add_status' ] );
	}

	public function add_status() {
	    if ( ! is_singular( self::CPT ) ) {
	        return;
        }

	    $vacancy_valid_through = strtotime( get_field( 'vacancy_valid_through' ) );
		$current_datetime      = current_datetime()->getTimestamp();
        ?>
        <?php if ( $vacancy_valid_through < $current_datetime ) : ?>
        <p class="vacancy-status vacancy-status--closed">Вакансия закрыта</p>
        <?php endif; ?>
        <?php
    }

	public function add_buttons() {
		if ( ! is_singular( self::CPT ) ) {
			return;
		}
		?>
        <style>
            .vacancy-buttons {
                background-color: #fff;
                padding: 2rem 2rem 1rem 2rem;
                position: relative;
            }
            .vacancy-buttons .button {
                margin-bottom: 1rem;
            }
            .vacancy-status {
                border-radius: 3px;
                padding: 3px 8px;
                display: inline-block;
                font-size: .9rem;
            }
            .vacancy-status--closed {
                background-color: #ba5625;
                color: #fff;
            }
            @media (max-width : 544px) {
                .vacancy-buttons {
                    margin-left: -1rem;
                    margin-right: -1rem;
                }
                .vacancy-buttons .button {
                    width: 100%;
                    display: block;
                    text-align: center;
                }
            }
        </style>
		<div class="vacancy-buttons">
			<a href="https://wp-digest.com/add-vacancy/" class="button" target="_blank">Добавить вакансию</a>
			<a href="https://t.me/wordpress_jobs" class="button" target="_blank"><svg viewBox="0 0 14 13" id="ui_telegram_2" width="16" height="16"><path fill="#ffffff" fill-rule="evenodd" d="M12.89.042a.846.846 0 011.088.997l-2.46 10.459a.84.84 0 01-.825.652.84.84 0 01-.533-.192L7.519 9.797l-2.26 1.691-.007.006c-.14.106-.275.206-.465.158-.19-.048-.28-.206-.318-.383l-.918-4.26L.506 5.677a.84.84 0 01-.505-.814.84.84 0 01.58-.763L12.89.042zM5.452 7.785a.411.411 0 01.098-.171l2.732-2.857-3.908 2.171.569 2.636.509-1.78zm.166 2.408l.231-.808.221-.773.793.648-1.245.933zm5.1 1.116L13.177.85c0-.006.002-.013-.008-.022s-.016-.007-.022-.005L.838 4.881c-.01.003-.015.005-.016.02 0 .016.005.019.014.023l3.05 1.334 6.37-3.539a.411.411 0 01.497.644L6.451 7.86l1.34 1.096 2.89 2.365c.004.003.01.008.021.004.012-.004.014-.012.015-.017z" clip-rule="evenodd"></path></svg> Telegram вакансий</a>
		</div>
		<?php
	}

	public function register_post_type() {
		add_rewrite_rule( 'vacancies/([0-9]+)?$', 'index.php?post_type=vacancy&p=$matches[1]', 'top' );

		register_post_type(
			self::CPT,
			array(
				'labels'             => array(
					'name'               => __( 'Вакансии', 'wp-digest' ),
					'singular_name'      => __( 'Вакансия', 'wp-digest' ),
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
				'supports'           => array( 'title','editor', 'author', 'thumbnail', 'comments' ),
				'yarpp_support'      => true,
			)
		);

		register_taxonomy(
			'job_hiring_organization',
			[ self::CPT ],
			[
				'labels'                => [
					'name'              => __( 'Organizations', 'wp-digest' ),
					'singular_name'     => __( 'Organization', 'wp-digest' ),
					'search_items'      => __( 'Search', 'wp-digest' ),
					'all_items'         => __( 'All organizations', 'wp-digest' ),
					'view_item '        => __( 'View organization', 'wp-digest' ),
					'edit_item'         => __( 'Edit organization', 'wp-digest' ),
					'update_item'       => __( 'Update organization', 'wp-digest' ),
					'add_new_item'      => __( 'Add new organization', 'wp-digest' ),
					'new_item_name'     => __( 'Add', 'wp-digest' ),
					'menu_name'         => __( 'Organizations', 'wp-digest' ),
				],
				'hierarchical'      => false,
				'public'            => true,
				'rewrite'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rest_base'         => 'job-hiring-organizations'
			]
		);

		register_taxonomy(
			self::CATEGORY,
			[ self::CPT ],
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
			[ self::CPT ],
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
	public function post_type_link( $post_link, \WP_Post $post ) {

		if ( is_object( $post ) && self::CPT === $post->post_type ){
			return home_url( 'vacancies/' . $post->ID );
		}

		return $post_link;
	}

}