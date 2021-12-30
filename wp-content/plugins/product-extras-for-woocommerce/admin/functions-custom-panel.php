<?php
/**
 * Functions for setting up the Product Add-Ons panel
 * @since 1.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * You can filter the Product Add-Ons name
 */
function pewc_get_post_type_labels() {
	return apply_filters(
		'pewc_filter_post_type_label',
		array(
			'single' => __( 'Product Add On' ),
			'plural' => __( 'Product Add Ons' )
		)
	);
}

/**
 * Product Add On tab / panel
 * @param $tabs	List of tabs
 */
function pewc_product_tabs( $tabs ) {
	$label = pewc_get_post_type_labels();
	$tabs['pewc'] = array(
		'label'		=> $label['plural'],
		'target'	=> 'pewc_options',
		'class'		=> array( 'show_if_simple', 'show_if_variable', 'show_if_simple_booking' ),
		'priority'	=> 100
	);
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'pewc_product_tabs' );

/**
 * Change tab icon
 */
function pewc_icon_style() { ?>
	<style>
		#woocommerce-product-data ul.wc-tabs li.pewc_options a:before { font-family: WooCommerce; content: '\e007'; }
	</style><?php
}
add_action( 'admin_head', 'pewc_icon_style' );

/**
 * Product Add-Ons tab options.
 */
function pewc_tab_options() {
	global $post;
	$class = pewc_is_pro() ? 'pewc-is-pro' : '';
	$has_migrated = pewc_has_migrated();
	if( $has_migrated ) {
		$class .= ' pewc_has_migrated';
	}
	if( pewc_enable_ajax_upload() == 'yes' ) {
		$class .= ' pewc-is-ajax-upload';
	} ?>

	<div id='pewc_options' class='panel woocommerce_options_panel pewc_panel <?php echo esc_attr( $class ); ?>'>

		<div class="options_group">
			<div class="options-group-inner">
				<ul class="new-field-list">
					<?php include( PEWC_DIRNAME . '/templates/admin/new-field-item.php' ); ?>
				</ul>
				<div class="new-option">
					<?php include( PEWC_DIRNAME . '/templates/admin/new-option.php' ); ?>
				</div>
				<div class="new-information-row">
					<?php include( PEWC_DIRNAME . '/templates/admin/views/information-row-new.php' ); ?>
				</div>

				<div class="product-extra-group-data" id="product_extra_groups">

					<!-- Start of the new-group-row element -->
					<?php include( PEWC_DIRNAME . '/templates/admin/new-group.php' ); ?>

					<?php include( PEWC_DIRNAME . '/templates/admin/new-conditional-row.php' ); ?>

					<?php $group_order = pewc_get_group_order( $post->ID ); ?>
					<?php $groups = pewc_get_extra_fields( $post->ID ); ?>

					<input type="hidden" id="pewc_group_order" value="<?php echo $group_order; ?>" name="pewc_group_order">

					<div id="pewc_group_wrapper">
						<?php pewc_display_product_groups( $groups, $post->ID ); ?>
					</div>

				</div><!-- #product_extra_groups -->

				<p><a href="#" class="button button-primary add_new_group"><?php _e( 'Add Group', 'pewc' ); ?></a></p>

			</div>
		</div>

		<?php include( PEWC_DIRNAME . '/templates/admin/group-settings.php' );
		// Deprecated in 2.1.0
		// include( PEWC_DIRNAME . '/templates/admin/import-groups.php' ); ?>
		<?php wp_nonce_field( 'add_new_pewc_group_nonce', 'add_new_pewc_group_nonce' ); ?>
		<div class="pewc-loading"><span class="spinner"></span></div>

	</div>

<?php }
add_action( 'woocommerce_product_data_panels', 'pewc_tab_options' );

/**
 * Display the groups
 * @param $groups 	_product_extra_groups meta data
 * @param $post_id	Post ID for product
 * @param $import		Deprecated
 * @param $global		Boolean, true if doing global addons
 *
 * Usually $post_id will be the ID of the product we're on
 * However, this function is also called by AJAX when importing groups
 * In which case, $post_id will be the ID of the product where groups are imported from
 */
