<?php


function load_metamask() {
	echo '<script src="https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js"></script>';
	echo "<script>							var web3;
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

									console.log('Send from: ' + walletAddress);
									console.log('Send to: ' + toAddress);								
									
									web3.eth.sendTransaction({
										from: walletAddress,
										to: toAddress,
										value: web3.utils.toWei('0.001', 'ether')
									}, function (error, result) {
										if (error) {
											console.log(error);
											
										} else {
											window.location.href = document.location.origin + '/checkout/order-received/';

										}
										
									});
									
								} else {
									
								}
							});</script>";
	
}
//add_action( 'woocommerce_thankyou', 'load_metamask');

//Mel: 30/12/21
// Adding Meta container admin shop_order pages to send NFT
add_action( 'add_meta_boxes', 'mv_add_meta_boxes' );
function mv_add_meta_boxes() {
	add_meta_box( 'mv_other_fields', __('Send NFT','woocommerce'), 'mv_add_other_fields_for_packaging', 'shop_order', 'side', 'core' );
}

// Adding Meta container admin shop_order pages to send NFT
function mv_add_other_fields_for_packaging() {
        global $post;

		$recipient_wallet = get_post_meta( $post->ID, 'wallet_address', true );
		
		echo '<label>User Wallet</label><br /><input type="text" id="wallet" name="user_wallet" value="' . $recipient_wallet . '" />';
		echo '<a class="button" href="javascript:;" id="sendToken">Send NFT Token</a><div id="loading3"></div>';
		
		wp_enqueue_script( 'web3js', get_template_directory_uri() . '/js/web3.min.js', array (), null, false);
		
		wp_enqueue_script( 'web3-metamask', get_template_directory_uri() . '/js/web3-metamask.js', array (), null, false);

}

//Mel: 30/12/21. Add a new currency
/**
 * Custom currency and currency symbol
 */
add_filter( 'woocommerce_currencies', 'add_my_currency' );

function add_my_currency( $currencies ) {
     $currencies['METIS'] = __( 'MetisDAO', 'woocommerce' );
     return $currencies;
}

add_filter('woocommerce_currency_symbol', 'add_my_currency_symbol', 10, 2);

function add_my_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
          case 'METIS': $currency_symbol = 'METIS'; break;
     }
     return $currency_symbol;
}

//Mel: 13/08/19. To enable the features below. Ref: https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox) 
add_theme_support( 'wc-product-gallery-zoom' );
//add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

//Mel: 10/12/19. To disable persisten cart caching
add_filter( 'woocommerce_persistent_cart_enabled', '__return_false' );

//Mel: 07/09/19. To allow duplicated SKU ID since we are using SKU field as Token ID field
add_filter( 'wc_product_has_unique_sku', '__return_false' ); 

//Mel: Begin. All commented functions on top (starting below) are not used or properly tested. Only use those live functions below them

//To add the buttons to link to respective wallets to retrieve tickets
/* function onekrowd_add_wallet_buttons() {
  global $product;

  echo "<br /><a href='". get_permalink() . "?wallet=metamask' ><img src='" . get_template_directory_uri() . "/images/connect-with-metamask.png' width='200'></a>";
  
}
add_action( 'woocommerce_single_product_summary', 'onekrowd_add_wallet_buttons', 30 );
 */

 //This function retrieves the download URL from the WooCommerce product that contains a downloadable file. Example of a download URL is http://localhost/boothstand/?download_file=49&order=wc_order_p7lmIgVkBIO1H&email=sun482%40yahoo.com&key=f1228c67-a255-41e4-898d-3bc8bcfccdf1 
 
/**
 * Add search for only private events which are actually the main or anchor events that are created by admin where other resellers could sell the tickets for
 */
/* function onekrowd_search_private_events($query){
 
    if ( !is_user_logged_in() && is_search() ) {
        $query->set('post_status', array( 'private' ) );
    }
} 
add_action('pre_get_posts', 'onekrowd_search_private_events');
 */
//To find for the events to retrieve the actual tickets
/* function woo_custom_product_searchform( $form ) {
    $form = '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">
    <div>
      <label class="screen-reader-text" for="s">' . __( 'Search for:', 'woocommerce' ) . '</label>
      <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . __( 'My Search form', 'woocommerce' ) . '" />
      <input type="submit" id="searchsubmit" value="'. esc_attr__( 'Search', 'woocommerce' ) .'" />
      <input type="hidden" name="post_type" value="product" />
    </div>
  </form>';
    return $form;
}
add_filter( 'get_product_search_form' , 'woo_custom_product_searchform' );
   */

/**
 * Show products only of selected category.
 */
/* function get_subcategory_terms( $terms, $taxonomies, $args ) {
 
	$new_terms 	= array();
	$hide_category 	= array( 15 ); // Ids of the category you don't want to display on the shop page
 	
 	  // if a product category and on the shop page
	if ( in_array( 'product_cat', $taxonomies ) && !is_admin() && is_shop() ) {

	    foreach ( $terms as $key => $term ) {

		if ( ! in_array( $term->term_id, $hide_category ) ) { 
			$new_terms[] = $term;
		}
	    }
	    $terms = $new_terms;
	}
  return $terms;
}
add_filter( 'get_terms', 'get_subcategory_terms', 10, 3 ); */

// Register Custom Post Status
/* function register_custom_post_status(){
    register_post_status( 'main', array(
        'label'                     => _x( 'Main', 'post' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Main <span class="count">(%s)</span>', 'Main <span class="count">(%s)</span>' ),
    ) );
}
add_action( 'init', 'register_custom_post_status' );

// Display Custom Post Status Option in Post Edit
function display_custom_post_status_option(){
    global $post;
    $complete = '';
    $label = '';
    if($post->post_type == 'product'){
        if($post->post_status == 'main'){
            $selected = 'selected';
        }
		echo '<script>
			$(document).ready(function(){
			$("select#post_status").append("<option value=\"main\" '.$selected.'>Main</option>");
			$(".misc-pub-section label").append("<span id=\"post-status-display\"> Main</span>");
			});
			</script>
			';
    }
}
add_action('admin_footer', 'display_custom_post_status_option'); 
   */ 

//Mel:22/06/19. Add Login and Logout menu items to primary menu so that when someone logs in using the menu link, they will be redirected to previous page before they logged-in (or logged out)
/* function wti_loginout_menu_link( $items, $args ) {
	if ($args->theme_location == 'primary') {
		if (is_user_logged_in()) {
			$items .= '<li class="right"><a href="'. wp_logout_url( get_permalink() ) .'">Logout</a></li>'; // For logout
		} else {
			$items .= '<li class="right"><a href="'. wp_login_url(get_permalink()) .'">Login</a></li>'; // For login
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );
    */

/* function onekrowd_generate_reg_wallet_fields() {
	
	echo '<div id="wallet-fields" style="visibility: hidden;">
			<input type="text" id="wallet_address" name="wallet_address" value="">
			<input type="text" id="private_key" name="private_key" value="">
			<input type="text" id="mnemonic_phrase" name="mnemonic_phrase" value="">
		</div>';
	
	//Execute generateWallet function using the wallet js scripts
	echo '<script type="text/javascript">
			var walletData = generateWallet();
			document.getElementById("wallet_address").value = walletData[0];
			document.getElementById("private_key").value = walletData[1];
			document.getElementById("mnemonic_phrase").value = walletData[2];
		</script>';
		
}
add_action( 'woocommerce_register_form', 'onekrowd_generate_reg_wallet_fields', 10 ); // register form */

//To set the min/max quantity and step (incremental) values for a product in an order
/* function jk_woocommerce_quantity_input_args( $args, $product ) {
	if ( is_singular( 'product' ) ) {
		$args['input_value'] 	= 2;	// Starting value (we only want to affect product pages, not cart)
	}
	$args['max_value'] 	= 80; 	// Maximum value
	$args['min_value'] 	= 2;   	// Minimum value
	$args['step'] 		= 2;    // Quantity steps
	return $args;
}
add_filter( 'woocommerce_quantity_input_args', 'jk_woocommerce_quantity_input_args', 10, 2 ); // Simple products
 */
//To set min/max quantity for a product for an order
/* function jk_woocommerce_available_variation( $args ) {
	$args['max_qty'] = 80; 		// Maximum value (variations)
	$args['min_qty'] = 2;   	// Minimum value (variations)
	return $args;
}
add_filter( 'woocommerce_available_variation', 'jk_woocommerce_available_variation' ); // For product variations
 */

//To adjust password strength when signing-up
/* function onekrowd_min_password_strength( $strength ) {
    return 0;
}
add_filter( 'woocommerce_min_password_strength', 'onekrowd_min_password_strength', 10, 1 ); */

