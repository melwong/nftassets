<?php
/**
 * Custom Shipping Methods for WooCommerce - General Section Settings
 *
 * @version 1.5.2
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Custom_Shipping_Methods_Settings_General' ) ) :

class Alg_WC_Custom_Shipping_Methods_Settings_General extends Alg_WC_Custom_Shipping_Methods_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'custom-shipping-methods-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.2
	 * @since   1.0.0
	 * @todo    [dev] Icons & Descriptions: (now) add "placeholders" list
	 * @todo    [dev] Icons & Descriptions: (maybe) make it always visible (and disabled) in each method's settings
	 * @todo    [dev] maybe set `alg_wc_custom_shipping_methods_do_trigger_checkout_update` to `yes` by default
	 * @todo    [dev] (maybe) make "Advanced" settings (i.e. "Custom return URL") optional
	 * @todo    [feature] admin `method_description`
	 */
	function get_settings() {

		$plugin_settings = array(
			array(
				'title'    => __( 'Custom Shipping Methods Options', 'custom-shipping-methods-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_custom_shipping_methods_plugin_options',
			),
			array(
				'title'    => __( 'Custom Shipping Methods', 'custom-shipping-methods-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'custom-shipping-methods-for-woocommerce' ) . '</strong>',
				'desc_tip' => __( 'Add custom shipping methods to WooCommerce.', 'custom-shipping-methods-for-woocommerce' ),
				'id'       => 'alg_wc_custom_shipping_methods_plugin_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_custom_shipping_methods_plugin_options',
			),
		);

		$admin_settings = array(
			array(
				'title'    => __( 'Admin Settings', 'custom-shipping-methods-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_custom_shipping_methods_admin_options',
				'desc'     => sprintf( __( 'Visit %s to set each method\'s options.', 'custom-shipping-methods-for-woocommerce' ),
					'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping' ) . '">' .
						__( 'WooCommerce > Settings > Shipping', 'custom-shipping-methods-for-woocommerce' ) . '</a>' ),
			),
			array(
				'title'    => __( 'Admin title', 'custom-shipping-methods-for-woocommerce' ),
				'id'       => 'alg_wc_custom_shipping_methods_admin_title',
				'default'  => __( 'Custom shipping', 'custom-shipping-methods-for-woocommerce' ),
				'type'     => 'text',
				'css'      => 'width:100%',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_custom_shipping_methods_admin_options',
			),
		);

		$frontend_settings = array(
			array(
				'title'    => __( 'Frontend Settings', 'custom-shipping-methods-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_custom_shipping_methods_frontend_options',
			),
			array(
				'title'    => __( 'Trigger checkout update', 'custom-shipping-methods-for-woocommerce' ),
				'desc'     => __( 'Enable', 'custom-shipping-methods-for-woocommerce' ),
				'desc_tip' => __( 'Will trigger the checkout update on any input change. This is useful if you are using cost calculation by distance to the customer.', 'custom-shipping-methods-for-woocommerce' ),
				'id'       => 'alg_wc_custom_shipping_methods_do_trigger_checkout_update',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Add to zero cost', 'custom-shipping-methods-for-woocommerce' ),
				'desc'     => __( 'Enable', 'custom-shipping-methods-for-woocommerce' ),
				'desc_tip' => __( 'Will add text to custom shipping cost on frontend in case if it\'s zero (i.e. free).', 'custom-shipping-methods-for-woocommerce' ),
				'id'       => 'alg_wc_custom_shipping_methods_do_replace_zero_cost',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'desc_tip' => __( 'Text to add to zero cost.', 'custom-shipping-methods-for-woocommerce' ) . ' ' .
					__( 'Ignored if "Add to zero cost" option above is disabled.', 'custom-shipping-methods-for-woocommerce' ),
				'desc'     => '<p>' . sprintf( __( 'E.g.: %s', 'custom-shipping-methods-for-woocommerce' ),
					'<code>' . esc_html( ': <span style="color:green;font-weight:bold;">Free!</span>' ) . '</code>' ) . '</p>',
				'id'       => 'alg_wc_custom_shipping_methods_replace_zero_cost_text',
				'default'  => '',
				'type'     => 'text',
				'alg_wc_csm_raw' => true,
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_custom_shipping_methods_frontend_options',
			),
		);

		$icon_and_desc_settings = array(
			array(
				'title'    => __( 'Icons & Descriptions Settings', 'custom-shipping-methods-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_custom_shipping_methods_icon_and_desc_options',
				'desc'     => sprintf( __( 'Visit %s to set each method\'s icon and description.', 'custom-shipping-methods-for-woocommerce' ),
					'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping' ) . '">' .
						__( 'WooCommerce > Settings > Shipping', 'custom-shipping-methods-for-woocommerce' ) . '</a>' ),
			),
			array(
				'title'    => __( 'Icons & Descriptions', 'custom-shipping-methods-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'custom-shipping-methods-for-woocommerce' ) . '</strong>',
				'desc_tip' => apply_filters( 'alg_wc_custom_shipping_methods_settings', sprintf( __( 'You will need %s plugin to set icons and descriptions.', 'custom-shipping-methods-for-woocommerce' ),
					'<a target="_blank" href="https://wpfactory.com/item/custom-shipping-methods-for-woocommerce/">' .
						__( 'Custom Shipping Methods for WooCommerce Pro', 'custom-shipping-methods-for-woocommerce' ) . '</a>' ) ),
				'id'       => 'alg_wc_custom_shipping_methods_icon_desc_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_custom_shipping_methods_settings', array( 'disabled' => 'disabled' ) ),
			),
			array(
				'title'    => __( 'Icon template', 'custom-shipping-methods-for-woocommerce' ),
				'id'       => 'alg_wc_custom_shipping_methods_icon_template',
				'default'  => '<img style="display:inline;" src="%icon_url%">',
				'type'     => 'textarea',
				'css'      => 'width:100%',
				'alg_wc_csm_raw' => true,
			),
			array(
				'title'    => __( 'Description template', 'custom-shipping-methods-for-woocommerce' ),
				'id'       => 'alg_wc_custom_shipping_methods_desc_template',
				'default'  => '<p style="font-size:small;font-style:italic;">%desc_text%</p>',
				'type'     => 'textarea',
				'css'      => 'width:100%',
				'alg_wc_csm_raw' => true,
			),
			array(
				'title'    => __( 'Final template', 'custom-shipping-methods-for-woocommerce' ),
				'id'       => 'alg_wc_custom_shipping_methods_icon_desc_template',
				'default'  => '%icon%%label%%desc%',
				'type'     => 'textarea',
				'css'      => 'width:100%',
				'alg_wc_csm_raw' => true,
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_custom_shipping_methods_icon_and_desc_options',
			),
		);

		return array_merge( $plugin_settings, $admin_settings, $frontend_settings, $icon_and_desc_settings );
	}

}

endif;

return new Alg_WC_Custom_Shipping_Methods_Settings_General();
