<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Fired during plugin activation
 *
 * @link       https://www.thedotstore.com/
 * @since      1.0.0
 *
 * @package    woo-quick-cart-for-multiple-variations
 * @subpackage woo-quick-cart-for-multiple-variations/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    woo-quick-cart-for-multiple-variations
 * @subpackage woo-quick-cart-for-multiple-variations/includes
 * @author     Thedotstore <wordpress@thedotstore.com>
 */
class Variant_Purchase_Extended_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		  global $wpdb;	
		  set_transient( '_welcome_screen_WC_Variable_Products_Purchase_Extended_activation_redirect_data', true, 30 );

        /**
         * This code updates all the product to auto apply the new template.
         */
        $products = get_posts(
            array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'fields' => 'ids',
            )
        );
        foreach ($products as $product_id) {
            update_post_meta($product_id,'product_visiblity_option','yes');
        }
	}
}
