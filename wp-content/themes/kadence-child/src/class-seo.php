<?php
/**
 * SEO Settings.
 */

namespace Mihdan\Kadence_Child;

use WP;

class SEO {
	public function __construct() {
	}

	public function setup_hooks() {
		/**
		 * Отключить ссылку на wp.org в виджете "Мета".
		 */
		add_filter( 'widget_meta_poweredby', '__return_empty_string' );

		/**
		 * Запретить индекацию служебных страниц,
		 * отправляя X-Robots-Tag.
		 *
		 * wp-login.php, sitemap.xml, /feed/
		 */
		add_action( 'login_init', [ $this, 'header_noindex' ], 1 );
		add_action( 'do_feed_rss2', [ $this, 'header_noindex' ], 1 );
		add_action( 'do_feed_rss', [ $this, 'header_noindex' ], 1 );
		add_action( 'do_feed_atom', [ $this, 'header_noindex' ], 1 );
		add_action( 'template_redirect', [ $this, 'noindex_feeds' ], 11 );

		/**
		 * Disable feed links.
		 */
		add_action( 'wp_head', [ $this, 'disable_feed_links' ], 1 );

		add_action( 'wp_head', [ $this, 'add_telegram_channel_link' ] );

		/**
		 * Disable sitemap indexation.
		 */
		//add_action( 'the_seo_framework_sitemap_header', [ $this, 'header_noindex' ], 11 );

		/**
		 * Set custom description.
		 */
		add_filter( 'the_seo_framework_generated_archive_title', [ $this, 'set_meta_archive_title' ], 10, 2 );
		add_filter( 'the_seo_framework_generated_description', [ $this, 'set_meta_description' ], 10, 2 );
		add_filter( 'get_the_archive_description', [ $this, 'set_description' ] );

		add_filter( 'kadence_microdata', '__return_false' );

		add_filter( 'the_seo_framework_robots_meta', function ( $meta ) {
		    if ( ! is_tag() ) {
		        return $meta;
            }

		    $meta['robots'] = 'noindex';

		    return $meta;
        } );
	}

	public function add_telegram_channel_link() {
		?>
		<meta property="telegram:channel" content="@wordpress_digest" />
		<?php if ( is_tag() ) : ?>
            <meta name="robots" content="noindex" />
        <?php endif; ?>
        <?php
	}

	public function set_meta_archive_title( $title, $args ) {
		if ( is_post_type_archive( 'event' ) ) {
			return 'Календарь событий и мероприятий WordPress';
		}

		return $title;

	}

	public function set_meta_description( $description, $args ) {

		if ( null === $args && is_post_type_archive( 'post' ) ) {
			//$description = 'My custom description';
		}

		if ( null === $args && is_archive() ) {
			$description = sprintf( 'В этой рубрике мы расскажем про %s и всё, что с этим связано.', mb_convert_case( single_cat_title( '', false ), MB_CASE_LOWER ) );
		}

		// В description выводим текст до тега load more.
		if ( null === $args && is_singular() ) {
		    $post = get_post();

			$description = wp_trim_words( get_extended( $post->post_content )['main'], 30 );
        }

		if ( null === $args && is_singular( ['vacancy'] ) ) {
			//$description = 'Компания «%s» ищет хорошего специалиста на вакансию «%s»';

            /**
             * Требуется «Senior WordPress-разработчик» (Москва)
             *
             * Компания «Admitad» ищет хорошего специалиста на вакансию «Senior WordPress-разработчик». Москва (Россия). Полный рабочий день. Можно удалённо. Требуемые навыки: #software, #senior, #PHP, #MySQL, #Wordpress.
             *
             * Требуется «Middle Full-stack Developer (Мидл фуллстэк разработчик)» (от 120 000 до 140 000 ₽)
             *
             * Компания «Wildlabs» ищет хорошего специалиста на вакансию «Middle Full-stack Developer (Мидл фуллстэк разработчик)». От 120 000 ₽ до 140 000 ₽. Полный рабочий день. Можно удалённо. Требуемые навыки: #frontend, #middle, #JavaScript, #Vue.js, #Адаптивнаяверстка, #Кроссбраузернаяверстка, #БЭМ, #Вебразработка, #Wordpress, #Laravel, #PHP.
             */
		}

		return $description;
	}

	public function set_description( $description ) {

		if ( is_archive() && empty( $description ) ) {
			$description = sprintf( '<p>Самая полная информация про %s и как их применять в WordPress.</p>', mb_convert_case( single_cat_title( '', false ), MB_CASE_LOWER ) );
		}

		return $description;
	}

	public function noindex_feeds() {
		if ( is_feed() ) {
			$this->header_noindex();
		}
	}

	public function disable_feed_links() {
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}

	public function header_noindex() {
		if ( ! headers_sent() ) {
			header( 'X-Robots-Tag: noindex', true );
		}
	}
}
