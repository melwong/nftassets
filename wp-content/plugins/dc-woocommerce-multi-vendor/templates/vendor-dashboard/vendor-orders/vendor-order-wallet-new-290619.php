<?php
/**
 * The template for displaying the interface to talk to crypto wallet such as MetaMask. This template is based on wp-content/plugins/dc-woocommerce-multi-vendor/templates/vendor-dashboard/vendor-orders/vendor-order-details.php
 *
 * This template CANNOT be overridden by copying it to yourtheme/dc-product-vendor/vendor-dashboard/vendor-orders/vendor-order-details.php. At least, it's not tested
 *
 * @author 	1Krowd
 * @package 	WCMp/Templates
 * @version   1.0.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly    
    exit;
}
global $woocommerce, $WCMp;
$vendor = get_current_vendor();
$order = wc_get_order($order_id);

if (!$order) {
    ?>
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php _e('Invalid order', 'dc-woocommerce-multi-vendor'); ?>
        </div>
    </div>
    <?php
    return;
}
$vendor_shipping_method = get_wcmp_vendor_order_shipping_method($order->get_id(), $vendor->id);
$vendor_items = get_wcmp_vendor_orders(array('order_id' => $order->get_id(), 'vendor_id' => $vendor->id));
$vendor_order_amount = get_wcmp_vendor_order_amount(array('order_id' => $order->get_id(), 'vendor_id' => $vendor->id));
$subtotal = 0;

//Mel: Begin
/* $items = $order->get_items();

foreach ( $items as $item ) {
    $product_name = $item->get_name();
    $product_id = $item->get_product_id();
}
	
$product = new WC_Product($product_id);

$contract_address = get_post_meta($product_id, 'smart_contract_address', true);
$recipient_address = get_post_meta($order->get_id(), 'wallet_address', true);
$abi = get_post_meta($product_id, 'abi', true); */
//$token_id = $product->get_attribute('TokenID');
//$token_id = get_post_meta($product_id, '_sku', true);
 
/* echo "contract_address: " . $contract_address;
echo "<br >";
echo "recipient_address: " . $recipient_address;
echo "<br >";
echo "abi: " . $abi;
echo "<br />"; */
 
?>

<script src="<?php echo $WCMp->plugin_url; ?>assets/frontend/js/web3.min.js"></script>
<script src="<?php echo $WCMp->plugin_url; ?>lib/jquery/jquery-3.4.1.min.js"></script>

