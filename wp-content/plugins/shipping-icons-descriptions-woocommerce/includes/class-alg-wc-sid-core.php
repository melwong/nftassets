<?php
/**
 * Shipping Icons and Descriptions for WooCommerce - Core Class
 *
 * @version 2.0.4
 * @since   1.0.0
 * @author  WP Design Duo
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Shipping_Icons_Descs_Core' ) ) :

class Alg_WC_Shipping_Icons_Descs_Core {

	/**
	 * Constructor.
	 *
	 * @version 2.0.4
	 * @since   1.0.0
	 * @todo    [dev] (maybe) make class names shorter (e.g. `Alg_WC_Shipping_Icons_Descs_Core` to `Alg_WC_SID_Core`)
	 */
	function __construct() {
		// Shipping Icons
		if ( 'yes' === get_option( 'alg_wc_shipping_icons_enabled', 'no' ) ) {
			add_filter( 'woocommerce_cart_shipping_method_full_label', array( $this, 'shipping_icon' ), PHP_INT_MAX, 2 );
		}
		// Shipping Descriptions
		if ( 'yes' === get_option( 'alg_wc_shipping_descriptions_enabled', 'no' ) ) {
			add_filter( 'woocommerce_cart_shipping_method_full_label', array( $this, 'shipping_description' ), PHP_INT_MAX, 2 );
			if ( 'yes' === get_option( 'alg_wc_shipping_descriptions_add_to_order_details', 'no' ) ) {
				add_filter( 'woocommerce_order_shipping_to_display', array( $this, 'add_shipping_description_to_order_details' ), PHP_INT_MAX, 3 );
			}
		}
		// Language shortcode
		if ( 'yes' === get_option( 'alg_wc_shipping_icons_shortcodes_enabled', 'no' ) || 'yes' === get_option( 'alg_wc_shipping_descriptions_shortcodes_enabled', 'no' ) ) {
			add_shortcode( 'alg_wc_sid_translate', array( $this, 'language_shortcode' ) );
		}
	}

	/**
	 * add_shipping_description_to_order_details.
	 *
	 * @version 2.0.4
	 * @since   2.0.4
	 */
	function add_shipping_description_to_order_details( $shipping, $order, $tax_display ) {
		if ( $order->get_shipping_method() ) {
			foreach ( $order->get_shipping_methods() as $method ) {
				// Converting from `WC_Order_Item_Shipping`
				$_method = new stdClass();
				$_method->instance_id = $method->get_instance_id();
				$_method->method_id   = $method->get_method_id();
				// Getting description
				if ( '' != ( $desc = $this->get_value( 'description', $_method ) ) ) {
					$shipping = $shipping . $desc;
				}
			}
		}
		return $shipping;
	}

	/**
	 * language_shortcode.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function language_shortcode( $atts, $content = '' ) {
		// E.g.: `[alg_wc_sid_translate lang="EN,DE" lang_text="Description for EN & DE" not_lang_text="Description for other languages"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				$atts['not_lang_text'] : $atts['lang_text'];
		}
		// E.g.: `[alg_wc_sid_translate lang="EN,DE"]Description for EN & DE[/alg_wc_sid_translate][alg_wc_sid_translate not_lang="EN,DE"]Description for other languages[/alg_wc_sid_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : $content;
	}

	/**
	 * is_visible.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	function is_visible( $visibility_option ) {
		switch ( $visibility_option ) {
			case 'both':
				return true;
			case 'checkout_only':
				return is_checkout();
			case 'cart_only':
				return is_cart();
			default:
				return true;
		}
	}

	/**
	 * get_value.
	 *
	 * @version 2.0.2
	 * @since   2.0.0
	 * @todo    [dev] (maybe) move all options (i.e. options when not using shipping instance) to array
	 */
	function get_value( $icon_or_description, $method ) {
		$use_shipping_instance = ( 'yes' === apply_filters( 'alg_wc_shipping_icons_descs', 'no', 'use_shipping_instance', array( 'icon_or_description' => $icon_or_description ) ) );
		$return = ( $use_shipping_instance ?
			apply_filters( 'alg_wc_shipping_icons_descs', '', 'get_value', array( 'icon_or_description' => $icon_or_description, 'instance_id' => $method->instance_id ) ) :
			get_option( 'alg_wc_shipping_' . $icon_or_description . '_' . $method->method_id, '' ) );

		/**
		 * `alg_wc_shipping_icons_descs_get_value` filter example.
		 *
		 * add_filter( 'alg_wc_shipping_icons_descs_get_value', 'alg_shipping_icons_descs_get_value_fallback', PHP_INT_MAX, 4 );
		 * if ( ! function_exists( 'alg_shipping_icons_descs_get_value_fallback' ) ) {
		 *    function alg_shipping_icons_descs_get_value_fallback( $value, $method, $use_shipping_instance, $icon_or_description ) {
		 *        if ( '' == $value && $use_shipping_instance && empty( $method->instance_id ) ) {
		 *            return get_option( 'alg_wc_shipping_' . $icon_or_description . '_' . $method->method_id, '' );
		 *        }
		 *        return $value;
		 *    }
		 * }
		 */
		$return = apply_filters( 'alg_wc_shipping_icons_descs_get_value', $return, $method, $use_shipping_instance, $icon_or_description );

		return ( 'yes' === get_option( 'alg_wc_shipping_' . $icon_or_description . 's_shortcodes_enabled', 'no' ) ? do_shortcode( $return ) : $return );
	}

	/**
	 * shipping_icon.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function shipping_icon( $label, $method ) {
		if ( ! $this->is_visible( apply_filters( 'alg_wc_shipping_icons_descs', 'both', 'visibility', array( 'icon_or_description' => 'icon' ) ) ) ) {
			return $label;
		}
		if ( '' != ( $icon_url = $this->get_value( 'icon', $method ) ) ) {
			$img = '<img' .
				' style="' . get_option( 'alg_wc_shipping_icons_style', 'display:inline;' ) . '"' .
				' class="' . get_option( 'alg_wc_shipping_icons_class', 'alg_wc_shipping_icon' ) . '"' .
				' id="alg_wc_shipping_icon_' . $method->method_id . '_' . $method->instance_id . '"' .
				' src="' . $icon_url . '"' .
			'>';
			$sep   = get_option( 'alg_wc_shipping_icons_sep', ' ' );
			$label = ( 'before' === get_option( 'alg_wc_shipping_icons_position', 'after' ) ? $img . $sep . $label : $label . $sep . $img );
		}
		return $label;
	}

	/**
	 * shipping_description.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function shipping_description( $label, $method ) {
		if ( ! $this->is_visible( apply_filters( 'alg_wc_shipping_icons_descs', 'both', 'visibility', array( 'icon_or_description' => 'description' ) ) ) ) {
			return $label;
		}
		if ( '' != ( $desc = $this->get_value( 'description', $method ) ) ) {
			$label = ( 'before' === get_option( 'alg_wc_shipping_descriptions_position', 'after' ) ? $desc . $label : $label . $desc );
		}
		return $label;
	}

	/**
	 * get_shipping_zones.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_shipping_zones( $include_empty_zone = true ) {
		$zones = WC_Shipping_Zones::get_zones();
		if ( $include_empty_zone ) {
			$zone                                                = new WC_Shipping_Zone( 0 );
			$zones[ $zone->get_id() ]                            = $zone->get_data();
			$zones[ $zone->get_id() ]['zone_id']                 = $zone->get_id();
			$zones[ $zone->get_id() ]['formatted_zone_location'] = $zone->get_formatted_location();
			$zones[ $zone->get_id() ]['shipping_methods']        = $zone->get_shipping_methods();
		}
		return $zones;
	}

	/**
	 * get_shipping_methods_instances.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function get_shipping_methods_instances() {
		$shipping_methods = array();
		foreach ( $this->get_shipping_zones() as $zone_id => $zone_data ) {
			foreach ( $zone_data['shipping_methods'] as $shipping_method ) {
				$shipping_methods[ $shipping_method->instance_id ] = array(
					'zone_id'                     => $zone_id,
					'zone_name'                   => $zone_data['zone_name'],
					'formatted_zone_location'     => $zone_data['formatted_zone_location'],
					'shipping_method_title'       => $shipping_method->title,
					'shipping_method_id'          => $shipping_method->id,
					'shipping_method_instance_id' => $shipping_method->instance_id,
				);
			}
		}
		return $shipping_methods;
	}

}

endif;

return new Alg_WC_Shipping_Icons_Descs_Core();
