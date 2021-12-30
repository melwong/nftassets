<?php
/**
 * Functions for orders / checkout
 * @since 1.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add custom meta to order
 */
function pewc_add_custom_data_to_order( $item, $cart_item_key, $values, $order ) {
	foreach( $item as $cart_item_key=>$values ) {
		if( isset( $values['product_extras'] ) ) {
			$item->add_meta_data( 'product_extras', $values['product_extras'], true );
		}
	}
}
add_action( 'woocommerce_checkout_create_order_line_item', 'pewc_add_custom_data_to_order', 10, 4 );

/**
 * Add product_extra information to front-end view order page
 */
function pewc_order_item_name( $product_name, $item ) {
	if( isset( $item['product_extras']['groups'] ) ) {
		foreach ( $item['product_extras']['groups'] as $groups ) {
			if( $groups ) {

				$hidden_group_types = apply_filters( 'pewc_hidden_group_types_in_order', array() );
				
				$product_name .= '<ul>';
				foreach( $groups as $group ) {

					if( isset( $group['type'] ) ) {

						if( in_array( $group['type'], $hidden_group_types ) ) {
							// Don't add this to the order if it's a hidden field type
							continue;
						}

					// if( isset( $group['type'] ) && empty( $group['flat_rate'] ) ) {
						$classes = array( strtolower( str_replace( ' ', '_', $group['type'] ) ) );
						$classes[] = strtolower( str_replace( ' ', '_', $group['label'] ) );
						// Display the price
						$price = '';
						$hide_zero = get_option( 'pewc_hide_zero', 'no' );
						// Calculate price
						if( isset( $group['price'] ) ) {
							if( $hide_zero != 'yes' && $group['price'] != '0.00' ) {
								global $product;
								$price = pewc_maybe_include_tax( $product, $group['price'] );
								$price = ' ' . wc_price( $price );
							}
						}
						if( $group['type'] == 'upload' ) {

							if( ! empty( $group['files'] ) ) {

								$display = sprintf(
									'<li class="%s"><span class="pewc-order-item-label">%s:</span> <span class="pewc-order-item-price">%s</span>',
									join( ' ', $classes ),
									$group['label'],
									$price
								);

								foreach( $group['files'] as $index=>$file ) {

									$display .= sprintf(
										'<br><span class="pewc-order-item-item">%s</span><br><img style="max-width: 50px; height: auto;" src="%s">',
										$file['display'],
										esc_url( $file['url'] )
									);

								}

								$display .= '</li>';

								$product_name .= $display;

							}

						} else if( $group['type'] == 'checkbox' ) {
							$product_name .= '<li class="' . join( ' ', $classes ) . '"><span class="pewc-order-item-label">' . $group['label'] . '</span> <span class="pewc-order-item-price">' . $price . '</span></li>';
						} else {
							$product_name .= '<li class="' . join( ' ', $classes ) . '"><span class="pewc-order-item-label">' . $group['label'] . ':</span> <span class="pewc-order-item-item">' . $group['value'] . '</span> <span class="pewc-order-item-price">' . $price . '</span></li>';
						}
					}
				}
				$product_name .= '</ul>';
			}
		}
	}

	return $product_name;

}
add_filter( 'woocommerce_order_item_name', 'pewc_order_item_name', 10, 2 );

/**
 * Add product_extra information to back-end view order page
 */
function pewc_add_order_itemmeta_admin( $item_id, $item, $product ) {
	if( isset( $item['product_extras']['groups'] ) ) {
		foreach ( $item['product_extras']['groups'] as $groups ) {
			if( $groups ) {
				$output = '<ul>';
				foreach( $groups as $group ) {
					if( isset( $group['type'] ) ) {
					// if( isset( $group['type'] ) && empty( $group['flat_rate'] ) ) {
						$classes = array( strtolower( str_replace( ' ', '_', $group['type'] ) ) );
						$classes[] = strtolower( str_replace( ' ', '_', $group['label'] ) );
						// Display the price
						$price = '';
						$hide_zero = get_option( 'pewc_hide_zero', 'no' );
						// Calculate price
						if( isset( $group['price'] ) ) {
							if( $hide_zero != 'yes' && $group['price'] != '0.00' ) {
								$price = pewc_maybe_include_tax( $product, $group['price'] );
								$price = ' ' . wc_price( $price );
							}
						}

						if( $group['type'] == 'upload' ) {

							if( ! empty( $group['files'] ) ) {

								$output .= '<li class="' . join( ' ', $classes ) . '">' . $group['label'] . ': ' . $price . '<ul>';

								foreach( $group['files'] as $index=>$file ) {

									$output .= '<li><a target="_blank" href="' . esc_url( $file['url'] ) . '">' . $file['display'] . '</a></li>';

								}

								$output .= '</ul></li>';

							}

						} else if( $group['type'] == 'checkbox' ) {

							$output .= '<li class="' . join( ' ', $classes ) . '">' . $group['label'] . ' ' . $price . '</li>';

						} else {

							$output .= '<li class="' . join( ' ', $classes ) . '">' . $group['label'] . ': ' . $group['value'] . ' ' . $price . '</li>';

						}
					}
				}
				$output .= '</ul>';
				echo $output;
			}
		}
	}
}
add_action( 'woocommerce_after_order_itemmeta', 'pewc_add_order_itemmeta_admin', 10, 3 );

/**
 * Create product_extra post when the order is processed
 */
