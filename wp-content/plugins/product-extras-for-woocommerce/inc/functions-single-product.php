<?php
/**
 * Functions for the product page
 * @since 1.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return whether user can upload files
 * @return Boolean
 */
function pewc_can_upload() {
	$require_log_in = get_option( 'pewc_require_log_in', 'yes' );
	if( $require_log_in == 'yes' && ! is_user_logged_in() ) {
		return false;
	}
	return true;
}

function pewc_enqueue_scripts() {
	if( ! function_exists( 'get_woocommerce_currency_symbol' ) ) {
		return;
	}
	global $product, $post;
	// $post_id = $post->ID;
	$version = defined( 'PEWC_SCRIPT_DEBUG' ) && PEWC_SCRIPT_DEBUG ? time() : PEWC_PLUGIN_VERSION;

	if( pewc_enable_ajax_upload() == 'yes' ) {
		wp_enqueue_style( 'pewc-dropzone-basic', trailingslashit( PEWC_PLUGIN_URL ) . 'assets/css/basic.min.css', array(), $version );
		wp_enqueue_style( 'pewc-dropzone', trailingslashit( PEWC_PLUGIN_URL ) . 'assets/css/dropzone.min.css', array(), $version );
	}

	wp_enqueue_style( 'pewc-style', trailingslashit( PEWC_PLUGIN_URL ) . 'assets/css/style.css', array(), $version );

	$deps = array( 'jquery', 'jquery-blockui', 'jquery-ui-datepicker' );

	// Only load math.js if we have a calculation field
	if( isset( $post->ID ) && pewc_has_calculation_field( $post->ID ) ) {
		wp_enqueue_script( 'pewc-math-js', trailingslashit( PEWC_PLUGIN_URL ) . 'assets/js/math.min.js', array(), '5.10.3', true );
		$deps[] = 'pewc-math-js';
	}

	if( pewc_enable_ajax_upload() == 'yes' ) {
		wp_register_script( 'pewc-dropzone', trailingslashit( PEWC_PLUGIN_URL ) . 'assets/js/dropzone.js', $deps, $version, true );
		$deps[] = 'pewc-dropzone';
	}

	wp_register_script( 'pewc-script', trailingslashit( PEWC_PLUGIN_URL ) . 'assets/js/pewc.js', $deps, $version, true );

	wp_localize_script(
		'pewc-script',
		'pewc_vars',
		array(
			'ajaxurl'							=> admin_url( 'admin-ajax.php' ),
			'post_id'							=> isset( $post->ID ) ? $post->ID : '',
			'currency_symbol'			=> get_woocommerce_currency_symbol(),
			'decimal_separator'  	=> wc_get_price_decimal_separator(),
			'thousand_separator' 	=> wc_get_price_thousand_separator(),
			'decimals'           	=> wc_get_price_decimals(),
			'price_format'       	=> get_woocommerce_price_format(),
			'currency_pos' 				=> get_option( 'woocommerce_currency_pos' ),
			'variable_1'					=> get_option( 'pewc_variable_1', 0 ),
			'variable_2'					=> get_option( 'pewc_variable_2', 0 ),
			'variable_3'					=> get_option( 'pewc_variable_3', 0 ),
			'drop_files_message'	=> apply_filters( 'pewc_filter_drop_files_message', __( 'Drop files here to upload', 'pewc' ), $post->ID )
		)
	);
	wp_enqueue_script( 'pewc-script' );
}
add_action( 'wp_enqueue_scripts', 'pewc_enqueue_scripts' );

function pewc_enqueue_child_products_script() {
	$version = defined( 'PEWC_SCRIPT_DEBUG' ) && PEWC_SCRIPT_DEBUG ? time() : PEWC_PLUGIN_VERSION;
	wp_register_script( 'pewc-variations-script', trailingslashit( PEWC_PLUGIN_URL ) . 'assets/js/pewc-variations.js', array( 'jquery', 'pewc-script', 'wc-add-to-cart-variation' ), $version, true );
	wp_enqueue_script( 'pewc-variations-script' );
}
add_action( 'pewc_products_column_layout', 'pewc_enqueue_child_products_script', 10 );

/**
 * Display the product_extra fields
 */
