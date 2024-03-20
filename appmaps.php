<?php
/*
	Plugin Name: AppMaps
	Plugin URI: https://blog.meloniq.net/donate/
	Description: Disables automatic updating geolocation in ClassiPress theme, and insert new box with map in admin to manually place marker.
	Author: MELONIQ.NET
	Version: 1.2
	Author URI: https://blog.meloniq.net
*/


/**
 * Avoid calling file directly
 */
if ( ! function_exists( 'add_action' ) )
	die( 'Whoops! You shouldn\'t be doing that.' );


/**
 * Plugin version and textdomain constants
 */
define( 'APPMAPS_VERSION', '1.1' );
define( 'APPMAPS_TD', 'appmaps' );


/**
 * Process actions on plugin activation
 */
register_activation_hook( plugin_basename( __FILE__ ), 'appmaps_activate' );


/**
 * Load Text-Domain
 */
load_plugin_textdomain( APPMAPS_TD, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/**
 * Load functions
 */
include_once( dirname( __FILE__ ) . '/appmaps-functions.php');


/**
 * Load frontend scripts
 */
function appmaps_load_scripts() {
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'appmaps_load_scripts' );


/**
 * Load backend scripts
 */
function appmaps_load_admin_scripts() {
	wp_enqueue_script( 'jquery-ui-tabs' );
}
add_action( 'admin_enqueue_scripts', 'appmaps_load_admin_scripts' );


/**
 * Load frontend styles
 */
function appmaps_load_styles() {
	wp_register_style( 'appmaps_style', plugins_url( 'style.css', __FILE__ ) );
	wp_enqueue_style( 'appmaps_style' );
}
//add_action( 'wp_enqueue_scripts', 'appmaps_load_styles' );


/**
 * Load backend styles
 */
function appmaps_load_admin_styles() {
	wp_register_style( 'appmaps_admin_style', plugins_url( 'admin-style.css', __FILE__ ) );
	wp_enqueue_style( 'appmaps_admin_style' );
}
add_action( 'admin_enqueue_scripts', 'appmaps_load_admin_styles' );


/**
 * Populate administration menu of the plugin
 */
function appmaps_add_menu_links() {

	add_options_page( __( 'AppMaps', APPMAPS_TD ), __( 'AppMaps', APPMAPS_TD ), 'administrator', 'appmaps', 'appmaps_menu_settings' );
}
add_action( 'admin_menu', 'appmaps_add_menu_links' );


/**
 * Create settings page in admin
 */
function appmaps_menu_settings() {
	include_once( dirname( __FILE__ ) . '/appmaps-admin.php' );
}


/**
 * Action on plugin activate
 */
function appmaps_activate() {

	appmaps_install_options();
}


/**
 * Install default options
 */
function appmaps_install_options() {

	$previous_version = get_option('appmaps_db_version');

	// fresh install
	if ( ! $previous_version ) {

		update_option( 'appmaps_active', 'yes' );
		update_option( 'appmaps_lat', '49.99782515937576' );
		update_option( 'appmaps_lng', '19.436830520629883' );
		update_option( 'appmaps_gmaps_lang', 'en' );
		update_option( 'appmaps_gmaps_region', 'PL' );

	}

	//Update DB version
	update_option( 'appmaps_db_version', APPMAPS_VERSION );
}

