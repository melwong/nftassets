<?php
/**
 * PDF invoice template body.
 *
 * This template can be overridden by copying it to youruploadsfolder/myticket/templates/invoice/simple/yourtemplatename/body.php.
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

$templater                      = WPI()->templater();
$order                          = $templater->order;
$invoice                        = $templater->invoice;
$formatted_shipping_address     = $order->get_formatted_shipping_address();
$formatted_billing_address      = $order->get_formatted_billing_address();
$line_items                     = $order->get_items( 'line_item' );
$color                          = $templater->get_option( 'bewpi_color_theme' );
$terms                          = $templater->get_option( 'bewpi_terms' );
$_GET['item_id']				= (isset($_GET['item_id'])?$_GET['item_id']:"");
?>

<?php

//Grab the wallet data inside the form from themes/myticket/woocommerce/checkout/thankyou.php
$ticket_qr = $_POST['ticketQr'] ;
$address = esc_attr( $_POST['address'] );
$private_key = esc_attr( $_POST['privateKey'] );
$mnemonic_phrase = esc_attr( $_POST['mnemonicPhrase'] );
$first_name = esc_attr( $_POST['firstName'] );
$last_name = esc_attr( $_POST['lastName'] );	

?>

<div class="title">

	<div class="watermark">
		<h2 class="rubber-stamp"><?php echo esc_html__( 'Your Ticket', 'myticket' ); ?></h2>
	</div>
	
</div>
<br />
<br />

<?php

foreach ( $line_items as $item_id => $item ) {

if ($_GET['item_id'] == $item_id || $_GET['item_id'] == "") {
?>

<?php
			
			$product_id = $item->get_product_id();
			
			$product = $item->get_product();
			
			//If the event is not a General Admission event and has seat numbers, display the seat number on the ticket. Seating is stored as a variation of the product
			if ( $product->is_type('variation') ) {
				$variation_id = wc_get_order_item_meta( $item_id, '_variation_id', true );	//Each seat numbering is represented as variation id 
				$variation_value = get_the_excerpt($variation_id);	//Seat numbering is stored in posts table under post_excerpt column
			}

			if ( has_post_thumbnail( $product_id ) ) {
				$attachment_ids[0] = get_post_thumbnail_id( $product_id );
				$attachment = wp_get_attachment_image_src($attachment_ids[0], 'thumbnail' ); 
									
			}
			
			//Get event descriptions
			$product_instance = wc_get_product($product_id);
			$product_full_description = $product_instance->get_description();
			$product_short_description = $product_instance->get_short_description();
		
?>
<div class="product-img" style="background-image: url('<?php echo $attachment[0] ; ?>');"></div>

<table cellpadding="0" cellspacing="0" class="new" style="background-image: url('<?php do_action('get_upload_dir', 'baseurl', '/2019/07/milky-way-1024x575.jpg'); ?>');">

<!--
<table cellpadding="0" cellspacing="0" class="new" style="background-image: url('http://localhost/1krowd1st/wp-content/themes/myticket/woocommerce-pdf-invoices/templates/invoice/simple/minimal/87399_low.jpg');">
-->
	<tr>
		<td>
			<div class="background-img" style="background-image: url('<?php echo $inputPath ; ?>');"></div>
			<?php //echo esc_html__( 'WALLET ADDRESS', 'myticket' ); ?> 
		</td>
	</tr>
	<tr>
		
		<td class="td-space">
		</td>
		<td>
			<table cellpadding="0" cellspacing="0">
				<thead>
				</thead>
				<tbody>
					<?php
					//foreach ( $line_items as $item_id => $item ) {

					//if ($_GET['item_id'] == $item_id || $_GET['item_id'] == "") {
					?>
						<tr>
							<td class="ticket">
								<h1 class="event"><?php echo esc_attr( $item['name'] ); ?></h3>
							</td>
						</tr>
						<tr>
							<td class="ticket">
								<h3 class="address"><?php echo esc_attr( $item['address'] ); ?></h1>
							</td>
						</tr>
						<tr>	
							<td class="ticket">
								<h3 class="address"><?php echo esc_attr( $item['venue'] ); ?></h3>
							</td>
						</tr>
						<tr>
							<td class="ticket">
								<h6 class="time"><?php echo esc_attr( $item['time'] ); ?></h6>
							</td>								
						</tr>
						<tr>	
							<td>
								<h1></h1>
							</td>
						</tr>
						<tr>	
							<td>
								<h1></h1>
							</td>
						</tr>						
						<tr>
							<td class="button">
								<h6 class="time"><?php echo $variation_value; //Display seat number ?></h6>
							</td>
						</tr>
						<tr>	
							<td>
								<h1></h1>
							</td>
						</tr>	
					<?php //} ?>
					<?php //} ?>
				</tbody>
			</table>	
		</td>
		<td>
			<?php
			//Mel
			//Print wallet private key on PDF
			echo '<img width="230" height="230" src="'.get_site_url().'/wp-content/plugins/myticket-plugin/custom/qrcode-wallet.php?id='.$ticket_qr.'" />';
			//echo nl2br( $templater->get_option( 'bewpi_company_address' ) );
			?>
		</td>
		<td>
		</td>
	</tr>
	<tr>
		<td class="wallet-label">
		</td>
	</tr>
</table>

<div class="qr">
	<?php echo esc_html__( 'Issued To:', 'myticket' ) . ' ' . $first_name . ' ' . $last_name; ?>
	<br />
	<?php echo '<img width="230" height="230" src="'.get_site_url().'/wp-content/plugins/myticket-plugin/custom/qrcode-wallet.php?id='.$ticket_qr.'" />'; ?>
</div>

<?php } ?>
<?php } ?>
<br />
<br />
<br />
<hr />
<br />
<br />
<table cellpadding="0" cellspacing="0">
	<thead>
		<tr class="heading" bgcolor="<?php echo esc_attr( $color ); ?>;">
			<th>
				<?php echo esc_html__( 'Product', 'myticket' ); ?>
			</th>

			<th>
				<?php echo esc_html__( 'Qty', 'myticket' ); ?>
			</th>

			<?php do_action( 'bewpi_line_item_headers_after_quantity', $invoice ); ?>

			<th>
				<?php echo esc_html__( 'Price', 'myticket' ); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ( $line_items as $item_id => $item ) {

	if($_GET['item_id'] == $item_id || $_GET['item_id'] == ""){
		?>
		<tr class="item">
			<td width="50%">
				<strong><?php echo esc_attr( $item['name'] ); ?></strong>
				<br />
				<?php
				printf( esc_html__( 'Order Date: %s', 'myticket' ), $invoice->get_formatted_order_date() );
				printf( '<br />' );
				printf( esc_html__( 'Order Number: %s', 'myticket' ), $order->get_order_number() );
				
				//Mel: 06/07/19. To remove the lines containing "Sold By" and ticket download link
				/*do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );

				$templater->wc_display_item_meta( $item, true );
				$templater->wc_display_item_downloads( $item, true );

				do_action( 'woocommerce_order_item_metaesc_html__nd', $item_id, $item, $order ); */
				
				?>
			</td>

			<td>
				<?php echo esc_attr( $item['qty'] ); ?>
			</td>

			<?php do_action( 'bewpi_line_item_after_quantity', $item_id, $item, $invoice ); ?>

			<td>
				<?php echo myticket_output_html( $order->get_formatted_line_subtotal( $item ) ); ?>
			</td>
		</tr>
	<?php } ?>
	<?php } ?>

	<tr class="spacer">
		<td></td>
	</tr>

	<?php if ( $_GET['item_id'] == "" ){ 

	foreach ( $invoice->get_order_item_totals() as $key => $total ) {
		$class = str_replace( '_', '-', $key );
		?>

		<tr class="total">
			<td></td>
			<td class="border <?php echo esc_attr( $class ); ?>" colspan="<?php echo esc_attr( $templater->invoice->colspan ); ?>"><?php echo esc_attr( $total['label'] ); ?></td>
			<td class="border <?php echo esc_attr( $class ); ?>"><?php echo myticket_output_html( $total['value'] ); ?></td>
		</tr>

	<?php } } ?>
	</tbody>
