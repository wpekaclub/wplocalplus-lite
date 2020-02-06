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
		$choice = isset( $_GET['choice'] ) ? sanitize_text_field( wp_unslash( $_GET['choice'] ) ) : '';
		$latlon = isset( $_GET['latlon'] ) ? sanitize_text_field( wp_unslash( $_GET['latlon'] ) ) : '';
		if ( isset( $_GET['wplocal_places_sort'] ) ) {
			check_admin_referer( 'places_sort' );
			$choice = isset( $_GET['wplocal_places_sort'] ) ? sanitize_text_field( wp_unslash( $_GET['wplocal_places_sort'] ) ) : '';
			$latlon = isset( $_GET['wplocal_places_location'] ) ? sanitize_text_field( wp_unslash( $_GET['wplocal_places_location'] ) ) : '';
		}
		ob_start();
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
			$paged = (int) array_pop( $req_arr );
		} else {
			$paged = 1;
		}
		$atts        = shortcode_atts(
			array(
				'location' => 'cambridgema',
				'type'     => 'hotels',
				'list'     => 'wplocal_places',
				'limit'    => 5,
			),
			$atts
		);
		$location    = explode( ',', $atts['location'] );
		$place_type  = explode( ',', $atts['type'] );
		$limit       = $atts['limit'];
		$list        = $atts['list'];
		$the_options = Wplocalplus_Lite::wplocalplus_lite_get_settings();
		$args        = array(
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
		);
		if ( isset( $choice ) && '0' !== $choice ) {
			$the_options['choice'] = $choice;
			switch ( $choice ) {
				case 'name':
					$args['orderby'] = 'post_title';
					$args['order']   = 'ASC';
					break;
				case 'ratings':
					$args['meta_key'] = 'ratings'; // phpcs:ignore slow query
					$args['orderby']  = 'meta_value_num';
					break;
				case 'reviews':
					$args['meta_key'] = '_wplocal_places_review_count'; // phpcs:ignore slow query
					$args['orderby']  = 'meta_value_num';
					break;
			}
		}
		$q = new WP_Query( $args );
		if ( isset( $latlon ) && isset( $choice ) && 'latlon' === $choice ) {
			$the_options['latlon'] = $latlon;
			$q                     = apply_filters( 'wplocalplus_lite_filter_by_location', $q, $latlon );
		}
		$the_options['q'] = $q;
		if ( 'wplocal_places' === $list ) {
			$this->wplocalplus_lite_get_template( 'places.php', $the_options );
		} elseif ( 'wplocal_reviews' === $list ) {
			$this->wplocalplus_lite_get_template( 'reviews.php', $the_options );
		}
		$big           = 999999;
		$paginate_args = array(
			'base'    => str_replace( $big, '%#%', html_entity_decode( get_pagenum_link( $big ) ) ),
			'format'  => '?paged=%#%',
			'current' => max( 1, $paged ),
			'total'   => $q->max_num_pages, // $q is your custom query.
		);
		if ( 'wplocal_places' === $list ) {
			$paginate_args['add_args'] = array(
				'choice' => $choice,
				'latlon' => $latlon,
			);
		}
		echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$paginate_args
		);
		wp_reset_postdata();
		return ob_get_clean();
	}

	/**
	 * Filter posts by location.
	 *
	 * @since 1.2
	 * @param Object $q Query post.
	 * @param String $latlon Latitude and Longitude.
	 * @return mixed
	 */
	public function wplocalplus_lite_filter_by_location( $q, $latlon ) {
		$position  = explode( ',', $latlon );
		$latitude  = $position[0];
		$longitude = $position[1];
		$places    = $q->posts;
		foreach ( $places as $place ) {
			$place_id        = $place->ID;
			$place_latitude  = get_field( 'latitude', $place_id );
			$place_longitude = get_field( 'longitude', $place_id );
			$place->distance = $this->wplocalplus_lite_calculate_distance( $latitude, $longitude, $place_latitude, $place_longitude, 'K' );
		}
		usort(
			$places,
			function( $place1, $place2 ) {
				if ( $place1->distance === $place2->distance ) {
					return 0;
				}
				return $place1->distance < $place2->distance ? -1 : 1;
			}
		);
		$q->posts = $places;
		return $q;
	}

	/**
	 * Calculate distance between two points.
	 *
	 * @since 1.2
	 * @param int    $lat1 Latitude 1.
	 * @param int    $lon1 Longitude 1.
	 * @param int    $lat2 Latitude 2.
	 * @param int    $lon2 Longitude 2.
	 * @param string $unit Unit.
	 * @return float|int
	 */
	public function wplocalplus_lite_calculate_distance( $lat1, $lon1, $lat2, $lon2, $unit ) {
		if ( ( $lat1 === $lat2 ) && ( $lon1 === $lon2 ) ) {
			return 0;
		} else {
			$theta = $lon1 - $lon2;
			$dist  = sin( deg2rad( $lat1 ) ) * sin( deg2rad( $lat2 ) ) + cos( deg2rad( $lat1 ) ) * cos( deg2rad( $lat2 ) ) * cos( deg2rad( $theta ) );
			$dist  = acos( $dist );
			$dist  = rad2deg( $dist );
			$miles = $dist * 60 * 1.1515;
			$unit  = strtoupper( $unit );

			if ( 'K' === $unit ) {
				return ( $miles * 1.609344 );
			} elseif ( 'N' === $unit ) {
				return ( $miles * 0.8684 );
			} else {
				return $miles;
			}
		}
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
		$this->wplocalplus_lite_get_template( 'single-place.php', array_merge( $the_options, $data ) );
		die();
	}

	/**
	 * Script required for Google Map and ratings in single place details popup.
	 *
	 * @since 1.0
	 * @param int $latitude Latitude.
	 * @param int $longitude Longitude.
	 * @param int $show_map Show Map.
	 */
	public function wplocalplus_lite_after_single_place_content( $latitude, $longitude, $show_map ) {
		echo '<script type="text/javascript">';
		echo 'jQuery(".wplocal_place_main_content_details_ratings").starRating({
            totalStars: 5,
            strokeColor: "#894A00",
            strokeWidth: 10,
            starSize: 15,
            readOnly: true
        });';
		if ( 1 === $show_map ) {
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
		}
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
		$gdata = array();
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
		$market_str    = '';
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

}
