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

	$( document ).ready(
		function(){
			$( ".wplocal_places_main_content_ratings" ).starRating(
				{
					totalStars: 5,
					strokeColor: "#894A00",
					strokeWidth: 10,
					starSize: 15,
					readOnly: true
				}
			);
			$( ".wplocal_reviews_main_content_ratings" ).starRating(
				{
					totalStars: 5,
					strokeColor: "#894A00",
					strokeWidth: 10,
					starSize: 15,
					readOnly: true
				}
			);
			$( ".wplocal_places_moredetail" ).click(
				function () {
					var url   = wplocalplus_lite_ajax.ajaxurl;
					var nonce = wplocalplus_lite_ajax.nonce;
					var id    = $( this ).attr( "data-value" );
					$.fancybox(
						{
							width:600,
							height:400,
							autosize:false,
							href:url + '?action=wplocalplus_lite_place_details&id=' + id + '&nonce=' + nonce,
							type:'ajax'
						}
					);
				}
			);
			$( ".wplocal_places_sort" ).change(
				function(){
					if (navigator && navigator.geolocation) {
						navigator.geolocation.getCurrentPosition( successCallback, errorCallback );
					} else {
						console.log( 'To use this feature, you should consider switching your application to a secure origin, such as HTTPS. See https://goo.gl/rStTGz for more details.' );
					}
				}
			);
		}
	);
	function errorCallback(error) {
		if (error.PERMISSION_DENIED) {
			var choice = $( '.wplocal_places_sort' ).children( "option:selected" ).val();
			if (choice == 'latlon') {
				alert( 'Geolocation is not enabled. Please enable to use this feature.' );
			} else {
				$( "#wplocal_places_form" ).submit();
			}
		}
	}

	function successCallback(position) {
		var lat = position.coords.latitude,
			lon = position.coords.longitude;
		$( ".wplocal_places_location" ).val( lat + ',' + lon );
		$( "#wplocal_places_form" ).submit();
	}

})( jQuery );
