<?php
/**
 * Functions for the admin
 * @since 3.2.3
 * @package WooCommerce Product Add-Ons Ultimate
 */

/*
// Need to do some kind of migration again
// Query groups - trash any with post_parent = 0 that are not in the current list of global groups
// Also, update group title fields
*/

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pewc_is_group_public() {
	$public = get_option( 'pewc_is_group_public', 'no' );
	return apply_filters( 'pewc_is_group_public', $public );
}

function pewc_filter_group_title( $title, $post_id=0 ) {
	if( get_post_type( $post_id ) == 'pewc_group' ) {
		$title = pewc_get_group_title( $post_id, array(), true );
	}
	return $title;
}
add_filter( 'the_title', 'pewc_filter_group_title', 10, 2 );

/**
 * Add custom columns
 */
function pewc_group_custom_columns( $columns ) {
	unset( $columns['title'] );
	unset( $columns['date'] );
	$columns['pewc_group_id'] = __( 'ID', 'pewc' );
	$columns['menu_order'] = __( 'Priority', 'pewc' );
	$columns['title'] = __( 'Title', 'pewc' );
	$columns['rule'] = __( 'Rule', 'pewc' );
	$columns['date'] = __( 'Date', 'pewc' );
	return $columns;
}
add_filter( 'manage_pewc_group_posts_columns', 'pewc_group_custom_columns' );

/**
 * Populate custom columns with values
 */
function pewc_group_manage_custom_columns( $column, $post_id ) {
	global $post;
	if ( get_post_type( $post_id ) == 'pewc_group' ) {
		if( $column == 'pewc_group_id' ) {
			echo '&#35;' . $post_id;
		} else if( $column == 'menu_order' ) {
			echo $post->menu_order;
		} else if( $column == 'rule' ) {
			$rule = get_post_meta( $post_id, 'global_rules', true );
			if( ! empty( $rule['all'] ) ) {
				printf(
					'<div>%s</div>',
					__( 'All', 'pewc' )
				);
			}
			if( ! empty( $rule['ids']['products'] ) ) {
				printf(
					'<div>%s: %s</div>',
					__( 'Product IDs', 'pewc' ),
					join( ', ', $rule['ids']['products'] )
				);
			}
			if( ! empty( $rule['categories']['cats'] ) ) {
				printf(
					'<div>%s: %s</div>',
					__( 'Category IDs', 'pewc' ),
					join( ', ', $rule['categories']['cats'] )
				);
			}
		// print_r( $rule );
		}
	}
}
add_action( 'manage_posts_custom_column', 'pewc_group_manage_custom_columns', 10, 2 );

/**
 * Only display global groups in the list
 */
function pewc_group_filter_global( $query ) {
  global $pagenow;
  // Get the post type
  $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
  if ( is_admin() && $pagenow == 'edit.php' && $post_type == 'pewc_group' ) {
		// Only get global
    $query->query_vars['post_parent'] = 0;
		$query->query_vars['orderby'] = 'menu_order';
		$query->query_vars['order'] = 'ASC';
  }
}
add_filter( 'parse_query', 'pewc_group_filter_global' );
