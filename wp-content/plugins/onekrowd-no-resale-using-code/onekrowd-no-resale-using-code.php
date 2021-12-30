<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              1krowd.io
 * @since             1.0.0
 * @package           Onekrowd_No_Resale_Using_Code
 *
 * @wordpress-plugin
 * Plugin Name:       OneKrowd No Resale - Using Photo and Unique Code
 * Plugin URI:        1krowd.io
 * Description:       Prevent ticket resale by using profile photo and unique code in the ticket QR code.
 * Version:           1.0.0
 * Author:            1Krowd
 * Author URI:        1krowd.io
 * License:           Copyright 1Krowd. All rights reserved
 * License URI:       https://1krowd.io
 * Text Domain:       onekrowd-no-resale-using-code
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
define( 'ONEKROWD_NO_RESALE_USING_CODE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-onekrowd-no-resale-using-code-activator.php
 */
function activate_onekrowd_no_resale_using_code() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-onekrowd-no-resale-using-code-activator.php';
	Onekrowd_No_Resale_Using_Code_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-onekrowd-no-resale-using-code-deactivator.php
 */
function deactivate_onekrowd_no_resale_using_code() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-onekrowd-no-resale-using-code-deactivator.php';
	Onekrowd_No_Resale_Using_Code_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_onekrowd_no_resale_using_code' );
register_deactivation_hook( __FILE__, 'deactivate_onekrowd_no_resale_using_code' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-onekrowd-no-resale-using-code.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_onekrowd_no_resale_using_code() {

	$plugin = new Onekrowd_No_Resale_Using_Code();
	$plugin->run();

}
run_onekrowd_no_resale_using_code();
