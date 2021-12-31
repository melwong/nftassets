<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_user_logged_in() ) {
	$user_id = get_current_user_id();
} else {
	//Redirect visitor to login page
	auth_redirect();
}

$myticket_thankyou = get_theme_mod('myticket_thankyou', 1);

//Mel:31/12/21
 if ( isset( $_GET['payment'] ) ) {
	$complete = filter_var($_GET['payment'], FILTER_SANITIZE_STRING);
	
	if ($complete == 'done') {
		$myticket_thankyou = 1;
	} 
 }

$meta = get_post_meta( $order->get_id() );
//print_r($meta);
$ticket_name = '';
if ( sizeof( $order->get_items() ) > 0 ) {
    foreach( $order->get_items() as $item ) {
        $_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
        $item_meta    = new WC_Order_Item_Product( $item['item_meta'], $_product );
        $ticket_name = $item['name'].", ";
    }
    $ticket_name = trim($ticket_name," ");
    $ticket_name = trim($ticket_name,",");
}

?>

<?php if ( 1 != $myticket_thankyou ): ?>

    <section class="section-page-header">
        <div class="container">
            <h1 class="entry-title"><?php esc_html_e('Access Your Wallet','myticket'); //Mel: 31/12/21 //esc_html_e('Download Your Ticket','myticket');?></h1>
        </div>
    </section>

    <section class="section-page-content">
        <div class="container">

			<?php
				
				$order->payment_complete();
				
				//Get the wallet data that was given to the user when they registered
				$address = get_user_meta($user_id, 'wallet_address', true);
				$private_key = get_user_meta($user_id, 'private_key', true);
				$mnemonic_phrase = get_user_meta($user_id, 'mnemonic_phrase', true);
				
				//Get user's first and last name
				$first_name = get_user_meta($user_id, 'first_name', true);
				$last_name = get_user_meta($user_id, 'last_name', true);
				
				//DEBUG
				//$url_with_scheme = set_url_scheme( $url, $scheme );
				//$upload_dir = wp_upload_dir();
				//echo "URL: " . set_url_scheme( $upload_dir['baseurl'], 'http' ) . '/2019/07/milky-way-1024x575.jpg';
				//echo "sdfsdfsd: " . do_action('get_upload_dir', 'baseurl', '/2019/07/milky-way-1024x575.jpg');
				
				//To ensure customer downloads ticket separately - one ticket with one QR code. No combined ticket where one QR represent each ticket and each seat
				if ( sizeof( $order->get_items() ) >= 1 && get_theme_mod('myticket_combine','') != 1 ) {
					
			?>
				
	            <div class="row">
					<div class="section-download-ticket">
					
						<p><?php esc_html_e('Proceed to your wallet','myticket'); //Mel: 31/12/21 esc_html_e('Thanks for purchasing','myticket'); ?></p>

						<?php
						
						//Mel: 31/12/21
						//To get the full URL of order received with query strings
						$parts = parse_url(home_url());
						$uri   = $parts['scheme'] . '://' . $parts['host'];

						if (array_key_exists('port', $parts)) {
							$uri .= ':' . $parts['port'];
						}

						$uri .= add_query_arg([]);
						$uri .= '&payment=done'; 
						
						if ($order->get_payment_method() == 'metis' ) {
							
							echo '<p><br /><br /><a id="sendToken" class="button" href="javascript:;">' . esc_html('Connect Wallet','myticket') . '</p></a>';
							echo '<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>';
							echo "<script>
							
								var web3;
								
								var button = document.getElementById('sendToken');
							
								button.addEventListener('click', function (event) {
									
									event.preventDefault();

									if (typeof web3 !== 'undefined') {
										
										console.log('Web3 Detected! ' + web3.currentProvider.constructor.name)
										
										window.web3.currentProvider.enable();
										web3 = new Web3(window.web3.currentProvider);
										
										//Send ETH To this wallet. Owner of smart contract
										var toAddress = '0x455A58fC32cc8C42f7873ab3214C3f69Ba0D7AB9' 

										var account = web3.eth.accounts;
										
										//Get the current MetaMask selected/active wallet
										walletAddress = account.givenProvider.selectedAddress;

										console.log('Send from: ' + walletAddress);
										console.log('Send to: ' + toAddress);								
										
										web3.eth.sendTransaction({
											from: walletAddress,
											to: toAddress,
											value: web3.utils.toWei('" . $order->get_total() . "', 'ether')
										}, function (error, result) {
											if (error) {
												alert('Error. Please read console log');
												console.log(error);
												
											} else {
												window.location.href = '" . $uri . "';

											}
											
										});
										
									} else {
										alert('Please install Metamask Chrome extension wallet or any other compatible wallet');
									}

								});</script>";
						
						} else {
							
							$i = 1;
								
							foreach( $order->get_items() as $item ) {
								
								//To check if the purchased item is NOT a parent product because parent product has a child product. We only display/download tickets for the child product not the parent.
								if ( !isset( $item['product_extras']['products']['child_products'] ) ) {
									
									//Get the ticket QR code
									$ticket_qr = wc_get_order_item_meta( $item->get_id(), '_ticket_qr', true);

									?>
								
									<form method="POST" action="<?php  echo do_shortcode( '[myticket-download-invoice-multi order_id="' . $order->get_id() . '" item_id="' . $item->get_id() . '" ticket_id="' . $i . '" ]' );  ?>">
										<input type="hidden" name="ticketQr" id="ticketQr" value="<?php echo $ticket_qr; ?>" /> 
										<input type="hidden" name="address" id="address" value="<?php echo $address; ?>" />
										<input type="hidden" name="privateKey" id="privateKey" value="<?php echo $private_key; ?>" />
										<input type="hidden" name="mnemonicPhrase" id="mnemonicPhrase" value="<?php echo $mnemonic_phrase; ?>" />
										<input type="hidden" name="firstName" id="firstName" value="<?php echo $first_name; ?>" />
										<input type="hidden" name="lastName" id="lastName" value="<?php echo $last_name; ?>" />
										
										<?php
										
										//Display ticket count if there're more than 1 ticket
										if ( sizeof( $order->get_items() ) == 1 ) {
											printf( '<input class="primary-link" type="submit" id="downloadButton" value="%s" /></form>', esc_html__('Download Your Ticket','myticket') );
												
										} else {
											printf( '<input class="primary-link" type="submit" id="downloadButton" value="%s #%d" /></form>', esc_html__('Download Your Ticket','myticket'), $i );
										}

									$i++;
									
								} 
							}
						
						}
						//Mel: End
						

						?>
						<br /><br />
					</div>
	            </div>

            <?php 
				}
			?>
			
        </div>
    </section>

