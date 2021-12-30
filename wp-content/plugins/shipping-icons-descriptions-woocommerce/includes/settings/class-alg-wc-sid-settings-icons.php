<?php
/**
 * Shipping Icons and Descriptions for WooCommerce - Icons Section Settings
 *
 * @version 2.0.2
 * @since   2.0.0
 * @author  WP Design Duo
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Shipping_Icons_Descs_Settings_Icons' ) ) :

class Alg_WC_Shipping_Icons_Descs_Settings_Icons extends Alg_WC_Shipping_Icons_Descs_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'Icons', 'shipping-icons-descriptions-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.0.2
	 * @since   2.0.0
	 * @todo    [dev] (maybe) add "Add media" button (i.e. WordPress media selector)
	 */
	function get_settings() {
		$use_shipping_instance = ( 'yes' === apply_filters( 'alg_wc_shipping_icons_descs', 'no', 'use_shipping_instance', array( 'icon_or_description' => 'icon' ) ) );
		$shipping_methods      = ( $use_shipping_instance ? alg_wc_shipping_icons_descs()->core->get_shipping_methods_instances() : WC()->shipping()->get_shipping_methods() );
		$shipping_methods      = apply_filters( 'alg_wc_shipping_icons_descs_shipping_methods', $shipping_methods, $use_shipping_instance, 'icons' );
		$settings = array(
			array(
				'title'    => __( 'Shipping Icons', 'shipping-icons-descriptions-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_shipping_icons_options',
				'desc'     => __( 'Here you can add icons for shipping methods on cart and checkout pages.', 'shipping-icons-descriptions-woocommerce' ),
			),
			array(
				'title'    => __( 'Shipping icons', 'shipping-icons-descriptions-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'shipping-icons-descriptions-woocommerce' ) . '</strong>',
				'desc_tip' => __( 'WooCommerce shipping icons.', 'shipping-icons-descriptions-woocommerce' ),
				'id'       => 'alg_wc_shipping_icons_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Icon position', 'shipping-icons-descriptions-woocommerce' ),
				'id'       => 'alg_wc_shipping_icons_position',
				'default'  => 'after',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					'after'  => __( 'After label', 'shipping-icons-descriptions-woocommerce' ),
					'before' => __( 'Before label', 'shipping-icons-descriptions-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Icon visibility', 'shipping-icons-descriptions-woocommerce' ),
				'id'       => 'alg_wc_shipping_icons_visibility',
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
					'<p>You will need <a target="_blank" href="%s">Shipping Icons and Descriptions for WooCommerce Pro</a> plugin to change icons visibility on pages.</p>',
						'https://wpfactory.com/item/shipping-icons-descriptions-woocommerce/' ), 'settings' ),
				'custom_attributes' => apply_filters( 'alg_wc_shipping_icons_descs', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'title'    => __( 'Icon HTML style', 'shipping-icons-descriptions-woocommerce' ),
				'desc_tip' => sprintf( __( 'For the %s tag.', 'shipping-icons-descriptions-woocommerce' ), '<em>img</em>' ),
				'id'       => 'alg_wc_shipping_icons_style',
				'default'  => 'display:inline;',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Icon HTML class', 'shipping-icons-descriptions-woocommerce' ),
				'desc_tip' => sprintf( __( 'For styling the %s tag with CSS.', 'shipping-icons-descriptions-woocommerce' ), '<em>img</em>' ),
				'id'       => 'alg_wc_shipping_icons_class',
				'default'  => 'alg_wc_shipping_icon',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Separator', 'shipping-icons-descriptions-woocommerce' ),
				'desc_tip' => __( 'Inserted between the text label and icon. Space symbol by default.', 'shipping-icons-descriptions-woocommerce' ),
				'id'       => 'alg_wc_shipping_icons_sep',
				'default'  => ' ',
				'type'     => 'text',
				'alg_wc_sid_raw' => true,
			),
			array(
				'title'    => __( 'Apply shortcodes', 'shipping-icons-descriptions-woocommerce' ),
				'desc'     => __( 'Enable', 'shipping-icons-descriptions-woocommerce' ),
				'desc_tip' => __( 'Check this if you want shortcodes to be enabled in icons.', 'shipping-icons-descriptions-woocommerce' ) . ' ' .
					sprintf( __( 'E.g.: %s', 'shipping-icons-descriptions-woocommerce' ),
						'<code>[alg_wc_sid_translate lang="EN,DE"]URL for EN & DE[/alg_wc_sid_translate][alg_wc_sid_translate not_lang="EN,DE"]URL for other languages[/alg_wc_sid_translate]</code>' ),
				'id'       => 'alg_wc_shipping_icons_shortcodes_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_shipping_icons_options',
			),
		);
		$methods_settings = array(
			array(
				'title'    => __( 'Methods', 'shipping-icons-descriptions-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_shipping_icons_methods_options',
			),
			array(
				'title'    => __( 'Use shipping instances', 'shipping-icons-descriptions-woocommerce' ),
				'desc'     => __( 'Enable', 'shipping-icons-descriptions-woocommerce' ),
				'desc_tip' => __( 'Enable this if you want to use shipping methods instances instead of shipping methods.', 'shipping-icons-descriptions-woocommerce' ) . ' ' .
					__( 'For example if you need to set different icons for different instances of Flat rate (or any other) shipping method (either in different or in same shipping zone).', 'shipping-icons-descriptions-woocommerce' ) . ' ' .
					apply_filters( 'alg_wc_shipping_icons_descs', sprintf(
						'You will need <a target="_blank" href="%s">Shipping Icons and Descriptions for WooCommerce Pro</a> plugin to use shipping instances.',
							'https://wpfactory.com/item/shipping-icons-descriptions-woocommerce/' ), 'settings_use_shipping_instance' ),
				'type'     => 'checkbox',
				'id'       => 'alg_wc_shipping_icons_use_shipping_instance',
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
					'desc_tip' => __( 'Image URL.', 'shipping-icons-descriptions-woocommerce' ),
					'id'       => ( $use_shipping_instance ?
						'alg_wc_shipping_icons[' . $method['shipping_method_instance_id'] . ']' :
						'alg_wc_shipping_icon_' . $method->id ),
					'desc'     => sprintf( __( 'For styling the current %s tag with CSS you can use ID %s.', 'shipping-icons-descriptions-woocommerce' ),
						'<strong>img</strong>',
						'<code>' . ( $use_shipping_instance ?
							'alg_wc_shipping_icon_' . $method['shipping_method_id'] . '_' . $method['shipping_method_instance_id'] :
							'alg_wc_shipping_icon_' . $method->id . '_{instance_id}' ) .
					'</code>' ),
					'default'  => '',
					'type'     => 'text',
					'css'      => 'width:100%;',
				),
			) );
		}
		$methods_settings = array_merge( $methods_settings, array(
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_shipping_icons_methods_options',
			),
		) );
		return array_merge( $settings, $methods_settings );
	}

}

endif;

return new Alg_WC_Shipping_Icons_Descs_Settings_Icons();
