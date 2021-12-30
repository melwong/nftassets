<?php
/**
 * Functions for adding product to cart
 * @since 1.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add product_extra field prices to cart item.
 */
function pewc_wc_calculate_total( $cart_obj ) {
	if( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	// Iterate through each cart item
	foreach( $cart_obj->get_cart() as $key=>$value ) {
		// Set the price to include extras
		if( isset( $value['product_extras']['price_with_extras'] ) ) {
			$new_price = apply_filters( 'pewc_filter_calculated_cost_before_calculate_totals', $value['product_extras']['price_with_extras'], $value, $key );
			$value['data']->set_price( floatval( $new_price ) );
		}
	}
}
add_action( 'woocommerce_before_calculate_totals', 'pewc_wc_calculate_total', 10, 1 );

function pewc_minicart_item_price( $price, $cart_item, $cart_item_key ) {
	if( ! empty( $cart_item['product_extras']['price_with_extras'] ) ) {
		$price = apply_filters( 'pewc_filter_calculated_cost_before_calculate_totals', $cart_item['product_extras']['price_with_extras'], $cart_item, $cart_item_key );
		$price = pewc_maybe_include_tax( $cart_item['data'], $price );
		$price = wc_price( $price );
	}
	return $price;
}
add_filter( 'woocommerce_cart_item_price', 'pewc_minicart_item_price', 10, 3 );

/**
 * Add product_extra flat rates to cart.
 */
function pewc_cart_calculate_fees() {
	if( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}
	$cart = WC()->cart->get_cart();
	$all_flat_rates = array();
	// Iterate through each cart item
	foreach( $cart as $cart_key=>$value ) {
		// Then through each group of Product Add-Ons
		if( isset( $value['product_extras']['groups'] ) ) {
			foreach( $value['product_extras']['groups'] as $group ) {
				foreach( $group as $group_key=>$item ) {
					// If the item is flat rate, then add it as a fee to the cart rather than include in the product price
					if( ! empty( $item['flat_rate'] ) ) {
						foreach( $item['flat_rate'] as $id=>$flat_rate ) {
							// Do it like this so we overwrite any duplicates
							if( ! empty( $all_flat_rates[$cart_key . '_' . $id] ) ) {
								// If we already have this ID, it's a global flat rate so only added once
								$flat_rate['label'] = apply_filters( 'pewc_filter_flat_rate_cart_global_label', $flat_rate['label'], $item );
							} else {
								// Include the product name in the label for clarity if it is not a global flat rate
								$product = wc_get_product( $value['product_id'] );
								// Include the variation
								$name = $product->get_name();
								if( apply_filters( 'pewc_allow_flat_rate_cart_label_variations', true, $item ) && ! empty( $value['variation_id'] ) ) {
									$variation = wc_get_product( $value['variation_id'] );
									$name = $variation->get_name();
								}
								$flat_rate['label'] = apply_filters( 'pewc_filter_flat_rate_cart_label', $name . ': ' . $flat_rate['label'], $item );

							}
							$all_flat_rates[$cart_key . '_' . $id] = $flat_rate;
						}
					}
				}
			}
		}
	}
	// If we have any flat rates, add them now
	if( $all_flat_rates ) {
		foreach( $all_flat_rates as $id=>$flat_rate ) {
			// But you can filter it if you like
			WC()->cart->add_fee(
				apply_filters( 'pewc_flat_rate_label', $flat_rate['label'], $id, $flat_rate ),
				$flat_rate['price'],
				true,
				'standard'
			);
		}
	}

}
add_action( 'woocommerce_cart_calculate_fees', 'pewc_cart_calculate_fees', 10 );

/**
 * Add cart item data.
 *
 * @param Array 		$cart_item_data Cart item meta data.
 * @param Integer   $product_id     Product ID.
 * @param Boolean  	$variation_id   Variation ID.
 *
 * @return Array
 */
function pewc_add_cart_item_data( $cart_item_data, $product_id, $variation_id, $quantity=0 ) {

	$product = wc_get_product( $product_id );

	if( ( $product->get_type() == 'variable' || $product->get_type() == 'variable-subscription' ) && $variation_id !== 0 ) {
		$product = wc_get_product( $variation_id );
	} else {
		$product = wc_get_product( $product_id );
	}

	$product_price = $product->get_price();
	$extra_price = 0;
	$title_str = $product->get_title();

	$post_data = $_POST;

	if( ! isset( $cart_item_data['product_extras'] ) ) {
		$cart_item_data['product_extras'] = array(
			'product_id'	=> $product_id,
			'title'			=> $title_str,
			'groups'		=> array()
		);
	} else {
		$cart_item_data['product_extras']['product_id']	= $product_id;
		$cart_item_data['product_extras']['title']	= $title_str;
		$cart_item_data['product_extras']['groups']	= array();
	}

	// Check for product_extra groups
	$product_extra_groups = pewc_get_extra_fields( $product_id );
	if( $product_extra_groups ) {

		foreach( $product_extra_groups as $group ) {

			if( isset( $group['items'] ) ) {

				foreach( $group['items'] as $item ) {

					if( empty( $item['field_price'] ) ) {
						$item['field_price'] = 0;
					}

					if( isset( $item['field_type'] ) && $item['field_type'] != 'upload' && $item['field_type'] != 'products' ) {

						$id = $item['id'];
						$price = 0;
						$value = isset( $_POST[$id] ) ? $_POST[$id] : '';

						$label = isset( $item['field_label'] ) ? $item['field_label'] : $item['id'];

						// If an extra is flat rate, it's not charged per product
						// It's a one-off fee that's added separately in the cart
						$is_flat_rate = isset( $item['field_flatrate'] ) ? true : false;
						$flat_rate_items = array();

						$is_percentage = ! empty( $item['field_percentage'] ) ? true : false;

						$group_id = $item['group_id'];
						$field_id = $item['field_id'];
						$field_type = $item['field_type'];

						$is_visible = pewc_get_conditional_field_visibility( $id, $item, $group['items'], $product_id, $_POST, $variation_id, $cart_item_data, $quantity );

						// Only add item if it's visible
						if( ! empty( $_POST[$id] ) && $is_visible ) {

							// Add the value of the field (not including the value of options)
							if( ! $is_flat_rate ) {
								$price = floatval( $item['field_price'] );
							} else {
								$flat_rate_items[$field_id] = array(
									'label'		=> $label,
									'price'		=> floatval( $item['field_price'] )
								);
							}

							// Check for Name Your Price
							if( $field_type == 'name_price' ) {
								if( ! $is_flat_rate ) {
									$price = $value;
								} else {
									$flat_rate_items[$field_id] = array(
										'label'		=> $label,
										'price'		=> $value
									);
								}
							}

							// Check for Calculation fields
							if( $field_type == 'calculation' ) {

								if( isset( $item['formula_action'] ) && $item['formula_action'] == 'cost' ) {

									if( ! $is_flat_rate ) {
										$price = $value;
									} else {
										$flat_rate_items[$field_id] = array(
											'label'		=> $label,
											'price'		=> $value
										);
									}

								} else {

									if( ! $is_flat_rate ) {
										// $price = $value;
									} else {
										$flat_rate_items[$field_id] = array(
											'label'		=> $label
											// 'price'		=> $value
										);
									}

								}

							}

							// Calculate price for per character fields
							if( ! empty( $item['per_character'] ) && ( $field_type == 'text' || $field_type == 'textarea' ) ) {
								$str_length = mb_strlen( str_replace( ' ', '', $value ) );
								if( ! empty( $item['field_freechars'] ) ) {
									$str_length -= absint( $item['field_freechars'] );
									$str_length = max( 0, $str_length );
								}
								if( ! $is_flat_rate ) {
									$price = $str_length * $price;
								} else {
									$flat_rate_items[$field_id] = array(
										'label'		=> $label,
										'price'		=> $str_length * floatval( $item['field_price'] )
									);
								}
							}

							// Calculate price for multiply fields
							if( ! empty( $item['multiply'] ) && ( $field_type == 'number' || $field_type == 'name_price' ) ) {
								if( ! $is_flat_rate ) {
									$price = $value * $price;
								} else {
									$flat_rate_items[$field_id] = array(
										'label'		=> $label,
										'price'		=> $value * floatval( $item['field_price'] )
									);
								}
							}

							// Calculate price for percentage fields
							if( $is_percentage && $field_type != 'calculation' ) {
								if( ! $is_flat_rate ) {
									$price = pewc_calculate_percentage_price( $item['field_price'], $product );
									// $price = $value * $price;
								} else {
									$flat_rate_items[$field_id] = array(
										'label'		=> $label,
										'price'		=> pewc_calculate_percentage_price( $item['field_price'], $product )
									);
								}
							}

							// Filtered by Bookings to include per unit cost for extras
							$price = apply_filters( 'pewc_filter_cart_item_data_price', $price, $cart_item_data, $item, $group_id, $field_id );

							// Find any additional cost for options and select fields
							if( ! empty( $item['field_options'] ) ) {

								// Record checkbox group values differently
								$checkbox_group_values = array();
								// Radio buttons are arrays, select are simple values
								if( $field_type == 'radio' || ( $field_type == 'image_swatch' && empty( $item['allow_multiple'] ) ) ) {
									$option_value = $value[0];
								} else {
									$option_value = $value;
								}

								// Some fields, like radio fields, have a key element
								// We use the key element, which has been sanitised, to find the value element, which is the proper label for the field value
								// The key element was only introduced in 2.4.5
								if( ! empty( $item['field_options'][0]['key'] ) ) {
									foreach( $item['field_options'] as $field_option ) {
										if( $field_option['key'] == $option_value ) {
											// Change the value (the label for the extra field) to the value element rather than the key element
											$value = $field_option['value'];
											break;
										}
									}
								}

								$options_total_price = 0;

								foreach( $item['field_options'] as $option ) {
									// If it's a checkbox group, we need to total all selected options
									if( $field_type == 'checkbox_group' || ( $field_type == 'image_swatch' && ! empty( $item['allow_multiple'] ) ) ) {
										if( ! empty( $option['price'] ) && in_array( $option['value'], $option_value ) ) {

											$option_price = $option['price'];

											if( $is_percentage ) {
												$option_price = pewc_calculate_percentage_price( $option_price, $product );
												// $option_price = pewc_maybe_include_tax( $product, $option_price );
											}
											if( ! $is_flat_rate ) {
												$price += floatval( $option_price );
												$option_price = pewc_maybe_include_tax( $product, $option_price );
												// $checkbox_group_values[] = $option['value'] . ' (' . wc_price( $option_price ) . ')';
												$checkbox_group_values[] = apply_filters( 'pewc_show_option_prices_in_cart', true, $item ) ? $option['value'] . ' (' . wc_price( $option_price ) . ')' : $option['value'];
											} else {
												$options_total_price += floatval( $option_price );
												$checkbox_group_values[] = apply_filters( 'pewc_show_option_prices_in_cart', true, $item ) ? $option['value'] . ' (' . wc_price( $option_price ) . ')' : $option['value'];
											}
										}
									} else if( ! empty( $option['price'] ) && $option['value'] == $option_value ) {
										if( ! $is_flat_rate ) {
											$price += floatval( $option['price'] );
											$value = apply_filters( 'pewc_show_option_prices_in_cart', true, $item ) ? $option_value . ' (' . wc_price( $option['price'] ) . ')' : $option_value;
											break;
										} else {
											$flat_rate_items[$field_id] = array(
												'label'		=> $label . ' (' . $option_value . ')',
												'price'		=> floatval( $option['price'] ) + floatval( $item['field_price'] )
											);
											$value = apply_filters( 'pewc_show_option_prices_in_cart', true, $item ) ? $option_value . ' (' . wc_price( $option['price'] ) . ')' : $option_value;
										}
									}
								}
								// Add the flat rate for the checkboxes here
								if( $field_type == 'checkbox_group' && $is_flat_rate ) {
									// Need to add the field cost as well
									$field_price = pewc_calculate_percentage_price( $item['field_price'], $product );
									$flat_rate_items[$field_id] = array(
										'label'		=> $label,
										'price'		=> floatval( $options_total_price ) + floatval( $field_price )
									);
									$value = $item['field_label'];
								}
							}

							// Just ensure we haven't ended up with any arrays here - $value will be displayed as meta data in the cart and order
							if( is_array( $value ) ) {
								$value = join( ' ', $value );
							}
							if( ! empty( $checkbox_group_values ) && $field_type == 'checkbox_group' ) {
								$value = join( ' | ', $checkbox_group_values );
							}

							// Filter the price of the product extra
							$price = apply_filters( 'pewc_add_cart_item_data_price', $price, $item, $product_id );

							$cart_item_data['product_extras']['groups'][$group_id][$field_id] = array(
								'type'			=> $item['field_type'],
								'label'			=> sanitize_text_field( $item['field_label'] ),
								'id'    		=> esc_attr( $id ),
								'group_id'  => $group_id,
								'field_id'  => $field_id,
								'price'   	=> floatval( $price ),
								'value'   	=> sanitize_text_field( $value ),
								'flat_rate'	=> $flat_rate_items
							);

							if( $field_type == 'calculation' && ( ! isset( $item['formula_action'] ) || $item['formula_action'] != 'cost' ) ) {
								unset( $cart_item_data['product_extras']['groups'][$group_id][$field_id]['price'] );
							}

							if( ! empty( $item['per_character'] ) ) {
								$cart_item_data['product_extras']['groups'][$group_id][$field_id]['per_character'] = 1;
							}
							$extra_price += floatval( $price );

							do_action( 'pewc_end_add_cart_item_data', $cart_item_data, $item, $group_id, $field_id );

							$cart_item_data = apply_filters( 'pewc_filter_end_add_cart_item_data', $cart_item_data, $item, $group_id, $field_id, $value );

						}

						// Ensure price can't be less than 0
						$new_price = floatval( $product_price ) + floatval( $extra_price );
						if( $new_price < 0 ) $new_price = 0;

						// Set parameter to record total product price including extras
						// Set this here before we start doing the uploads
						$cart_item_data['product_extras']['price_with_extras'] = floatval( $new_price );

					} else if( ( isset( $item['field_type'] ) && $item['field_type'] == 'products' ) && pewc_is_pro() ) {

						$field_id = $item['id'];
						// Add-on products are handled differently
						$is_visible = pewc_get_conditional_field_visibility( $field_id, $item, $group['items'], $product_id, $_POST, $variation_id, $cart_item_data, $quantity );

						if( ! empty( $_POST[$field_id . '_child_product'] ) && $is_visible ) {

							$child_product_id = $_POST[$field_id . '_child_product'];
							// Check if one or more child products has been selected and confirm that the main product is not in the array of child products
							$parent_product_hash = $_POST['pewc_product_hash'];
							$cart_item_data['product_extras']['products']['field_id'] = $field_id;
							if( ! isset( $cart_item_data['product_extras']['products']['child_products'] ) ) {
								$cart_item_data['product_extras']['products']['child_products'] = array();
							}
							// Add child product data to the main product
							if( ! is_array( $child_product_id ) ) {
								$cart_item_data['product_extras']['products']['child_products'][$child_product_id] = array(
									'child_product_id' 	=> $child_product_id,
									'field_id' 					=> $field_id,
									'quantities'				=> $_POST[$field_id . '_quantities'],
									'allow_none'				=> $_POST[$field_id . '_allow_none']
								);
							} else {
								// Adding multiple child products from checkboxes
								foreach( $child_product_id as $each_id ) {
									$cart_item_data['product_extras']['products']['child_products'][$each_id] = array(
										'child_product_id' 	=> $child_product_id,
										'field_id' 					=> $field_id,
										'quantities'				=> $_POST[$field_id . '_quantities'],
										'allow_none'				=> $_POST[$field_id . '_allow_none']
									);
								}

							}

							// $cart_item_data['product_extras']['products'][$parent_product_hash]['child_products'] = $_POST['_pewc_child_products'];
							// If we've added a child product to this item, let's link them in the cart
							$cart_item_data['product_extras']['products']['pewc_parent_product'] = $product_id;
							$cart_item_data['product_extras']['products']['parent_field_id'] = $parent_product_hash;
						}

					}

				}

			}

		}

	}

	// Do the file uploads separately

	if( pewc_enable_ajax_upload() == 'yes' ) {

		// Iterate through each field and get all the files

		foreach( $product_extra_groups as $group ) {

			if( isset( $group['items'] ) ) {

				foreach( $group['items'] as $item ) {

					$field_id = $item['field_id'];

					if( ! empty( $_POST['pewc_file_data'][$field_id] ) ) {
						// Using jQuery version

						// Make this an array like $_FILES
						if( empty( $files ) ) {

							$files = pewc_get_files_array( $_POST['pewc_file_data'][$field_id], $item['id'] );

						} else {

							$files = array_merge( $files, pewc_get_files_array( $_POST['pewc_file_data'][$field_id], $item['id'] ) );
						}

						$is_ajax_upload = true;

					}

				}

			}

		}

	} else if( ! empty( $_FILES ) ) {

		// Standard method

		$files = $_FILES;
		$is_ajax_upload = false;

	}

	if( isset( $files ) ) {

		$max = pewc_get_max_upload();
		$max_mb = $max * pow( 1024, 2 );

		foreach( $files as $id=>$file ) {

			// Work out group and field IDs from the $id
			$last_index = strrpos( $id, '_' );
			$field_id = substr( $id, $last_index + 1 ); // Find last instance of _
			$group_id = substr( $id, 0, $last_index ); // Remove _field_id from $id
			//$field_id = str_replace( '_', '', $field_id );
			$group_id = strrchr( $group_id, '_' );
			$group_id = str_replace( '_', '', $group_id );

			$group = $product_extra_groups[$group_id];
			$item = $group['items'][$field_id];
			$is_visible = pewc_get_conditional_field_visibility( $id, $item, $group['items'], $product_id, $_POST, $variation_id, $cart_item_data, $quantity );

			if( isset( $product_extra_groups[$group_id]['items'][$field_id] ) && $is_visible ) {

				$item = $product_extra_groups[$group_id]['items'][$field_id];
				$label = isset( $item['field_label'] ) ? $item['field_label'] : '';
				$price = isset( $item['field_price'] ) ? $item['field_price'] : '';

				$flat_rate_items = array();
				$is_flat_rate = isset( $item['field_flatrate'] ) ? true : false;
				if( $is_flat_rate ) {
					$flat_rate_items = array(
						$field_id => array(
							'label'		=> $label,
							'price'		=> floatval( $price )
						)
					);
				}

				$can_upload = pewc_can_upload();

				if( $can_upload ) {

					$uploads = array();

					foreach( $file['size'] as $i=>$size ) {

						// Check file size
						if( empty( $size ) || $size > $max_mb ) {
							// File size wrong
						} else {

							$upload_file = array(
								'file'			=> $file['file'][$i],
								'name'			=> $file['name'][$i],
								'display'		=> $file['name'][$i],
								'type'			=> $file['type'][$i],
								'tmp_name'	=> $file['tmp_name'][$i],
								'error'			=> $file['error'][$i],
								'size'			=> $file['size'][$i]
							);

							if( isset( $file['url'][$i] ) ) {
								$upload_file['url'] = esc_url( $file['url'][$i] );
							}

							if( ! $is_ajax_upload ) {

								// We need to upload the files if they haven't already been uploaded via AJAX
								$upload = pewc_handle_upload( $upload_file );
								$upload['display'] = $file['name'][$i];

								if ( empty( $upload['error'] ) && ! empty( $upload['file'] ) ) {
									$uploads[] = $upload;
								}

							} else {

								// AJAX upload has already been done
								$base_url = get_site_url();
								$truncated_url = $upload_file['url'];
								// Stitch our URL back together again
								$upload_file['url'] = $base_url . $truncated_url;
								$uploads[] = $upload_file;

							}

						}

					}

					$cart_item_data['product_extras']['groups'][$group_id][$field_id] = array(
						'files'			=> $uploads,
						// 'file'			=> $upload['file'], // Save this so we can delete file later
						'type'			=> 'upload',
						'label'			=> sanitize_text_field( $label ),
						'id'    		=> esc_attr( $id ),
						'group_id'  => $group_id,
						'field_id' 	=> $field_id,
						'price'   	=> floatval( $price ),
						// 'url'   		=> wc_clean( $upload['url'] ),
						// 'display' 	=> basename( wc_clean( $upload['url'] ) ),
						'flat_rate'	=> $flat_rate_items
					);

					// Only add the cost of the extra to the product price if it's not flat rate
					if( ! $is_flat_rate ) {
						$extra_price += floatval( $price );
					}

					// Ensure price can't be less than 0
					$new_price = floatval( $product_price ) + floatval( $extra_price );
					if( $new_price < 0 ) $new_price = 0;

					// Set parameter to record total product price including extras
					$cart_item_data['product_extras']['price_with_extras'] = floatval( $new_price );

				}

			}

		}

	}

	return $cart_item_data;

}
// Add item data to the cart.
add_filter( 'woocommerce_add_cart_item_data', 'pewc_add_cart_item_data', 10, 4 );

/**
 * Validate cart item data.
 *
 * @param Array 		$cart_item_data Cart item meta data.
 * @param Integer   $product_id     Product ID.
 * @param Boolean  	$variation_id   Variation ID.
 *
 * @return Array
 */
function pewc_validate_cart_item_data( $passed, $product_id, $quantity, $variation_id=null, $cart_item_data=array() ) {

	// Check for product_extra groups
	$product_extra_groups = pewc_get_extra_fields( $product_id );

	if( $product_extra_groups ) {

		$max = pewc_get_max_upload();
		$max_mb = $max * pow( 1024, 2 );

		foreach( $product_extra_groups as $group ) {
			// The group requirement setting
			// This is going to be deprecated in favour of conditionals for groups
			$group_req = false; // No requirement set by default
			if( isset( $group['meta']['group_required'] ) ) {
				$group_req = $group['meta']['group_required'];
			}

			if( isset( $group['items'] ) ) {
				foreach( $group['items'] as $item ) {
					$id = $item['id'];

					// If label isn't set, use id
					$label = $id;
					if( isset( $item['field_label'] ) ) {
						$label = $item['field_label'];
					}

					// Check if the field is required
					$field_req = false;
					if( isset( $item['field_required'] ) ) {
						$field_req = $item['field_required'];
					}
					$is_visible = pewc_get_conditional_field_visibility( $id, $item, $group['items'], $product_id, $_POST, $variation_id, $cart_item_data, $quantity );
					if( ! $is_visible ) {
						// If the field is hidden by a condition, it can't be required
						$is_required = false;
					} else {
						// Will reinstate something similar to this with group conditionals
						// $is_required = pewc_is_field_required( $group_req, $field_req, $id, $group['items'] );
						$is_required = $field_req;
					}

					if( isset( $item['field_type'] ) && ( $item['field_type'] == 'text' || $item['field_type'] == 'textarea' ) ) {
						if( empty( $_POST[$id] ) && $is_required ) {
							// Required field
							wc_add_notice( apply_filters( 'pewc_filter_validation_notice', esc_html( $label ) . __( ' is a required field.', 'pewc' ), $label, $item ), 'error' );
							$passed = false;
						} else {
							// Character length
							if( ! empty( $item['field_minchars'] ) || ! empty( $item['field_maxchars'] ) ) {
								$length = isset( $_POST[$id] ) ? mb_strlen( str_replace( ' ', '', $_POST[$id] ) ) : 0;
								if( ! empty( $item['field_minchars'] ) && $length < $item['field_minchars'] && $is_required ) {
									wc_add_notice( apply_filters( 'pewc_filter_minchars_validation_notice', esc_html( $label ) . __( ': minimum number of characters: ', 'pewc' ) . esc_html( $item['field_minchars'] ), $label, $item ), 'error' );
									$passed = false;
								} else if( ! empty( $item['field_maxchars'] ) && $length > $item['field_maxchars'] && $is_required ) {
									wc_add_notice( apply_filters( 'pewc_filter_maxchars_validation_notice', esc_html( $label ) . __( ': maximum number of characters: ', 'pewc' ) . esc_html( $item['field_maxchars'] ), $label, $item ), 'error' );
									$passed = false;
								}
							}
						}

					} else if( isset( $item['field_type'] ) && $item['field_type'] == 'date' ) {
						if( empty( $_POST[$id] ) && $is_required ) {
							// Required field
							wc_add_notice( apply_filters( 'pewc_filter_validation_notice', esc_html( $label ) . __( ' is a required field.', 'pewc' ), $label, $item ), 'error' );
							$passed = false;
						}

					} else if( isset( $item['field_type'] ) && $item['field_type'] == 'checkbox' ) {
						if( empty( $_POST[$id] ) && $is_required ) {
							// Required field
							wc_add_notice( apply_filters( 'pewc_filter_validation_notice', esc_html( $label ) . __( ' is a required field.', 'pewc' ), $label, $item ), 'error' );
							$passed = false;
						}

					} else if( isset( $item['field_type'] ) && $item['field_type'] == 'checkbox_group' && pewc_is_pro() ) {
						if( empty( $_POST[$id] ) && $is_required ) {
							// Required field
							wc_add_notice( apply_filters( 'pewc_filter_validation_notice', esc_html( $label ) . __( ' is a required field.', 'pewc' ), $label, $item ), 'error' );
							$passed = false;
						}
						// Check for minimum and maximum number of checkboxes
						if( ! empty( $item['field_minchecks'] ) && ! empty( $_POST[$id] ) && count( $_POST[$id] ) < $item['field_minchecks'] ) {
							// Not enough checkboxes checked
							wc_add_notice(
								apply_filters(
									'pewc_filter_minchecks_notice',
									sprintf(
										'%s requires at least %s checkboxes to be selected',
										$label,
										$item['field_minchecks']
									)
								),
								'error'
							);
							$passed = false;
						}
						if( ! empty( $item['field_maxchecks'] ) && ! empty( $_POST[$id] ) && count( $_POST[$id] ) > $item['field_maxchecks'] ) {
							// Not enough checkboxes checked
							wc_add_notice(
								apply_filters(
									'pewc_filter_maxchecks_notice',
									sprintf(
										'%s requires a maximum of %s checkboxes to be selected',
										$label,
										$item['field_maxchecks']
									)
								),
								'error'
							);
							$passed = false;
						}

					} else if( isset( $item['field_type'] ) && ( $item['field_type'] == 'number' || $item['field_type'] == 'name_price' ) ) {

						if( ( empty( $_POST[$id] ) && ! is_numeric( $_POST[$id] ) ) && $is_required ) {
							// Required field
							wc_add_notice( apply_filters( 'pewc_filter_validation_notice', esc_html( $label ) . __( ' is a required field.', 'pewc' ), $label, $item ), 'error' );
							$passed = false;

						} else {

							// Does the number field need to be required in order to carry out value validation?
							$require_required = apply_filters( 'pewc_only_validate_number_field_value_if_field_required', false, $product_id, $item );

							if( ! $require_required || $is_required ) {

								// The field doesn't need to be required or it is required - so do value validation
								if( ! empty( $item['field_minval'] ) || ! empty( $item['field_maxval'] ) ) {

									$val = $_POST[$id];
									if( ! empty( $item['field_minval'] ) && $val < $item['field_minval'] ) {
										wc_add_notice( apply_filters( 'pewc_filter_minval_validation_notice', esc_html( $label ) . __( ': minimum value is ', 'pewc' ) . esc_html( $item['field_minval'] ) ), 'error' );
										$passed = false;
									} else if( ! empty( $item['field_maxval'] ) && $val > $item['field_maxval'] ) {
										wc_add_notice( apply_filters( 'pewc_filter_maxval_validation_notice', esc_html( $label ) . __( ': maximum value is ', 'pewc' ) . esc_html( $item['field_maxval'] ) ), 'error' );
										$passed = false;
									}

								}

							}

						}

					} else if( isset( $item['field_type'] ) && $item['field_type'] == 'upload' ) {

						$files = array();

						if( ! empty( $_FILES ) ) {
							// We're using the standard image upload
							$files = $_FILES;

						} else if( ! empty( $_POST['pewc_file_data'][$item['field_id']] ) ) {
							// Using jQuery version

							// Make this an array like $_FILES
							$files = pewc_get_files_array( $_POST['pewc_file_data'][$item['field_id']], $id );

						}

						if( ! empty( $files[$id]['size'] ) ) {

							foreach( $files[$id]['size'] as $key=>$size ) {

								if( $size > $max_mb ) {
									// File too big
									wc_add_notice( apply_filters( 'pewc_filter_file_size_validation_notice', esc_html( $files[$id]['name'][$key] ) . __( ': File size too large.', 'pewc' ) ), 'error' );
									return false;
								}

								if( $size == 0 && $is_required ) {
									// Required field
									wc_add_notice( apply_filters( 'pewc_filter_validation_notice', esc_html( $label ) . __( ' is a required upload field.', 'pewc' ), $label, $item ), 'error' );
									return false;
								}

							}

						} else if( $is_required ) {

							// Required field
							wc_add_notice( apply_filters( 'pewc_filter_validation_notice', esc_html( $label ) . __( ' is a required upload field.', 'pewc' ), $label, $item ), 'error' );
							return false;

						}

						$mime_types = pewc_get_permitted_mimes();

						// Check file type
						if( ! empty( $files[$id]['type'] ) ) {

							foreach( $files[$id]['type'] as $key=>$type ) {

								// Use wp_check_filetype for additional security
								$file_info = wp_check_filetype( basename( $type ), $mime_types );

								if( ! empty( $type ) ) {
									// File type is permitted
								} else {

									if( $is_required ) {
										wc_add_notice( apply_filters( 'pewc_file_not_valid_message', __( 'File not valid.', 'pewc' ) ), 'error' );
										return false;
									}

								}

							}

						}

					} else if( isset( $item['field_type'] ) && ( $item['field_type'] == 'radio' || $item['field_type'] == 'image_swatch' || $item['field_type'] == 'select' ) ) {

						if( empty( $_POST[$id] ) && $is_required ) {
							// Required field
							wc_add_notice( apply_filters( 'pewc_filter_validation_notice', esc_html( $label ) . __( ' is a required field.', 'pewc' ), $label, $item ), 'error' );
							$passed = false;
						}

					} else if( isset( $item['field_type'] ) && $item['field_type'] == 'products' ) {

						// Validate minimum / maximum
						if( $item['products_quantities'] == 'independent' && ( ! empty( $item['min_products'] ) || ! empty( $item['max_products'] ) ) ) {
							$min_products = ! empty( $item['min_products'] ) ? $item['min_products'] : 0;
							$max_products = ! empty( $item['max_products'] ) ? $item['max_products'] : '';
							// Total up the quantity of child products
							$child_products = ! empty( $item['child_products'] ) ? $item['child_products'] : array();
							$child_quantity = 0;
							foreach( $child_products as $key=>$child_product_id ) {
								$child_quantity += $_POST[$id . '_child_quantity_' . $child_product_id];
							}

							// Check if we've got too many or too few child products
							if( $min_products == $max_products && $child_quantity != $min_products && $is_visible ) {
								wc_add_notice(
									apply_filters(
										'pewc_filter_exact_children_validation_notice',
										sprintf(
											__( '%s requires you to choose %s products', 'pewc' ),
											esc_html( $label ),
											$min_products
										)
									),
									'error'
								);
								$passed = false;
							} else if( $child_quantity < $min_products && $is_visible ) {
								wc_add_notice(
									apply_filters(
										'pewc_filter_min_children_validation_notice',
										sprintf(
											__( '%s requires you to choose a minimum of %s products', 'pewc' ),
											esc_html( $label ),
											$min_products
										)
									),
									'error'
								);
								$passed = false;
							} else if( $max_products && $child_quantity > $max_products && $is_visible ) {
								wc_add_notice(
									apply_filters(
										'pewc_filter_max_children_validation_notice',
										sprintf(
											__( '%s requires you to choose a maximum of %s products', 'pewc' ),
											esc_html( $label ),
											$max_products
										)
									),
									'error'
								);
								$passed = false;
							}
						}

						// If the products layout is select, the quantities type is independent and the field is required, the quantity field must be a minimum of 1
						if( $item['products_layout'] == 'select' && $item['products_quantities'] == 'independent' && ! empty( $item['field_required'] ) && empty( $_POST[$id . '_child_quantity'] ) ) {
							wc_add_notice( apply_filters( 'pewc_filter_independent_select_validation_notice', esc_html( $label ) . __( ' must have a quantity entered.', 'pewc' ) ), 'error' );
							$passed = false;
						}

						if( empty( $_POST[$id . '_child_product'] ) && $is_required ) {
							// Required field
							wc_add_notice( apply_filters( 'pewc_filter_validation_notice', esc_html( $label ) . __( ' is a required field.', 'pewc' ), $label, $item ), 'error' );
							$passed = false;

						}
					}
				}
			}
		}

	}
	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'pewc_validate_cart_item_data', 10, 5 );

/**
 * Is this field required?
 * @param $group_req 	The requirement setting for the group
 * @param $field_req 	The requirement setting for the field
 * @param $id					Current item ID
 * @param $items			Array of all items in group
 * @return Boolean
 */
function pewc_is_field_required( $group_req, $field_req, $id, $items ) {
	if( ! $group_req && ! $field_req ) {
		// No requirements set
		return false;
	} else if( $group_req == 'all' && $field_req ) {
		return true;
	} else if( $group_req == 'depends' && $field_req ) { // Remove this option - conditionals will replace this
		// Field is not required if it's the first field in the group
		if( isset( $items[0]['id'] ) && $items[0]['id'] == $id ) {
			return false;
		} else {
			// Field is required if the first field in the group is not empty
			if( ! empty( $_POST[$items[0]['id']] ) ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Display custom fields for each cart item
 */
function pewc_get_item_data( $other_data, $cart_item ) {
	if ( ! empty( $cart_item['product_extras']['groups'] ) ) {
		foreach ( $cart_item['product_extras']['groups'] as $groups ) {
			if( $groups ) {
				foreach( $groups as $item ) {

					if( isset( $item['type'] ) && $item['type'] != 'products' ) {

						$price = '';
						$hide_zero = get_option( 'pewc_hide_zero', 'no' );
						$show_prices = apply_filters( 'pewc_show_field_prices_in_cart', true, $item );

						// Calculate price
						if( isset( $item['price'] ) ) {

							if( ( $hide_zero == 'yes' && $item['price'] == '0.00' ) || ! $show_prices ) {
								// If price is zero and hide_zero is set, hide the price
								$price = '';
							} else {
								$product_id = $cart_item['data']->get_id();
								$product = wc_get_product( $product_id );
								$price = pewc_maybe_include_tax( $product, $item['price'] );
								$price = ' ' . wc_price( $price );
							}

						}

						if( ! empty( $item['flat_rate'] ) ) {
							$price = '<span class="pewc-flat-rate-cart-label">(' . __( 'Flat rate cost', 'pewc' ) . ')</span>';
						}

						$price = apply_filters( 'pewc_filter_cart_item_price', $price, $item );

						if( $item['type'] == 'upload' ) {

							if( ! empty( $item['files'] ) ) {

								$display = sprintf(
									'<div class="pewc-upload-thumb-wrapper">%s<span class="pewc-cart-item-price">%s</span>',
									sanitize_text_field( $item['label'] ),
									$price
								);

								foreach( $item['files'] as $index=>$file ) {

									$thumb = $file['url'];
									$display .= sprintf(
										'<br><img src="%s">',
										esc_url( $thumb )
									);

								}

								$display .= '</div>';

								$other_data[] = array(
									'name'    => sanitize_text_field( $item['label'] ),
									// 'value'   => sanitize_text_field( $item['display'] ),
									'display' => $display,
								);

							}

						} else if( $item['type'] == 'checkbox' ) {
							$other_data[] = array(
								'name'    => sanitize_text_field( $item['label'] ),
								'value'   => '<span class="pewc-price pewc-cart-item-price">' . sanitize_text_field( $price ). '</span>',
								'display' => '',
							);
						} else if( $item['type'] == 'checkbox_group' ) {
							$other_data[] = array(
								'name'    => sanitize_text_field( $item['label'] ),
								'value'   => str_replace( ' | ', '<br>', $item['value'] ),
								'display' => '',
							);
						} else if( $item['type'] == 'name_price' ) {
							$value = wc_price( $item['value'] );
							$other_data[] = array(
								'name'    => sanitize_text_field( $item['label'] ),
								'value'   => sanitize_text_field( $value ),
								'display' => '',
							);
						} else {
							$other_data[] = array(
								'name'    => sanitize_text_field( $item['label'] ),
								'value'   => sanitize_text_field( $item['value'] ),
								'display' => esc_html( stripslashes( $item['value'] ) ) . '<span class="pewc-cart-item-price">' . $price . '</span>',
							);
						}
					}
				}
			}
		}
	}
	return $other_data;
}
add_filter( 'woocommerce_get_item_data', 'pewc_get_item_data', 10, 2 );


/**
 * Convert the AJAX uploaded files object into a $_FILES type array
 * @param $pewc_file_data		The files object uploaded via jQuery
 * @param $id 							The field ID
 */
function pewc_get_files_array( $pewc_file_data, $id ) {

	$files[$id] = array();
	$pewc_file_data = json_decode( stripslashes( $pewc_file_data ) );
	$index = 0;

	foreach( $pewc_file_data as $upload ) {

		// foreach( $upload as $file ) {

			$files[$id]['file'][$index] 	= $upload->file;
			$files[$id]['name'][$index] 	= $upload->name;
			$files[$id]['type'][$index] 	= $upload->filetype->type;
			$files[$id]['error'][$index] 	= $upload->error;
			$files[$id]['size'][$index] 	= $upload->size;
			$files[$id]['url'][$index] 		= $upload->url;
			$files[$id]['tmp_name'][$index] 		= $upload->tmp_name;
			$index++;

		// }

	}

	return $files;

}
