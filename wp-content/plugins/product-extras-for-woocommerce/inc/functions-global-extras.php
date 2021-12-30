<?php
/**
 * Functions for global Product Add-Ons
 * @since 1.6.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add settings page
 * @since 1.6.0
 */
function pewc_add_global_settings() {
	if( pewc_is_group_public() == 'yes' ) {
		return;
	}
	add_submenu_page(
		'edit.php?post_type=pewc_product_extra',
		__( 'Global Add-Ons', 'pewc' ),
		__( 'Global Add-Ons', 'pewc' ),
		'manage_woocommerce',
		'global',
		'pewc_do_global_settings_page'
	);
}
add_action( 'admin_menu', 'pewc_add_global_settings' );

/**
 * Print the settings page
 * @since 1.6.0
 */
function pewc_do_global_settings_page() {
	wp_enqueue_media();
	$class = pewc_is_pro() ? 'pewc-is-pro' : '';
	if( pewc_enable_ajax_upload() == 'yes' ) {
		$class .= ' pewc-is-ajax-upload';
	} ?>
	<form name="pewc_global_settings_form" id="pewc_global_settings_form" class="<?php echo $class; ?>" method="post">
		<ul class="new-field-list">
			<?php include( PEWC_DIRNAME . '/templates/admin/new-field-item.php' ); ?>
		</ul>
		<div class="new-option">
			<?php include( PEWC_DIRNAME . '/templates/admin/new-option.php' ); ?>
		</div>
		<div class="new-information-row">
			<?php include( PEWC_DIRNAME . '/templates/admin/views/information-row-new.php' ); ?>
		</div>
		<?php include( PEWC_DIRNAME . '/templates/admin/new-global-set.php' ); ?>
	 	<div class="wrap pewc-global-settings product-extra-group-data">
	 		<?php printf( '<h1>%s</h2>', __( 'Global Product Add-Ons', 'pewc' ) ); ?>
	 		<p><?php _e( 'Add Product Add-Ons in bulk to your products. You can add Product Add-Ons to individual products by editing the product and clicking on the \'Product Add-Ons\' tab of the Product Data section.', 'pewc' ); ?></p>
			<?php
			echo '<div class="pewc-global-set-wrap">';

				include( PEWC_DIRNAME . '/templates/admin/new-conditional-row.php' );

				echo '<div id="pewc_group_wrapper">';

					if( ! pewc_has_migrated() ) {
						// Pre 3.0
						$globals = get_option( 'pewc_global_extras' );
						pewc_display_product_groups( $globals, 99999, false );
					} else {
						// 3.0
						$group_order = get_option( 'pewc_global_group_order', '' );
						if( $group_order ) {
							// pewc_display_product_groups expects an array with the group_id as the key
							$new_order = explode( ',', $group_order );
							$combined_order = array_combine( $new_order, $new_order );
							// 0 in the second param indicates global groups
							pewc_display_product_groups( $combined_order, 0, false );
						}

					}

				echo '</div>';

				printf(
					'<input type="hidden" id="pewc_global_group_order" name="pewc_global_group_order" value="%s">',
					esc_attr( $group_order )
				);

				echo '<div class="pewc-loading"><span class="spinner"></span></div>';

			echo '</div>'; ?>

			<p>
				<a class="button button-secondary" id="pewc_add_global_set" href="#"><?php _e( 'Add New Global Group', 'pewc' ); ?></a>
				&nbsp;
				<span style="display: inline-block;">
					<span class="spinner"></span>
					<button class="button button-primary" id="pewc_save_globals"><?php _e( 'Save Global Add-Ons', 'pewc' ); ?></button>
				</span>
			</p>

			<?php wp_nonce_field( 'pewc_global_set', 'pewc_global_set', true ); ?>
			<?php wp_nonce_field( 'add_new_pewc_group_nonce', 'add_new_pewc_group_nonce', true ); ?>

	 	</div>
	</form>
<?php }

/**
 * Add extra elements for global conditions
 * @since 1.6.0
 */
