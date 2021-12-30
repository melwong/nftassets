<?php
/**
 * The template for displaying the interface to talk to crypto wallet such as MetaMask. This template is based on wp-content/plugins/dc-woocommerce-multi-vendor/templates/vendor-dashboard/vendor-orders/vendor-order-details.php
 *
 * This template CANNOT be overridden by copying it to yourtheme/dc-product-vendor/vendor-dashboard/vendor-orders/vendor-order-details.php. At least, it's not tested.
 * NOTE: This code only works when someone orders tickets for one event at a time. Not tickets for different events.
 *
 * @author 	1Krowd/Melvin
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

//Mel: 26/09/19. To upgrade from 3.3.4 to 3.4.3. During upgrade, we noticed that the function get_wcmp_vendor_orders couldn't get the array of data we need, thus we changed it to $order->get_items();
$vendor_items = $order->get_items();
//$vendor_items = get_wcmp_vendor_orders(array('order_id' => $order->get_id(), 'vendor_id' => $vendor->id));

$vendor_order_amount = get_wcmp_vendor_order_amount(array('order_id' => $order->get_id(), 'vendor_id' => $vendor->id));
$subtotal = 0;

//Mel: Begin
$items = $order->get_items();

foreach ( $items as $item ) {
    $product_name = $item->get_name();
    $product_id = $item->get_product_id();
}
	
$product = new WC_Product($product_id);

$contract_address = get_post_meta($product_id, 'smart_contract_address', true);

//Mel: 26/09/19. To upgrade from 3.3.4 to 3.4.3. We have to change from using $order->get_id() to $order->get_id() minus 1 because the order id before this order id contains the wallet address metadata 
$recipient_address = get_post_meta($order->get_id()-1, 'wallet_address', true);
//$recipient_address = get_post_meta($order->get_id()-1, 'wallet_address', true);

$abi = get_post_meta($product_id, 'abi', true);
//$token_id = $product->get_attribute('TokenID');
//$token_id = get_post_meta($product_id, '_sku', true);
 

/* echo "Seating: " . $product->get_attribute( 'Seating' );
echo "<br >";
echo "TokenID: " . $product->get_attribute( 'TokenID' );
echo "<br >";
echo "order id: " . $order->get_id();
echo "<br >";
echo "product id: " . $product_id;
echo "<br >";
echo "contract: " . $contract_address;
echo "<br >";
echo "wallet_address: " . $contract_address;
echo "<br />";

echo 'URL to send transaction hash: ' . esc_url(wcmp_get_vendor_dashboard_endpoint_url('vendor-orders', $order->get_id()));
 */

?>

<script src="<?php echo $WCMp->plugin_url; ?>assets/frontend/js/web3.min.js"></script>
<script src="<?php echo $WCMp->plugin_url; ?>lib/jquery/jquery-3.4.1.min.js"></script>

<script>
	
var tokenBalance = 0;
var contractAddress = '';
var abi = '';
var smartContract = null;

contractAddress = '<?php echo $contract_address; ?>';
abi = <?php echo $abi; ?>;

function startApp(web3) {
	
	//console.log("contract address: " + contractAddress);

	smartContract = new web3.eth.Contract(abi, contractAddress);
	
}

