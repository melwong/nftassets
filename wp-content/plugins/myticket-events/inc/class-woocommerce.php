<?php 

//add woocommerce support
add_theme_support('woocommerce');

//Store the custom field
function myticket_add_cart_item_custom_data( $cart_item_meta, $product_id ) {
    global $woocommerce;
  
    $myticket_time = get_post_meta( $product_id, 'myticket_datetime_start', '');
    $myticket_venue = get_post_meta( $product_id, 'myticket_title', '');
    $myticket_address = get_post_meta( $product_id, 'myticket_address', '');
    if(sizeof($myticket_time)>0){

        $myticket_time = date_i18n( get_option( 'date_format' )." | ".get_option( 'time_format' ), intval( $myticket_time[0] ) );
		$cart_item_meta['myticket_time'] = (isset($_POST['myticket_time'])) ? sanitize_text_field( $_POST['myticket_time'] ): $myticket_time;
    }
    
	if(sizeof($myticket_venue)>0)
    $cart_item_meta['myticket_venue'] = (isset($_POST['myticket_venue'])) ? sanitize_text_field( $_POST['myticket_venue'] ): $myticket_venue[0];

    if(sizeof($myticket_address)>0)
    $cart_item_meta['myticket_address'] = (isset($_POST['myticket_address'])) ? sanitize_text_field( $_POST['myticket_address'] ): $myticket_address[0];
    
    $cart_item_meta['myticket_name'] = (isset($_POST['myticket_name'])) ? sanitize_text_field( $_POST['myticket_name'] ): "";
    $cart_item_meta['myticket_email'] = (isset($_POST['myticket_email'])) ? sanitize_email( $_POST['myticket_email'] ): "";

    return $cart_item_meta; 
}
add_filter( 'woocommerce_add_cart_item_data', 'myticket_add_cart_item_custom_data', 10, 2 );

// define the woocommerce_before_cart_contents callback 
function myticket_before_cart_contents() { 
   
    if ( '1' == get_theme_mod('myticket_participants', '0') ){ ?>

        <script> 
        var allowCheckout = false, nameValidated = true, emailValidated = true, nameUpdated = true, emailUpdated = true;
        jQuery(function ($) {

            $('.checkout-button').on('click',function(){

                revalidate();

                if(!allowCheckout){
                    alert($(".update").data('warning'));
                    return false;
                }

                checkIfUpdated();

                if(!nameUpdated || !emailUpdated){
                    alert($(".update").data('warning2'));
                    return false;
                }
            });
        });

        //checkout-button
        function revalidate(){

            allowCheckout = nameValidated = emailValidated = true;
            jQuery('.cart_pers_n').each(function(index){if(jQuery('.cart_pers_n').val() === ""){nameValidated = false;}});
            jQuery('.cart_pers_e').each(function(index){if(!validateEmail(jQuery('.cart_pers_e').val())){emailValidated = false;}});
            allowCheckout = (nameValidated && emailValidated)?true:false;
        }

        function checkIfUpdated(){

            nameUpdated = emailUpdated = true;
            jQuery('.cart_pers_n').each(function(index){if(jQuery('.cart_pers_n').data('value') === ""){nameUpdated = false;}});
            jQuery('.cart_pers_e').each(function(index){if(jQuery('.cart_pers_e').data('value') === ""){emailUpdated = false;}});
        }

        function validateEmail(mail){ if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) return (true) }

        </script>
    <?php }
}; 
add_action( 'woocommerce_before_cart_contents', 'myticket_before_cart_contents', 10, 0 ); 