function pewc_global_after_group_title( $group_key, $group, $post_id, $import ) {
	$screen = get_current_screen();
	if( $screen->id != 'pewc_product_extra_page_global' ) {
		return;
	}
	include( PEWC_DIRNAME . '/templates/admin/global-rule.php' );
}
add_action( 'pewc_after_group_title', 'pewc_global_after_group_title', 10, 4 );

/**
 * Add extra elements for new global conditions
 * @since 1.6.0
 */
function pewc_global_after_new_group_title( $group_key, $group, $post_id, $import ) {
	$screen = get_current_screen();
	if( $screen->id != 'pewc_product_extra_page_global' ) {
		return;
	}
	include( PEWC_DIRNAME . '/templates/admin/new-global-rule.php' );
}
add_action( 'pewc_after_new_group_title', 'pewc_global_after_new_group_title', 10, 4 );

/**
 * Save the globals
 * @since 1.6.0
 */
function pewc_save_globals() {

	if( ! is_user_logged_in() ) {
		wp_send_json( array( 'error' => 1 ) );
	}

	if( ! current_user_can( 'manage_woocommerce' ) ) {
		wp_send_json( array( 'error' => 12 ) );
	}

	if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'pewc_global_set' ) ) {
		wp_send_json( array( 'error' => 13 ) );
	}

	if( isset( $_POST['form'] ) ) {

		// Check if migration has occurred
		$has_migrated = pewc_has_migrated();

		if( ! $has_migrated ) {

			parse_str( $_POST['form'], $form );
			if( isset( $form['_product_extra_groups']['GROUP_KEY'] ) ) {
				unset( $form['_product_extra_groups']['GROUP_KEY'] );
			}
			update_option( 'pewc_global_extras', $form['_product_extra_groups'] );

		} else {

			// Save the group order
			$group_order = ! empty( $_POST['order'] ) ? $_POST['order'] : '';
			update_option( 'pewc_global_group_order', $group_order );

			// Convert form data from object to array
			$form_obj = json_decode( stripslashes( $_POST['form'] ) );

			// Save form data into temporary arrays
			$global_rules = array();
			$child_products = array();
			$options = array();
			$rows = array();
			$condition_fields = array();
			$condition_rules = array();
			$condition_values = array();
			$all_field_ids = array();

			$all_field_params = array(); // This is going to be improved
			$all_params = pewc_get_field_params();

			foreach( $form_obj as $item ) {

				$value = $item->value;
				$name = $item->name;

				if( strpos( $name, '_product_extra_groups' ) !== false ) {

					$name = str_replace( '_product_extra_groups[', '', $name );
					$name = trim( $name, '][' );
					$name_array = explode( '][', $name );
					$group_id = $name_array[0];

					if( strpos( $name, 'group_title' ) !== false ) {
						update_post_meta( $group_id, 'group_title', sanitize_text_field( $value ) );
					}
					if( strpos( $name, 'group_description' ) !== false ) {
						update_post_meta( $group_id, 'group_description', sanitize_text_field( $value ) );
					}
					if( strpos( $name, 'operator' ) !== false ) {
						$global_rules['operator'] = $value;
					}

					if( $name_array[2] == 'all' && $name_array[3] == 'is_selected' ) {

						$global_rules[$group_id]['all']['is_selected'] = 'on';

					} else if( $name_array[2] == 'ids' && $name_array[3] == 'is_selected' ) {

						$global_rules[$group_id]['ids']['is_selected'] = 'on';

					} else if( $name_array[2] == 'ids' && $name_array[3] == 'products' ) {

						if( isset( $global_rules[$group_id]['ids']['products'] ) ) {
							$global_rules[$group_id]['ids']['products'][] = $value;
						} else {
							$global_rules[$group_id]['ids']['products'] = array( $value );
						}

					} else if( $name_array[2] == 'categories' && $name_array[3] == 'is_selected' ) {

						$global_rules[$group_id]['categories']['is_selected'] = 'on';

					} else if( $name_array[2] == 'categories' && $name_array[3] == 'cats' ) {

						if( isset( $global_rules[$group_id]['categories']['cats'] ) ) {
							$global_rules[$group_id]['categories']['cats'][] = $value;
						} else {
							$global_rules[$group_id]['categories']['cats'] = array( $value );
						}

					} else if( $name_array[1] == 'items' ) {

						// Saving field data here
						$fields_with_multiples = array( 'field_options', 'child_products', 'field_rows' );
						$field_id = $name_array[2];
						$all_field_ids[$group_id][$field_id] = $field_id;
						$param = $name_array[3];

						if( ! isset( $all_field_params[$field_id] ) ) {
							$all_field_params[$field_id] = $all_params;
						}
						// Remove found params from list, then we'll iterate through the list at the end to delete any params we've missed
						// That way, checkbox fields will get updated
						// This will be completely removed when we list groups using standard post type list
						if( ( $key = array_search( $param, $all_field_params[$field_id] ) ) !== false) {
						    unset( $all_field_params[$field_id][$key] );
						}

						if( ! in_array( $param, $fields_with_multiples ) && strpos( $param, 'condition' ) === false ) {

							// Simple value
							if( $value ) {
								update_post_meta( $field_id, $param, $value );
							} else {
								delete_post_meta( $field_id, $param );
							}

						} else if( $param == 'child_products' ) {

							if( isset( $child_products[$field_id] ) ) {
								$child_products[$field_id][] = $value;
							} else {
								$child_products[$field_id] = array( $value );
							}

						} else if( $param == 'field_options' ) {

							$option_count = $name_array[4];
							$option_param = $name_array[5];
							$options[$field_id][$option_count][$option_param] = $value;

						} else if( $param == 'field_rows' ) {

							$row_count = $name_array[4];
							$row_param = $name_array[5];
							$rows[$field_id][$row_count][$row_param] = $value;

						} else if( $param == 'condition_action' || $param == 'condition_match' ) {

							if( $value ) {
								update_post_meta( $field_id, $param, $value );
							} else {
								delete_post_meta( $field_id, $param );
							}

						} else if( $param == 'condition_field' ) {

							if( isset( $condition_fields[$field_id] ) ) {
								$condition_fields[$field_id][] = $value;
							} else {
								$condition_fields[$field_id] = array( $value );
							}

						} else if( $param == 'condition_rule' ) {

							if( isset( $condition_rules[$field_id] ) ) {
								$condition_rules[$field_id][] = $value;
							} else {
								$condition_rules[$field_id] = array( $value );
							}

						} else if( $param == 'condition_value' ) {

							if( isset( $condition_values[$field_id] ) ) {
								$condition_values[$field_id][] = $value;
							} else {
								$condition_values[$field_id] = array( $value );
							}

						}

					}
				}
			}

			if( $global_rules ) {
				foreach( $global_rules as $key=>$value ) {
					update_post_meta( $key, 'global_rules', $value );
				}
			}
			if( $child_products ) {
				foreach( $child_products as $key=>$value ) {
					update_post_meta( $key, 'child_products', $value );
				}
			}
			if( $condition_fields ) {
				foreach( $condition_fields as $key=>$value ) {
					update_post_meta( $key, 'condition_field', $value );
				}
			}
			if( $condition_rules ) {
				foreach( $condition_rules as $key=>$value ) {
					update_post_meta( $key, 'condition_rule', $value );
				}
			}
			if( $condition_values ) {
				foreach( $condition_values as $key=>$value ) {
					update_post_meta( $key, 'condition_value', $value );
				}
			}
			if( $options ) {
				foreach( $options as $key=>$value ) {
					update_post_meta( $key, 'field_options', $value );
				}
			}
			if( $rows ) {
				foreach( $rows as $key=>$value ) {
					update_post_meta( $key, 'field_rows', $value );
				}
			}
			if( $all_field_ids ) {
				foreach( $all_field_ids as $group_id=>$ids ) {
					update_post_meta( $group_id, 'field_ids', array_keys( $ids ) );
				}
			}

			// Unset any checkboxes
			if( $all_field_params ) {
				foreach( $all_field_params as $field_id=>$field ) {
					if( $field ) {
						foreach( $field as $key=>$value ) {
							delete_post_meta( $field_id, $value );
						}
					}
				}
			}

		}

		wp_send_json( array( 'saved' => 1 ) );
		exit;
	}
	wp_send_json( array( 'error' => 14 ) );
	exit;
}
add_action( 'wp_ajax_pewc_save_globals', 'pewc_save_globals' );

