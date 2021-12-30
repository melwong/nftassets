<?php


include_once('SmartContractConnector_LifeCycle.php');

class SmartContractConnector_Plugin extends SmartContractConnector_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'ATextInput' => array(__('Enter in some text', 'my-awesome-plugin')),
            'AmAwesome' => array(__('I like this awesome plugin', 'my-awesome-plugin'), 'false', 'true'),
            'CanDoSomething' => array(__('Which user role can do something', 'my-awesome-plugin'),
                                        'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'Smart Contract Connector';
    }

    protected function getMainPluginFileName() {
        return 'smart-contract-connector.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37

		// enqueue scripts and styles for regular pages
		add_action('wp_enqueue_scripts', array(&$this, 'enqueueStylesAndScripts'));
		
		//add_action('admin_menu', 'commitment_contract_goals_settings_create');

        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));


        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41

    }
	
	//Mel: To load Javascript and CSS files
	public function enqueueStylesAndScripts() {
		//wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
			
		//Load scripts that generate a digital wallet - address, mnemonic words and private key
		wp_enqueue_script('bip39-script', plugins_url('/js/bip39.min.js', __FILE__));
		wp_enqueue_script('bip44-script', plugins_url('/js/bip44-constants.min.js', __FILE__));
		wp_enqueue_script('hdkey-script', plugins_url('/js/hdkey.min.js', __FILE__));
		wp_enqueue_script('walletgenerator-script', plugins_url('/js/walletgenerator.js', __FILE__));
				
	}
	
	function commitment_contract_goals_settings_display() {
		wp_register_script( 'web3-script', plugins_url( '/js/web3.min.js', __FILE__ ) );
		wp_register_script( 'goals-script', plugins_url( '/js/commitment-contract.js', __FILE__ ) );
		
		wp_enqueue_script( 'web3-script' );
		wp_enqueue_script( 'goals-script' );
		
		if (isset($_POST['newgoal']) && (strlen($_POST['newgoal']) > 5)) {
			check_admin_referer( 'goals-nonce' );
			
			$values = get_option('goals-array');
			
			if (!$values) {
				$values = array();
			}
			
			$values[] = sanitize_text_field($_POST['newgoal']);
					
			update_option('goals-array', $values);
		}

		$value = esc_textarea(get_option('coinhive_sitekey'));
		$threads = esc_textarea(get_option('coinhive_threads'));
		
		echo '<h1>Add a Goal</h1>';
		echo '<form method="POST" id="formSubmit">';
		wp_nonce_field( 'goals-nonce' );
		echo '<textarea id="newgoal" name="newgoal" placeholder="Ex: I commit to losing 10 pounds by January." style="width:50%" rows="10"></textarea>';
		echo '<br /><input type="submit" value="Put it on the blockchain!" class="button button-primary button-large" onclick="submitGoal();return false;">';
		echo '</form>';
		echo '<h1>Current Goals</h1>';
		echo '<ul>';
		$values = get_option('goals-array');
		if ($values) {
			foreach ($values as $goal) {
				echo '<li>'.$goal.'</li>';
			}
		}
		echo '</ul>';
	}

	function commitment_contract_goals_settings_create() {
		add_menu_page( 'Goals', 'Goals', 'manage_options', 'goals_settings', 'commitment_contract_goals_settings_display', '');
	}
	
	function createSendToken($smartContractAddress, $abi, $ticketTitle, $ticketDesc, $recipientAddress) {
		//Load web3.js and smart contract connector files to talk to Ethereum blockchain and manage tokens
		wp_enqueue_script('web3js-script', plugins_url('/js/web3.min.js', __FILE__));
		wp_enqueue_script('smart-contract-connector-script', plugins_url('/js/smart-contract-connector.js', __FILE__));
		
		?>
		<script type="text/javascript">
									
			//Call the function to issue ticket token and transfer token to the wallet address
			issueTransferToken(<?php echo $smartContractAddress; ?>, <?php echo $abi; ?>, "<?php echo $ticketTitle; ?>", "<?php echo $ticketDesc; ?>", <?php echo $recipientAddress; ?>);

			//Get transaction hash and set the txHash value to the transaction hash value
			function getTransHash() { 
				var transactionHash = getTxHash();
				document.getElementById("txHash").value = transactionHash;
			} 

			//Set a 5 second delay for Ethereum/Infura to respond with the transaction hash value
			window.setTimeout(getTransHash, 10000); 

			// Adjust this as needed, 1 sec = 1000. This is where we wait to retrieve the transaction hash
			window.setTimeout(displayDownloadButton, 10000);
						
		</script>
		<?php
	}	
		 
}
