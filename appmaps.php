<?php
/**
 * Plugin Name:       AppMaps
 * Plugin URI:        https://blog.meloniq.net/donate/
 * Description:       Disables automatic updating geolocation in ClassiPress theme, and insert new box with map in admin to manually place marker.
 *
 * Requires at least: 4.9
 * Requires PHP:      7.4
 * Version:           1.3
 *
 * Author:            MELONIQ.NET
 * Author URI:        https://blog.meloniq.net
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain:       appmaps
 */


// If this file is accessed directly, then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'APPMAPS_TD', 'appmaps' );
define( 'APPMAPS_FILE', __FILE__ );


/**
 * Setup Plugin data.
 *
 * @return void
 */
function appmaps_setup() {
	global $mnet_appmaps;

	require_once( dirname( __FILE__ ) . '/src/class-install.php' );

	$mnet_appmaps['install'] = new AppMaps_Install();

	// admin pages
	if ( is_admin() ) {
		require_once( dirname( __FILE__ ) . '/src/class-settings.php' );

		$mnet_appmaps['settings'] = new AppMaps_Settings();
	}
}
add_action( 'after_setup_theme', 'appmaps_setup' );

/**
 * Load Text-Domain.
 *
 * @return void
 */
function appmaps_load_textdomain() {
	load_plugin_textdomain( APPMAPS_TD, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'appmaps_load_textdomain' );



/**
 * Load functions
 */
include_once( dirname( __FILE__ ) . '/appmaps-functions.php');