/**
 * Get the list of possible rules
 * @since 1.6.0
 */
function pewc_get_group_rules() {
	$rules = array(
		'all'		=> array(
			'id'							=> 'all',
			'title'						=> __( 'On all products', 'pewc' ),
			'verification'		=> 'pewc_verify_all_products' // Callback to verify if a product meets the rule
		),
		'ids'		=> array(
			'id'							=> 'ids',
			'title'						=> __( 'By product', 'pewc' ),
			'callback'				=> 'pewc_show_products_rule', // Callback to render the rule's markup
			'verification'		=> 'pewc_verify_products_rule' // Callback to verify if a product meets the rule
		),
		'categories'		=> array(
			'id'							=> 'categories',
			'title'						=> __( 'By category', 'pewc' ),
			'callback'				=> 'pewc_show_categories_rule', // Callback to render the rule's markup
			'verification'		=> 'pewc_verify_categories_rule' // Callback to verify if a product meets the rule
		)
	);
	return apply_filters( 'pewc_global_rules', $rules );
}

/**
 * Get the list of all extras, including globals
 * @since 1.6.0
 */
function pewc_filter_product_extra_groups( $product_extra_groups, $product_id ) {

	if( is_admin() ) {
		// Don't display global groups in the product edit screen
		return $product_extra_groups;
	}

	// Check if post 3.0 migration has been done
	$has_migrated = pewc_has_migrated();

	if( ! $has_migrated ) {

		$globals = get_option( 'pewc_global_extras' );

	} else {

		// Build an array for $globals so that it matches the pre-3.0 format
		$global_order = pewc_get_all_global_group_ids();

		$globals = array();
		if( $global_order ) {
			$global_ids = explode( ',', $global_order );
			foreach( $global_ids as $group_id ) {
				// Check whether we need to meet all rules or any rule
				$globals[$group_id]['meta']['group_title'] = pewc_get_group_title( $group_id, array(), true );
				$globals[$group_id]['meta']['group_description'] = pewc_get_group_description( $group_id, array(), true );
				$globals[$group_id]['global_rules'] = pewc_get_global_rules( $group_id, array() );
				$globals[$group_id]['items'] = pewc_get_group_fields( $group_id );
			}
		}

	}

	// In case the product doesn't have local extras
	if( false === $product_extra_groups || ! is_array( $product_extra_groups ) ) {
		$product_extra_groups = array();
	}

	if( $globals ) {

		// Now we need to check through each global rule to see if it applies for this product
		foreach( $globals as $global_id=>$global ) {

			$operator = pewc_get_group_operator( $global_id, $global );
			$verified = ( $operator == 'all' ) ? true : false;

			// Iterate through each rule
			$rules = pewc_get_group_rules();

			$rule_set = false; // Check that there is a rule set

			foreach( $rules as $rule ) {

				// Verify the rule if it's set
				if( isset( $global['global_rules'][$rule['id']]['is_selected'] ) && $global['global_rules'][$rule['id']]['is_selected'] == 'on' && isset( $rule['verification'] ) && function_exists( $rule['verification'] ) ) {

					$rule_verified = call_user_func( $rule['verification'], $product_id, $global, $rule );
					$rule_set = true;

					if( $rule_verified && $operator == 'any' ) {
						// If we only need to meet one rule, we can break here
						$verified = true;
						break;
					} else if( ! $rule_verified && $operator == 'all' ) {
						// If we need to meet all rules and one fails, we're bust
						$verified = false;
						break;
					} else if( $rule_verified && $operator == 'all' ) {
						// $verified = true;
					}

				}

			}

			if( ( $verified && $rule_set ) ) {
				// If rules are met correctly, then add the global extra
				$product_extra_groups[$global_id] = $global;
			}

		}

	}

	return $product_extra_groups;
}
add_filter( 'pewc_filter_product_extra_groups', 'pewc_filter_product_extra_groups', 10, 2 );

