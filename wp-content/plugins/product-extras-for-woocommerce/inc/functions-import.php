<?php
/**
 * Functions for importing Product Add-Ons groups from other products
 * @since 1.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pewc_import_groups() {
	if( ! isset( $_POST['pewc_import_nonce'] ) || ! wp_verify_nonce( $_POST['pewc_import_nonce'], 'pewc_import_nonce' ) ) {
		wp_send_json( array( 'result' => $_POST['pewc_import_nonce'] ) );
		exit;
	}

	$content = '';
	$import_id = absint( $_POST['import_id'] );
	$groups = get_post_meta( $import_id, '_product_extra_groups', true );
	if( ! empty( $groups ) ) {
		ob_start();
		pewc_display_product_groups( $groups, $import_id, true );
		$content = ob_get_clean();
		wp_send_json( array(
			'content'	=> $content,
			'groups'	=> $groups
		) );
		exit;
	}

	wp_send_json( array(
		'result'	=> null
	) );

	exit;

}
add_action( 'wp_ajax_pewc_import_groups', 'pewc_import_groups' );

function pewc_get_id_code() {
	$result = array();
  $strLength = 17;
  $charset = 'abcdef0123456789';
  while (--$strLength) {
	$result[] = $charset[rand( 0, 15 )];
  }
  return join( '', $result );
}
