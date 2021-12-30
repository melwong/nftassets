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
 * @package    Um_Profile_Photo
 * @subpackage Um_Profile_Photo/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Um_Profile_Photo
 * @subpackage Um_Profile_Photo/includes
 * @author     Awais <awais300@gmail.com>
 */
class Um_Profile_Photo_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'um-profile-photo',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
