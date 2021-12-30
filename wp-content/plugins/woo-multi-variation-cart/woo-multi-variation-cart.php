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
 * @package           Woo_Multi_Variation_Cart
 *
 * @wordpress-plugin
 * Plugin Name:       Woo Multi Variation to Cart
 * Plugin URI:        awais300@gmail.com
 * Description:       Add multi WooCommerce variable products to cart at once.
 * Version:           1.0.0
 * Author:            Awais
 * Author URI:        awais300@gmail.com
 * Text Domain:       woo-multi-variation-cart
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
define( 'WOO_MULTI_VARIATION_CART_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-multi-variation-cart-activator.php
 */
function activate_woo_multi_variation_cart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-multi-variation-cart-activator.php';
	Woo_Multi_Variation_Cart_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-multi-variation-cart-deactivator.php
 */
function deactivate_woo_multi_variation_cart() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-multi-variation-cart-deactivator.php';
	Woo_Multi_Variation_Cart_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_multi_variation_cart' );
register_deactivation_hook( __FILE__, 'deactivate_woo_multi_variation_cart' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-multi-variation-cart.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_multi_variation_cart() {

	$plugin = new Woo_Multi_Variation_Cart();
	$plugin->run();

}
run_woo_multi_variation_cart();
