<?php
/**
 * Functions for exporting Product Add-Ons
 * @since 1.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter post class
 * @since 1.4.0
 */
function pewc_filter_post_classes( $classes ) {
	global $post;
	if( 'product' == get_post_type( $post->ID ) && pewc_has_extra_fields( $post->ID ) ) {
		$classes[] = 'has-extra-fields';
		if( pewc_has_flat_rate_field( $post->ID ) ) {
			$classes[] = 'has-flat-rate';
		}
	}
	return $classes;
}
add_filter( 'post_class', 'pewc_filter_post_classes' );

/**
 * Filter body class
 * @since 1.7.0
 */
function pewc_filter_body_classes( $classes ) {
	global $post;
	if( isset( $post->ID ) && 'product' == get_post_type( $post->ID ) && pewc_has_extra_fields( $post->ID ) ) {
		if( pewc_has_flat_rate_field( $post->ID ) ) {
			$classes[] = 'has-flat-rate';
		}
	}
	return $classes;
}
add_filter( 'body_class', 'pewc_filter_body_classes' );

/**
 * Get the product's extra fields
 * @since 1.6.0
 * @param $post_id
 * @return Array
 */
function pewc_get_extra_fields( $post_id ) {
	$has_migrated = pewc_has_migrated();
	if( ! $has_migrated ) {
		// This is the old, pre-3.0.0 method and will be deprecated in future versions
		$product_extra_groups = get_post_meta( $post_id, '_product_extra_groups', true );

	} else {
		// This is the post-3.0.0 method using post types
		// However, it still returns a big groups array like the old method for backwards compatibility
		$product_extra_groups = pewc_get_pewc_groups( $post_id );
	}

	// Filter the groups
	$product_extra_groups = apply_filters( 'pewc_filter_product_extra_groups', $product_extra_groups, $post_id );

	return $product_extra_groups;
}

/**
 * Get child groups for this product
 * @since 3.0.0
 * @return Array
 */
function pewc_get_pewc_groups( $post_id ) {
	$groups = array();
	$group_ids = pewc_get_group_order( $post_id );
	// Iterate through the group IDs and build a big array
	if( $group_ids ) {
		$group_ids = explode( ',', $group_ids );
		foreach( $group_ids as $index=>$group_id ) {
			// Confirm that the group ID is an actual group
			if( 'publish' === get_post_status( $group_id ) ) {
				$groups[$group_id]['items'] = pewc_get_group_fields( $group_id );
			}
		}
	}
	return $groups;
}


/**
 * Get global a correctly formatted global group
 * Post 3.0.0 this is passed a group ID
 * @since 3.0.0
 * @param $group_param Mixed Either integer or array
 * @return Array
 */
function pewc_get_global_groups( $group_param ) {
	$has_migrated = pewc_has_migrated();
	if( ! $has_migrated ) {
		// This is the old, pre-3.0.0 method and will be deprecated in future versions
		return $group_param;
	} else {
		// This is the post-3.0.0 method using post types
		// We want it to return a big groups array like the old method for backwards compatibility
		$group['items'] = pewc_get_group_fields( $group_param );
		return $group;
	}
}

/**
 * Get the list of all global group IDs
 * @since 3.3.0
 * @return List
 */
function pewc_get_all_global_group_ids() {

	if( pewc_is_group_public() != 'yes' ) {

		$global_order = get_option( 'pewc_global_group_order' );

	} else {

		// Get all groups with no parent
		$args = array(
			'post_type'				=> 'pewc_group',
			'post_parent'			=> 0,
			'fields'					=> 'ids',
			'posts_per_page'	=> 999,
			'orderby'					=> 'menu_order',
			'order'						=> 'ASC'
		);
		$groups = new WP_Query( $args );
		$global_order = join( ',', $groups->posts );

	}

	return $global_order;

}

/**
 * Get the display order of groups for this product
 * @since 3.0.0
 * @return String
 */
function pewc_get_group_order( $product_id ) {
	$order = get_post_meta( $product_id, 'group_order', true );
	return $order;
}

/**
 * Get child fields for this group
 * @since 3.0.0
 * @return Array
 */