function pewc_product_extra_fields() {
	$did = did_action( 'woocommerce_before_add_to_cart_button' );
	if( $did > 1 ) {
		return;
	}
	global $product, $post;
	$post_id = $post->ID;
	$licence = pewc_get_license_level();

	if( $product->get_type() != 'simple' && $product->get_type() != 'variable' && $product->get_type() != 'simple_booking' ) {
		// return;
	}

	$product_extra_groups = pewc_get_extra_fields( $post_id );

	if( $product_extra_groups ) {

		$groups_wrapper_classes = apply_filters(
			'pewc_filter_groups_wrapper_classes',
			array(
				'pewc-product-extra-groups-wrap'
			),
			$product_extra_groups,
			$post_id
		);

		echo '<div class="' . join( ' ', $groups_wrapper_classes ) . '">';

		do_action( 'pewc_start_groups', array( $post_id, $product_extra_groups ) );

		// Check for permissions
		$can_upload = pewc_can_upload();
		$first_group_class = 'first-group';

		foreach( $product_extra_groups as $group_id=>$group ) {

			$wrapper_classes = apply_filters(
				'pewc_filter_group_wrapper_class',
				array(
					'pewc-group-wrap',
					'pewc-group-wrap-' . $group_id,
					$first_group_class
				),
				$group_id,
				$group,
				$post_id
			);

			$first_group_class = '';

			echo '<div class="' . join( ' ', $wrapper_classes ) . '">';

				echo '<div class="pewc-group-heading-wrapper">';

					$group_title = pewc_get_group_title( $group_id, $group, pewc_has_migrated() );

					if( $group_title ) {
						echo apply_filters( 'pewc_filter_group_title', sprintf( '<h3>%s</h3>', esc_html( $group_title ), $group ) );
					}
					$group_class = '';
					if( isset( $group['meta']['group_required'] ) ) {
						$group_class = 'require-' . $group['meta']['group_required'];
					}

				echo '</div><!-- .pewc-group-heading-wrapper -->';
				echo '<div class="pewc-group-content-wrapper">';

					$description = pewc_get_group_description( $group_id, $group, pewc_has_migrated() );

					if( $description ) {
						echo apply_filters(
							'pewc_filter_group_description',
							sprintf(
								'<p class="pewc-group-description">%s</p>',
								wp_kses_post( $description ),
								$group
							)
						);
					}

					$group_layout = pewc_get_group_layout( $group_id );

					echo '<' . $group_layout . ' class="pewc-product-extra-groups ' . esc_attr( $group_class ) . '">';

					if( $group_layout == 'table' ) {
						echo '<tbody>';
					}

					if( isset( $group['items'] ) ) {

						foreach( $group['items'] as $item ) {

							$item = apply_filters( 'pewc_filter_item_start_list', $item, $group, $group_id, $post_id );

							if( isset( $item['field_type'] ) ) {

								$id = $item['id'];

								// Set default value if it exists
								$value = ! empty( $item['field_default_hidden'] ) ? $item['field_default_hidden'] : '';
								// Ensure fields are repopulated after failed validation
								$value = ! empty( $_POST[$id] ) ? $_POST[$id] : $value;
								// Ensure checkbox default is retained
								if( $value == 'checked' || $value == '__checked__' ) $value = 1;

								// Set the wrapper classes
								$required_class = '';
								if( isset( $item['field_required'] ) && $item['field_type'] != 'products' ) {
									$required_class = 'required-field';
								}

								$classes = array( $id );

								$classes[] = 'pewc-group-' . esc_attr( $item['field_type'] );
								$classes[] = 'pewc-item-' . esc_attr( $item['field_type'] );
								$classes[] = 'pewc-field-' . esc_attr( $item['field_id'] );

								if( ! empty( $item['field_required'] ) ) {
									$classes[] = 'required-field';
								}
								if( pewc_is_field_hidden( $item, $post_id ) ) {
									$classes[] = 'pewc-hidden-field';
								}
								if( isset( $item['per_character'] ) ) {
									$classes[] = 'pewc-per-character-pricing';
								}
								if( ! empty( $item['field_maxchars'] ) ) {
									$classes[] = 'pewc-has-maxchars';
								}
								if( isset( $item['multiply'] ) ) {
									$classes[] = 'pewc-multiply-pricing';
								}
								if( isset( $item['field_flatrate'] ) ) {
									$classes[] = 'pewc-flatrate';
								}
								if( ! empty( $item['variation_field'] ) ) {
									$classes[] = 'pewc-variation-dependent';
								}
								if( $licence > -1 ) {
									if( isset( $item['field_percentage'] ) ) {
										$classes[] = 'pewc-percentage';
									}
								}
								$hidden_calculation = ! empty( $item['hidden_calculation'] );
								if( $hidden_calculation && $item['field_type'] == 'calculation' ) {
									$classes[] = 'pewc-hidden-calculation';
								}

								$field_image = pewc_get_field_image( $item, $id );
								if( $field_image ) {
									$classes[] = 'pewc-has-field-image';
								}

								if( $item['field_type'] == 'products' && ! empty( $item['products_layout'] ) ) {
									$classes[] = 'pewc-item-products-' . esc_attr( $item['products_layout'] );
								}

								$classes = apply_filters( 'pewc_filter_single_product_classes', $classes, $item );

								$price = 0;
								if( isset( $item['field_price'] ) && $item['field_type'] != 'products' ) {
									$price = floatval( $item['field_price'] );
								}
								$price = pewc_maybe_include_tax( $product, $price );

								$attributes = array(
									'data-price'									=> $price,
									'data-id'											=> $id,
									'data-selected-option-price'	=> ''
								);

								if( pewc_is_pro() ) {

									if( ! empty( $item['field_percentage'] ) && ! empty( $item['field_price'] ) ) {
										// Set the option price as a percentage of the product price
										$product_price = $product->get_price();
										$price = ( floatval( $item['field_price'] ) / 100 ) * $product_price;
										// Get display price according to inc tax / ex tax setting
										$price = pewc_maybe_include_tax( $product, $price );
										$attributes['data-price']	= $price;
										$attributes['data-percentage'] = floatval( $item['field_price'] );
									}

								}

								$attributes = apply_filters( 'pewc_filter_item_attributes', $attributes, $item );
								$attribute_string = '';
								foreach( $attributes as $attribute=>$attribute_value ) {
									$attribute_string .= ' ' . $attribute . '="' . $attribute_value . '"';
								}

								$group_inner_tag = 'li';
								$cell_tag = 'div';
								$open_td = '';
								$close_td = '';
								if( $group_layout == 'table' ) {
									$group_inner_tag = 'tr';
									$cell_tag = 'td';
									$open_td = '<td>';
									$close_td = '</td>';
								}	?>

								<<?php echo $group_inner_tag; ?> class="pewc-group pewc-item <?php echo join( ' ', $classes ); ?>" <?php echo $attribute_string; ?>>

									<?php // Check for an image
									if( $field_image ) {
										printf(
											'<%s class="pewc-item-field-image-wrapper">%s</%s>',
											$cell_tag,
											$field_image,
											$cell_tag
										);
									} else if( ! $field_image && $group_layout == 'table' ) {
										// Include an empty td to ensure table columns are equal
										echo '<td></td>';
									}

									if( $group_layout == 'ul' ) {
										echo '<' . $cell_tag . ' class="pewc-item-field-wrapper">';
									}

										// Include the field template
										$file = str_replace( '_', '-', $item['field_type'] ) . '.php';

										if( $file ) {

											if( $file == 'radio-image.php') $file = 'image-swatch.php';

											/**
											 * @hooked pewc_before_frontend_template
											 */
											do_action( 'pewc_before_include_frontend_template', $item, $id, $group_layout, $file );

											$path = pewc_include_frontend_template( $file );
											include( $path );

											/**
											 * @hooked pewc_after_frontend_template	10
											 */
											do_action( 'pewc_after_include_frontend_template', $item, $id, $group_layout, $file );

										}

										/**
										 * @hooked pewc_field_description_list_layout
										 */
										do_action( 'pewc_after_field_template', $item, $id, $group_layout );

										if( $group_layout == 'ul' ) {
											echo '</' . $cell_tag . '>';
										} ?>

								</<?php echo $group_inner_tag; ?>><!-- .pewc-item -->

							<?php }

						}

						if( $group_layout == 'table' ) {
							echo '</tbody>';
						}

					}

					echo '</' . $group_layout . '><!-- .pewc-product-extra-groups -->';

				echo '</div><!-- .pewc-group-content-wrapper -->';

			echo '</div><!-- .pewc-product-extra-group-wrap -->';

		}

		// Add total fields
		$show_totals = apply_filters( 'pewc_product_show_totals', get_option( 'pewc_show_totals', 'all' ), $post_id );
		if( $show_totals == 'all' ) {
			$path = pewc_include_frontend_template( 'price-subtotals.php' );
			include( $path );
		} else if( $show_totals == 'total' ) {
			printf(
				'<p class="pewc-total-only">%s<span id="pewc-grand-total" class="pewc-total-field"></span></p>',
				apply_filters( 'pewc_total_only_text', '', $post_id )
			);
		}

		// Hidden fields with product data
		if( $product->is_type( 'variable' ) ) {
			$default_price = 0;
		} else {
			$default_price = $product->get_price();
		}
		echo '<input type="hidden" id="pewc-product-price" name="pewc-product-price" value="' . pewc_maybe_include_tax( $product, $default_price ) . '">';
		echo '<input type="hidden" id="pewc_total_calc_price" name="pewc_total_calc_price" value="">';
		echo '<input type="hidden" id="pewc_variation_price" name="pewc_variation_price" value="">';
		echo '</div><!-- .pewc-product-extra-groups-wrap -->';
		wp_nonce_field( 'pewc_file_upload', 'pewc_file_upload' );
		wp_nonce_field( 'pewc_total', 'pewc_total' );
	}
	do_action( 'pewc_after_product_fields' );
}
add_action( 'woocommerce_before_add_to_cart_button', 'pewc_product_extra_fields' );