// Display custom data on cart and checkout page.
add_filter( 'woocommerce_get_item_data', 'myticket_get_item_data' , 25, 2 );
function myticket_get_item_data ( $cart_data, $cart_item ) {

    //https://stackoverflow.com/questions/47865226/add-custom-fields-as-cart-item-meta-and-order-item-meta-in-woocommerce
    if ( '1' == get_theme_mod('myticket_participants', '0') ){
        echo '<br><div class="name2"><input class="cart_pers cart_pers_n" type="text" name="cart['.esc_attr($cart_item['key']).'][myticket_name]" data-value="'.esc_attr($cart_item['myticket_name']).'" value="'.esc_attr($cart_item['myticket_name']).'" placeholder="'.esc_attr__( 'Ticket Holder Name', 'myticket-events' ).'" required /></div>';
        echo '<div class="email2 name2"><input class="cart_pers cart_pers_e" type="email" name="cart['.esc_attr($cart_item['key']).'][myticket_email]" data-value="'.esc_attr($cart_item['myticket_email']).'" value="'.esc_attr($cart_item['myticket_email']).'" placeholder="'.esc_attr__( 'Ticket Holder Email', 'myticket-events' ).'" required /></div>';
        echo '<div class="update" data-warning="'.esc_attr__( 'Please enter name and email correctly!', 'myticket-events' ).'" data-warning2="'.esc_attr__( 'Please click on update button first!', 'myticket-events' ).'">'.esc_html__( '*Update cart after changes', 'myticket-events' ).'</div>';
        if ( strlen($cart_item['myticket_name']) < 2 && strlen($cart_item['myticket_email']) < 5 ){
            $allow_checkout = false;
        }
    }
	
	//Mel: 05/11/19. Comment the below to avoid displaying the date and time as it's irrelevant in context of assets
	//Mel: 28/08/19. To check if the cart item is not empty before outputing anything
/*     if ($cart_item['myticket_time']) {
		$cart_data[] = array(
			'name'    => esc_html__( "Date | Time", "myticket-events"),
			'display' => esc_html($cart_item['myticket_time'])
		);
	} */
	//print_r("<br>".esc_html( $cart_item['myticket_time'] ));
	
	//Mel: 28/08/19. To check if the cart item is not empty before outputing anything
	if ($cart_item['myticket_venue']) {
		$cart_data[] = array(
			'name'    => esc_html__( "Venue", "myticket-events"),
			'display' => esc_html($cart_item['myticket_venue'])
		);
	}	
    /* $cart_data[] = array(
        'name'    => esc_html__( "Venue", "myticket-events"),
        'display' => esc_html($cart_item['myticket_venue'])
    ); */
	
	//Mel: 01/09/19. To add venue address
	if ($cart_item['myticket_address']) {
		$cart_data[] = array(
			'name'    => esc_html__( "Address", "myticket-events"),
			'display' => esc_html($cart_item['myticket_address'])
		);
	}

	
	//Mel: 06/09/19. To add more seating map cart data to be displayed in cart and checkout.
	//This is used when we use seating map to select seats
	if(isset($_COOKIE['myticket_user_id'])){
        
		if ( isset($cart_item['myticket_zone']) && !empty($cart_item['myticket_zone']) ) {
			$cart_data[] = array(
				'name'    => esc_html__( "Zone", "myticket-events"),
				'display' => esc_html($cart_item['myticket_zone'])
			);
		}
	
		if ( isset($cart_item['myticket_row']) && !empty($cart_item['myticket_row']) ) {
			$cart_data[] = array(
				'name'    => esc_html__( "Row", "myticket-events"),
				'display' => esc_html($cart_item['myticket_row'])
			);
		}
		
		if ( isset($cart_item['myticket_seats']) && !empty($cart_item['myticket_seats']) ) {
			$cart_data[] = array(
				'name'    => esc_html__( "Seat", "myticket-events"),
				'display' => esc_html($cart_item['myticket_seats'])
			);
		}
	} 
/*     if(isset($_COOKIE['myticket_user_id'])){
        $cart_data[] = array(
            'name'    => esc_html__( "Seat", "myticket-events"),
            'display' => esc_html($cart_item['myticket_seats'])
        );
	} */
	
	//Mel: 29/08/19
	//file_put_contents('C:/laragon/www/1krowd1st/wp-content/debug.log', print_r($cart_item, true), FILE_APPEND);
	//$output = print_r($cart_item['product_extras']);
	
	//echo "TYPE: " . print_r($cart_item['product_extras']['groups']['8929']['8932']['type']);
	
/* 	$product_extras = $cart_item['product_extras'];

	array_walk_recursive($product_extras, function ($value, $key) {
		
		if (count(array_intersect([$key], ['type', 'label', 'value'])) > 0) {
			$selected_value = $value;
			echo $selected_value;
		}
	});
	
	$cart_data[] = array(
            'name'    => esc_html__( "Seat", "myticket-events"),
            'display' => esc_html($selected_value)
    ); */
	
/* 	array_walk_recursive($product_extras, function ($value, $key) {
		
		// check if key matches with type, label or value
		if (count(array_intersect([$key], ['type', 'label', 'value'])) > 0) {
			echo "Value: " . $value . "\n";
			echo "keys: " . key($product_extras);
			//$key_values = array_intersect([$key], ['type', 'label', 'value']);
			
			//echo "[$key] \n";
			//echo "[$value] ";
			if( $key == 'type' && $value == 'select' ) {
				//echo "[$key] \n";
				echo $value; 
			}
		}
	}); */
	
/* 	array_walk_recursive( $product_extras, function ($value, $key ) {
		
		if( $key === 'type' && $value == 'select' ) {
			echo "$value";
		}

	}); */

	
/* 	foreach($product_extras as $key => $value){
        //If $value is an array.
        if(is_array($value)){
            //We need to loop through it.
            recursive($value);
        } else{
            //It is not an array, so print it out.
            echo $value, '<br>';
        }
    } */
	
	//Mel: 30/08/19. To display selected variations on cart. Such as "Row 12, Seat 10" selection
	if( isset($cart_item['variation']) && $cart_item['variation_id'] != '0' ) {
		
		$variation_id = $cart_item['variation_id'];
		$variation_name = get_the_excerpt( $variation_id ); 
		
		//print_r( "<br>" . esc_html($variation_name) );
		
		//Display selected variation
		$cart_data[] = array(
			'display' => esc_html($variation_name)
		);
			
		//get the WooCommerce Product object by product ID
		/* $current_product = new  WC_Product_Variable($product_id);
		
		//get the variations of this product
		$variations = $current_product->get_available_variations();
		
		//Loop through each variation to get its title
		foreach($variations as $index => $data){
		  $variation_id = $data['variation_id'];
		  $variation_title = esc_html ( get_the_title(variation_id) );
		  $variation_price = $data['display_price'];
		  $variation_attr  = $data['attributes'];
		} */
	}
	
	//Mel: 31/08/19. Check to see if it's a parent product so that we can remove it
	/* if ( isset( $cart_item['product_extras']['products']['child_products'] ) ) {
		
		global $woocommerce;
		
		$parent_product_id = $cart_item['product_id'];
		
		//Go through each cart item to find the parent product
		foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
			
			//If the cart item is a parent product, remove it but leave child product in the cart
			if ($cart_item['product_id'] == $parent_product_id) {
				
				//Remove parent product
				$woocommerce->cart->remove_cart_item($cart_item_key);
	
			}
		}
	} */
		
		
		/* $cart_id = WC()->cart->generate_cart_id( $parent_product_id );
		$cart_item_key = WC()->cart->find_product_in_cart( $cart_id );
		WC()->cart->remove_cart_item( $cart_item_key );
		
		unset( $cart->cart_contents[$cart_item_key] ); */
		
		//echo "cart_item_key: " . $cart_item_key;
		
		// Cycle through each product in the cart
/*         foreach( WC()->cart->cart_contents as $prod_in_cart ) {
            
			// Get the Variation or Product ID
            $prod_id = ( isset( $prod_in_cart['variation_id'] ) && $prod_in_cart['variation_id'] != 0 ) ? $prod_in_cart['variation_id'] : $prod_in_cart['product_id'];
			
			echo "prod_id: " . $prod_id;
			
            // Check to see if IDs match
            if( $prod_id == $parent_product_id ) {
				
				echo "YES";
				
                // Get it's unique ID within the Cart
                $prod_unique_id = WC()->cart->generate_cart_id( $parent_product_id );
				
				echo "prod_unique_id: " . $prod_unique_id;
				
                // Remove it from the cart by un-setting it
                unset( WC()->cart->cart_contents[$prod_unique_id] );
            }
        }
	
	}*/
	
	//To detect if the cart item is a child product
/* 	if ( isset( $cart_item['product_extras']['products']['child_field'] ) && $cart_item['product_extras']['products']['child_field'] == 1 ) { 
		
		$parent_product_id = $cart_item['product_extras']['products']['pewc_parent_product'];
		
		//Use display parent product's info if there's a parent. Since the child only shows the seating area, we need to display parent's details like event name, date/time, venue
		if ($parent_product_id) {
			
			$parent_product = get_the_title( $parent_product_id );
			$product = wc_get_product( $parent_product_id );
			
			$myticket_time = get_post_meta( $parent_product_id, 'myticket_datetime_start', true);
			$myticket_time = date_i18n( get_option( 'date_format' )." | ".get_option( 'time_format' ), intval( $myticket_time ) );
			$myticket_venue = get_post_meta( $parent_product_id, 'myticket_title', true);
			$myticket_address = get_post_meta( $parent_product_id, 'myticket_address', true);
			
			$cart_data[] = array(
				'name'   => esc_html__( "Event", "myticket-events"),
				'display' => esc_html($parent_product)
			);
			
			$cart_data[] = array(
				'name'    => esc_html__( "Date | Time", "myticket-events"),
				'display' => esc_html($myticket_time)
			);
			
			$cart_data[] = array(
				'name'    => esc_html__( "Venue", "myticket-events"),
				'display' => esc_html($myticket_venue)
			);
			
			$cart_data[] = array(
				'name'    => esc_html__( "Address", "myticket-events"),
				'display' => esc_html($myticket_address)
			);

		}
	} */
	
    return $cart_data;

}

