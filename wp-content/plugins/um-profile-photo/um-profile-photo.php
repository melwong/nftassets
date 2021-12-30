<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              awais300@gmail.com
 * @since             1.0.0
 * @package           Um_Profile_Photo
 *
 * @wordpress-plugin
 * Plugin Name:       UM Profile Photo
 * Plugin URI:        awais300@gmail.com
 * Description:       Extend Ultimate Memeber's Image Field. Ultimate Memeber is required to use this Plugin.
 * Version:           1.0.0
 * Author:            Awais
 * Author URI:        awais300@gmail.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       um-profile-photo
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'UM_PROFILE_PHOTO_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-um-profile-photo-activator.php
 */
function activate_um_profile_photo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-um-profile-photo-activator.php';
	Um_Profile_Photo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-um-profile-photo-deactivator.php
 */
function deactivate_um_profile_photo() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-um-profile-photo-deactivator.php';
	Um_Profile_Photo_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_um_profile_photo' );
register_deactivation_hook( __FILE__, 'deactivate_um_profile_photo' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-um-profile-photo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_um_profile_photo() {

	$plugin = new Um_Profile_Photo();
	$plugin->run();

}
run_um_profile_photo();


//add_action("um_upload_stream_image_process", 'testing', 8, 6);
// function testing($image_path, $src, $key, $user_id, $coord, $crop ) {
//     echo $image_path;
//     echo "<br/>";
//     echo $src;
//     echo "<br/>";
//     echo $key;
//     echo "<br/>";
//     echo 'u: ' . $user_id;
//     echo "<br/>";
//     echo $coord;
//     echo "<br/>";
//     dd($crop);
//     echo "<br/>";
//     exit('dddddddddddddddddddd');
// }