//Add Login and Logout menu items to menu whose menu name (theme_location) is 'primary'
//Redirects to current page after login/logout
/* function wti_loginout_menu_link( $items, $args ) {

	if ($args->theme_location == 'primary') {
		if (is_user_logged_in()) {
			$items .= '<li class="right"><a href="'. wp_logout_url( get_permalink() ) .'">Logout</a></li>'; // For logout
		} else {
			$items .= '<li class="right"><a href="'. wp_login_url(get_permalink()) .'">Login</a></li>'; // For login
		}
	}
		return $items;
}
add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 ); */

//Mel: 10/08/19. To hide certain attributes like when user chooses seats based on section (as a product variation). Then we will auto assign a seat for them in checkout. That seating number is hidden from their view.  
/* function my_attribute_hider ( $attributes ) {
	
	if ( isset( $attributes['pa_section-a'] ) ){
		unset( $attributes['pa_section-a'] );
	}
	return $attributes;
}
add_filter( 'woocommerce_get_product_attributes', 'my_attribute_hider' );
 */

 //Shows each product variation as an item in the Child field search in the PluginRepublic WooCommerce Product Add on plugin. 
/* function prefix_search_products( $product_type, $product_id, $field_id ) {
	$product_type = 'woocommerce_json_search_products_and_variations'; // Allows you to include variations
	
	return $product_type;
}
add_filter( 'pewc_filter_child_products_method', 'prefix_search_products', 10, 3 );
  */
  
//Mel: 25/11/19. To edit account menu (left menu) to remove address and downloads menu items. Ref: https://www.atomicsmash.co.uk/blog/customising-the-woocommerce-my-account-section/
function add_my_menu_items( $items ) {
	
    $menuOrder = array(
 		'dashboard'          => __( 'Dashboard', 'woocommerce' ),
 		'orders'             => __( 'Orders', 'woocommerce' ),
 		'edit-account'    	=> __( 'Edit Account', 'woocommerce' ),
 		'customer-logout'    => __( 'Logout', 'woocommerce' ),
 	);
 	return $menuOrder;
}
add_filter( 'woocommerce_account_menu_items', 'add_my_menu_items', 99, 1 );

  
//Mel: 02/09/19. Don't display the quantity of parent product since child product displays it. Otherwise, people will think there are two separate tickets
function remove_parent_quantity_count( $product_quantity, $cart_item_key, $cart_item ){
	
	//Check to see if the item is a parent product by checking if it has a child
	if ( isset( $cart_item['product_extras']['products']['child_products'] ) )
		$product_quantity = '';

	return $product_quantity;
}
add_filter( 'woocommerce_cart_item_quantity', 'remove_parent_quantity_count', 10, 3 );
  
//Mel: 01/09/19. To replace the comma separating two product attributes with pip (from "Row A, Seat 1" to "Row A | Seat 1")
function change_attribute_separator( $excerpt ){
    $new_excerpt = str_replace(',', ' -', $excerpt);
    return $new_excerpt;
}
add_filter( 'get_the_excerpt', 'change_attribute_separator', 10, 1 );
  
//Mel: 30/08/19. Remove quantity selector from cart page
function wc_remove_quantity_field_from_cart( $return, $product ) {
	if ( is_cart() )
		return true;
}
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_quantity_field_from_cart', 10, 2 );
  
//Mel: 30/08/19. Remove product link from cart page.
function my_remove_cart_product_link() {
    return __return_null();
}
add_filter( 'woocommerce_cart_item_permalink', 'my_remove_cart_product_link', 10 );
  
//Mel: 30/08/19. To hide the View Product button below the product thumbnail at shop or related products page
function hide_pewc_view_product_button() {
	remove_filter( 'woocommerce_loop_add_to_cart_link', 'pewc_view_product_button' );
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'hide_pewc_view_product_button' );

//Mel: 31/08/19. Commented out since no longer used since we are able to remove parent product from cart (parent product usually shows $0). Error also occurs when other product without child is added into cart where their prices are not shown. Like http://1krowd1st.test/event/boxing-ryan-fury-vs-titto-william/
//Mel: 30/08/19. To hide the $0 price in cart page for parent product
function hide_woocommerce_cart_item_price( $price,  $cart_item, $cart_item_key ) {
    
	//Check if the cart item is a parent product. We only hide parent since child carries the price
	if ( isset( $cart_item['product_extras']['products']['child_products'] ) ) {
		
		$product = wc_get_product($cart_item['product_id']);

		// Don't show price when its $0
		if ( $product->get_price() == 0 ) {
			return '';
		}
	}
    return $price; 
}
add_filter('woocommerce_cart_item_price', 'hide_woocommerce_cart_item_price', 10, 3);
add_filter('woocommerce_cart_item_subtotal', 'hide_woocommerce_cart_item_price', 10, 3);
  
//Mel: 24/08/19. To remove Add to Cart buttons from products on shop and category pages
function remove_add_to_cart_buttons() {
  if( is_product_category() || is_shop()) { 
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
  }
}
//add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 10 );
  
/**
 * Change number of products that are displayed per page (shop page)
 */
function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options -> Reading
  // Return the number of products you wanna show per page.
  $cols = 20;
  return $cols;
}
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );
 
//Mel: 13/08/19. Automatically set the WooCommerce order status from "On-Hold" to "Processing" for all payment methods except cash on delivery (cod). This is to allow WooCommerce to display the thankyou.php at the end of the purchasing process so that we can download the ticket that contains the blockchain wallet. And to allow ticket reseller to transfer the ticket token to the buyer. The order status should be changed to "Completed" for primary ticket purchasing since there's no need for the ticket reseller to transfer the ticket token to the buyer.  
//Ref: https://www.tychesoftwares.com/how-to-automatically-complete-woocommerce-orders-when-they-go-to-the-processing-status/
function ts_auto_complete_by_payment_method($order_id) {
	
	global $product;
	
	if ( !$order_id ) {
		return;
	}
	
	$order = wc_get_order( $order_id );

	if ($order->data['status'] == 'on-hold') {
		$payment_method = $order->get_payment_method();
		
		if ($payment_method != "cod") {
			$order->update_status( 'processing' );
		}

	}

}
add_action('woocommerce_order_status_changed', 'ts_auto_complete_by_payment_method');


//Mel: 11/08/19. This works with the variation option where it allows you to control the number of variations to be loaded when the page loads. Useful when you want to show only seats that are available. Otherwise, it will show ALL seats regardless of the stock and when user selects one seat/variation, the site will check the backend using ajax to see if the stock/seat is available. It also WooCommerce default setting states that if there are greater than 20 variations in the product, it will use ajax to find matches and wonâ€™t hide combinations (because doing so would mean loading all variations before display). Thus, we need to change the return value to an amount of variations (like "return 200") that we want to load without using Ajax to check if the stock is available for each variation. Source: https://gist.github.com/claudiosanches/6f91ad228c2176b986b2 and https://wordpress.org/support/topic/out-of-stock-variations-still-showing/
function custom_wc_ajax_variation_threshold( $qty, $product ) {
	return 200;
}
add_filter( 'woocommerce_ajax_variation_threshold', 'custom_wc_ajax_variation_threshold', 10, 2 );

//Mel: 28/08/19. Reinstated this filter cos we've changed how variation names/options are displayed.
//Mel: 22/08/19. This is commented out cos we need to use the PluginRepublic plugin to view all the variation names/terms where the term is like the seat number.
//Mel: 08/08/19. Removing attribute values from Product variation title. Like from Bruno Mars Live - Section A, Row 1, Seat 1 as title to juut Bruno Mars Live
function variation_title_not_include_attributes( $boolean ){
    //if ( ! is_cart() )
        $boolean = false;
    return $boolean;
}
add_filter( 'woocommerce_product_variation_title_include_attributes', 'variation_title_not_include_attributes' );

//Mel: 08/08/19. Display product variation attributes label and values in separate rows
function remove_attribute_in_product_name( $boolean ){
    //if ( ! is_cart() )
        $boolean = false;
    return $boolean;
}
add_filter( 'woocommerce_is_attribute_in_product_name', 'remove_attribute_in_product_name' );

//Mel: 08/08/19. Remove the quantity from the product title in checkout page
function remove_product_variation_qty_from_title( $quantity_html, $cart_item, $cart_item_key ){
    if ( $cart_item['data']->is_type('variation') && is_checkout() )
        $quantity_html = '';

    return $quantity_html;
}
//Mel: 02/09/19. Disabled this because we need to indicate the quantity as "x 1" on the right of product title so that it matches the UI design of pluginrepublic add on plugin. See https://prnt.sc/p0ota4
//add_filter( 'woocommerce_checkout_cart_item_quantity', 'remove_product_variation_qty_from_title', 10, 3 );

