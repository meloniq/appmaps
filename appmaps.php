<?php
/*
	Plugin Name: AppMaps
	Plugin URI: http://blog.meloniq.net/donate/
	Description: Disables automatic updating geolocation in ClassiPress theme, and insert new box with map in admin to manually place marker.
	Author: MELONIQ.NET
	Version: 1.1
	Author URI: http://blog.meloniq.net
*/

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $appmaps_dbversion;
$appmaps_version = '1.1';
define('APPMAPS_VERSION', '1.1');
$appmaps_dbversion = '11';
// Init options & tables during activation & deregister init option
register_activation_hook( plugin_basename(__FILE__), 'appmaps_activate' );

/* PLUGIN and WP-CONTENT directory constants if not already defined */
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

if ( ! defined( 'APPMAPS_PLUGIN_BASENAME' ) )
	define( 'APPMAPS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'APPMAPS_PLUGIN_NAME' ) )
	define( 'APPMAPS_PLUGIN_NAME', trim( dirname( APPMAPS_PLUGIN_BASENAME ), '/' ) );
if ( ! defined( 'APPMAPS_PLUGIN_DIR' ) )
	define( 'APPMAPS_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . APPMAPS_PLUGIN_NAME );
if ( ! defined( 'APPMAPS_PLUGIN_URL' ) )
	define( 'APPMAPS_PLUGIN_URL', WP_PLUGIN_URL . '/' . APPMAPS_PLUGIN_NAME );
	
	
/**
 * Load Text-Domain
 */
load_plugin_textdomain( 'appmaps', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Load functions
 */
include_once (dirname (__FILE__) . '/appmaps-functions.php');

/**
 * Initialize admin menu
 */
if ( is_admin() ) {	
	add_action('admin_menu', 'appmaps_add_menu_links');
} else {
	// Add a author to the header
	//add_action('wp_head', create_function('', 'echo "\n<meta name=\'AppMaps\' content=\'http://blog.meloniq.net\' />\n";') );
}

/**
 * Load scripts
 */
function appmaps_load_scripts() {
  wp_enqueue_script('jquery');
}		
add_action('wp_print_scripts', 'appmaps_load_scripts');

function appmaps_load_admin_scripts() {
  wp_enqueue_script('jquery-ui-tabs'); 
}
add_action('admin_enqueue_scripts', 'appmaps_load_admin_scripts');			

/**
 * Load styles
 */
function appmaps_load_styles() {
	wp_register_style('appmaps_style', plugins_url(APPMAPS_PLUGIN_NAME.'/style.css'));
	wp_enqueue_style('appmaps_style');	
}		
//add_action('wp_print_styles', 'appmaps_load_styles');

function appmaps_load_admin_styles() {
	wp_register_style('appmaps_admin_style', plugins_url(APPMAPS_PLUGIN_NAME.'/admin-style.css'));
	wp_enqueue_style('appmaps_admin_style');	
}
add_action('admin_enqueue_scripts', 'appmaps_load_admin_styles');			


/**
 * Populate administration menu of the plugin
 */
function appmaps_add_menu_links() {
	if (function_exists('add_options_page')) {
		add_options_page(__('AppMaps','appmaps'), __('AppMaps','appmaps'), 'administrator', 'appmaps', 'appmaps_menu_settings' );
	}
}
		
/**
 * Create settings page in admin
 */
function appmaps_menu_settings() {
	include_once (dirname (__FILE__) . '/appmaps-admin.php');
}

/**
 * Action on plugin activate
 */
function appmaps_activate() {
	global $wpdb, $appmaps_dbversion;
	appmaps_install_options($appmaps_dbversion);
}

/**
 * Install default options
 */
function appmaps_install_options($appmaps_dbversion) {
	global $wpdb;
	
	$appmaps_saved_dbversion = get_option('appmaps_db_version');
	
  //If fresh installation, save defaults
	if(!$appmaps_saved_dbversion){

  	update_option('appmaps_db_version', $appmaps_dbversion);
  	update_option('appmaps_active', 'yes');
  	update_option('appmaps_lat', '49.99782515937576');
  	update_option('appmaps_lng', '19.436830520629883');
  	update_option('appmaps_gmaps_lang', 'en');
  	update_option('appmaps_gmaps_region', 'PL');

	} else if($appmaps_saved_dbversion < 11) {

  	update_option('appmaps_gmaps_lang', 'en');
  	update_option('appmaps_gmaps_region', 'PL');
  	delete_option('appmaps_gmaps_loc');
  	delete_option('appmaps_api_key');

	}

  //Update DB version
  update_option('appmaps_db_version', $appmaps_dbversion);
}		
?>