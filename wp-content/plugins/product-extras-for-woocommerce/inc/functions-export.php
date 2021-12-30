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

if( isset( $_GET['do_export'] ) ) {
	// Do the export
	add_action( 'admin_init', 'pewc_generate_csv' );
}

function pewc_register_export_page() {
	add_submenu_page(
		'edit.php?post_type=pewc_product_extra',
		__( 'Export', 'pewc' ),
		__( 'Export', 'pewc' ),
		'manage_options',
		'export-product-extras',
		'pewc_export_page_callback'
	);
}
add_action( 'admin_menu', 'pewc_register_export_page', 99 );

function pewc_export_page_callback() { ?>
	<div class="wrap">
		<?php printf( '<h1>%s</h2>', __( 'Export Product Add-Ons', 'pewc' ) ); ?>
		<p><?php _e( 'Click the Export button to export all your Product Add-Ons to a csv file which you can then open with Excel or other spreadsheet software.', 'pewc' ); ?></p>
		<p class="submit">
			<a href="<?php echo admin_url( 'admin.php?page=export-product-extras&do_export=true' ); ?>" id="export_product-extras" class="button button-primary"><?php _e( 'Export', 'pewc' ); ?></a>
		</p>

	</div>
<?php }

function pewc_generate_csv() {
	// Capability check
	if( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$filename = 'product-extras-export-' . time() . '.csv';
	$header_row = array(
		'0' => __( 'Product Add On ID', 'pewc' ),
		'1' => __( 'Product Add On Date', 'pewc' ),
		'2' => __( 'User ID', 'pewc' ),
		'3' => __( 'User Name', 'pewc' ),
		'4' => __( 'Email', 'pewc' ),
		'5' => __( 'Phone Number', 'pewc' ),
		'6' => __( 'Address', 'pewc' ),
		'7' => __( 'Order ID', 'pewc' ),
		'8' => __( 'Product', 'pewc' ),
		'9' => __( 'Product Add On Content', 'pewc' ),
		'10'	=> __( 'Notes', 'pewc' ),
		'11'	=> __( 'Status', 'pewc' )
	);
	$data_rows = array();

	$args = array(
		'post_type'			=> 'pewc_product_extra',
		'posts_per_page'	=> -1,
		'post_status'		=> 'publish',
		'fields'			=> 'ids'
	);
	$subs = new WP_Query( $args );

	if( $subs->posts ) {
		$date_format = get_option( 'date_format' );
		foreach( $subs->posts as $sub ) {
			$user_id = get_post_meta( $sub, 'pewc_user_id', true );
			$user = get_userdata( $user_id );
			$address = array();
			$address[] = get_user_meta( $user_id, 'billing_address_1', true );
			$address[] = get_user_meta( $user_id, 'billing_address_2', true );
			$address[] = get_user_meta( $user_id, 'billing_city', true );
			$address[] = get_user_meta( $user_id, 'billing_state', true );
			$address[] = get_user_meta( $user_id, 'billing_country', true );
			$address[] = get_user_meta( $user_id, 'billing_postcode', true );
			$sub_content = '';
			$groups = get_post_meta( $sub, 'pewc_product_extra_fields', true );
			if( $groups ) {
				foreach( $groups as $group ) {
					foreach( $group as $item ) {
						if( isset( $item['type'] ) && $item['type'] == 'upload' && isset( $item['url'] ) ) {
							$sub_content .= esc_url( $item['url'] ) . "\n";
						} else if( isset( $item['type'] ) ) {
							if( isset( $item['label'] ) ) {
								$sub_content .= esc_html( $item['label'] ) . ': ';
							}
							$sub_content .= esc_html( $item['value'] ) . "\n";
						}

					}
				}
			}
			$row = array(
				'0'	=> $sub,
				'1'	=> get_the_date( $date_format, $sub ),
				'2'	=> $user_id,
				'3'	=> $user->display_name,
				'4'	=> $user->user_email,
				'5'	=> get_post_meta( $sub, 'pewc_user_phone', true ),
				'6'	=> str_replace( ', , ', ', ', join( ', ', $address ) ),
				'7'	=> get_post_meta( $sub, 'pewc_order_id', true ),
				'8'	=> get_post_meta( $sub, 'pewc_product_id', true ),
				'9'	=> $sub_content,
				'10'	=> get_post_meta( $sub, 'pewc_extra_notes', true ),
				'11'	=> get_post_meta( $sub, 'pewc_extra_status', true )
			);
			$data_rows[] = $row;
		}
	}

	$fh = @fopen( 'php://output', 'w' );
	fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Content-Description: File Transfer' );
	header( 'Content-type: text/csv' );
	header( "Content-Disposition: attachment; filename={$filename}" );
	header( 'Expires: 0' );
	header( 'Pragma: public' );
	fputcsv( $fh, $header_row );
	foreach ( $data_rows as $data_row ) {
		fputcsv( $fh, $data_row );
	}
	fclose( $fh );
	die();
}
