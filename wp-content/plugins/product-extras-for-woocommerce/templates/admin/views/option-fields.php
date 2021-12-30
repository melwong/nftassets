<?php
/**
 * The markup for a field item in the admin
 *
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div id="pewc_option_<?php echo esc_attr( $group_id ); ?>_<?php echo esc_attr( $item_key ); ?>" class="pewc-fields-wrapper pewc-option-fields">
	<div>
		<div class="pewc-option-image">&nbsp;</div>
		<div class="pewc-option-option">
			<?php printf( '<div class="pewc-label">%s</div>', __( 'Option', 'pewc' ) ); ?>
		</div>
		<div class="pewc-option-price">
			<?php printf( '<div class="pewc-label">%s</div>', __( 'Price', 'pewc' ) ); ?>
		</div>
		<?php do_action( 'pewc_after_option_params_titles', $group_id, $item_key, $item ); ?>
	</div>
	<?php // Add option data to wrapper
	$option_count = 0;
	$data = array();
	if( ! empty( $item['field_options'] ) ) {
		foreach( $item['field_options'] as $key=>$value ) {
			// Escaped this 2.4.5
			$data[] = $value['value'];
		}
	}
	$data = json_encode( $data ); ?>
	<div class="pewc-field-options-wrapper pewc-data-options" data-options='<?php echo esc_attr( $data ); ?>'>
		<?php $option_count = 0;
		if( ! empty( $item['field_options'] ) ) {
			foreach( $item['field_options'] as $key=>$value ) {
				include( PEWC_DIRNAME . '/templates/admin/option.php' );
				$option_count++;
			}
		} ?>
	</div>
	<p><a href="#" class="button add_new_option"><?php _e( 'Add Option', 'pewc' ); ?></a></p>
	<p class="pewc-select-field-only">
		<?php $checked = ! empty( $item['first_field_empty'] ); ?>
		<input <?php checked( $checked, 1, true ); ?> type="checkbox" class="pewc-field-item pewc-first-field-empty" name="_product_extra_groups[<?php echo esc_attr( $group_id ); ?>][items][<?php echo esc_attr( $item_key ); ?>][first_field_empty]" value="1">
		<label class="pewc-checkbox-field-label">
			<?php _e( 'First field is instruction only', 'pewc' ); ?>
			<?php echo wc_help_tip( 'Select this if your first option is an instruction to the user, e.g. "Pick an item"', 'pewc' ); ?>
		</label>
	</p>
</div><!-- .pewc-fields-wrapper -->