//NOTE: The entire code below doesn't really work. Needs to be tested. I place it here for future reference in case I need to reuse the codes
function listenForClicks (recipientAddress, walletAddress, smartContract, web3, tokenId) {
	
		console.log("recipientAddress: " + recipientAddress);
		console.log("tokenId: " + tokenId);
		console.log("smartContract: " + smartContract);
		console.log("web3: " + web3);
		console.log("walletAddress: " + walletAddress);
		
		//Find all buttons on the page with the name transferToken
		var button = document.querySelectorAll('button.transferToken');
		
		//doesTokenIdExist(tokenId, smartContract, walletAddress).then(function(result) {
			
			for (var i = 0; i < button.length; i++) {
			
				button[i].addEventListener('click', function () {
					
					doesTokenIdExist(tokenId, smartContract, walletAddress).then(function(result) {
						
						if (result) {
							
								smartContract.methods.transferFrom(walletAddress, recipientAddress, tokenId).send({
								from: walletAddress,
								gas: 4700000,
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
					
					});
					
					//Working
					/*smartContract.methods.transferFrom(senderAddress, recipientAddress, 50).send({
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
					/*smartContract.methods.buyTransferToken(1, 'Lady Gaga', 'VIP Tick', recipient_address).send({
					  from: walletAddress,
					  // if payable, specify value
					  // value: web3js.toWei(value, 'ether')
					});*/
				  
				  //Working
				  /*smartContract.methods.balanceOf(walletAddress).call(function(err,res){
						if(!err){
							console.log(res);
							setTokenBalance(res);
						} else {
							console.log(err);
						}
					});*/
					
				});
			}
			
		//});
		
	}

async function retrieveTokenBalance(contract, walletAddress) {

  await contract.methods.balanceOf(walletAddress).call(function(err,res){
		if (!err){
			console.log("Token balance in wallet: " + res);
			setTokenBalance(res);
		} else {
			console.log(err);
		}
	});
		
}

//Checks if the current wallet holder owns the token by checking who is the owner of the tokenId. If the owner of the token matches the current wallet holder, then return true. Otherwise false
/* async function doesTokenIdExist(tokenId, contract, walletAddress) {
	
	var tokenExists = false;
	
	await contract.methods.exists(tokenId).call(async function (err, res) {
		
		//DEBUG
		console.log("contract.methods.exists(tokenId): " + res);
		
		//DEBUG
		console.log("1: " + res);
		
		if (res) {
			
			//DEBUG
			console.log("2: " + res);
			
			await contract.methods.ownerOf(tokenId).call(function (err, res) {
				
				//DEBUG
				console.log("3: " + res);
			
				if (!err) {
				
					console.log("Owner of token with tokenId " + tokenId + " is " + res);
					console.log("Current wallet address is " + walletAddress);
					
					tokenAddress = res.toLowerCase();
					walletAddress = walletAddress.toLowerCase();
					
					if (tokenAddress.localeCompare(walletAddress) == 0){
						
						tokenExists = true;
						
						//DEBUG
						console.log("function doesTokenIdExist =" + tokenExists);
	
					} else {
						tokenExists = false;
					}
				
				} else {

					console.log("function doesTokenIdExist ERROR: " + err);
					console.log("function doesTokenIdExist tokenExists = " + tokenExists);
					tokenExists = false;

				}
				
				//return tokenExists;
			
			});
		
		
		} else {
			
			console.log("ERROR: " + err);
			
			tokenExists = false;
		
		}
				
			
	});
	
	//DEBUG
	console.log("function doesTokenIdExist bottom =" + tokenExists);
	
	return tokenExists;
		
} */

async function doesTokenIdExist(tokenId, contract, walletAddress) {
	
	var tokenExists = false;
	
	try {
		
		await contract.methods.ownerOf(tokenId).call(function(err, res) {
	  
			if (!err){
				
				//console.log("Owner of token with tokenId " + tokenId + " is " + res);
				//console.log("Current wallet address is " + walletAddress);
				
				tokenAddress = res.toLowerCase();
				walletAddress = walletAddress.toLowerCase();
				
				if (tokenAddress.localeCompare(walletAddress) == 0){
					tokenExists = true;
				} 
				
			} else {
				console.log(err);
			}
		});
			
	} catch (error) {
		
		console.log(error);
		tokenExists = false;
	
	}
	
	return tokenExists;	
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
	alert("<?php _e('Your ticket token is being transferred to the buyer\'s wallet', 'dc-woocommerce-multi-vendor'); ?>");
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
						
						<!--Mel-->
                        <tbody id="buttondiv">
							
							<script>
							
								window.addEventListener('load', async () => {
									
									// To gain access to modern dapp browsers like MetaMask. Yes, MetaMask is a dapp browser and also a wallet! User needs to accept.
									if (window.ethereum) {
										
										web3 = new Web3(ethereum);
										
										try {
											// Request account access if needed
											await ethereum.enable();
											
											//Accounts now exposed
											
											var version = web3.version;
											
											//console.log("Using web3js version " + version );
											
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
											var account = web3.eth.accounts;
											
											//Get the current MetaMask selected/active wallet
											walletAddress = account.givenProvider.selectedAddress;
											
											//Check if Metamask is locked
											if (!empty(walletAddress)) {
												
												//Detect if the user changes the account on MetaMask
												window.ethereum.on('accountsChanged', function (accounts) {
													console.log("MetaMask account change. Reloading...");
													window.location.reload(); 
												})
												
												//If not locked, continue to run the app
												startApp(web3);
												
												<?php $i = 0; ?>
												<?php foreach ($vendor_items as $item): 
													
													//Mel: 26/09/19. To upgrade from 3.3.4 to 3.4.3. We changed from using $item_obj to $item due to change in how we grab the data for $vendor_items. See line 32
													//$item_obj = $order->get_item($item->order_item_id); 
													
													/*$edit_product_link = '';
													if (current_user_can('edit_published_products') && get_wcmp_vendor_settings('is_edit_delete_published_product', 'capabilities', 'product') == 'Enable') {
														$edit_product_link = esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_edit_product_endpoint', 'vendor', 'general', 'edit-product'), $item->product_id));
													}*/
													
													//Mel: 26/09/19. To upgrade from 3.3.4 to 3.4.3. We changed from using $item_obj to $item due to change in how we grab the data for $vendor_items. See line 32
													
													//Mel: 05/11/19. To fix the problem where if the product is a simple product without a variation id, we use get_product_id()
													$variation_id = $item->get_product_id();
													//$variation_id = $item->get_variation_id();
													//$variation_id = $item_obj->get_variation_id();
													
													//Mel: 26/09/19. To upgrade from 3.3.4 to 3.4.3. We commented these 3 variables below out and we use the same variables from above.
													//$contract_address = get_post_meta($item->product_id, 'smart_contract_address', true);
													//$recipient_address = get_post_meta($order->get_id(), 'wallet_address', true);
													//$abi = get_post_meta($item->product_id, 'abi', true);
													
													$token_id = get_post_meta($variation_id, '_sku', true);
													$i++;

												?>
													tokenId<?php echo $i; ?> = <?php echo $token_id; ?>;
													recipientAddress<?php echo $i; ?> = '<?php echo $recipient_address; ?>';
													
													//Mel: 26/09/19. To upgrade from 3.3.4 to 3.4.3. We changed from using $item_obj to $item due to change in how we grab the data for $vendor_items. See line 32
													document.getElementById("buttondiv").insertAdjacentHTML('beforeend', '<tr><td><?php echo $edit_product_link ? '<a href="' . $edit_product_link . '" class="wcmp-order-item-link">' . esc_html( $item->get_name() ) . '</a>' : esc_html( $item->get_name() ); ?><small class="times">&times;</small><?php esc_html( $item->get_quantity() ); ?><br /><?php _e('Ticket Token ID', 'dc-woocommerce-multi-vendor'); ?>: <?php echo $token_id; ?><br /><?php _e('Contract Address', 'dc-woocommerce-multi-vendor'); ?>: <?php echo $contract_address; ?><br /><?php _e('Buyer Address', 'dc-woocommerce-multi-vendor'); ?>: <?php echo $recipient_address; ?></td><td><button class="transferToken<?php echo $i; ?>"><?php _e('Send Ticket Token', 'dc-woocommerce-multi-vendor'); ?></button></td></tr>');
													
													var button<?php echo $i; ?> = document.querySelector('button.transferToken<?php echo $i; ?>');
														
													button<?php echo $i; ?>.addEventListener('click', function () {
															
														doesTokenIdExist(tokenId<?php echo $i; ?>, smartContract, walletAddress).then( function (result) {

															if (result) {
																
																smartContract.methods.transferFrom(walletAddress, recipientAddress<?php echo $i; ?>, tokenId<?php echo $i; ?>).send({
																	from: walletAddress,
																	gasLimit: 4700000,
																  // if payable, specify value
																  // value: web3js.toWei(value, 'ether')
																}, function (err, transactionHash) {
																	
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
														
														});
																
													});				
													
												<?php endforeach; ?>

											  } else {
												  
												 window.location.reload(); 
											  
												//If locked, alert user to unlock their MetaMask
												/*if (alert("<?php _e('Your MetaMask is locked. Please unlock with your password and then press OK below.', 'dc-woocommerce-multi-vendor'); ?>")){
												} else {
													window.location.reload(); 
												}*/
											  }
											
											
										} catch (error) {
											
											console.log(error);
											
											if (alert("<?php _e('Error in system. Please try again later', 'dc-woocommerce-multi-vendor'); ?>")) {
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
														
							</script>
							<!--Mel: End -->

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