//Mel: 31/08/19. If cart item is a child product, rename it with the parent product name
/* function rename_child_product( $item_name,  $cart_item,  $cart_item_key ) {
	
	//Detect if the item is a child product
	if ( isset( $cart_item['product_extras']['products']['child_field'] ) && $cart_item['product_extras']['products']['child_field'] == 1 ) { 
		
		//Get parent id
		$parent_product_id = $cart_item['product_extras']['products']['pewc_parent_product'];
		
		if ($parent_product_id) {
			//Display parent product name
			echo get_the_title( $parent_product_id );
		} 
		
	} else {
		//If not a child product, just display its original name
		echo $item_name;
	}
} */
//add_filter( 'woocommerce_cart_item_name', 'rename_child_product', 10, 3 );

/* 
function so_29985124_order_item_title( $title, $order_item ) {
	
	echo "Out";
	
	//Detect if the item is a child product
	if ( isset( $order_item['product_extras']['products']['child_field'] ) && $order_item['product_extras']['products']['child_field'] == 1 ) { 
		
		//Get parent id
		$parent_product_id = $order_item['product_extras']['products']['pewc_parent_product'];
		
		echo "IN: " . $parent_product_id;
		
		if ($parent_product_id) {
			//Display parent product name
			$title = get_the_title( $parent_product_id );
		} 
		
	} 
   return $title;

}
add_filter( 'woocommerce_order_item_name', 'so_29985124_order_item_title', 10, 2 ); */

