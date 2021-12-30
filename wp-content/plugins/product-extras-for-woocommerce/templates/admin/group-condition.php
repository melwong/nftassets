<?php
/**
 * The markup for a conditional row, i.e. one condition
 *
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php $style = 'style="display: none;"';
if( isset( $group['condition_field'] ) ) {
	$style = 'style="display: block;"';
} ?>
<div class="product-extra-conditional-row product-extra-action-match-row" <?php echo $style; ?>>

	<div class="product-extra-field-half">
		<?php $actions = pewc_get_actions();
		$action = '';
		if( isset( $group['condition_action'] ) ) {
			$action = $group['condition_action'];
		}
		if( ! empty( $actions ) ) { ?>
			<select class="pewc-condition-action" name="_product_extra_groups[<?php echo esc_attr( $group_key ); ?>][condition_action]">
			<?php foreach( $actions as $key=>$value ) {
				$selected = selected( $action, $key, false );
				echo '<option ' . $selected . ' value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';
			} ?>
			</select>
		<?php } ?>
	</div>

	<div class="product-extra-field-half">
		<?php $matches = pewc_get_matches();
		$match = '';
		if( isset( $group['condition_match'] ) ) {
			$match = $group['condition_match'];
		}
		if( ! empty( $matches ) ) { ?>
			<select class="pewc-condition-condition" name="_product_extra_groups[<?php echo esc_attr( $group_key ); ?>][condition_match]">
			<?php foreach( $matches as $key=>$value ) {
				$selected = selected( $match, $key, false );
				echo '<option ' . $selected . ' value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';
			} ?>
			</select>
		<?php } ?>
	</div>
</div>

<?php
if( isset( $item['condition_field'] ) ) {
	$condition_count = 0;
	foreach( $item['condition_field'] as $condition ) { ?>
		<div class="product-extra-conditional-row product-extra-conditional-rule" data-condition-count="<?php echo esc_attr( $condition_count ); ?>">
			<div class="product-extra-field-third">
				<?php $fields = pewc_get_all_fields( $group );
				$id = 'pewc_group_' . $group_key;
				unset( $fields[$id] );
				$field = '';
				if( isset( $group['condition_field'][$condition_count] ) ) {
					$field = $group['condition_field'][$condition_count];
				}
				// Get the field type of the selected field
				$cond_group_id = pewc_get_group_id( $field );
				$cond_field_id = pewc_get_field_id( $field );
				$field_type = '';
				if( isset( $groups[$cond_group_id]['items'][$cond_field_id]['field_type'] ) ) {
					$field_type = $groups[$cond_group_id]['items'][$cond_field_id]['field_type'];
				}
				if( ! empty( $fields ) ) { ?>
					<select class="pewc-condition-field pewc-condition-select" name="_product_extra_groups[<?php echo esc_attr( $group_key ); ?>][condition_field][<?php echo esc_attr( $condition_count ); ?>]" id="condition_field_<?php echo esc_attr( $group_key ); ?>_<?php echo esc_attr( $item_key ); ?>_<?php echo esc_attr( $condition_count ); ?>" data-group-id="<?php echo esc_attr( $group_key ); ?>" data-item-id="<?php echo esc_attr( $item_key ); ?>" data-condition-id="<?php echo esc_attr( $condition_count ); ?>" data-field-type="<?php echo $field_type; ?>">
					<?php foreach( $fields as $key=>$value ) {
						$selected = selected( $field, $key, false );
						echo '<option ' . $selected . ' value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';
					} ?>
					</select>
				<?php } ?>
			</div>

			<div class="product-extra-field-sixth">
				<?php $class = "pewc-condition-rule pewc-condition-select";
				$rules = pewc_get_rules();
				$allow_multiple = ! empty( $groups[$cond_group_id]['allow_multiple'] ) ? true : false;
				if( $field_type == 'image_swatch' || $field_type == 'checkbox_group' || ( $field_type == 'products' && $allow_multiple ) ) {
					$class .= ' pewc-has-multiple';
				} ?>
				<select class="<?php echo $class; ?>" name="_product_extra_groups[<?php echo esc_attr( $group_key ); ?>][items][condition_rule][<?php echo esc_attr( $condition_count ); ?>]" id="condition_rule_<?php echo esc_attr( $group_key ); ?>_<?php echo esc_attr( $condition_count ); ?>" data-group-id="<?php echo esc_attr( $group_key ); ?>" data-item-id="<?php echo esc_attr( $item_key ); ?>" data-condition-id="<?php echo esc_attr( $condition_count ); ?>">
					<?php
					$rule = 'not-selected';
					if( isset( $group['condition_rule'][$condition_count] ) ) {
						$rule = $group['condition_rule'][$condition_count];
					}
					foreach( $rules as $key=>$value ) {
						$selected = selected( $rule, $key, false );
						echo '<option ' . $selected . ' value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>';
					} ?>
				</select>
			</div>

			<div class="product-extra-field-half product-extra-field-last pewc-condition-value-field">
				<?php $value = '';
				if( isset( $group['condition_value'][$condition_count] ) ) {
					$value = $group['condition_value'][$condition_count];
				}
				if( $field_type == 'text' ) { ?>
					<input class="pewc-condition-value pewc-condition-set-value" type="text" name="_product_extra_groups[<?php echo esc_attr( $group_key ); ?>][condition_value][<?php echo esc_attr( $condition_count ); ?>]" id="condition_value_<?php echo esc_attr( $group_key ); ?>_<?php echo esc_attr( $condition_count ); ?>" data-group-id="<?php echo esc_attr( $group_key ); ?>" data-condition-id="<?php echo esc_attr( $condition_count ); ?>" value="<?php echo esc_attr( $value ); ?>">
				<?php } else if( $field_type == 'number' ) { ?>
					<input class="pewc-condition-value pewc-condition-set-value" type="number" name="_product_extra_groups[<?php echo esc_attr( $group_key ); ?>][condition_value][<?php echo esc_attr( $condition_count ); ?>]" id="condition_value_<?php echo esc_attr( $group_key ); ?>_<?php echo esc_attr( $condition_count ); ?>" data-group-id="<?php echo esc_attr( $group_key ); ?>" data-condition-id="<?php echo esc_attr( $condition_count ); ?>" value="<?php echo esc_attr( $value ); ?>">
				<?php } else if( $field_type == 'select' || $field_type == 'radio' || $field_type == 'image_swatch' || $field_type == 'products' || $field_type == 'checkbox_group' ) { ?>
					<select class="pewc-condition-value pewc-condition-set-value" name="_product_extra_groups[<?php echo esc_attr( $group_key ); ?>][condition_value][<?php echo esc_attr( $condition_count ); ?>]" id="condition_value_<?php echo esc_attr( $group_key ); ?>_<?php echo esc_attr( $condition_count ); ?>" data-group-id="<?php echo esc_attr( $group_key ); ?>" data-condition-id="<?php echo esc_attr( $condition_count ); ?>">
						<?php // Populate the select field
						if( $field_type == 'products' ) {
							$field_options = $groups[$cond_group_id]['items'][$cond_field_id]['child_products'];
							if( $field_options ) {
								foreach( $field_options as $option ) {
									$selected = selected( $value, $option, false ); ?>
									<option <?php echo $selected; ?> value="<?php echo esc_attr( $option ); ?>"><?php echo esc_attr( $option ); ?></option>
								<?php }
							}
						} else {
							$field_options = $groups[$cond_group_id]['items'][$cond_field_id]['field_options'];
							if( $field_options ) {
								foreach( $field_options as $option ) {
									$selected = selected( $value, $option['value'], false ); ?>
									<option <?php echo $selected; ?> value="<?php echo esc_attr( $option['value'] ); ?>"><?php echo esc_attr( $option['value'] ); ?></option>
								<?php }
							}
						} ?>
					</select>
				<?php } else if( $field_type == 'checkbox' ) { ?>
					<span class="pewc-checked-placeholder"><?php _e( 'Checked', 'pewc' ); ?></span>
					<input class="pewc-condition-value pewc-condition-set-value" type="hidden" name="_product_extra_groups[<?php echo esc_attr( $group_key ); ?>][condition_value][<?php echo esc_attr( $condition_count ); ?>]" id="condition_value_<?php echo esc_attr( $group_key ); ?>_<?php echo esc_attr( $condition_count ); ?>" data-group-id="<?php echo esc_attr( $group_key ); ?>" data-condition-id="<?php echo esc_attr( $condition_count ); ?>" value="__checked__">
				<?php } ?>

				<span class="remove-condition pewc-action"><?php _e( 'Remove', 'pewc' ); ?></span>

			</div>

		</div><!-- .product-extra-conditional-row -->
	<?php $condition_count++;
	}
}
?>
<p><a href="#" class="button add_new_condition"><?php _e( 'Add Condition', 'pewc' ); ?></a></p>
