<?php
/**
 * The template for displaying vendor order detail and called from vendor_order_item.php template
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/vendor-orders/vendor-order-details.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   2.2.0
 */
if (!defined('ABSPATH')) {
    // Exit if accessed directly    
    exit;
}
global $woocommerce, $WCMp;
$vendor = get_current_vendor();
$order = wc_get_order($order_id);
if (!$order) {
    ?>
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php _e('Invalid order', 'dc-woocommerce-multi-vendor'); ?>
        </div>
    </div>
    <?php
    return;
}
// Get the payment gateway
$payment_gateway = wc_get_payment_gateway_by_order( $order );
$vendor_order = wcmp_get_order($order_id);
$vendor_shipping_method = get_wcmp_vendor_order_shipping_method($order->get_id(), $vendor->id);
$vendor_items = get_wcmp_vendor_orders(array('order_id' => $order->get_id(), 'vendor_id' => $vendor->id));
$vendor_order_amount = get_wcmp_vendor_order_amount(array('order_id' => $order->get_id(), 'vendor_id' => $vendor->id));
$subtotal = 0;
?>

<!--Mel: 26/09/19. To upgrade from 3.3.4 to 3.4.3 -->
<!--Mel: Begin -->
<?php

//$order = wc_get_order( $order_id );

//Mel: 26/09/19. Commented out cos it's not being used anymore
/* $items = $order->get_items();

foreach ( $items as $item ) {
    $product_name = $item->get_name();
    $product_id = $item->get_product_id();
}
	$product = new WC_Product($product_id);
	
	$contract_address = get_post_meta($product_id, 'smart_contract_address', true);
	$recipient_address = get_post_meta($order->get_id(), 'wallet_address', true);
	$abi = get_post_meta($product_id, 'abi', true);
	$token_id = $product->get_attribute('TokenID');
	 */
	/*
	echo "order id: " . $order->get_id();
	echo "<br >";
	echo "product id: " . $product_id;
	echo "<br >";
	echo "contract: " . $contract_address;
	echo "<br >";
	echo "wallet_address: " . $contract_address;
	echo "<br />";
	*/
?>

<script>

//Mel: 26/09/19. Commented out cos it's not being used anymore
//To post the form data to the MetaMask connector by opening a new window
/* function post_to_url(path, method) {
    method = method || "post"; // Set method to post by default, if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
	
	// setting form target to a window named 'result-form'
	form.setAttribute("target", "result-form");
	
	//Adding hidden input fields with values
    var addField = function( key, value ){
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", value );

        form.appendChild(hiddenField);
    }; 
	
	addField('contract_address', '<?php echo $contract_address; ?>');
	addField('recipient_address', '<?php echo $recipient_address; ?>');
	addField('abi', '<?php echo $abi; ?>');
	addField('token_id', '<?php echo $token_id; ?>');
	addField('order_id', '<?php echo $order->get_id(); ?>');

    document.body.appendChild(form);
	
	// creating the 'result-form' window with custom features prior to submitting the form
	window.open('test.html', 'result-form', 'scrollbars=no,resizable=no,status=yes,toolbar=no,menubar=no,width=600,height=300,left=0,top=0');
	
    form.submit();
} */

</script>
<!--Mel: End-->

<div id="wcmp-order-details" class="col-md-12">
    <div class="panel panel-default panel-pading pannel-outer-heading mt-0 order-detail-top-panel">
        <div class="panel-heading clearfix">
            <h3 class="pull-left">
                <?php 
                /* translators: 1: order type 2: order number */
                printf(
                        esc_html__( 'Order details #%1$s', 'dc-woocommerce-multi-vendor' ),
                        esc_html( $order->get_order_number() )
                ); ?>
                <input type="hidden" id="order_ID" value="<?php echo $order->get_id(); ?>" />
            </h3>
            <div class="change-status pull-left">
                <div class="order-status-text pull-left <?php echo 'wc-' . $order->get_status( 'edit' ); ?>">
                    <i class="wcmp-font ico-pendingpayment-status-icon"></i>
                    <span class="order_status_lbl"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
                </div>
                <?php if( $order->get_status( 'edit' ) != 'cancelled' ) : ?>
                <div class="dropdown-order-statuses dropdown pull-left clearfix">
                    <span class="order-status-edit-button pull-left dropdown-toggle" data-toggle="dropdown"><u><?php _e( 'Edit', 'dc-woocommerce-multi-vendor' ); ?></u></span>
                    <input type="hidden" id="order_current_status" value="<?php echo 'wc-' . $order->get_status( 'edit' ); ?>" />
                    <ul id="order_status" class="dropdown-menu dropdown-menu-right" style="margin-top:9px;z-index:1;">
                            <?php
                            $statuses = apply_filters( 'wcmp_vendor_order_statuses', wc_get_order_statuses(), $order );
                            foreach ( $statuses as $status => $status_name ) {
                                    echo '<li><a href="javascript:void(0);" data-status="' . esc_attr( $status ) . '" ' . selected( $status, 'wc-' . $order->get_status( 'edit' ), false ) . '>' . esc_html( $status_name ) . '</a></li>';
                            }
                            ?>
                    </ul>   
                </div>   
                <?php endif; ?>
            </div>
        </div>
        <?php
        $WCMp->template->get_template( 'vendor-dashboard/vendor-orders/views/html-order-info.php', array( 'order' => $order, 'vendor_order' => $vendor_order, 'vendor' => $vendor ) );
        ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            $WCMp->template->get_template( 'vendor-dashboard/vendor-orders/views/html-order-items.php', array( 'order' => $order, 'vendor_order' => $vendor_order, 'vendor' => $vendor ) );
            ?>
        </div>
        
        <div class="col-md-8">
            <!-- Downloadable product permissions -->
            <?php
            $WCMp->template->get_template( 'vendor-dashboard/vendor-orders/views/html-order-downloadable-permissions.php', array( 'order' => $order, 'vendor_order' => $vendor_order, 'vendor' => $vendor ) );
            ?>    
        </div>
        
        <div class="col-md-4">
            <?php
            $WCMp->template->get_template( 'vendor-dashboard/vendor-orders/views/html-order-notes.php', array( 'order' => $order, 'vendor_order' => $vendor_order, 'vendor' => $vendor ) );
            ?>
        </div>
        
    </div>
</div>