/**
 * Show select field for all products by ID and title
 * @return HTML
 */
function pewc_show_products_rule( $group_key, $group, $rule ) {

	// Get the saved value for this rule, if set
	$set_products = array();

	$group_rule_values = pewc_get_global_rules( $group_key, $group );
	if( isset( $group_rule_values['ids']['products'] ) ) {
		$set_products = $group_rule_values['ids']['products'];
	}

	$args = array(
		'numberposts' => 9999,
	 	'status'			=> 'publish',
		'return'			=> 'ids'
	);
	$products = wc_get_products( $args );

	$select = '';

	if( $products ) {
		$select = '<select multiple="multiple" class="pewc-rule-field pewc-rule-select" name="_product_extra_groups[' . esc_attr( $group_key ) . '][global_rules][ids][products][]" data-name="_product_extra_groups[GROUP_KEY][global_rules][ids][products][]" id="_product_extra_groups[' . esc_attr( $group_key ) . '][global_rules][ids][products]">';
		foreach( $products as $id ) {
			$selected = ( in_array( $id, $set_products ) ) ? 'selected="selected"' : '';
			$select .= '<option ' . $selected . ' value="'. $id . '">' . get_the_title( $id ) . '</option>';
		}
		$select .= '</select>';
	}
	return $select;

}

