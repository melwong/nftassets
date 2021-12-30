<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       1krowd.io
 * @since      1.0.0
 *
 * @package    Onekrowd_No_Resale_Using_Code
 * @subpackage Onekrowd_No_Resale_Using_Code/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Onekrowd_No_Resale_Using_Code
 * @subpackage Onekrowd_No_Resale_Using_Code/includes
 * @author     1Krowd <info@1krowd.io>
 */
class Onekrowd_No_Resale_Using_Code_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'onekrowd-no-resale-using-code',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
