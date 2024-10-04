<?php

/*
Plugin Name: Not A Blog
Description: A WordPress plugin to use when you're using WordPress solely as a CMS to power a custom plugin.
Version: 1.0
Author: Christopher Finke
*/

add_action( 'wp_loaded', array( 'NOTABLOG', 'redirects' ), 1 );
add_action( 'admin_init', array( 'NOTABLOG', 'admin_menu' ), 99 );
add_action( 'admin_bar_menu', array( 'NOTABLOG', 'admin_bar_menu' ), 99 );
add_filter( 'all_plugins', array( 'NOTABLOG', 'modify_plugin_description' ) );

class NOTABLOG {
	public static $default_destination_page = 'wp-admin/';

	/**
	 * Redirect frontend pages and possibly the default wp-admin page to the plugin's chosen default page.
	 */
	public static function redirects() {
		global $pagenow;

		if ( is_login() ) {
			return;
		}

		if (
			! is_admin() &&
			! ( defined('REST_REQUEST') && REST_REQUEST )
		) {
			wp_redirect( site_url( 'wp-admin/' ) );
			exit();
		}

		// If is_admin() and the user is on the dashboard, redirect to the main plugin page.
		// ...but only if a plugin has defined a destination page; otherwise, we'll be caught in a loop.
		if ( is_admin() && is_user_logged_in() && 'index.php' === $pagenow ) {
			if ( apply_filters( 'not_a_blog_default_page', NOTABLOG::$default_destination_page ) !== NOTABLOG::$default_destination_page ) {
				wp_redirect( site_url( apply_filters( 'not_a_blog_default_page', NOTABLOG::$default_destination_page ) ) );
				exit();
			}
		}
	}

	public static function admin_menu() {
		remove_menu_page( 'index.php' );
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'upload.php' );
		remove_menu_page( 'edit.php?post_type=page' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'themes.php' );

		remove_submenu_page( 'tools.php', 'import.php' );
		remove_submenu_page( 'tools.php', 'export.php' );
		remove_submenu_page( 'tools.php', 'export-personal-data.php' );
		remove_submenu_page( 'tools.php', 'erase-personal-data.php' );
		remove_submenu_page( 'tools.php', 'theme-editor.php' );

		remove_submenu_page( 'options-general.php', 'options-writing.php' );
		remove_submenu_page( 'options-general.php', 'options-reading.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
		remove_submenu_page( 'options-general.php', 'options-media.php' );
		remove_submenu_page( 'options-general.php', 'options-permalink.php' );
		remove_submenu_page( 'options-general.php', 'options-privacy.php' );
	}

	public static function admin_bar_menu( $admin_bar ) {
		$admin_bar->remove_node( 'comments' );

		$admin_bar->remove_node( 'new-post' );
		$admin_bar->remove_node( 'new-page' );
		$admin_bar->remove_node( 'new-media' );
	}

	public static function modify_plugin_description( $all_plugins ) {
		if ( isset( $all_plugins['not-a-blog/not-a-blog.php'] ) ) {
			$all_plugins['not-a-blog/not-a-blog.php']['Description'] .= '  ' .
				sprintf(
					__( 'The default landing page, as determined by the %1$s filter, is %2$s.', 'notablog' ),
					'<code>not_a_blog_default_page</code>',
					'<code>' . esc_html( apply_filters( 'not_a_blog_default_page', NOTABLOG::$default_destination_page ) ) . '</code>'
				);
		}

		return $all_plugins;
	}
}