<?php
/**
 * SEO Settings.
 */

namespace Mihdan\WP_Digest;

use WP;

class SEO {
	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
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

		/**
		 * Disable sitemap indexation.
		 */
		//add_action( 'the_seo_framework_sitemap_header', [ $this, 'header_noindex' ], 11 );
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
			header( 'X-Robots-Tag: noindex, nofollow', true );
		}
	}
}

new SEO();

// eol.
