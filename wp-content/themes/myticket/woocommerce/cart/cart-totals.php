<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$myticket_calories_total = $myticket_proteins_total = $myticket_fats_total = $myticket_carbohydrates_total = 0;
$metering = get_theme_mod( 'myticket_metering', 'g' );
$metering_cal = get_theme_mod( 'myticket_calories', '' );
foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
	$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

    $myticket_title = get_post_meta( $product_id, 'myticket_title', '');
    if( sizeof($myticket_title)==0 ) $myticket_title[0] = '';

    $myticket_calories = get_post_meta( $product_id, 'myticket_calories', '');
    if( sizeof($myticket_calories)==0 ) { $myticket_calories[0] = ''; }else{ $myticket_calories_total += (intval($myticket_calories[0]) * $cart_item['quantity']); }

    $myticket_proteins = get_post_meta( $product_id, 'myticket_proteins', '');
    if( sizeof($myticket_proteins)==0 ) { $myticket_proteins[0] = ''; }else{ $myticket_proteins_total += (intval($myticket_proteins[0]) * $cart_item['quantity']); }

    $myticket_fats = get_post_meta( $product_id, 'myticket_fats', '');
    if( sizeof($myticket_fats)==0 ) { $myticket_fats[0] = ''; }else{ $myticket_fats_total += (intval($myticket_fats[0]) * $cart_item['quantity']); }

    $myticket_carbohydrates = get_post_meta( $product_id, 'myticket_carbohydrates', '');
    if( sizeof($myticket_carbohydrates)==0 ) { $myticket_carbohydrates[0] = ''; }else{ $myticket_carbohydrates_total += (intval($myticket_carbohydrates[0]) * $cart_item['quantity']); }
}
?>
<div class="cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">



	<!-- nutritation-->
	<div class="col-xs-12 col-sm-5 wow fadeInLeft hidden">
	    <div class="nutrition-fact nutrition-fact-ajax">
	        <h6><?php echo esc_html__('Nutrition Facts Calculated', 'myticket'); ?></h6>
	        <div class="facts-table">
	            <table>
	                <tbody>
	                    <tr>
	                        <td><span><?php echo esc_html__('Calories', 'myticket'); ?></span></td>
	                        <td><span class="cart_calories"><?php echo esc_attr(number_format($myticket_calories_total,0," "," ").$metering_cal); ?></span></td>
	                    </tr>
	                    <tr>
	                        <td><span class="cart_proteins"><?php echo esc_html__('Proteins', 'myticket'); ?></span></td>
	                        <td><span><?php echo esc_attr(number_format($myticket_proteins_total,0," "," ").$metering); ?></span></td>
	                    </tr>
	                    <tr>
	                        <td><span><?php echo esc_html__('Fats', 'myticket'); ?></span></td>
	                        <td><span class="cart_fats"><?php echo esc_attr(number_format($myticket_fats_total,0," "," ").$metering); ?></span></td>
	                    </tr>
	                    <tr>
	                        <td><span><?php echo esc_html__('Carbohydrates', 'myticket'); ?></span></td>
	                        <td><span class="cart_carbohydrates"><?php echo esc_attr(number_format($myticket_carbohydrates_total,0," "," ").$metering); ?></span></td>
	                    </tr>
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>
	<!-- nutritation ends-->
<!-- 	<div class="col-xs-12 col-sm-2 wow fadeInLeft">

	</div> -->
	<!-- cart proceed -->
	<div class="col-xs-12 col-sm-6  text-right wow fadeInRight pull-right">



		<?php do_action( 'woocommerce_before_cart_totals' ); ?>

		<h2><?php esc_html_e( 'Cart totals', 'myticket' ); ?></h2>

		<table cellspacing="0" class="shop_table shop_table_responsive">

			<tr class="cart-subtotal">
				<th><?php esc_html_e( 'Subtotal', 'myticket' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Subtotal', 'myticket' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>

			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

				<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

				<?php wc_cart_totals_shipping_html(); ?>

				<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

			<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

				<tr class="shipping">
					<th><?php esc_html_e( 'Shipping', 'myticket' ); ?></th>
					<td data-title="<?php esc_attr_e( 'Shipping', 'myticket' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
				</tr>

			<?php endif; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<tr class="fee">
					<th><?php echo esc_html( $fee->name ); ?></th>
					<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) :
				$taxable_address = WC()->customer->get_taxable_address();
				$estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
						? sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'myticket' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
						: '';

				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
					<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
						<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
							<th><?php echo esc_html( $tax->label ) . $estimated_text; ?></th>
							<td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr class="tax-total">
						<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></th>
						<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>

			<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

			<tr class="order-total">
				<th><?php esc_html_e( 'Total', 'myticket' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Total', 'myticket' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>

			<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

		</table>

		<div class="wc-proceed-to-checkout">
			<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_cart_totals' ); ?>





	</div>
</div>
