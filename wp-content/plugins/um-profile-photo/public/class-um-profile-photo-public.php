<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       awais300@gmail.com
 * @since      1.0.0
 *
 * @package    Um_Profile_Photo
 * @subpackage Um_Profile_Photo/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Um_Profile_Photo
 * @subpackage Um_Profile_Photo/public
 * @author     Awais <awais300@gmail.com>
 */
class Um_Profile_Photo_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Um_Profile_Photo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Um_Profile_Photo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/um-profile-photo-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Um_Profile_Photo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Um_Profile_Photo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/um-profile-photo-public.js', array( 'jquery' ), $this->version, false );

		$local_vars = array(
			'cimage' => plugins_url('um-profile-photo/vendor/cimage/'),
			'iw' => IMAGE_WIDTH,
			'ih' => IMAGE_HEIGHT,
			'iq1' => IMAGE1_QUALITY,
			'iq2' => IMAGE2_QUALITY,
			'iq3' => IMAGE3_QUALITY,
			'iq4' => IMAGE4_QUALITY
		);
    	wp_localize_script( $this->plugin_name, 'LOCAL_VAR', $local_vars );
	}

	public function head_enqueue_styles() {
		?>
        <style>
            .pp-img-inner {
                width: <?php echo IMAGE_WIDTH * 3 ?>px;
            }
        </style>
    <?php
	}

	public function um_registration_complete($user_id, $args = '') {
		$all_user_meta = get_user_meta($user_id);
		$img_url = $all_user_meta['img_data'][0];
		$img_name = $all_user_meta[UM_FIELD_NAME][0];


		$ext = strtolower(substr(strrchr($img_name, '.'), 1));
		if(strtolower($ext) != 'jpg' || strtolower($ext) != 'jpeg') {
			$path = wp_upload_dir()['basedir'] . "/ultimatemember/{$user_id}/{$img_name}";
			$file_name = pathinfo($path, PATHINFO_FILENAME);
			$new_path = wp_upload_dir()['basedir'] . "/ultimatemember/{$user_id}/{$file_name}.jpg";
			$img_name = $file_name . '.jpg';
			if(rename($path, $new_path)) {
				update_user_meta( $user_id, UM_FIELD_NAME, $img_name);
			}
		}

		$query = parse_url($img_url);
		parse_str($query['query'], $result);

		/*$result['q'] = '35';
		$result['wdith'] = '40';
		$result['height'] = '40';*/

		//Get and save uploaded image
		$new_url = plugins_url() . "/um-profile-photo/vendor/cimage/imgd.php?src={$user_id}/{$img_name}&width={$result['width']}&height={$result['height']}&crop-to-fit&q={$result['q']}&f={$result['f']}&nc&save-as=jpg";
		$new_path = wp_upload_dir()['basedir'] . "/ultimatemember/{$user_id}/$img_name";
		$response = wp_remote_get($new_url);
		file_put_contents($new_path, wp_remote_retrieve_body($response));

		try {
	    	Tinify\setKey("fM8RPpr62c7czkbM1yCcnbRWSSgN0yXm");
			$source = \Tinify\fromFile($new_path);
			$source->toFile($new_path);
		} catch(Exception $e) {
		    wp_die('Something went wrong at tinyJPG API. Please try again later.');
		}

		/*$img = imagecreatefromjpeg($new_path);
		imagejpeg($img, $new_path, $result['q']);
		imagedestroy($img);*/
	}

	public function um_check_dependent_plugin() {
		if (! class_exists( 'UM_Functions' )) {
			if(is_admin()) {
				add_action( 'admin_notices', array($this, 'um_admin_notice') );
			}
		}
	}

	public function um_admin_notice() {
		  ?>
	    <div class="notice notice-warning is-dismissible">
	        <p><?php _e( 'Ultimate Member plugin is required for photo processing.', $this->plugin_name ); ?></p>
	    </div>
    <?php
	}


}