function pewc_get_group_fields( $group_id ) {
	$all_fields = array();
	$fields = get_post_meta( $group_id, 'field_ids', true );
	if( $fields ) {
		foreach( $fields as $field_id ) {
			// Confirm that the field ID is an actual field
			if( 'publish' === get_post_status( $field_id ) ) {
				$all_fields[$field_id] = pewc_create_item_object( $field_id );
			}
		}
	}
	return $all_fields;
}

/**
 * Before 3.0.0, field data was stored as a serialised array
 * This function just gets our post meta and formats it in an array so we can continue using pre-3.0 templates
 * @since 3.0.0
 * @return Array
 */
function pewc_create_item_object( $field_id ) {
	$item = array(
		'field_id' 	=> $field_id
	);
	$params = pewc_get_field_params( $field_id );
	if( $params ) {
		foreach( $params as $param ) {
			$value = get_post_meta( $field_id, $param, true );
			if( $value ) {
				$item[$param] = $value;
			}
		}
	}
	return apply_filters( 'pewc_item_object', $item, $field_id );
}

/**
 * Returns a list of all params for a field
 * @since 3.0.0
 * @return Array
 */
function pewc_get_field_params( $field_id=null ) {
	$params = array(
		'id', 'group_id', 'field_label', 'field_type', 'field_price', 'field_options', 'first_field_empty', 'field_minchecks', 'field_maxchecks', 'child_products', 'products_layout', 'products_quantities', 'allow_none', 'number_columns', 'hide_labels', 'allow_multiple', 'select_placeholder', 'min_products', 'max_products', 'child_discount', 'discount_type', 'field_required', 'field_flatrate', 'field_percentage', 'field_minchars', 'field_maxchars', 'per_character', 'field_freechars', 'field_alphanumeric', 'field_alphanumeric_charge', 'field_minval', 'field_maxval', 'multiply', 'min_date_today', 'field_mindate', 'field_maxdate', 'field_default', 'field_default_hidden', 'field_image', 'field_description', 'condition_action', 'condition_match', 'condition_field', 'condition_rule', 'condition_value', 'variation_field', 'formula', 'formula_action', 'formula_round', 'decimal_places', 'field_rows', 'multiple_uploads', 'max_files', 'multiply_price', 'hidden_calculation'
	);
	return apply_filters( 'pewc_item_params', $params, $field_id );
}

/**
 * Returns the group title
 * @since 3.0.0
 * @return Array
 */
function pewc_get_group_title( $group_id, $group, $has_migrated ) {
	$group_title = '';
	if( $has_migrated ) {
		$group_title = get_post_meta( $group_id, 'group_title', true );
	} else if( isset( $group['meta']['group_title'] ) ) {
		$group_title = $group['meta']['group_title'];
	}

	return apply_filters( 'pewc_get_group_title', $group_title, $group_id, $has_migrated );
}

/**
 * Returns the group title
 * @since 3.0.0
 * @return Array
 */
function pewc_get_group_description( $group_id, $group, $has_migrated ) {
	$group_description = '';
	if( $has_migrated ) {
		$group_description = get_post_meta( $group_id, 'group_description', true );
	} else if( isset( $group['meta']['group_description'] ) ) {
		$group_description = $group['meta']['group_description'];
	}

	return apply_filters( 'pewc_get_group_description', $group_description, $group_id, $has_migrated );
}

/**
 * Returns the group layout
 * @since 3.1.1
 * @return Array
 */
function pewc_get_group_layout( $group_id ) {
	$group_layout = get_post_meta( $group_id, 'group_layout', true );
	if( ! $group_layout ) $group_layout = 'ul';
	// return 'table';
	return apply_filters( 'pewc_get_group_layout', $group_layout, $group_id );
}

/**
 * Returns the global group rules
 * @since 3.0.0
 * @return Array
 */
function pewc_get_global_rules( $group_id, $group ) {
	$has_migrated = pewc_has_migrated();
	if( $has_migrated ) {
		$rules = get_post_meta( $group_id, 'global_rules', true );
	} else {
		$rules = isset( $group['global_rules'] ) ? $group['global_rules'] : false;
	}
	return $rules;
}

/**
 * Returns the global group operator
 * @since 3.0.0
 * @return Array
 */
