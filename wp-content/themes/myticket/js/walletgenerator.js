/*
Original Author: Robert Lie (mobilefish.com)
Source: https://www.mobilefish.com/download/ethereum/hd_wallet.html

More information:
https://www.mobilefish.com/developer/blockchain/blockchain_quickguide_ethereum_tools.html
https://www.mobilefish.com/developer/nodejs/nodejs_quickguide_browserify_bip39.html
https://www.mobilefish.com/developer/nodejs/nodejs_quickguide_browserify_bip44_constants.html
https://www.mobilefish.com/developer/nodejs/nodejs_quickguide_browserify_ethereumjs_wallets.html

Mel: 25 April 2019
Tested with MEW, Jaxx, Trust Wallet, Infinito Wallet, imToken, MetaMask, Lumi Wallet and works. Ensure derivation path is m/44'/60'/0'/0 otherwise not all wallets will work. 

THIS APPLICATION ONLY CREATE VALID KEYS AND ADDRESSES FOR ETHEREUM (ETH) WALLETS IMPLEMENTING
BIP-39 as currently used below (other option is BIP-32 AND BIP-44 but not tested).

This application uses the following libraries:
https://github.com/bitcoinjs/bip39
https://github.com/bitcoinjs/bip44-constants
https://github.com/ethereumjs/ethereumjs-wallet
*/

"use strict";

//Global variable to store the wallet seed
var seed;

//To generate the wallet by producing the address, private key and mnemonic phrase
function generateWallet(){

	var walletSeed = generateSeed();
	
	var walletData = generateAddress(walletSeed);

	walletData[2] = walletSeed[1];
	
	//Return walletData[0] as wallet address, walletData[1] as private key and walletData[2] as mnemonic phrase
	return walletData;

}

//To generate the seed and mnemonic phrase for the wallet
function generateSeed() {
	
	//Set 128 bit entrophy length (12 word mnemonic phrase)
	const strength = 128;
	
	//Set the language of the mnemonic phrase to be English
	const wordName = 'english';
	
	//No need to set password
	const password = "";

	const rng = null;  // Let module randombytes create this for us.
	const wordList = eval('bip39.wordlists.'+wordName);

	const mnemonic = bip39.generateMnemonic(strength, rng, wordList);
	const seedHex = bip39.mnemonicToSeedHex(mnemonic, password);
	seed = bip39.mnemonicToSeed(mnemonic, password);
	const randomNumber = bip39.mnemonicToEntropy(mnemonic, wordList);

	const isMnemonicValid = bip39.validateMnemonic(mnemonic, wordList);

	const numberOfWords = (parseInt(strength) + (strength / 32)) / 11;

	const hdwallet = hdkey.fromMasterSeed(seed);
	const privateExtendedKey= hdwallet.privateExtendedKey();
	const publicExtendedKey= hdwallet.publicExtendedKey();
	
	//Return the seed and the 12 word phrase
	return [seed, mnemonic];

}

//To generate the wallet address and private key
function generateAddress(_seedHex){
	
	//Set coinType to be 60 for Ethereum type
	const coinType = 60;

	// Get BIP39 seed
	const seedHex = _seedHex;

	//Set start address index to be 0
	let startIndex = 0;
	
	//Set end address index to be 1
	let endIndex = 1;

	//IMPORTANT
	// According to original author, "I have stored seed in a global variable. This is a hacky solution.
	// Better solution (not implemented): let seed = new Buffer(seedHex, 'hex');"
	const hd = hdkey.fromMasterSeed(seed);

	const privateExtendedKey= hd.privateExtendedKey();
	const publicExtendedKey= hd.publicExtendedKey();
	
	//Mel
	let _address = "";
	let _privateKey = "";
	
	//Mel: Set the derivationPath to be m / purpose' / coin_type' / account' / change / address_index
	let derivationPath = "m/44'/60'/0'/0";

	// Parse the derivationPath
	const parts = derivationPath.split('/');
	const m_part = parts[0];
	const purpose_part = parts[1];
	const coinType_part = parts[2];
	const account_part = parts[3];
	const change_part = parts[4];

	// Construct
	// accountNodeDerivationPath: m / purpose' / coin_type' / account'
	// changeNodeDerivationPath: m / purpose' / coin_type' / account' / change
	const accountNodeDerivationPath = m_part + "/" + purpose_part + "/" + coinType_part + "/" + account_part;
	const changeNodeDerivationPath = accountNodeDerivationPath + "/" + change_part;

	// Get Account xprv key and xpub key
	const accountNode = hd.derivePath(accountNodeDerivationPath);
	const accountNodeExtendedPrivateKey= accountNode.privateExtendedKey();
	const accountNodeExtendedPublicKey= accountNode.publicExtendedKey();

	// Get Change xprv key and xpub key
	const changeNode = hd.derivePath(changeNodeDerivationPath);
	const changeNodeExtendedPrivateKey= changeNode.privateExtendedKey();
	const changeNodeExtendedPublicKey= changeNode.publicExtendedKey();
	

	for(let i=startIndex; i<endIndex; i++) {
		let addressIndexDerivationPath = changeNodeDerivationPath + "/" + i;

		// There are two methods to create the addressIndexWallet.
		// Method A:
		// let addressIndexWallet = hd.derivePath(addressIndexDerivationPath).getWallet();
		// Method B:
		let addressIndexWallet = changeNode.deriveChild(i).getWallet();

		let publicKey = addressIndexWallet.getPublicKey().toString('hex');
		let privateKey = addressIndexWallet.getPrivateKey().toString('hex');

		// The ethereumjs library can only generate valid addresses for Ethereum and not for other coin types.
		if(coinType == 60) {
			let address = addressIndexWallet.getAddress().toString("hex");
			
			//Mel
			_address = "0x" + address;
			
		}
		
		//Mel
		_privateKey = privateKey;
		

	}

	//Mel: Return the wallet public address and private key in an array
	return [_address, _privateKey];
}

