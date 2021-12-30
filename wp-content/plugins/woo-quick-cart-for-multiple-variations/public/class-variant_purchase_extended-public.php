<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.thedotstore.com/
 * @since      1.0.0
 *
 * @package    woo-quick-cart-for-multiple-variations
 * @subpackage woo-quick-cart-for-multiple-variations/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    woo-quick-cart-for-multiple-variations
 * @subpackage woo-quick-cart-for-multiple-variations/public
 * @author     Thedotstore <wordpress@thedotstore.com>
 */
class Variant_Purchase_Extended_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	function wqcmv_enqueue_styles() {

		global $post;
		$product_data = wc_get_product( $post->ID );

		$enqueue_style = false;
		if ( ! empty( $product_data ) && 'variable' === $product_data->get_type() ) {
			$enqueue_style = true;
		} elseif ( has_shortcode( $post->post_content, 'vpe-woo-variable-product' ) ) {
			$enqueue_style = true;
		}

		if ( $enqueue_style ) {
			$theme = wp_get_theme();
			wp_enqueue_style( $this->plugin_name . '-variable-image-fancybox-css', WQCMV_PLUGIN_URL . 'public/css/jquery.fancybox.css', '', '2.1.5' );
			/*
			 * Enqueue CSS based on activated theme.
			 * */

			if ( 'Twenty Sixteen' === $theme->name || 'Twenty Sixteen' === $theme->parent_theme ) {
				wp_enqueue_style( $this->plugin_name, WQCMV_PLUGIN_URL . 'public/css/twenty-sixteen.css', array(), $this->version, 'all' );
			} else if ( 'Twenty Seventeen' === $theme->name || 'Twenty Seventeen' === $theme->parent_theme ) {
				wp_enqueue_style( $this->plugin_name, WQCMV_PLUGIN_URL . 'public/css/twenty-seventeen.css', array(), $this->version, 'all' );
			} else if ( 'Twenty Eighteen' === $theme->name || 'Twenty Eighteen' === $theme->parent_theme ) {
				wp_enqueue_style( $this->plugin_name, WQCMV_PLUGIN_URL . 'public/css/twenty-eighteen.css', array(), $this->version, 'all' );
			} else if ( 'Twenty Nineteen' === $theme->name || 'Twenty Nineteen' === $theme->parent_theme ) {
				wp_enqueue_style( $this->plugin_name, WQCMV_PLUGIN_URL . 'public/css/twenty-nineteen.css', array(), $this->version, 'all' );
			} else if ( 'Salient' === $theme->name || 'Salient' === $theme->parent_theme ) {
				wp_enqueue_style( $this->plugin_name, WQCMV_PLUGIN_URL . 'public/css/sallient.css', array(), $this->version, 'all' );
			} else {
				wp_enqueue_style( $this->plugin_name, WQCMV_PLUGIN_URL . 'public/css/general.css', array(), $this->version, 'all' );
			}
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	function wqcmv_enqueue_scripts() {
		global $post;
		$product_data = wc_get_product( $post->ID );

		$enqueue_script = false;
		if ( ! empty( $product_data ) && 'variable' === $product_data->get_type() ) {
			$enqueue_script = true;
		} elseif ( has_shortcode( $post->post_content, 'vpe-woo-variable-product' ) ) {
			$enqueue_script = true;
		}

		if ( $enqueue_script ) {
			wp_enqueue_script( 'variable-image-fancybox-js', WQCMV_PLUGIN_URL . 'public/js/jquery.fancybox.pack.js', array( 'jquery' ), '2.1.5', true );
			wp_enqueue_script( 'variable-purchase-public-js', WQCMV_PLUGIN_URL . 'public/js/variant_purchase_extended-public.js', array( 'jquery' ), $this->version, true );
			$localize_arr = array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'loader_url' => includes_url( 'images/spinner-2x.gif' ),
			);
			wp_localize_script( 'variable-purchase-public-js', 'WQCMV_Public_JS_Obj', $localize_arr );
		}
	}

	/*
	 * Hook For Remove Default Single Variation Add to Cart Button
	 */
	function wqcmv_remove_default_add_to_cart_button() {
		global $product;
		$product_id               = $product->get_id();
		$product_visiblity_option = get_post_meta( $product_id, 'product_visiblity_option', true );
		if ( 'variable' === $product->get_type() ) {
			if ( 'yes' === $product_visiblity_option ) {
				remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
			}
		}
	}

