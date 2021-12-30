<?php
/*
Plugin Name: Shipping Icons and Descriptions for WooCommerce
Plugin URI: https://wpfactory.com/item/shipping-icons-descriptions-woocommerce/
Description: Frontend icons and description for WooCommerce shipping methods.
Version: 2.1.0
Author: WP Design Duo
Author URI: https://wpdesignduo.com/
Text Domain: shipping-icons-descriptions-woocommerce
Domain Path: /langs
Copyright: © 2019 WP Design Duo
WC tested up to: 3.8
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if (
	! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) &&
	! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
) {
	return;
}

if ( 'shipping-icons-descriptions-woocommerce.php' === basename( __FILE__ ) ) {
	// Check if Pro is active, if so then return
	$plugin = 'shipping-icons-descriptions-woocommerce-pro/shipping-icons-descriptions-woocommerce-pro.php';
	if (
		in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
		( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		return;
	}
}

if ( ! class_exists( 'Alg_WC_Shipping_Icons_Descs' ) ) :

/**
 * Main Alg_WC_Shipping_Icons_Descs Class
 *
 * @class   Alg_WC_Shipping_Icons_Descs
 * @version 2.1.0
 * @since   1.0.0
 */
final class Alg_WC_Shipping_Icons_Descs {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '2.1.0';

	/**
	 * @var   Alg_WC_Shipping_Icons_Descs The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Shipping_Icons_Descs Instance
	 *
	 * Ensures only one instance of Alg_WC_Shipping_Icons_Descs is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Alg_WC_Shipping_Icons_Descs - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Shipping_Icons_Descs Constructor.
	 *
	 * @version 2.1.0
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {

		// Set up localisation
		load_plugin_textdomain( 'shipping-icons-descriptions-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Pro
		if ( 'shipping-icons-descriptions-woocommerce-pro.php' === basename( __FILE__ ) ) {
			require_once( 'includes/pro/class-alg-wc-sid-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin stuff
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_shipping_icons_descs' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'shipping-icons-descriptions-woocommerce.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a href="https://wpfactory.com/item/shipping-icons-descriptions-woocommerce/">' .
				__( 'Unlock All', 'shipping-icons-descriptions-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 2.0.4
	 * @since   1.0.0
	 */
	function includes() {
		// Core
		$this->core = require_once( 'includes/class-alg-wc-sid-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 2.0.4
	 * @since   2.0.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		require_once( 'includes/settings/class-alg-wc-sid-settings-section.php' );
		$this->settings = array();
		$this->settings['icons']        = require_once( 'includes/settings/class-alg-wc-sid-settings-icons.php' );
		$this->settings['descriptions'] = require_once( 'includes/settings/class-alg-wc-sid-settings-descs.php' );
		// Version updated
		if ( get_option( 'alg_wc_shipping_icons_descs_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * version_updated.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function version_updated() {
		update_option( 'alg_wc_shipping_icons_descs_version', $this->version );
	}

	/**
	 * Add Shipping Icons and Descriptions settings tab to WooCommerce settings.
	 *
	 * @version 2.0.4
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'includes/settings/class-alg-wc-settings-sid.php' );
		return $settings;
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( ! function_exists( 'alg_wc_shipping_icons_descs' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Shipping_Icons_Descs to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_Shipping_Icons_Descs
	 */
	function alg_wc_shipping_icons_descs() {
		return Alg_WC_Shipping_Icons_Descs::instance();
	}
}

alg_wc_shipping_icons_descs();
