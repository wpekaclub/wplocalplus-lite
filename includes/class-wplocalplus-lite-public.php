<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://club.wpeka.com
 * @since      1.0
 *
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/public
 * @author     WPeka <support@wpeka.com>
 */
class Wplocalplus_Lite_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_shortcode( 'wplocalplus', array( $this, 'wplocalplus_lite_shortcode' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wplocalplus_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wplocalplus_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( $this->plugin_name, WPLOCALPLUS_LITE_ASSETS_URL . 'css/wplocalplus-lite-public.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name . '-fancybox-css', WPLOCALPLUS_LITE_ASSETS_URL . 'libraries/fancybox/fancybox/jquery.fancybox.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name . '-star-rating-svg-css', WPLOCALPLUS_LITE_ASSETS_URL . 'libraries/star-rating-svg/css/star-rating-svg.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wplocalplus_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wplocalplus_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, WPLOCALPLUS_LITE_ASSETS_URL . 'js/wplocalplus-lite-public.js', array( 'jquery' ), $this->version, true );
		wp_register_script( $this->plugin_name . '-gmap3', WPLOCALPLUS_LITE_ASSETS_URL . 'libraries/gmap3/gmap3.js', array( 'jquery' ), $this->version, true );
		wp_register_script( $this->plugin_name . '-fancybox', WPLOCALPLUS_LITE_ASSETS_URL . 'libraries/fancybox/fancybox/jquery.fancybox.pack.js', array( 'jquery' ), $this->version, true );
		wp_register_script( $this->plugin_name . '-star-rating-svg', WPLOCALPLUS_LITE_ASSETS_URL . 'libraries/star-rating-svg/jquery.star-rating-svg.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Shortcode - [wplocalplus].
	 *
	 * @since 1.0
	 * @param string $atts Shortcode attributes.
	 * @return string|void
	 */
	public function wplocalplus_lite_shortcode( $atts ) {
		if ( is_admin() ) {
			return;
		}
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );
		wp_localize_script(
			$this->plugin_name,
			'wplocalplus_lite_ajax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'place_details' ),
			)
		);
		wp_enqueue_style( $this->plugin_name . '-fancybox-css' );
		wp_enqueue_script( $this->plugin_name . '-fancybox' );
		wp_enqueue_style( $this->plugin_name . '-star-rating-svg-css' );
		wp_enqueue_script( $this->plugin_name . '-star-rating-svg' );
		global $wp;
		$req     = $wp->request;
		$req_arr = explode( '/', $req );
		if ( $req_arr ) {
			$paged = array_pop( $req_arr );
		} else {
			$paged = 1;
		}
		$atts       = shortcode_atts(
			array(
				'location' => 'cambridgema',
				'type'     => 'hotels',
				'list'     => 'wplocal_places',
				'limit'    => -1,
			),
			$atts
		);
		$location   = $atts['location'];
		$place_type = explode( ',', $atts['type'] );
		$limit      = $atts['limit'];
		$list       = $atts['list'];
		$meta_key   = '_wplocal_places_featured';
		if ( 'wplocal_reviews' === $list ) {
			$meta_key = '_wplocal_reviews_featured';
		}
		$the_options      = Wplocalplus_Lite::wplocalplus_lite_get_settings();
		$args             = array(
			'post_type'      => $list,
			'numberposts'    => -1,
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'paged'          => $paged,
			'tax_query'      => array( // phpcs:ignore slow query
				'relation' => 'AND',
				array(
					'taxonomy' => 'wplocal_location',
					'field'    => 'slug',
					'terms'    => $location,
				),
				array(
					'taxonomy' => 'wplocal_place_type',
					'field'    => 'slug',
					'terms'    => $place_type,
				),
			),
			'meta_key'       => $meta_key, // phpcs:ignore slow query
			'orderby'        => 'meta_value',
			'order'          => 'DESC',
		);
		$q                = new WP_Query( $args );
		$the_options['q'] = $q;
		if ( 'wplocal_places' === $list ) {
			$content = $this->wplocalplus_lite_get_template( 'places.php', $the_options );
		} elseif ( 'wplocal_reviews' === $list ) {
			$content = $this->wplocalplus_lite_get_template( 'reviews.php', $the_options );
		} else {
			$content = '';
		}
		$big      = 999999;
		$content .= paginate_links(
			array(
				'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'  => '?paged=%#%',
				'current' => max( 1, $paged ),
				'total'   => $q->max_num_pages, // $q is your custom query.
			)
		);
		wp_reset_postdata();
		return $content;
	}

	/**
	 * Return single place details including reviews.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_place_details() {
		if ( isset( $_GET['nonce'] ) && ! empty( $_GET['nonce'] ) ) {
			$nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'place_details' ) ) {
				return;
			}
		}
		$the_options          = Wplocalplus_Lite::wplocalplus_lite_get_settings();
		$data                 = array();
		$place_id             = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
		$data['name']         = get_the_title( $place_id );
		$data['address']      = get_field( 'address', $place_id );
		$data['phone_number'] = get_field( 'phone_number', $place_id );
		$data['latitude']     = get_field( 'latitude', $place_id );
		$data['longitude']    = get_field( 'longitude', $place_id );
		$data['profile_url']  = get_field( 'urls_profile', $place_id );
		$data['website_url']  = get_field( 'urls_website', $place_id );
		$data['ratings']      = get_field( 'ratings', $place_id );
		$attachment_id        = get_field( 'image', $place_id );
		$data['image_url']    = wp_get_attachment_url( $attachment_id );
		$args                 = array(
			'post_type'   => 'wplocal_reviews',
			'post_parent' => $place_id,
			'post_status' => 'publish',
			'meta_key'    => '_wplocal_reviews_featured', // phpcs:ignore slow query
			'orderby'     => 'meta_value',
			'numberposts' => 5,
		);
		$reviews              = get_posts( $args );
		$reviews_data         = array();
		if ( isset( $reviews ) && ! empty( $reviews ) ) {
			foreach ( $reviews as $review ) {
				$temp_review                     = array();
				$review_id                       = $review->ID;
				$custom                          = get_post_custom( $review_id );
				$temp_review['author_image_url'] = isset( $custom['_wplocal_reviews_author_image_url'][0] ) ? $custom['_wplocal_reviews_author_image_url'][0] : '';
				$temp_review['review_title']     = get_the_title( $review );
				$temp_review['author']           = get_field( 'review_author', $review_id );
				$temp_review['author_url']       = get_field( 'author_url', $review_id );
				$reviews_data[]                  = $temp_review;
			}
		}
		$data['reviews'] = $reviews_data;
		$content         = $this->wplocalplus_lite_get_template( 'single-place.php', array_merge( $the_options, $data ) );
		die();
	}

	/**
	 * Script required for Google Map and ratings in single place details popup.
	 *
	 * @since 1.0
	 * @param int $latitude Latitude.
	 * @param int $longitude Longitude.
	 */
	public function wplocalplus_lite_after_single_place_content( $latitude, $longitude ) {
		echo '<script type="text/javascript">';
		echo 'jQuery(".wplocal_place_main_content_details_ratings").starRating({
            totalStars: 5,
            strokeColor: "#894A00",
            strokeWidth: 10,
            starSize: 15,
            readOnly: true
        });';
		echo 'jQuery("#gmap3").gmap3(
              { action:"init",
                options:{
                  center:[' . esc_attr( $latitude ) . ',' . esc_attr( $longitude ) . '],
                  zoom: 14
                }
              },
              { action: "addMarker",
                latLng:[' . esc_attr( $latitude ) . ', ' . esc_attr( $longitude ) . ']
              }
            );';
		echo '</script>';
	}

	/**
	 * Search for the template and include the file.
	 *
	 * @since 1.0
	 * @param string $template_name Template to load.
	 * @param array  $args Arguments passed to the template file.
	 * @param string $tempate_path Path to templates.
	 * @param string $default_path Default path to templates.
	 */
	public function wplocalplus_lite_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
		if ( is_array( $args ) && isset( $args ) ) :
			extract( $args ); // @codingStandardsIgnoreLine
		endif;
		$template_file = $this->wplocalplus_lite_locate_template( $template_name, $tempate_path, $default_path );
		if ( ! file_exists( $template_file ) ) :
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_attr( $template_file ) ), '1.0' );
			return;
		endif;
		include $template_file;
	}

	/**
	 * Locate template.
	 *
	 * Locate the called template.
	 * Search order:
	 * 1. /themes/theme/wplocalplus-lite/$template_name
	 * 2. /themes/theme/$template_name
	 * 3. /plugins/wplocalplus-lite/templates/$template_name
	 *
	 * @since 1.0
	 * @param string $template_name Template to load.
	 * @param string $template_path Path to templates.
	 * @param string $default_path Default path to templates.
	 * @return mixed|void
	 */
	public function wplocalplus_lite_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		// Set variable to search in wplocalplus-lite folder of theme.
		if ( ! $template_path ) :
			$template_path = 'wplocalplus-lite/';
		endif;
		// Set default plugin templates path.
		if ( ! $default_path ) :
			$default_path = WPLOCALPLUS_LITE_PLUGIN_PATH . 'templates/'; // Path to the template folder.
		endif;
		// Search template file in theme folder.
		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name,
			)
		);
		// Get plugins template file.
		if ( ! $template ) :
			$template = $default_path . $template_name;
		endif;
		return apply_filters( 'wplocalplus_lite_locate_template', $template, $template_name, $template_path, $default_path );

	}

	/**
	 * Return Google Map for places.
	 *
	 * @since 1.0
	 * @param Object $q Post.
	 */
	public function wplocalplus_lite_make_google_map( $q ) {
		$the_options         = Wplocalplus_Lite::wplocalplus_lite_get_settings();
		$google_maps_api_key = isset( $the_options['google_maps_api_key'] ) ? $the_options['google_maps_api_key'] : '';
		$google_script_link  = 'https://maps.googleapis.com/maps/api/js?libraries=places&key=' . $google_maps_api_key;
		wp_enqueue_script( 'mapsapilibrary', $google_script_link, array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-gmap3' );
		while ( $q->have_posts() ) {
			$q->the_post();
			$post_id                       = get_the_ID();
			$custom                        = get_post_custom( $post_id );
			$gdataset                      = array();
			$gdataset['lat']               = (float) get_field( 'latitude', $post_id );
			$gdataset['long']              = (float) get_field( 'longitude', $post_id );
			$gdataset['name']              = (string) get_the_title();
			$gdataset['address']           = (string) get_field( 'address', $post_id );
			$gdataset['phone']             = (string) get_field( 'phone_number', $post_id );
			$gdataset['review']            = (string) isset( $custom['_wplocal_places_review_count'][0] ) ? $custom['_wplocal_places_review_count'][0] : '';
			$gdataset['rating']            = (string) get_field( 'ratings', $post_id );
			$gdataset['profile']           = (string) get_field( 'urls_profile', $post_id );
			$gdataset['sample_categories'] = (string) isset( $custom['_wplocal_places_sample_categories'][0] ) ? $custom['_wplocal_places_sample_categories'][0] : '';
			$gdata[]                       = $gdataset;
		}
		$this->wplocalplus_lite_make_googlemap( $gdata );
	}

	/**
	 * Make Google Maps for places.
	 *
	 * @since 1.0
	 * @param Array $gdata Google Places data.
	 */
	public function wplocalplus_lite_make_googlemap( $gdata ) {
		$max_latitude  = - 9999999;
		$min_latitude  = 9999999;
		$max_longitude = - 9999999;
		$min_longitude = 9999999;

		if ( $gdata ) {
			$count      = 0;
			$total_lat  = 0;
			$total_long = 0;
			$market_str = '';
			foreach ( $gdata as $row ) {
				if ( '' !== $row['lat'] && '' !== $row['long'] ) {
					// Max min latitude, longitude.
					if ( $row['lat'] > $max_latitude ) {
						$max_latitude = $row['lat'];
					}
					if ( $row['lat'] < $min_latitude ) {
						$min_latitude = $row['lat'];
					}
					if ( $row['long'] > $max_longitude ) {
						$max_longitude = $row['long'];
					}
					if ( $row['lat'] < $min_longitude ) {
						$min_longitude = $row['long'];
					}

					$total_lat  += $row['lat'];
					$total_long += $row['long'];
					$market_str .= '{lat:' . $row['lat'] . ', lng:' . $row['long'] . ", data:'<div style=\"min-height:100px;font-size:12px;line-height:15px\"><strong>" . addslashes( $row['name'] ) . '</strong><br/>Address :' . addslashes( $row['address'] ) . '<br/>Phone :<small>' . $row['phone'] . "</small></div>'},";
					$count ++;
				}
			}
		}
		$market_str = rtrim( $market_str, ',' );

		$center_latitude  = (float) $min_latitude + ( $max_latitude - $min_latitude ) / 2;
		$center_longitude = (float) $min_longitude + ( $max_longitude - $min_longitude ) / 2;

		$miles = ( 3958.75 * acos( sin( $min_latitude / 57.2958 ) * sin( $max_latitude / 57.2958 ) + cos( $min_latitude / 57.2958 ) * cos( $max_latitude / 57.2958 ) * cos( $max_longitude / 57.2958 - $min_longitude / 57.2958 ) ) );
		if ( $miles < 0.2 ) {
			$zoom = 16;
		} elseif ( $miles < 0.2 ) {
			$zoom = 16;
		} elseif ( $miles < 0.5 ) {
			$zoom = 15;
		} elseif ( $miles < 1 ) {
			$zoom = 14;
		} elseif ( $miles < 2 ) {
			$zoom = 13;
		} elseif ( $miles < 3 ) {
			$zoom = 12;
		} elseif ( $miles < 7 ) {
			$zoom = 11;
		} elseif ( $miles < 15 ) {
			$zoom = 10;
		} elseif ( $miles < 20 ) {
			$zoom = 9;
		} else {
			$zoom = 4;
		}

		echo '<script type="text/javascript">';
		echo 'jQuery(document).ready(function(){
		    jQuery("#gmap").gmap3({
		        action:"init",
		        options:{
		            center: [' . esc_attr( $center_latitude ) . ',' . esc_attr( $center_longitude ) . '],
		            zoom:' . esc_attr( $zoom ) . '
		        }
		    },
		    {
		        action: "addMarkers",';
		echo 'markers: [' . $market_str . '],'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo 'marker: {
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
		})';
		echo '</script>';
	}

	/**
	 * Return signed url for Google Maps.
	 *
	 * @since 1.0
	 * @param string $url_to_sign Signing URL.
	 * @param string $google_maps_sign_secret Google Maps Sign secret key.
	 * @return string
	 */
	public function wplocalplus_lite_sign_url( $url_to_sign, $google_maps_sign_secret ) {
		// Parse the url.
		$url = wp_parse_url( $url_to_sign );

		$url_part_to_sign = $url['path'] . '?' . $url['query'];

		// Decode the private key into its binary format.
		$decoded_key = $this->decode_base_64_url_safe( $google_maps_sign_secret );

		// Create a signature using the private key and the URL-encoded.
		// string using HMAC SHA1. This signature will be binary.
		$sign_secret = hash_hmac( 'sha1', $url_part_to_sign, $decoded_key, true );

		$encoded_signature = $this->encode_base_64_url_safe( $sign_secret );

		return $url_to_sign . '&signature=' . $encoded_signature;
	}

	/**
	 * Encode a string to URL-safe base64.
	 *
	 * @since 1.0
	 * @param string $value Value.
	 * @return mixed
	 */
	public function encode_base_64_url_safe( $value ) {
		return str_replace(
			array( '+', '/' ),
			array( '-', '_' ),
			base64_encode( $value ) // phpcs:ignore
		);
	}

	/**
	 * Decode a string from URL-safe base64.
	 *
	 * @since 1.0
	 * @param string $value Value.
	 * @return bool|string
	 */
	public function decode_base_64_url_safe( $value ) {
		return base64_decode(  // phpcs:ignore
			str_replace(
				array( '-', '_' ),
				array( '+', '/' ),
				$value
			)
		);
	}

}
