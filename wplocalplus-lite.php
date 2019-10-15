<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://club.wpeka.com
 * @since             1.0
 * @package           Wplocalplus_Lite
 *
 * @wordpress-plugin
 * Plugin Name:       WP Local Plus
 * Plugin URI:        https://club.wpeka.com/
 * Description:       WordPress Business Directory Plugin
 * Version:           1.0
 * Author:            WPeka
 * Author URI:        https://club.wpeka.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wplocalplus-lite
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// ACF custom field.
require_once plugin_dir_path( __FILE__ ) . 'includes/libraries/acf/acf.php';

add_filter( 'acf/settings/url', 'wplocalplus_lite_acf_settings_url' );
/**
 * Return ACF settings url.
 *
 * @since 1.0
 * @param string $url Settings url.
 * @return string
 */
function wplocalplus_lite_acf_settings_url( $url ) {
	return plugin_dir_url( __FILE__ ) . 'includes/libraries/acf/';
}

add_filter( 'acf/settings/show_admin', 'wplocalplus_lite_acf_settings_show_admin' );
/**
 * Hide ACF admin menu item.
 *
 * @since 1.0
 * @param bool $show_admin Show admin.
 * @return bool
 */
function wplocalplus_lite_acf_settings_show_admin( $show_admin ) {
	return false;
}

/**
 * Currently plugin version.
 * Start at version 1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if ( ! defined( 'WPLOCALPLUS_LITE_VERSION' ) ) {
	define( 'WPLOCALPLUS_LITE_VERSION', '1.0' );
}
if ( ! defined( 'WPLOCALPLUS_PLACE_POST_TYPE' ) ) {
	define( 'WPLOCALPLUS_PLACE_POST_TYPE', 'wplocal_places' );
}
if ( ! defined( 'WPLOCALPLUS_REVIEW_POST_TYPE' ) ) {
	define( 'WPLOCALPLUS_REVIEW_POST_TYPE', 'wplocal_reviews' );
}
if ( ! defined( 'WPLOCALPLUS_LITE_ASSETS_URL' ) ) {
	define( 'WPLOCALPLUS_LITE_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets/' );
}
if ( ! defined( 'WPLOCALPLUS_LITE_PLUGIN_PATH' ) ) {
	define( 'WPLOCALPLUS_LITE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'WPLOCALPLUS_LITE_PLUGIN_URL' ) ) {
	define( 'WPLOCALPLUS_LITE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'WPLOCALPLUS_LITE_SETTINGS_FIELD' ) ) {
	define( 'WPLOCALPLUS_LITE_SETTINGS_FIELD', 'wplocalplus_1.0' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wplocalplus-lite-activator.php
 */
function activate_wplocalplus_lite() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wplocalplus-lite-activator.php';
	Wplocalplus_Lite_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wplocalplus-lite-deactivator.php
 */
function deactivate_wplocalplus_lite() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wplocalplus-lite-deactivator.php';
	Wplocalplus_Lite_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wplocalplus_lite' );
register_deactivation_hook( __FILE__, 'deactivate_wplocalplus_lite' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wplocalplus-lite.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0
 */
function run_wplocalplus_lite() {

	$plugin = new Wplocalplus_Lite();
	$plugin->run();

}
run_wplocalplus_lite();
