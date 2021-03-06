<?php
/**
 * A radio button template
 * @since 2.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Labels are used with the inputs
// echo pewc_field_label( $item, $id );

if( isset( $item['field_options'] ) ) {
  $index = 0;

	$radio_buttons = '<ul class="pewc-checkbox-group-wrapper">';

	  foreach( $item['field_options'] as $key=>$option_value ) {

			$label = esc_html( $option_value['value'] );

			// Check if a key is set
			// $value is a misleading name for radio buttons because it refers to the label rather than the value
			// However, $value here is used for any existing value (e.g. from a default value for the field)
			// And we use $key instead for the value of the field being output. Make sense?
			if( empty( $option_value['key'] ) ) {
				// This field was saved before 2.4.5 and hasn't been updated since
				$key = $option_value['value'];
			} else {
				$key = $option_value['key'];
				// Remove any unwanted characters from the default or received value
				// $value = pewc_keyify_field( $value );
			}

			$option_price = ! empty( $option_value['price'] ) ? pewc_maybe_include_tax( $product, $option_value['price'] ) : 0;

	    if( ! empty( $option_price ) && apply_filters( 'pewc_show_option_prices', true, $item ) ) {
	      $label .= apply_filters( 'pewc_option_price_separator', ' - ', $item ) . pewc_get_semi_formatted_raw_price( $option_price );
	    }

			// $radio_id = $id . '_' . strtolower( str_replace( ' ', '_', $option_value['value'] ) );
	    $radio_id = $id . '_' . strtolower( str_replace( ' ', '_', $key ) );

			$checked = ( is_array( $value ) && in_array( $key, $value ) ) || ( ! is_array( $value ) && $value == $key )  ? 'checked="checked"' : '';

	    $radio = sprintf(
	      '<li><label for="%s"><input data-option-cost="%s" type="radio" name="%s[]" id="%s" class="pewc-radio-form-field" value="%s" %s>&nbsp;%s</label></li>',
	      esc_attr( $radio_id ), // for
	      esc_attr( $option_price ), // data-option-cost
	      esc_attr( $id ), // name
	      esc_attr( $radio_id ), // id
	      esc_attr( $key ), // value
				esc_attr( $checked ),
	      $label
	    );

			$radio_buttons .= apply_filters( 'pewc_filter_radio_button_field', $radio, $radio_id, $option_price, $id, $label, $option_value, $item );

	  }

	$radio_buttons .= '</ul>';

	echo $open_td;
	echo $radio_buttons;
	echo $close_td;

}