	/*
	 * Hook For Remove Default Single Variation Form (HTML)
	 */
	function wqcmv_remove_default_variation_form() {
		global $product;
		$product_id               = $product->get_id();
		$product_visiblity_option = get_post_meta( $product_id, 'product_visiblity_option', true );
		if ( 'variable' === $product->get_type() ) {
			if ( 'yes' === $product_visiblity_option ) {
				remove_action( 'woocommerce_dropdown_variation_attribute_options_html', 'woocommerce_template_single_add_to_cart', 30 );
			}
		}
	}

	/*
	 * Load Custom HTML For Variation Product
	 */
	function wqcmv_woocommerce_after_single_product_summary() {
		global $product;
		$product_id = $product->get_id();
		echo do_shortcode( '[vpe-woo-variable-product id="' . $product_id . '"]' );
	}

	/**
	 * Define the shortcode template.
	 *
	 * @param $atts
	 *
	 * @return false|string
	 */
	function wqcmv_shortcode_template( $atts ) {

		$product_id = ( isset( $atts['id'] ) && ! empty( $atts['id'] ) ) ? $atts['id'] : '';
		if ( '' !== $product_id ) {
			$product_visiblity_option = get_post_meta( $product_id, 'product_visiblity_option', true );
			ob_start();
			if ( 'yes' === $product_visiblity_option ) {
				$enable_stock_visibility_option = get_option( 'vpe_enable_stock_visibility' );
				$add_to_cart_button_text        = get_option( 'vpe_add_to_cart_button_text' );
				$variation_per_page_option      = (int) get_option( 'vpe_variation_per_page' );
				$variations_per_page            = - 1;
				if ( ! empty( $variation_per_page_option ) && 0 !== $variation_per_page_option ) {
					$variations_per_page = $variation_per_page_option;
				}
				$variations = wqcmv_conditional_logic_variation( $product_id, $variations_per_page );
				?>
				<div class="vpe-variations-container">
					<div class="vpe_table_responsive">
						<table class="vpe_table" style="width:100%" id="vpe_table">
							<thead>
							<tr>
								<th><?php esc_html_e( 'Title', 'woo-quick-cart-for-multiple-variations' ); ?></th>
								<th><?php esc_html_e( 'SKU', 'woo-quick-cart-for-multiple-variations' ); ?></th>
								<?php if ( 'yes' === $enable_stock_visibility_option ) { ?>
									<th><?php esc_html_e( 'Stock Status', 'woo-quick-cart-for-multiple-variations' ); ?></th>
								<?php } ?>
								<th><?php esc_html_e( 'Price', 'woo-quick-cart-for-multiple-variations' ); ?></th>
								<th><?php esc_html_e( 'Quantity', 'woo-quick-cart-for-multiple-variations' ); ?></th>
							</tr>
							</thead>
							<tbody class="pagination_row">
							<?php
							foreach ( $variations as $variation_id ) {
								echo wqcmv_fetch_product_block_html( $variation_id );
							}
							?>
							</tbody>
						</table>
					</div>
					<!-- PAGINATION OF Products -->
					<?php
					$total_variations = wqcmv_conditional_logic_variation( $product_id );

					if ( 0 !== $variation_per_page_option ) {
						if ( count( $total_variations ) > $variation_per_page_option ) {
							?>
							<div class="pagination-for-products">
								<button type="button" data-loadchunk="0"
								        class="prev products-pagination vpe-normal-directory-paginate vpe-core-btn"
								        disabled="disabled">
									<?php esc_html_e( 'Previous', 'woo-quick-cart-for-multiple-variations' ); ?>
								</button>
								<button type="button" data-loadchunk="1"
								        class="next products-pagination vpe-normal-directory-paginate vpe-core-btn"><?php esc_html_e( 'Next', 'woo-quick-cart-for-multiple-variations' ); ?>
								</button>
								<input type="hidden" id="vpe-active-chunk" value="0">
								<input type="hidden" id="vpe-next-chunk" value="1">
							</div>
						<?php }
					} ?>
					<div class="vpe_container_btn">
						<button type="button"
						        class="vpe_single_add_to_cart_button"
						        disabled><?php echo ( ! empty( $add_to_cart_button_text ) ) ? $add_to_cart_button_text : esc_html__( 'Add to cart', 'woo-quick-cart-for-multiple-variations' ); ?></button>
						<a href="<?php echo wc_get_cart_url(); ?>"
						   class="vpe-view-cart"><?php esc_html_e( 'View Cart', 'woo-quick-cart-for-multiple-variations' ); ?></a>
					</div>
					<div class="error-message-blk"></div>
					<div class="vpe-ajax-loader">
						<img src="<?php echo includes_url( 'images/spinner-2x.gif' ); ?>" alt="spinner-2x">
						<p class="vpe-ajax-loader-message"></p>
						<input type="hidden" id="vpe-parent-product-id" value="<?php echo $product_id; ?>" />
					</div>
				</div>
				<?php
			}

			return ob_get_clean();
		} else {
			echo 'Product ID missing';
		}

	}

