<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
   
?>

<main> 
	<!-- main content starts -->
    <!-- ============== cart page starts ============== -->

	<div class="container ">
           
        <div class="row block">
            <!--shop table-->
            <div class="col-xs-12 col-sm-10 col-sm-offset-1 shop-list">
             <?php wc_print_notices(); ?> 

<?php
do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<table class="shop_table shop_table_responsive cart shop_table_products" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-thumbnail">&nbsp;</th>
			<th class="product-name" colspan="2"><?php esc_html_e( 'Event Name', 'myticket' ); ?></th>
			<th class="product-price"><?php esc_html_e( 'Price', 'myticket' ); ?></th>
			<th class="product-quantity"><?php esc_html_e( 'Tickets', 'myticket' ); ?></th>
			<th class="product-subtotal"><?php esc_html_e( 'Total', 'myticket' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php

		$allow_checkout = true;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-remove" data-title="<?php esc_html_e( 'Action', 'myticket' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_html__( 'Remove this item', 'myticket' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
						?>
					</td>

					<td class="product-thumbnail">
						<?php
							$thumbnail = apply_filters( 'myticket-thumb', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo '<figure class="product-thumb">'.$thumbnail.'</figure>';
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
							}
						?>
					</td>

					<td class="product-name" data-title="<?php esc_html_e( 'Product', 'myticket' ); ?>" colspan="2">
						<div class="prod-disc clear">
						<?php
							if ( ! $product_permalink ) {
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
							} else {
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<h6 class="text-sp"><a href="%s">%s</a></h6>', esc_url( $product_permalink ), $_product->get_title() ), $cart_item, $cart_item_key );
							}

				            $myticket_title = get_post_meta( $product_id, 'myticket_title', '');
                            if( sizeof($myticket_title)==0 ) $myticket_title[0] = '';

                            $temp = get_post_meta( $product_id, 'myticket_datetime_start', '' );
                            if ( sizeof($temp) ){
                            	
                            	$myticket_time = date_i18n(  get_option( 'date_format' )." | ".get_option( 'time_format' ), intval( $temp[0] ) );                                         
								echo '<div class="text-sp venue"><em>'.esc_attr($myticket_time).' <span>@ '.esc_attr($myticket_title[0]).'</span></em></div>'; 
							
							} 

							?>

							<?php
							// Meta data
							echo wc_get_formatted_cart_item_data( $cart_item );

							// if ( '1' == get_theme_mod('myticket_participants', '0') ){ 
							// 	echo '<br><div class="name2"><input class="cart_pers" type="text" name="cart['.$cart_item_key.'][myticket_name]" value="'.$cart_item['myticket_name'].'" placeholder="'.esc_attr__( 'Full Name', 'myticket' ).'" /></div>';
							// 	echo '<div class="email2 name2"><input class="cart_pers" type="email" name="cart['.$cart_item_key.'][myticket_email]" value="'.$cart_item['myticket_email'].'" placeholder="'.esc_attr__( 'Email', 'myticket' ).'" /></div>';
							// 	echo '<div class="update">'.esc_attr__( '*Update cart after changes', 'myticket' ).'</div>';
							// 	if ( strlen($cart_item['myticket_name']) < 2 && strlen($cart_item['myticket_email']) < 5 ){
							// 		$allow_checkout = false;
							// 	}
							// } 

							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'myticket' ) . '</p>';
							}
						?>
						</div>
					</td>

					<td class="product-price" data-title="<?php esc_html_e( 'Price', 'myticket' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-quantity" data-title="<?php esc_html_e( 'Quantity', 'myticket' ); ?>">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									'min_value'   => '0'
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?>
					</td>

					<td class="product-subtotal" data-title="<?php esc_html_e( 'Total', 'myticket' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td colspan="6" class="actions">

				<?php if ( wc_coupons_enabled() ) { ?>
					<div class="coupon">

						<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'myticket' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'myticket' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'myticket' ); ?>" />

						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				<?php } ?>

				<input type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'myticket' ); ?>" />

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">

	<?php if ( $allow_checkout ) do_action( 'woocommerce_cart_collaterals' ); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

			</div>
		</div>

	</div>
</main>