/**
 * Display the field label
 * For all fields except checkbox in list view
 */
function pewc_before_frontend_template( $item, $id, $group_layout, $file ) {
	if( $group_layout == 'table' || $item['field_type'] != 'checkbox' ) {
		echo pewc_field_label( $item, $id, $group_layout );
	}
}
add_action( 'pewc_before_include_frontend_template', 'pewc_before_frontend_template', 10, 4 );

/**
 * Display the field label for the checkbox in list view
 */
function pewc_after_frontend_template( $item, $id, $group_layout, $file ) {
	if( $group_layout == 'ul' && $item['field_type'] == 'checkbox' ) {
		echo pewc_field_label( $item, $id, $group_layout );
	}
}
add_action( 'pewc_after_include_frontend_template', 'pewc_after_frontend_template', 10, 4 );

/**
 * Get the field label
 */
function pewc_field_label( $item, $id, $group_layout='ul' ) {

	global $product;

	$open_td = '';
	$close_td = '';
	if( $group_layout == 'table' ) {
		$open_td = '<td>';
		$close_td = '</td>';
	}

	$label = $open_td;
	$price_label = '';

	// if( $item['field_type'] == 'calculation' ) {
	// 	$price_label = '';
	// }

	if( isset( $item['field_label'] ) || isset( $item['field_price'] ) ) {

		$label .= '<label class="pewc-field-label" for="' . esc_attr( $id ) . '">';
		if( isset( $item['field_label'] ) ) {
			$label .= wp_kses_post( $item['field_label'] );
		}
		$label .= '<span class="required"> &#42;</span>';

		// Get the price
		if( ! empty( $item['field_price'] ) && $item['field_type'] != 'name_price' && $item['field_type'] != 'products' ) {
			// Check if it's a percentage
			$price = apply_filters( 'pewc_filter_display_price_for_percentages', $item['field_price'], $product, $item );
			// Get display price according to inc tax / ex tax setting
			$price = pewc_maybe_include_tax( $product, $price );
			// Format the price
			$formatted_price = pewc_wc_format_price( $price );
			$price_label .= '<span class="pewc-field-price"> ' . $formatted_price;
			if( ! empty( $item['per_character'] ) && ( $item['field_type'] == 'text' || $item['field_type'] == 'textarea' ) ) {
				$price_label .= ' <span class="pewc-per-character-label">' . __( 'per character', 'pewc' ) . '</span>';
			}
			$price_label .= '</span>';
		}

		// if( $group_layout == 'ul' ) {
			$label .= $price_label;
		// }

		$label .= '</label>';

		if( $group_layout == 'table' ) {
			$label .= pewc_get_field_description( $item, $id, $group_layout );
		}

		$label .= $close_td;

	}

	return apply_filters( 'pewc_filter_field_label', $label, $item, $id );

}