function pewc_display_product_groups( $groups, $post_id, $import=false, $global=false ) {

	$licence = pewc_get_license_level();
	$group_count = 0;
	$has_migrated = pewc_has_migrated();

	if( $groups ) {

		$field_types = pewc_field_types();

		foreach( $groups as $group_id=>$group ) {

			if( $post_id === 0 ) {
				// Need to check if migration has taken place and make sure $group is formed correctly
				$group = pewc_get_global_groups( $group_id );
			} ?>

			<div data-group-count="<?php echo $group_count; ?>" data-group-id="<?php echo esc_attr( $group_id ); ?>" id="group-<?php echo esc_attr( $group_id ); ?>" class="group-row">

				<div class="new-field-table field-table collapse-panel">

					<?php $group_title = pewc_get_group_title( $group_id, $group, $has_migrated ); ?>

					<div class="wc-metabox">

						<div class="pewc-group-heading-wrap">

							<?php
							printf(
								'<h3 class="pewc-group-meta-heading">%s <span class="meta-item-id">%s</span>: <span class="pewc-display-title">%s</span></h3>',
								__( 'Group', 'pewc' ),
								'&#35;' . $group_id,
								stripslashes( $group_title )
							); ?>

							<?php include( PEWC_DIRNAME . '/templates/admin/group-meta-actions.php' ); ?>

						</div><!-- .pewc-group-heading-wrap -->

					</div><!-- .pewc-group-meta-table -->

					<?php do_action( 'pewc_after_group_title', $group_id, $group, $post_id, $import ); ?>

					<div class="pewc-all-fields-wrapper">

						<div class="pewc-group-meta-table wc-metabox">
							<div class="form-row">
								<div class="product-extra-field-third">
									<label>
										<?php _e( 'Group Title', 'pewc' ); ?>
										<?php echo wc_help_tip( 'Enter a title for this group that will be displayed on the product page. Leave blank if you wish.', 'pewc' ); ?>
									</label>
								</div>
								<div class="product-extra-field-two-thirds-right">
									<input type="text" class="pewc-group-title" name="_product_extra_groups[<?php echo $group_id; ?>][meta][group_title]" value="<?php echo stripslashes( $group_title ); ?>">
								</div>
							</div>
							<div class="form-row">
								<div class="product-extra-field-third pewc-description">
									<?php $description = pewc_get_group_description( $group_id, $group, $has_migrated ); ?>
									<label>
										<?php _e( 'Group Description', 'pewc' ); ?>
										<?php echo wc_help_tip( 'An optional description for the group', 'pewc' ); ?>
									</label>
								</div>
								<div class="product-extra-field-two-thirds-right">
									<textarea class="pewc-group-description" name="_product_extra_groups[<?php echo esc_attr( $group_id ); ?>][meta][group_description]"><?php echo esc_html( $description ); ?></textarea>
								</div>
							</div>
							<div class="form-row">
								<div class="product-extra-field-third">
									<?php $group_layout = pewc_get_group_layout( $group_id ); ?>
									<label>
										<?php _e( 'Group Layout', 'pewc' ); ?>
										<?php echo wc_help_tip( 'Choose how to display the fields in this group.', 'pewc' ); ?>
									</label>
								</div>
								<div class="product-extra-field-two-thirds-right">
									<select class="pewc-group-layout" name="_product_extra_groups[<?php echo $group_id; ?>][meta][group_layout]">
										<option <?php selected( $group_layout, 'ul', true ); ?> value="ul"><?php _e( 'Standard', 'pewc' ); ?></option>
										<option <?php selected( $group_layout, 'table', true ); ?> value="table"><?php _e( 'Table', 'pewc' ); ?></option>
									</select>
								</div>
							</div>
							<!-- <div class="form-row"> -->
								<!-- <label> -->
									<?php // _e( 'Group Conditions', 'pewc' ); ?>
								<!-- </label> -->
								<?php // include( PEWC_DIRNAME . '/templates/admin/group-condition.php' ); ?>
							<!-- </div> -->
						</div>

						<?php printf(
							'<h3 class="pewc-field-heading">%s</h3>',
							__( 'Fields', 'pewc' )
						); ?>
						<ul class="field-list">
							<?php if( isset( $group['items'] ) ) {
								$item_count = 0;
								foreach( $group['items'] as $item ) {
									if( isset( $item['field_type'] ) ) {
										include( PEWC_DIRNAME . '/templates/admin/field-item.php' );
										$item_count++;
									}
								}
							} ?>
						</ul>
						<p><a href="#" class="button add_new_field"><?php _e( 'Add Field', 'pewc' ); ?></a></p>
					</div><!-- .pewc-fields-wrapper -->
				</div>

			</div><!-- .group-row -->
		<?php $group_count++;
		}
	}
}

/**
 * Save the custom fields.
 */
