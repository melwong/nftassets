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
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<script src="<?php echo get_template_directory_uri(); ?>/js/web3.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-3.4.1.min.js"></script>


<?php 
global $product;

$token_array = $_POST['tokenIds'];

$product_id = $product->get_id();
$product = new WC_Product($product_id);
$contract_address = get_post_meta($product_id, 'smart_contract_address', true);
$abi = get_post_meta($product_id, 'abi', true);

echo $token_array;
echo "<br />";

$tokens = explode(',', $token_array);

if (count($tokens) > 1 ) {
	foreach ($tokens as $token) {
		do_action('onekrowd_get_ticket_url', $product_id, $token);
		echo "product_id: " . $product_id;
		echo "<br />";
		echo "token id: " . $token;
		echo "<br />";
	}
}

/* echo "Seating: " . $product->get_attribute( 'Seating' );
echo "<br >";
echo "TokenID: " . $product->get_attribute( 'TokenID' );
echo "<br >";
echo "product id: " . $product_id;
echo "<br >";
echo "contract: " . $contract_address;
echo "<br >";
echo "abi: " . $abi;
echo "<br />"; */

/* $pa_koostis_value = get_post_meta($product->id, '_sku', true);

echo "pa_koostis_value: " . $pa_koostis_value;
echo "This works. Product: " . $product->get_attribute( '_sku' ); */


?>

<form action="" method="post" id="form1">
	<input type="hidden" id="tokenIds" name="tokenIds" value="" />
	<button class="transferToken"><?php _e('Send Ticket Token', 'dc-woocommerce-multi-vendor'); ?></button>
</form>

<script>
	
var walletAddress = '';
var tokenId = 0;
var contractAddress = '';
var abi = '';

contractAddress = '<?php echo $contract_address; ?>';
abi = <?php echo $abi; ?>;

window.addEventListener('load', function() {
	// Check if Web3 has been injected by the browser:
	if (typeof web3 !== 'undefined') {
		// You have a web3 browser! Continue below!
		web3 = new Web3(web3.currentProvider);
		
		//var version = web3.version;
		//console.log("Using web3js version " + version );
		
		//This is another way to retrieve the current wallet on MetaMask
		/*var accounts = web3.eth.getAccounts(function(error, result) {
			if (error) {
				console.log(error);
			} else {
				console.log(result + " is current account");
			}       
		});*/
		
		//Get wallet info in the form of Javascript object
		account = web3.eth.accounts;
		
		//Get the current MetaMask selected/active wallet
		walletAddress = account.givenProvider.selectedAddress;
		
		//Check if Metamask is locked
		if (!empty(walletAddress)){
			
			//Detect if the user changes the account on MetaMask
			window.ethereum.on('accountsChanged', function (accounts) {
				console.log("MetaMask account change. Reloading...");
				window.location.reload(); 
			})
		
			console.log(walletAddress + " is the current wallet address");
			
			//If not locked, continue to run the app
			startApp(web3);

		  } else {
		  
			//If locked, alert user to unlock their MetaMask
			if (alert("<?php _e('Your MetaMask is locked. Please unlock with your password and then press OK below.', 'dc-woocommerce-multi-vendor'); ?>")){
			} else {
				window.location.reload(); 
			}
		  }
		
	} else {
	
		if (alert("<?php _e('No MetaMask plugin detected. Please install MetaMask digital wallet at www.metamask.io', 'dc-woocommerce-multi-vendor'); ?>")){
		} else {
			window.location.reload(); 
		}
	
	}
});



function startApp(web3) {

	smartContract = new web3.eth.Contract(abi, contractAddress);
	
	retrieveTokenId(smartContract, walletAddress).then(function(result) {
		
		if (result.length != 0) {
			
			document.getElementById('tokenIds').value = result;
			
		} else {
			
			if (alert("<?php _e('Your wallet does not contain any ticket token for this event. Please ensure you use the correct account', 'dc-woocommerce-multi-vendor'); ?>")){
			} else {
				//window.location.reload(); 
			}
			
		}
	
	});
	
}

/* function listenForClicks (miniToken, web3, tokenArray) {

    var button = document.querySelector('button.transferToken');
	
    button.addEventListener('click', function() {

		

    });
} */

async function retrieveTokenId(contract, walletAddress) {
	
	const tokenIdArray = await contract.methods.myTokens().call({
		from: walletAddress
	});
	
	//Check if there's token in the wallet
	/*if (tokenIdArray.length != 0) {
		
		for (i=0; i < tokenIdArray.length; i++) {
			//console.log(tokenIdArray[i]);
		}
		
	} else {
			
			
	}*/
	
	return tokenIdArray;

}

//Send token Id array back to the page in order to generate the ticket download URL
/* function sendTokenId(tokenIdArray){
	var data = {
		tokenArray: tokenIdArray,
	};
	
	//console.log("TOKEN ARRAY: " + JSON.stringify(tokenIdArray));
	
	//Use Post method to post the token array back to the page
	$.post(get_permalink(), data);
} */

//To check if an object (or something) is empty
function empty(n){
	return !(!!n ? typeof n === 'object' ? Array.isArray(n) ? !!n.length : !!Object.keys(n).length : true : false);
}


</script>
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