</table>
<br />

<table>
	<tr>
		<td>
			<?php echo $product_full_description; ?>
		</td>
	</tr>
</table>
<br />

<!--Hide the blockchain wallet table if it is not used
<table cellpadding="0" cellspacing="0" class="wallet">
	<tr>
		<td class="wallet-label">
			<?php //echo esc_html__( 'WALLET ADDRESS', 'myticket' ); ?> 
		</td>
		<td>
		</td>
		<td class="wallet-label">
			<?php //echo esc_html__( 'PRIVATE KEY', 'myticket' ); ?> 
		</td>
	</tr>
	<tr>
		<td>
			<?php 
			
			//Mel
			//Print wallet public address on PDF
			//echo '<img width="200" height="200" src="'.get_site_url().'/wp-content/plugins/myticket-plugin/custom/qrcode-wallet.php?id='.$address.'" />';
			?>
		</td>
			<td>
				<h2><?php //echo esc_html__( 'This is Your Virtual Ticket. Protect It', 'myticket' ); ?></h2>
				<?php //echo esc_html__( 'This virtual ticket consists of a token stored inside a digital wallet in a secured blockchain. This ticket allows you to access the actual ticket once it is ready before the date of the event. Treat this ticket like money. Do not let anyone scan the QR codes or copy the private key without your permission. To learn more, visit https://1krowd.com/faq', 'myticket' ); ?>
			</td>
		<td>
			<?php
			//Mel
			//Print wallet private key on PDF
			//echo '<img width="200" height="200" src="'.get_site_url().'/wp-content/plugins/myticket-plugin/custom/qrcode-wallet.php?id='.$private_key.'" />';
			//echo nl2br( $templater->get_option( 'bewpi_company_address' ) );
			?>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="wallet-info">
		<?php //printf( esc_html__( 'Wallet Address: %s', 'myticket' ), $address); ?>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="wallet-info">
		<?php //printf( esc_html__( 'Private Key: %s', 'myticket' ), $private_key); ?>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="wallet-info">
		<?php //printf( esc_html__( 'Mnemonic Phrase: %s', 'myticket' ), $mnemonic_phrase); ?>
		</td>
	</tr>
