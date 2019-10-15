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
			<h2 class="wpeka-panel-heading">WP LocalPlus Settings</h2>
		<?php if ( $reset ) : ?>
			<div id="message" class="notice is-dismissible"><p>Settings reset.</p></div>
		<?php elseif ( $saved ) : ?>
			<div id="message" class="notice is-dismissible"><p>Settings saved.</p></div>
		<?php endif; ?>
			<div class="postbox">
				<h3 class="hndle"><span>How it works </span></h3>
				<div class="inside" style="width: 800px;overflow: hidden;">
					<h4><a target="_blank" href="https://www.wplocalplus.com/us-edition/">Click Here For WP LocalPlus
							Setup Instructions</a></h4>
				</div>
			</div>
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
						<div class="toshow1"><b>Instructions are as follows -</b><br/> 1. Visit the APIs console at
							https://code.google.com/apis/console and log in with your Google Account.<br/>2. Create
							new project -> Write your Project name- Project Name -> Click Create<br/>3. Click Enable
							and Manage -> Search and Click on Google place API Web Services
							<br/>4. Click on Enable API-> Click on Go to Credentials<br/>5. Click on New Credentials
							-> API key -> Browser Key<br/>6. Get the API key in pop up window <br/>
						</div>
						<p class="wplocalplus_citygrid_publisher_key_note">Note: Please don't forget to enter your own
							API keys above.
							</br> <a target="_blank" href="https://code.google.com/apis/console/">Click Here to create
								your own Google Maps API Key.</a><br/>Default Key: <br>ABQIAAAAXuX847HLKfJC60JtneDOUhQ8oGF9gkOSJpYWLmRvGTmYZugFaxRX7q0DDCWBSdfC1tIHIXIZqTPM-A
						</p>
					</div>
					<div class="inside" style="width: 800px;overflow: hidden;">
						<label style="display:block;"><strong>Google Maps URL signing secret</strong></label>
						<input style="width:50%;padding:5px 10px;" class="api_box" type="text" name="field_google_maps_sign_secret" id="google_maps_sign_secret"
								value="<?php echo esc_attr( $the_options['google_maps_sign_secret'] ); ?>"/>
						<p class="wplocalplus_citygrid_publisher_key_note">Note: Please visit the link<br> <a
									target="_blank"
									href="https://developers.google.com/maps/documentation/maps-static/get-api-key#get-secret">Click
								Here to create your own Google Maps URL signing secret.</a></p>
					</div>
					<h3 class="hndle"><span>Customization </span></h3>
					<div class="inside" style="width: 800px;overflow: hidden;">
						<p><strong>Title </strong></p>
						<p>
							<label class="labelcustom">Color</label>
							<input type="text" class="color" name="field_title_color"
									value="<?php echo esc_attr( $the_options['title_color'] ); ?>"/>
							<label class="labelcustom">Size <input class="side2" type="text"
																	name="field_title_size"
																	value="<?php echo esc_attr( $the_options['title_size'] ); ?>"/>
								<label class="labelcustom">Format :</label> <input
										type="radio"
										<?php
										if ( 'normal' === $the_options['title_format'] ) {
											echo 'checked=""'; }
										?>
										value="normal" name="field_title_format"/> Normal
								<input value="italic"
								<?php
								if ( 'italic' === $the_options['title_format'] ) {
									echo 'checked=""'; }
								?>
								name="field_title_format" type="radio"/> Italic
						</p>
						<p><strong>Business Title </strong></p>
						<p>
							<label class="labelcustom">Color</label>
							<input type="text" class="color" name="field_biztitle_color"
								value="<?php echo esc_attr( $the_options['biztitle_color'] ); ?>"/>
							<label class="labelcustom">Size</label> <input class="side2" type="text"
																			name="field_biztitle_size"
																			value="<?php echo esc_attr( $the_options['biztitle_size'] ); ?>"/>
							<label class="labelcustom">Format :</label> <input
									type="radio"
									<?php
									if ( 'normal' === $the_options['biztitle_format'] ) {
										echo 'checked=""'; }
									?>
									value="normal" name="field_biztitle_format"/> Normal
							<input value="italic"
							<?php
							if ( 'italic' === $the_options['biztitle_format'] ) {
								echo 'checked=""'; }
							?>
							name="field_biztitle_format" type="radio"/> Italic
						</p>
						<p><strong>Paragraph</strong></p>
						<p>
							<label class="labelcustom">Color<label>
									<input type="text" class="color" name="field_prh_color"
									value="<?php echo esc_attr( $the_options['prh_color'] ); ?>"/>
									<label class="labelcustom">Size</label> <input class="side2" type="text"
																				name="field_prh_size"
																				value="<?php echo esc_attr( $the_options['prh_size'] ); ?>"/>
									<label class="labelcustom">Format :</label>
									<input
									<?php
									if ( 'normal' === $the_options['prh_format'] ) {
										echo 'checked=""'; }
									?>
									value="normal" name="field_prh_format"
									type="radio"/> Normal
									<input
									<?php
									if ( 'italic' === $the_options['prh_format'] ) {
										echo 'checked=""'; }
									?>
									value="italic" name="field_prh_format"
									type="radio"/> Italic
						</p>
						<p><strong>Small Text</strong></p>
						<p>
							<label class="labelcustom">Color</label>
							<input type="text" class="color" name="field_short_text_color"
									value="<?php echo esc_attr( $the_options['short_text_color'] ); ?>"/>
							<label class="labelcustom">Size</label> <input class="side2" type="text"
																			name="field_short_text_size"
																			value="<?php echo esc_attr( $the_options['short_text_size'] ); ?>"/>
							<label class="labelcustom">Format :</label>
							<input
							<?php
							if ( 'normal' === $the_options['short_text_format'] ) {
								echo 'checked=""'; }
							?>
							value="normal" name="field_short_text_format" type="radio"/>
							Normal
							<input
							<?php
							if ( 'italic' === $the_options['short_text_format'] ) {
								echo 'checked=""'; }
							?>
							value="italic" name="field_short_text_format" type="radio"/>
							Italic
						</p>
						<p><strong>Links </strong></p>
						<p>
							<label class="labelcustom">Color</label>
							<input type="text" class="color" name="field_link_color"
									value="<?php echo esc_attr( $the_options['link_color'] ); ?>"/>
							<label class="labelcustom">Size</label> <input class="side2" type="text"
																		name="field_link_size"
																		value="<?php echo esc_attr( $the_options['link_size'] ); ?>"/>
							<label class="labelcustom">Format :</label>
							<input
							<?php
							if ( 'normal' === $the_options['link_format'] ) {
								echo 'checked=""'; }
							?>
							value="normal" name="field_link_format" type="radio"/>
							Normal
							<input
							<?php
							if ( 'italic' === $the_options['link_format'] ) {
								echo 'checked=""'; }
							?>
								value="italic" name="field_link_format" type="radio"/>
							Italic
						</p>
						<p><strong>Separator </strong></p>
						<p>
							<label class="labelcustom">Color</label>
							<input type="text" class="color" name="field_separator_color"
									value="<?php echo esc_attr( $the_options['separator_color'] ); ?>"/>
						</p>
						<br/>
						<p><input type="submit" name="customization_update" value="Update" class="bluebutton"/>
							<input style="margin-left: 20px;" type="submit" name="customization_restore"
									value="Restore Default Value" class="bluebutton"/></p>
					</div>
				</form>
				<br/></br>
				<div class="inside" style="width: 800px;overflow: hidden;"> Please raise ticket on&nbsp;<a target="_blank" href="mailto:support@wpeka.com">support@wpeka.com</a>&nbsp;in case of any help.
				</div>
			</div>
	</div>
