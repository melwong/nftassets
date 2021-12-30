<?php
/**
 * A products field template for the column layout
 * @since 2.6.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked pewc_enqueue_variations_scripts
 */
do_action( 'pewc_products_column_layout' );

$checkboxes_wrapper_classes = array(
	'pewc-column-wrapper',
	'child-product-wrapper'
);
$manage_stock = false;

if( ! empty( $item['products_quantities'] ) ) {
	$products_quantities = ! empty( $item['products_quantities'] ) ? $item['products_quantities'] : '';
	$checkboxes_wrapper_classes[] = 'products-quantities-' . $item['products_quantities'];
} ?>

<div class="<?php echo join( ' ', $checkboxes_wrapper_classes ); ?>" data-products-quantities="<?php echo esc_attr( $item['products_quantities'] ); ?>">

<?php foreach( $item['child_products'] as $child_product_id ) {

	$wrapper_classes = array(
		'pewc-checkbox-image-wrapper'
	);

	$child_product = wc_get_product( $child_product_id );
	$variant_wrapper = '';

	$excerpt = sprintf(
		'<div class="pewc-column-excerpt"><p>%s</p></div>',
		get_the_excerpt( $child_product_id )
	);

	if( $child_product->get_type() == 'variable' ) {
		$variants = $child_product->get_children();
		if( $variants ) {
			$available_variations = $child_product->get_available_variations();

			$variant_wrapper = sprintf(
				'<select name="%s" id="%s" class="pewc-variable-child-select" data-product_variations="%s">',
				'pewc_child_variants_' . $child_product_id,
				'pewc_child_variants_' . $child_product_id,
				htmlspecialchars( wp_json_encode( $available_variations ) )
			);
			foreach( $variants as $variant_id ) {
				$variant = wc_get_product( $variant_id );
				$variant_price = pewc_maybe_include_tax( $variant, $variant->get_price() );
				// $variant_price = wc_price( $variant_price );

				// Check stock availability
				$disabled = '';
				if( ! $variant->is_purchasable() || ! $variant->is_in_stock() ) {
					$disabled = 'disabled';
				}

				// Check available stock if stock is managed
				$available_stock = '';
				if( $variant->managing_stock() ) {
					$manage_stock = true;
					$available_stock = $variant->get_stock_quantity();
				}

				// Write the option
				$variant_wrapper .= sprintf(
					'<option data-option-cost="%s" data-stock="%s" value="%s" %s>%s</option>',
					esc_attr( number_format( $variant_price, get_option( 'woocommerce_price_num_decimals', 2 ) ) ),
					esc_attr( $available_stock ),
					$variant_id,
					$disabled,
					$variant->get_name()
				);
			}
			$variant_wrapper .= '</select>';
			$wrapper_classes[] = 'pewc-variable-child-product-wrapper';
		}
		$description = '<div class="pewc-column-description"></div>';
	} else {
		// Simple product
		$wrapper_classes[] = 'pewc-simple-child-product-wrapper';

		$description = '';
	}

	// $price = pewc_maybe_include_tax( $child_product, $child_product->get_price() );
	// $price = wc_price( $price );
	$child_price = $child_product->get_price();
	if( ! empty( $item['child_discount'] ) && ! empty( $item['discount_type'] ) ) {
		$discounted_price = pewc_get_discounted_child_price( $child_price, $item['child_discount'], $item['discount_type'] );
		$price = wc_format_sale_price( $child_price, $discounted_price );
		$option_cost = pewc_maybe_include_tax( $child_product, $discounted_price );
	} else {
		$price = $child_product->get_price_html();
		$option_cost = pewc_maybe_include_tax( $child_product, $child_price );
	}


	if( ! $option_cost ) $option_cost = 0;

	// Check stock availability
	$disabled = '';
	if( ! $child_product->is_purchasable() || ! $child_product->is_in_stock() ) {
		$disabled = 'disabled';
	}
	// Check available stock if stock is managed
	$available_stock = '';
	if( $child_product->managing_stock() ) {
		$manage_stock = true;
		$available_stock = $child_product->get_stock_quantity();
	}

	$image_url = ( get_post_thumbnail_id( $child_product_id ) ) ? wp_get_attachment_url( get_post_thumbnail_id( $child_product_id ) ) : wc_placeholder_img_src();
	$image = '<img src="' . esc_url( $image_url ) . '">';

  $name = sprintf(
		'<h4 class="pewc-radio-image-desc"><a href="%s" target="%s">%s</a></h4>',
		get_permalink( $child_product_id ),
		apply_filters( 'pewc_child_product_title_target', '_blank' ),
		get_the_title( $child_product_id )
	);

	$price = sprintf(
		'<p class="pewc-column-price-wrapper">%s</p>',
		$price
	);

	$field_name = $id . '_child_product';

	$checkbox_id = $id . '_' . $child_product_id;

	if( $disabled ) {
		$wrapper_classes[] = 'pewc-checkbox-disabled';
	}

	$checked = ( $value == $id ) ? 'checked="checked"' : '';

	$quantity_field = '';
	if( $products_quantities == 'independent' ) {
		// Add a quantity field for each child checkbox
		// The name format is {$id}_child_quantity_{$child_product_id}
		// Where $id is the field ID and $child_product_id is the child product ID
		$quantity_field = sprintf(
			'<span class="pewc-quantity-wrapper"><input type="number" min="0" step="1" max="%s" class="pewc-form-field pewc-child-quantity-field" name="%s" value="0" %s></span>',
			$available_stock,
			esc_attr( $id ) . '_child_quantity_' . esc_attr( $child_product_id ),
			$disabled
		);
	}

	// $option_cost = pewc_maybe_include_tax( $child_product, $child_product->get_price() );
	// if( ! $option_cost ) $option_cost = 0;

	$add_button = sprintf(
		'%s<a href="#" class="button alt pewc-add-button">%s</a><a href="#" class="button pewc-add-button pewc-added">%s</a>',
		$quantity_field,
		__( 'Add', 'pewc' ),
		__( 'Remove', 'pewc' )
	);

  $checkbox = sprintf(
    '<div class="%s" data-manage-stock="%s"><label for="%s"><input data-option-cost="%s" type="checkbox" name="%s[]" id="%s" class="pewc-checkbox-form-field pewc-column-form-field" value="%s" %s %s>%s</label><div class="pewc-checkbox-desc-wrapper">%s%s%s<div class="pewc-column-variants-wrapper">%s</div>%s<p class="pewc-column-add-wrapper">%s</p></div></div>',
		join( ' ', $wrapper_classes ),
		$manage_stock,
    esc_attr( $checkbox_id ),
    esc_attr( number_format( $option_cost, get_option( 'woocommerce_price_num_decimals', 2 ) ) ),
    esc_attr( $field_name ),
    esc_attr( $checkbox_id ),
    esc_attr( $child_product_id ),
		esc_attr( $checked ),
		esc_attr( $disabled ),
    $image,
		$name,
		$price,
		$excerpt,
		$variant_wrapper,
		$description,
		$add_button
  );
  echo apply_filters( 'pewc_filter_checkbox', $checkbox, $child_product_id, $price, $id, $name, $item );
} ?>

</div><!-- .pewc-radio-images-wrapper -->
