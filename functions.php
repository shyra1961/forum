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

/**
 * Set default options
 */
add_filter( 'generate_option_defaults', 'set_forum_defaults', 10, 1 );
function set_forum_defaults( $options ) {
	$options['footer_widget_setting'] = '0';
	return apply_filters( 'forum_defaults', $options );
}

/**
 * Выводит код div-а с кнопками uLogin
 */
add_action( 'bp_before_account_details_fields', 'forum_ulogin_panel', 10, 0 );
function forum_ulogin_panel() {
	echo get_ulogin_panel( 0, true, true ); 
}

/**
 * Remove logo WP in adminbar
 */
function remove_admin_bar_links() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    }
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );

/**
 * Добавляем поле "сайт" в списке пользователей в админпанеле
 */
add_filter( 'manage_users_columns', 'show_user_url_column' );
add_action( 'manage_users_custom_column',  'show_user_url_column_content', 10, 3 );
function show_user_url_column( $columns ) {
    $columns['user_url'] = __( 'Сайт', 'forum' );
    return $columns;
}
function show_user_url_column_content( $value, $column_name, $user_id ) {
    if ( 'user_url' == $column_name )
        return get_user_meta( $user_id, 'user_url', true );
    return $value;
}

/**
 * Add favicon
 */

function forum_favicon() {
	echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.get_bloginfo('stylesheet_directory').'/assets/img/favicon.png" />';
}
add_action('wp_head', 'forum_favicon');


