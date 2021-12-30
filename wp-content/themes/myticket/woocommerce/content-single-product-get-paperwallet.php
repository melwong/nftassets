<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		Melvin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<script src="<?php echo get_template_directory_uri(); ?>/js/web3.min.js"></script>

<?php 
	global $product;
	
	$key_err = '';
	$private_key = '';
	$token_array = '';
	$wallet_address = '';
	$product_id = 0;
	$contract_address = '';
	$abi = '';

	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$user_id = $user->ID;
		$user_email = $user->user_email;
	} else {
		//Redirect visitor to login page
		auth_redirect();
	}
	
	$product_id = $product->get_id();
	
	if ( $product->is_type('variable') ) {
		
/* 		echo "Product ID: " . $product_id;
		echo "<br />";
		echo "This is a variable product";
		echo "<br />"; */
		
		//$product = new WC_Product_Variable( $product_id );
		$variations = $product->get_available_variations();
		
		//foreach ( $variations as $variation ) {

			// get variation ID
			//$variation_id = $variation['variation_id'];
			
			//echo "The variation id: " . $variation_id;

			// get variations meta
			//$product_variation = new WC_Product_Variation( $variation_id );

			// get variation featured image
			//$variation_image = $product_variation->get_image();

			// get variation price
			//$variation_price = $product_variation->get_price_html();

			//get_post_meta( $variation_id , '_text_field_date_expire', true );
			
			$contract_address = get_post_meta($product_id, 'smart_contract_address', true);
			
			$abi = get_post_meta($product_id, 'abi', true);	
			
			//$product_id = $variation['variation_id'];

		//}
		
		//foreach ($variations as $variation) {
			
			//$contract_address = get_post_meta($product_id, 'smart_contract_address', true);
			//$abi = get_post_meta($product_id, 'abi', true);	
			//$product_id = $variation['variation_id'];
		//}
	
	} else {
		
		$product_id = $product->get_id();
		$contract_address = get_post_meta($product_id, 'smart_contract_address', true);
		$abi = get_post_meta($product_id, 'abi', true);	
	
	}
	
/* 	echo "The variation id: " . $variation_id;
	echo "<br />";
	echo "The contract address: " . $contract_address;
	echo "<br />";
	//echo "The abi: " . $abi;
	echo "<br />"; */
?>

	<section class="section-page-header">
        <div class="container">
            <h1 class="entry-title"><?php esc_html_e('Retrieve Tickets','myticket');?></h1>
        </div>
    </section>