/**
 * Return the markup for the field image, if present
 * @since 1.7.2
 * @return Markup
 */
function pewc_get_field_image( $item, $id ) {
	$image = '';
	if( ! empty( $item['field_image'] ) ) {
		$attachment_id = $item['field_image'];
		$size = apply_filters( 'pewc_filter_field_image_size', 'thumbnail' );
		$image = wp_get_attachment_image( $attachment_id, $size );
	}
	return apply_filters( 'pewc_filter_field_image', $image, $item, $id );
}

/**
 * Show the description for the list view
 */
function pewc_field_description_list_layout( $item, $id, $group_layout='ul' ) {
	if( $group_layout == 'ul' ) {
		pewc_field_description( $item, $id, $group_layout );
	}
}
add_action( 'pewc_after_field_template', 'pewc_field_description_list_layout', 10, 3 );

/**
 * Get the description
 */
function pewc_get_field_description( $item, $id, $group_layout='ul' ) {

	$additional_info = '';
	if( ! empty( $item['field_minval'] ) ) {
		if( $item['field_type'] == 'name_price' ) {
			$min = wc_price( $item['field_minval'] );
		} else {
			$min = esc_html( $item['field_minval'] );
		}
		$additional_info .= sprintf( '<small>%s: %s</small>',
			__( 'Min', 'pewc' ),
			$min
		);
	}
	if( ! empty( $item['field_maxval'] ) ) {
		if( $item['field_type'] == 'name_price' ) {
			$max = wc_price( $item['field_maxval'] );
		} else {
			$max = esc_html( $item['field_maxval'] );
		}
		$additional_info .= sprintf( '<small>%s: %s</small>',
			__( 'Max', 'pewc' ),
			$max
		);
	}
	if( ! empty( $item['field_minchars'] ) && ( $item['field_type'] == 'text' || $item['field_type'] == 'textarea' ) ) {
		$additional_info .= sprintf( '<small>%s: %s %s</small>',
			__( 'Min', 'pewc' ),
			esc_html( $item['field_minchars'] ),
			__( 'characters', 'pewc' )
		);
	}
	if( ! empty( $item['field_maxchars'] ) && ( $item['field_type'] == 'text' || $item['field_type'] == 'textarea' ) ) {
		$additional_info .= sprintf( '<small>%s: %s %s</small>',
			__( 'Max', 'pewc' ),
			esc_html( $item['field_maxchars'] ),
			__( 'characters', 'pewc' )
		);
	}
	if( $item['field_type'] == 'upload' ) {
		$max = pewc_get_max_upload();
		$file_types = pewc_get_pretty_permitted_mimes();
		$additional_info .= sprintf( '<small>%s: %s MB</small>',
			apply_filters( 'pewc_filter_max_file_size_message', __( 'Max file size', 'pewc' ) ),
			$max
		);
		$additional_info .= sprintf( '<small>%s: %s</small>',
			apply_filters( 'pewc_filter_permitted_file_types_message', __( 'Permitted file types', 'pewc' ) ),
			$file_types
		);
	}

	$field_description = ! empty( $item['field_description'] ) ? $item['field_description'] : '';
	if( ! empty( $item['field_description'] ) || $additional_info ) {
		return apply_filters(
			'pewc_filter_field_description',
			sprintf(
				'<p class="pewc-description">%s%s</p>',
				wp_kses_post( $field_description ),
				$additional_info
			),
			$item
		);
	}
}