function pewc_get_group_operator( $group_id, $group ) {
	$rules = pewc_get_global_rules( $group_id, $group );
	$operator = ( isset( $rules['operator'] ) && $rules['operator'] == 'any' ) ? 'any' : 'all';
	return $operator;
}

/**
 * Check if this product has extra fields
 * @since 1.4.0
 * @return Boolean
 */
function pewc_has_extra_fields( $product_id ) {
	$product_extra_groups = pewc_get_extra_fields( $product_id );
	if( ! empty( $product_extra_groups ) ) {
		return true;
	}
	return false;
}

function pewc_get_group_id( $id ) {
	// Work out group and field IDs from the $id
	$last_index = strrpos( $id, '_' );
	$field_id = substr( $id, $last_index + 1 ); // Find last instance of _
	$group_id = substr( $id, 0, $last_index ); // Remove _field_id from $id
	//$field_id = str_replace( '_', '', $field_id );
	$group_id = strrchr( $group_id, '_' );
	$group_id = str_replace( '_', '', $group_id );
	return $group_id;
}

function pewc_get_field_id( $id ) {
	// Work out group and field IDs from the $id
	$last_index = strrpos( $id, '_' );
	$field_id = substr( $id, $last_index + 1 ); // Find last instance of _
	return $field_id;
}

function pewc_get_field_type( $id, $items ) {
	if( $items ) {
		foreach( $items as $item ) {
			if( $item['id'] == $id ) {
				$field_type = $item['field_type'];
				return $field_type;
			}
		}
	}
	return '';
}

/**
 * Abbreviated form of wc_price
 * @param $price	Price
 * @param $args		Args
 * @return HTML
 */
function pewc_wc_format_price( $price, $args=array() ) {
	extract( apply_filters( 'wc_price_args', wp_parse_args( $args, array(
		'ex_tax_label'       => false,
		'currency'           => '',
		'decimal_separator'  => wc_get_price_decimal_separator(),
		'thousand_separator' => wc_get_price_thousand_separator(),
		'decimals'           => wc_get_price_decimals(),
		'price_format'       => get_woocommerce_price_format(),
	) ) ) );
	$negative = $price < 0;
	$price = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * -1 : $price ) );
	$price = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );
	if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
		$price = wc_trim_zeros( $price );
	}
	$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol( $currency ) . '</span>', $price );
	return $formatted_price;
}

/**
 * Check if this product has a flat rate field
 * @since 1.4.0
 * @return Boolean
 */
function pewc_has_flat_rate_field( $product_id ) {
	$product_extra_groups = pewc_get_extra_fields( $product_id );
	if( ! empty( $product_extra_groups ) ) {
		foreach( $product_extra_groups as $group ) {
			if( ! empty( $group['items'] ) ) {
				foreach( $group['items'] as $key=>$item ) {
					if( ! empty( $item['field_flatrate'] ) ) {
						return true;
					}
				}
			}
		}
	}
	return false;
}

/**
 * Return attributes for text or textarea field
 * @since 2.1.0
 * @return Array
 */
function pewc_get_text_field_attributes( $item ) {
	$attributes = array(
		'data-minchars'							=> ! empty( $item['field_minchars'] ) ? $item['field_minchars'] : '',
		'data-maxchars'							=> ! empty( $item['field_maxchars'] ) ? $item['field_maxchars'] : '',
		'data-freechars'						=> '0',
		'data-alphanumeric'					=> '',
		'data-alphanumeric-charge'	=> '',
	);
	if( pewc_is_pro() ) {
		$attributes['data-freechars'] = ! empty( $item['field_freechars'] ) ? $item['field_freechars'] : '';
		$attributes['data-alphanumeric'] = ! empty( $item['field_alphanumeric'] ) ? $item['field_alphanumeric'] : '';
		$attributes['data-alphanumeric-charge'] = ! empty( $item['field_alphanumeric_charge'] ) ? $item['field_alphanumeric_charge'] : '';
	}
	$attributes = apply_filters( 'pewc_filter_text_field_attributes', $attributes, $item );
	$return = '';
	if( $attributes ) {
		foreach( $attributes as $attribute=>$value ) {
			$return .= $attribute . '="' . $value . '" ';
		}
	}

	return $return;
}

