<?php
/**
 * Custom Shipping Methods for WooCommerce - Core Class
 *
 * @version 1.5.3
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Custom_Shipping_Methods_Core' ) ) :

class Alg_WC_Custom_Shipping_Methods_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.5.2
	 * @since   1.0.0
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_wc_custom_shipping_methods_plugin_enabled', 'yes' ) ) {

			// Init
			add_action( 'init', array( $this, 'init_custom_shipping' ) );

			// Evaluate shipping cost
			add_filter( 'alg_wc_custom_shipping_methods_evaluate_cost_replace',      array( $this, 'add_evaluate_cost_custom_values' ), 10, 2 );
			add_filter( 'alg_wc_custom_shipping_methods_evaluate_cost_shortcodes',   array( $this, 'add_evaluate_cost_shortcodes' ) );
			add_filter( 'alg_wc_custom_shipping_methods_evaluate_cost_args_package', array( $this, 'add_evaluate_cost_custom_args_package' ), 10, 2 );
			add_filter( 'alg_wc_custom_shipping_methods_evaluate_cost_args_class',   array( $this, 'add_evaluate_cost_custom_args_class' ), 10, 2 );
			add_action( 'alg_wc_custom_shipping_methods_evaluate_cost_args',         array( $this, 'save_evaluate_cost_args' ) );

			// Replace zero cost
			if ( 'yes' === get_option( 'alg_wc_custom_shipping_methods_do_replace_zero_cost', 'no' ) ) {
				add_filter( 'woocommerce_cart_shipping_method_full_label', array( $this, 'replace_zero_cost' ), PHP_INT_MAX - 1, 2 );
			}

			// Shipping icons & descriptions
			if ( 'yes' === get_option( 'alg_wc_custom_shipping_methods_icon_desc_enabled', 'no' ) ) {
				add_filter( 'woocommerce_cart_shipping_method_full_label', array( $this, 'add_icon_and_description' ), PHP_INT_MAX, 2 );
			}

			// Trigger checkout update script
			if ( 'yes' === get_option( 'alg_wc_custom_shipping_methods_do_trigger_checkout_update', 'no' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_checkout_script' ) );
			}

			// Custom return URL
			add_filter( 'woocommerce_get_return_url', array( $this, 'change_order_return_url' ), PHP_INT_MAX, 2 );

		}
	}

	/**
	 * get_order_item_shipping_prop.
	 *
	 * @version 1.5.3
	 * @since   1.5.3
	 */
	function get_order_item_shipping_prop( $order, $prop ) {
		if ( $order && is_a( $order, 'WC_Order' ) && method_exists( $order, 'get_shipping_methods' ) ) {
			foreach ( $order->get_shipping_methods() as $order_item_shipping ) {
				if (
					is_a( $order_item_shipping, 'WC_Order_Item_Shipping' ) &&
					method_exists( $order_item_shipping, 'get_method_id' ) && 'alg_wc_shipping' === $order_item_shipping->get_method_id() &&
					method_exists( $order_item_shipping, 'get_instance_id' ) && ( $instance_id = $order_item_shipping->get_instance_id() ) &&
					class_exists( 'WC_Shipping_Alg_Custom' )
				) {
					$shipping = new WC_Shipping_Alg_Custom( $instance_id );
					if ( $shipping && '' != $shipping->{$prop} ) {
						return $shipping->{$prop};
					}
				}
			}
		}
		return false;
	}

	/**
	 * change_order_return_url.
	 *
	 * @version 1.5.3
	 * @since   1.5.2
	 * @todo    [dev] (maybe) also add this to `woocommerce_get_checkout_order_received_url` filter
	 */
	function change_order_return_url( $return_url, $order ) {
		return ( false !== ( $custom_return_url = $this->get_order_item_shipping_prop( $order, 'return_url' ) ) ? $custom_return_url : $return_url );
	}

	/**
	 * enqueue_checkout_script.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function enqueue_checkout_script() {
		if( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return;
		}
		wp_enqueue_script( 'alg-wc-custom-shipping-methods-checkout',
			alg_wc_custom_shipping_methods()->plugin_url() . '/includes/js/alg-wc-custom-shipping-methods-checkout.js',
			array( 'jquery' ),
			alg_wc_custom_shipping_methods()->version,
			true
		);
	}


	/**
	 * replace_zero_cost.
	 *
	 * @version 1.2.1
	 * @since   1.2.1
	 * @todo    [dev] recheck if this is still working
	 */
	function replace_zero_cost( $label, $rate ) {
		return ( isset( $rate->method_id ) && 'alg_wc_shipping' == $rate->method_id && 0 == $rate->cost ?
			$rate->get_label() . get_option( 'alg_wc_custom_shipping_methods_replace_zero_cost_text', '' ) : $label );
	}

	/**
	 * add_icon_and_description.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 * @todo    [feature] add visibility options: cart / checkout
	 */
	function add_icon_and_description( $label, $rate ) {
		return apply_filters( 'alg_wc_custom_shipping_methods_option', $label, 'icon_and_description', array( 'rate' => $rate ) );
	}

	/*
	 * add_evaluate_cost_custom_values.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_evaluate_cost_custom_values( $values, $args ) {
		$values['[weight]'] = $args['weight'];
		$values['[volume]'] = $args['volume'];
		return $values;
	}

	/*
	 * add_evaluate_cost_shortcodes.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function add_evaluate_cost_shortcodes( $shortcodes ) {
		$shortcodes['costs_table'] = array( $this, 'costs_table' );
		$shortcodes['distance']    = array( $this, 'distance' );
		return $shortcodes;
	}

	/**
	 * format address (for distance shortcode).
	 *
	 * @version 1.5.1
	 * @since   1.3.0
	 * @todo    [dev] (maybe) move this to `alg_wc_custom_shipping_methods_option()`
	 */
	function format_address( $address, $city, $postcode, $country ) {
		return ( $address || $city || $postcode || $country ? "{$address}, {$city} {$postcode}, {$country}" : false );
	}

	/**
	 * get destination address field (for distance shortcode).
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 * @todo    [dev] (maybe) move this to `alg_wc_custom_shipping_methods_option()`
	 */
	function get_dst_field( $field ) {
		// Shipping
		if ( isset( $_POST[ 's_' . $field ] ) ) {
			return $_POST[ 's_' . $field ];
		}
		if ( function_exists( 'WC' ) && isset( WC()->session->customer ) ) {
			$customer = maybe_unserialize( WC()->session->customer );
			if ( isset( $customer[ 'shipping_' . $field ] ) ) {
				return $customer[ 'shipping_' . $field ];
			}
		}
		// Billing
		if ( isset( $_POST[ $field ] ) ) {
			return $_POST[ $field ];
		}
		if ( function_exists( 'WC' ) && isset( WC()->session->customer ) ) {
			$customer = maybe_unserialize( WC()->session->customer );
			if ( isset( $customer[ $field ] ) ) {
				return $customer[ $field ];
			}
		}
		return '';
	}

	/**
	 * work out distance (shortcode).
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 * @param   array $atts
	 * @return  string
	 * @see     https://developers.google.com/maps/documentation/distance-matrix/intro
	 * @todo    [dev] (maybe) add global option for the `key` (can be set via `$default_atts`)
	 * @todo    [feature] add `api` attribute, i.e. option to use another API (i.e. instead of `maps.googleapis.com`)
	 */
	function distance( $atts ) {
		return apply_filters( 'alg_wc_custom_shipping_methods_option', '', 'distance', array( 'atts' => $atts ) );
	}

	/**
	 * work out costs_table (shortcode).
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   array $atts
	 * @return  string
	 */
	function costs_table( $atts ) {
		return apply_filters( 'alg_wc_custom_shipping_methods_option', '', 'costs_table', array( 'atts' => $atts, 'args' => $this->evaluate_cost_args ) );
	}

	/*
	 * add_evaluate_cost_custom_args_package.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_evaluate_cost_custom_args_package( $args, $package ) {
		$args['weight'] = $this->get_package_item_weight( $package );
		$args['volume'] = $this->get_package_item_volume( $package );
		return $args;
	}

	/*
	 * add_evaluate_cost_custom_args_class.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_evaluate_cost_custom_args_class( $args, $products ) {
		$args['weight'] = $this->get_products_weight( $products );
		$args['volume'] = $this->get_products_volume( $products );
		return $args;
	}

	/*
	 * save_evaluate_cost_args.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function save_evaluate_cost_args( $args ) {
		$this->evaluate_cost_args = $args;
	}

	/**
	 * get items volume in package.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   array $package
	 */
	function get_package_item_volume( $package ) {
		return $this->get_products_volume( $package['contents'] );
	}

	/**
	 * get_products_volume.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   array $products
	 */
	function get_products_volume( $products ) {
		$total_volume = 0;
		foreach ( $products as $item_id => $values ) {
			if ( $values['data']->needs_shipping() && $values['data']->get_height() && $values['data']->get_width() && $values['data']->get_length() ) {
				$total_volume += $values['data']->get_height() * $values['data']->get_width() * $values['data']->get_length() * $values['quantity'];
			}
		}
		return $total_volume;
	}

	/**
	 * get items weight in package.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   array $package
	 */
	function get_package_item_weight( $package ) {
		return $this->get_products_weight( $package['contents'] );
	}

	/**
	 * get_products_weight.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   array $products
	 */
	function get_products_weight( $products ) {
		$total_weight = 0;
		foreach ( $products as $item_id => $values ) {
			if ( $values['data']->needs_shipping() && $values['data']->get_weight() ) {
				$total_weight += $values['data']->get_weight() * $values['quantity'];
			}
		}
		return $total_weight;
	}

	/*
	 * init_custom_shipping.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function init_custom_shipping() {
		if ( class_exists( 'WC_Shipping_Method' ) ) {
			require_once( 'class-wc-shipping-alg-custom.php' );
			add_filter( 'woocommerce_shipping_methods', array( $this, 'add_custom_shipping' ) );
		}
	}

	/*
	 * add_custom_shipping.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_custom_shipping( $methods ) {
		$methods['alg_wc_shipping'] = 'WC_Shipping_Alg_Custom';
		return $methods;
	}

}

endif;

return new Alg_WC_Custom_Shipping_Methods_Core();
