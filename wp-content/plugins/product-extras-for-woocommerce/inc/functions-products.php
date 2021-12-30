<?php
/**
 * Functions for add-on products
 * @since 2.2.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add an ID field to connect parent products with their child products
 * @since 2.2.0
 */
function pewc_add_product_hash_field( $args=array() ) {
	$parent_product_hash = uniqid( 'pewc_' ); ?>
	<input type="hidden" name="pewc_product_hash" value="<?php echo $parent_product_hash; ?>">
<?php }
add_action( 'pewc_start_groups', 'pewc_add_product_hash_field' );

/**
 * Add the child product when we add the parent product
 */
function pewc_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	// If the product being added has a child product set, add that child product
	if( ! empty( $_POST ) ) {
		$child_product_ids = array();
		foreach( $_POST as $key=>$value ) {
			if( strpos( $key, '_child_product' ) !== false ) {
				$field_id = str_replace( '_child_product', '', $key );
				if( ! $value ) {
					// There's no data here, so no child products being added
					continue;
				} else if( is_array( $value ) ) {
					// If $value is an array, we're using checkboxes so add multiple products
					foreach( $value as $value_id ) {
						// Add independent quantity if set
						$child_quantity = '';
						if( ! empty( $_POST[$field_id . '_child_quantity_' . $value_id] ) ) {
							// Get the quantity for the child product in independent checkboxes
							$child_quantity = intval( $_POST[$field_id . '_child_quantity_' . $value_id] );
						} else if( ! empty( $_POST[$field_id . '_child_quantity'] ) ) {
							// Get the quantity for the child product in linked checkboxes
							$child_quantity = intval( $_POST[$field_id . '_child_quantity'] );
						}

						// If we're adding a variable product, then get the variation ID
						if( ! empty( $_POST['pewc_child_variants_' . $value_id] ) ) {
							// Add the variant, not the variable product
							$value_id = $_POST['pewc_child_variants_' . $value_id];
						}
						$child_product_ids[] = array(
							'child_product_id'	=> $value_id,
							'field_id' 					=> $field_id,
							'quantities'				=> $_POST[$field_id . '_quantities'],
							'allow_none'				=> $_POST[$field_id . '_allow_none'],
							'child_quantity'		=> $child_quantity,
							'child_discount'		=> $_POST[$field_id . '_child_discount'],
							'discount_type'			=> $_POST[$field_id . '_discount_type']
						);

					}

				} else {

					// Not an array, so just a single child product
					$child_quantity = '';
					if( ! empty( $_POST[$field_id . '_child_quantity'] ) ) {
						$child_quantity = intval( $_POST[$field_id . '_child_quantity'] );
					}
					// If we're adding a variable product, then get the variation ID
					if( ! empty( $_POST['pewc_child_variants_' . $value] ) ) {
						// Add the variant, not the variable product
						$value_id = $_POST['pewc_child_variants_' . $value];
					}
					$child_product_ids[] = array(
						'child_product_id'	=> $value,
						'field_id' 					=> $field_id,
						'quantities'				=> $_POST[$field_id . '_quantities'],
						'allow_none'				=> $_POST[$field_id . '_allow_none'],
						'child_quantity'		=> $child_quantity,
						'child_discount'		=> $_POST[$field_id . '_child_discount'],
						'discount_type'			=> $_POST[$field_id . '_discount_type']
					);
				}

			} else if( strpos( $key, '_child_variation' ) !== false ) {
				// Swatches fields

				$field_id = str_replace( '_child_variation', '', $key );

				// Iterate through each child product and identify the selected variation
				foreach( $value as $parent_id=>$variation_id ) {
					// This should be an array of variation IDs

					$child_quantity = '';
					if( ! empty( $_POST[$field_id . '_child_quantity_' . $parent_id] ) ) {
						// Get the quantity for the child product in independent checkboxes
						$child_quantity = intval( $_POST[$field_id . '_child_quantity_' . $parent_id] );
					} else if( ! empty( $_POST[$field_id . '_child_quantity'] ) ) {
						// Get the quantity for the child product in linked checkboxes
						$child_quantity = intval( $_POST[$field_id . '_child_quantity'] );
					}

					// If we're adding a variable product, then get the variation ID
					// if( ! empty( $_POST['pewc_child_variants_' . $value_id] ) ) {
					// 	// Add the variant, not the variable product
					// 	$value_id = $_POST['pewc_child_variants_' . $value_id];
					// }


					$child_product_ids[] = array(
						'child_product_id'	=> $variation_id,
						'field_id' 					=> $field_id,
						'quantities'				=> $_POST[$field_id . '_quantities'],
						'allow_none'				=> $_POST[$field_id . '_allow_none'],
						'child_quantity'		=> $child_quantity,
						'child_discount'		=> $_POST[$field_id . '_child_discount'],
						'discount_type'			=> $_POST[$field_id . '_discount_type']
					);

				}

			}

		}

		$parent_product_hash = isset( $_POST['pewc_product_hash'] ) ? $_POST['pewc_product_hash'] : '';

		pewc_add_on_product( $child_product_ids, $quantity, $product_id, $parent_product_hash, $cart_item_data );

	}

}
add_action( 'woocommerce_add_to_cart', 'pewc_add_to_cart', 10, 6 );

