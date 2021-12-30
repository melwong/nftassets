<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<!--Mel: Begin-->
<?php 
global $product, $woocommerce, $post;

$product_id = $product->get_id();
	
$product = new WC_Product($product_id);

$contract_address = get_post_meta($product_id, 'smart_contract_address', true);
$abi = get_post_meta($product_id, 'abi', true);
$token_id = $product->get_attribute('TokenID');
$res = get_post_meta($product->id);

?>
<!--Mel: End-->


<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			 
			//Mel: Begin
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
			
			do_action( 'woocommerce_single_product_summary' );
			
			echo "<br /><br />";
			echo "<a href='". get_permalink() . "?get=metamask' ><img src='" . get_template_directory_uri() . "/images/connect-with-metamask.png' width='200'></a>";
			
		?>
		<br /><br />
		<!--Mel: To toggle the form that accepts paper wallet info to retrieve the tickets -->
		<button type="button" id="paperWalletFormButton"><?php esc_html_e('Retrieve Tickets Using Wallet','myticket'); ?></button>

		 <form id="paper-wallet-form" method="post" action="<?php echo htmlspecialchars(get_permalink()) . '?get=paperwallet'; ?>">
				<div style="line-height: 3;">
				<label><?php esc_html_e('Enter Your Wallet Private Key', 'myticket'); ?></label>
				<br />
				<input type="text" size="65" name="key" placeholder="e.g: f6b896131706c40f0245ea95d7877c9d174b4a9129b6c990ca99cd3774205ca6" required>
				<br />
				<input type="submit" class="" value="<?php esc_html_e('Retrieve Tickets','myticket'); ?>">
				</div>
		 </form>
		 
		 <!--Mel: The jquery script to toggle the form from appearing-->
		 <script src="<?php echo get_template_directory_uri(); ?>/js/jquery-3.4.1.min.js"></script>
		 <script>
			$("#paperWalletFormButton").click(function(){
				$("#paper-wallet-form").toggle();
			});
		 </script>
		

	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		
		//Mel: 07/07/19
		//do_action( 'woocommerce_after_single_product_summary' );
		
	?>

	<!--Mel
	<meta itemprop="url" content="<?php the_permalink(); ?>" />
	-->
	
</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
