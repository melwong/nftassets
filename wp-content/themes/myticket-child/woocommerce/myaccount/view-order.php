<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<p><?php
	/* translators: 1: order number 2: order date 3: order status */
	printf(
		__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'myticket' ),
		'<mark class="order-number">' . $order->get_order_number() . '</mark>',
		'<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>',
		'<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>'
	);
?></p>

<?php if ( $notes = $order->get_customer_order_notes() ) : ?>
	<h2><?php _e( 'Order updates', 'myticket' ); ?></h2>
	<ol class="woocommerce-OrderUpdates commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="woocommerce-OrderUpdate comment note">
			<div class="woocommerce-OrderUpdate-inner comment_container">
				<div class="woocommerce-OrderUpdate-text comment-text">
					<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( __( 'l jS \o\f F Y, h:ia', 'myticket' ), strtotime( $note->comment_date ) ); ?></p>
					<div class="woocommerce-OrderUpdate-description description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); ?>
					</div>
	  				<div class="clear"></div>
	  			</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>
<?php do_action( 'woocommerce_view_order', $order_id ); ?>

<?php //echo do_shortcode( '[bewpi-download-invoice class="primary-link" title="Download Ticket" order_id="' . $order_id . '"]' ); ?>

<!--Mel: 09/08/19. To change the ticket download mechanism to be like our thankyou.php-->
<?php

	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
	} else {
		//Redirect visitor to login page
		auth_redirect();
	}
				
	//Get the wallet data that was given to the user when they registered
	$address = get_user_meta($user_id, 'wallet_address', true);
	$private_key = get_user_meta($user_id, 'private_key', true);
	$mnemonic_phrase = get_user_meta($user_id, 'mnemonic_phrase', true);
	
	//Get user's first and last name
	$first_name = get_user_meta($user_id, 'first_name', true);
	$last_name = get_user_meta($user_id, 'last_name', true);
	
	//To ensure customer downloads ticket separately - one ticket with one QR code. No combined ticket where one QR represent each ticket and each seat
	if ( sizeof( $order->get_items() ) >= 1 && get_theme_mod('myticket_combine','') != 1 ) {
		
?>
	<br /><br />
	<div class="row">
		
		<?php
		
		$i=1;
			
		foreach( $order->get_items() as $item ) {	
				
			$ticket_qr = wc_get_order_item_meta( $item->get_id(), '_ticket_qr', true);
			
		?>
			
			<form method="POST" action="<?php echo do_shortcode( '[bewpi-download-ticket-multi order_id="' . $order->get_id() . '" item_id="' . $item->get_id() . '" ticket_id="' . $i . '"]' ); ?>">
				<input type="hidden" name="ticketQr" id="ticketQr" value="<?php echo $ticket_qr; ?>" /> 
				<input type="hidden" name="address" id="address" value="<?php echo $address; ?>" />
				<input type="hidden" name="privateKey" id="privateKey" value="<?php echo $private_key; ?>" />
				<input type="hidden" name="mnemonicPhrase" id="mnemonicPhrase" value="<?php echo $mnemonic_phrase; ?>" />
				<input type="hidden" name="firstName" id="firstName" value="<?php echo $first_name; ?>" />
				<input type="hidden" name="lastName" id="lastName" value="<?php echo $last_name; ?>" />
				<input class="primary-link" type="submit" id="downloadButton" value="<?php echo esc_html__('Download Your Ticket','myticket') . ' #' . esc_attr($i); ?>" />
			</form>
			<br /><br />

		<?php
			$i++;
		}
		?>
	</div>

<?php } ?>
			

