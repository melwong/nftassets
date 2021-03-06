<?php
/**
 * PDF invoice header template that will be visible on every page.
 *
 * This template can be overridden by copying it to youruploadsfolder/woocommerce-pdf-invoices/templates/invoice/simple/yourtemplatename/header.php.
 *
 * HOWEVER, on occasion WooCommerce PDF Invoices will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author  Bas Elbers
 * @package WooCommerce_PDF_Invoices/Templates
 * @version 0.0.1
 */

$templater       = WPI()->templater();
$order           = $templater->order;
$invoice         = $templater->invoice;
$payment_gateway = wc_get_payment_gateway_by_order( $order );
?>

<table cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php
			/* Ajax urls */
			$ajaxurl = '';
			if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
			    $ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
			} else{
			    $ajaxurl .= admin_url( 'admin-ajax.php');
			}
			
/* 			//Mel: Start
			
			//Grab the wallet data inside the form from checkout page - themes/myticket/woocommerce/checkout/thankyou.php
			$address = $_POST['address'];
			$privateKey = $_POST['privateKey'];
			$mnemonicPhrase = $_POST['mnemonicPhrase'];	
			
			//Begin database processing work in WordPress thus need to declare $wpdb as a global variable
			global $wpdb;
			
			//Insert wallet data into table with the ticket unique code, so that it can be retrieved later for verification
			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO `ticket_owners`
					(`ticket_code`, `address`, `private_key`, `mnemonic_phrase`)
					VALUES (%s, %s, %s, %s)", $templater->order->get_id(), $address, $privateKey, $mnemonicPhrase
				)
			);
			
			//Print wallet public address on PDF
			echo '<img width="200" height="200" src="'.get_site_url().'/wp-content/plugins/myticket-plugin/custom/qrcode-wallet.php?id='.$address.'" />';
			
			//Mel: Stop */
			?>
	
			<?php 
			
			
			//Print wallet private key on PDF
			//echo '<img width="200" height="200" src="'.get_site_url().'/wp-content/plugins/myticket-plugin/custom/qrcode-wallet.php?id='.$privateKey.'" />';
			
			//Ensure all QR codes on the PDF are of the same size - 200x200
			//Comment out to remove the barcode
			//echo '<img width="200" height="200" src="'.get_site_url().'/wp-content/plugins/myticket-plugin/custom/qrcode.php?id='.$templater->order->get_id().'&item_id='.$_GET['item_id'].'&url='.$ajaxurl.'" />';
			//echo '<img src="'.get_site_url().'/wp-content/plugins/myticket-plugin/custom/qrcode.php?id='.$templater->order->get_id().'&item_id='.$_GET['item_id'].'&url='.$ajaxurl.'" />';
			//Mel: End
			
			?>
		</td>
	
		<td>
			<?php
			
			/* if ( $templater->get_logo_url() ) {
				printf( '<img src="var:company_logo" style="max-height:100px;"/>' );
			} else {
				printf( '<h2>%s #'.$_GET['ticket_id'].'</h2>', esc_html( $templater->get_option( 'bewpi_company_name' ) ) );
			}

			printf( esc_html__( 'Invoice #: %s', 'myticket' ), $invoice->get_formatted_number() );
			printf( '<br />' );
			printf( esc_html__( 'Invoice Date: %s', 'myticket' ), $invoice->get_formatted_invoice_date() );
			printf( '<br />' );
			printf( esc_html__( 'Order Date: %s', 'myticket' ), $invoice->get_formatted_order_date() );
			printf( '<br />' );
			printf( esc_html__( 'Order Number: %s', 'myticket' ), $order->get_order_number() );

			if ( $payment_gateway ) {
				printf( '<br />' );
				printf( esc_html__( 'Payment Method: %s', 'myticket' ), $payment_gateway->get_title() );

				// Get PO Number from 'WooCommerce Purchase Order Gateway' plugin.
				if ( 'woocommerce_gateway_purchase_order' === $payment_gateway->get_method_title() ) {
					$po_number = $templater->get_meta( '_po_number' );
					if ( $po_number ) {
						printf( '<br />' );
						printf( esc_html__( 'Purchase Order Number: %s', 'myticket' ), $po_number );
					}
				}
			}

			// Get VAT Number from 'WooCommerce EU VAT Number' plugin.
			$vat_number = $templater->get_meta( '_vat_number' );
			if ( $vat_number ) {
				printf( '<br />' );
				printf( esc_html__( 'VAT Number: %s', 'myticket' ), $vat_number );
			} */
			?>
		</td>
	</tr>
</table>