//Original function by author. Not used
function generateAddresses(){
	document.getElementById("outputKeys").innerHTML = "";

	// Get coinType
	const coinType = document.getElementById('coinlist').value;

	// Get BIP39 seed
	const seedHex = document.getElementById("bip39Seed").value;

	// Check BIP39 seed
	if (!seedHex.match(/[0-9A-F]{128}/gi)) {
		alert("Not a valid BIP-39 seed");
		return;
	}

	let startIndex = document.getElementById('startAddressIndex').value;
	if(isNaN(startIndex) || startIndex < 0) {
		alert("Not a valid start address index value");
		return;
	}

	let endIndex = document.getElementById('endAddressIndex').value;
	if(isNaN(endIndex) || endIndex < 0) {
		alert("Not a valid end address index value");
	}

	// I have stored seed in a global variable. This is a hacky solution.
	// Better solution (not implemented): let seed = new Buffer(seedHex, 'hex');
	const hd = hdkey.fromMasterSeed(seed);

	const privateExtendedKey= hd.privateExtendedKey();
	const publicExtendedKey= hd.publicExtendedKey();

	const toggle = document.getElementById('toggleCustomDerivationPath').checked;

	let output="";

	if(toggle) {
		// Custom derivation path
		let nodeDerivationPath = document.getElementById('derivationPath2').value;

		// Remove all white spaces
		nodeDerivationPath = nodeDerivationPath.replace(/\s/g,'');

		// Check the derivationPath
		const re = /^m(\/[0-9]+'?)+$/g;
		if(!re.test(nodeDerivationPath)) {
			alert("The custom derivation path is invalid");
			return;
		}

		const node = hd.derivePath(nodeDerivationPath);
		const nodeExtendedPrivateKey= node.privateExtendedKey();
		const nodeExtendedPublicKey= node.publicExtendedKey();

		output += "<div id='outputContainer3'>";
		output += "Node:<br />";
		output += "Derivation path: "+nodeDerivationPath+"<br />";
		output += "Extended private key: "+nodeExtendedPrivateKey+"<br />";
		output += "Extended public key: "+nodeExtendedPublicKey;
		output += "<br /><br />";
		output += "------------------------------------------------------------";
		output += "<br /><br />";

		for(let i=startIndex; i<endIndex; i++) {
			let addressIndexDerivationPath = nodeDerivationPath + "/" + i;
			let addressIndexWallet = node.deriveChild(i).getWallet();
			let publicKey = addressIndexWallet.getPublicKey().toString('hex');
			let privateKey = addressIndexWallet.getPrivateKey().toString('hex');

			output += "Derivation path: "+addressIndexDerivationPath + "<br />";

			// The ethereumjs library can only generate valid addresses for Ethereum and not for other coin types.
			if(coinType == 60) {
				let address = addressIndexWallet.getAddress().toString("hex");
				output += "Address: "+ address + "<br />";
			}
			output += "Private key: "+ privateKey + "<br />";
			output += "Public key: "+ publicKey;

			output += "<br /><br />";
		}
		output += "<"+"/div>";
	} else {
		// Get the derivationPath: m / purpose' / coin_type' / account' / change / address_index
		let derivationPath = document.getElementById('derivationPath').value;

		// Parse the derivationPath
		const parts = derivationPath.split('/');
		const m_part = parts[0];
		const purpose_part = parts[1];
		const coinType_part = parts[2];
		const account_part = parts[3];
		const change_part = parts[4];

		// Construct
		// accountNodeDerivationPath: m / purpose' / coin_type' / account'
		// changeNodeDerivationPath: m / purpose' / coin_type' / account' / change
		const accountNodeDerivationPath = m_part + "/" + purpose_part + "/" + coinType_part + "/" + account_part;
		const changeNodeDerivationPath = accountNodeDerivationPath + "/" + change_part;

		// Get Account xprv key and xpub key
		const accountNode = hd.derivePath(accountNodeDerivationPath);
		const accountNodeExtendedPrivateKey= accountNode.privateExtendedKey();
		const accountNodeExtendedPublicKey= accountNode.publicExtendedKey();

		// Get Change xprv key and xpub key
		const changeNode = hd.derivePath(changeNodeDerivationPath);
		const changeNodeExtendedPrivateKey= changeNode.privateExtendedKey();
		const changeNodeExtendedPublicKey= changeNode.publicExtendedKey();

		output += "<div id='outputContainer3'>";
		output += "Account:<br />";
		output += "Derivation path: "+accountNodeDerivationPath+"<br />";
		output += "Extended private key: "+accountNodeExtendedPrivateKey+"<br />";
		output += "Extended public key: "+accountNodeExtendedPublicKey;
		output += "<br /><br />";
		output += "Change or BIP32:<br />";
		output += "Derivation path: "+changeNodeDerivationPath+"<br />";
		output += "Extended private key: "+changeNodeExtendedPrivateKey+"<br />";
		output += "Extended public key: "+changeNodeExtendedPublicKey;

		output += "<br /><br />";
		output += "------------------------------------------------------------";
		output += "<br /><br />";

		for(let i=startIndex; i<endIndex; i++) {
			let addressIndexDerivationPath = changeNodeDerivationPath + "/" + i;

			// There are two methods to create the addressIndexWallet.
			// Method A:
			// let addressIndexWallet = hd.derivePath(addressIndexDerivationPath).getWallet();
			// Method B:
			let addressIndexWallet = changeNode.deriveChild(i).getWallet();

			let publicKey = addressIndexWallet.getPublicKey().toString('hex');
			let privateKey = addressIndexWallet.getPrivateKey().toString('hex');

			output += "Derivation path: "+addressIndexDerivationPath + "<br />";

			// The ethereumjs library can only generate valid addresses for Ethereum and not for other coin types.
			if(coinType == 60) {
				let address = addressIndexWallet.getAddress().toString("hex");
				output += "Address: "+ address + "<br />";
			}
			output += "Private key: "+ privateKey + "<br />";
			output += "Public key: "+ publicKey;

			output += "<br /><br />";
		}
		output += "<"+"/div>";
	} // end if(toggle)

	document.getElementById("outputKeys").innerHTML = output;
}

//Original function by author. Not used. And not tested
function restoreAddresses(){
	let restoreKey = document.getElementById('restoreKey').value;
	restoreKey = restoreKey.trim();

	document.getElementById("outputRestore").innerHTML = "";

	const toggle = document.getElementById('toggleFromEthreumWallet').checked;

	let startIndex = document.getElementById('startAddressIndex2').value;
	if(isNaN(startIndex) || startIndex < 0) {
		alert("Not a valid start address index value");
		return;
	}

	let endIndex = document.getElementById('endAddressIndex2').value;
	if(isNaN(endIndex) || endIndex < 0) {
		alert("Not a valid end address index value");
	}

	// Simpel check
	const prefix = restoreKey.substring(0, 4);

	if(prefix != "xprv" && prefix != "xpub") {
		alert("Not a valid xprv or xpub key");
		return;
	}

	var hdnode = hdkey.fromExtendedKey(restoreKey);

	let extendedPublicKey = "";
	let extendedPrivateKey = "";

	if(prefix == "xprv"){
		extendedPublicKey = hdnode.publicExtendedKey();
		extendedPrivateKey = hdnode.privateExtendedKey();
	}

	if(prefix == "xpub"){
		extendedPublicKey = hdnode.publicExtendedKey();
	}

	let output= "<div id='outputContainer4'>";
	if(prefix == "xprv"){
		output += "Extended private key: " +extendedPrivateKey +"<br />";
	}
	output += "Extended public key: " +extendedPublicKey;

	output += "<br /><br />";
	output += "------------------------------------------------------------";
	output += "<br /><br />";

	for(let i=startIndex; i<endIndex; i++) {
		let childWallet = hdnode.deriveChild(i).getWallet();
		let publicKey = childWallet.getPublicKey().toString('hex');
		let privateKey = "";
		if(prefix == "xprv"){
			privateKey = childWallet.getPrivateKey().toString('hex');
		}

		output += "Address index: "+ i + "<br />";

		// The ethereumjs library can only generate valid addresses for Ethereum and not for other coin types.
		if(toggle) {
			let address = childWallet.getAddress().toString("hex");
			output += "Address: "+ address + "<br />";
		}
		if(prefix == "xprv"){
			output += "Private key: "+privateKey+"<br />";
		}
		output += "Public key: "+ publicKey;

		output += "<br /><br />";
	}
	output += "<"+"/div>";
	document.getElementById("outputRestore").innerHTML = output;
}