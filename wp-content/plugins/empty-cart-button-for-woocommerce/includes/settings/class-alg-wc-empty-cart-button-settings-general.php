<?php
/**
 * Empty Cart Button for WooCommerce - General Section Settings
 *
 * @version 1.2.3
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Empty_Cart_Button_Settings_General' ) ) :

class Alg_WC_Empty_Cart_Button_Settings_General extends Alg_WC_Empty_Cart_Button_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'empty-cart-button-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_checkout_position_options.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_checkout_position_options() {
		return array(
			'disable'                            => __( 'Do not add', 'empty-cart-button-for-woocommerce' ),
			'woocommerce_before_checkout_form'   => __( 'Before checkout form', 'empty-cart-button-for-woocommerce' ),
			'woocommerce_after_checkout_form'    => __( 'After checkout form', 'empty-cart-button-for-woocommerce' ),
		);
	}

	/**
	 * get_cart_position_options.
	 *
	 * @version 1.2.3
	 * @since   1.2.0
	 * @todo    [dev] (important) `woocommerce_cart_coupon` and `woocommerce_cart_actions` are inside `<form>` tag
	 * @todo    [dev] (maybe) add `woocommerce_cart_collaterals` // cart.php
	 */
	function get_cart_position_options() {
		return array(
			'disable'                             => __( 'Do not add', 'empty-cart-button-for-woocommerce' ),
			'woocommerce_before_cart'             => __( 'Before cart', 'empty-cart-button-for-woocommerce' ),                         // cart.php
			'woocommerce_cart_coupon'             => __( 'After "Apply coupon" button', 'empty-cart-button-for-woocommerce' ),         // cart.php
			'woocommerce_cart_actions'            => __( 'After "Update cart" button', 'empty-cart-button-for-woocommerce' ),          // cart.php
			'woocommerce_proceed_to_checkout'     => __( 'After "Proceed to checkout" button', 'empty-cart-button-for-woocommerce' ),  // cart-totals.php
			'woocommerce_after_cart_totals'       => __( 'After cart totals', 'empty-cart-button-for-woocommerce' ),                   // cart-totals.php
			'woocommerce_after_cart'              => __( 'After cart', 'empty-cart-button-for-woocommerce' ),                          // cart.php
		);
	}

	/**
	 * get_settings.
	 *
	 * @version 1.2.3
	 * @since   1.0.0
	 * @todo    [feature] widget
	 * @todo    [feature] multiple positions (e.g. on cart page)
	 * @todo    [feature] maybe add different "Label Options", "Confirmation Options" and "Redirect Options" for a) Cart, b) Checkout and c) Shortcode
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'    => __( 'Empty Cart Button Options', 'empty-cart-button-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_empty_cart_button_options',
			),
			array(
				'title'    => __( 'Empty Cart Button', 'empty-cart-button-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'empty-cart-button-for-woocommerce' ) . '</strong>',
				'desc_tip' => '<a href="https://wpfactory.com/item/empty-cart-button-woocommerce/" target="_blank">' .
					__( 'Empty Cart Button for WooCommerce.', 'empty-cart-button-for-woocommerce' ) . '</a>',
				'id'       => 'alg_wc_empty_cart_button_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_empty_cart_button_options',
			),
			array(
				'title'    => __( 'Position Options', 'empty-cart-button-for-woocommerce' ),
				'desc'     => sprintf(
					__( 'Alternatively you can also use %s shortcode or %s PHP code to add "empty cart" button anywhere on your site.', 'empty-cart-button-for-woocommerce' ),
					'<code>[alg_wc_empty_cart_button]</code>',
					'<code>do_shortcode( \'[alg_wc_empty_cart_button]\' );</code>'
				),
				'type'     => 'title',
				'id'       => 'alg_wc_empty_cart_button_position_options',
			),
			array(
				'title'    => __( 'Cart: Button position', 'empty-cart-button-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'Possible positions are: %s.', 'empty-cart-button-for-woocommerce' ), implode( '; ', $this->get_cart_position_options() ) ),
				'id'       => 'alg_wc_empty_cart_position',
				'default'  => 'woocommerce_after_cart',
				'type'     => 'select',
				'options'  => $this->get_cart_position_options(),
				'desc'     => apply_filters( 'alg_wc_empty_cart_button', sprintf( '<br>' . 'Get <a target="_blank" href="%s">%s</a> plugin to change button position on cart page.',
					'https://wpfactory.com/item/empty-cart-button-woocommerce/', 'Empty Cart Button for WooCommerce Pro' ), 'settings' ),
				'custom_attributes' => apply_filters( 'alg_wc_empty_cart_button', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'title'    => __( 'Cart: Button position priority', 'empty-cart-button-for-woocommerce' ),
				'desc_tip' => __( 'Change this if you want to move the button inside the Position selected above.', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_position_priority',
				'default'  => 10,
				'type'     => 'number',
			),
			array(
				'title'    => __( 'Checkout: Button position', 'empty-cart-button-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'Possible positions are: %s.', 'empty-cart-button-for-woocommerce' ), implode( '; ', $this->get_checkout_position_options() ) ),
				'id'       => 'alg_wc_empty_cart_checkout_position',
				'default'  => 'disable',
				'type'     => 'select',
				'options'  => $this->get_checkout_position_options(),
			),
			array(
				'title'    => __( 'Checkout: Button position priority', 'empty-cart-button-for-woocommerce' ),
				'desc_tip' => __( 'Change this if you want to move the button inside the Position selected above.', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_checkout_position_priority',
				'default'  => 10,
				'type'     => 'number',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_empty_cart_button_position_options',
			),
			array(
				'title'    => __( 'Style Options', 'empty-cart-button-for-woocommerce' ),
				'desc'     => sprintf(
					__( 'Alternatively, if using %s shortcode, you can style the button with %s attributes, e.g.:<br>%s', 'empty-cart-button-for-woocommerce' ),
					'<code>[alg_wc_empty_cart_button]</code>',
					'<code>html_template</code>, <code>html_style</code>, <code>html_class</code>',
					'<code>' . esc_html( '[alg_wc_empty_cart_button html_template="<div style=\'float:right;\'>%button_form%</div>" html_style="" html_class="button"]' ) . '</code>'
				),
				'type'     => 'title',
				'id'       => 'alg_wc_empty_cart_button_style_options',
			),
			array(
				'title'    => __( 'Cart: HTML template', 'empty-cart-button-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'HTML template for wrapping the button. Replaced value: %s', 'empty-cart-button-for-woocommerce' ), '%button_form%' ),
				'id'       => 'alg_wc_empty_cart_template',
				'default'  => '<div style="float:right;">%button_form%</div>',
				'type'     => 'textarea',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Cart: Button HTML class', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_button_class',
				'default'  => 'button',
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Cart: Button HTML style', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_button_style',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Checkout: HTML template', 'empty-cart-button-for-woocommerce' ),
				'desc_tip' => sprintf( __( 'HTML template for wrapping the button. Replaced value: %s', 'empty-cart-button-for-woocommerce' ), '%button_form%' ),
				'id'       => 'alg_wc_empty_cart_template_checkout',
				'default'  => '<div style="float:right;">%button_form%</div>',
				'type'     => 'textarea',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Checkout: Button HTML class', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_button_class_checkout',
				'default'  => 'button',
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'title'    => __( 'Checkout: Button HTML style', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_button_style_checkout',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_empty_cart_button_style_options',
			),
			array(
				'title'    => __( 'Label Options', 'empty-cart-button-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_empty_cart_button_label_options',
			),
			array(
				'title'    => __( 'Button label', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_text',
				'default'  => 'Empty cart',
				'type'     => 'text',
				'desc'     => apply_filters( 'alg_wc_empty_cart_button', sprintf( '<br>' . 'Get <a target="_blank" href="%s">%s</a> plugin to change button label.',
					'https://wpfactory.com/item/empty-cart-button-woocommerce/', 'Empty Cart Button for WooCommerce Pro' ), 'settings' ),
				'custom_attributes' => apply_filters( 'alg_wc_empty_cart_button', array( 'readonly' => 'readonly' ), 'settings' ),
				'css'      => 'width:100%;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_empty_cart_button_label_options',
			),
			array(
				'title'    => __( 'Confirmation Options', 'empty-cart-button-for-woocommerce' ),
				'desc'     => __( 'In this section you can select if you want user to confirm after empty cart button was pressed.', 'empty-cart-button-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_empty_cart_button_confirmation_options',
			),
			array(
				'title'    => __( 'Confirmation', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_confirmation',
				'default'  => 'no_confirmation',
				'type'     => 'select',
				'options'  => array(
					'no_confirmation'         => __( 'No confirmation', 'empty-cart-button-for-woocommerce' ),
					'confirm_with_pop_up_box' => __( 'Confirm by pop up box', 'empty-cart-button-for-woocommerce' ),
				),
			),
			array(
				'title'    => __( 'Confirmation text', 'empty-cart-button-for-woocommerce' ),
				'desc_tip' => __( 'Ignored if confirmation is not enabled.', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_confirmation_text',
				'default'  => __( 'Are you sure?', 'empty-cart-button-for-woocommerce' ),
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_empty_cart_button_confirmation_options',
			),
			array(
				'title'    => __( 'Redirect Options', 'empty-cart-button-for-woocommerce' ),
				'desc'     => __( 'In this section you can select if you want to redirect the user to another page after cart is emptied.', 'empty-cart-button-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_empty_cart_button_redirect_options',
			),
			array(
				'title'    => __( 'Redirect', 'empty-cart-button-for-woocommerce' ),
				'desc'     => __( 'Enable', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_button_redirect_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'desc_tip' => apply_filters( 'alg_wc_empty_cart_button', sprintf( 'Get <a target="_blank" href="%s">%s</a> plugin to enable redirection.',
					'https://wpfactory.com/item/empty-cart-button-woocommerce/', 'Empty Cart Button for WooCommerce Pro' ), 'settings' ),
				'custom_attributes' => apply_filters( 'alg_wc_empty_cart_button', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'title'    => __( 'Redirect location', 'empty-cart-button-for-woocommerce' ),
				'desc_tip' => __( 'Ignored if redirect is not enabled.', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_button_redirect_location',
				'default'  => '',
				'type'     => 'text',
				'css'      => 'width:100%;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_empty_cart_button_redirect_options',
			),
			array(
				'title'    => __( 'Advanced Options', 'empty-cart-button-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_empty_cart_button_advanced_options',
			),
			array(
				'title'    => __( 'Button type', 'empty-cart-button-for-woocommerce' ),
				'id'       => 'alg_wc_empty_cart_button_type',
				'default'  => 'form',
				'type'     => 'select',
				'class'    => 'chosen_select',
				'options'  => array(
					'form' => __( 'Form', 'empty-cart-button-for-woocommerce' ),
					'a'    => __( 'Hyperlink', 'empty-cart-button-for-woocommerce' ),
				),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_empty_cart_button_advanced_options',
			),
		);
		return $settings;
	}

}

endif;

return new Alg_WC_Empty_Cart_Button_Settings_General();