<script>

	//var walletAddress = '';
	var tokenBalance = 0;
	var tokenExist = false;
	//var abi = '';
	
	//abi = <?php echo $abi; ?>;
		
	function startApp(smartContract, web3, tokenId, contractAddress, recipientAddress, walletAddress) {

		//smartContract = new web3.eth.Contract(abi, contractAddress);
		
		//Wait to check if the active wallet address contains the tokenId
		doesTokenIdExist(tokenId, smartContract, walletAddress).then(function(result){

			listenForClicks(walletAddress, recipientAddress, smartContract, web3, tokenId);	
			
		});
		
	}

	function listenForClicks (walletAddress, recipientAddress, miniToken, web3, tokenId) {

		var button = document.querySelector('button.transferToken');
		
		button.addEventListener('click', function() {
			
			if (getTokenExist()){
					miniToken.methods.transferFrom(walletAddress, recipientAddress, tokenId).send({
					from: walletAddress,
				  // if payable, specify value
				  // value: web3js.toWei(value, 'ether')
				}, function(err, transactionHash) {
					if (!err) {
						console.log(transactionHash + " success"); 
						setTimeout(processTransactionHash(transactionHash), 5000);
						waitForTxToBeMined(transactionHash);
					} else {
						console.log(err);
					}
				});
			} else {
				alert("<?php _e('Your wallet does not contain the ticket token with the token ID. Please ensure you use the correct account', 'dc-woocommerce-multi-vendor'); ?>");
			}
			
			//Working
			/*miniToken.methods.transferFrom(senderAddress, recipientAddress, 50).send({
			  from: senderAddress,
			  // if payable, specify value
			  // value: web3js.toWei(value, 'ether')
			}, function(err, transactionHash) {
				if (!err) {
					console.log(transactionHash + " success"); 
					waitForTxToBeMined(transactionHash);
				} else {
					console.log(err);
				}
			});*/
			
			//Working
			/*miniToken.methods.buyTransferToken(1, 'Lady Gaga', 'VIP Tick', recipient_address).send({
			  from: walletAddress,
			  // if payable, specify value
			  // value: web3js.toWei(value, 'ether')
			});*/
		  
		  //Working
		  /*miniToken.methods.balanceOf(walletAddress).call(function(err,res){
				if(!err){
					console.log(res);
					setTokenBalance(res);
				} else {
					console.log(err);
				}
			});*/

		});
	}

	async function retrieveTokenBalance(contract, walletAddress) {

	  await contract.methods.balanceOf(walletAddress).call(function(err,res){
			if(!err){
				console.log("Token balance in wallet: " + res);
				setTokenBalance(res);
			} else {
				console.log(err);
			}
		});
			
	}

	//Checks if the current wallet holder owns the token by checking who is the owner of the tokenId. If the owner of the token matches the current wallet holder, then return true. Otherwise false
	async function doesTokenIdExist(tokenId, contract, walletAddress) {
		
	  await contract.methods.ownerOf(tokenId).call(function(err,res) {
		  
			if (!err){
				
				//console.log("Owner of token with tokenId " + tokenId + " is " + res);
				//console.log("Current wallet address is " + walletAddress);
				
				tokenAddress = res.toLowerCase();
				walletAddress = walletAddress.toLowerCase();
				
				if (tokenAddress.localeCompare(walletAddress) == 0){
					setTokenExist();
				} 
				
			} else {
				console.log(err);
			}
		});
			
	}

	function setTokenExist() {
		tokenExist = true;
	}

	function getTokenExist() {
		return tokenExist;
	}


	function setTokenBalance(balance) {
		tokenBalance = balance;
	}

	function getTokenBalance() {
		return tokenBalance;
	}

	async function waitForTxToBeMined (txHash) {
		let txReceipt;
		
		while (!txReceipt) {
			try {
				txReceipt = await web3.eth.getTransactionReceipt(txHash);
			} catch (err) {
				return indicateFailure(err);
			}
		}
		indicateSuccess(txReceipt);
	}

	function indicateFailure(error){
		alert("<?php _e('Opps! There seems to be an error. Please try to switch your wallet from Ethereum Main Network to Test Network and back. Otherwise, contact us at support@1krowd.com', 'dc-woocommerce-multi-vendor'); ?>");
		console.log(error);
	}

	function indicateSuccess(txReceipt){
		alert("<?php _e('Well done! Your ticket token has been successfully transferred to the buyer\'s wallet', 'dc-woocommerce-multi-vendor'); ?>");
		console.log(txReceipt);
	}


	//To check if an object (or something) is empty
	function empty(n){
		return !(!!n ? typeof n === 'object' ? Array.isArray(n) ? !!n.length : !!Object.keys(n).length : true : false);
	}

	//Currently not being used
	function sleep(ms) {
	  return new Promise(resolve => setTimeout(resolve, ms));
	}

	//Currently not being used
	async function demo() {
	  console.log('Taking a break...');
	  await sleep(2000);
	}

	//To read the transaction hash and send it back to vendor-order-details page to be marked as shipped
	function processTransactionHash(txHash){
		var data = {
			hash: txHash,
			order_id: <?php echo $order_id; ?>,
			tracking_url: "https://ropsten.etherscan.io/tx/" + txHash,
			tracking_id: txHash,
			["wcmp-submit-mark-as-ship"]: "set",
		};
		
		//Post the transaction hash data back to the vendor-order-details.php template page
		$.post("<?php echo esc_url(wcmp_get_vendor_dashboard_endpoint_url('vendor-orders', $order->get_id())); ?>", data);
	}
	
</script>

<!--Mel: End-->

