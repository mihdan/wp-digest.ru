<?php
namespace Mihdan\Kadence_Child;

use PHPMailer\PHPMailer\PHPMailer;
use WP_Admin_Bar;
use Auryn\Injector;

// @codeCoverageIgnoreStart
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
// @codeCoverageIgnoreEnd

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';

	( new Main( new Injector() ) )->init();
}

define( 'TWENTYNINETEEN_CHILD_VERSION', '1.0' );

/**
 * Настройки темы
 */
function setup_theme() {

	// Велючаем счетчик просмотров поста
	add_theme_support( 'pageviews' );

	// Включаем вставку ссылок на фиды в шапку сайта
	add_theme_support( 'automatic-feed-links' );

	// Включаем вставку тега тайтла в шапку сайта
	add_theme_support( 'title-tag' );

	// Включаем поддержку AMP
	add_theme_support(
		'amp',
		array(
			'comments_live_list' => true,
		)
	);

	// Влючаем поддуржку темой HTML5
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	// Показать метабокс с выбором формата поста
	$formats = array(
		'link',
		'audio',
		'video',
	);
	add_theme_support( 'post-formats', $formats );

	add_editor_style( plugins_url( 'assets/css/gutenberg.css', __FILE__ ) );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup_theme' );

/**
 * Подключаем зависимые стили и скрипты
 */
function enqueue_assets() {
	//wp_enqueue_style( 'twentynineteen', get_template_directory_uri() . '/style.css', array(), TWENTYNINETEEN_CHILD_VERSION );
	//wp_enqueue_style('kadence', get_template_directory_uri() .'/style.css' );
	wp_enqueue_style( 'app', get_theme_file_uri( 'assets/css/app.css' ), array(), filemtime( get_theme_file_path( 'assets/css/app.css' ) ) );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets', 11 );

/**
 * Добавить тумбочки к RSS
 *
 * @param $content
 * @return string
 */
function rss_post_excerpt_thumbnail( $content ) {
	global $post;

	$extended = get_extended( $post->post_content );

	if ( ! empty( $extended['main'] ) ) {
		$content = do_blocks( $extended['main'] );

		if ( has_post_thumbnail( $post->ID ) ) {
			$content = '<p>' . get_the_post_thumbnail( $post->ID, 'full' ) . '</p>' . $content;
		}

		return $content;
	}

	return $content;
}
add_filter( 'the_excerpt_rss', __NAMESPACE__ . '\rss_post_excerpt_thumbnail' );

/**
 * Если в результате поиска нашелся всего один пост - редиректим на него
 */
function search_redirect() {
	if ( is_search() && ! is_paged() ) {
		global $wp_query;
		if ( 1 === $wp_query->post_count ) {
			wp_safe_redirect( get_permalink( $wp_query->posts[0]->ID ) );
		}
	}
}
add_action( 'template_redirect', __NAMESPACE__ . '\search_redirect' );

/**
 * Remove class from post_class() function.
 *
 * Этот класс используется в микроформатах гуглом
 */
function remove_hentry_class( $classes ) {
	$classes = array_diff( $classes, array( 'hentry' ) );
	return $classes;
}
add_filter( 'post_class', __NAMESPACE__ . '\remove_hentry_class' );

/**
 * Добавить микроразметку в ссылку на предыдущуя статью
 *
 * @param $output
 *
 * @return mixed
 */
function add_itemprop_to_post_link( $output ) {
	return str_replace( 'rel="', 'itemprop="relatedLink" rel="', $output );
}
add_filter( 'previous_post_link', __NAMESPACE__ . '\add_itemprop_to_post_link' );
add_filter( 'next_post_link', __NAMESPACE__ . '\add_itemprop_to_post_link' );

/**
 * Меняем title логотипа на странице логина.
 *
 * @param string $title - текущий тайтл.
 *
 * @return string
 */
function login_headertitle( $title ) {
	$title = get_bloginfo( 'name' );
	return $title;
}
add_filter( 'login_headertitle', __NAMESPACE__ . '\login_headertitle' );

/**
 * Меняем URL логотипа на странице логина.
 *
 * @param string $url - текущий URL.
 *
 * @return string
 */
function login_headerurl( $url ) {
	$url = get_bloginfo( 'url' );
	return $url;
}
add_filter( 'login_headerurl', __NAMESPACE__ . '\login_headerurl' );

/**
 * Удалить пункты меню из тулбара WordPress
 *
 * @param \WP_Admin_Bar $wp_admin_bar объект тулбара
 */
function remove_toolbar_node( \WP_Admin_Bar $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );
	//$wp_admin_bar->remove_node( 'customize' );
	$wp_admin_bar->remove_node( 'comments' );
	$wp_admin_bar->remove_node( 'wpseo-menu' );
	$wp_admin_bar->remove_node( 'search' );
	$wp_admin_bar->remove_node( 'ai-toolbar-settings' );
}
add_action( 'admin_bar_menu', __NAMESPACE__ . '\remove_toolbar_node', 999 );

/**
 * Настройка SMTP
 *
 * @param PHPMailer $phpmailer объект мэилера
 */
function smtp_settings( PHPMailer $phpmailer ) {
	// @codingStandardsIgnoreStart
	$phpmailer->isSMTP();
	$phpmailer->Host       = SMTP_HOST;
	$phpmailer->SMTPAuth   = SMTP_AUTH;
	$phpmailer->Port       = SMTP_PORT;
	$phpmailer->Username   = SMTP_USER;
	$phpmailer->Password   = SMTP_PASS;
	$phpmailer->SMTPSecure = SMTP_SECURE;
	$phpmailer->From       = SMTP_FROM;
	$phpmailer->FromName   = SMTP_NAME;
	// @codingStandardsIgnoreEnd
}
add_action( 'phpmailer_init', __NAMESPACE__ . '\smtp_settings' );

/**
 * Запретить кривые урлы на посты редиректить на первый найденый пост
 *
 * @param $url
 *
 * @return bool
 */
function stop_404_guessing( $url ) {
	return ( is_404() ) ? false : $url;
}
add_filter( 'redirect_canonical', __NAMESPACE__ . '\stop_404_guessing' );

/**
 * Редирект после выхода с сайта
 */
function redirect_after_logout() {
	wp_safe_redirect( wp_get_referer() );
	exit();
}
add_action( 'wp_logout', __NAMESPACE__ . '\redirect_after_logout' );

/**
 * Запретить индексацию ссылок входа/выхода
 *
 * @param $link
 *
 * @return mixed
 */
function add_nofollow_to_loginout( $link ) {

	$link = str_replace( 'href="', 'rel="nofollow" data-toggle="modal" data-target="#modal-login" href="', $link );

	return $link;
}
add_filter( 'loginout', __NAMESPACE__ . '\add_nofollow_to_loginout' );

/**
 * Добавить шеры от likely
 *
 * @param string $content Содержимое записи.
 */
function add_likely( $content ) {

	if ( is_admin() ) {
		return $content;
	}

	if ( ! is_singular( [ 'post', 'recommendations' ] ) ) {
		return $content;
	}

	//require_once 'template-parts/likely.php';
	$url  = get_post_meta( get_the_ID(), 'post_source_url', true );
	$text = $title = get_post_meta( get_the_ID(), 'post_source_text', true );

	if ( ! $url ) {
		return $content;
	}

	$url = add_query_arg( 'utm_source', 'wp-digest.com', $url );

	if ( ! $text ) {
		$text  = 'Читать далее';
		$title = parse_url( $url, PHP_URL_HOST );
	}

	//return $content . sprintf( '<div class="wp-block-buttons wp-block-buttons--actions"><div class="wp-block-button wp-block-button--more"><a class="wp-block-button__link wp-block-button__link--more" href="%s" target="_blank" title="%s">%s</a></div><!--div class="wp-block-button is-style-outline wp-block-button--comments"><a href="#reply-title" class="wp-block-button__link button--comments icon-comment-alt">Оставить комментарий</a></div--><!--div class="wp-block-button is-style-outline wp-block-button--emoji"><a href="#post-emoji" class="wp-block-button__link button--emoji">Оценить</a></div--></div>', esc_url( $url ), esc_attr( $title ), esc_attr( $text ) );

	return $content . sprintf( '<p class="read-more read-more--post"><a href="%s" target="_blank" title="%s">%s</a></p>', esc_url( $url ), esc_attr( $title ), esc_attr( $text ) );
}
add_filter( 'the_content', __NAMESPACE__ . '\add_likely', 1 );

add_filter(
	'widget_title',
	function ( $title ) {
		if ( ! empty( $title ) ) {
			return sprintf( '<span class="widget-title__wrapper">%s</span>', $title );
		}

		return $title;
	}
);

/**
 * Удалить у стандартных постов ненужные метабоксы
 */
function remove_content_from_post() {
	remove_post_type_support( 'post', 'excerpt' );
	remove_post_type_support( 'post', 'custom-fields' );
	remove_post_type_support( 'post', 'page-attributes' );
	remove_post_type_support( 'post', 'post-formats' );
	remove_post_type_support( 'post', 'trackbacks' );
}
add_action( 'init', __NAMESPACE__ . '\remove_content_from_post' );

/**
 * Код Google Tag Manager в <head>.
 */
add_action(
	'wp_head',
	function () {
		?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-NB92PPM');</script>
		<!-- End Google Tag Manager -->
		<?php
	}
);

/**
 * Код Google Tag Manager в <body>.
 */
add_action(
	'wp_body_open',
	function () {
		?>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NB92PPM" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php
	}
);

/**
 * Добавить лого на странице авторизации.
 */
add_action(
	'login_footer',
	function () {
		if ( has_custom_logo() ) {
			?>
			<style>
                #login h1 a {
                    width: 160px;
                    height: 74px;
                    background-image: url("<?php echo ( wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) ); ?>");
                    background-size: 160px auto;
                }
                #nav,
                #backtoblog,
                .privacy-policy-page-link {
                    display: none;
                }
			</style>
			<?php
		}
	}
);