</table>
<br />
-->

<table class="notes" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php
			// Customer notes.
			if ( $templater->get_option( 'bewpi_show_customer_notes' ) ) {
				// Note added by customer.
				$customer_note = BEWPI_WC_Order_Compatibility::get_customer_note( $order );
				if ( $customer_note ) {
					printf( '<strong>' . esc_html( 'Note from customer: %s', 'myticket' ) . '</strong><br />', nl2br( $customer_note ) );
				}

				// Notes added by administrator on 'Edit Order' page.
				foreach ( $order->get_customer_order_notes() as $custom_order_note ) {
					printf( '<strong>' . esc_html( 'Note to customer: %s', 'myticket' ) . '</strong><br />', nl2br( $custom_order_note->comment_content ) );
				}
			}
			?>
		</td>
	</tr>

	<tr>
		<td>
			<?php
			// Zero Rated VAT message.
			if ( 'true' === $templater->get_meta( '_vat_number_is_valid' ) && count( $order->get_tax_totals() ) === 0 ) {
				echo esc_html__( 'Zero rated for VAT as customer has supplied EU VAT number', 'myticket' );
				printf( '<br />' );
			}
			?>
		</td>
	</tr>
</table>

<?php if ( $terms ) { ?>
	<div class="terms">
		<table>
			<tr>
				<td style="border: 1px solid #000;">
					<?php echo nl2br( $terms ); ?>
				</td>
			</tr>
		</table>
	</div>
<?php }
