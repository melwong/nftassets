
	var tokenBalance = 0;
	var contractAddress = '';
	var abi = '';
	var smartContract = null;
	var web3EndPoint = '';
	var walletAddress = '';
	var privateKey = '';
	var masterAccount = '';
	var recipientAddress = '';
	var tokenId = '';
	var paymentMethod = '';
	var explorerUrl = '';
	var ExplorerName = '';
	var address = '';

	abi = [{"constant":false,"inputs":[{"name":"_tokenId","type":"uint256"},{"name":"_uri","type":"string"}],"name":"_setTokenURI","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_interfaceId","type":"bytes4"}],"name":"supportsInterface","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_tokenId","type":"uint256"}],"name":"getApproved","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_to","type":"address"},{"name":"_tokenId","type":"uint256"}],"name":"approve","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"newPrice","type":"uint256"}],"name":"setCurrentPrice","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"InterfaceId_ERC165","outputs":[{"name":"","type":"bytes4"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_type","type":"uint256"},{"name":"_title","type":"string"},{"name":"_description","type":"string"},{"name":"_uri","type":"string"}],"name":"mintNftToken","outputs":[],"payable":true,"stateMutability":"payable","type":"function"},{"constant":false,"inputs":[{"name":"_from","type":"address"},{"name":"_to","type":"address"},{"name":"_tokenId","type":"uint256"}],"name":"transferFrom","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_owner","type":"address"},{"name":"_index","type":"uint256"}],"name":"tokenOfOwnerByIndex","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"kill","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_from","type":"address"},{"name":"_to","type":"address"},{"name":"_tokenId","type":"uint256"}],"name":"safeTransferFrom","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_tokenId","type":"uint256"}],"name":"exists","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_index","type":"uint256"}],"name":"tokenByIndex","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_tokenId","type":"uint256"}],"name":"ownerOf","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_owner","type":"address"}],"name":"balanceOf","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"renounceOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_tokenId","type":"uint256"}],"name":"viewToken","outputs":[{"name":"tokenType_","type":"uint256"},{"name":"tokenTitle_","type":"string"},{"name":"tokenDescription_","type":"string"},{"name":"tokenUri_","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"myTokens","outputs":[{"name":"","type":"uint256[]"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_to","type":"address"},{"name":"_approved","type":"bool"}],"name":"setApprovalForAll","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_from","type":"address"},{"name":"_to","type":"address"},{"name":"_tokenId","type":"uint256"},{"name":"_data","type":"bytes"}],"name":"safeTransferFrom","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"name":"_tokenId","type":"uint256"}],"name":"tokenURI","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_type","type":"uint256"},{"name":"_title","type":"string"},{"name":"_description","type":"string"},{"name":"_to","type":"address"}],"name":"buyTransferToken","outputs":[],"payable":true,"stateMutability":"payable","type":"function"},{"constant":true,"inputs":[{"name":"_owner","type":"address"},{"name":"_operator","type":"address"}],"name":"isApprovedForAll","outputs":[{"name":"","type":"bool"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"getCurrentPrice","outputs":[{"name":"price","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"name":"_newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"name":"buyer","type":"address"},{"indexed":false,"name":"tokenId","type":"uint256"}],"name":"BoughtToken","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"previousOwner","type":"address"}],"name":"OwnershipRenounced","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"previousOwner","type":"address"},{"indexed":true,"name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_from","type":"address"},{"indexed":true,"name":"_to","type":"address"},{"indexed":true,"name":"_tokenId","type":"uint256"}],"name":"Transfer","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_owner","type":"address"},{"indexed":true,"name":"_approved","type":"address"},{"indexed":true,"name":"_tokenId","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_owner","type":"address"},{"indexed":true,"name":"_operator","type":"address"},{"indexed":false,"name":"_approved","type":"bool"}],"name":"ApprovalForAll","type":"event"}];
	
	
	//To fill the user's wallet address text field entered by admin. This is required when user did not provide a wallet
	//function getWalletAddress(value) {
		//document.getElementById('wallet').setAttribute("value", value);
	//} 
	
	walletAddress = '0xCA6c272710698bbF3358c8131035CbD2AFDFea67'; //Account on Metamask. Owner of smart contract
	
	//Mel: 29/12/21. These variables are currently unused but here for safekeeping if we decided to use signTransaction with priv key
	privateKey = 'f150328450b492ab'; //Private key of wallet address
	masterAccount = '0xCA6c272710698bbF3358c8131035CbD2AFDFea67';	//Should be same as wallet address

	//Avalanche smart contract on Fuji testnet
	contractAddress = '0x71Dce47cd7b92D18daf02e6ee5390bdF90470D15'; 
	
	//Endpoint is like https://kovan.infura.io/v3/f2e537e744a14d3a9981ddec2ae859c9 but leave empty if wanna use Metamask
	web3EndPoint = '';
	
	explorerUrl = 'https://stardust-explorer.metis.io/tx/';
	explorerName = 'Stardust Explorer (Testnet)';

	function startApp(web3) {

		smartContract = new web3.eth.Contract(abi, contractAddress);
		
	}

	async function doesTokenIdExist(tokenId, contract, walletAddress) {
		
		var tokenExists = false;
		
		try {
			
			await contract.methods.ownerOf(tokenId).call(function(err, res) {
		  
				if (!err){
					
					console.log("Owner of token with tokenId " + tokenId + " is " + res);
					console.log("Current wallet address is " + walletAddress);
					
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

	async function waitForTxToBeMined(txHash) {
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
		alert("<?php esc_html_e('Error. Please try to switch your wallet from main network to test network and back.', 'advanced-classifieds-and-directory-pro'); ?>");
		console.log(error);
	}

	function indicateSuccess(txReceipt){
		alert("<?php esc_html_e('Transaction completed.', 'advanced-classifieds-and-directory-pro'); ?>");
		console.log(txReceipt);
		
	}


	//To check if an object (or something) is empty
	function empty(n){
		return !(!!n ? typeof n === 'object' ? Array.isArray(n) ? !!n.length : !!Object.keys(n).length : true : false);
	}

	//To read the transaction hash and send it back to vendor-order-details page to be marked as shipped
	function processTransactionHash(txHash){
		var data = {
			hash: txHash,
			tracking_url: explorerUrl + txHash,
			tracking_id: txHash,
		};
	}

	window.addEventListener('load', async () => {
	
		// To gain access to modern dapp browsers like MetaMask. Yes, MetaMask is a dapp browser and also a wallet! User needs to accept.
		if (window.ethereum) {
			
			if (web3EndPoint != '') {
				
				//Use web3 endpoint such as from Infura
				web3 = new Web3( new Web3.providers.HttpProvider(web3EndPoint) );
				
			} else {
				
				//Use Metamask
				web3 = new Web3(ethereum);
			}
			
			try {
				// Request account access if needed
				//await ethereum.enable();
				
				//Accounts now exposed
				
				var version = web3.version;
				
				console.log("Using web3js version " + version );
				
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
						console.log("MetaMask account changed. Reloading...");
						window.location.reload(); 
					})
					
					//If not locked, continue to run the app
					startApp(web3);

									//After minted, we can then send the token to buyer
									var buttonSendToken = document.getElementById('sendToken');

									buttonSendToken.addEventListener('click', function (event) {
										
										event.preventDefault();
										
										//Get wallet address from filled wallet text field
										recipientAddress = document.getElementById('wallet').value;

										console.log("recipientAddress: " + recipientAddress);

											
												if ( recipientAddress != '' && recipientAddress != null ) {
												
													//document.getElementById("loading3").innerHTML = '<div class="acadp-spinner"></div>';
													
													console.log("Send from: " + walletAddress);
													console.log("Send to: " + recipientAddress);
													
													smartContract.methods.transferFrom(walletAddress, recipientAddress, 2).send({
														from: walletAddress,
														gasLimit: 4700000,
													  // if payable, specify value
													  // value: web3js.toWei(value, 'ether')
													}, function (err, transferTransactionHash) {
														
														if (!err) {
															console.log("Transfer transaction: " + transferTransactionHash); 
															document.getElementById("loading3").innerHTML = 'Processed. <a href="' + explorerUrl + transferTransactionHash + '">View on ' + explorerName + '</a><input type="hidden" name="transfer_transaction_hash" value="' + explorerUrl + transferTransactionHash + '">';
															setTimeout(processTransactionHash(transferTransactionHash), 5000);
															waitForTxToBeMined(transferTransactionHash);
															
														} else {
															alert('Error. Please read console log.');
															console.log(err);
															document.getElementById("loading3").innerHTML = '';
														}
														
													});
												
												} else {
													alert('Ensure you entered a recipient wallet address.');
												}

									
									});
									//Mel: 19/11/21 End
										
				  } else { //if (!empty(walletAddress)) {
					  
					  //Mel: 29/12/21. Had the problem where the page keeps reloading cos we can't call a wallet address from Metamask 
					 //window.location.reload(); 
				  
				  }
				
				
			} catch (error) {
				alert('Error. Please read console log.');
				console.log(error);

			}

			}
	});
														