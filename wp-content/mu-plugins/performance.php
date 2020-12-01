<?php
add_action(
	'wp_print_styles',
	function () {
		wp_deregister_style( 'kadence-comments' );
	}
);

add_action(
	'wp_print_scripts',
	function () {
		wp_deregister_script( 'comment-reply' );
		wp_deregister_script( 'wp-embed' );
	}
);
