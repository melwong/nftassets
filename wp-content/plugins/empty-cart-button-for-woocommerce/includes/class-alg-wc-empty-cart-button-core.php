<?php
/**
 * Empty Cart Button for WooCommerce - Core Class
 *
 * @version 1.2.3
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Empty_Cart_Button_Core' ) ) :

class Alg_WC_Empty_Cart_Button_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_wc_empty_cart_button_enabled', 'yes' ) ) {
			add_action( 'init', array( $this, 'empty_cart' ) );
			if ( 'disable' != ( $empty_cart_cart_position = apply_filters( 'alg_wc_empty_cart_button', 'woocommerce_after_cart', 'value_position_cart' ) ) ) {
				add_action( $empty_cart_cart_position, array( $this, 'output_empty_cart_form' ), get_option( 'alg_wc_empty_cart_position_priority', 10 ) );
			}
			if ( 'disable' != ( $empty_cart_checkout_position = get_option( 'alg_wc_empty_cart_checkout_position', 'disable' ) ) ) {
				add_action( $empty_cart_checkout_position, array( $this, 'output_empty_cart_form_checkout' ), get_option( 'alg_wc_empty_cart_checkout_position_priority', 10 ) );
			}
			add_shortcode( 'alg_wc_empty_cart_button', array( $this, 'get_empty_cart_form_shortcode' ) );
		}
	}

	/**
	 * get_empty_cart_form_shortcode.
	 *
	 * @version 1.2.1
	 * @since   1.1.0
	 */
	function get_empty_cart_form_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'html_template' => '<div style="float:right;">%button_form%</div>',
			'html_style'    => '',
			'html_class'    => 'button',
		), $atts, 'alg_wc_empty_cart_button' );
		return $this->get_empty_cart_form( $atts['html_template'], $atts['html_style'], $atts['html_class'], apply_filters( 'alg_wc_empty_cart_button', 'Empty cart', 'value_text' ) );
	}

	/**
	 * output_empty_cart_form.
	 *
	 * @version 1.2.1
	 * @since   1.1.0
	 */
	function output_empty_cart_form() {
		echo $this->get_empty_cart_form(
			get_option( 'alg_wc_empty_cart_template', '<div style="float:right;">%button_form%</div>' ),
			get_option( 'alg_wc_empty_cart_button_style', '' ),
			get_option( 'alg_wc_empty_cart_button_class', 'button' ),
			apply_filters( 'alg_wc_empty_cart_button', 'Empty cart', 'value_text' )
		);
	}

	/**
	 * output_empty_cart_form_checkout.
	 *
	 * @version 1.2.1
	 * @since   1.2.0
	 */
	function output_empty_cart_form_checkout() {
		echo $this->get_empty_cart_form(
			get_option( 'alg_wc_empty_cart_template_checkout', '<div style="float:right;">%button_form%</div>' ),
			get_option( 'alg_wc_empty_cart_button_style_checkout', '' ),
			get_option( 'alg_wc_empty_cart_button_class_checkout', 'button' ),
			apply_filters( 'alg_wc_empty_cart_button', 'Empty cart', 'value_text' )
		);
	}

	/**
	 * get_empty_cart_form.
	 *
	 * @version 1.2.3
	 * @since   1.0.0
	 * @todo    [dev] (maybe) remove `<form>` (and use only `<a>`)
	 * @todo    [dev] (maybe) add `<button>` as optional "type"
	 * @todo    [dev] (maybe) add optional `method="get"` in `<form>`
	 */
	function get_empty_cart_form( $html_template, $html_style, $html_class, $label ) {
		$confirmation_html = ( 'confirm_with_pop_up_box' == get_option( 'alg_wc_empty_cart_confirmation', 'no_confirmation' ) ) ?
			' onclick="return confirm(\'' . get_option( 'alg_wc_empty_cart_confirmation_text', __( 'Are you sure?', 'empty-cart-button-for-woocommerce' ) ) . '\')"' : '';
		switch ( get_option( 'alg_wc_empty_cart_button_type', 'form' ) ) {
			case 'a':
				$button_form_html = '<a href="' . add_query_arg( array( 'alg_wc_empty_cart' => '' ), wc_get_cart_url() ) . '" style="' . $html_style . '"' .
					' class="button ' . $html_class . '" id="alg_wc_empty_cart"' . $confirmation_html . '>' . $label . '</a>';
				break;
			case 'form':
				$button_form_html = '<form action="" method="post"><input type="submit" style="' . $html_style . '"' .
					' class="' . $html_class . '" name="alg_wc_empty_cart" id="alg_wc_empty_cart" value="' . $label . '"' . $confirmation_html . '></form>';
				break;
		}
		return str_replace( '%button_form%', $button_form_html, $html_template );
	}

	/**
	 * empty_cart.
	 *
	 * @version 1.2.3
	 * @since   1.0.0
	 */
	function empty_cart() {
		if ( isset( $_REQUEST['alg_wc_empty_cart'] ) ) {
			WC()->cart->empty_cart();
			if ( 'yes' === apply_filters( 'alg_wc_empty_cart_button', 'no', 'value_redirection' ) ) {
				if ( wp_redirect( get_option( 'alg_wc_empty_cart_button_redirect_location', '' ) ) ) {
					exit;
				}
			}
			if ( isset( $_GET['alg_wc_empty_cart'] ) ) {
				if ( wp_redirect( remove_query_arg( 'alg_wc_empty_cart' ) ) ) {
					exit;
				}
			}
		}
	}

}

endif;

return new Alg_WC_Empty_Cart_Button_Core();
