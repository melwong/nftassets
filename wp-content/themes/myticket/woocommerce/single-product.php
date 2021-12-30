<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
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
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<?php get_header( 'shop' ); ?>
<div class="site-content content-wrapper page-content latest-post product-single">
<div class="container">
	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php 
			
			//Mel: 20/08/19. Added when upgraded to latest 1.0.7 theme. 
			//To add the param at the end of the product URL with ?get=tickets or ?get=metamask or ?get=paperwallet so that we could use the product page to retreive the ticket (check-in). Example, http://localhost/boothstand/product/bruno-mars-concert/?get=tickets
			
			//wc_get_template_part( 'content', 'single-product' );
			
			$get_method = $_GET['get'];
			
			if ($get_method) {
				
				if ($get_method == 'tickets') {
					wc_get_template_part( 'content', 'single-product-get-tickets' );
				} elseif ($get_method == 'metamask') {
					wc_get_template_part( 'content', 'single-product-get-metamask' );
				} elseif ($get_method == 'paperwallet') {
					wc_get_template_part( 'content', 'single-product-get-paperwallet' );
				} else {
					wc_get_template_part( 'content', 'single-product' );
				}
				
			} else {
				wc_get_template_part( 'content', 'single-product' );
			}
			
			//Mel: End	
			
			
			?>

		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		$sidebar_sprod = get_theme_mod('sidebar_sprod', '1');

		if ( $sidebar_sprod == '1' ) : 
			do_action( 'woocommerce_sidebar' );
		endif;
	?>
</div>
</div>
<?php get_footer( 'shop' ); ?>

