/**
 * Frontend JavaScript.
 *
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/assets
 * @author     wpeka <https://club.wpeka.com>
 */

 (function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 jQuery(document).ready(function(){
		jQuery("#gmap").gmap3({
			action:"init",
			options:{
				center: [obj.center_latitude, obj.center_longitude],
				zoom: parseInt(obj.zoom)
			}
		},
		{
			action: "addMarkers",
			markers: obj.market_str,
			marker: {
				options: {
					draggable: false
				},
				events: {
					mouseover: function(marker, event, data) {
						var map = jQuery(this).gmap3("get"),
						infowindow = jQuery(this).gmap3({
							action:"get",
							name:"infowindow"
						});
						if (infowindow){
							infowindow.open(map, marker);
							infowindow.setContent(data);
						  } else {
							jQuery(this).gmap3({
								action:"addinfowindow", 
								anchor:marker, 
								options:{
									content: data
								}
							 });
						  }
					},
					mouseout: function() {
						var infowindow = jQuery(this).gmap3({
								action:"get", 
								name:"infowindow"
							});
						if (infowindow){
							infowindow.close();
						}
					}
				}
			}
		})
	})
})( jQuery );
