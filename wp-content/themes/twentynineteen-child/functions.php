<?php
namespace WP_Digest;

define( 'TWENTYNINETEEN_CHILD_VERSION', '1.0' );

/**
 * Подключаем зависимые стили и скрипты
 */
function enqueue_assets() {
	wp_enqueue_style( 'twentynineteen', get_template_directory_uri() . '/style.css', array(), TWENTYNINETEEN_CHILD_VERSION );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );

// eof;