/**
 * Добавляет в админ-сайдбар ссылки на инструкции-шпаргалки.
 * Меню создаётся классическим способом через админку.
 */
add_action( 'after_setup_theme', function () {
	$title = 'Шпаргалки';
	$slug  = 'admin-instructions';

	register_nav_menu( $slug, $title );

	add_action( 'admin_bar_menu', function ( WP_Admin_Bar $toolbar ) use ( $title, $slug ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$items = wp_get_nav_menu_items( get_nav_menu_locations()[ $slug ] ?? false );

		if ( ! $items ) {
			return;
		}

		$toolbar->add_node( [
			'id'    => $slug,
			'title' => $title,
		] );

		foreach ( $items as $item ) {
			$toolbar->add_node( [
				'parent' => $slug,
				'id'     => $slug . '-' . (int) $item->ID,
				'title'  => esc_html( $item->title ),
				'href'   => esc_url( $item->url ),
				'meta'   => [
					'target' => '_blank',
				],
			] );
		}
	}, 90 );

} );

add_action(
	'kadence_after_entry_meta',
	function () {
		if ( ! is_singular( 'resume' ) ) {
			return;
		}
		$status    = get_term( get_option( 'default_term_resume_status' ) )->name;
		$resume_id = get_the_ID();
		$statuses  = wp_get_post_terms( $resume_id, 'resume_status', [ 'fields' => 'names' ] );

		if ( $statuses ) {
			$status = $statuses[0];
		}
		?>
		<span>Статус: <?php echo esc_html( $status ); ?></span>
		<?php
	}
);