<?php else: ?>

	<!-- main content starts -->
    <!-- ============== cart page starts ============== -->
	<div class="container">     
        <div class="row block">
            <!--shop table-->
            <div class="col-xs-12 col-sm-10 col-sm-offset-1 shop-list">
			
				<?php $order->payment_complete(); //Mel:31/12/21 ?>

				<?php if ( $order ) : ?>

					<?php if ( $order->has_status( 'failed' ) ) : ?>

						<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'myticket' ); ?></p>

						<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
							<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'myticket' ) ?></a>
							<?php if ( is_user_logged_in() ) : ?>
								<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'myticket' ); ?></a>
							<?php endif; ?>
						</p>

					<?php else : ?>

						<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'myticket' ), $order ); ?></p>

						<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

							<li class="woocommerce-order-overview__order order">
								<?php esc_html_e( 'Order number:', 'myticket' ); ?>
								<strong><?php echo esc_attr($order->get_order_number()); ?></strong>
							</li>

							<li class="woocommerce-order-overview__date date">
								<?php esc_html_e( 'Date:', 'myticket' ); ?>
								<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
							</li>

							<li class="woocommerce-order-overview__total total">
								<?php esc_html_e( 'Total:', 'myticket' ); ?>
								<strong><?php echo ($order->get_formatted_order_total()); ?></strong>
							</li>

							<?php if ( $order->get_payment_method_title() ) : ?>

							<li class="woocommerce-order-overview__payment-method method">
								<?php esc_html_e( 'Payment method:', 'myticket' ); ?>
								<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
							</li>

							<?php endif; ?>

						</ul>

					<?php endif; ?>

					<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
					<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

				<?php else : ?>

					<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'myticket' ), null ); ?></p>

				<?php endif; ?>

			</div>
		</div>
	</div>

<?php endif; ?>
