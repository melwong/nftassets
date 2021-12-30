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

<div class="product-extra-option-wrapper" data-option-count="">
	<div class="pewc-option-image">
		<?php	$placeholder = trailingslashit( PEWC_PLUGIN_URL ) . 'assets/images/placeholder-small.png'; ?>
		<div class="pewc-field-image">
			<div class='image-preview-wrapper'>
				<a href="#" class="pewc-upload-button pewc-upload-option-image" data-item-id="">
					<img data-placeholder="<?php echo $placeholder; ?>" src="<?php echo esc_url( $placeholder ); ?>" style="height: 30px; width: 30px;">
				</a>
			</div>
			<input type="hidden" name="" class="pewc-image-attachment-id" value="">
		</div>
	</div>
	<div class="pewc-option-option">
		<input type="text" class="pewc-field-option-value" name="" value="">
	</div>
	<div class="pewc-option-price">
		<input type="number" class="pewc-field-option-price" name="" value="" step="0.01">
	</div>
	<div class="product-extra-field-10 pewc-actions pewc-select-actions">
		<span class="sort-option pewc-action"><span class="dashicons dashicons-menu"></span></span>
		<span class="remove-option pewc-action"><?php _e( 'Remove', 'pewc' ); ?></span>
	</div>
</div>
