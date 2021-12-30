<?php
/**
 * Shipping Icons and Descriptions for WooCommerce - Descriptions Section Settings
 *
 * @version 2.0.4
 * @since   2.0.0
 * @author  WP Design Duo
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Shipping_Icons_Descs_Settings_Descs' ) ) :

class Alg_WC_Shipping_Icons_Descs_Settings_Descs extends Alg_WC_Shipping_Icons_Descs_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function __construct() {
		$this->id   = 'descriptions';
		$this->desc = __( 'Descriptions', 'shipping-icons-descriptions-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.4
	 * @since   2.0.0
	 * @todo    [dev] (maybe) add option to use instances and methods simultaneously (i.e. merge) (same in "Icons" section)
	 */
	function get_settings() {
		$use_shipping_instance = ( 'yes' === apply_filters( 'alg_wc_shipping_icons_descs', 'no', 'use_shipping_instance', array( 'icon_or_description' => 'description' ) ) );
		$shipping_methods      = ( $use_shipping_instance ? alg_wc_shipping_icons_descs()->core->get_shipping_methods_instances() : WC()->shipping()->get_shipping_methods() );
		$shipping_methods      = apply_filters( 'alg_wc_shipping_icons_descs_shipping_methods', $shipping_methods, $use_shipping_instance, 'descriptions' );
		$example_desc          = __( 'Your shipping description.', 'shipping-icons-descriptions-woocommerce' );
		$examples              = array(
			'<br><small>'                                . $example_desc . '</small>',
			'<p style="color:gray;font-style:italic;">'  . $example_desc . '</p>',
			'<span class="alg_wc_shipping_description">' . $example_desc . '</span>'
		);
		$settings = array(
			array(
				'title'    => __( 'Shipping Descriptions', 'shipping-icons-descriptions-woocommerce' ),
				'type'     => 'title',
				'desc'     => __( 'Here you can add any text (i.e. description) for shipping methods on cart and checkout pages.', 'shipping-icons-descriptions-woocommerce' ),
				'id'       => 'alg_wc_shipping_descriptions_options',
			),
			array(
				'title'    => __( 'Shipping descriptions', 'shipping-icons-descriptions-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'shipping-icons-descriptions-woocommerce' ) . '</strong>',
				'desc_tip' => __( 'WooCommerce shipping descriptions.', 'shipping-icons-descriptions-woocommerce' ),
				'id'       => 'alg_wc_shipping_descriptions_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Description position', 'shipping-icons-descriptions-woocommerce' ),
				'id'       => 'alg_wc_shipping_descriptions_position',
				'default'  => 'after',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'after'  => __( 'After label', 'shipping-icons-descriptions-woocommerce' ),
					'before' => __( 'Before label', 'shipping-icons-descriptions-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Description visibility', 'shipping-icons-descriptions-woocommerce' ),
				'id'       => 'alg_wc_shipping_descriptions_visibility',
				'default'  => 'both',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'both'          => __( 'On both cart and checkout pages', 'shipping-icons-descriptions-woocommerce' ),
					'cart_only'     => __( 'Only on cart page', 'shipping-icons-descriptions-woocommerce' ),
					'checkout_only' => __( 'Only on checkout page', 'shipping-icons-descriptions-woocommerce' ),
				),
				'desc_tip' => __( 'Possible values: on both cart and checkout pages; only on cart page; only on checkout page', 'shipping-icons-descriptions-woocommerce' ),
				'desc'     => apply_filters( 'alg_wc_shipping_icons_descs', sprintf(
					'<p>You will need <a target="_blank" href="%s">Shipping Icons and Descriptions for WooCommerce Pro</a> plugin to change descriptions visibility on pages.</p>',
						'https://wpfactory.com/item/shipping-icons-descriptions-woocommerce/' ), 'settings' ),
				'custom_attributes' => apply_filters( 'alg_wc_shipping_icons_descs', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'title'    => __( 'Order details', 'shipping-icons-descriptions-woocommerce' ),
				'desc'     => __( 'Add', 'shipping-icons-descriptions-woocommerce' ),
				'desc_tip' => __( 'Enable this if you want shipping descriptions to be added to order details ("Order received" page, emails etc.).', 'shipping-icons-descriptions-woocommerce' ),
				'id'       => 'alg_wc_shipping_descriptions_add_to_order_details',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Apply shortcodes', 'shipping-icons-descriptions-woocommerce' ),
				'desc'     => __( 'Enable', 'shipping-icons-descriptions-woocommerce' ),
				'desc_tip' => __( 'Check this if you want shortcodes to be enabled in descriptions.', 'shipping-icons-descriptions-woocommerce' ) . ' ' .
					sprintf( __( 'E.g.: %s', 'shipping-icons-descriptions-woocommerce' ),
						'<code>[alg_wc_sid_translate lang="EN,DE" lang_text="Description for EN & DE" not_lang_text="Description for other languages"]</code>' ),
				'id'       => 'alg_wc_shipping_descriptions_shortcodes_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_shipping_descriptions_options',
			),
		);
		$methods_settings = array(
			array(
				'title'    => __( 'Methods', 'shipping-icons-descriptions-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_shipping_descriptions_methods_options',
				'desc'     => sprintf( __( 'You can add HTML tags here, e.g.: %s', 'shipping-icons-descriptions-woocommerce' ),
					'<p>' . '<code>' . implode(  '</code>' . '</p>' . '<p>' . '<code>', array_map( 'esc_html', $examples ) ) .  '</code>' . '</p>'
				),
			),
			array(
				'title'    => __( 'Use shipping instances', 'shipping-icons-descriptions-woocommerce' ),
				'desc'     => __( 'Enable', 'shipping-icons-descriptions-woocommerce' ),
				'desc_tip' => __( 'Enable this if you want to use shipping methods instances instead of shipping methods.', 'shipping-icons-descriptions-woocommerce' ) . ' ' .
					__( 'For example if you need to set different descriptions for different instances of Flat rate (or any other) shipping method (either in different or in same shipping zone).', 'shipping-icons-descriptions-woocommerce' ) . ' ' .
					apply_filters( 'alg_wc_shipping_icons_descs', sprintf(
						'You will need <a target="_blank" href="%s">Shipping Icons and Descriptions for WooCommerce Pro</a> plugin to use shipping instances.',
							'https://wpfactory.com/item/shipping-icons-descriptions-woocommerce/' ), 'settings_use_shipping_instance' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_shipping_descriptions_use_shipping_instance',
				'default'  => 'no',
				'custom_attributes' => apply_filters( 'alg_wc_shipping_icons_descs', array( 'disabled' => 'disabled' ), 'settings' ),
			),
		);
		foreach ( $shipping_methods as $method ) {
			$methods_settings = array_merge( $methods_settings, array(
				array(
					'title'    => ( $use_shipping_instance ?
						$method['zone_name'] . ': ' . $method['shipping_method_title'] :
						$method->method_title ),
					'id'       => ( $use_shipping_instance ?
						'alg_wc_shipping_descriptions[' . $method['shipping_method_instance_id'] . ']' :
						'alg_wc_shipping_description_' . $method->id ),
					'default'  => '',
					'type'     => 'textarea',
					'css'      => 'width:100%;',
					'alg_wc_sid_raw' => true,
				),
			) );
		}
		$methods_settings = array_merge( $methods_settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_shipping_descriptions_methods_options',
			),
		) );
		return array_merge( $settings, $methods_settings );
	}

}

endif;

return new Alg_WC_Shipping_Icons_Descs_Settings_Descs();
