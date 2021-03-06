<?php
/**
 * Functions for fields and groups in the admin
 * @since 3.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create a new group and return its ID
 * @since 3.0.0
 * @return Integer
 */
function pewc_get_new_group_id() {
  // Do security check
  if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'add_new_pewc_group_nonce' ) ) {
		wp_send_json_error( 'nonce_fail' );
	}
  if( ! isset( $_POST['parent_id'] ) ) {
		wp_send_json_error( 'no_parent' );
	}
	// MOVE THIS TO SEPARATE FUNCTION SO THAT WE CAN CALL IT FROM THE MIGRATION TOOL
  $parent_id = absint( $_POST['parent_id'] );
	$group_order = $_POST['group_order'];
	update_post_meta( $parent_id, 'group_order', $group_order );

  // Create a new group
  $group_data = pewc_create_new_group( $parent_id, $group_order );
	if( $group_data ) {
		wp_send_json_success( $group_data );
	}

	wp_send_json_error( 'group_not_created' );
	exit;
}
add_action( 'wp_ajax_pewc_get_new_group_id', 'pewc_get_new_group_id' );

/**
 * Create a new group post
 * @since 3.0.0
 * @return Mixed Array if successful, false if not
 */
function pewc_create_new_group( $parent_id, $group_order ) {

  // Create a new group
  $group_id = wp_insert_post(
    array(
      'post_status' => 'publish',
      'post_type'   => 'pewc_group',
      'post_parent' => $parent_id
    )
  );
  if( $group_id ) {
		// Add this group to the list of groups
		$group_order = pewc_add_group_to_group_order( $parent_id, $group_id );
		update_post_meta( $parent_id, 'group_order', $group_order );
    return array(
			'group_id'		=> $group_id,
			'group_order'	=> $group_order
		);
  }

	return false;

}

/**
 * Duplicate a group
 * @since 3.0.0
 * @return Integer
 */
function pewc_duplicate_group() {

	// Do security check
  if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'add_new_pewc_group_nonce' ) ) {
		wp_send_json_error( 'nonce_fail' );
	}

  if( ! isset( $_POST['old_group_id'] ) ) {
		wp_send_json_error( 'no_old_group_id' );
	}

	$old_group_id = absint( $_POST['old_group_id'] );
	// 0 for product ID means it's a global group
	$product_id = ! empty( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
	$group_order = $_POST['group_order'];
	// update_post_meta( $product_id, 'group_order', $group_order );

	$old_group = get_post( $old_group_id );
  // Create a new group
  $group_id = wp_insert_post(
    array(
      'post_status' => 'publish',
      'post_type'   => 'pewc_group',
      'post_parent' => $product_id
    )
  );

  if( $group_id ) {

		$new_fields = array();
		$mapped_fields = array();

		// Check if the duplicated group has fields
		$duplicated_fields = get_post_meta( $old_group_id, 'field_ids', true );

		if( $duplicated_fields ) {

			// Duplicate each field
			foreach( $duplicated_fields as $old_field_id ) {

				$new_field_id = pewc_duplicate_field_by_id( $old_field_id );
				// Make an array to map old field IDs to their duplicated versions
				$mapped_fields[$old_field_id] = $new_field_id;

			}

		}

		update_post_meta( $group_id, 'field_ids', array_values( $mapped_fields ) );

		if( $product_id ) {

			// On the product page
			$group_order = pewc_add_group_to_group_order( $product_id, $group_id );
			update_post_meta( $product_id, 'group_order', $group_order );

		} else {

			// Global group
			$group_order = array_filter( explode( ',', $group_order ) );
			// Add new group ID
			$group_order[] = $group_id;
			// Convert back to string
			$group_order = join( ',', $group_order );
			update_option( 'pewc_global_group_order', $group_order );

		}

		$group_title = get_post_meta( $old_group_id, 'group_title', true );
		if( $group_title ) {
			update_post_meta( $group_id, 'group_title', sanitize_text_field( $group_title ) );
		}

		$group_description = get_post_meta( $old_group_id, 'group_description', true );
		if( $group_description ) {
			update_post_meta( $group_id, 'group_description', wp_kses_post( $group_description ) );
		}

    wp_send_json_success(
			array(
				'group_id'		=> $group_id,
				'group_order'	=> $group_order,
				'fields'			=> $mapped_fields
			)
		);

  }

	wp_send_json_error( 'group_not_created' );
	exit;

}
add_action( 'wp_ajax_pewc_duplicate_group', 'pewc_duplicate_group' );

