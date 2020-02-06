<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://club.wpeka.com
 * @since      1.0
 *
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/includes
 * @author     WPeka <support@wpeka.com>
 */
class Wplocalplus_Lite_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wplocalplus-lite',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
