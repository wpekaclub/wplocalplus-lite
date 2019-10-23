<?php
	/**
	 * Provide a admin area view for the plugin
	 *
	 * This file is used to markup the admin-facing aspects of the plugin.
	 *
	 * @link       https://club.wpeka.com
	 * @since      1.0
	 *
	 * @package    Wplocalplus_Lite
	 * @subpackage Wplocalplus_Lite/assets/admin/settings
	 */

	// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
	<!-- This file should primarily consist of HTML with a little bit of PHP. -->
	<div class="wrap">
			<h2 class="wpeka-panel-heading">WP Local Plus Settings</h2>
		<?php if ( $reset ) : ?>
			<div id="message" class="notice is-dismissible"><p>Settings reset.</p></div>
		<?php elseif ( $saved ) : ?>
			<div id="message" class="notice is-dismissible"><p>Settings saved.</p></div>
		<?php endif; ?>
			<div class="postbox">
				<h3 class="hndle"><span>Google Maps API Keys</span></h3>
				<form action="" method="post">
					<?php
					if ( function_exists( 'wp_nonce_field' ) ) {
						wp_nonce_field( 'wplocalplus-lite-settings-' . WPLOCALPLUS_LITE_SETTINGS_FIELD );
					}
					?>
					<div class="inside" style="width: 800px;overflow: hidden;"><label><strong>Show Map</strong></label>
						<input
						<?php
						if ( 1 === $the_options['show_map'] ) {
							echo 'checked=""';
						}
						?>
						name="field_show_map" type="checkbox"/></div>
					<div class="inside" style="width: 800px;overflow: hidden;"><label style="display:block;"><strong>Google Maps API</strong></label> <input style="width:50%;padding:5px 10px;" type="text"
																								name="field_google_maps_api_key"
																								id="id2"
																								value="<?php echo esc_attr( $the_options['google_maps_api_key'] ); ?>"/>
						<div class="toshow1"><b>Instructions are as follows -</b><br/> 1. Go to https://console.developers.google.com/<br/>2. Go to top menu "Select a project" -> Create a new project -> write your project name- "Project Name" -> Click "Create"<br/>3. Click "Enable APIS AND SERVICES" from the top. Search for Maps Static API, Maps JavaScript API, Geocoding API & Geolocation API and enable all of them.<br/>4. Now, go to the "Credentials" tab and click on "Create Credentials-> API Key".<br/>5. Click on "RESTRICT KEY". Under it go to API restrictions tab and choose Places API, Maps JavaScript API, Geocoding API & Geolocation API.<br/>6. Then go to the Application restrictions tab and click on HTTP referrers (web sites). Add following domain referrers values: https://*.yourwebsite.com/*, https://yourwebsite.com/*<br/>7. Save it.<br/>8. Copy the key and paste it in WP Local Plus -> Settings under Google Maps API.<br/>
						</div>
						<p class="wplocalplus_citygrid_publisher_key_note">Note: Please don't forget to enter your own
							API keys above.
							</br> <a target="_blank" href="https://console.developers.google.com/">Click Here to create
								your own Google Maps API Key.</a><br/>Default Key: <br>ABQIAAAAXuX847HLKfJC60JtneDOUhQ8oGF9gkOSJpYWLmRvGTmYZugFaxRX7q0DDCWBSdfC1tIHIXIZqTPM-A
						</p>
					</div>
					<div class="inside" style="width: 800px;overflow: hidden;">
						<p><input type="submit" name="customization_update" value="Update" class="bluebutton"/>
							<input style="margin-left: 20px;" type="submit" name="customization_restore"
									value="Restore Default Value" class="bluebutton"/></p>
					</div>
				</form>
				<div class="inside" style="width: 800px;overflow: hidden;"> Please raise ticket on&nbsp;<a target="_blank" href="mailto:support@wpeka.com">support@wpeka.com</a>&nbsp;in case of any help.
				</div>
			</div>
	</div>
