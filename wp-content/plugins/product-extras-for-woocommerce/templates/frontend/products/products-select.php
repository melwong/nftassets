<?php
/**
 * A products field template for the select layout
 * @since 2.2.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

$child_product_wrapper_class = array( 'child-product-wrapper' );
if( ! empty( $item['products_quantities'] ) ) {
	$products_quantities = ! empty( $item['products_quantities'] ) ? $item['products_quantities'] : '';
	$child_product_wrapper_class[] = 'products-quantities-' . $item['products_quantities'];
} ?>

<div class="<?php echo join( ' ', $child_product_wrapper_class ); ?>" data-products-quantities="<?php echo esc_attr( $item['products_quantities'] ); ?>">

	<select class="pewc-form-field pewc-child-select-field" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $id ); ?>_child_product">
	<?php if( ! empty( $item['select_placeholder'] ) ) {
		// Add the placeholder instruction text
		echo '<option value="">' . esc_html( $item['select_placeholder'] ) . '</option>';
	}
	foreach( $item['child_products'] as $child_product_id ) {

		$child_product = wc_get_product( $child_product_id );
		$child_price = pewc_maybe_include_tax( $child_product, $child_product->get_price() );
		if( ! empty( $item['child_discount'] ) && ! empty( $item['discount_type'] ) ) {
			$price = pewc_get_discounted_child_price( $child_price, $item['child_discount'], $item['discount_type'] );
			// $price = wc_format_sale_price( $child_price, $discounted_price );
			$option_cost = pewc_maybe_include_tax( $child_product, $price );
		} else {
			$price = $child_product->get_price_html();
			$option_cost = pewc_maybe_include_tax( $child_product, $child_price );
		}
		$price = wc_price( $price );

		$disabled = '';
		if( ! $child_product->is_purchasable() || ! $child_product->is_in_stock() ) {
			$disabled = 'disabled';
		}
		// Check available stock if stock is managed
		$available_stock = '';
		if( $child_product->managing_stock() ) {
			$available_stock = $child_product->get_stock_quantity();
		}
		// $price = pewc_get_semi_formatted_price( $child_product );
		$name = get_the_title( $child_product_id ) . apply_filters( 'pewc_option_price_separator', ' - ', $item ) . $price;
		// $option_cost = pewc_maybe_include_tax( $child_product, $child_product->get_price() );
		echo '<option data-option-cost="' . esc_attr( $option_cost ) . '" ' . $disabled . ' value="' . esc_attr( $child_product_id ) . '" data-stock="' . esc_attr( $available_stock ) . '">' . $name . '</option>';
	} ?>
	</select>

	<?php if( $products_quantities == 'independent' ) {
		// Add a quantity field for the child product ?>
		<input type="number" min="0" step="1" class="pewc-form-field pewc-child-quantity-field" name="<?php echo esc_attr( $id ); ?>_child_quantity" value="0">
	<?php } ?>

</div><!-- .child-product-wrapper -->
