<?php
/**
 * Fired during plugin activation
 *
 * @link       https://club.wpeka.com
 * @since      1.0
 *
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/includes
 * @author     WPeka <support@wpeka.com>
 */
class Wplocalplus_Lite_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0
	 */
	public static function activate() {
		if ( is_plugin_active( 'wplocalplus/wplocalplus.php' ) ) {
			deactivate_plugins( 'wplocalplus/wplocalplus.php' );
		}
	}

}
