<?php
//add woocommerce support
add_theme_support('woocommerce');

//Mel: 20/08/19. Added after upgrading to myticket theme 1.0.7. Comment out according to the latest theme ver at C:\mydoc\tto\tech\myticket\150819 - from themes dot kenzap dot com slash myticket\myticket\myticket\inc. This basically ensures there's no clashing error with myticket-events plugin
//Store the custom field
/* function myticket_add_cart_item_custom_data( $cart_item_meta, $product_id ) {
  global $woocommerce;

    $myticket_time = get_post_meta( $product_id, 'myticket_datetime', '');
    $myticket_venue = get_post_meta( $product_id, 'myticket_title', '');
    $myticket_address = get_post_meta( $product_id, 'myticket_address', '');
    if(sizeof($myticket_time)>0){

        $myticket_time = date_i18n(  get_option( 'date_format' )." | ".get_option( 'time_format' ), intval( $myticket_time[0] ) );
        $cart_item_meta['myticket_time'] = (isset($_POST['myticket_time'])) ? esc_attr( $_POST['myticket_time'] ): $myticket_time;
    }

    if(sizeof($myticket_venue)>0)
    $cart_item_meta['myticket_venue'] = (isset($_POST['myticket_venue'])) ? esc_attr( $_POST['myticket_venue'] ): $myticket_venue[0];

    if(sizeof($myticket_address)>0)
    $cart_item_meta['myticket_address'] = (isset($_POST['myticket_address'])) ? esc_attr( $_POST['myticket_address'] ): $myticket_address[0];

    return $cart_item_meta; 
}
add_filter( 'woocommerce_add_cart_item_data', 'myticket_add_cart_item_custom_data', 10, 2 ); */

//Mel: 20/08/19. Added after upgrading to myticket theme 1.0.7. Comment out according to the latest theme ver at C:\mydoc\tto\tech\myticket\150819 - from themes dot kenzap dot com slash myticket\myticket\myticket\inc
//Get it from the session and add it to the cart variable
/* function myticket_get_cart_items_from_session( $item, $values, $key ) {

    if ( array_key_exists( 'myticket_time', $values ) )
      $item[ 'myticket_time' ] = $values['myticket_time'];
    if ( array_key_exists( 'myticket_venue', $values ) )
      $item[ 'myticket_venue' ] = $values['myticket_venue'];
    if ( array_key_exists( 'myticket_address', $values ) )
      $item[ 'myticket_address' ] = $values['myticket_address'];
    return $item;
}
add_filter( 'woocommerce_get_cart_item_from_session', 'myticket_get_cart_items_from_session', 1, 3 ); */

//Mel: 20/08/19. Added after upgrading to myticket theme 1.0.7. Comment out according to the latest theme ver at C:\mydoc\tto\tech\myticket\150819 - from themes dot kenzap dot com slash myticket\myticket\myticket\inc
//pass custom cart field to checkout
/* function myticket_add_order_item_meta($itemID, $values) {

    $myticket_time = $values['myticket_time'];
    if (!empty($myticket_time)) {
        wc_add_order_item_meta($itemID, esc_html__( 'time', 'myticket' ), $myticket_time);
    }

    $myticket_venue = $values['myticket_venue'];
    if (!empty($myticket_venue)) {
        wc_add_order_item_meta($itemID, esc_html__( 'venue', 'myticket' ), $myticket_venue);
    }

    $myticket_address = $values['myticket_address'];
    if (!empty($myticket_address)) {
        wc_add_order_item_meta($itemID, esc_html__( 'address', 'myticket' ), $myticket_address);
    }
}
add_action('woocommerce_add_order_item_meta','myticket_add_order_item_meta', 1, 2); */

//Mel: 20/08/19. Added after upgrading to myticket theme 1.0.7. Comment out according to the latest theme ver at C:\mydoc\tto\tech\myticket\150819 - from themes dot kenzap dot com slash myticket\myticket\myticket\inc
/** 
 * Register new status
 * Tutorial: http://www.sellwithwp.com/woocommerce-custom-order-status-2/
**/
/* function myticket_register_custom_order_status() {
    register_post_status( 'wc-validated', array(
        'label'                     => 'Validated',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Validated <span class="count">(%s)</span>', 'Validated <span class="count">(%s)</span>', 'myticket' )
    ) );
}
add_action( 'init', 'myticket_register_custom_order_status' ); */

//Mel: 20/08/19. Added after upgrading to myticket theme 1.0.7. Comment out according to the latest theme ver at C:\mydoc\tto\tech\myticket\150819 - from themes dot kenzap dot com slash myticket\myticket\myticket\inc
// Add to list of WC Order statuses
/* function myticket_add_custom_order_status( $order_statuses ) {

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
add_filter( 'wc_order_statuses', 'myticket_add_custom_order_status' ); */

// Extra woocommerce stylings
function myticket_wc_custom() {  

    $theme = wp_get_theme('myticket');
    $version = $theme['Version'];
    wp_enqueue_style( 'myticket-wc', get_template_directory_uri() .'/css/admin.css', array(), $version, 'all' ); 
}
add_action( 'admin_enqueue_scripts', 'myticket_wc_custom' );

//Mel: 20/08/19. Added after upgrading to myticket theme 1.0.7. Comment out according to the latest theme ver at C:\mydoc\tto\tech\myticket\150819 - from themes dot kenzap dot com slash myticket\myticket\myticket\inc
//split cart items if quntity for the same item is > 1
/* function mai_split_multiple_quantity_products_to_separate_cart_items( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

    // If product has more than 1 quantity
    if ( $quantity > 1 ) {

        // Keep the product but set its quantity to 1
        WC()->cart->set_quantity( $cart_item_key, 1 );

        // Run a loop 1 less than the total quantity
        for ( $i = 1; $i <= $quantity -1; $i++ ) {
            
            //Set a unique key.
            //This is what actually forces the product into its own cart line item
            $cart_item_data['unique_key'] = md5( microtime() . rand() . "Hi Mom!" );

            // Add the product as a new line item with the same variations that were passed
            WC()->cart->add_to_cart( $product_id, 1, $variation_id, $variation, $cart_item_data );
        }

    }
}
if ( get_theme_mod('myticket_combine','') != 1 ) { add_action( 'woocommerce_add_to_cart', 'mai_split_multiple_quantity_products_to_separate_cart_items', 10, 6 ); } */