function pewc_save_product_extra_options( $post_id ) {

	$has_migrated = pewc_has_migrated();

	// Check nonce

	if( $has_migrated ) {

		// Save the group order
		if( ! empty( $_POST['pewc_group_order'] ) ) {

			// Save all group IDs to this product
			update_post_meta( $post_id, 'group_order', sanitize_text_field( $_POST['pewc_group_order'] ) );
			$group_order = explode( ',', $_POST['pewc_group_order'] );

		} else {

			$group_order = array();
			delete_post_meta( $post_id, 'group_order' );

		}

		if( isset( $_POST['_product_extra_groups'] ) ) {

			/**
			 * Iterate through each group ID and save its meta
			 * @since 3.0.0
			 */
			$groups = $_POST['_product_extra_groups'];
			$group_ids = array();
			foreach( $groups as $group_id=>$group ) {

				if( ! in_array( $group_id, $group_order ) ) {
					// If we've found a group that isn't in the group order, delete it
					$delete = wp_delete_post( $group_id, true );

				} else {

					// Save group meta
					update_post_meta( $group_id, 'group_title', sanitize_text_field( $_POST['_product_extra_groups'][$group_id]['meta']['group_title'] ) );
					update_post_meta( $group_id, 'group_description', wp_kses_post( $_POST['_product_extra_groups'][$group_id]['meta']['group_description'] ) );
					update_post_meta( $group_id, 'group_layout', $_POST['_product_extra_groups'][$group_id]['meta']['group_layout'] );

					$group_ids[] = $group_id;
					$items = isset( $_POST['_product_extra_groups'][$group_id]['items'] ) ? $_POST['_product_extra_groups'][$group_id]['items'] : array();
					$params = pewc_get_field_params();

					$field_ids = array(); // Save the child fields to the group post
					foreach( $items as $item_id=>$item ) {

						// Save each parameter as post meta
						foreach( $params as $param ) {

							if( isset( $item[$param] ) ) {

								// Ensure the options array doesn't get out of sync
								if( in_array( $param, array( 'field_options', 'condition_field', 'condition_rule', 'condition_value' ) ) ) {
									$item[$param] = array_values( $item[$param] );
								}
								// Ensure the conditions array doesn't get out of sync
								if( $param == 'field_options' ) {
									$item[$param] = array_values( $item[$param] );
								}
								// Need to sanitise this
								update_post_meta( $item_id, $param, $item[$param] );
							} else {
								delete_post_meta( $item_id, $param );
							}
						}

						$field_ids[] = $item_id;

					}

					update_post_meta( $group_id, 'field_ids', $field_ids );

				}

			}

		}

	} else {

		if( isset( $_POST['_product_extra_groups'] ) ) {
			// Add some sanitisation
			$groups = pewc_sanitise_groups( $_POST['_product_extra_groups'] );
			update_post_meta( $post_id, '_product_extra_groups', $groups );
		} else {
			delete_post_meta( $post_id, '_product_extra_groups' );
		}

	}

	$display = ( isset( $_POST['pewc_display_groups'] ) ) ? $_POST['pewc_display_groups'] : 'standard';
	if( isset( $_POST['pewc_display_groups'] ) ) {
		$product = wc_get_product( $post_id );
		$product->update_meta_data( 'pewc_display_groups', sanitize_text_field( $display ) );
		$product->save();
	}

}
add_action( 'woocommerce_process_product_meta', 'pewc_save_product_extra_options'  );

/**
 * Available field types
 */
function pewc_field_types() {
	$field_types = array(
		'calculation'			=> __( 'Calculation', 'pewc' ),
		'checkbox'				=> __( 'Checkbox', 'pewc' ),
		'checkbox_group'	=> __( 'Checkbox Group', 'pewc' ),
		'date'						=> __( 'Date', 'pewc' ),
		'image_swatch'		=> __( 'Image Swatch', 'pewc' ),
		'information'			=> __( 'Information', 'pewc' ),
		'name_price'			=> __( 'Name Your Price', 'pewc' ),
		'number'					=> __( 'Number', 'pewc' ),
		'products'				=> __( 'Products', 'pewc' ),
		'radio'						=> __( 'Radio Group', 'pewc' ),
		'select'					=> __( 'Select', 'pewc' ),
		'text'						=> __( 'Text', 'pewc' ),
		'textarea'				=> __( 'Textarea', 'pewc' ),
		'upload'					=> __( 'Upload', 'pewc' )
	);
	return apply_filters( 'pewc_filter_field_types', $field_types );
}

/**
 * Group requirements
 * @return Array
 */
function pewc_group_requirements() {
	$group_requirements = array(
		'all'		=> __( 'All required fields', 'pewc' ),
		'depends'	=> __( 'All required fields if first field complete', 'pewc' )
	);
	return $group_requirements;
}

/**
 * Add the custom price label fields
 * @since 2.4.0
 */
function pewc_display_fields() {
	woocommerce_wp_text_input(
		array(
			'id'            => 'pewc_price_label',
			'label'         => __( 'Price label', 'pewc' ),
			'desc_tip'      => true,
			'description'   => __( 'Additional or replacement text for the price.', 'pewc' ),
		)
	);
	woocommerce_wp_select(
		array(
			'id'            => 'pewc_price_display',
			'label'         => __( 'Price label display', 'pewc' ),
			'desc_tip'      => true,
			'description'   => __( 'Decide where to display the label.', 'pewc' ),
			'options'				=> array(
				'before'			=> __( 'Before price', 'pewc' ),
				'after'				=> __( 'After price', 'pewc' ),
				'hide'				=> __( 'Hide price', 'pewc' )
			)
		)
	);
}
add_action( 'woocommerce_product_options_pricing', 'pewc_display_fields' );

// Save the custom fields
function pewc_save_custom_label_fields( $post_id ) {
	$product = wc_get_product( $post_id );
	$pewc_price_label = isset( $_POST['pewc_price_label'] ) ? $_POST['pewc_price_label'] : '';
	$product->update_meta_data( 'pewc_price_label', sanitize_text_field( $pewc_price_label ) );
	$pewc_price_display = isset( $_POST['pewc_price_display'] ) ? $_POST['pewc_price_display'] : '';
	$product->update_meta_data( 'pewc_price_display', sanitize_text_field( $pewc_price_display ) );
	$product->save();
}
add_action( 'woocommerce_process_product_meta', 'pewc_save_custom_label_fields' );