	/**
	 * Add CSS On Variation Product
	 */
	function wqcmv_variation_products_css() {
		global $post;
		$product_visiblity_option = get_post_meta( $post->ID, 'product_visiblity_option', true );
		if ( 'yes' === $product_visiblity_option ) {
			?>
			<style>
				table.variations, .single_variation_wrap, .product-type-variable .product_meta {
					display: none !important;
				}
			</style>
			<?php
		}

	}

	/**
	 * Ajax Call For Get Variable Product As Quantity From Single Add to Cart Button
	 */
	function wqcmv_woocommerce_ajax_add_to_cart() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( isset( $action ) && 'wqcmv_woocommerce_ajax_add_to_cart' === $action ) {
			$variations = wp_unslash( $_POST['variations'] );
			$product_id = filter_input( INPUT_POST, 'parent_product_id', FILTER_SANITIZE_STRING );
			foreach ( $variations as $variation ) {
				$vid = (int) $variation['vid'];
				$qty = (int) $variation['qty'];
				WC()->cart->add_to_cart( $product_id, $qty, $vid );
			}
			$cart_total            = WC()->cart->get_cart_total();
			$cart_count            = WC()->cart->get_cart_contents_count();
			$redirect_to_cart_page = get_option( 'woocommerce_cart_redirect_after_add' );
			$cart_url              = wc_get_page_permalink( 'cart' );

			$result = array(
				'message'          => 'vpe-product-added-to-cart-prac',
				'cart_count'       => $cart_count,
				'redirect_to_cart' => $redirect_to_cart_page,
				'cart_url'         => $cart_url,
				'cart_total'       => $cart_total,
			);
			wp_send_json_success( $result );
			wp_die();
		}

	}


	/**
	 * AJAX function called to update the mini cart.
	 *
	 * @since    1.0.0
	 * @author   Thedotstore <info@thedotstore.com>
	 */
	function wqcmv_update_mini_cart() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( isset( $action ) && 'wqcmv_update_mini_cart' === $action ) {
			echo wc_get_template( 'cart/mini-cart.php' );
			wp_die();
		}

	}

	/**
	 * Products Pagination Ajax Call
	 */
	function wqcmv_products_pagination() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( isset( $action ) && 'wqcmv_products_pagination' === $action ) {
			$product_id                = filter_input( INPUT_POST, 'parent_product_id', FILTER_SANITIZE_STRING );
			$changed_variations        = ( isset( $_POST['changed_variations'] ) && ! empty( $_POST['changed_variations'] ) ) ? wp_unslash( $_POST['changed_variations'] ) : array();
			$variation_per_page_option = (int) get_option( 'vpe_variation_per_page' );
			$variation_pids            = wqcmv_conditional_logic_variation( $product_id );
			$loadchunk                 = (int) filter_input( INPUT_POST, 'loadchunk', FILTER_SANITIZE_STRING );
			$array_chunked             = array_chunk( $variation_pids, $variation_per_page_option );

			$html = '';
			if ( array_key_exists( $loadchunk, $array_chunked ) ) {
				$chunk_available = 'yes';
				$chunk           = $array_chunked[ $loadchunk ];
				foreach ( $chunk as $chunk_product_id ) {
					$html .= wqcmv_fetch_product_block_html( $chunk_product_id, $changed_variations );
				}
			} else {
				$chunk_available = 'no';
			}
			$result = array(
				'message'              => 'vpe-product-pagination',
				'html'                 => $html,
				'loadchunk'            => $loadchunk,
				'chunk_available'      => $chunk_available,
				'next_chunk_available' => ( isset( $array_chunked[ $loadchunk + 1 ] ) && ! empty( $array_chunked[ $loadchunk + 1 ] ) ) ? 'yes' : 'no',
				'prev_chunk_available' => ( isset( $array_chunked[ $loadchunk - 1 ] ) && ! empty( $array_chunked[ $loadchunk - 1 ] ) ) ? 'yes' : 'no',
			);
			wp_send_json_success( $result );
			wp_die();
		}
	}

	/**
	 * Filter added to add a class in the body.
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	function wqcmv_body_classes( $classes ) {

		global $post;
		if ( has_shortcode( $post->post_content, 'vpe-woo-variable-product' ) ) {
			$classes[] = 'vpe-shortcode';
		}

		return $classes;

	}

}
