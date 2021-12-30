
	var abi = [
	{
		"constant": false,
		"inputs": [
			{
				"name": "_tokenId",
				"type": "uint256"
			},
			{
				"name": "_uri",
				"type": "string"
			}
		],
		"name": "_setTokenURI",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"name": "_interfaceId",
				"type": "bytes4"
			}
		],
		"name": "supportsInterface",
		"outputs": [
			{
				"name": "",
				"type": "bool"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [],
		"name": "name",
		"outputs": [
			{
				"name": "",
				"type": "string"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "getApproved",
		"outputs": [
			{
				"name": "",
				"type": "address"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "_to",
				"type": "address"
			},
			{
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "approve",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [],
		"name": "totalSupply",
		"outputs": [
			{
				"name": "",
				"type": "uint256"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "newPrice",
				"type": "uint256"
			}
		],
		"name": "setCurrentPrice",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [],
		"name": "InterfaceId_ERC165",
		"outputs": [
			{
				"name": "",
				"type": "bytes4"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "_from",
				"type": "address"
			},
			{
				"name": "_to",
				"type": "address"
			},
			{
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "transferFrom",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"name": "_owner",
				"type": "address"
			},
			{
				"name": "_index",
				"type": "uint256"
			}
		],
		"name": "tokenOfOwnerByIndex",
		"outputs": [
			{
				"name": "",
				"type": "uint256"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [],
		"name": "kill",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "_from",
				"type": "address"
			},
			{
				"name": "_to",
				"type": "address"
			},
			{
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "safeTransferFrom",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "exists",
		"outputs": [
			{
				"name": "",
				"type": "bool"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"name": "_index",
				"type": "uint256"
			}
		],
		"name": "tokenByIndex",
		"outputs": [
			{
				"name": "",
				"type": "uint256"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "_type",
				"type": "uint256"
			},
			{
				"name": "_title",
				"type": "string"
			},
			{
				"name": "_description",
				"type": "string"
			}
		],
		"name": "buyToken",
		"outputs": [],
		"payable": true,
		"stateMutability": "payable",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "ownerOf",
		"outputs": [
			{
				"name": "",
				"type": "address"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"name": "_owner",
				"type": "address"
			}
		],
		"name": "balanceOf",
		"outputs": [
			{
				"name": "",
				"type": "uint256"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [],
		"name": "renounceOwnership",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "viewToken",
		"outputs": [
			{
				"name": "tokenType_",
				"type": "uint256"
			},
			{
				"name": "tokenTitle_",
				"type": "string"
			},
			{
				"name": "tokenDescription_",
				"type": "string"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [],
		"name": "owner",
		"outputs": [
			{
				"name": "",
				"type": "address"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [],
		"name": "myTokens",
		"outputs": [
			{
				"name": "",
				"type": "uint256[]"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [],
		"name": "symbol",
		"outputs": [
			{
				"name": "",
				"type": "string"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "_to",
				"type": "address"
			},
			{
				"name": "_approved",
				"type": "bool"
			}
		],
		"name": "setApprovalForAll",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "_from",
				"type": "address"
			},
			{
				"name": "_to",
				"type": "address"
			},
			{
				"name": "_tokenId",
				"type": "uint256"
			},
			{
				"name": "_data",
				"type": "bytes"
			}
		],
		"name": "safeTransferFrom",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "tokenURI",
		"outputs": [
			{
				"name": "",
				"type": "string"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "_type",
				"type": "uint256"
			},
			{
				"name": "_title",
				"type": "string"
			},
			{
				"name": "_description",
				"type": "string"
			},
			{
				"name": "_to",
				"type": "address"
			}
		],
		"name": "buyTransferToken",
		"outputs": [],
		"payable": true,
		"stateMutability": "payable",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"name": "_owner",
				"type": "address"
			},
			{
				"name": "_operator",
				"type": "address"
			}
		],
		"name": "isApprovedForAll",
		"outputs": [
			{
				"name": "",
				"type": "bool"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [],
		"name": "getCurrentPrice",
		"outputs": [
			{
				"name": "price",
				"type": "uint256"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": false,
		"inputs": [
			{
				"name": "_newOwner",
				"type": "address"
			}
		],
		"name": "transferOwnership",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "constructor"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"name": "buyer",
				"type": "address"
			},
			{
				"indexed": false,
				"name": "tokenId",
				"type": "uint256"
			}
		],
		"name": "BoughtToken",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"name": "previousOwner",
				"type": "address"
			}
		],
		"name": "OwnershipRenounced",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"name": "previousOwner",
				"type": "address"
			},
			{
				"indexed": true,
				"name": "newOwner",
				"type": "address"
			}
		],
		"name": "OwnershipTransferred",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"name": "_from",
				"type": "address"
			},
			{
				"indexed": true,
				"name": "_to",
				"type": "address"
			},
			{
				"indexed": true,
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "Transfer",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"name": "_owner",
				"type": "address"
			},
			{
				"indexed": true,
				"name": "_approved",
				"type": "address"
			},
			{
				"indexed": true,
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "Approval",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"name": "_owner",
				"type": "address"
			},
			{
				"indexed": true,
				"name": "_operator",
				"type": "address"
			},
			{
				"indexed": false,
				"name": "_approved",
				"type": "bool"
			}
		],
		"name": "ApprovalForAll",
		"type": "event"
	}
]

var walletAddress = '';
var tokenBalance = 0;
var numOfTokenToSend = 1;
var tokenId = 51;
const contract_address = '0xc7ab30034934953c3c2a9d9562ab2b630597ec69';
var recipientAddress = '0xCA6c272710698bbF3358c8131035CbD2AFDFea67'; //Acc 7
var senderAddress = '0x9cef2dB5b1C31B966424d0B2e1477B332cDe4898'; //Acc 6
	
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
			if (alert("Your MetaMask is locked. Please unlock it by entering your MetaMask password.")){;
			} else {
				window.location.reload(); 
			}
		  }
		
	} else {
		if (alert("No MetaMask plugin detected. To proceed, please install MetaMask digital wallet at www.metamask.io")){
		} else {
			window.location.reload(); 
		}
	}
});



function startApp(web3) {

	smartContract = new web3.eth.Contract(abi, contract_address);
	
	retrieveTokenBalance(smartContract, walletAddress);
	
	listenForClicks(smartContract, web3);
	
}

function listenForClicks (miniToken, web3) {

    var button = document.querySelector('button.transfer-token');
	
    button.addEventListener('click', function() {
		
		console.log("Below var button"); 
	
		if (getTokenBalance() >= numOfTokenToSend){
				miniToken.methods.transferFrom(walletAddress, recipientAddress, tokenId).send({
				from: walletAddress,
			  // if payable, specify value
			  // value: web3js.toWei(value, 'ether')
			}, function(err, transactionHash) {
				if (!err) {
					console.log(transactionHash + " success"); 
					waitForTxToBeMined(transactionHash);
				} else {
					console.log(err);
				}
			});
		} else {
			alert("You don't have enough ticket token to send. Your balance is " + getTokenBalance());
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
			console.log(txReceipt);
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
