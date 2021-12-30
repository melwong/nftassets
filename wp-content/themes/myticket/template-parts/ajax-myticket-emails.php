<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package myticket
 */

//add_action('wp_ajax_nopriv_myticket_send_emails_ajax', 'myticket_send_emails');
//add_action('wp_ajax_myticket_send_emails_ajax', 'myticket_send_emails');
if ( ! function_exists( 'myticket_send_emails' ) ) {
    function myticket_send_emails() {

        if ( '1' == get_theme_mod('myticket_participants', '0') && '1' == get_theme_mod('myticket_email_thankyou', '0') ){ 

            $output   = [];

            $orderid = (isset($_POST['orderid'])) ? $_POST['orderid'] : "";
            myticket_process_qrcode_email($orderid);

            $output['success'] = true;
            echo json_encode($output);  
            wp_reset_postdata();
            wp_die();
        }
    }
}

function myticket_woocommerce_order_status_completed( $order_id ) {
  
    if ( '1' == get_theme_mod('myticket_participants', '0') && '1' == get_theme_mod('myticket_email_order_completed', '1') ){ 
        myticket_process_qrcode_email( $order_id );
    }
}
//add_action( 'woocommerce_order_status_completed', 'myticket_woocommerce_order_status_completed', 10, 1 );

function myticket_process_qrcode_email( $orderid ){
        
    //send email to each participant
    $order = new WC_Order( $orderid );
    if ( sizeof( $order->get_items() ) > 0 ) {
        foreach( $order->get_items() as $item ) {
           
            $output   = [];

            $time = wc_get_order_item_meta( $item->get_id(), "time", true );
            $venue = wc_get_order_item_meta( $item->get_id(), "venue", true );
            $address = wc_get_order_item_meta( $item->get_id(), "address", true );
            $name = wc_get_order_item_meta( $item->get_id(), "name", true );
            $email = wc_get_order_item_meta( $item->get_id(), "email", true );

            $to = $email;
            $subject = $item->get_name();
            $body = "<b>".esc_html__( 'Ticket for:', 'myticket' )."</b> ".$item->get_name()."<br>";
            $body .= "<b>".esc_html__( 'Venue:', 'myticket' )."</b> ".$venue."<br>";
            $body .= "<b>".esc_html__( 'Address:', 'myticket' )."</b> ".$address."<br>";
            $body .= "<b>".esc_html__( 'Time: ', 'myticket' )."</b> ".$time."<br>";
            $body .= "<b>".esc_html__( 'Ticket Holder: ', 'myticket' )."</b> ".$name."<br>";
            $body .= "<b>".esc_html__( 'Please use the QR-code below to enter the event.', 'myticket' )."</b><br><br>";
            $headers = array('Content-Type: text/html; charset=UTF-8');
            
            $ajaxurl = '';
            if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
                $ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
            } else{
                $ajaxurl .= admin_url( 'admin-ajax.php');
            }

            $body .= '<img src="'.get_site_url().'/wp-content/plugins/myticket-plugin/custom/qrcode.php?id='.$orderid.'item_id='.$item->get_id().'&url='.$ajaxurl.'" />';

            //echo $body;
            $mail = wp_mail( $to, $subject, $body, $headers );
            $output['mail'] = $mail;
        }
    }
}