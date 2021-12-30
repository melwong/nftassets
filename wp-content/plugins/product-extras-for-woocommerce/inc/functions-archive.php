<?php
/**
 * Functions for the product archive
 * @since 1.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Does the product have product_extra groups?
 * @return Boolean
 */
function pewc_has_product_extra_groups( $product_id ) {
	$product_extra_groups = pewc_get_extra_fields( $product_id );
	if( $product_extra_groups ) {
		return true;
	}
	return false;
}

/**
 * Products with product_extra groups can't be purchased from archive
 * @return Boolean
 */
function pewc_filter_is_purchasable( $is_purchasable, $product ) {
	if( is_archive() ) {
		$product_id = $product->get_id();
		$product_extra_groups = pewc_get_extra_fields( $product_id );
		if( $product_extra_groups ) {
			return false;
		}
	}
	return $is_purchasable;
}
// add_filter( 'woocommerce_is_purchasable', 'pewc_filter_is_purchasable', 10, 2 );

/**
 * Replace add to cart button in archive, shop, home and products shortcode
 * @return HTML
 */
function pewc_view_product_button( $button, $product ) {
	$product_id = $product->get_id();
	if( ! pewc_has_product_extra_groups( $product_id ) ) {
		return $button;
	}
	$text = apply_filters(
		'pewc_filter_view_product_text',
		__( 'View product', 'pewc' ),
		$product
	);
  $button = sprintf(
		'<a class="button" href="%s">%s</a>',
		$product->get_permalink(),
		esc_html( $text )
	);
  return $button;
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'pewc_view_product_button', 10, 2 );