//Get it from the session and add it to the cart variable
function myticket_get_cart_items_from_session( $item, $values, $key ) {

    if ( array_key_exists( 'myticket_time', $values ) )
    $item[ 'myticket_time' ] = $values['myticket_time'];
    if ( array_key_exists( 'myticket_venue', $values ) )
    $item[ 'myticket_venue' ] = $values['myticket_venue'];
    if ( array_key_exists( 'myticket_address', $values ) )
    $item[ 'myticket_address' ] = $values['myticket_address'];

    if(isset($_POST['cart']))
        if ( array_key_exists( 'myticket_name', $_POST['cart'][$item['key']] ) )
            $item[ 'myticket_name' ] = sanitize_text_field( $_POST['cart'][$item['key']]['myticket_name'] );
    if(isset($_POST['cart']))
        if ( array_key_exists( 'myticket_email', $_POST['cart'][$item['key']] ) )
            $item[ 'myticket_email' ] = sanitize_text_field( $_POST['cart'][$item['key']]['myticket_email'] );

    return $item;
}
//Mel: 06/09/19. To disable this since we don't need items to be kept in the cart and to see if this could fix the prob of "cart is empty"
//add_filter( 'woocommerce_get_cart_item_from_session', 'myticket_get_cart_items_from_session', 1, 3 );

