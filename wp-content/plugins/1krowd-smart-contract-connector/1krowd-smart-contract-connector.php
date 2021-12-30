<?php
/*
Plugin Name: 1Krowd Smart Contract Connector
Description: Connects this site to the 1Krowd Ethereum smart contract that is deployed in Ethereum blockchain. The smart contract offers the ability to issue ticket tokens and transfer tokens from the site to the ticket buyer and from ticket buyer to another ticket buyer.
Version: 1.0.0
Author: Melvin Wong
Author URI: https://1krowd.com
License: Copyright 1Krowd Co. Ltd.
*/
function smart_contract__connector_activate(){
	wp_register_script( 'web3-script', plugins_url( 'js/web3.min.js', __FILE__ ) );
	//wp_register_script( 'web3-script', 'https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.0.0-beta.34/dist/web3.min.js', '', '', false);
	wp_register_script( 'goals-script', plugins_url( 'js/smart-contract-connector.js', __FILE__ ) );
	
	wp_enqueue_script( 'web3-script' );
	wp_enqueue_script( 'goals-script' );
	
	//echo '<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.0.0-beta.34/dist/web3.min.js"></script>';
	
	//echo '<script type="text/javascript" src="https://raw.githubusercontent.com/ethereum/web3.js/0.16.0/dist/web3.min.js"></script>';
	
	/* echo '<script type="text/javascript" src="js/web3.min.js"></script>'; */
	//echo '<script type="text/javascript" src="js/smart-contract-connector.js"></script>';
	
	echo '<script type="text/javascript">createToken(1, "Test Title", "Test Desc");</script>';
	/* echo '<script>startApp();</script>'; */
	
	//echo '<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.0.0-beta.34/dist/web3.min.js"></script>';
	
	//echo '<script>createToken(1, "Test Title", "Test Desc");</script>';

}

register_activation_hook( __FILE__, 'smart_contract__connector_activate' );

//add_action('wp_enqueue_scripts','smart_contract__connector_activate');

?>