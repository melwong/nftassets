<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

?>

<section class="section-page-content error-404">
	<div class="container">
		<div class="row">					
			<div id="primary" class="col-sm-12 col-md-12">
				<div class="section-404">
					<div class="col-sm-offset-5 col-sm-7 col-md-offset-6 col-md-6">
						<h2><?php esc_html_e( 'CART', 'myticket' );?></h2>
						<p><?php esc_html_e( 'Nothing to show! Your ', 'myticket' ); ?> <br> <?php esc_html_e( ' cart is currently empty', 'myticket' ); ?></p>
						<a href="<?php echo get_home_url();?>" class="secondary-link"><?php esc_html_e( 'Back to homepage', 'myticket' );?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<?php //do_action( 'woocommerce_cart_is_empty' ); ?>

<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<p class="return-to-shop">
		<a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php esc_html_e( 'Return to shop', 'myticket' ) ?>
		</a>
	</p>
<?php endif; ?>
