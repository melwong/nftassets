//Create getter and setter functions for transaction hash
/* var txHash = {
  transHash:    '',
  getTxHash: function() {
            return this.transHash;
        },
  setTxHash: function(val) {
            this.transHash = val;
        }
} */

var txHash = '';
var tokenExist = false;
var balanceOf = 0;

function setTxHash(transHash) {
	txHash = transHash;
}

function getTxHash(){
	return txHash;
} 

function setTokenExist(exist) {
	tokenExist = exist;
}

function getTokenExist(){
	return tokenExist;
}

//To issue ticket token and transfer token to the wallet address
function createTransferToken(smartContract, abiString, ticketTitle, ticketDesc, recipientAddress) {
	
	const abi = abiString;
	
	//Always set this to 1 for now
	const ticketType = 1;
	
/* 	console.log("ticketType: " + ticketType);
	console.log("ticketTitle: " + ticketTitle);
	console.log("ticketDesc: " + ticketDesc);
	console.log("recipientAddress: " + recipientAddress);
	console.log("smartContract: " + smartContract); */
	
	//Use the contract of document_deployed_on_mainnet_with_buyTransferToken.sol
	const contractAddress = smartContract;
	
	//Private key of Account 4 on my MetaMask wallet. Ensure there's a 0x in front of private key string
	const privateKey = '0x' + '12714D7FC9FBCE18C518EFA9B145D73955B7F4274C60C5042CE734D7A3D8CC53';
	const walletAddress = '0x77b8042e431fF76439721738237DAFD78199ac60';

	if (typeof web3 !== 'undefined') {
            web3 = new Web3(web3.currentProvider);
        } else {
            // set the provider you want from Web3.providers
            web3 = new Web3(new Web3.providers.HttpProvider("https://ropsten.infura.io/v3/1d31dab8c4aa43698aa98f111d870fde"));
        }
	
	smartContract = new web3.eth.Contract(abi, contractAddress);

	// change this to whatever contract method you are trying to call, e.g. buyToken(ticketType, ticketTitle, ticketDesc)
	const query = smartContract.methods.buyTransferToken(ticketType, ticketTitle, ticketDesc, recipientAddress);
	const encodedABI = query.encodeABI();
	const tx = {
	  from: walletAddress,
	  to: contractAddress,
	  gas: 4700000,
	  data: encodedABI,
	};
	
	web3.eth.accounts.signTransaction(tx, privateKey).then(signed => {
	  const tran = web3.eth
		.sendSignedTransaction(signed.rawTransaction)
		.on('confirmation', (confirmationNumber, receipt) => {
		  console.log('=> confirmation: ' + confirmationNumber);
		})
		.on('transactionHash', hash => {
		  console.log('=> hash');
		  console.log(hash);
		  
		  //Set the transaction hash value
		  setTxHash(hash);
		    
		})
		.on('receipt', receipt => {
		  console.log('=> receipt');
		  console.log(receipt);
		})
		.on('error', console.error);
	});
	
} 

//To issue ticket token and transfer token to the wallet address
function createToken(ticketType, ticketTitle, ticketDesc, recipientAddress) {
	
	const abi = [
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
];
	
	console.log("ticketType: " + ticketType);
	console.log("ticketTitle: " + ticketTitle);
	console.log("ticketDesc: " + ticketDesc);
	
	//Use the contract of document_deployed_on_mainnet_with_buyTransferToken.sol
	const contractAddress = '0xc7ab30034934953c3c2a9d9562ab2b630597ec69';
	//const contractAddress = '0x580938fa8d19d39771893ac9e9c28cb3a1d399c4';
	
	//Private key of Account 4 on my MetaMask wallet. Ensure there's a 0x in front of private key string
	const privateKey = '0x' + '12714D7FC9FBCE18C518EFA9B145D73955B7F4274C60C5042CE734D7A3D8CC53';
	const walletAddress = '0x77b8042e431fF76439721738237DAFD78199ac60';

	if (typeof web3 !== 'undefined') {
            web3 = new Web3(web3.currentProvider);
        } else {
            // set the provider you want from Web3.providers
            web3 = new Web3(new Web3.providers.HttpProvider("https://ropsten.infura.io/v3/1d31dab8c4aa43698aa98f111d870fde"));
        }
	
	smartContract = new web3.eth.Contract(abi, contractAddress);
	
	//console.log(smartContract);

	// change this to whatever contract method you are trying to call, e.g. buyToken(ticketType, ticketTitle, ticketDesc)
	const query = smartContract.methods.buyTransferToken(ticketType, ticketTitle, ticketDesc, recipientAddress);
	//const query = smartContract.methods.buyToken(ticketType, ticketTitle, ticketDesc);
	const encodedABI = query.encodeABI();
	const tx = {
	  from: walletAddress,
	  to: contractAddress,
	  gas: 4700000,
	  data: encodedABI,
	};
	
	//const account = web3.eth.accounts.privateKeyToAccount(privateKey);
	//console.log("Account: " , account);
	//web3.eth.getBalance(walletAddress).then(console.log);
	
	web3.eth.accounts.signTransaction(tx, privateKey).then(signed => {
	  const tran = web3.eth
		.sendSignedTransaction(signed.rawTransaction)
		.on('confirmation', (confirmationNumber, receipt) => {
		  console.log('=> confirmation: ' + confirmationNumber);
		})
		.on('transactionHash', hash => {
		  console.log('=> hash');
		  console.log(hash);
		  
		  //Set the transaction hash value
		  setTxHash(hash);
		    
		})
		.on('receipt', receipt => {
		  console.log('=> receipt');
		  console.log(receipt);
		})
		.on('error', console.error);
	});

	
/* 	token = smartContract.methods.viewToken("1").call(function(err,res){
		if(!err){
			console.log(res);
		} else {
			console.log(err);
		}
	}); 
	
	console.log(token);*/
	
}

//This fucntion checks the balance of a wallet for a specific smart contract and at the same time set the global variable indicating if there's any token inside the wallet. To call the getTokenExist function above, you must call this fucntion first.
function getBalanceOf(tokenAddress, walletAddress) {

	// The minimum ABI to get ERC20 Token balance
	let minABI = [
	  // balanceOf
	  {
		"constant":true,
		"inputs":[{"name":"_owner","type":"address"}],
		"name":"balanceOf",
		"outputs":[{"name":"balance","type":"uint256"}],
		"type":"function"
	  },
	  // decimals
	  {
		"constant":true,
		"inputs":[],
		"name":"decimals",
		"outputs":[{"name":"","type":"uint8"}],
		"type":"function"
	  }
	];
	
	if (typeof web3 !== 'undefined') {
            web3 = new Web3(web3.currentProvider);
        } else {
            // set the provider you want from Web3.providers
            web3 = new Web3(new Web3.providers.HttpProvider("https://ropsten.infura.io/v3/1d31dab8c4aa43698aa98f111d870fde"));
        }

	// Get ERC20 Token contract instance
	let contract = new web3.eth.Contract(minABI, tokenAddress);
	let exist = false;
	
	// Call balanceOf function
	contract.methods.balanceOf(walletAddress).call().then(function (result) {
		if (result) {
			
			balanceOf = result;
			
			exist = true;
			
			//Tell the global variable (see top of code) that the token exists in the wallet address 
			setTokenExist(exist);
		}
    });
}









