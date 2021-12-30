<?php
/**
 * A text field template
 * @since 2.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

// echo pewc_field_label( $item, $id );
$attributes = pewc_get_text_field_attributes( $item );
printf(
	'%s<textarea class="pewc-form-field" id="%s" name="%s" %s>%s</textarea>%s',
	$open_td, // Set in functions-single-product.php
	esc_attr( $id ),
	esc_attr( $id ),
	$attributes,
	esc_html( $value ),
	$close_td
); ?>