/**
 * Delete a group
 * @since 3.0.0
 * @return Integer
 */
function pewc_remove_group_id() {
  // Do security check
  if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'add_new_pewc_group_nonce' ) ) {
		wp_send_json_error( 'nonce_fail' );
	}
  if( ! isset( $_POST['group_id'] ) ) {
		wp_send_json_error( 'no_group_id' );
	}
  $group_id = absint( $_POST['group_id'] );
	// 0 for product ID means it's a global group

	$product_id = ! empty( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;

	if( $product_id ) {
		$group_order = $_POST['group_order'];
	} else {
		$group_order = get_option( 'pewc_global_group_order', '' );
	}

  // Create a new group
  $delete = wp_delete_post( $group_id, true );
	// Delete the group from the groups list
	$group_order = pewc_remove_group_from_group_order( $group_order, $group_id );

	if( $product_id ) {
		update_post_meta( $product_id, 'group_order', $group_order );
	} else {
		update_option( 'pewc_global_group_order', $group_order );
	}

	wp_send_json_success(
		array(
			'group_id'		=> $group_id,
			'group_order'	=> $group_order
		)
	);
	exit;
}
add_action( 'wp_ajax_pewc_remove_group_id', 'pewc_remove_group_id' );

/**
 * Create a new field and return its ID
 * @since 3.0.0
 * @return Integer
 */
function pewc_get_new_field_id() {
  // Do security check
  if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'add_new_pewc_group_nonce' ) ) {
		wp_send_json_error( 'nonce_fail' );
	}
  if( ! isset( $_POST['group_id'] ) ) {
		wp_send_json_error( 'no_group' );
	}
  $group_id = absint( $_POST['group_id'] );
  // Create a new group
  $field_id = pewc_create_new_field( $group_id );
  if( $field_id ) {
    wp_send_json_success( $field_id );
  }
	wp_send_json_error( 'field_not_created' );
	exit;
}
add_action( 'wp_ajax_pewc_get_new_field_id', 'pewc_get_new_field_id' );

/**
 * Create a new field post
 * @since 3.0.0
 * @param $group_id		The parent group ID
 * @return Mixed Integer if successful, false if not
 */
function pewc_create_new_field( $group_id ) {
	// Create a new group
  $field_id = wp_insert_post(
    array(
      'post_status' => 'publish',
      'post_type'   => 'pewc_field',
      'post_parent' => $group_id
    )
  );
  if( $field_id ) {
		// Add the field ID to the list of fields under this group
		pewc_add_field_to_group( $field_id, $group_id );
    return $field_id;
  }

	return false;

}

/**
 * Duplicate a field
 * @since 3.0.0
 * @return Integer
 */
function pewc_duplicate_field() {
	// Do security check
  if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'add_new_pewc_group_nonce' ) ) {
		wp_send_json_error( 'nonce_fail' );
	}
  if( ! isset( $_POST['old_field_id'] ) ) {
		wp_send_json_error( 'no_field' );
	}
	$old_field_id = absint( $_POST['old_field_id'] );
	$product_id = absint( $_POST['product_id'] );
  $group_id = absint( $_POST['group_id'] );
  // Create a new group
  $field_id = pewc_duplicate_field_by_id( $old_field_id );
  if( $field_id ) {
		// Add the field ID to the list of fields under this group
		pewc_add_field_to_group( $field_id, $group_id );
    wp_send_json_success( $field_id );
  }
	wp_send_json_error( 'field_not_created' );
	exit;

}
add_action( 'wp_ajax_pewc_duplicate_field', 'pewc_duplicate_field' );

/**
 * Duplicate a field by ID
 * @since 3.0.0
 * @return Integer
 */