/**
 * Add a child product to the cart
 * @since 2.2.0
 */
function pewc_add_on_product( $child_product_ids, $original_quantity, $product_id, $parent_product_hash, $cart_item_data ) {
	if( ! pewc_is_pro() ) {
		return false;
	}
	do_action( 'pewc_add_on_product' );
	// Only add child products once
	$did = did_action( 'pewc_add_on_product' );
	if( $did > 1 ) {
		return;
	}

	// Add each child product to the cart
	foreach( $child_product_ids as $child_product_values ) {

		$child_product_id = $child_product_values['child_product_id'];

		if( ! empty( $cart_item_data['product_extras']['products']['field_id'] ) ) {

			// Only add visible fields to the cart
			$field_id = $cart_item_data['product_extras']['products']['field_id'];
			$cart_item['product_extras']['products']['pewc_parent_product'] = $product_id;
			$cart_item['product_extras']['products']['parent_field_id'] = $parent_product_hash;
			$cart_item['product_extras']['products']['child_field'] = 1;
			$cart_item['product_extras']['products'][$field_id . '_child_field'] = $field_id;
			// Check the quantity
			if( $child_product_values['quantities'] == 'one-only' ) {
				$quantity = 1;
			} else if( $child_product_values['quantities'] == 'linked' ) {
				$quantity = $original_quantity;
			} if( $child_product_values['quantities'] == 'independent' || ! empty( $child_product_values['child_quantity'] ) ) {
				$quantity = $child_product_values['child_quantity'];
			}
			$cart_item['product_extras']['products']['products_quantities'] = $child_product_values['quantities'];
			$cart_item['product_extras']['products']['allow_none'] = $child_product_values['allow_none'];

			if( ! empty( $child_product_values['child_discount'] ) && ! empty( $child_product_values['discount_type'] ) ) {
				$child_product = wc_get_product( $child_product_id );
				$child_price = $child_product->get_price();
				$child_price = pewc_get_discounted_child_price( $child_price, $child_product_values['child_discount'], $child_product_values['discount_type'] );

				$cart_item['product_extras']['price_with_extras'] = $child_price;
			}

			WC()->cart->add_to_cart( $child_product_id, $quantity, 0, array(), $cart_item );

		}

	}
}

/**
 * Filter row classes in the cart for child and parent products
 * @since 2.2.0
 */
function pewc_cart_item_class( $class, $cart_item, $cart_item_key ) {
	if( ! empty( $cart_item['product_extras']['products']['child_products'] ) ) {
		// This is a parent product
		$parent_id = $cart_item['product_extras']['products']['pewc_parent_product'];
		$class .= ' pewc-parent-product pewc-parent-id-' . $parent_id;

	} else if( ! empty( $cart_item['product_extras']['products']['child_field'] ) ) {
		$class .= ' pewc-child-product';
	}
	if( ! empty( $cart_item['product_extras']['products']['parent_field_id'] ) ) {
		$class .= ' ' . $cart_item['product_extras']['products']['parent_field_id'];
	}
	return $class;
}
add_filter( 'woocommerce_cart_item_class', 'pewc_cart_item_class', 10, 3 );

/**
 * Re-order cart items so that parent products sit above child products
 * @since 2.2.0
 */
