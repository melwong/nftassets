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
function issueTransferToken(smartContract, abiString, ticketType, ticketTitle, ticketDesc, recipientAddress) {
	
	const abi = abiString;
	
	console.log("ticketType: " + ticketType);
	console.log("ticketTitle: " + ticketTitle);
	console.log("ticketDesc: " + ticketDesc);
	
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









