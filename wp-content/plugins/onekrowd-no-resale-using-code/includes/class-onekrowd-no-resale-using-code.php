<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       1krowd.io
 * @since      1.0.0
 *
 * @package    Onekrowd_No_Resale_Using_Code
 * @subpackage Onekrowd_No_Resale_Using_Code/includes
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
 * @package    Onekrowd_No_Resale_Using_Code
 * @subpackage Onekrowd_No_Resale_Using_Code/includes
 * @author     1Krowd <info@1krowd.io>
 */
class Onekrowd_No_Resale_Using_Code {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Onekrowd_No_Resale_Using_Code_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'ONEKROWD_NO_RESALE_USING_CODE_VERSION' ) ) {
			$this->version = ONEKROWD_NO_RESALE_USING_CODE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'onekrowd-no-resale-using-code';

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
	 * - Onekrowd_No_Resale_Using_Code_Loader. Orchestrates the hooks of the plugin.
	 * - Onekrowd_No_Resale_Using_Code_i18n. Defines internationalization functionality.
	 * - Onekrowd_No_Resale_Using_Code_Admin. Defines all hooks for the admin area.
	 * - Onekrowd_No_Resale_Using_Code_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-onekrowd-no-resale-using-code-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-onekrowd-no-resale-using-code-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-onekrowd-no-resale-using-code-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-onekrowd-no-resale-using-code-public.php';

		$this->loader = new Onekrowd_No_Resale_Using_Code_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Onekrowd_No_Resale_Using_Code_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Onekrowd_No_Resale_Using_Code_i18n();

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

		$plugin_admin = new Onekrowd_No_Resale_Using_Code_Admin( $this->get_plugin_name(), $this->get_version() );

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

		$plugin_public = new Onekrowd_No_Resale_Using_Code_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    Onekrowd_No_Resale_Using_Code_Loader    Orchestrates the hooks of the plugin.
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
	 * Convert user's photo from binary to hex and then encrypt the hex string. Add that encrypted string with the ticket unique code number. The result shall be the string stored inside the QR code
	 *
	 * @since     1.0.0
	 * @return    string    the image in hex.
	 */
	public function generate_qr_number_old( $user_id ) {

		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/ultimatemember/' . $user_id;
		 
		if ( $user_id ) {
			$photo_file = get_user_meta( $user_id, 'photo_id' , true );
			 
			if ( ! empty( $photo_file ) ){
				
				$photo_file_path = $upload_dir . '/' . $photo_file;
				
				//Pull the data from your file into a variable. Using the 'rb' tag tells it to read as binary.
				$data = fopen($photo_file, 'rb');

				$size = filesize($photo_file);

				$contents = fread($data, $size);

				fclose ($data);

				echo "data is " . $data;
				echo "<br />";
				echo "content is " . $contents;
				echo "<br />";
				echo "image path is " . $photo_file_path;
				echo "<br />";
				
				$bintohex = bin2hex($contents);

				echo "bintohex is " . $bintohex;
				echo "<br />";
				
				$ticket_code = mt_rand(1955296618947165, 9998614657298076);	//Randomly generate a 16-digit unique ticket code
				
				$final_code = $contents . "|" . $ticket_code;
				
				echo "final code is " . $final_code;
				
				return $final_code;
				
/* 				$list = 'enqueued';
				
				//To check if the wallet javascript files have been loaded
				if ( wp_script_is( 'bip39', $list ) && wp_script_is( 'bip44-constants', $list ) && wp_script_is( 'hdkey', $list ) && wp_script_is( 'walletgenerator', $list ) ) {
					
					//Execute generateWallet function using the wallet js scripts
					echo '<script type="text/javascript">
						var walletData = generateWallet();
						document.getElementById("wallet_address").value = walletData[0];
						document.getElementById("private_key").value = walletData[1];
						document.getElementById("mnemonic_phrase").value = walletData[2];
					</script>';
					
				} else {

					//Load scripts that generate a digital wallet - address, mnemonic words and private key
					wp_enqueue_script( 'bip39', get_template_directory_uri() . '/js/bip39.min.js', array (), null, false);
					wp_enqueue_script( 'bip44-constants', get_template_directory_uri() . '/js/bip44-constants.min.js', array (), null, false);
					wp_enqueue_script( 'hdkey', get_template_directory_uri() . '/js/hdkey.min.js', array (), null, false);
					wp_enqueue_script( 'walletgenerator', get_template_directory_uri() . '/js/walletgenerator.js', array (),null, false);	
				} */

			}
		}
	}
	//add_action('onekrowd_generate_qr_number', 'generate_qr_number', 10, 1);
	
/* 	public function renym_content_footer_note( $content ) {
		$content .= '<footer class="renym-content-footer">Thank you for reading this tutorial. Maybe next time I will let you buy me a coffee! For more WordPress tutorials visit our <a href="http://wpexplorer.com/blog" title="WPExplorer Blog">Blog</a></footer>';
		return $content;
	}
	add_filter( 'the_content', 'renym_content_footer_note' ); */

}