function pewc_cart_loaded_from_session() {
	if( WC()->cart->get_cart_contents_count() == 0 ) {
		// Empty cart so do nothing
		return;
	}
	$cart = WC()->cart->get_cart();
	$new_order = array();
	$grouped_items = array();
	foreach( $cart as $key=>$item ) {
		if( ! isset( $item['product_extras']['products'] ) ) {
			// Not a linked product
			$new_order[$key] = $item;
		} else {
			// Arrange linked products into groups
			$parent_field_id = $item['product_extras']['products']['parent_field_id'];
			if( ! isset( $grouped_items[$parent_field_id] ) ) {
				// Create a new array for this set of linked products
				$grouped_items[$parent_field_id] = array();
			}
			if( isset( $item['product_extras']['products']['child_products'] ) ) {
				// This is the parent product, so push to the end
				$grouped_items[$parent_field_id][] = $key;
			} else {
				// This is a child product, so prepend to start of list
				array_unshift( $grouped_items[$parent_field_id], $key );
			}
		}
	}
	// If we have linked products, then re-order the cart
	if( ! empty( $grouped_items ) ) {
		foreach( $grouped_items as $unique_key=>$grouped_item ) {
			foreach( $grouped_item as $cart_key ) {
				// Add each cart key, starting with child products, finishing with parent product
				$new_order[$cart_key] = $cart[$cart_key];
			}
		}
		// Reverse the order so that parent items are first
		WC()->cart->cart_contents = array_reverse( $new_order );
	} else {
		// Just display the original cart order
		WC()->cart->cart_contents = $cart;
	}
}
add_action( 'woocommerce_cart_loaded_from_session', 'pewc_cart_loaded_from_session' );

/**
 * Update any link child product quantities
 * @since 2.2.0
 */
function pewc_after_cart_item_quantity_update( $cart_item_key, $quantity, $old_quantity ) {
	$cart = WC()->cart->get_cart();
	if( ! empty( $cart[$cart_item_key]['product_extras']['products']['parent_field_id'] ) ) {
		$parent_field_id = $cart[$cart_item_key]['product_extras']['products']['parent_field_id'];
		// We've updated this parent ID so let's update all child products if linked
		foreach( $cart as $key=>$item ) {
			// Check that parent IDs match, that it's a child product, and that quantities are linked
			if( ! empty( $item['product_extras']['products']['parent_field_id'] ) &&
					$item['product_extras']['products']['parent_field_id'] == $parent_field_id &&
					isset( $item['product_extras']['products']['child_field'] ) &&
					isset( $item['product_extras']['products']['products_quantities'] ) &&
					$item['product_extras']['products']['products_quantities'] == 'linked' ) {
						// This is a child of the product we've just updated, so update the quantity
				WC()->cart->cart_contents[$key]['quantity'] = $quantity;
			}
		}
	}
}
add_action( 'woocommerce_after_cart_item_quantity_update', 'pewc_after_cart_item_quantity_update', 10, 3 );

/**
 * Remove any linked child products
 * allow_none means that the child product is not a required field - a user can buy a parent product without selecting a child product
 * @since 2.2.0
 */
function pewc_remove_cart_item( $cart_item_key, $cart ) {
	if( empty( $cart->cart_contents[$cart_item_key]['product_extras']['products']['parent_field_id'] ) ) {
		// This isn't a parent or child product, so don't need to do anything here
		return;
	}
	// Remove a parent, remove all linked children
	$parent_field_id = $cart->cart_contents[$cart_item_key]['product_extras']['products']['parent_field_id'];

	if( empty( $cart->cart_contents[$cart_item_key]['product_extras']['products']['child_field'] ) ) {

		// This is a parent product so let's remove all child products
		foreach( $cart->cart_contents as $key=>$item ) {
			// Check that parent IDs match and that it's a child product
			if( ! empty( $item['product_extras']['products']['parent_field_id'] ) &&
					$item['product_extras']['products']['parent_field_id'] == $parent_field_id &&
					isset( $item['product_extras']['products']['child_field'] ) ) {
						// This is a child of the product we've just removed, so remove it
				unset( $cart->cart_contents[$key] );
				// Add a notice that we'll use to remove the 'removed' notice, so that users can't undo the remove action
				wc_add_notice( 'Clear cart notices', 'pewc_clear_cart_notices' );
			}
		}

	} else {
		// Remove a child, so remove linked parent and other children, if allow_none is not set
		if( ! empty( $cart->cart_contents[$cart_item_key]['product_extras']['products']['allow_none'] ) ) {

			// Allow none is set, meaning the parent product doesn't require a child product - so we don't need to remove anything else
			return;

		} else {

			if( apply_filters( 'pewc_do_not_remove_parents', false ) ) {
				return;
			}

			// Allow none is not set, so all associated products must be removed
			foreach( $cart->cart_contents as $key=>$item ) {
				// Check that parent IDs match
				if( ! empty( $item['product_extras']['products']['parent_field_id'] ) &&
						$item['product_extras']['products']['parent_field_id'] == $parent_field_id ) {
							// This is a child of the product we've just removed, so remove it
					unset( $cart->cart_contents[$key] );
					// Add a notice that we'll use to remove the 'removed' notice, so that users can't undo the remove action
					wc_add_notice( 'Clear cart notices', 'pewc_clear_cart_notices' );
				}
			}
		}

	}
	
}
add_action( 'woocommerce_remove_cart_item', 'pewc_remove_cart_item', 10, 2 );
add_action( 'woocommerce_before_cart_item_quantity_zero', 'pewc_remove_cart_item', 10, 2 );

