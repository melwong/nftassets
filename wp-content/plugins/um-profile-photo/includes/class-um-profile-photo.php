<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       awais300@gmail.com
 * @since      1.0.0
 *
 * @package    Um_Profile_Photo
 * @subpackage Um_Profile_Photo/includes
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
 * @since      1.0.0
 * @package    Um_Profile_Photo
 * @subpackage Um_Profile_Photo/includes
 * @author     Awais <awais300@gmail.com>
 */
class Um_Profile_Photo {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Um_Profile_Photo_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'UM_PROFILE_PHOTO_VERSION' ) ) {
			$this->version = UM_PROFILE_PHOTO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'um-profile-photo';

		$this->set_global_constants();
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
	 * - Um_Profile_Photo_Loader. Orchestrates the hooks of the plugin.
	 * - Um_Profile_Photo_i18n. Defines internationalization functionality.
	 * - Um_Profile_Photo_Admin. Defines all hooks for the admin area.
	 * - Um_Profile_Photo_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/*Third Party*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/tinify/lib/Tinify/Exception.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/tinify/lib/Tinify/ResultMeta.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/tinify/lib/Tinify/Result.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/tinify/lib/Tinify/Source.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/tinify/lib/Tinify/Client.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/tinify/lib/Tinify.php';

		/*$path = 'D:\Web\laragon\www\testwp/wp-content/uploads/ultimatemember/15/stream_photo_232a71d2_537c1d21987e90a3fa189d85ff6a0655649d586e.jpg';
		Tinify\setKey("fM8RPpr62c7czkbM1yCcnbRWSSgN0yXm");
		$source = \Tinify\fromFile($path);
		$source->toFile($path);
		exit('testing');*/
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-um-profile-photo-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-um-profile-photo-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-um-profile-photo-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-um-profile-photo-public.php';

		$this->loader = new Um_Profile_Photo_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Um_Profile_Photo_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Um_Profile_Photo_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Um_Profile_Photo_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Um_Profile_Photo_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'plugins_loaded', $plugin_public, 'um_check_dependent_plugin');
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'head_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'um_registration_complete', $plugin_public, 'um_registration_complete', 10, 2 );
		//$this->loader->add_action( 'init', $plugin_public, 'um_registration_complete', 10, 2 );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Um_Profile_Photo_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Set global constants
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function set_global_constants() {
		//Best result so far are with 40x40 with 35% quality
		//With TinyJPG implementation 60x60 with 50% quality giving good results too

		// Images width and height after processing
		define('IMAGE_WIDTH', '65');
		define('IMAGE_HEIGHT', '65');

		// Image quality value range is between 1 to 100
		define('IMAGE1_QUALITY', '75');
		//define('IMAGE1_QUALITY', '50');
		define('IMAGE2_QUALITY', '45');
		define('IMAGE3_QUALITY', '40');
		define('IMAGE4_QUALITY', '35');

		// Do not change this unless you know what you are doing
		define('UM_FIELD_NAME', 'um_profile_upload_image');
	}
}