//Mel: 08/08/19. Add back the cart item quantity in a separate row in checkout page
function filter_get_item_data( $item_data, $cart_item ) {

    if ( $cart_item['data']->is_type('variation') && is_checkout() )
        $item_data[] = array(
            'key'      => __('QTY'),
            'display'  => $cart_item['quantity']
        );

    return $item_data;
}
//Mel: 02/09/19. Disabled this because we need to indicate the quantity as "x 1" on the right of product title so that it matches the UI design of pluginrepublic add on plugin. See https://prnt.sc/p0ota4
//add_filter( 'woocommerce_get_item_data', 'filter_get_item_data', 10, 2 );

/**Mel: 07/08/19
 * Get the upload URL for tickets' background images (works with SSL). Used for generating PDF tickets.
 *
 * @param $param string "basedir" or "baseurl"
 * @return string
 */
function onekrowd_get_upload_dir( $param, $folder_filename = '' ) {
    $upload_dir = wp_upload_dir();
    $url = $upload_dir[ $param ];
 
    if ( $param === 'baseurl' && is_ssl() ) {
        $url = str_replace( 'http://', 'https://', $url );
    }
 
    echo $url . $folder_filename;
}
add_action( 'get_upload_dir', 'onekrowd_get_upload_dir', 10, 2 );

/*Mel: 21/07/19
* Add ticket barcode number to each order item metadata (to be stored inside wp_woocommerce_order_itemmeta table). Each item in the cart which represents a ticket shall have one unique barcode
 *
 * @param WC_Order_Item_Product $item
 * @param string                $cart_item_key
 * @param array                 $values
 * @param WC_Order              $order
 */
function onekrowd_add_qr_number_to_order_item( $item, $cart_item_key, $values, $order ) {
	
	$user_id = (int) $order->get_user_id();
	
	//Check if user is logged in. 0 means not logged in (guest)
    if ( $user_id > 0 ){
        $qr_code = generate_qr_number($user_id);
        $item->update_meta_data( '_ticket_qr', $qr_code );
    } else {
		wp_redirect( wp_login_url() );
	}	
	
}
add_action( 'woocommerce_checkout_create_order_line_item', 'onekrowd_add_qr_number_to_order_item', 10, 4 );

/**Mel: 21/07/19
 * Convert user's photo from binary to hex and then concatenate it with the ticket unique code number. The result is then encrypted using AES (symmetric)
 */
function generate_qr_number( $user_id ) {

	$upload = wp_upload_dir();
	$upload_dir = $upload['basedir'];
	$upload_dir = $upload_dir . '/ultimatemember/' . $user_id;
	$upload_dir = wp_normalize_path($upload_dir);
	 
	if ( $user_id ) {
		
		$photo_file = get_user_meta( $user_id, 'um_profile_upload_image' , true );
		 
		if ( ! empty( $photo_file ) ) {
			
			$photo_file_path = $upload_dir . '/' . $photo_file;
			
			//Pull the data from your file into a variable. Using the 'rb' tag tells it to read as binary.
			$data = fopen($photo_file_path, 'rb');

			$size = filesize($photo_file_path);

			$contents = fread($data, $size);

			fclose ($data);
			
			$photo_hex = bin2hex($contents);
			
			//Generate random string (alphabet and numbers) of 64 chars in length
			$ticket_code = bin2hex( random_bytes(32) );
			
			//Concatenate the comma to delimit the data between photo hex data and ticket code. The QR scanner app will have a comma-separated-value (CSV) data with the same comma-delimiting format thus allowing it to match the data
			$final_code = $photo_hex . "," . $ticket_code;
			
			//DEBUG
			//file_put_contents('C:/laragon/www/1krowd1st/wp-content/debug.log', 'Ticket number: ' . $ticket_code . "\n", FILE_APPEND);
			//file_put_contents('C:/laragon/www/1krowd1st/wp-content/debug.log', 'QR code with photo and ticket number: ' . $final_code . "\n", FILE_APPEND);
			
			//This is the symmetric encryption key. We need to provide this key to the person developing the QR scanner app so that they can decrypt the data in the QR code
			$key = 'Yq3t6w9z$C&F)J@NcRfTjWnZr4u7x!A%';
			
			$final_code = bin2hex( encrypt($final_code, $key) );
			
			//DEBUG
			//file_put_contents('C:/laragon/www/1krowd1st/wp-content/debug.log', 'Encrypted QR code: ' . $final_code . "\n", FILE_APPEND);

			return $final_code;

		}
	}
}

/**Mel: 21/07/19
 * Encrypts (but does not authenticate) the message (the ticket barcode) via symmetric encryption using AES
 * 
 * @param string $message - plaintext message
 * @param string $key - encryption key (raw binary expected)
 * @param boolean $encode - set to TRUE to return a base64-encoded 
 * @return string (raw binary)
 */
function encrypt($message, $key, $encode = false) {
	
	$encryption_method = 'aes-256-ctr';
	
	// Compress the message
	$message = gzencode($message, 9);
	
	$nonceSize = openssl_cipher_iv_length($encryption_method);
	$nonce = openssl_random_pseudo_bytes($nonceSize);

	$ciphertext = openssl_encrypt(
		$message,
		$encryption_method,
		$key,
		OPENSSL_RAW_DATA,
		$nonce
	);

	// Now let's pack the IV and the ciphertext together
	// Naively, we can just concatenate
	if ($encode) {
		return base64_encode($nonce.$ciphertext);
	}
	return $nonce.$ciphertext;
}

//Mel: 20/07/19. Not being used since we opt for symmetric encryption using AES
//To generate the public and private key pairs for assymmetric encryption that are required to encrypt and decrypt the ticket code
function generate_public_private_keys() {
	
	$privateKey = openssl_pkey_new(array(
		'private_key_bits' => 1024,
		'private_key_type' => OPENSSL_KEYTYPE_RSA,
		 ));

	// write private key to file
	openssl_pkey_export_to_file($privateKey, 'private.key');

	// generate public key from private key
	$publicKey = openssl_pkey_get_details($privateKey);

	// write public key to file
	file_put_contents('public.key', $publicKey['key']);

	// clear key
	openssl_free_key($privateKey);

}

//Mel: 20/07/19. Not being used since we opt for symmetric encryption using AES
//To encrypt the ticket barcode
function encrypt_code($ticket_code) {
	
	// Compress the ticket code
	$ticket_code = gzcompress($ticket_code);
	 
	// Get the public Key
	//$folder = $_SERVER['DOCUMENT_ROOT'] . '/1krowd1st/public.key';
	$publicKey = openssl_pkey_get_public(file_get_contents('public.key'));
	$a_key = openssl_pkey_get_details($publicKey);
	
	// Encrypt the data in small chunks and then combine and send it.
	$chunkSize = ceil($a_key['bits'] / 8) - 11;
	$output = '';
	 
	while ($ticket_code)
	{
		$chunk = substr($ticket_code, 0, $chunkSize);
		$ticket_code = substr($ticket_code, $chunkSize);
		$encrypted = '';
		if (!openssl_public_encrypt($chunk, $encrypted, $publicKey))
		{
			die('Failed to encrypt data');
		}
		$output .= $encrypted;
	}
	openssl_free_key($publicKey);
	 
	// This is the final encrypted data
	return $output;

}


//Mel: 17/07/19. To ensure user only uses one registration/login page to access the site. Which is the ultimate member plugin reg/login form. 
function custom_woocommerce_login_redirect_to_checkout_page() {

  //If user is not logged in and tries to go to checkout, it redirects to the login page and then redirects them back to checkout page. To make ir work in localhost, you need to change the url to /your_folder/login/?redirect_to=
  if ( !is_user_logged_in() && is_checkout() )
	  wp_redirect('/login/?redirect_to=' . $_SERVER["REQUEST_URI"]);
  
    // Case 1: If user is not logged in and tries to go to checkout, it redirects to a page with [woocommerce_my_account] shortcode. 
	/* if ( !is_user_logged_in() && is_checkout() )
        wp_redirect( get_permalink( get_page_by_path('my-account') ) ); */

  //Case 2: If user logs in or register in this page, the usual my account page would be displayed in this page, however we redirect users who are logged in and are in this page to checkout.
/*   if ( is_page('my-account') ) {
    if( is_user_logged_in() || WC()->cart->is_empty() ) {
        wp_redirect( get_permalink( get_page_by_path('checkout') ) );
	}
  } */
}
//Mel: 10/12/19. Disabled so that customers will not be redirected to login page when clicking "Buy" instead can reg an account in the checkout process
//add_action( 'template_redirect', 'custom_woocommerce_login_redirect_to_checkout_page' );

