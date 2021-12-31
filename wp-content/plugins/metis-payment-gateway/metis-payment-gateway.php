<?php
/*
 * Plugin Name: Metis WooCommerce Payment Gateway
 * Description: Accept MetisDAO token as payments on your woocommerce store.
 * Author: Melvin Wong
 * Author URI: 
 * Version: 1.0
 */
 
//Check if WooCommerce plugin is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

add_filter( 'woocommerce_payment_gateways', 'add_custom_gateway_class' );
function add_custom_gateway_class( $gateways ) {
    $gateways[] = 'WC_Metis_Gateway'; // payment gateway class name
    return $gateways;
}

add_action( 'plugins_loaded', 'initialize_gateway_class' );
function initialize_gateway_class() {
	
	class WC_Metis_Gateway extends WC_Payment_Gateway {
		
		public function __construct() {

			$this->id = 'metis'; // payment gateway ID
			$this->icon = ''; // payment gateway icon
			$this->has_fields = true; // for custom credit card form
			$this->title = __( 'MetisDAO Gateway', 'text-domain' ); // vertical tab title
			$this->method_title = __( 'MetisDAO Gateway', 'text-domain' ); // payment method name
			$this->method_description = __( 'Custom MetisDAO payment gateway', 'text-domain' ); // payment method description

			$this->supports = array( 'default_credit_card_form' );

			// load backend options fields
			$this->init_form_fields();

			// load the settings.
			$this->init_settings();
			$this->title = $this->get_option( 'title' );
			$this->description = $this->get_option( 'description' );
			$this->enabled = $this->get_option( 'enabled' );
			$this->test_mode = 'yes' === $this->get_option( 'test_mode' );
			$this->private_key = $this->test_mode ? $this->get_option( 'test_private_key' ) : $this->get_option( 'private_key' );
			$this->publish_key = $this->test_mode ? $this->get_option( 'test_publish_key' ) : $this->get_option( 'publish_key' );

			// Action hook to saves the settings
			if(is_admin()) {
				  add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			}

			// Action hook to load custom JavaScript
			add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
			
		}

		public function init_form_fields(){

			$this->form_fields = array(
				'enabled' => array(
					'title'       => __( 'Enable/Disable', 'text-domain' ),
					'label'       => __( 'Enable MetisDAO Gateway', 'text-domain' ),
					'type'        => 'checkbox',
					'description' => __( 'This enable the MetisDAO gateway which allow to accept payment using Metis tokens.', 'text-domain' ),
					'default'     => 'no',
					'desc_tip'    => true
				),
				'title' => array(
					'title'       => __( 'Title', 'text-domain'),
					'type'        => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'text-domain' ),
					'default'     => __( 'MetisDAO', 'text-domain' ),
					'desc_tip'    => true,
				),
				'description' => array(
					'title'       => __( 'Description', 'text-domain' ),
					'type'        => 'textarea',
					'description' => __( 'This controls the description which the user sees during checkout.', 'text-domain' ),
					'default'     => __( 'Pay with your Metis tokens via our super-cool payment gateway.', 'text-domain' ),
				),
				'test_mode' => array(
					'title'       => __( 'Test mode', 'text-domain' ),
					'label'       => __( 'Enable Test Mode', 'text-domain' ),
					'type'        => 'checkbox',
					'description' => __( 'Place the payment gateway in test mode using test API keys.', 'text-domain' ),
					'default'     => 'yes',
					'desc_tip'    => true,
				),
				'test_publish_key' => array(
					'title'       => __( 'Test Publish Key', 'text-domain' ),
					'type'        => 'text'
				),
				'test_private_key' => array(
					'title'       => __( 'Test Private Key', 'text-domain' ),
					'type'        => 'password',
				),
				'publish_key' => array(
					'title'       => __( 'Live Publish Key', 'text-domain' ),
					'type'        => 'text'
				),
				'private_key' => array(
					'title'       => __( 'Live Private Key', 'text-domain' ),
					'type'        => 'password'
				)
			);
		}
		
		public function payment_fields() {

			if ( $this->description ) {
				if ( $this->test_mode ) {
					$this->description .= '';
				}
				echo wpautop( wp_kses_post( $this->description ) );
			}
			
			?>
			
			<fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">

				<?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>

				<div class="form-row form-row-wide">
					<!--<label>Connect Wallet</label>
					<button id="sendToken" name="sendToken" value="Pay Via Metamask">-->
					<!--<label>Card Number <span class="required">*</span></label>
					<input id="ybc_ccno" type="text" autocomplete="off">-->
				</div>
				<!--<div class="form-row form-row-first">
					<label>Expiry Date <span class="required">*</span></label>
					<input id="ybc_expdate" type="text" autocomplete="off" placeholder="MM / YY">
				</div>
				<div class="form-row form-row-last">
					<label>Card Code <span class="required">*</span></label>
					<input id="ybc_cvc" type="password" autocomplete="off" placeholder="CVC">
				</div>-->
				<div class="clear"></div>

				<?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>

				<div class="clear"></div>

			</fieldset>

			<?php
		 
		}
		
		public function payment_scripts() {

			// process a token only on cart/checkout pages
			if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
				return;
			}

			// stop enqueue JS if payment gateway is disabled
			if ( 'no' === $this->enabled ) {
				return;
			}

			// stop enqueue JS if API keys are not set
			/* if ( empty( $this->private_key ) || empty( $this->publishable_key ) ) {
				return;
			} */

			// stop enqueue JS if test mode is enabled
/* 			if ( ! $this->test_mode ) {
				return;
			} */

			// stop enqueue JS if site without SSL
/* 			if ( ! is_ssl() ) {
				return;
			} */

			// payment processor JS that allows to get a token
			//wp_enqueue_script( 'metis_js', 'https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js' );

			// custom JS that works with get-token.js
			//wp_register_script( 'woocommerce_pay_metis', plugins_url( 'metamask.js', __FILE__ ), array( 'jquery', 'metis_js' ) );

			// use public key to get token
/* 			wp_localize_script( 'woocommerce_pay_metis', 'metis_params', array(
				'publishKey' => $this->publish_key
			) ); */

			//wp_enqueue_script( 'woocommerce_pay_metis' );
			
			//wp_enqueue_script( 'metis_js', 'https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js' );
			//wp_enqueue_script( 'jquery2', 'https://code.jquery.com/jquery-3.6.0.js' );
			
?>
<!--
<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>-->
<script>
				/*
					jQuery( function($) {     
					  $("form.woocommerce-checkout")
					  .on('submit', function() { 
						
							var web3;
							window.addEventListener('load', function () {
								if (typeof web3 !== 'undefined') {
									console.log('Web3 Detected! ' + web3.currentProvider.constructor.name)
									
									window.web3.currentProvider.enable();
									web3 = new Web3(window.web3.currentProvider);
									
									//window.web3 = new Web3(web3.currentProvider);
									
									//Send ETH To this wallet. Owner of smart contract
									var toAddress = '0x455A58fC32cc8C42f7873ab3214C3f69Ba0D7AB9' 

									var account = web3.eth.accounts;
									
									//Get the current MetaMask selected/active wallet
									walletAddress = account.givenProvider.selectedAddress;

									console.log("Send from: " + walletAddress);
									console.log("Send to: " + toAddress);								
									
									web3.eth.sendTransaction({
										from: walletAddress,
										to: toAddress,
										value: web3.utils.toWei('<?php echo WC()->cart->cart_contents_total; ?>', 'ether')
									}, function (error, result) {
										if (error) {
											console.log(error);
											
										} else {
											window.location.href = document.location.origin + "/checkout/order-received/";

										}
										
									});
									
								} else {
									
								}
							});
						}); 
					}); 
*/
			</script>

<?php

		}
		
		public function validate_fields(){

/* 			if( empty( $_POST[ 'billing_first_name' ]) ) {
				wc_add_notice(  'First name is required!', 'error' );
				return false;
			}
			if( empty( $_POST[ 'billing_email' ]) ) {
				wc_add_notice(  'Email is required!', 'error' );
				return false;
			} */
			return true;
		 
		}
		
		public function process_payment( $order_id ) {

			global $woocommerce;
		 
			// get order detailes
			$order = wc_get_order( $order_id );
			
?>



<?php
		 
			// Array with arguments for API interaction
			/* $args = array(
			
			);
			
			$response = wp_remote_post( '{payment-processor-endpoint}', $args ); */
		 
			//if( !is_wp_error( $response ) ) {
		 
				//$body = json_decode( $response['body'], true );
		 
				// it could be different depending on your payment processor
				//if ( $body['response']['responseCode'] == 'APPROVED' ) {
		 
					// we received the payment
					$order->payment_complete();
					$order->reduce_order_stock();
		 
					// notes to customer
					$order->add_order_note( 'Hey, your order is paid! Thank you!', true );
					$order->add_order_note( 'This private note shows only on order edit page', false );
		 
					// empty cart
					$woocommerce->cart->empty_cart();
		 
					// redirect to the thank you page
					return array(
						'result' => 'success',
						'redirect' => $this->get_return_url( $order )
					);
		 
				//} else {
					//wc_add_notice(  'Please try again.', 'error' );
					//return;
				//}
		 
			//} else {
				//wc_add_notice(  'Connection error.', 'error' );
				//return;
			//}
		 
		}
	
	}
}

?>
