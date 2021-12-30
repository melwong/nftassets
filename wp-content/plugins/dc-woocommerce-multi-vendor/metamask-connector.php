<?php
/**
 * To connect to MetaMask wallet
 *
 * @author 	1Krowd
 * @package 	
 * @version   1.0.0
 */
?>
<script src="<?php echo $WCMp->plugin_url; ?>assets/frontend/js/web3.min.js"></script>
<script src="<?php echo $WCMp->plugin_url; ?>lib/jquery/jquery-3.4.1.min.js"></script>

<?php

//Ensure all parameters are set. These parameters need to be assigned when adding a product in WCMP or WooCommerce.
if (!empty($_POST['contract_address']) && !empty($_POST['abi']) && !empty($_POST['recipient_address']) && !empty($_POST['token_id']) && !empty($_POST['order_id'])) {
	
	//$contract_address = '0x580938fa8d19d39771893ac9e9c28cb3a1d399c4'; //For testing
	$contract_address = $_POST['contract_address'];
	$abi = $_POST['abi'];
	$recipient_address = $_POST['recipient_address'];
	$token_id = $_POST['token_id'];
	$order_id = $_POST['order_id'];
} else {
	echo 'ERROR: HTTP Post variables are not fully set. You are unable to send ticket token. Ensure contract address, ABI and tokenId are set.';
}
/*
echo "abi: " . $abi;
echo "contractAddress: " . $contract_address;
echo "recipientAddress: " . $recipient_address;
echo "tokenId: " . $token_id;
echo "order_Id: " . $order_id;
*/

?>

<script>
	
var walletAddress = '';
var tokenBalance = 0;
var tokenExist = false;
var numOfTokenToSend = 1;
var tokenId = 0;
var contractAddress = '';
var abi = '';
var recipientAddress = '';

//tokenId = 4; //17 May: For testing purposes. For contract 0x580938fa8d19d39771893ac9e9c28cb3a1d399c4, you can use tokenId 4 to 137
tokenId = <?php echo $token_id; ?>;
contractAddress = '<?php echo $contract_address; ?>';
abi = <?php echo $abi; ?>;
recipientAddress = '<?php echo $recipient_address; ?>';
	
window.addEventListener('load', function() {
	// Check if Web3 has been injected by the browser:
	if (typeof web3 !== 'undefined') {
		// You have a web3 browser! Continue below!
		web3 = new Web3(web3.currentProvider);
		
		var version = web3.version;
		console.log("Using web3js version " + version );
		
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
			if (alert("Your MetaMask is locked. Please unlock with your password and then press OK below.")){
			} else {
				window.location.reload(); 
			}
		  }
		
	} else {
		if (alert("No MetaMask plugin detected. Please install MetaMask digital wallet at www.metamask.io")){
		} else {
			window.location.reload(); 
		}
	}
});



function startApp(web3) {

	smartContract = new web3.eth.Contract(abi, contractAddress);
	
	//Wait to check if the active wallet address contains the tokenId
	doesTokenIdExist(tokenId, smartContract, walletAddress).then(function(result){
		
		console.log("Does current wallet holder own the token? " + getTokenExist());

		listenForClicks(smartContract, web3);	
		
	});
	
	
	
}

function listenForClicks (miniToken, web3) {

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
			alert("Your wallet does not contain the ticket token with the tokenId " + tokenId + ". Please ensure you use the correct wallet");
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
	
  await contract.methods.ownerOf(tokenId).call(function(err,res){
		if(!err){
			
			console.log("Owner of token with tokenId " + tokenId + " is: " + res);
			console.log("Current wallet address is " + walletAddress);
			
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
	alert("Error");
	console.log(error);
}

function indicateSuccess(txReceipt){
	alert("Success");
	console.log(txReceipt);
}


//To check if an object (or something) is empty
function empty(n){
	return !(!!n ? typeof n === 'object' ? Array.isArray(n) ? !!n.length : !!Object.keys(n).length : true : false);
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function demo() {
  console.log('Taking a break...');
  await sleep(2000);
  //console.log('Two seconds later, showing sleep in a loop...');

  // Sleep in loop
  /*for (let i = 0; i < 5; i ++) {
  if (i === 3)
    await sleep(2000);
  console.log(i);
  }*/
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

	$.post("http://localhost/boothstand/dashboard/vendor-orders/<?php echo $order_id; ?>", data);
}

</script>

<p>You are about to send your ticket token with the ID <?php echo $token_id; ?> to the wallet address <?php echo $recipient_address; ?>. </p>
<button class="transferToken">Send Ticket Token Now</button>