<div class="col-md-12">
    <div class="icon-header">
        <span><i class="wcmp-font ico-order-details-icon"></i></span>
        <h2><?php _e('Send Ticket Token', 'dc-woocommerce-multi-vendor'); ?></h2>
        <h3><?php _e('Deliver the ticket token in your wallet to buyer\'s wallet', 'dc-woocommerce-multi-vendor'); ?></h3>
    </div>
    <div class="row">
        <div>
            <div class="panel panel-default pannel-outer-heading mt-0">
                <div class="panel-heading"><h3><?php _e('Ticket Details', 'dc-woocommerce-multi-vendor'); ?></h3></div>
                <div class="panel-body panel-content-padding">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?php _e('Product', 'dc-woocommerce-multi-vendor'); ?></th>
                                <th><?php _e('Total', 'dc-woocommerce-multi-vendor'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
						
								<!--Mel: 29/06/19 -->
								<script>
								
									

									window.addEventListener('load', async () => {
																			
										// To gain access to modern dapp browsers like MetaMask. Yes, MetaMask is a dapp browser and also a wallet! User needs to accept.
										if (window.ethereum) {
											
											web3<?php echo $i; ?> = new Web3(ethereum);
											
											try {
												// Request account access if needed
												await ethereum.enable();
												
												//Accounts now exposed
												
												var version<?php echo $i; ?> = web3<?php echo $i; ?>.version;
												
												console.log("Using web3js version " + version<?php echo $i; ?> );
												
												//This is another way to retrieve the current wallet address on MetaMask
												/*var accounts = web3.eth.getAccounts(function(error, result) {
													if (error) {
														console.log(error);
													} else {
														console.log(result + " is current account");
													}       
												});*/
												
												//The other recommended way to get wallet address 
												//walletAddress = web3.eth.defaultAccount;
												
												//Get wallet info in the form of Javascript object
												var account<?php echo $i; ?> = web3<?php echo $i; ?>.eth.accounts;
												
												//Get the current MetaMask selected/active wallet
												walletAddress<?php echo $i; ?> = account<?php echo $i; ?>.givenProvider.selectedAddress;
												
												//Check if Metamask is locked
												if (!empty(walletAddress<?php echo $i; ?>)){
													
													//Detect if the user changes the account on MetaMask
													window.ethereum.on('accountsChanged', function (accounts) {
														console.log("MetaMask account change. Reloading...");
														window.location.reload(); 
													})
													
													smartContract<?php echo $i; ?> = new web3<?php echo $i; ?>.eth.Contract(abi<?php echo $i; ?>, contractAddress<?php echo $i; ?>);
													
													//If not locked, continue to run the app
													startApp(smartContract<?php echo $i; ?>, web3<?php echo $i; ?>, tokenId<?php echo $i; ?>, contractAddress<?php echo $i; ?>, recipientAddress<?php echo $i; ?>, walletAddress<?php echo $i; ?>);

												} else {
													  
													 window.location.reload(); 
												  
													//If locked, alert user to unlock their MetaMask
													/* 				if (alert("<?php _e('Your MetaMask is locked. Please unlock with your password and then press OK below.', 'dc-woocommerce-multi-vendor'); ?>")){
																	} else {
																		window.location.reload(); 
																	} */
												  }
												
											} catch (error) {
												
												console.log(error);
													
												if (alert("<?php _e('Error in system please try again later', 'dc-woocommerce-multi-vendor'); ?>")){
												} 
											}
										}
										//Legacy dapp browsers like Mist I guess
										else if (window.web3) {
											
											// Accounts always exposed
											
											// You have a web3 browser! Continue below!
											web3<?php echo $i; ?> = new Web3(web3.currentProvider);
											
											var version<?php echo $i; ?> = web3<?php echo $i; ?>.version;

											//Get wallet info in the form of Javascript object
											var account<?php echo $i; ?> = web3<?php echo $i; ?>.eth.accounts;
											
											//Get the current MetaMask selected/active wallet
											walletAddress<?php echo $i; ?> = account<?php echo $i; ?>.givenProvider.selectedAddress;
											
											//Check if Metamask is locked
											if (!empty(walletAddress<?php echo $i; ?>)){
												
												//Detect if the user changes the account on MetaMask
												window.ethereum.on('accountsChanged', function (accounts) {
													console.log("MetaMask account change. Reloading...");
													window.location.reload(); 
												})
												
												smartContract<?php echo $i; ?> = new web3<?php echo $i; ?>.eth.Contract(abi<?php echo $i; ?>, contractAddress<?php echo $i; ?>);
													
												//If not locked, continue to run the app
												startApp(smartContract<?php echo $i; ?>, web3<?php echo $i; ?>, tokenId<?php echo $i; ?>, contractAddress<?php echo $i; ?>, recipientAddress<?php echo $i; ?>, walletAddress<?php echo $i; ?>);

											  } else {
											  
												//If locked, alert user to unlock their MetaMask
												if (alert("<?php _e('Your MetaMask is locked. Please unlock with your password and then press OK below.', 'dc-woocommerce-multi-vendor'); ?>")){
												} else {
													window.location.reload(); 
												}
											  }
										}
										// Non-dapp browsers...
										else {
											
											if (alert("<?php _e('No MetaMask plugin detected. Please install MetaMask digital wallet at www.metamask.io', 'dc-woocommerce-multi-vendor'); ?>")){
											} else {
												window.location.reload(); 
											}
										}

									});				

								
	
							

							
							

                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php do_action( 'wcmp_vendor_dashboard_order_details_table_info', $order, $vendor ); ?>
                        </tfoot>
                    </table>
                </div>
            </div>  
        </div>
       
    </div>
</div>