//To produce a search result where each of the product contains the '?get=tickets" to retrieve the tickets. Works with the template myticket/woocommerce/content-product-search.php. We actually dont need this function below. Just that if we don't turn it on, the search result layout will be messed up (thumnail shrinks). See https://nimb.ws/4zSa6A
function onekrowd_before_shop_loop_item() {
	global $product;

	$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

	echo '<a href="' . esc_url( $link ) . '?get=tickets" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
		
}
add_action('woocommerce_before_shop_loop_item_search', 'onekrowd_before_shop_loop_item', 10);

//To produce a search result where each of the product contains the '?get=tickets" to retrieve the tickets. Works with the template myticket/woocommerce/content-product-search.php.
function onekrowd_after_shop_loop_item() {
	global $product;

	$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
	
	echo '<a href="' . esc_url( $link ) . '?get=tickets" class="button">' . esc_html__('Retrieve Tickets', 'myticket') . '</a>';

}
add_action('woocommerce_after_shop_loop_item_search', 'onekrowd_after_shop_loop_item', 10); 

//To remove some tabs on product page, especially those created by WC Marketplace
function onekrowd_remove_product_tabs( $tabs ) {
   unset( $tabs['wcmp_customer_qna'] );    // FAQ
   unset( $tabs['vendor'] );              // Vendor
   unset( $tabs['additional_information'] ); //The useless Additional Information tab
   return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'onekrowd_remove_product_tabs', 99 );

/**
 * Remove the password strength meter feature when user is registering during checkout
 */
function onekrowd_deactivate_pass_strength_meter() {
 
	wp_dequeue_script( 'wc-password-strength-meter' );
 
}
add_action( 'wp_enqueue_scripts', 'onekrowd_deactivate_pass_strength_meter', 10 );
  
/**
 * Code Function:  Hides Woocommerce Billing Address During Checkout
 * Reference      https://www.smartwpfix.com
 * Author         Woocommerce Support @ smartwpfix.com
 * Woocommerce Compatibility:    Tested up-to version 3.4.4
 * This code only hides the billing address if only the items added into the cart include a virtual item. 
 */
function smartwpfix_remove_billing_woo_virtual( $fields ) {
     
    $only_virtual = true;
     
    foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        // Perform a check if the cart contains virtual items 
        if ( ! $cart_item['data']->is_virtual() ) $only_virtual = false; 
    }
     
    if( $only_virtual ) {
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_country']);
        unset($fields['billing']['billing_state']);
        //unset($fields['billing']['billing_phone']);
    }
     
    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'smartwpfix_remove_billing_woo_virtual' );
 
//Mel: 30/06/19. To redirect customer directly to checkout page instead of cart page
function onekrowd_redirect_add_to_cart() {
	
    global $woocommerce;
    $cw_redirect_url_checkout = $woocommerce->cart->get_checkout_url();
    return $cw_redirect_url_checkout;
	
}
//Mel: 10/12/19. Disabled. 
//add_filter('add_to_cart_redirect', 'onekrowd_redirect_add_to_cart');

//Mel: 30/60/19. NOTE: Doesn't really work. Try using translation instead. Rename the Add to Cart button
/* function onekrowd_rename_cart_button() {
	
    return __( 'Go To Checkout', 'woocommerce' );
	
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'onekrowd_rename_cart_button' );
add_filter( 'woocommerce_product_add_to_cart_text', 'onekrowd_rename_cart_button' ); */

//Mel: 27/06/19. To add wallet address field at checkout page.
function onekrowd_user_wallet_fields( $checkout ) {
	
	echo '<div id="wallet-fields" style="visibility: hidden;">';
	
	//If it's an existing user who already has a wallet
	if ( is_user_logged_in() ) {
		
		$user = wp_get_current_user();
		$user_id = $user->ID;
		$wallet_address = get_user_meta( $user_id, 'wallet_address', true );
		
		woocommerce_form_field( 'wallet_address', array(
			'type'          => 'text',
			), $wallet_address );
		
	} else {
		
		 woocommerce_form_field( 'wallet_address', array(
			  'type'          => 'text',
			), $checkout->get_value( 'wallet_address' ) );
			
		woocommerce_form_field( 'private_key', array(
			  'type'          => 'text',
			), $checkout->get_value( 'private_key' ) );
		
		woocommerce_form_field( 'mnemonic_phrase', array(
			  'type'          => 'text',
			), $checkout->get_value( 'mnemonic_phrase' ) );
		
		//Generate a new wallet for the user who is going to register
		echo '<script type="text/javascript">
		
				var walletData = generateWallet();
				document.getElementById("wallet_address").value = walletData[0];
				document.getElementById("private_key").value = walletData[1];
				document.getElementById("mnemonic_phrase").value = walletData[2];

			</script>'; 
		
	}
	
	echo '</div>';
	

}
add_action('woocommerce_after_order_notes', 'onekrowd_user_wallet_fields');

//Mel: 27/06/19. To update wp_postmeta table with user wallet address. One wallet for one registered user
function onekrowd_checkout_wallet_address_meta( $order_id ) {
	if ( !empty($_POST['wallet_address']) ) update_post_meta( $order_id, 'wallet_address', esc_attr($_POST['wallet_address']));
}
add_action('woocommerce_checkout_update_order_meta', 'onekrowd_checkout_wallet_address_meta');
 
//Mel: 25/06/19. To check the data entered by user. For security reason
function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//Mel: 25/06/19. To replace default WP login page with Ultimate Member login page. This means if the auth_redirect() function is called, it will redirect them to this new login page too
function onekrowd_um_login_url( $link ) {
  /*whatever you need to do with the link*/
  return get_site_url() . '/login';
}
add_filter( 'login_url', 'onekrowd_um_login_url');

//Mel. 24/06/19. To add the wallet fields from Ultimate Member registration form and checkout form into wp_usermeta table for the newly registered user
function onekrowd_save_wallet_fields($user_id) {
	
    update_usermeta( $user_id, 'wallet_address', $_POST['wallet_address'] );
    update_usermeta( $user_id, 'private_key', $_POST['private_key'] );
    update_usermeta( $user_id, 'mnemonic_phrase', $_POST['mnemonic_phrase'] );
}
add_action( 'user_register', 'onekrowd_save_wallet_fields', 10, 1 );

//Mel: 24/06/19. To add the wallet fields to Ultimate Member registration form (address, private key and seed phrase)
function onekrowd_add_wallet_fields() {
	echo '<input type="hidden" name="wallet_address" id="wallet_address" value="" />';
	echo '<input type="hidden" name="private_key" id="private_key" value="" />';
	echo '<input type="hidden" name="mnemonic_phrase" id="mnemonic_phrase" value="" />';
	
	//Execute generateWallet function using the wallet js scripts
	echo '<script type="text/javascript">
			var walletData = generateWallet();
			document.getElementById("wallet_address").value = walletData[0];
			document.getElementById("private_key").value = walletData[1];
			document.getElementById("mnemonic_phrase").value = walletData[2];
		</script>';
}
add_action('um_after_register_fields', 'onekrowd_add_wallet_fields');
	
//Mel: 24/06/19. To stop Wordpress from guessing or auto completing a URL and redirect it to there. Example if I enter go.com/bruno-mars, it will redirect to go.com/bruno-mars-2 since go.com/bruno-mars is not available. This function prevents it from happening.
function onekrowd_no_redirect_404($redirect_url)
{
    if (is_404()) {
        return false;
    }
    return $redirect_url;
}
add_filter('redirect_canonical', 'onekrowd_no_redirect_404');	
	
//Mel:22/06/19 Allow subscribers/customers/vendors to see private products because we hide these products/events from the public (visibility=private, catalog visibility=hidden). Public should only be alble to view the products/events that vendors are selling. We need to hide these because we create our parent products/events as private so that vendor could add their own products/events and buyers (customers) could go through the check-in process by searching for the parent product and then begin to retrieve the ticket. 
function allow_viewing_private_products(){
	
	//Allow subscriber to read
	$subRole = get_role( 'subscriber' );
	$subRole->add_cap( 'read_private_products' );
	
	//Allow WooCommerce customer to read
	$custRole = get_role( 'customer' );
	$custRole->add_cap( 'read_private_products' );
	
	//Allow vendor aka ticket reseller to read
	$vendRole = get_role( 'dc_vendor' );
	$vendRole->add_cap( 'read_private_products' );

}
add_action( 'init', 'allow_viewing_private_products' );

