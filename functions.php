<?php
/**
 * Generate functions and definitions
 *
 * @package Forum
 */
	
define( 'FORUM_VERSION', '1.1.0');
define( 'FORUM_URI', get_stylesheet_directory_uri() );
define( 'FORUM_DIR', get_template_directory() );

/**
 * Подключаем стили после того, как загрузится родительская тема,
 * иначе подгрузка стилей/скриптов работающих в зависимости от 
 * родительской темы не работает.
 */
add_action ('init', 'child_theme_enqueue_scripts' );
function child_theme_enqueue_scripts () {

	/**
	 * Enqueue scripts and styles
	 */
	add_action( 'wp_enqueue_scripts', 'forum_scripts' );
	function forum_scripts() {
		// Forum stylesheets
		wp_enqueue_style( 'forum-buddypress-custom-style', FORUM_URI . '/assets/css/buddypress-custom.css', 'generate-child-css', FORUM_VERSION );
		wp_enqueue_style( 'forum-style', FORUM_URI . '/assets/css/style.css', 'generate-child-css', FORUM_VERSION );


		// Forum scripts
		/*
		wp_deregister_script( 'jquery' );
    	wp_register_script( 'jquery', 'http://yastatic.net/jquery/1.11.1/jquery.js', '', '', true);
    	wp_enqueue_script( 'jquery' );		
		*/
		wp_enqueue_script( 'jquery.scrollTo', FORUM_URI . '/assets/vendor/jquery.scrollTo/jquery.scrollTo.js', array( 'jquery' ), '', true);
    	wp_enqueue_script( 'jquery.localScroll', FORUM_URI . '/assets/vendor/jquery.localScroll/jquery.localScroll.js', array( 'jquery', 'jquery.scrollTo' ), '', true);
    	wp_enqueue_script( 'forum_scripts', FORUM_URI . '/assets/js/scripts.js', array( 'jquery' ), '', true);
    
	}
}


/**
 * Set default options
 */
add_filter( 'generate_option_defaults', 'set_forum_defaults', 10, 1 );
function set_forum_defaults( $options ) {
	$options['footer_widget_setting'] = '0';
	$options['nav_position_setting'] = null;
	$options['layout_setting'] = 'no-sidebar';
	$options['blog_layout_setting'] = 'no-sidebar';
	$options['single_layout_setting'] = 'no-sidebar';

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
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );
function remove_admin_bar_links() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('user-actions');

    if ( !is_admin() ) {
    	$wp_admin_bar->remove_menu('site-name');
	}
}

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
        return get_user_by( 'id', $user_id )->user_url;
    return $value;
}

/**
 * Add favicon
 */

function forum_favicon() {
	echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.get_bloginfo('stylesheet_directory').'/assets/img/favicon.png" />';
}
add_action('wp_head', 'forum_favicon');

/**
 * Добавляем class not-login
 */
add_filter('body_class','css_class_logged_no');
function css_class_logged_no($classes) {
	if ( !is_user_logged_in() ) { 
		$classes[] = 'logged-no'; 
	}

	return $classes;
}

/**
 * Кнопка "Создать новую тему"
 */
add_action( 'forum_button_create_topic', 'forum_button_create_topic' );
function forum_button_create_topic() {
	if ( is_user_logged_in() ) {
		echo forum_get_button_create_topic();
	}
}
function forum_get_button_create_topic() {
	$btn = "<a class='btn btn-create-topic' href='#new-post' title=''>".sprintf( __( 'Create New Topic in &ldquo;%s&rdquo;', 'bbpress' ), bbp_get_forum_title() )."</a>";
	return $btn;
}

/**
 * Убрает лого WP со страницы входа
 */
function forum_remove_login_logo(){
	echo '<style type="text/css">
	h1 a { display: none !important; }
	</style>';
}
add_action('login_head', 'forum_remove_login_logo');

/**
 * Добавляет кнопку "выйти" в админбар
 */
add_action( 'admin_bar_menu', 'forum_toolbar_link', 999 );
function forum_toolbar_link( $wp_admin_bar ) {
	if ( is_user_logged_in() ) {
		$args = array(
			'id'    => 'forum_logout',
			'title' => 'Выйти',
			'href'  => wp_logout_url( get_permalink() ),
			'parent' => 'my-account-buddypress',
			'meta'  => array( 'class' => 'my-toolbar-page' )
		);
		$wp_admin_bar->add_node( $args );
	}
}

/**
 * Ссылка профиль в админке изменяется 
 * на ссылку на профиль в  bbpress
 */
add_filter( 'edit_profile_url', 'forum_edit_profile_url', 10, 2 );
function forum_edit_profile_url( $url, $user_id ) {
	return bbp_get_user_profile_url( $user_id );
}