/**
 * Remove notices in the cart
 */
function pewc_remove_cart_notice() {
	if( is_admin() ) {
		return;
	}
	if( ! function_exists( 'WC' ) ) {
		return;
	}
	$notices = isset( WC()->session ) ? WC()->session->get( 'wc_notices', array() ) : array();
	// If pewc_clear_cart_notices is set, then remove the success notice
	if( isset( $notices['pewc_clear_cart_notices'] ) ) {
		unset( $notices['pewc_clear_cart_notices'] );
		unset( $notices['success'] );
		$notices = WC()->session->set( 'wc_notices', $notices );
	}
}
add_action( 'init', 'pewc_remove_cart_notice' );

/**
 * Filter remove link in cart
 */
function pewc_cart_item_remove_link( $link, $cart_item_key ) {
	$cart = WC()->cart->get_cart();
	$cart_item_data = $cart[$cart_item_key];
	if( isset( $cart_item_data['product_extras']['products']['products_quantities'] ) &&
		$cart_item_data['product_extras']['products']['products_quantities'] == 'independent' ) {
		// Independent quantities can be removed separately
		return $link;
	}
	// Filter out the remove link if it's a child product and allow_none is not set
	if( isset( $cart_item_data['product_extras']['products']['child_field'] ) &&
			$cart_item_data['product_extras']['products']['child_field'] &&
			empty( $cart_item_data['product_extras']['products']['allow_none'] ) ) {
		// This is a child product with a linked quantity
		$arrow_right = sprintf(
			'<img src="%s" class="pewc-arrow-right">',
			esc_url( trailingslashit( PEWC_PLUGIN_URL ) . 'assets/images/arrow-right.svg' )
		);
		return apply_filters( 'pewc_filter_cart_remove_linked_product', $arrow_right, $cart_item_key );
	}
	return $link;
}
add_filter( 'woocommerce_cart_item_remove_link', 'pewc_cart_item_remove_link', 10, 2 );

/**
 * Filter quantity in cart
 */
function pewc_cart_item_quantity( $product_quantity, $cart_item_key, $cart_item ) {
	if( isset( $cart_item['product_extras']['products']['child_field'] ) &&
			$cart_item['product_extras']['products']['child_field'] == 1 &&
			isset( $cart_item['product_extras']['products']['products_quantities'] ) &&
			$cart_item['product_extras']['products']['products_quantities'] != 'independent' ) {
				// This is a child product with a linked quantity
		return apply_filters( 'pewc_filter_cart_quantity_linked_product', $cart_item['quantity'], $cart_item_key );
	}
	return $product_quantity;
}
add_filter( 'woocommerce_cart_item_quantity', 'pewc_cart_item_quantity', 10, 3 );

/**
 * Return the discounted child product price
 * @since 2.7.0
 */
function pewc_get_discounted_child_price( $child_price, $discount, $discount_type ) {
	$discounted_price = $child_price;
	if( $discount_type == 'fixed' ) {
		$discounted_price = max( $child_price - $discount, 0 );
	} else {
		$discounted_price = max( $child_price * ( ( 100 -  $discount ) / 100 ), 0 );
	}
	return $discounted_price;
}

/**
 * Find matching product variation
 *
 * @param WC_Product $product
 * @param array $attributes
 * @return int Matching variation ID or 0.
 */
function pewc_find_matching_product_variation( $product, $attributes ) {

    foreach( $attributes as $key => $value ) {
	    if( strpos( $key, 'attribute_' ) === 0 ) {
		    continue;
	    }

	    unset( $attributes[ $key ] );
	    $attributes[ sprintf( 'attribute_%s', $key ) ] = $value;
    }

    if( class_exists('WC_Data_Store') ) {

        $data_store = WC_Data_Store::load( 'product' );
        return $data_store->find_matching_product_variation( $product, $attributes );

    } else {

        return $product->get_matching_variation( $attributes );

    }

}

/**
 * Get variation default attributes
 *
 * @param WC_Product $product
 * @return array
 */
function pewc_get_default_attributes( $product ) {

    if( method_exists( $product, 'get_default_attributes' ) ) {

        return $product->get_default_attributes();

    } else {

        return $product->get_variation_default_attributes();

    }

}

function pewc_get_default_variation_id( $product ) {
	$default_attributes = pewc_get_default_attributes( $product );
	$variation_id = pewc_find_matching_product_variation( $product, $default_attributes );
	return $variation_id;
}
