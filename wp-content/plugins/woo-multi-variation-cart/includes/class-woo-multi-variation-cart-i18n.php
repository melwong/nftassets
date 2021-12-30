<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       awais300@gmail.com
 * @since      1.0.0
 *
 * @package    Woo_Multi_Variation_Cart
 * @subpackage Woo_Multi_Variation_Cart/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woo_Multi_Variation_Cart
 * @subpackage Woo_Multi_Variation_Cart/includes
 * @author     Awais <awais@gmail.com>
 */
class Woo_Multi_Variation_Cart_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-multi-variation-cart',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