add_action(
	'kadence_single_after_entry_content',
	function () {
		if ( ! is_singular( 'resume' ) ) {
			return;
		}

		$status    = get_term( get_option( 'default_term_resume_status' ) )->name;
		$resume_id = get_the_ID();
		$statuses  = wp_list_pluck( wp_get_post_terms( $resume_id, 'resume_status' ), 'slug' );

		// Нашел работу.
		if ( 'employed' === $statuses[0] ) {
			return;
		}

		$contacts = get_field( 'resume_contacts', $resume_id );

		if ( ! $contacts ) {
			return;
		}
		?>
		<h2 id="resume-contacts">Контакты</h2>
		<ul>
			<?php foreach ( $contacts as $contact ) : ?>
				<li>
					<?php if ( 'telegram' === $contact['acf_fc_layout'] ) : ?>
						Телеграм: <a href="<?php echo esc_url( $contact['value'] ); ?>" target="_blank"><?php echo esc_html( $contact['key'] ); ?></a>
					<?php elseif ( 'email' === $contact['acf_fc_layout'] ) : ?>
						Email: <a href="mailto:<?php echo esc_attr( $contact['value'] ); ?>" target="_blank"><?php echo esc_html( $contact['key'] ); ?></a>
					<?php elseif ( 'link' === $contact['acf_fc_layout'] ) : ?>
						<?php echo esc_html( $contact['key'] ); ?>: <a href="<?php echo esc_url( $contact['value'] ); ?>" target="_blank"><?php echo esc_url( $contact['value'] ); ?></a>
					<?php elseif ( 'phone' === $contact['acf_fc_layout'] ) : ?>
						Телефон: <a href="tel:<?php echo absint( $contact['value'] ); ?>" target="_blank">+<?php echo absint( $contact['value'] ); ?></a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
);


add_filter(
	'previous_post_link_',
	function( $output, $format, $link, $post, $adjacent ){
//        print_r($output);

		$thumbnail = get_the_post_thumbnail_url( $post );

		return $output . $thumbnail;
	},
	10,
	5
);