//Mel: To get the ticket URL to download the actual ticket PDF that was uploaded by reseller. This function is used when trying to retrieve the ticket (check-in)   
function get_ticket_url($ticket_count, $product_id, $token_id, $email_address, $wallet_address, $private_key) {
	
	global $wpdb;
	
	//To remove the 0x from the beginning of private key, such as 0x95eea6dbcc4f953622c08e2f6d169fd9694c9b7762bf4f7a712104049ffcdc9e
	$private_key = substr($private_key, 2, strlen($private_key));
	
/* 	echo "parent: " . $product_id;
	echo "<br />";
	echo "tokenid: " . $token_id;
	echo "<br />";
	echo "email: " . $email_address;
	echo "<br />";
	echo "priv key: " . $private_key;
	echo "<br />";
	echo "wallet: " . $wallet_address;
	echo "<br />"; */
	
	$query = $wpdb->prepare("SELECT DISTINCT b.product_id, b.download_id, b.order_key, b.user_email FROM ".$wpdb->prefix."postmeta a, ".$wpdb->prefix."woocommerce_downloadable_product_permissions b, ".$wpdb->prefix."users c, ".$wpdb->prefix."posts f, (SELECT * FROM ".$wpdb->prefix."postmeta a WHERE a.meta_key='_sku' AND a.meta_value='%s') as tokens, (SELECT * FROM ".$wpdb->prefix."usermeta b WHERE b.meta_key='private_key' AND b.meta_value='%s') as wallet, (SELECT product_id FROM ".$wpdb->prefix."wcmp_products_map WHERE product_map_id IN (SELECT product_map_id FROM ".$wpdb->prefix."wcmp_products_map where product_id=%d)) AS product_maps WHERE tokens.post_id=a.post_id AND tokens.post_id=f.ID AND f.ID=a.post_id AND f.post_parent=product_maps.product_id AND c.user_email COLLATE utf8mb4_unicode_ci=b.user_email COLLATE utf8mb4_unicode_ci AND wallet.user_id=c.ID AND b.product_id=a.post_id AND b.product_id=tokens.post_id AND b.product_id=f.ID AND c.user_email='%s' AND a.meta_key='_sku' AND a.meta_value='%s'", $token_id, $private_key, $product_id, $email_address, $token_id );
	
	//$query = $wpdb->prepare("SELECT DISTINCT b.product_id, b.download_id, b.order_key, b.user_email FROM ".$wpdb->prefix."postmeta a, ".$wpdb->prefix."woocommerce_downloadable_product_permissions b, ".$wpdb->prefix."users c, ".$wpdb->prefix."posts f, (SELECT * FROM ".$wpdb->prefix."postmeta a WHERE a.meta_key='_sku' AND a.meta_value='%s') as tokens, (SELECT * FROM ".$wpdb->prefix."usermeta b WHERE b.meta_key='private_key' AND b.meta_value='%s') as wallet, (SELECT * FROM ".$wpdb->prefix."posts c WHERE c.post_parent=%d AND c.post_status='publish') as variation, (SELECT order_id FROM ".$wpdb->prefix."woocommerce_order_items d, ".$wpdb->prefix."woocommerce_order_itemmeta e WHERE d.order_item_id=e.order_item_id AND e.meta_key='_product_id' AND e.meta_value=%d) as ordered WHERE tokens.post_id=a.post_id AND tokens.post_id=variation.ID AND tokens.post_id=f.ID AND variation.ID=a.post_id AND f.ID=a.post_id AND f.ID=variation.ID AND c.user_email COLLATE utf8mb4_unicode_ci=b.user_email COLLATE utf8mb4_unicode_ci AND wallet.user_id=c.ID AND ordered.order_id=b.order_id AND b.product_id=a.post_id AND b.product_id=tokens.post_id AND b.product_id=variation.ID AND b.product_id=f.ID AND c.user_email='%s' AND a.meta_key='_sku' AND a.meta_value='%s'", $token_id, $private_key, $product_id, $product_id, $email_address, $token_id );
	
	//$query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."postmeta a, ".$wpdb->prefix."woocommerce_downloadable_product_permissions b, ".$wpdb->prefix."users c, (SELECT * FROM ".$wpdb->prefix."postmeta c WHERE c.meta_key='_sku' AND c.meta_value='%s') as tokens, (SELECT * FROM ".$wpdb->prefix."usermeta e WHERE e.meta_key='private_key' AND e.meta_value='%s') as wallet, (SELECT product_id FROM ".$wpdb->prefix."wcmp_products_map WHERE product_map_id IN (SELECT product_map_id FROM ".$wpdb->prefix."wcmp_products_map where product_id=%d)) AS product_maps WHERE product_maps.product_id=a.post_id AND tokens.post_id=a.post_id AND b.product_id=tokens.post_id AND b.product_id=a.post_id AND b.product_id=product_maps.product_id AND c.user_email COLLATE utf8mb4_unicode_ci = b.user_email COLLATE utf8mb4_unicode_ci AND wallet.user_id=c.ID AND c.user_email='%s' AND a.meta_key='_sku' AND a.meta_value='%s'", $token_id, $private_key, $product_id, $email_address, $token_id);
	
	//echo "query: " . $query;
	//echo "<br />";
	
	//$query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."postmeta a, ".$wpdb->prefix."woocommerce_downloadable_product_permissions b, (SELECT * FROM ".$wpdb->prefix."postmeta c WHERE c.meta_key='_sku' AND c.meta_value='%s') as tokens, (SELECT product_id FROM ".$wpdb->prefix."wcmp_products_map WHERE product_map_id IN (SELECT product_map_id FROM ".$wpdb->prefix."wcmp_products_map where product_id=%d)) AS product_maps WHERE product_maps.product_id=a.post_id AND tokens.post_id=a.post_id AND b.product_id=tokens.post_id AND b.product_id=a.post_id AND b.product_id=product_maps.product_id AND a.meta_key='_sku' AND a.meta_value='%s'", $token_id, $product_id, $token_id);	
	
	//Return one row only, containing the download URL parameters
	$result = $wpdb->get_row($query);

	if ($result) {
		
		//Read the respective columns to get the download URL parameters
		$product_id = $result->product_id;
		$download_id = $result->download_id;
		$order_key = $result->order_key;
		$user_email = $result->user_email;
		
		$download_url = get_site_url() . "/?download_file=".$product_id."&order=".$order_key."&email=".$user_email."&key=".$download_id;
		
		//echo "Download link: " . $download_url;
		//echo "br />";
		
		//Redirect to download URL so that the PDF download popup will appear on user's browser
		//wp_redirect( $download_url );
		//exit;
		echo '<section class="section-page-content">
					<div class="row">                   
						<div id="primary" class="col-sm-12 col-md-12">
							<div class="section-download-ticket">
								<p>' . esc_html__('Click on the button below to download your ticket', 'myticket') . '</p>';
								
		if ($ticket_count != 0) {
			echo '<p><a href="' . $download_url . '" target="_blank"><i class="fa fa-download"></i> ' . sprintf( esc_html__('Download Ticket %d', 'myticket'), $ticket_count ) .  '</a></p>';
		} else {
			echo '<p><a href="' . $download_url . '" target="_blank"><i class="fa fa-download"></i> ' . esc_html__('Download Your Ticket', 'myticket') . '</a></p>';
		}
								
		echo '				</div>
						</div>
					</div>
			</section>';
	
	} else {
		
		echo '<section class="section-page-content">
					<div class="row">                   
						<div id="primary" class="col-sm-12 col-md-12">
							<div class="section-download-ticket">
								<p>' . esc_html__('No Ticket File Available', 'myticket') . '</p>
							</div>
							<div class="core-quote">
								<p>' . esc_html__('Ensure you selected the right event, right private key and logged-in using the correct account.', 'myticket') . '</p>
							</div>
						</div>
					</div>
			</section>';
	
	}
}
add_action('onekrowd_get_ticket_url', 'get_ticket_url', 10, 6);
 
//To use Web3js to connect to Ethereum blockchain to issue ticket token and then delivery that token to the recipient wallet address  
function create_send_token($smart_contract_address, $abi, $ticket_title, $ticket_desc, $recipient_address) {
	
	?>
	<div id="txHash"></div>
	
	<script type="text/javascript">
								
		//Call the function to issue ticket token and transfer token to the wallet address
		createTransferToken('<?php echo $smart_contract_address; ?>', <?php echo $abi; ?>, '<?php echo $ticket_title; ?>', '<?php echo $ticket_desc; ?>', '<?php echo $recipient_address; ?>');

		//Get transaction hash and set the txHash value to the transaction hash value
		function getTransHash() { 
			var transactionHash = getTxHash();
			document.getElementById("txHash").innerHtml = transactionHash;
		} 

		//Set a 5 second delay for Ethereum/Infura to respond with the transaction hash value
		window.setTimeout(getTransHash, 10000); 

	</script>
	<?php
}
add_action('onekrowd_create_send_token', 'create_send_token', 10, 5);