/**
 * Get a formatted price but without any HTML
 */
function pewc_get_semi_formatted_price( $child_product ) {
	$price = $child_product->get_price();
	$semi_formatted_price = $price;
	$negative = $price < 0;
	$price = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * -1 : $price ) );
	$price = apply_filters( 'formatted_woocommerce_price', number_format( $price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() ), $price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() );
	if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && wc_get_price_decimals() > 0 ) {
		$price = wc_trim_zeros( $price );
	}
	$semi_formatted_price = ( $negative ? '-' : '' ) . sprintf( get_woocommerce_price_format(), get_woocommerce_currency_symbol(), $price );
	return $semi_formatted_price;
}

/**
 * Get a formatted price without any HTML for a price string
 */
function pewc_get_semi_formatted_raw_price( $price ) {
	$semi_formatted_price = $price;
	$negative = $price < 0;
	$price = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * -1 : $price ) );
	$price = apply_filters( 'formatted_woocommerce_price', number_format( $price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() ), $price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator() );
	if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && wc_get_price_decimals() > 0 ) {
		$price = wc_trim_zeros( $price );
	}
	$semi_formatted_price = ( $negative ? '-' : '' ) . sprintf( get_woocommerce_price_format(), get_woocommerce_currency_symbol(), $price );
	return $semi_formatted_price;
}

/**
 * Get all simple products
 */
function pewc_get_simple_products() {
	$args = array(
		'type'		=> 'simple',
		'return'	=> 'ids',
		'limit'		=> 999
	);
	$products = wc_get_products( $args );
	return $products;
}

/**
 * Check whether we're displaying prices with tax or not
 */
function pewc_maybe_include_tax( $product, $price ) {
	// global $product;
	$ignore = get_option( 'pewc_ignore_tax', 'no' );
	if ( $price === '' || $price == '0' || $ignore == 'yes' ) {
		return $price;
	}

	$is_negative = ( $price < 0 ) ? true : false;
	if( $is_negative ) {
		return $price;
	}

	if( is_object( $product ) ) {
		$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );
		$display_price = $tax_display_mode == 'incl' ? wc_get_price_including_tax( $product, array( 'price' => $price, 'qty' => 1 ) ) : wc_get_price_excluding_tax( $product, array( 'price' => $price, 'qty' => 1 ) );
	} else {
		$display_price = $price;
	}

	return $display_price;
}

/**
 * Check if product has a calculation field
 */
function pewc_has_calculation_field( $product_id ) {
	$has_calculation = false;
	$groups = pewc_get_extra_fields( $product_id );
	foreach( $groups as $group ) {
		if( isset( $group['items'] ) ) {
			foreach( $group['items'] as $field ) {
				if( $field['field_type'] == 'calculation' ) {
					$has_calculation = true;
					break;
				}
			}
		}
	}
	return $has_calculation;
}

/**
 * Have we enabled DropZone.js uploads?
 */
function pewc_enable_ajax_upload() {
	$enable_js = get_option( 'pewc_enable_dropzonejs', 'no' );
	return apply_filters( 'pewc_enable_dropzonejs', $enable_js );
}

function pewc_get_max_upload() {
	$pewc_max_upload = get_option( 'pewc_max_upload', 1 );
	return apply_filters( 'pewc_filter_max_upload', $pewc_max_upload );
}

/**
 * Get a list of all subscription variations
 * @return Array
 */
function pewc_get_subscription_variations() {

	$variations = array();

	$args = array(
		'type'		=> 'variable-subscription',
		'limit'		=> -1,
		'return'	=> 'ids'
	);
	$query = new WC_Product_Query( $args );
	$variable_subscriptions = $query->get_products();

	if( $variable_subscriptions ) {

		foreach( $variable_subscriptions as $variable_subscription ) {

			$variation = new WC_Product_Variable( $variable_subscription );
			$available_variations = $variation->get_available_variations();

			if( $available_variations ) {

				foreach( $available_variations as $available_variation ) {

					$v = wc_get_product( $available_variation['variation_id'] );
					$variations[$available_variation['variation_id']] = $v->get_name();

				}

			}

		}

	}

	return $variations;

}