function pewc_duplicate_field_by_id( $old_field_id ) {
	$old_field = get_post( $old_field_id );
	// Create a new group
	$field_id = wp_insert_post(
		array(
			'post_status' => 'publish',
			'post_type'   => 'pewc_field',
			'post_parent' => $old_field->post_parent
		)
	);
	if( $field_id ) {
		// Iterate through all meta fields
		$params = pewc_get_field_params( $field_id );
		if( $params ) {
			foreach( $params as $param ) {
				// Copy each value to new field
				$value = get_post_meta( $old_field_id, $param, true );
				update_post_meta( $field_id, $param, $value );
			}
		}
		return $field_id;
	}
	return false;
}

/**
 * Delete a field
 * @since 3.0.0
 * @return Integer
 */
function pewc_remove_field_id() {
  // Do security check
  if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'add_new_pewc_group_nonce' ) ) {
		wp_send_json_error( 'nonce_fail' );
	}
  if( ! isset( $_POST['item_id'] ) || ! isset( $_POST['group_id'] ) ) {
		wp_send_json_error( 'no_ids' );
	}
	$group_id = absint( $_POST['group_id'] );
  $item_id = absint( $_POST['item_id'] );

  // Delete the field post
  $delete = wp_delete_post( $item_id, true );
	// Remove this field ID from the parent group
	$fields = get_post_meta( $group_id, 'field_ids', true );
	// Unset element by value
	if( ( $key = array_search( $item_id, $fields ) ) !== false ) {
    unset( $fields[$key] );
	}
	update_post_meta( $group_id, 'field_ids', $fields );
  wp_send_json_success( $item_id );
	exit;
}
add_action( 'wp_ajax_pewc_remove_field_id', 'pewc_remove_field_id' );

/**
 * Create a new group and return its ID
 * Called from the Global Add-Ons page
 * @since 3.0.0
 * @return Integer
 */
function pewc_get_new_global_group_id() {
  // Do security check
  if( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'pewc_global_set' ) ) {
		wp_send_json_error( 'nonce_fail' );
	}

  $parent_id = 0;
	$group_order = $_POST['group_order'];
	update_option( 'pewc_global_group_order', $group_order );

  // Create a new group
  $group_id = wp_insert_post(
    array(
      'post_status' => 'publish',
      'post_type'   => 'pewc_group',
      'post_parent' => 0
    )
  );
  if( $group_id ) {
		// Add this group to the list of global groups
		$group_order = array_filter( explode( ',', $group_order ) );
		// Add new group ID
		$group_order[] = $group_id;
		// Convert back to string
		$group_order = join( ',', $group_order );

		update_option( 'pewc_global_group_order', $group_order );
    wp_send_json_success(
			array(
				'group_id'		=> $group_id,
				'group_order'	=> $group_order
			)
		);
  }
	wp_send_json_error( 'group_not_created' );
	exit;
}
add_action( 'wp_ajax_pewc_get_new_global_group_id', 'pewc_get_new_global_group_id' );

/**
 * Add a group to a product's list of groups
 * @since 3.0.0
 * @return String		E.g. 259,265,275
 */
function pewc_add_group_to_group_order( $product_id, $group_id ) {
	// Group order is saved as comma separated string
	$groups = trim( pewc_get_group_order( $product_id ), ',' );
	// Convert to array, without any empty elements
	$groups = array_filter( explode( ',', $groups ) );
	// Add new group ID
	$groups[] = $group_id;
	// Convert back to string
	$groups = join( ',', $groups );
	$groups = trim( $groups, ',' );
	return $groups;
}

/**
 * Remove a group from a list of groups
 * @since 3.0.0
 * @return String		E.g. 259,265,275
 */
function pewc_remove_group_from_group_order( $group_order, $group_id ) {
	$group_order = explode( ',', $group_order );
	// Unset element by value
	if( ( $key = array_search( $group_id, $group_order ) ) !== false ) {
    unset( $group_order[$key] );
	}
	$group_order = join( ',', $group_order );
	$group_order = trim( $group_order, ',' );
	return $group_order;
}

/**
 * Add a field to a group
 * @since 3.0.0
 * @return String		E.g. 259,265,275
 */
function pewc_add_field_to_group( $field_id, $group_id ) {
	// field_ids is an array
	$fields = get_post_meta( $group_id, 'field_ids', true );
	if( ! $fields ) {
		$fields = array( $field_id );
	} else {
		$fields[] = $field_id;
	}
	update_post_meta( $group_id, 'field_ids', $fields );
}
