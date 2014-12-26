<?php
/**
 * Generate functions and definitions
 *
 * @package Forum
 */
	
define( 'FORUM_VERSION', '1.0.0');
define( 'FORUM_URI', get_stylesheet_directory_uri() );
define( 'FORUM_DIR', get_template_directory() );

/**
 * Enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'forum_scripts' );
function forum_scripts() {
	// Forum stylesheets
	wp_enqueue_style( 'forum-buddypress-custom-style', FORUM_URI . '/css/buddypress-custom.css', false, FORUM_VERSION );


	// Forum scripts
}