function pewc_create_product_extra( $order_id ) {
	$order = wc_get_order( $order_id );
	$payment_method = is_callable( array( $order, 'get_payment_method' ) ) ? $order->get_payment_method() : $order->payment_method;

	// Don't publish product_extras for COD orders.
	if ( $order->has_status( 'processing' ) && 'cod' === $payment_method ) {
		// return;
	}

	// Get the product_extra meta data and create the product_extra
	$order_items = $order->get_items( 'line_item' );

	if( $order_items ) {
		foreach( $order_items as $order_item ) {
			$product_extras = $order_item->get_meta( 'product_extras' );
			if( ! empty( $product_extras['groups'] ) || ! empty( $product_extras['products'] ) ) {
				// Save the product_extra data
				$product_extra_id = wp_insert_post( array(
					'post_title'	=> $product_extras['title'],
					'post_type'   => 'pewc_product_extra',
					'post_status'	=> 'publish'
				) );
				if( ! is_wp_error( $product_extra_id ) ) {
					wp_update_post(
						array(
							'ID'					=> $product_extra_id,
							'post_title'	=> $product_extras['title'] . ' #' . $product_extra_id
						)
					);
					// User data
					$user_id = $order->get_user_id();
					$user = get_userdata( $user_id );
					if( $user && ! is_wp_error( $user ) ) {
						update_post_meta( $product_extra_id, 'pewc_user_id', absint( $user_id ) );
					}

					update_post_meta( $product_extra_id, 'pewc_order_id', absint( $order_id ) );
					update_post_meta( $product_extra_id, 'pewc_item_cost', $order->get_item_total( $order_item ) );
					update_post_meta( $product_extra_id, 'pewc_order_total', $order->get_total() );
					update_post_meta( $product_extra_id, 'pewc_product_id', absint( $product_extras['product_id'] ) );

					update_post_meta( $product_extra_id, 'pewc_user_name', sanitize_text_field( $order->get_formatted_billing_full_name() ) );
					update_post_meta( $product_extra_id, 'pewc_user_email', sanitize_email( $order->get_billing_email() ) );
					update_post_meta( $product_extra_id, 'pewc_user_phone', sanitize_text_field( $order->get_billing_phone() ) );

					// Save the product_extra ID to the order as well
					update_post_meta( $order_id, 'pewc_product_extra_id', absint( $product_extra_id ) );

				}

				$fields = array();
				if( ! empty( $product_extras['groups'] ) ) {
					foreach( $product_extras['groups'] as $groups ) {
						if( $groups ) {
							foreach( $groups as $group ) {
								if( isset( $group['type'] ) ) {

									$group_id = $group['group_id'];
									$field_id = $group['field_id'];
									$fields[$group_id][$field_id] = array(
										'id'	=> sanitize_text_field( $group['id'] ),
										'type'	=> sanitize_text_field( $group['type'] ),
										'label'	=> sanitize_text_field( $group['label'] ),
										'price'	=> $group['price']
									);

									if( $group['type'] == 'upload' ) {
										// require_once( ABSPATH . 'wp-admin/includes/media.php' );
										// require_once( ABSPATH . 'wp-admin/includes/file.php' );
										// require_once( ABSPATH . 'wp-admin/includes/image.php' );
										// Move image to media folder - we stopped doing this in 3.0
										// $image_src = media_sideload_image( $group['url'], $product_extra_id, '', 'src' ); // Removed 3.0.0
										// Save new location
										// $fields[$group_id][$field_id]['url'] = esc_url( $image_src ); // Removed 3.0.0
										$fields[$group_id][$field_id]['files'] = $group['files'];
										// $fields[$group_id][$field_id]['url'] = esc_url( $group['url'] );
										// $fields[$group_id][$field_id]['display'] = sanitize_text_field( $group['display'] );
										// Delete uploaded image in product_extras folder (tidy up time)
										// unlink( $group['file'] );
									} else {
										$fields[$group_id][$field_id]['value'] = sanitize_text_field( $group['value'] );
									}

									// Use this for fancy stuff, like sending custom emails
									do_action( 'pewc_after_create_product_extra', $product_extra_id, $order, $group );

								}
							}
						}
					}
					if( ! empty( $fields ) ) {
						update_post_meta( $product_extra_id, 'pewc_product_extra_fields', $fields );
					}
				}
			}
		}

	}
}
add_action( 'woocommerce_checkout_order_processed', 'pewc_create_product_extra', 10, 1 );


/**
 * Optionally attach uploaded images to the order email
 */
function pewc_attach_images_to_email( $attachments, $id, $order ) {

	if( ( $id == 'new_order' || $id == 'customer_on_hold_order' ) && get_option( 'pewc_email_images', 'no' ) == 'yes' ) {

		// Find any attachments
		$order_items = $order->get_items( 'line_item' );
		if( $order_items ) {
			foreach( $order_items as $order_item ) {
				$product_extras = $order_item->get_meta( 'product_extras' );
				if( ! empty( $product_extras['groups'] ) ) {
					foreach( $product_extras['groups'] as $group ) {
						foreach( $group as $item_id=>$item ) {
							if( ! empty( $item['files'] ) ) {
								foreach( $item['files'] as $index=>$file ) {
									$attachments[] = $file['file'];
								}
							}
						}
					}
				}
			}
		}

	}

	return $attachments;

}
add_filter( 'woocommerce_email_attachments', 'pewc_attach_images_to_email', 10, 3 );