//To ensure the wallet js scripts are located inside <head>
function onekrowd_load_wallet_scripts() {
	//Load scripts that generate a digital wallet - address, mnemonic words and private key
	wp_enqueue_script( 'bip39', get_template_directory_uri() . '/js/bip39.min.js', array (), null, false);
	wp_enqueue_script( 'bip44-constants', get_template_directory_uri() . '/js/bip44-constants.min.js', array (), null, false);
	wp_enqueue_script( 'hdkey', get_template_directory_uri() . '/js/hdkey.min.js', array (), null, false);
	wp_enqueue_script( 'walletgenerator', get_template_directory_uri() . '/js/walletgenerator.js', array (),null, false);
	
	//Load web3.js and smart contract connector files to talk to Ethereum blockchain and manage tokens
	wp_enqueue_script( 'web3js', get_template_directory_uri() . '/js/web3.min.js', array (), null, false);
	wp_enqueue_script( 'smart-contract-connector', get_template_directory_uri() . '/js/smart-contract-connector.js', array (), null, false);
}
add_action('wp_enqueue_scripts', 'onekrowd_load_wallet_scripts');

//To dynamically add a wallet data for each product item added to cart. E.g: if I select 2 tickets for Bruno Mars concert, the site will generate 2 wallets
//NOTE: NOT THOROUGHLY TESTED
function woocommerce_total_product_price() {
    global $woocommerce, $product;
	
	echo '<div id="wallet-fields" style="visibility: hidden;">';
	
    // let's setup our divs
    echo sprintf('<div id="product_total_price" style="margin-bottom:20px;display:none">%s %s</div>',__('Product Total:','woocommerce'),'<span class="price">'.$product->get_price().'</span>');
    echo sprintf('<div id="cart_total_price" style="margin-bottom:20px;display:none">%s %s</div>',__('Cart Total:','woocommerce'),'<span class="price">'.$product->get_price().'</span>');
    ?>
        <script>
            jQuery(function($){
                var price = <?php echo $product->get_price(); ?>,
                    current_cart_total = <?php echo $woocommerce->cart->cart_contents_total; ?>,
                    currency = '<?php echo get_woocommerce_currency_symbol(); ?>';
 
                $('[name=quantity]').change(function(){
                    if (!(this.value < 1)) {
						
						
						var product_total = parseFloat(price * this.value),
                        cart_total = parseFloat(product_total + current_cart_total);
 
                        $('#product_total_price .price').html( currency + product_total.toFixed(2));
                        $('#cart_total_price .price').html( currency + cart_total.toFixed(2));
						
						var i;
						var output = '';
						
						for (i = 1; i <= this.value; i++) { 
							output += '<input type="hidden" id="wallet_address' + i + '" name="wallet_address' + i + '" value="">';
							output += '<input type="hidden" id="private_key' + i + '" name="private_key' + i + '" value="">';
							output += '<input type="hidden" id="mnemonic_phrase' + i + '" name="mnemonic_phrase' + i + '" value="">';
						}
						$('#wallet-fields').html(output);
						
						for (i = 1; i <= this.value; i++) { 
							var walletData = generateWallet();
							$('#wallet_address' + i).attr('value', walletData[0]);
							$('#private_key' + i).attr('value', walletData[0]);
							$('#mnemonic_phrase' + i).attr('value', walletData[0]);
						}
						
                    }
                    $('#product_total_price,#cart_total_price').toggle(!(this.value <= 1));
 
                });
            });
        </script>
    <?php
}

//we are going to hook this on priority 31, so that it would display below add to cart button.
//add_action( 'woocommerce_single_product_summary', 'woocommerce_total_product_price', 31 );

/**
 * Output wallet fields on the product page before pressing the "Add to Cart" button. Generate one wallet for each item in the cart. Meaning if we purchase two tickets for same event, one wallet will be issued. But if we purchase two tickets for two events, two wallets will be issued.
 */
function onekrowd_generate_wallet_fields() {
	
	echo '<div id="wallet-fields" style="visibility: hidden;">
			<input type="text" id="wallet_address" name="wallet_address" value="">
			<input type="text" id="private_key" name="private_key" value="">
			<input type="text" id="mnemonic_phrase" name="mnemonic_phrase" value="">
		</div>';
	
	//Execute generateWallet function using the wallet js scripts
	echo '<script type="text/javascript">
			var walletData = generateWallet();
			document.getElementById("wallet_address").value = walletData[0];
			document.getElementById("private_key").value = walletData[1];
			document.getElementById("mnemonic_phrase").value = walletData[2];
		</script>';
		
}
//add_action( 'woocommerce_before_add_to_cart_button', 'onekrowd_generate_wallet_fields', 10 );

//Add each wallet data for each item in the cart. Each item in the cart shall have one wallet
function onekrowd_add_wallet_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
	
	$wallet_address = filter_input( INPUT_POST, 'wallet_address' );
	$private_key = filter_input( INPUT_POST, 'private_key' );
	$mnemonic_phrase = filter_input( INPUT_POST, 'mnemonic_phrase' );

	if ( empty( $wallet_address ) ) {
		return $cart_item_data;
	}

	$cart_item_data['wallet_address'] = $wallet_address;
	$cart_item_data['private_key'] = $private_key;
	$cart_item_data['mnemonic_phrase'] = $mnemonic_phrase;

	return $cart_item_data;
}
//add_filter( 'woocommerce_add_cart_item_data', 'onekrowd_add_wallet_to_cart_item', 10, 3 );

//Add wallet data and fields in the checkout page. One wallet for entire order items in the cart
/* function cmk_additional_button() {

	$items_in_cart = WC()->cart->get_cart();
	
	$counter = 1;
	
	foreach ( $items_in_cart as $value ) {
	   
	   echo '<div id="wallet-fields'.$counter.'" style="visibility: hidden;">';
	
		 woocommerce_form_field( 'wallet_address'.$counter, array(
		  'type'          => 'text',
		), $checkout->get_value( 'wallet_address'.$counter ) );
		
		woocommerce_form_field( 'private_key'.$counter, array(
		  'type'          => 'text',
		), $checkout->get_value( 'private_key'.$counter ) );
		
		woocommerce_form_field( 'mnemonic_phrase'.$counter, array(
		  'type'          => 'text',
		), $checkout->get_value( 'mnemonic_phrase'.$counter ) );
		
		echo '</div>';
		
		//Execute generateWallet function using the wallet js scripts
		echo '<script type="text/javascript">
			
				var walletData = generateWallet();
				document.getElementById("wallet_address").value = walletData[0];
				document.getElementById("private_key").value = walletData[1];
				document.getElementById("mnemonic_phrase").value = walletData[2];

			</script>';
			
		$counter++;
	}
} */
//add_action('woocommerce_after_add_to_cart_button','cmk_additional_button'); 


//To generate wallet data and fields at checkout page. One wallet for entire order items in the cart
function onekrowd_wallet_fields( $checkout ) {
	
	echo '<div id="wallet-fields" style="visibility: hidden;">';
	
	 woocommerce_form_field( 'wallet_address', array(
      'type'          => 'text',
    ), $checkout->get_value( 'wallet_address' ) );
	
	woocommerce_form_field( 'private_key', array(
      'type'          => 'text',
    ), $checkout->get_value( 'private_key' ) );
	
	woocommerce_form_field( 'mnemonic_phrase', array(
      'type'          => 'text',
    ), $checkout->get_value( 'mnemonic_phrase' ) );
	
	echo '</div>';
	
	//Execute generateWallet function using the wallet js scripts
	echo '<script type="text/javascript">
		
			var walletData = generateWallet();
			document.getElementById("wallet_address").value = walletData[0];
			document.getElementById("private_key").value = walletData[1];
			document.getElementById("mnemonic_phrase").value = walletData[2];

		</script>';
}
//add_action('woocommerce_after_order_notes', 'onekrowd_wallet_fields');

//To update wp_postmeta table with wallet custom metadata below for the order. One wallet for one order regardless of the order items
function onekrowd_checkout_order_meta( $order_id ) {
    if ($_POST['wallet_address']) update_post_meta( $order_id, 'wallet_address', esc_attr($_POST['wallet_address']));
	if ($_POST['private_key']) update_post_meta( $order_id, 'private_key', esc_attr($_POST['private_key']));
	if ($_POST['mnemonic_phrase']) update_post_meta( $order_id, 'mnemonic_phrase', esc_attr($_POST['mnemonic_phrase']));
}
//add_action('woocommerce_checkout_update_order_meta', 'onekrowd_checkout_order_meta');

