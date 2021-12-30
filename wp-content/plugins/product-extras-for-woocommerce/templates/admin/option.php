<?php
/**
 * The markup for an option
 *
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<div class="product-extra-option-wrapper" data-option-count="<?php echo esc_attr( $option_count ); ?>">
	<div class="pewc-option-image">
		<?php $image_wrapper_classes = array(
			'pewc-field-image-' . $item_key . '_' . $option_count
		);
		$remove_class = '';
		$field_image = '';
		$src = trailingslashit( PEWC_PLUGIN_URL ) . 'assets/images/placeholder-small.png';
		$placeholder = trailingslashit( PEWC_PLUGIN_URL ) . 'assets/images/placeholder-small.png';
		$option_image = '';
		if( ! empty( $item['field_options'][$option_count]['image'] ) ) {
			$option_image = $item['field_options'][$option_count]['image'];
			$src = wp_get_attachment_image_src( $option_image );
			$src = $src[0];
			$image_wrapper_classes[] = 'has-image';
			$remove_class = 'remove-image';
		} ?>
		<div class="pewc-field-image <?php echo join( ' ', $image_wrapper_classes ); ?>">
			<div class='image-preview-wrapper'>
				<a href="#" class="pewc-upload-button pewc-upload-option-image <?php echo esc_attr( $remove_class ); ?>" data-item-id="<?php echo esc_attr( $item_key . '_' . $option_count ); ?>">
					<img data-placeholder="<?php echo $placeholder; ?>" src="<?php echo esc_url( $src ); ?>" style="height: 30px; width: 30px;">
				</a>
			</div>
			<input type="hidden" name="_product_extra_groups[<?php echo esc_attr( $group_id ); ?>][items][<?php echo esc_attr( $item_key ); ?>][field_options][<?php echo esc_attr( $option_count ); ?>][image]" class="pewc-image-attachment-id" value="<?php echo esc_attr( $option_image ); ?>">
		</div>
	</div>
	<div class="pewc-option-option">
		<input type="text" class="pewc-field-option-value" name="_product_extra_groups[<?php echo esc_attr( $group_id ); ?>][items][<?php echo esc_attr( $item_key ); ?>][field_options][<?php echo esc_attr( $option_count ); ?>][value]" value="<?php echo esc_attr( $item['field_options'][esc_attr( $key )]['value'] ); ?>">
	</div>
	<div class="pewc-option-price">
		<input type="number" class="pewc-field-option-price" name="_product_extra_groups[<?php echo esc_attr( $group_id ); ?>][items][<?php echo esc_attr( $item_key ); ?>][field_options][<?php echo esc_attr( $option_count ); ?>][price]" value="<?php echo esc_attr( $item['field_options'][esc_attr( $key )]['price'] ); ?>" step="0.01">
	</div>

	<?php do_action( 'pewc_after_option_params', $option_count, $group_id, $item_key, $item, $key ); ?>
	
	<div class="product-extra-field-10 pewc-actions pewc-select-actions">
		<span class="sort-option pewc-action"><span class="dashicons dashicons-menu"></span></span>
		<span class="remove-option pewc-action"><?php _e( 'Remove', 'pewc' ); ?></span>
	</div>

</div>