<?php
	if ( !empty($_POST['token_ids']) && !empty($_POST['wallet_address']) && !empty($_POST['key']) ) {
		
		$ticket_count = 0;
		$token_array = clean_input($_POST['token_ids']);
		$wallet_address = clean_input($_POST['wallet_address']);
		$private_key = clean_input($_POST['key']);

/* 		echo "Product ID: " . $product_id;
		echo "<br >";
		echo "User ID: " . $user_id;
		echo "<br >";
		echo "User Email: " . $user_email;
		echo "<br >";
		echo "wallet_address: " . $wallet_address;
		echo "<br >";
		echo "private_key: " . $private_key;
		echo "<br >";
		echo "token_array: " . $token_array;
		echo "<br >";
		echo "contract_address: " . $contract_address;
		echo "<br >"; */

		$tokens = explode(',', $token_array);

		if ( count($tokens) > 1 ) {
			foreach ( $tokens as $token ) {
				$ticket_count++;
				do_action('onekrowd_get_ticket_url', $ticket_count, $product_id, $token, $user_email, $wallet_address, $private_key);
			}
		} elseif ( count($tokens) == 1 ) {
			foreach ( $tokens as $token ) {
				do_action('onekrowd_get_ticket_url', $ticket_count, $product_id, $token, $user_email, $wallet_address, $private_key);	
			}
		}
		
	} else if ( !empty($_POST["key"]) ) {
		
		$private_key = clean_input($_POST["key"]);
		
/* 		echo "Priv key is not empty";
		echo "<br >";
		echo "Product ID: " . $product_id;
		echo "<br >";
		echo "User ID: " . $user_id;
		echo "<br >";
		echo "User Email: " . $user_email;
		echo "<br >";
		echo "wallet_address: " . $wallet_address;
		echo "<br >";
		echo "private_key: " . $private_key;
		echo "<br >";
		echo "token_array: " . $token_array;
		echo "<br >";
		echo "contract_address: " . $contract_address;
		echo "<br >"; */
?>
	
		<section class="section-page-content">
			<div class="container">
				<div class="row">                   
					<div id="primary" class="col-sm-12 col-md-12">
						<div class="section-download-ticket">
							<p><?php esc_html_e('Click on the button below to download your ticket','myticket');?></p>
							
							<form action="" method="post" id="form1">
								<input type="hidden" id="token_ids" name="token_ids" value="" />
								<input type="hidden" id="wallet_address" name="wallet_address" value="" />
								<input type="hidden" id="key" name="key" value="" />
								<button id="paperWalletFormButton" class="downloadTicket"><?php esc_html_e('Download Your Ticket','myticket');?></button>
							</form>
							<br /><br /><br/><br /><br /><br/>		

						</div>
					</div>
				</div>
			</div>
		</section>
		
		<!--To enable the overlay that covers the background when the alert pop-up appears.-->
		<div class="overlay"></div>
		
		<script>
	
			var walletAddress = '';
			var privateKey = '';
			var tokenId = 0;
			var contractAddress = '';
			var abi = '';

			contractAddress = '<?php echo $contract_address; ?>';
			abi = <?php echo $abi; ?>;
			privateKey = '0x' + '<?php echo $private_key; ?>';

			web3 = new Web3(new Web3.providers.HttpProvider("https://ropsten.infura.io/v3/1d31dab8c4aa43698aa98f111d870fde"));
			
			try {
			  	//Get account object which provides the address of the wallet
				account = web3.eth.accounts.privateKeyToAccount(privateKey);
				
			} catch ( err ) {
				
				//Display the background cover
				toggleOverlay(true);
				
				// Kudos to Kobe for pointing out that the alert is triggered before the repaint
				// simple way to solve the problem is a timeout, which will
				// make the browser paint the changes before the alert is triggered
				setTimeout(function() {
					
					//If the private key that was entered has a wrong format, show error message
					if ( alert("<?php esc_html_e('Wrong private key format', 'myticket'); ?>") ) {
						} else {
							//Return to previous page which is the form
							window.history.go(-1);
						} 
					toggleOverlay(false);
						
				}, 0);

			}

			//Get the account wallet address
			walletAddress = account.address;

			startApp(web3);
					

			function startApp(web3) {

				smartContract = new web3.eth.Contract(abi, contractAddress);
				
				retrieveTokenId(smartContract, walletAddress).then(function(result) {
					
					if (result.length != 0) {
						
						document.getElementById('token_ids').value = result;
						document.getElementById('wallet_address').value = walletAddress;
						document.getElementById('key').value = privateKey;
						
					} else {
						
						//Display the background cover
						toggleOverlay(true);

						setTimeout(function() {
							
							if (alert("<?php esc_html_e('Your wallet does not contain any ticket token for this event', 'myticket'); ?>")){
									
								} else {
									//Return to previous page which is the form
									window.history.go(-1);
								}
							toggleOverlay(false);
								
						}, 0);
									
					}
				
				});
				
			}

			async function retrieveTokenId(contract, walletAddress) {
				
				const tokenIdArray = await contract.methods.myTokens().call({
					from: walletAddress
				});
				
				console.log(tokenIdArray);
				
				return tokenIdArray;

			}

			//To check if an object (or something) is empty
			function empty(n){
				return !(!!n ? typeof n === 'object' ? Array.isArray(n) ? !!n.length : !!Object.keys(n).length : true : false);
			}
			
			//To toggle the appearance of the background overlay that covers the screen when alert box appears
			function toggleOverlay(show) {
				document.querySelector('.overlay').style.display = (show === true) ? 'block' : 'none';
			}

		</script>
		
		
		
<?php
	} else if ( empty($_POST["key"]) ) {
	
		$key_err = __('Wallet private key is required', 'myticket') ;
?>
		 
		<section class="section-page-content">
			<div class="container">
				<div class="row">                   
					<div id="primary" class="col-sm-12 col-md-12">
						<div class="section-download-ticket">
							
							 <form method="post" action="<?php echo htmlspecialchars(get_permalink()) . '?get=paperwallet'; ?>">
								<div class="form-spacing">
								<label><?php esc_html_e('Enter Your Wallet Private Key', 'myticket'); ?></label>
								<br />
								<span class="error"><?php echo $key_err; ?></span>
								<br />
								<input type="text" size="69" name="key" placeholder="e.g: f6b896131706c40f0245ea95d7877c9d174b4a9129b6c990ca99cd3774205ca6" required>
								<br />
								<input type="submit" id="paperWalletFormButton" value="<?php esc_html_e('Retrieve Tickets', 'myticket'); ?>">
								</div>
							 </form>		

						</div>
					</div>
				</div>
			</div>
		</section>
		 
<?php
	}
?>

<!--Mel: End-->


<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		//do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			 
			 //Mel
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
			
			//do_action( 'woocommerce_single_product_summary' );
		?>

	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		//do_action( 'woocommerce_after_single_product_summary' );
	
	?>

	<!--Mel
	<meta itemprop="url" content="<?php the_permalink(); ?>" />
	-->
	
</div><!-- #product-<?php the_ID(); ?> -->

<?php //do_action( 'woocommerce_after_single_product' ); ?>