function pewc_field_description( $item, $id, $group_layout='ul' ) {

	echo pewc_get_field_description( $item, $id, $group_layout='ul' );

}

/**
 * Filter the price label
 */
function pewc_get_price_html( $price, $product ) {
	// Only for products that have Product Add-Ons
	if( ! pewc_has_product_extra_groups( $product->get_id() ) ) {
		return $price;
	}
	// Override with any product specific settings
	$pewc_price_label = $product->get_meta( 'pewc_price_label' );
	$pewc_price_display = $product->get_meta( 'pewc_price_display' );
	if( $pewc_price_label && $pewc_price_display == 'before' ) {
		$price = $pewc_price_label . ' ' . $price;
	} else if( $pewc_price_label && $pewc_price_display == 'after' ) {
		$price = $price . ' ' . $pewc_price_label;
	} else if( $pewc_price_display == 'hide' ) {
		$price = $pewc_price_label;
	} else {
		// If no product label set, check the global
		$pewc_price_label = get_option( 'pewc_price_label' );
		$pewc_price_display = get_option( 'pewc_price_display' );
		if( $pewc_price_label && $pewc_price_display == 'before' ) {
			$price = $pewc_price_label . ' ' . $price;
		} else if( $pewc_price_label && $pewc_price_display == 'after' ) {
			$price = $price . ' ' . $pewc_price_label;
		} else if( $pewc_price_display == 'hide' ) {
			$price = $pewc_price_label;
		}
	}

	return $price;
}
add_filter( 'woocommerce_get_price_html', 'pewc_get_price_html', 10, 2 );