/**
 * Show select field for all products by ID and title
 * @return HTML
 */
function pewc_show_categories_rule( $group_key, $group, $rule ) {
	// Get the saved value for this rule, if set
	$set_categories = array();
	$group_rule_values = pewc_get_global_rules( $group_key, $group );
	if( isset( $group_rule_values['categories']['cats'] ) ) {
		$set_categories = $group_rule_values['categories']['cats'];
	}

	$args = array(
		'taxonomy'	=> 'product_cat',
		'fields'		=> 'ids'
	);
	$categories = get_terms( $args );

	$select = '';
	if( $categories ) {
		$select = '<select multiple="multiple" class="pewc-rule-field pewc-rule-select" name="_product_extra_groups[' . esc_attr( $group_key ) . '][global_rules][categories][cats][]" data-name="_product_extra_groups[GROUP_KEY][global_rules][categories][cats][]" id="_product_extra_groups[' . esc_attr( $group_key ) . '][global_rules][categories][cats]">';
		foreach( $categories as $id ) {
			$selected = ( in_array( $id, $set_categories ) ) ? 'selected="selected"' : '';
			$term = get_term_by( 'id', $id, 'product_cat' );
			$select .= '<option ' . $selected . ' value="'. $id . '">' . $term->name . '</option>';
		}
		$select .= '</select>';
	}
	return $select;

}

/**
 * Verify all products rule
 * @return Boolean
 */
function pewc_verify_all_products( $product_id, $global, $rule ) {

	$verified = false;
	if( isset( $global['global_rules']['all']['is_selected'] ) && $global['global_rules']['all']['is_selected'] == 'on' ) {
		$verified = true;
	}
	return apply_filters( 'pewc_after_verify_all_products', $verified, $product_id, $global, $rule );

}

/**
 * Verify the products rule
 * @return Boolean
 */
function pewc_verify_products_rule( $product_id, $global, $rule ) {

	$verified = false;
	if( isset( $global['global_rules']['ids']['is_selected'] ) && isset( $global['global_rules']['ids']['products'] ) ) {
		// This rule is set so check whether the product ID is in the list of specified IDs
		$permitted_ids = $global['global_rules']['ids']['products'];
		if( in_array( $product_id, $permitted_ids ) ) {
			$verified = true;
		}
	}
	return apply_filters( 'pewc_after_pewc_verify_products_rule', $verified, $product_id, $global, $rule );

}

/**
 * Verify the categories rule
 * @return Boolean
 */
function pewc_verify_categories_rule( $product_id, $global, $rule ) {

	$verified = false;
	if( isset( $global['global_rules']['categories']['is_selected'] ) && isset( $global['global_rules']['categories']['cats'] ) ) {
		// This rule is set so check whether the product is in the specified categories
		$permitted_cats = $global['global_rules']['categories']['cats'];
		if( has_term( $permitted_cats, 'product_cat', $product_id ) ) {
			$verified = true;
		}
	}
	return apply_filters( 'pewc_after_pewc_verify_categories_rule', $verified, $product_id, $global, $rule );

}
