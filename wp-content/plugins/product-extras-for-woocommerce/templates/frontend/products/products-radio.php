<?php
/**
 * A products field template for the radio layout
 * @since 2.2.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

$number_columns = ( isset( $item['number_columns'] ) ) ? $item['number_columns'] : 3;
$radio_wrapper_classes = array(
	'pewc-radio-images-wrapper',
	'child-product-wrapper'
);
$radio_wrapper_classes[] = 'pewc-columns-' . intval( $number_columns );
if( ! empty( $item['hide_labels'] ) ) {
	$radio_wrapper_classes[] = 'pewc-hide-labels';
}

if( ! empty( $item['products_quantities'] ) ) {
	$products_quantities = ! empty( $item['products_quantities'] ) ? $item['products_quantities'] : '';
	$radio_wrapper_classes[] = 'products-quantities-' . $item['products_quantities'];
}  ?>

<div class="<?php echo join( ' ', $radio_wrapper_classes ); ?>" data-products-quantities="<?php echo esc_attr( $item['products_quantities'] ); ?>">

<?php foreach( $item['child_products'] as $child_product_id ) {
	
	$child_product = wc_get_product( $child_product_id );
	$child_price = pewc_maybe_include_tax( $child_product, $child_product->get_price() );
	if( ! empty( $item['child_discount'] ) && ! empty( $item['discount_type'] ) ) {
		$discounted_price = pewc_get_discounted_child_price( $child_price, $item['child_discount'], $item['discount_type'] );
		$price = wc_format_sale_price( $child_price, $discounted_price );
		$option_cost = pewc_maybe_include_tax( $child_product, $discounted_price );
	} else {
		$price = $child_product->get_price_html();
		$option_cost = pewc_maybe_include_tax( $child_product, $child_price );
	}

	$disabled = '';
	if( ! $child_product->is_purchasable() || ! $child_product->is_in_stock() ) {
		$disabled = 'disabled';
	}
	// Check available stock if stock is managed
	$available_stock = '';
	if( $child_product->managing_stock() ) {
		$available_stock = $child_product->get_stock_quantity();
	}

	$image_url = ( get_post_thumbnail_id( $child_product_id ) ) ? wp_get_attachment_url( get_post_thumbnail_id( $child_product_id ) ) : wc_placeholder_img_src();
	$image = '<img src="' . esc_url( $image_url ) . '">';

  $name = get_the_title( $child_product_id ) . apply_filters( 'pewc_option_price_separator', ' - ', $item ) . $price;

	$field_name = $id . '_child_product';

	$radio_id = $id . '_' . $child_product_id;

	$wrapper_classes = array(
		'pewc-radio-image-wrapper'
	);

	$checked = ( $value == $id ) ? 'checked="checked"' : '';

	// $option_cost = pewc_maybe_include_tax( $child_product, $child_product->get_price() );

  $radio = sprintf(
    '<div class="%s"><label for="%s"><input %s data-stock="%s" data-option-cost="%s" type="radio" name="%s[]" id="%s" class="pewc-radio-form-field" value="%s" %s>%s</label><div class="pewc-radio-image-desc">%s</div></div>',
		join( ' ', $wrapper_classes ),
    esc_attr( $radio_id ),
		esc_attr( $disabled ),
		esc_attr( $available_stock ),
    esc_attr( $option_cost ),
    esc_attr( $field_name ),
    esc_attr( $radio_id ),
    esc_attr( $child_product_id ),
		esc_attr( $checked ),
    $image,
		$name
  );
  echo apply_filters( 'pewc_filter_radio', $radio, $child_product_id, $price, $id, $name, $item );
} ?>

</div><!-- .pewc-radio-images-wrapper -->

<?php if( $products_quantities == 'independent' ) {
	// Add a quantity field for the child product ?>
	<input type="number" min="0" step="1" class="pewc-form-field pewc-child-quantity-field" name="<?php echo esc_attr( $id ); ?>_child_quantity" value="0">
<?php } ?>
