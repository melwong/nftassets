					
					
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
									value: web3.utils.toWei('0.05710', 'ether')
								}, function (error, result) {
									if (error) {
										//document.getElementById('output').innerHTML = "Something went wrong. " + error.message
									} else {
										
										/* var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
										
										var data = {
											action : 'acadp_public_add_transaction_id',
											order_id : <?php echo $order_id ?>,
											post_id: <?php echo $post_id ?>,
											from_wallet: web3.eth.accounts[0],
											payment_method: paymentGateway,
											tx_url: tx_prefix + result
										}; */
									 
										// ajaxurl is always defined in the admin header and points to admin-ajax.php
										//$.post(ajaxurl, data, function(response) {
											window.location.href = document.location.origin + "/checkout/order-received/";
											//console.log('This is return from the server: ' + response);
										//});
										
										//document.getElementById('output').innerHTML = 'Payment successful. Track the payment at <a href="' + tx_prefix + result + '">'+ tx_prefix + result + '<a/>';
									}
								});
							} else {
								//document.getElementById('output').innerHtml = 'Please download and install Metamask wallet at <a href="https://metamask.io/">https://metamask.io/</a>'
							}
						});