// Save custom data to order item meta data. One wallet for one order regardless of the order items
/* function onekrowd_add_meta_to_line_item( $item, $cart_item_key, $values, $order) {
  if ($_POST['wallet_address']) $item->add_meta_data( 'wallet_address', esc_attr($_POST['wallet_address'], true ));
  if ($_POST['private_key']) $item->add_meta_data( 'private_key', esc_attr($_POST['private_key'], true ));
  if ($_POST['mnemonic_phrase']) $item->add_meta_data( 'mnemonic_phrase', esc_attr($_POST['mnemonic_phrase'], true ));
}
add_action( 'woocommerce_checkout_create_order_line_item', 'onekrowd_add_meta_to_line_item', 10, 4 );
 */
//Mel: End

/**
 * myticket functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package myticket
 */

class myticket {

	static function load_fonts(){

		$fonts_url = '';
		$font_families = array();
		
		$font_families[] = 'Open Sans:300,400,600,700';
		$font_families[] = 'Montserrat:100,200,300,400,700';
		$font_families[] = 'Elsie:400,900';

		$font1 = get_theme_mod( 'myticket_font1', '0' );
		$font2 = get_theme_mod( 'myticket_font2', '0' );
		$font3 = get_theme_mod( 'myticket_font3', '0' );
		if ( empty($font1) ) { $font1 = '0'; }
		if ( empty($font2) ) { $font2 = '0'; }
		if ( empty($font3) ) { $font3 = '0'; }

		if ( '0' != $font1 || '0' != $font2 || '0' != $font3 ){
			$fonts_arr = myticket::google_fonts();
		}

		if ( '0' !== $font1 ) {
			$font1 = $fonts_arr[$font1];
			$font_families[] = $font1;
		}

		if ( '0' !== $font2 ) {
			$font2 = $fonts_arr[$font2];
			$font_families[] = $font2;
		}

		if ( '0' !== $font3 ) {
			$font3 = $fonts_arr[$font3];
			$font_families[] = $font3;
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

		return esc_url_raw( $fonts_url );
	}

	static function google_fonts( ){

    	return array(
        'Default','ABeeZee','Abel','Abhaya Libre','Abril Fatface','Aclonica','Acme','Actor','Adamina','Advent Pro','Aguafina Script','Akronim','Aladin','Aldrich','Alef','Alegreya','Alegreya SC','Alegreya Sans','Alegreya Sans SC','Alex Brush','Alfa Slab One','Alice','Alike','Alike Angular','Allan','Allerta','Allerta Stencil','Allura','Almendra','Almendra Display','Almendra SC','Amarante','Amaranth','Amatic SC','Amatica SC','Amethysta','Amiko','Amiri','Amita','Anaheim','Andada','Andika','Angkor','Annie Use Your Telescope','Anonymous Pro','Antic','Antic Didone','Antic Slab','Anton','Arapey','Arbutus','Arbutus Slab','Architects Daughter','Archivo','Archivo Black','Archivo Narrow','Aref Ruqaa','Arima Madurai','Arimo','Arizonia','Armata','Arsenal','Artifika','Arvo','Arya','Asap','Asap Condensed','Asar','Asset','Assistant','Astloch','Asul','Athiti','Atma','Atomic Age','Aubrey','Audiowide','Autour One','Average','Average Sans','Averia Gruesa Libre','Averia Libre','Averia Sans Libre','Averia Serif Libre','Bad Script','Bahiana','Baloo','Baloo Bhai','Baloo Bhaijaan','Baloo Bhaina','Baloo Chettan','Baloo Da','Baloo Paaji','Baloo Tamma','Baloo Tammudu','Baloo Thambi','Balthazar','Bangers','Barrio','Basic','Battambang','Baumans','Bayon','Belgrano','Bellefair','Belleza','BenchNine','Bentham','Berkshire Swash','Bevan','Bigelow Rules','Bigshot One','Bilbo','Bilbo Swash Caps','BioRhyme','BioRhyme Expanded','Biryani','Bitter','Black Ops One','Bokor','Bonbon','Boogaloo','Bowlby One','Bowlby One SC','Brawler','Bree Serif','Bubblegum Sans','Bubbler One','Buda','Buenard','Bungee','Bungee Hairline','Bungee Inline','Bungee Outline','Bungee Shade','Butcherman','Butterfly Kids','Cabin','Cabin Condensed','Cabin Sketch','Caesar Dressing','Cagliostro','Cairo','Calligraffitti','Cambay','Cambo','Candal','Cantarell','Cantata One','Cantora One','Capriola','Cardo','Carme','Carrois Gothic','Carrois Gothic SC','Carter One','Catamaran','Caudex','Caveat','Caveat Brush','Cedarville Cursive','Ceviche One','Changa','Changa One','Chango','Chathura','Chau Philomene One','Chela One','Chelsea Market','Chenla','Cherry Cream Soda','Cherry Swash','Chewy','Chicle','Chivo','Chonburi','Cinzel','Cinzel Decorative','Clicker Script','Coda','Coda Caption','Codystar','Coiny','Combo','Comfortaa','Coming Soon','Concert One','Condiment','Content','Contrail One','Convergence','Cookie','Copse','Corben','Cormorant','Cormorant Garamond','Cormorant Infant','Cormorant SC','Cormorant Unicase','Cormorant Upright','Courgette','Cousine','Coustard','Covered By Your Grace','Crafty Girls','Creepster','Crete Round','Crimson Text','Croissant One','Crushed','Cuprum','Cutive','Cutive Mono','Damion','Dancing Script','Dangrek','David Libre','Dawning of a New Day','Days One','Dekko','Delius','Delius Swash Caps','Delius Unicase','Della Respira','Denk One','Devonshire','Dhurjati','Didact Gothic','Diplomata','Diplomata SC','Domine','Donegal One','Doppio One','Dorsa','Dosis','Dr Sugiyama','Droid Sans','Droid Sans Mono','Droid Serif','Duru Sans','Dynalight','EB Garamond','Eagle Lake','Eater','Economica','Eczar','El Messiri','Electrolize','Elsie','Elsie Swash Caps','Emblema One','Emilys Candy','Encode Sans','Encode Sans Condensed','Encode Sans Expanded','Encode Sans Semi Condensed','Encode Sans Semi Expanded','Engagement','Englebert','Enriqueta','Erica One','Esteban','Euphoria Script','Ewert','Exo','Exo 2','Expletus Sans','Fanwood Text','Farsan','Fascinate','Fascinate Inline','Faster One','Fasthand','Fauna One','Faustina','Federant','Federo','Felipa','Fenix','Finger Paint','Fira Mono','Fira Sans','Fira Sans Condensed','Fira Sans Extra Condensed','Fjalla One','Fjord One','Flamenco','Flavors','Fondamento','Fontdiner Swanky','Forum','Francois One','Frank Ruhl Libre','Freckle Face','Fredericka the Great','Fredoka One','Freehand','Fresca','Frijole','Fruktur','Fugaz One','GFS Didot','GFS Neohellenic','Gabriela','Gafata','Galada','Galdeano','Galindo','Gentium Basic','Gentium Book Basic','Geo','Geostar','Geostar Fill','Germania One','Gidugu','Gilda Display','Give You Glory','Glass Antiqua','Glegoo','Gloria Hallelujah','Goblin One','Gochi Hand','Gorditas','Goudy Bookletter 1911','Graduate','Grand Hotel','Gravitas One','Great Vibes','Griffy','Gruppo','Gudea','Gurajada','Habibi','Halant','Hammersmith One','Hanalei','Hanalei Fill','Handlee','Hanuman','Happy Monkey','Harmattan','Headland One','Heebo','Henny Penny','Herr Von Muellerhoff','Hind','Hind Guntur','Hind Madurai','Hind Siliguri','Hind Vadodara','Holtwood One SC','Homemade Apple','Homenaje','IM Fell DW Pica','IM Fell DW Pica SC','IM Fell Double Pica','IM Fell Double Pica SC','IM Fell English','IM Fell English SC','IM Fell French Canon','IM Fell French Canon SC','IM Fell Great Primer','IM Fell Great Primer SC','Iceberg','Iceland','Imprima','Inconsolata','Inder','Indie Flower','Inika','Inknut Antiqua','Irish Grover','Istok Web','Italiana','Italianno','Itim','Jacques Francois','Jacques Francois Shadow','Jaldi','Jim Nightshade','Jockey One','Jolly Lodger','Jomhuria','Josefin Sans','Josefin Slab','Joti One','Judson','Julee','Julius Sans One','Junge','Jura','Just Another Hand','Just Me Again Down Here','Kadwa','Kalam','Kameron','Kanit','Kantumruy','Karla','Karma','Katibeh','Kaushan Script','Kavivanar','Kavoon','Kdam Thmor','Keania One','Kelly Slab','Kenia','Khand','Khmer','Khula','Kite One','Knewave','Kotta One','Koulen','Kranky','Kreon','Kristi','Krona One','Kumar One','Kumar One Outline','Kurale','La Belle Aurore','Laila','Lakki Reddy','Lalezar','Lancelot','Lateef','Lato','League Script','Leckerli One','Ledger','Lekton','Lemon','Lemonada','Libre Barcode 128','Libre Barcode 128 Text','Libre Barcode 39','Libre Barcode 39 Extended','Libre Barcode 39 Extended Text','Libre Barcode 39 Text','Libre Baskerville','Libre Franklin','Life Savers','Lilita One','Lily Script One','Limelight','Linden Hill','Lobster','Lobster Two','Londrina Outline','Londrina Shadow','Londrina Sketch','Londrina Solid','Lora','Love Ya Like A Sister','Loved by the King','Lovers Quarrel','Luckiest Guy','Lusitana','Lustria','Macondo','Macondo Swash Caps','Mada','Magra','Maiden Orange','Maitree','Mako','Mallanna','Mandali','Manuale','Marcellus','Marcellus SC','Marck Script','Margarine','Marko One','Marmelad','Martel','Martel Sans','Marvel','Mate','Mate SC','Maven Pro','McLaren','Meddon','MedievalSharp','Medula One','Meera Inimai','Megrim','Meie Script','Merienda','Merienda One','Merriweather','Merriweather Sans','Metal','Metal Mania','Metamorphous','Metrophobic','Michroma','Milonga','Miltonian','Miltonian Tattoo','Miniver','Miriam Libre','Mirza','Miss Fajardose','Mitr','Modak','Modern Antiqua','Mogra','Molengo','Molle','Monda','Monofett','Monoton','Monsieur La Doulaise','Montaga','Montez','Montserrat','Montserrat Alternates','Montserrat Subrayada','Moul','Moulpali','Mountains of Christmas','Mouse Memoirs','Mr Bedfort','Mr Dafoe','Mr De Haviland','Mrs Saint Delafield','Mrs Sheppards','Mukta','Mukta Mahee','Mukta Malar','Mukta Vaani','Muli','Mystery Quest','NTR','Neucha','Neuton','New Rocker','News Cycle','Niconne','Nixie One','Nobile','Nokora','Norican','Nosifer','Nothing You Could Do','Noticia Text','Noto Sans','Noto Serif','Nova Cut','Nova Flat','Nova Mono','Nova Oval','Nova Round','Nova Script','Nova Slim','Nova Square','Numans','Nunito','Nunito Sans','Odor Mean Chey','Offside','Old Standard TT','Oldenburg','Oleo Script','Oleo Script Swash Caps','Open Sans','Open Sans Condensed','Oranienbaum','Orbitron','Oregano','Orienta','Original Surfer','Oswald','Over the Rainbow','Overlock','Overlock SC','Overpass','Overpass Mono','Ovo','Oxygen','Oxygen Mono','PT Mono','PT Sans','PT Sans Caption','PT Sans Narrow','PT Serif','PT Serif Caption','Pacifico','Padauk','Palanquin','Palanquin Dark','Pangolin','Paprika','Parisienne','Passero One','Passion One','Pathway Gothic One','Patrick Hand','Patrick Hand SC','Pattaya','Patua One','Pavanam','Paytone One','Peddana','Peralta','Permanent Marker','Petit Formal Script','Petrona','Philosopher','Piedra','Pinyon Script','Pirata One','Plaster','Play','Playball','Playfair Display','Playfair Display SC','Podkova','Poiret One','Poller One','Poly','Pompiere','Pontano Sans','Poppins','Port Lligat Sans','Port Lligat Slab','Pragati Narrow','Prata','Preahvihear','Press Start 2P','Pridi','Princess Sofia','Prociono','Prompt','Prosto One','Proza Libre','Puritan','Purple Purse','Quando','Quantico','Quattrocento','Quattrocento Sans','Questrial','Quicksand','Quintessential','Qwigley','Racing Sans One','Radley','Rajdhani','Rakkas','Raleway','Raleway Dots','Ramabhadra','Ramaraja','Rambla','Rammetto One','Ranchers','Rancho','Ranga','Rasa','Rationale','Ravi Prakash','Redressed','Reem Kufi','Reenie Beanie','Revalia','Rhodium Libre','Ribeye','Ribeye Marrow','Righteous','Risque','Roboto','Roboto Condensed','Roboto Mono','Roboto Slab','Rochester','Rock Salt','Rokkitt','Romanesco','Ropa Sans','Rosario','Rosarivo','Rouge Script','Rozha One','Rubik','Rubik Mono One','Ruda','Rufina','Ruge Boogie','Ruluko','Rum Raisin','Ruslan Display','Russo One','Ruthie','Rye','Sacramento','Sahitya','Sail','Saira','Saira Condensed','Saira Extra Condensed','Saira Semi Condensed','Salsa','Sanchez','Sancreek','Sansita','Sarala','Sarina','Sarpanch','Satisfy','Scada','Scheherazade','Schoolbell','Scope One','Seaweed Script','Secular One','Sedgwick Ave','Sedgwick Ave Display','Sevillana','Seymour One','Shadows Into Light','Shadows Into Light Two','Shanti','Share','Share Tech','Share Tech Mono','Shojumaru','Short Stack','Shrikhand','Siemreap','Sigmar One','Signika','Signika Negative','Simonetta','Sintony','Sirin Stencil','Six Caps','Skranji','Slabo 13px','Slabo 27px','Slackey','Smokum','Smythe','Sniglet','Snippet','Snowburst One','Sofadi One','Sofia','Sonsie One','Sorts Mill Goudy','Source Code Pro','Source Sans Pro','Source Serif Pro','Space Mono','Special Elite','Spectral','Spicy Rice','Spinnaker','Spirax','Squada One','Sree Krushnadevaraya','Sriracha','Stalemate','Stalinist One','Stardos Stencil','Stint Ultra Condensed','Stint Ultra Expanded','Stoke','Strait','Sue Ellen Francisco','Suez One','Sumana','Sunshiney','Supermercado One','Sura','Suranna','Suravaram','Suwannaphum','Swanky and Moo Moo','Syncopate','Tangerine','Taprom','Tauri','Taviraj','Teko','Telex','Tenali Ramakrishna','Tenor Sans','Text Me One','The Girl Next Door','Tienne','Tillana','Timmana','Tinos','Titan One','Titillium Web','Trade Winds','Trirong','Trocchi','Trochut','Trykker','Tulpen One','Ubuntu','Ubuntu Condensed','Ubuntu Mono','Ultra','Uncial Antiqua','Underdog','Unica One','UnifrakturCook','UnifrakturMaguntia','Unkempt','Unlock','Unna','VT323','Vampiro One','Varela','Varela Round','Vast Shadow','Vesper Libre','Vibur','Vidaloka','Viga','Voces','Volkhov','Vollkorn','Voltaire','Waiting for the Sunrise','Wallpoet','Walter Turncoat','Warnes','Wellfleet','Wendy One','Wire One','Work Sans','Yanone Kaffeesatz','Yantramanav','Yatra One','Yellowtail','Yeseva One','Yesteryear','Yrsa','Zeyada','Zilla Slab','Zilla Slab Highlight',
        );
    }
}

$myticket_class = new myticket();

if ( ! function_exists( 'myticket_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function myticket_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on myticket, use a find and replace
	 * to change 'myticket' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'myticket', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'myticket' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'myticket_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );




}
endif;
add_action( 'after_setup_theme', 'myticket_setup' );

if ( ! isset( $content_width ) ) $content_width = 1020; /* pixels */
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load shortcodes, post-types.
 */
require get_template_directory() . '/inc/init.php';

/**
 * Load suggested plugins
 */
require get_template_directory() . '/inc/class-tgm-plugin-activation.php';
require get_template_directory() . '/inc/plugins.php';

/**
 * Implement Ajax ticket listing.
 */
require get_template_directory() . '/template-parts/ajax-myticket-list-init.php';

/**
 * Implement Ajax ticket schedule listing.
 */
require get_template_directory() . '/template-parts/ajax-myticket-schedule-init.php';

/**
 * Implement Ajax ticket products methonds.
 */
require get_template_directory() . '/template-parts/ajax-products.php';


/**
 * Implement Ajax ticket email methonds.
 */
require get_template_directory() . '/template-parts/ajax-myticket-emails.php';