<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://club.wpeka.com
 * @since      1.0
 *
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/includes/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/includes/admin
 * @author     WPeka <support@wpeka.com>
 */
class Wplocalplus_Lite_Admin {

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
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_register_style( $this->plugin_name, WPLOCALPLUS_LITE_ASSETS_URL . 'css/admin/wplocalplus-lite-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_register_script( $this->plugin_name, WPLOCALPLUS_LITE_ASSETS_URL . 'js/admin/wplocalplus-lite-admin.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name . '-jscolor', WPLOCALPLUS_LITE_ASSETS_URL . 'libraries/jscolor/jscolor.js', '', $this->version, false );

	}

	/**
	 * Returns plugin action links.
	 *
	 * @param array $links Plugin actions links.
	 * @return array
	 */
	public function wplocalplus_lite_plugin_action_links( $links ) {
		$links = array_merge(
			array(
				'<a href="' . esc_url( 'https://club.wpeka.com/product/wplocalplus/?utm_source=wplocalplus&utm_medium=plugins&utm_campaign=link&utm_content=upgrade-to-pro' ) . '" target="_blank" rel="noopener noreferrer"><strong style="color: #11967A; display: inline;">' . __( 'Upgrade to Pro', 'wplocalplus-lite' ) . '</strong></a>',
			),
			$links
		);
		return $links;
	}

	/**
	 * Register block.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_register_block_type() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		$place_types    = array();
		$locations      = array();
		$terms          = get_terms( 'wplocal_place_type' );
		$place          = array();
		$place['label'] = __( 'Select', 'wplocalplus-lite' );
		$place['value'] = '';
		$place_types[]  = $place;
		foreach ( $terms as $term ) {
			$place          = array();
			$place['label'] = $term->name;
			$place['value'] = $term->slug;
			$place_types[]  = $place;
		}
		$terms        = get_terms( 'wplocal_location' );
		$loc          = array();
		$loc['label'] = __( 'Select', 'wplocalplus-lite' );
		$loc['value'] = '';
		$locations[]  = $loc;
		foreach ( $terms as $term ) {
			$loc          = array();
			$loc['label'] = $term->name;
			$loc['value'] = $term->slug;
			$locations[]  = $loc;
		}
		wp_enqueue_script(
			$this->plugin_name . '-block',
			WPLOCALPLUS_LITE_ASSETS_URL . 'js/blocks/wplocalplus-lite-block.js',
			array(
				'jquery',
				'wp-blocks',
				'wp-i18n',
				'wp-editor',
				'wp-element',
				'wp-components',
			),
			$this->version,
			false
		);
		wp_localize_script( $this->plugin_name . '-block', 'place_types', $place_types );
		wp_localize_script( $this->plugin_name . '-block', 'locations', $locations );
		register_block_type(
			'wplocalplus-lite/block',
			array(
				'editor_script'   => $this->plugin_name . '-block',
				'render_callback' => array( $this, 'wplocalplus_lite_block_render_callback' ),
				'attributes'      => array(
					'list'     => array(
						'default' => 'wplocal_places',
						'type'    => 'string,',
					),
					'type'     => array(
						'default' => 'hotels',
						'type'    => 'string,',
					),
					'location' => array(
						'default' => 'cambridgema',
						'type'    => 'string,',
					),
					'limit'    => array(
						'default' => 5,
						'type'    => 'number',
					),
				),
			)
		);
	}

	/**
	 * Render callback for block.
	 *
	 * @since 1.0
	 * @param Array $atts Shortcode attributes.
	 * @return string
	 */
	public function wplocalplus_lite_block_render_callback( $atts ) {
		$list     = isset( $atts['list'] ) ? $atts['list'] : '';
		$type     = isset( $atts['type'] ) ? ( is_array( $atts['type'] ) && ! empty( $atts['type'] ) ) ? implode( ',', $atts['type'] ) : $atts['type'] : '';
		$location = isset( $atts['location'] ) ? ( is_array( $atts['location'] ) && ! empty( $atts['location'] ) ) ? implode( ',', $atts['location'] ) : $atts['location'] : '';
		$limit    = isset( $atts['limit'] ) ? $atts['limit'] : 5;
		return do_shortcode( "[wplocalplus list='" . $list . "' type='" . $type . "' location='" . $location . "' limit='" . $limit . "']" );
	}

	/**
	 * Registers menu options, hooked into admin_menu.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_admin_menu() {
		add_menu_page( __( 'WP Local Plus', 'wplocalplus-lite' ), __( 'WP Local Plus', 'wplocalplus-lite' ), 'edit_pages', 'wplocalplus-lite', false, WPLOCALPLUS_LITE_ASSETS_URL . 'images/wplocalplus.png' );
		add_submenu_page( 'wplocalplus-lite', __( 'Settings', 'wplocalplus-lite' ), __( 'Settings', 'wplocalplus-lite' ), 'manage_options', 'wplocalplus-lite', array( $this, 'wplocalplus_lite_settings' ) );
		add_submenu_page( 'wplocalplus-lite', __( 'Places', 'wplocalplus-lite' ), __( 'Places', 'wplocalplus-lite' ), 'manage_options', 'edit.php?post_type=' . WPLOCALPLUS_PLACE_POST_TYPE );
		add_submenu_page( 'wplocalplus-lite', __( 'Reviews', 'wplocalplus-lite' ), __( 'Reviews', 'wplocalplus-lite' ), 'manage_options', 'edit.php?post_type=' . WPLOCALPLUS_REVIEW_POST_TYPE );
		add_submenu_page( 'wplocalplus-lite', __( 'Place Types', 'wplocalplus-lite' ), __( 'Place Types', 'wplocalplus-lite' ), 'manage_options', 'edit-tags.php?taxonomy=wplocal_place_type&post_type=' . WPLOCALPLUS_PLACE_POST_TYPE );
		add_submenu_page( 'wplocalplus-lite', __( 'Locations', 'wplocalplus-lite' ), __( 'Locations', 'wplocalplus-lite' ), 'manage_options', 'edit-tags.php?taxonomy=wplocal_location&post_type=' . WPLOCALPLUS_PLACE_POST_TYPE );
	}

	/**
	 * Highlight custom post menu.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_highlight_menu() {
		global $parent_file, $submenu_file, $post_type, $current_screen;

		if ( WPLOCALPLUS_PLACE_POST_TYPE === $post_type ) {
			$parent_file  = 'wplocalplus-lite'; // phpcs:ignore override ok.
			$submenu_file = 'edit.php?post_type=' . WPLOCALPLUS_PLACE_POST_TYPE; // phpcs:ignore override ok.
		}
		if ( WPLOCALPLUS_REVIEW_POST_TYPE === $post_type ) {
			$parent_file  = 'wplocalplus-lite'; // phpcs:ignore override ok.
			$submenu_file = 'edit.php?post_type=' . WPLOCALPLUS_REVIEW_POST_TYPE; // phpcs:ignore override ok.
		}
		if ( 'wplocal_place_type' === $current_screen->taxonomy ) {
			$parent_file  = 'wplocalplus-lite'; // phpcs:ignore override ok.
			$submenu_file = 'edit-tags.php?taxonomy=wplocal_place_type&post_type=' . WPLOCALPLUS_PLACE_POST_TYPE; // phpcs:ignore override ok.
		}
		if ( 'wplocal_location' === $current_screen->taxonomy ) {
			$parent_file  = 'wplocalplus-lite'; // phpcs:ignore override ok.
			$submenu_file = 'edit-tags.php?taxonomy=wplocal_location&post_type=' . WPLOCALPLUS_PLACE_POST_TYPE; // phpcs:ignore override ok.
		}

	}

	/**
	 * Add banner metabox.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_add_metaboxes() {
		add_meta_box( 'wplocalplus_lite_places_banner', 'Places Banner', array( $this, 'wplocalplus_lite_render_places_banner' ), WPLOCALPLUS_PLACE_POST_TYPE, 'side', 'default' );
		add_meta_box( 'wplocalplus_lite_reviews_banner', 'Reviews Banner', array( $this, 'wplocalplus_lite_render_reviews_banner' ), WPLOCALPLUS_REVIEW_POST_TYPE, 'side', 'default' );
	}

	/**
	 * Render Places banner metabox.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_render_places_banner() {
		wp_enqueue_style( $this->plugin_name );
		echo '<div class="wplocalplus_lite_banner"><a href="https://club.wpeka.com/product/wp-local-plus/?utm_source=wp-local-plus-lite&utm_medium=banner&utm_campaign=wp-local-plus&utm_content=add-edit-place" target="_blank"><img src="' . esc_url( WPLOCALPLUS_LITE_ASSETS_URL ) . 'images/places_banner.png" alt="Upgrade to WPLocalPlus Pro"/></a></div>';
	}

	/**
	 * Render Reviews banner metabox.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_render_reviews_banner() {
		wp_enqueue_style( $this->plugin_name );
		echo '<div class="wplocalplus_lite_banner"><a href="https://club.wpeka.com/product/wp-local-plus/?utm_source=wp-local-plus-lite&utm_medium=banner&utm_campaign=wp-local-plus&utm_content=add-edit-review" target="_blank"><img src="' . esc_url( WPLOCALPLUS_LITE_ASSETS_URL ) . 'images/reviews_banner.png" alt="Upgrade to WPLocalPlus Pro"/></a></div>';
	}

	/**
	 * Filter priority for ACF field group.
	 *
	 * @since 1.0
	 * @param String $priority Priority.
	 * @param Array  $field_group Field Group.
	 * @return string
	 */
	public function wplocalplus_lite_filter_acf_priority( $priority, $field_group ) {
		$context = isset( $field_group['position'] ) ? $field_group['position'] : '';

		if ( ! empty( $context ) && 'side' === $context ) {
			$priority = 'default';
		}
		return $priority;
	}

	/**
	 * Admin settings page.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_settings() {
		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name );
		wp_enqueue_script( $this->plugin_name . '-jscolor' );
		// Lock out non-admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_attr__( 'You do not have sufficient permission to perform this operation', 'wplocalplus-lite' ) );
		}
		$reset       = false;
		$saved       = false;
		$the_options = Wplocalplus_Lite::wplocalplus_lite_get_settings();
		if ( isset( $_POST['customization_update'] ) ) {
			check_admin_referer( 'wplocalplus-lite-settings-' . WPLOCALPLUS_LITE_SETTINGS_FIELD );
			foreach ( $the_options as $key => $value ) {
				if ( isset( $_POST[ 'field_' . $key ] ) ) {
					// Store sanitised values only.
					$the_options[ $key ] = Wplocalplus_Lite::wplocalplus_lite_sanitise_settings( $key, wp_unslash( $_POST[ 'field_' . $key ] ) ); // phpcs:ignore input var ok, CSRF ok, sanitization ok.
				}
			}
			if ( ! array_key_exists( 'field_show_map', $_POST ) ) {
				$the_options['show_map'] = '0';
			}
			update_option( WPLOCALPLUS_LITE_SETTINGS_FIELD, $the_options );
			$saved = true;
		} elseif ( isset( $_POST['customization_restore'] ) ) {
			check_admin_referer( 'wplocalplus-lite-settings-' . WPLOCALPLUS_LITE_SETTINGS_FIELD );
			$the_options = Wplocalplus_Lite::wplocalplus_lite_get_default_settings();
			update_option( WPLOCALPLUS_LITE_SETTINGS_FIELD, $the_options );
			$reset = true;
		}
		require_once plugin_dir_path( __FILE__ ) . 'settings/wplocalplus-lite-admin-display.php';
	}

	/**
	 * Register custom post type for WPLocalPlus Places and Reviews.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_register_custom_post_type() {
		$labels = array(
			'name'                  => __( 'Places', 'wplocalplus-lite' ),
			'all_items'             => __( 'Places', 'wplocalplus-lite' ),
			'singular_name'         => __( 'Place', 'wplocalplus-lite' ),
			'add_new'               => __( 'Add New', 'wplocalplus-lite' ),
			'add_new_item'          => __( 'Add New Place', 'wplocalplus-lite' ),
			'edit_item'             => __( 'Edit Place', 'wplocalplus-lite' ),
			'new_item'              => __( 'New Place', 'wplocalplus-lite' ),
			'view_item'             => __( 'View Place', 'wplocalplus-lite' ),
			'search_items'          => __( 'Search Places', 'wplocalplus-lite' ),
			'not_found'             => __( 'Nothing found', 'wplocalplus-lite' ),
			'not_found_in_trash'    => __( 'Nothing found in Trash', 'wplocalplus-lite' ),
			'featured_image'        => __( 'Place image', 'wplocalplus-lite' ),
			'set_featured_image'    => __( 'Set place image', 'wplocalplus-lite' ),
			'remove_featured_image' => __( 'Remove place image', 'wplocalplus-lite' ),
			'use_featured_image'    => __( 'Use as place image', 'wplocalplus-lite' ),
			'parent_item_colon'     => '',
		);
		$args   = array(
			'labels'              => $labels,
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_rest'        => false,
			'query_var'           => true,
			'rewrite'             => true,
			'capabilities'        => array(
				'publish_posts'       => 'manage_options',
				'edit_posts'          => 'manage_options',
				'edit_others_posts'   => 'manage_options',
				'delete_posts'        => 'manage_options',
				'delete_others_posts' => 'manage_options',
				'read_private_posts'  => 'manage_options',
				'edit_post'           => 'manage_options',
				'delete_post'         => 'manage_options',
				'read_post'           => 'manage_options',
			),
			'supports'            => array( 'title' ),
		);
		if ( ! post_type_exists( WPLOCALPLUS_PLACE_POST_TYPE ) ) {
			register_post_type( WPLOCALPLUS_PLACE_POST_TYPE, $args );
		}
		$labels           = array(
			'name'               => __( 'Reviews', 'wplocalplus-lite' ),
			'all_items'          => __( 'Reviews', 'wplocalplus-lite' ),
			'singular_name'      => __( 'Review', 'wplocalplus-lite' ),
			'add_new'            => __( 'Add New', 'wplocalplus-lite' ),
			'add_new_item'       => __( 'Add New Review', 'wplocalplus-lite' ),
			'edit_item'          => __( 'Edit Review', 'wplocalplus-lite' ),
			'new_item'           => __( 'New Review', 'wplocalplus-lite' ),
			'view_item'          => __( 'View Review', 'wplocalplus-lite' ),
			'search_items'       => __( 'Search Reviews', 'wplocalplus-lite' ),
			'not_found'          => __( 'Nothing found', 'wplocalplus-lite' ),
			'not_found_in_trash' => __( 'Nothing found in Trash', 'wplocalplus-lite' ),
			'parent_item_colon'  => '',
		);
		$args['labels']   = $labels;
		$args['supports'] = array( 'title', 'editor' );
		register_post_type( WPLOCALPLUS_REVIEW_POST_TYPE, $args );
	}

	/**
	 * Register custom taxonomies for WPLocalPlus Custom Post types - Places and Reviews.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_register_custom_taxonomies() {
		$labels = array(
			'name'          => __( 'Locations', 'wplocalplus-lite' ),
			'singular_name' => __( 'Location', 'wplocalplus-lite' ),
			'search_items'  => __( 'Search Locations', 'wplocalplus-lite' ),
			'all_items'     => __( 'All Locations', 'wplocalplus-lite' ),
			'edit_item'     => __( 'Edit Location', 'wplocalplus-lite' ),
			'update_item'   => __( 'Update Location', 'wplocalplus-lite' ),
			'add_new_item'  => __( 'Add New Location', 'wplocalplus-lite' ),
			'new_item_name' => __( 'New Location Name', 'wplocalplus-lite' ),
			'menu_name'     => __( 'Locations', 'wplocalplus-lite' ),
		);
		$args   = array(
			'label'             => __( 'Location', 'wplocalplus-lite' ),
			'labels'            => $labels,
			'hierarchical'      => false,
			'show_ui'           => true,
			'meta_box_cb'       => false,
			'show_admin_column' => false,
			'show_in_rest'      => true,
		);
		register_taxonomy( 'wplocal_location', array( WPLOCALPLUS_PLACE_POST_TYPE, WPLOCALPLUS_REVIEW_POST_TYPE ), $args );
		$labels = array(
			'name'          => __( 'Place Types', 'wplocalplus-lite' ),
			'singular_name' => __( 'Place Type', 'wplocalplus-lite' ),
			'search_items'  => __( 'Search Place Types', 'wplocalplus-lite' ),
			'all_items'     => __( 'All Place Types', 'wplocalplus-lite' ),
			'edit_item'     => __( 'Edit Place Type', 'wplocalplus-lite' ),
			'update_item'   => __( 'Update Place Type', 'wplocalplus-lite' ),
			'add_new_item'  => __( 'Add New Place Type', 'wplocalplus-lite' ),
			'new_item_name' => __( 'New Place Type Name', 'wplocalplus-lite' ),
			'menu_name'     => __( 'Place Types', 'wplocalplus-lite' ),
		);
		$args   = array(
			'label'             => __( 'Place Type', 'wplocalplus-lite' ),
			'labels'            => $labels,
			'hierarchical'      => false,
			'show_ui'           => true,
			'meta_box_cb'       => false,
			'show_admin_column' => false,
			'show_in_rest'      => true,
		);
		register_taxonomy( 'wplocal_place_type', array( WPLOCALPLUS_PLACE_POST_TYPE, WPLOCALPLUS_REVIEW_POST_TYPE ), $args );
	}

	/**
	 * Manage Places columns.
	 *
	 * @since 1.0
	 * @return array|void
	 */
	public function wplocalplus_lite_manage_edit_places_columns() {
		global $current_screen;
		if ( WPLOCALPLUS_PLACE_POST_TYPE !== $current_screen->post_type ) {
			return;
		}
		wp_enqueue_style( $this->plugin_name );
		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'title'        => 'Name',
			'place_type'   => 'Place Type',
			'location'     => 'Location',
			'address'      => 'Address',
			'phone_number' => 'Phone Number',
			'ratings'      => 'Ratings',
			'review_count' => 'Review Count',
			'source'       => 'Source',
			'date'         => 'Date',
		);
		return $columns;
	}

	/**
	 * Manage Reviews columns.
	 *
	 * @since 1.0
	 * @return array|void
	 */
	public function wplocalplus_lite_manage_edit_reviews_columns() {
		global $current_screen;
		if ( WPLOCALPLUS_REVIEW_POST_TYPE !== $current_screen->post_type ) {
			return;
		}
		wp_enqueue_style( $this->plugin_name );
		$columns = array(
			'cb'            => '<input type="checkbox" />',
			'title'         => 'Title',
			'business_name' => 'Business Name',
			'review_rating' => 'Ratings',
			'review_author' => 'Author',
			'review_date'   => 'Review Date',
			'review_source' => 'Source',
		);
		return $columns;
	}

	/**
	 * Manage Places columns.
	 *
	 * @since 1.0
	 * @param string $column Column.
	 * @param int    $post_id Post ID.
	 */
	public function wplocalplus_lite_places_manage_posts_custom_columns( $column, $post_id = 0 ) {
		global $post;
		global $current_screen;

		if ( WPLOCALPLUS_PLACE_POST_TYPE !== $current_screen->post_type ) {
			return;
		}

		switch ( $column ) {
			case 'place_type':
				$terms   = get_field( 'place_type' );
				$content = '';
				if ( $terms ) {
					foreach ( $terms as $term ) {
						$content .= $term->name . ', ';
					}
					$content = rtrim( $content, ', ' );
				}
				echo esc_attr( $content );
				break;
			case 'location':
				$term = get_field( 'location' );
				if ( $term ) {
					echo esc_attr( $term->name );
				}
				break;
			case 'address':
				echo esc_attr( get_field( 'address' ) );
				break;
			case 'phone_number':
				echo esc_attr( get_field( 'phone_number' ) );
				break;
			case 'ratings':
				echo esc_attr( get_field( 'ratings' ) );
				break;
			case 'review_count':
				$custom       = get_post_custom();
				$review_count = isset( $custom['_wplocal_places_review_count'][0] ) ? $custom['_wplocal_places_review_count'][0] : '';
				echo esc_attr( $review_count );
				break;
			case 'source':
				$custom = get_post_custom();
				$source = isset( $custom['_wplocal_places_source'][0] ) ? $custom['_wplocal_places_source'][0] : '';
				if ( 'city_grid' === $source ) {
					echo 'City Grid';
				} elseif ( 'google' === $source ) {
					echo 'Google';
				} else {
					echo 'Manual';
				}
				break;
		}
	}

	/**
	 * Manage Reviews columns.
	 *
	 * @since 1.0
	 * @param string $column Column.
	 * @param int    $post_id Post ID.
	 */
	public function wplocalplus_lite_reviews_manage_posts_custom_columns( $column, $post_id = 0 ) {
		global $post;
		global $current_screen;

		if ( WPLOCALPLUS_REVIEW_POST_TYPE !== $current_screen->post_type ) {
			return;
		}

		switch ( $column ) {
			case 'business_name':
				// Get business details from post.
				$review_place = get_field( 'review_place' );
				if ( isset( $review_place ) ) {
					echo '<a target="_blank" href="' . esc_url( get_edit_post_link( $review_place ) ) . '" title="' . esc_attr( get_the_title( $review_place ) ) . '">' . esc_attr( get_the_title( $review_place ) ) . '</a>';
				}
				break;
			case 'review_rating':
				echo esc_attr( get_field( 'review_rating' ) );
				break;
			case 'review_author':
				$author_url = get_field( 'author_url' );
				if ( isset( $author_url ) && ! empty( $author_url ) ) {
					echo '<a target="_blank" href="' . esc_url( get_field( 'author_url' ) ) . '" title="' . esc_attr( get_field( 'review_author' ) ) . '">' . esc_attr( get_field( 'review_author' ) ) . '</a>';
				} else {
					echo esc_attr( get_field( 'review_author' ) );
				}
				break;
			case 'review_date':
				echo esc_attr( get_field( 'review_date' ) );
				break;
			case 'review_source':
				$custom = get_post_custom();
				$source = isset( $custom['_wplocal_reviews_source'][0] ) ? $custom['_wplocal_reviews_source'][0] : '';
				if ( 'city_grid' === $source ) {
					echo 'City Grid';
				} elseif ( 'google' === $source ) {
					echo 'Google';
				} else {
					echo 'Manual';
				}
				break;
		}
	}

	/**
	 * Add custom filter for Places and Reviews.
	 *
	 * @since 1.0
	 * @param string $post_type Post Type.
	 * @param string $which Which.
	 */
	public function wplocalplus_lite_restrict_manage_posts( $post_type, $which ) {
		if ( WPLOCALPLUS_PLACE_POST_TYPE !== $post_type && WPLOCALPLUS_REVIEW_POST_TYPE !== $post_type ) {
			return;
		}

		$taxonomies = array( 'wplocal_location', 'wplocal_place_type' );
		foreach ( $taxonomies as $slug ) {
			$tax_obj = get_taxonomy( $slug );

			$tax_name = $tax_obj->labels->name;

			$terms = get_terms( $slug );

			echo "<select name='" . esc_attr( $slug ) . "' id='" . esc_attr( $slug ) . "' class='postform'>";
			echo '<option value="">' .
				/* translators: 1: Taxonomy */
				sprintf( esc_html__( 'All %s', 'wplocalplus-lite' ), esc_attr( $tax_name ) ) . '</option>';
			foreach ( $terms as $term ) {
				printf(
					'<option value="%1$s" %2$s>%3$s (%4$s)</option>',
					esc_attr( $term->slug ),
					( ( isset( $_GET[ $slug ] ) && ( $_GET[ $slug ] === $term->slug ) ) ? ' selected="selected"' : '' ), // phpcs:ignore WordPress.Security.NonceVerification
					esc_attr( $term->name ),
					esc_attr( $term->count )
				);
			}
			echo '</select>';
		}

		if ( WPLOCALPLUS_REVIEW_POST_TYPE === $post_type ) {
			global $wpdb;
			$sql          = 'SELECT ID, post_title FROM ' . $wpdb->posts . " WHERE post_type='wplocal_places' AND post_parent=0 AND post_status='PUBLISH' ORDER BY post_title";
			$parent_pages = $wpdb->get_results( $sql, OBJECT_K ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$select       = '<select name="reviews_parent_places">';
			$select      .= '<option value="0">All Places</option>';
			$current      = isset( $_GET['reviews_parent_places'] ) ? sanitize_text_field( wp_unslash( $_GET['reviews_parent_places'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
			foreach ( $parent_pages as $page ) {
				/* translators: 1: Place post ID 2: Place Title */
				$select .= sprintf(
					'<option value="%s"%s>%s</option>',
					$page->ID,
					$page->ID === $current ? ' selected="selected"' : '',
					$page->post_title
				);
			}
			$select .= '</select>';
			echo $select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Custom filter for Reviews.
	 *
	 * @since 1.0
	 * @param Object $query Query object.
	 */
	public function wplocalplus_lite_reviews_posts_filter( $query ) {
		global $pagenow;
		if ( is_admin() && 'edit.php' === $pagenow && ! empty( $_GET['reviews_parent_places'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$query->query_vars['post_parent'] = sanitize_text_field( wp_unslash( $_GET['reviews_parent_places'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}
	}

	/**
	 * Remove quick edit action from Places and Reviews.
	 *
	 * @since 1.0
	 * @param Array  $actions Actions.
	 * @param Object $post Post.
	 * @return mixed
	 */
	public function wplocalplus_lite_row_actions( $actions, $post ) {
		if ( WPLOCALPLUS_PLACE_POST_TYPE !== $post->post_type && WPLOCALPLUS_REVIEW_POST_TYPE !== $post->post_type ) {
			return $actions;
		}
		unset( $actions['inline hide-if-no-js'] );
		return $actions;
	}

	/**
	 * Remove view action from place types and locations.
	 *
	 * @since 1.0
	 * @param Array $actions Actions.
	 * @param Array $tag Tags.
	 * @return mixed
	 */
	public function wplocalplus_lite_taxonomy_row_actions( $actions, $tag ) {
		unset( $actions['view'] );
		return $actions;
	}

	/**
	 * Save place.
	 *
	 * @since 1.0
	 * @param int $post_id Post ID.
	 */
	public function wplocalplus_lite_places_save_custom_post( $post_id ) {
		$post = get_post( $post_id );
		if ( WPLOCALPLUS_PLACE_POST_TYPE !== $post->post_type ) {
			return;
		}
		if ( $post_id ) {
			$google_map_coordinates = get_field( 'google_map_coordinates' );
			$latitude               = isset( $google_map_coordinates['lat'] ) ? $google_map_coordinates['lat'] : '';
			$longitude              = isset( $google_map_coordinates['lng'] ) ? $google_map_coordinates['lng'] : '';
			update_field( 'latitude', $latitude, $post_id );
			update_field( 'longitude', $longitude, $post_id );
			$location = get_field( 'location' );
			if ( ! empty( $location ) ) {
				$term_id = wp_set_object_terms( $post_id, $location->slug, 'wplocal_location', false );
			}
			$place_type = get_field( 'place_type' );
			if ( ! empty( $place_type ) ) {
				foreach ( $place_type as $type ) {
					$term_id = wp_set_object_terms( $post_id, $type->slug, 'wplocal_place_type', true );
				}
			}
			$featured = get_post_meta( $post_id, '_wplocal_places_featured' );
			if ( ! $featured ) {
				update_post_meta( $post_id, '_wplocal_places_featured', '0' );
			}
			$source = get_post_meta( $post_id, '_wplocal_places_source' );
			if ( ! $source ) {
				update_post_meta( $post_id, '_wplocal_places_source', 'manual' );
			}
			$custom       = get_post_custom( $post_id );
			$review_count = isset( $custom['_wplocal_places_review_count'][0] ) ? $custom['_wplocal_places_review_count'][0] : '';
			if ( empty( $review_count ) ) {
				update_post_meta( $post_id, '_wplocal_places_review_count', 0 );
			}
		}
	}

	/**
	 * Save review.
	 *
	 * @since 1.0
	 * @param int $post_id Post ID.
	 */
	public function wplocalplus_lite_reviews_save_custom_post( $post_id ) {
		$post = get_post( $post_id );
		if ( WPLOCALPLUS_REVIEW_POST_TYPE !== $post->post_type ) {
			return;
		}
		if ( $post_id ) {
			$featured = get_post_meta( $post_id, '_wplocal_reviews_featured' );
			if ( ! $featured ) {
				update_post_meta( $post_id, '_wplocal_reviews_featured', '0' );
			}
			$source = get_post_meta( $post_id, '_wplocal_reviews_source' );
			if ( ! $source ) {
				update_post_meta( $post_id, '_wplocal_reviews_source', 'manual' );
			}
			$review_place = get_field( 'review_place' );
			if ( $review_place ) {
				$post_parent = wp_get_post_parent_id( $post_id );
				if ( 0 === $post_parent ) {
					$custom       = get_post_custom( $review_place );
					$review_count = isset( $custom['_wplocal_places_review_count'][0] ) ? $custom['_wplocal_places_review_count'][0] : '';
					if ( ! empty( $review_count ) ) {
						$review_count++;
					} else {
						$review_count = 1;
					}
					update_post_meta( $review_place, '_wplocal_places_review_count', $review_count );
					wp_update_post(
						array(
							'ID'          => $post_id,
							'post_parent' => $review_place,
						)
					);
				}
				$location = get_field( 'location', $review_place );
				if ( ! empty( $location ) ) {
					$term_id = wp_set_object_terms( $post_id, $location->slug, 'wplocal_location', false );
				}
				$place_type = get_field( 'place_type', $review_place );
				if ( ! empty( $place_type ) ) {
					foreach ( $place_type as $type ) {
						$term_id = wp_set_object_terms( $post_id, $type->slug, 'wplocal_place_type', true );
					}
				}
			}
		}
	}

	/**
	 * Trash Custom post review associated with place.
	 *
	 * @since 1.0
	 * @param int $post_id Post ID.
	 */
	public function wplocalplus_lite_trash_custom_post( $post_id ) {
		$post = get_post( $post_id );
		if ( WPLOCALPLUS_PLACE_POST_TYPE !== $post->post_type ) {
			return;
		}
		$args = array(
			'post_parent' => $post_id,
			'post_type'   => WPLOCALPLUS_REVIEW_POST_TYPE,
		);

		$posts = get_posts( $args );

		if ( is_array( $posts ) && count( $posts ) > 0 ) {
			// Trash all the Children of the Parent Page.
			foreach ( $posts as $post ) {
				wp_trash_post( $post->ID );
			}
		}
	}

	/**
	 * Delete permanently custom post review associated with place.
	 *
	 * @since 1.0
	 * @param int $post_id Post ID.
	 */
	public function wplocalplus_lite_delete_custom_post( $post_id ) {
		$post = get_post( $post_id );
		if ( WPLOCALPLUS_PLACE_POST_TYPE !== $post->post_type ) {
			return;
		}
		$args = array(
			'post_parent' => $post_id,
			'post_type'   => WPLOCALPLUS_REVIEW_POST_TYPE,
			'post_status' => 'trash',
		);

		$posts = get_posts( $args );

		if ( is_array( $posts ) && count( $posts ) > 0 ) {
			// Delete all the Children of the Parent Page.
			foreach ( $posts as $post ) {
				wp_delete_post( $post->ID, true );
			}
		}
	}

	/**
	 * Restore custom post review associated with place.
	 *
	 * @since 1.0
	 * @param int $post_id Post ID.
	 */
	public function wplocalplus_lite_untrash_custom_post( $post_id ) {
		$post = get_post( $post_id );
		if ( WPLOCALPLUS_PLACE_POST_TYPE !== $post->post_type ) {
			return;
		}
		$args = array(
			'post_parent' => $post_id,
			'post_type'   => WPLOCALPLUS_REVIEW_POST_TYPE,
			'post_status' => 'trash',
		);

		$posts = get_posts( $args );

		if ( is_array( $posts ) && count( $posts ) > 0 ) {
			// Untrash all the Children of the Parent Page.
			foreach ( $posts as $post ) {
				wp_untrash_post( $post->ID );
			}
		}
	}

	/**
	 * Trash custom post review count associated with place.
	 *
	 * @since 1.2
	 * @param int $post_id Post ID.
	 */
	public function wplocalplus_lite_trash_custom_review_post( $post_id ) {
		$post = get_post( $post_id );
		if ( WPLOCALPLUS_REVIEW_POST_TYPE !== $post->post_type ) {
			return;
		}
		$parent_post  = get_post( $post->post_parent );
		$custom       = get_post_custom( $parent_post->ID );
		$review_count = isset( $custom['_wplocal_places_review_count'][0] ) ? $custom['_wplocal_places_review_count'][0] : '';
		$review_count--;
		update_post_meta( $parent_post->ID, '_wplocal_places_review_count', $review_count );
	}

	/**
	 * Restore custom post review count associated with place.
	 *
	 * @since 1.2
	 * @param int $post_id Post ID.
	 */
	public function wplocalplus_lite_untrash_custom_review_post( $post_id ) {
		$post = get_post( $post_id );
		if ( WPLOCALPLUS_REVIEW_POST_TYPE !== $post->post_type ) {
			return;
		}
		$parent_post  = get_post( $post->post_parent );
		$custom       = get_post_custom( $parent_post->ID );
		$review_count = isset( $custom['_wplocal_places_review_count'][0] ) ? $custom['_wplocal_places_review_count'][0] : '';
		$review_count++;
		update_post_meta( $parent_post->ID, '_wplocal_places_review_count', $review_count );
	}

	/**
	 * Registers custom fields for Places and Reviews.
	 *
	 * @since 1.0
	 */
	public function wplocalplus_lite_add_acf_fields() {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			// Places - Address, Phone Number.
			acf_add_local_field_group(
				array(
					'key'                   => 'group_address',
					'title'                 => 'Address',
					'fields'                => array(
						array(
							'key'               => 'field_address',
							'label'             => 'Address',
							'name'              => 'address',
							'type'              => 'textarea',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'maxlength'         => '',
							'rows'              => '',
							'new_lines'         => '',
						),
						array(
							'key'               => 'field_phone_number',
							'label'             => 'Phone Number',
							'name'              => 'phone_number',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_places',
							),
						),
					),
					'menu_order'            => 1,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'left',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
			// Places - Location.
			acf_add_local_field_group(
				array(
					'key'                   => 'group_location',
					'title'                 => 'Location',
					'fields'                => array(
						array(
							'key'               => 'field_location',
							'label'             => '',
							'name'              => 'location',
							'type'              => 'taxonomy',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'taxonomy'          => 'wplocal_location',
							'field_type'        => 'select',
							'allow_null'        => 1,
							'add_term'          => 1,
							'save_terms'        => 0,
							'load_terms'        => 0,
							'return_format'     => 'object',
							'multiple'          => 0,
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_places',
							),
						),
					),
					'menu_order'            => 1,
					'position'              => 'side',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
			// Places - Place Type.
			acf_add_local_field_group(
				array(
					'key'                   => 'group_place_type',
					'title'                 => 'Place Type',
					'fields'                => array(
						array(
							'key'               => 'field_place_type',
							'label'             => '',
							'name'              => 'place_type',
							'type'              => 'taxonomy',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'taxonomy'          => 'wplocal_place_type',
							'field_type'        => 'multi_select',
							'allow_null'        => 1,
							'add_term'          => 1,
							'save_terms'        => 0,
							'load_terms'        => 0,
							'return_format'     => 'object',
							'multiple'          => 0,
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_places',
							),
						),
					),
					'menu_order'            => 2,
					'position'              => 'side',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
			// Places - URLS (Profile, Website).
			acf_add_local_field_group(
				array(
					'key'                   => 'group_urls',
					'title'                 => 'URLS',
					'fields'                => array(
						array(
							'key'               => 'field_urls',
							'label'             => '',
							'name'              => 'urls',
							'type'              => 'group',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'layout'            => 'row',
							'sub_fields'        => array(
								array(
									'key'               => 'field_profile',
									'label'             => 'Profile',
									'name'              => 'profile',
									'type'              => 'url',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
								),
								array(
									'key'               => 'field_website',
									'label'             => 'Website',
									'name'              => 'website',
									'type'              => 'url',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
								),
							),
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_places',
							),
						),
					),
					'menu_order'            => 2,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
			// Places - Map Coordinates (Latitude, Longitude). Inactive.
			acf_add_local_field_group(
				array(
					'key'                   => 'group_map_coordinates',
					'title'                 => 'Map Coordinates',
					'fields'                => array(
						array(
							'key'               => 'field_latitude',
							'label'             => 'Latitude',
							'name'              => 'latitude',
							'type'              => 'number',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'min'               => '',
							'max'               => '',
							'step'              => '',
						),
						array(
							'key'               => 'field_longitude',
							'label'             => 'Longitude',
							'name'              => 'longitude',
							'type'              => 'number',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'min'               => '',
							'max'               => '',
							'step'              => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_places',
							),
						),
					),
					'menu_order'            => 3,
					'position'              => 'side',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => false,
					'description'           => '',
				)
			);
			// Places - Ratings.
			acf_add_local_field_group(
				array(
					'key'                   => 'group_ratings',
					'title'                 => 'Ratings',
					'fields'                => array(
						array(
							'key'               => 'field_ratings',
							'label'             => '',
							'name'              => 'ratings',
							'type'              => 'range',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'min'               => 0,
							'max'               => 5,
							'step'              => '.5',
							'prepend'           => '',
							'append'            => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_places',
							),
						),
					),
					'menu_order'            => 4,
					'position'              => 'side',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
			// Places - Featured Image.
			acf_add_local_field_group(
				array(
					'key'                   => 'group_featured_image',
					'title'                 => 'Featured Image',
					'fields'                => array(
						array(
							'key'               => 'field_featured_image',
							'label'             => '',
							'name'              => 'image',
							'type'              => 'image',
							'instructions'      => '(Recommended Size : 650X250 pixels)',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'id',
							'preview_size'      => 'medium',
							'library'           => 'all',
							'min_width'         => '',
							'min_height'        => '',
							'min_size'          => '',
							'max_width'         => '',
							'max_height'        => '',
							'max_size'          => '',
							'mime_types'        => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_places',
							),
						),
					),
					'menu_order'            => 5,
					'position'              => 'side',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
			// Places - Google Map.
			acf_add_local_field_group(
				array(
					'key'                   => 'group_google_map',
					'title'                 => 'Map',
					'fields'                => array(
						array(
							'key'               => 'field_google_map_coordinates',
							'label'             => '',
							'name'              => 'google_map_coordinates',
							'type'              => 'google_map',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'center_lat'        => '',
							'center_lng'        => '',
							'zoom'              => '',
							'height'            => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_places',
							),
						),
					),
					'menu_order'            => 3,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
			// Reviews - Business Details (Pros, Cons).
			acf_add_local_field_group(
				array(
					'key'                   => 'group_business_details',
					'title'                 => 'Business Details',
					'fields'                => array(
						array(
							'key'               => 'field_business_details',
							'label'             => '',
							'name'              => 'business_details',
							'type'              => 'group',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'layout'            => 'row',
							'sub_fields'        => array(
								array(
									'key'               => 'field_pros',
									'label'             => 'Pros',
									'name'              => 'pros',
									'type'              => 'textarea',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'maxlength'         => '',
									'rows'              => '',
									'new_lines'         => '',
								),
								array(
									'key'               => 'field_cons',
									'label'             => 'Cons',
									'name'              => 'cons',
									'type'              => 'textarea',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'maxlength'         => '',
									'rows'              => '',
									'new_lines'         => '',
								),
							),
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_reviews',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
			// Reviews - Ratings.
			acf_add_local_field_group(
				array(
					'key'                   => 'group_review_ratings',
					'title'                 => 'Ratings',
					'fields'                => array(
						array(
							'key'               => 'field_review_rating',
							'label'             => '',
							'name'              => 'review_rating',
							'type'              => 'range',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'min'               => 0,
							'max'               => 5,
							'step'              => '.5',
							'prepend'           => '',
							'append'            => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_reviews',
							),
						),
					),
					'menu_order'            => 4,
					'position'              => 'side',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
			// Reviews - Review URL, Author, Author URL, Date.
			acf_add_local_field_group(
				array(
					'key'                   => 'group_review',
					'title'                 => 'Review',
					'fields'                => array(
						array(
							'key'               => 'field_review_url',
							'label'             => 'Review URL',
							'name'              => 'review_url',
							'type'              => 'url',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
						),
						array(
							'key'               => 'field_review_author',
							'label'             => 'Author',
							'name'              => 'review_author',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_author_url',
							'label'             => 'Author URL',
							'name'              => 'author_url',
							'type'              => 'url',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
						),
						array(
							'key'               => 'field_review_date',
							'label'             => 'Date',
							'name'              => 'review_date',
							'type'              => 'date_picker',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'display_format'    => 'Y-m-d',
							'return_format'     => 'Y-m-d',
							'first_day'         => 1,
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_reviews',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'side',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
			// Reviews - Review Place.
			acf_add_local_field_group(
				array(
					'key'                   => 'group_review_place',
					'title'                 => 'Place',
					'fields'                => array(
						array(
							'key'               => 'field_review_place',
							'label'             => '',
							'name'              => 'review_place',
							'type'              => 'post_object',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'post_type'         => array(
								0 => 'wplocal_places',
							),
							'taxonomy'          => '',
							'allow_null'        => 0,
							'multiple'          => 0,
							'return_format'     => 'id',
							'ui'                => 1,
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'wplocal_reviews',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'side',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
		}
		if ( function_exists( 'acf_update_setting' ) ) {
			$the_options = Wplocalplus_Lite::wplocalplus_lite_get_settings();
			acf_update_setting( 'google_api_key', $the_options['google_maps_api_key'] );
		}
	}
}