//pass custom cart field to checkout
function myticket_add_order_item_meta($item_id, $cart_item, $order_id) {
    
	//Mel: 11/12/19. To fix the bug when the meta key text is translated (using LocoTranslate) like changing "venue" to "location" in esc_html__( 'venue', 'myticket-events' ), the order item meta key will be changed to "location" thus if any code tries to read the metadata like $myticket_venue = $item['venue'] inwp-content\themes\myticket\myticket-events\ticket-individual\index.php the $myticket_venue will retrieve nothing. 
	if ( !empty( $cart_item->legacy_values['myticket_time'] ) ) {
        wc_add_order_item_meta($item_id, 'time', $cart_item->legacy_values['myticket_time']);
    } 

    if ( !empty( $cart_item->legacy_values['myticket_venue'] ) ) {
        wc_add_order_item_meta($item_id, 'venue', $cart_item->legacy_values['myticket_venue']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_address'] ) ) {
        wc_add_order_item_meta($item_id, 'address', $cart_item->legacy_values['myticket_address']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_name'] ) ) {
        wc_add_order_item_meta($item_id, 'name', $cart_item->legacy_values['myticket_name']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_email'] ) ) {
        wc_add_order_item_meta($item_id, 'email', $cart_item->legacy_values['myticket_email']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_seats'] ) ) {
        wc_add_order_item_meta($item_id, 'seat', $cart_item->legacy_values['myticket_seats']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_zone'] ) ) {
        wc_add_order_item_meta($item_id, 'zone', $cart_item->legacy_values['myticket_zone']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_row'] ) ) {
        wc_add_order_item_meta($item_id, 'row', $cart_item->legacy_values['myticket_row']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_seat_id'] ) ) {
        wc_add_order_item_meta($item_id, 'seat_id', $cart_item->legacy_values['myticket_seat_id']);
    }
	//Mel: End
	
/*     if ( !empty( $cart_item->legacy_values['myticket_time'] ) ) {
        wc_add_order_item_meta($item_id, esc_html__( 'time', 'myticket-events' ), $cart_item->legacy_values['myticket_time']);
    } 

    if ( !empty( $cart_item->legacy_values['myticket_venue'] ) ) {
        wc_add_order_item_meta($item_id, esc_html__( 'venue', 'myticket-events' ), $cart_item->legacy_values['myticket_venue']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_address'] ) ) {
        wc_add_order_item_meta($item_id, esc_html__( 'address', 'myticket-events' ), $cart_item->legacy_values['myticket_address']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_name'] ) ) {
        wc_add_order_item_meta($item_id, esc_html__( 'name', 'myticket-events' ), $cart_item->legacy_values['myticket_name']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_email'] ) ) {
        wc_add_order_item_meta($item_id, esc_html__( 'email', 'myticket-events' ), $cart_item->legacy_values['myticket_email']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_seats'] ) ) {
        wc_add_order_item_meta($item_id, esc_html__( 'seat', 'myticket-events' ), $cart_item->legacy_values['myticket_seats']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_zone'] ) ) {
        wc_add_order_item_meta($item_id, esc_html__( 'zone', 'myticket-events' ), $cart_item->legacy_values['myticket_zone']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_row'] ) ) {
        wc_add_order_item_meta($item_id, esc_html__( 'row', 'myticket-events' ), $cart_item->legacy_values['myticket_row']);
    }

    if ( !empty( $cart_item->legacy_values['myticket_seat_id'] ) ) {
        wc_add_order_item_meta($item_id, esc_html__( 'seat_id', 'myticket-events' ), $cart_item->legacy_values['myticket_seat_id']);
    } */
}
add_action('woocommerce_new_order_item','myticket_add_order_item_meta', 10, 3);

/** 
 * Register new status
 * Tutorial: http://www.sellwithwp.com/woocommerce-custom-order-status-2/
 **/
function myticket_register_custom_order_status() {
    register_post_status( 'wc-validated', array(
        'label'                     => 'Validated',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Validated <span class="count">(%s)</span>', 'Validated <span class="count">(%s)</span>', 'myticket-events' )
    ) );
}
add_action( 'init', 'myticket_register_custom_order_status' );

// Add to list of WC Order statuses
function myticket_add_custom_order_status( $order_statuses ) {

    $new_order_statuses = array();

    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;

        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-validated'] = 'Validated';
        }
    }

    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'myticket_add_custom_order_status' );

//split cart items if quntity for the same item is > 1
function myticket_split_multiple_quantity_products_to_separate_cart_items( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

    // If product has more than 1 quantity
    if ( $quantity > 1 ) {

        // Keep the product but set its quantity to 1
        WC()->cart->set_quantity( $cart_item_key, 1 );

        // Run a loop 1 less than the total quantity
        for ( $i = 1; $i <= $quantity -1; $i++ ) {
            /**
             * Set a unique key.
             * This is what actually forces the product into its own cart line item
             */
            $cart_item_data['unique_key'] = md5( microtime() . rand() . "Kenzap" );

            // Add the product as a new line item with the same variations that were passed
            WC()->cart->add_to_cart( $product_id, 1, $variation_id, $variation, $cart_item_data );
        }
    }         
}

function myticket_split_product_individual_cart_items( $cart_item_data, $product_id ){

    $unique_cart_item_key = uniqid();
    $cart_item_data['unique_key'] = $unique_cart_item_key;
    $user_id = sanitize_text_field($_COOKIE['myticket_user_id']);

    if(isset($user_id)){

        // get seat one by one
        $reservations = json_decode(get_option("myticket_".$product_id, '[]'), true);

        // remove previous reservations for current user
        foreach ($reservations as $key => $value) {

            //clear not booked reservations older than 30 mins | Ex.: abandoned cart case
            if($reservations[$key]['time']<time()-1800 && $reservations[$key]['type'] < 3){
                unset($reservations[$key]); 
            }

            if($reservations[$key]['user']==$user_id && $reservations[$key]['type']==1){

                $reservations[$key]['type'] = 2;
                $cart_item_data['myticket_seat_id'] = $key;
                $cart_item_data['myticket_seats'] = $reservations[$key]['ticket_text'];
                $cart_item_data['myticket_zone'] = $reservations[$key]['zone_text'];
                $cart_item_data['myticket_row'] = $reservations[$key]['ticket_row'];

                update_option("myticket_".$product_id, json_encode($reservations));

                break;
            }
        }

    }
    return $cart_item_data;
}

function myticket_limit_cart_item_quantity( $cart_item_key, $quantity, $old_quantity, $cart ){

    // Here the quantity limit
    $limit = 1;
    $orders_added = $quantity - $limit;

    if( $quantity > $limit ){

        $cart->cart_contents[ $cart_item_key ]['quantity'] = $limit;
        $product_id = $cart->cart_contents[ $cart_item_key ][ 'product_id' ];

        for( $i = 0; $i< $orders_added; $i++ ){

            $unique_cart_item_key = uniqid();
            $cart_item_data = array();
            $cart_item_data['unique_key'] = $unique_cart_item_key;
            $cart->add_to_cart( $product_id, 1, 0, 0, $cart_item_data );
        }

        // Add a custom notice
        wc_add_notice( __('We Split out quantities of more than one into invididual line items for tracking purposes, please update quantities as needed'), 'notice' );
    }
}

if ( get_theme_mod('myticket_combine','') != 1 ) { 
    
    add_action( 'woocommerce_add_to_cart', 'myticket_split_multiple_quantity_products_to_separate_cart_items', 10, 6 );
    add_filter( 'woocommerce_add_cart_item_data', 'myticket_split_product_individual_cart_items', 10, 2 );  
    add_action( 'woocommerce_after_cart_item_quantity_update', 'myticket_limit_cart_item_quantity', 20, 4 );
}

//https://rudrastyh.com/woocommerce/thank-you-page.html
function myticket_add_content_thankyou( $order_id ) {

    $order = new WC_Order( $order_id );
    $meta = get_post_meta( $order->get_id() );

    $ticket_name = '';
    $_product = '';
    if ( sizeof( $order->get_items() ) > 0 ) {
        foreach( $order->get_items() as $item ) {
            $_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
            $item_meta    = new WC_Order_Item_Product( $item['item_meta'], $_product );
            $ticket_name = $item['name'].", ";
        }
        $ticket_name = trim($ticket_name," ");
        $ticket_name = trim($ticket_name,",");
    }

    // combined ticket download button
    if(get_theme_mod('myticket_btn_master', 1)){

        echo '<div class="kenzap-download-ticket-cont" >';
            echo '<div class="kenzap-download-ticket" style="background-image:url('.MYTICKET_URL.'/dist/assets/download-ticket-bg.png);">
                    <div class="kenzap-container" style="max-width:1170px">
                        <img src="'.MYTICKET_URL.'/dist/assets/download-ticket-img.png" alt="'.esc_attr__('Download image','myticket-events').'">
                        <p>'.esc_html__('Thanks for purchasing','myticket-events').' "<strong>'.esc_html($ticket_name).'</strong>" '.esc_html__('ticket','myticket-events').'. </p>';
                        
                            echo '<p>'.esc_html__('You can directly download ticket by clicking','myticket-events').' <span>'.esc_html__('Download','myticket-events').'</span> '.esc_html__('button below','myticket-events').'.</p>';
                            $order->payment_complete();
                            echo do_shortcode( '[myticket-download-invoice class="primary-link" title="'.esc_attr__('Download Your Ticket','myticket-events').'" order_id="' . esc_attr($order->get_id()) . '"]' );
                        
                        echo '
                    </div>';
            echo '</div>';

        }

        // separate ticket download button
        if ( sizeof( $order->get_items() ) > 0 && get_theme_mod('myticket_combine','') != 1 ) { ?>

            <div class="row">   
                <div class="section-download-ticket-multi">

                    <h4><?php esc_html_e('Or download each ticket individually','myticket');?></h4>

                    <?php $i=1; foreach( $order->get_items() as $item ) {

                        echo do_shortcode( '[myticket-download-invoice-multi title="'.esc_html__('Download ticket','myticket').' #'.esc_attr($i).'" order_id="' . $order->get_id() . '" item_id="' . $item->get_id() . '" ticket_id="' . $i . '" ]' ); ?>

                    <?php $i++; } ?>

                </div>
            </div>
        <?php }

    echo '</div>';

    $product_id = $_product->get_id();
    $user_id = sanitize_text_field($_COOKIE['myticket_user_id']);

    if(isset($user_id)){

        // get seat one by one
        $reservations = json_decode(get_option("myticket_".$product_id, '[]'), true);

        // remove previous reservations for current user
        foreach ($reservations as $key => $value) {

            // reserve only those tickets that were actually booked 
            if($reservations[$key]['user']==$user_id && $reservations[$key]['type'] < 3){

                foreach( $order->get_items() as $item ) {

                    if($key==$item['item_meta']['seat_id']){

                        $reservations[$key]['type'] = 3;
                    }
                }
            }
        }

        // restore not booked reservations older than 30 mins | Ex.: abandoned cart case
        // restore seats that were not booked. Ex. user removed one ticket from cart
        foreach ($reservations as $key => $value) {
            if( ($reservations[$key]['time']<time()-1800 && $reservations[$key]['type'] < 3) || ($reservations[$key]['type'] < 3 && $reservations[$key]['user']==$user_id)){
                unset($reservations[$key]); 
            }
        }

        // reset ticket seat reservation cookie after successful checkout 
        setcookie("myticket_user_id", "", time()-10, "/");
        setcookie("tickets", "", time()-10, "/");
        update_option("myticket_".$product_id, json_encode($reservations));
    }
}
add_action( 'woocommerce_thankyou', 'myticket_add_content_thankyou', 1, 1 );

if ( ! function_exists( 'myticket_get_order_data_ajax' ) ) {
    function myticket_get_order_data_ajax() {
        global $woocommerce;

        $id       = (isset($_POST['id'])) ? sanitize_text_field($_POST['id']) : "";
        $item_id  = (isset($_POST['item_id'])) ? sanitize_text_field($_POST['item_id']) : "";
        $token    = (isset($_POST['token'])) ? sanitize_text_field($_POST['token']) : "";

        if ( !is_numeric($id) ) $id = "";
        if ( !is_numeric($item_id) ) $item_id = "";

        if ( $id == "" ){

            ob_start();
            $output['success'] = false;
            $output['code'] = 401;
            $output['reason'] = 'Wrong arguments';
            echo json_encode($output);   
            $buffer = ob_get_clean();
            wp_reset_postdata();
            wp_die($buffer); 
        }

        //check if backend is restricted to certain app IDs only. Customizer > MyTicket section
        $myticket_app_private = get_theme_mod( 'myticket_app_private', 0 );
        $myticket_app_ids = get_theme_mod( 'myticket_app_ids', '' );
        if( 1 == $myticket_app_private ):
            $pos = strrpos($myticket_app_ids, $token);
            if ($pos === false) {
                
                ob_start();
                $output['success'] = false;
                $output['code'] = 401;
                $output['reason'] = 'Unauthorized';
                echo json_encode($output);   
                $buffer = ob_get_clean();
                wp_reset_postdata();
                wp_die($buffer); 
            }
        endif;

        $order = new WC_Order( $id );
 
        ob_start();
        $output = [];
        $output['success'] = true;
        $output['id'] = $id;
        $output['item_id'] = $item_id;
        $output['order_status'] = $order->get_status();
        $output['order_total'] = $order->get_total();
        $output['order_meta'] = get_post_meta( sanitize_text_field($_POST['id']) );
        $output['order_items'] = [];

        foreach ($order->get_items() as $key => $lineItem) {
            $order_items = array('name' => $lineItem['name'], 'id' => $lineItem->get_id(), 'quantity' => $lineItem['quantity'], 'meta_data' => $lineItem->get_meta_data());
            array_push( $output['order_items'], $order_items );    
        }

        echo json_encode($output);   
        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer); 
    }
}
add_action('wp_ajax_nopriv_myticket_get_order_data_ajax', 'myticket_get_order_data_ajax');
add_action('wp_ajax_myticket_get_order_data_ajax', 'myticket_get_order_data_ajax');

if ( ! function_exists( 'myticket_set_order_status_ajax' ) ) {
    function myticket_set_order_status_ajax() {
        global $woocommerce;

        //check if backend is restricted to certain apps only.
        $myticket_app_private = get_theme_mod( 'myticket_app_private', '' );

        $output   = [];
        $id       = (isset($_POST['id'])) ? sanitize_text_field($_POST['id']) : "";
        $item_id  = (isset($_POST['item_id'])) ? sanitize_text_field($_POST['item_id']) : "";
        $status   = (isset($_POST['status'])) ? sanitize_text_field($_POST['status']) : "";
        $token    = (isset($_POST['token'])) ? sanitize_text_field($_POST['token']) : "";

        $order = new WC_Order($id);
        $validated = true;
        if($item_id==""){

            //combined ticket
            $order->update_status($status, 'Updated from MyTicket Scanner App');
            $output['order_status'] = $order->get_status();

            //validate all individual tickets automatically
            foreach ($order->get_items() as $key => $lineItem) {

                wc_update_order_item_meta($lineItem->get_id(), "status", $status);
                wc_update_order_item_meta($lineItem->get_id(), "device ID", $token);
            }
        }else{

            //split ticket
            wc_update_order_item_meta($item_id, "status", $status);
            wc_update_order_item_meta($item_id, "device ID", $token);
            $output['order_status'] = $status;

            //check if all separately sold tickets are validated
            foreach ($order->get_items() as $key => $lineItem) {

                $custom_field = wc_get_order_item_meta( $lineItem->get_id(), 'status', true ); 
                if($custom_field!='validated'){
                    $validated = false;
                }
            }

            //mark all order as validated if all separate tickets are validated
            if($validated){
                $order->update_status($status, 'Updated from MyTicket App ('.$token.')');
            }else{
                if($status!='validated')
                    $order->update_status($status, 'Updated from MyTicket App ('.$token.')');
            }
        }

        ob_start();
        
        $output['success'] = true;
        $output['id'] = $id;
        $output['validated'] = $validated;
        $output['item_id'] = $item_id;
        
        echo json_encode($output);   
        $buffer = ob_get_clean();
        wp_reset_postdata();
        wp_die($buffer); 
    }
}
add_action('wp_ajax_nopriv_myticket_set_order_status_ajax', 'myticket_set_order_status_ajax');
add_action('wp_ajax_myticket_set_order_status_ajax', 'myticket_set_order_status_ajax');

//set woocommerce order status change listener to restore tickets in case order is cancelled, failed or refunded
function myticket_woocommerce_order_status_changed( $id, $status_from, $status_to, $instance ) { 

    // clear booking amount
    $change = 0;
    $to = array('cancelled', 'failed', 'refunded');

    // decrease booking
    if ( in_array($status_to, $to) && !in_array($status_from, $to) ){
        $change = -1;
    }
    
    // increase booking
    if ( !in_array($status_to, $to) && in_array($status_from, $to) ){
        $change = 1;
    }
    
    // only save in case booking amounts should be updated
    if ( $change != 0 ){

        $line_items = $instance->get_items( 'line_item' );

        foreach ( $line_items as $item_id => $item ) {

            $product_id = $item->get_product_id();
            $reservations = json_decode(get_option("myticket_".$product_id, '[]'), true);
            $seat_id = wc_get_order_item_meta($item_id, "seat_id", true); 

            if(isset($reservations[$seat_id])){

                if($change == -1){
                    $reservations[$seat_id]['type'] = 1;
                    unset($reservations[$seat_id]);
                }

                // after ticket failed do no restore seats any more
                //if($change == 1)
                //    $reservations[$seat_id]['type'] = 3;

                update_option("myticket_".$product_id, json_encode($reservations));
            }
        }
    }
};
add_action( 'woocommerce_order_status_changed', 'myticket_woocommerce_order_status_changed', 10, 4 ); 