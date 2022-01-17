<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://club.wpeka.com
 * @since      1.0
 *
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/includes
 * @author     WPeka <support@wpeka.com>
 */
class Wplocalplus_Lite {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      Wplocalplus_Lite_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0
	 */
	public function __construct() {
		if ( defined( 'WPLOCALPLUS_LITE_VERSION' ) ) {
			$this->version = WPLOCALPLUS_LITE_VERSION;
		} else {
			$this->version = '1.4.5';
		}
		$this->plugin_name = 'wplocalplus-lite';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wplocalplus_Lite_Loader. Orchestrates the hooks of the plugin.
	 * - Wplocalplus_Lite_I18n. Defines internationalization functionality.
	 * - Wplocalplus_Lite_Admin. Defines all hooks for the admin area.
	 * - Wplocalplus_Lite_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wplocalplus-lite-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wplocalplus-lite-i18n.php';

		/**
		 * The class responsible for the actions and filters of the
		 * ACF plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/libraries/acf/acf.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/admin/class-wplocalplus-lite-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wplocalplus-lite-public.php';

		$this->loader = new Wplocalplus_Lite_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wplocalplus_Lite_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wplocalplus_Lite_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wplocalplus_Lite_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wplocalplus_lite_admin_menu' );
		$this->loader->add_action( 'init', $plugin_admin, 'wplocalplus_lite_register_custom_post_type' );
		$this->loader->add_action( 'init', $plugin_admin, 'wplocalplus_lite_register_custom_taxonomies' );
		$this->loader->add_action( 'acf/init', $plugin_admin, 'wplocalplus_lite_add_acf_fields' );
		$this->loader->add_filter( 'acf/input/meta_box_priority', $plugin_admin, 'wplocalplus_lite_filter_acf_priority', 10, 2 );
		$this->loader->add_action( 'manage_edit-wplocal_places_columns', $plugin_admin, 'wplocalplus_lite_manage_edit_places_columns' );
		$this->loader->add_action( 'manage_edit-wplocal_reviews_columns', $plugin_admin, 'wplocalplus_lite_manage_edit_reviews_columns' );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'wplocalplus_lite_places_manage_posts_custom_columns' );
		$this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'wplocalplus_lite_reviews_manage_posts_custom_columns' );
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'wplocalplus_lite_restrict_manage_posts', 10, 2 );
		$this->loader->add_filter( 'parse_query', $plugin_admin, 'wplocalplus_lite_reviews_posts_filter', 10, 1 );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'wplocalplus_lite_row_actions', 10, 2 );
		$this->loader->add_filter( 'wplocal_place_type_row_actions', $plugin_admin, 'wplocalplus_lite_taxonomy_row_actions', 10, 2 );
		$this->loader->add_filter( 'wplocal_location_row_actions', $plugin_admin, 'wplocalplus_lite_taxonomy_row_actions', 10, 2 );
		$this->loader->add_action( 'save_post', $plugin_admin, 'wplocalplus_lite_places_save_custom_post' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'wplocalplus_lite_reviews_save_custom_post' );
		$this->loader->add_action( 'wp_trash_post', $plugin_admin, 'wplocalplus_lite_trash_custom_post' );
		$this->loader->add_action( 'wp_trash_post', $plugin_admin, 'wplocalplus_lite_trash_custom_review_post' );
		$this->loader->add_action( 'delete_post', $plugin_admin, 'wplocalplus_lite_delete_custom_post' );
		$this->loader->add_action( 'untrash_post', $plugin_admin, 'wplocalplus_lite_untrash_custom_post' );
		$this->loader->add_action( 'untrash_post', $plugin_admin, 'wplocalplus_lite_untrash_custom_review_post' );
		$this->loader->add_action( 'init', $plugin_admin, 'wplocalplus_lite_register_block_type' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'wplocalplus_lite_highlight_menu' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wplocalplus_lite_add_metaboxes' );
		$this->loader->add_filter( 'plugin_action_links_' . WPLOCALPLUS_LITE_PLUGIN_BASENAME, $plugin_admin, 'wplocalplus_lite_plugin_action_links' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wplocalplus_Lite_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wplocalplus_lite_make_google_map', $plugin_public, 'wplocalplus_lite_make_google_map', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wplocalplus_lite_place_details', $plugin_public, 'wplocalplus_lite_place_details' );
		$this->loader->add_action( 'wp_ajax_nopriv_wplocalplus_lite_place_details', $plugin_public, 'wplocalplus_lite_place_details' );
		$this->loader->add_action( 'wplocalplus_lite_after_single_place_content', $plugin_public, 'wplocalplus_lite_after_single_place_content', 10, 3 );
		$this->loader->add_filter( 'wplocalplus_lite_filter_by_location', $plugin_public, 'wplocalplus_lite_filter_by_location', 10, 2 );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0
	 * @return    Wplocalplus_Lite_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the default settings.
	 *
	 * @since 1.0
	 * @param string $key Key for the setting.
	 * @return array|mixed
	 */
	public static function wplocalplus_lite_get_default_settings( $key = '' ) {
		$settings = array(
			'show_map'            => '0',
			'google_maps_api_key' => 'ABQIAAAAXuX847HLKfJC60JtneDOUhQ8oGF9gkOSJpYWLmRvGTmYZugFaxRX7q0DDCWBSdfC1tIHIXIZqTPM-A',
		);
		return '' !== $key ? $settings[ $key ] : $settings;
	}

	/**
	 * Retrieve plugin settings.
	 *
	 * @since 1.0
	 * @return array|mixed
	 */
	public static function wplocalplus_lite_get_settings() {
		$settings = self::wplocalplus_lite_get_default_settings();
		$settings = wp_parse_args( get_option( WPLOCALPLUS_LITE_SETTINGS_FIELD ), $settings );
		update_option( WPLOCALPLUS_LITE_SETTINGS_FIELD, $settings );
		return $settings;
	}

	/**
	 * Returns sanitised content based on field-specific rules defined here
	 * used for both read AND write operations.
	 *
	 * @since  1.0
	 * @param string $key Key for the setting.
	 * @param string $value Value for the setting.
	 * @return int|string
	 */
	public static function wplocalplus_lite_sanitise_settings( $key, $value ) {
		switch ( $key ) {
			case 'show_map':
				if ( 'on' === $value ) {
					return 1;
				} else {
					return 0;
				}
			default:
				return sanitize_text_field( $value );
		}
	}

}
