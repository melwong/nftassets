<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.thedotstore.com/
 * @since      1.0.0
 *
 * @package    woo-quick-cart-for-multiple-variations
 * @subpackage woo-quick-cart-for-multiple-variations/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    woo-quick-cart-for-multiple-variations
 * @subpackage woo-quick-cart-for-multiple-variations/admin
 * @author     Thedotstore <wordpress@thedotstore.com>
 */
class Variant_Purchase_Extended_Admin {

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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	function wqcmv_enqueue_styles() {

		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		if ( isset( $page ) && ! empty( $page ) && 'woo-quick-cart-for-multiple-variations' === $page ) {
			wp_enqueue_style( $this->plugin_name . 'font-awesome', WQCMV_PLUGIN_URL . 'admin/css/font-awesome.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, WQCMV_PLUGIN_URL . 'admin/css/wc-variable-products-purchase-extended-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'fancybox-css', WQCMV_PLUGIN_URL . 'admin/css/jquery.fancybox.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'variant-purchase-extended-main-style', WQCMV_PLUGIN_URL . 'admin/css/style.css', array(), $this->version );
			wp_enqueue_style( 'variant-purchase-extended-media', WQCMV_PLUGIN_URL . 'admin/css/media.css', array(), $this->version );
			wp_enqueue_style( 'variant-purchase-extended-webkit', WQCMV_PLUGIN_URL . 'admin/css/webkit.css', array(), $this->version );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	function wqcmv_enqueue_scripts() {

		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		if ( isset( $page ) && ! empty( $page ) && 'woo-quick-cart-for-multiple-variations' === $page ) {
			wp_enqueue_script( $this->plugin_name, WQCMV_PLUGIN_URL . 'admin/js/wc-variable-products-purchase-extended-admin.js', array(
				'jquery',
				'jquery-ui-dialog',
			), $this->version, false );
			wp_enqueue_script( 'wp-pointer' ); //enqueue script for notice pointer
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_script( 'fancy-box', WQCMV_PLUGIN_URL . 'admin/js/jquery.fancybox.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( 'fancybox', WQCMV_PLUGIN_URL . 'admin/js/jquery.fancybox.pack.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( 'fancybox-buttons', WQCMV_PLUGIN_URL . 'admin/js/jquery.fancybox-buttons.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( 'fancybox-media', WQCMV_PLUGIN_URL . 'admin/js/jquery.fancybox-media.js', array( 'jquery' ), $this->version );
			wp_enqueue_script( 'fancybox-thumbs', WQCMV_PLUGIN_URL . 'admin/js/jquery.fancybox-thumbs.js', array( 'jquery' ), $this->version );
		}
	}

	// Function for welocme screen page

	function wqcmv_welcome_variable_purchase_extended_screen_do_activation_redirect() {

		if ( ! get_transient( '_welcome_screen_WC_Variable_Products_Purchase_Extended_activation_redirect_data' ) ) {
			return;
		}
		// Delete the redirect transient
		delete_transient( '_welcome_screen_WC_Variable_Products_Purchase_Extended_activation_redirect_data' );
		// if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}
		// Redirect to extra cost welcome  page
		wp_safe_redirect( html_entity_decode( add_query_arg( array( 'page' => 'woo-quick-cart-for-multiple-variations&tab=wqcmv_variant_purchase_extended_get_started_method' ), admin_url( 'admin.php' ) ) ) );
	}

	/**
	 * Dotstore menu.
	 */
	// custom menu for dots store menu

	public function wqcmv_dot_store_menu_traking_fbg() {
		global $GLOBALS;
		if ( empty( $GLOBALS['admin_page_hooks']['dots_store'] ) ) {
			add_menu_page(
				esc_html__( 'DotStore Plugins', 'woo-quick-cart-for-multiple-variations' ), esc_html__( 'DotStore Plugins', 'woo-quick-cart-for-multiple-variations' ), 'NULL', 'dots_store', array(
				$this,
				'dot_store_menu_customer_io',
			), WQCMV_PLUGIN_URL . 'admin/images/menu-icon.png', 25
			);
		}
	}

	// custom submenu for extra flate rate shipping

	public function wqcmv_add_new_menu_items_traking_fbg() {
		add_submenu_page( "dots_store", esc_html__( 'WooCommerce Quick Cart for Multiple Variation', 'woo-quick-cart-for-multiple-variations' ), esc_html__( 'WooCommerce Quick Cart for Multiple Variation', 'woo-quick-cart-for-multiple-variations' ), "manage_options", "woo-quick-cart-for-multiple-variations", 'custom_variant_extended', "", 99 );

		function custom_variant_extended() {

			$url = admin_url( 'admin.php?page=woo-quick-cart-for-multiple-variations&tab=wqcmv_variant_purchase_extended' );
			$tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			include_once( 'partials/header/plugin-header.php' );
			if ( ! empty( $tab ) ) {
				if ( 'wqcmv_variant_purchase_extended' === $tab ) {
					wqcmv_variable_product_purchase_extended_setting();
				}
				if ( 'wqcmv_variant_purchase_extended_get_started_method' === $tab ) {
					wqcmv_get_started_dots_plugin_settings();
				}
				if ( 'wqcmv_introduction_variant_extended' === $tab ) {
					wqcmv_introduction_variant_extended();
				}
			} else {
				wp_redirect( $url );
				exit;
			}
			include_once( 'partials/header/plugin-sidebar.php' );
		}

	}

	function wqcmv_admin_footer_review() {
		echo sprintf( esc_html__( 'If you like %1$s plugin, please leave us ★★★★★ ratings on %2$s.', '' ), '<strong>' . esc_html__( 'Variable Product Purchase Extended Settings', '' ) . '</strong>', '<a href="javascript:void(0);" target="_blank">' . esc_html__( 'WordPress', '' ) . '</a>' );
	}

	/**
	 * Add custom Field in Product general tab meta Section
	 */
	function wqcmv_add_product_visiblity_field() {
		global $post;
		$product = wc_get_product( $post->ID );
		if ( $product->is_type( 'variable' ) ) {
			echo '<div class="options_group">';
			woocommerce_wp_checkbox( array(
				'id'          => 'product_visiblity_option',
				'value'       => get_post_meta( $post->ID, 'product_visiblity_option', true ),
				'label'       => esc_html__( 'Product Visiblity', 'woo-quick-cart-for-multiple-variations' ),
				'desc_tip'    => true,
				'description' => esc_html__( 'Product Visiblity', 'woo-quick-cart-for-multiple-variations' ),
			) );
			echo '</div>';
		}
	}

	/**
	 * @param $id
	 * Save Custom Field in Product general tab meta section
	 */
	function wqcmv_save_product_visiblity_field( $id ) {
		$product_visiblity_option = filter_input( INPUT_POST, 'product_visiblity_option', FILTER_SANITIZE_STRING );
		update_post_meta( $id, 'product_visiblity_option', $product_visiblity_option );
	}

}

/**
 * Function for add custom pointer
 *
 * @return unknown
 */
function wqcmv_variable_product_purchase_extended_setting() {
	$vpe_submit_plugin = filter_input( INPUT_POST, 'vpe_submit_plugin', FILTER_SANITIZE_STRING );

	if ( isset( $vpe_submit_plugin ) ) {

		$variant_extended                                     = filter_input( INPUT_POST, 'variant-extended', FILTER_SANITIZE_STRING );
		$vpe_allow_unavailable_variants                       = filter_input( INPUT_POST, 'vpe_allow_unavailable_variants', FILTER_SANITIZE_STRING );
		$vpe_enable_stock_visibility                          = filter_input( INPUT_POST, 'vpe_enable_stock_visibility', FILTER_SANITIZE_STRING );
		$vpe_enable_price_visibility_for_nonloggedin_customer = filter_input( INPUT_POST, 'vpe_enable_price_visibility_for_nonloggedin_customer', FILTER_SANITIZE_STRING );
		$vpe_variation_per_page_post                          = filter_input( INPUT_POST, 'vpe_variation_per_page', FILTER_SANITIZE_NUMBER_INT );
		$vpe_add_to_cart_button_text_post                     = filter_input( INPUT_POST, 'vpe_add_to_cart_button_text', FILTER_SANITIZE_STRING );
		// verify nonce
		if ( ! isset( $variant_extended ) || ! wp_verify_nonce( $variant_extended, basename( __FILE__ ) ) ) {
			die( 'Failed security check' );
		}

		$allow_unavailable_variants  = isset( $vpe_allow_unavailable_variants ) ? sanitize_text_field( wp_unslash( $vpe_allow_unavailable_variants ) ) : "";
		$enable_stock_visibility     = isset( $vpe_enable_stock_visibility ) ? sanitize_text_field( wp_unslash( $vpe_enable_stock_visibility ) ) : "";
		$enable_price_visibility     = isset( $vpe_enable_price_visibility_for_nonloggedin_customer ) ? sanitize_text_field( wp_unslash( $vpe_enable_price_visibility_for_nonloggedin_customer ) ) : "";
		$vpe_variation_per_page      = isset( $vpe_variation_per_page_post ) ? sanitize_text_field( wp_unslash( $vpe_variation_per_page_post ) ) : "";
		$vpe_add_to_cart_button_text = isset( $vpe_add_to_cart_button_text_post ) ? sanitize_text_field( wp_unslash( $vpe_add_to_cart_button_text_post ) ) : "";

		/**
		 * Filter the variants per page.
		 */
		if ( 0 > $vpe_variation_per_page ) {
			$error_msg[] = esc_html__( 'Variants per page cannot be negative.', 'woo-quick-cart-for-multiple-variations' );
		} elseif ( 0 === $vpe_variation_per_page ) {
			$error_msg[] = esc_html__( 'Variants per page cannot be 0.', 'woo-quick-cart-for-multiple-variations' );
		} elseif ( ! ctype_digit( $vpe_variation_per_page ) ) {
			$error_msg[] = esc_html__( 'Variants per page cannot be a decimal value.', 'woo-quick-cart-for-multiple-variations' );
		}

		/**
		 * Filter the add to cart button text
		 */
		if ( is_numeric( $vpe_add_to_cart_button_text ) ) {
			$error_msg[] = esc_html__( 'Button text cannot be negative.', 'woo-quick-cart-for-multiple-variations' );
		}

		if ( ! empty( $error_msg ) ) {

			$error_html = '<ul>';
			foreach ( $error_msg as $msg ) {
				$error_html .= "<li>{$msg}</li>";
			}
			$error_html .= '<ul>';

			/**
			 * Now display the error message
			 */
			?>
			<div id="message" class="updated error notice is-dismissible"><p><?php echo $error_html; ?></p></div>
			<?php
		} else {


			/**
			 * Everything is fine, proceed to save changes.
			 */
			$allow_unavailable_variants = ! empty( $allow_unavailable_variants ) ? 'yes' : 'no';
			$enable_stock_visibility    = ! empty( $enable_stock_visibility ) ? 'yes' : 'no';
			$enable_price_visibility    = ! empty( $enable_price_visibility ) ? 'yes' : 'no';
			if ( ! empty( $allow_unavailable_variants ) ) {
				update_option( 'vpe_allow_unavailable_variants', $allow_unavailable_variants );
			}
			if ( ! empty( $enable_stock_visibility ) ) {
				update_option( 'vpe_enable_stock_visibility', $enable_stock_visibility );
			}
			if ( ! empty( $enable_price_visibility ) ) {
				update_option( 'vpe_enable_price_visibility_for_nonloggedin_customer', $enable_price_visibility );
			}
			if ( ! empty( $vpe_variation_per_page ) ) {
				update_option( 'vpe_variation_per_page', $vpe_variation_per_page );
			}
			if ( ! empty( $vpe_add_to_cart_button_text ) ) {
				update_option( 'vpe_add_to_cart_button_text', $vpe_add_to_cart_button_text );
			}
			?>
			<div id="message" class="updated inline"><p>
					<strong><?php esc_html_e( 'Your settings have been saved.', 'woo-quick-cart-for-multiple-variations' ); ?></strong>
				</p>
			</div>
			<?php
		}
	}
	?>
	<div class="vpe-table">
		<form id="cw_plugin_form_id" method="post" action="" enctype="multipart/form-data" novalidate="novalidate">
			<?php wp_nonce_field( basename( __FILE__ ), 'variant-extended' ); ?>
			<div class="under-table third-tab">
				<div class="set">
					<h2><?php esc_html_e( 'Variable Product Purchase Extended Settings', 'woo-quick-cart-for-multiple-variations' ); ?></h2>
				</div>
				<table class="table-outer form-table">
					<tbody>
					<tr>
						<td class="ur-1"><?php esc_html_e( "Allow out of stock product", 'woo-quick-cart-for-multiple-variations' ); ?></td>
						<?php
						$allow_unavailable_variants = get_option( 'vpe_allow_unavailable_variants' );
						?>
						<td class="ur-2">
							<input name="vpe_allow_unavailable_variants"
							       id="vpe_allow_unavailable_variants" type="checkbox"
							       class="" value="1" <?php
							if ( 'yes' === $allow_unavailable_variants ) {
								echo 'checked';
							}
							?>>
							<span class="enable_vpe_disctiption_tab"><i
										class="fa fa-question-circle "></i></span>
							<p class="description"
							   style="display:none;"><?php esc_html_e( 'Allow products that are not in stock to be visible in the front.', 'woo-quick-cart-for-multiple-variations' ); ?></p>
						</td>
					</tr>
					<tr>
						<td class="ur-1"><?php esc_html_e( "Enable stock visibility", 'woo-quick-cart-for-multiple-variations' ); ?> </td>
						<?php
						$enable_stock_visibility = get_option( 'vpe_enable_stock_visibility' );
						?>
						<td class="ur-2">
							<input name="vpe_enable_stock_visibility"
							       id="vpe_enable_stock_visibility" type="checkbox" class=""
							       value="1" <?php
							if ( 'yes' === $enable_stock_visibility ) {
								echo 'checked';
							}
							?>>
							<span class="enable_vpe_disctiption_tab"><i
										class="fa fa-question-circle "></i></span>
							<p class="description"
							   style="display:none;"><?php esc_html_e( 'Allow showing the stock number to the customers per variant.', 'woo-quick-cart-for-multiple-variations' ); ?></p>
						</td>
					</tr>
					<tr>
						<?php
						$vpe_variation_per_page = (int) get_option( 'vpe_variation_per_page' );
						?>
						<td class="ur-1"><?php esc_html_e( "Variants per page", 'woo-quick-cart-for-multiple-variations' ); ?> </td>
						<td class="ur-2">
							<input value="<?php echo isset( $vpe_variation_per_page ) ? esc_attr( $vpe_variation_per_page ) : ""; ?>"
							       name="vpe_variation_per_page"
							       id="vpe_variation_per_page" required type="number" placeholder="eg. 1,2,..." min="1">
							<span class="enable_vpe_disctiption_tab"><i
										class="fa fa-question-circle "></i></span>
							<p class="description"
							   style="display:none;"><?php esc_html_e( 'This shows the number of variants that would be visible per page. Default: -1 which means all the variants will be shown', 'woo-quick-cart-for-multiple-variations' ); ?></p>
						</td>
					</tr>
					<tr>
						<?php
						$vpe_add_to_cart_button_text = get_option( 'vpe_add_to_cart_button_text' );
						?>
						<td class="ur-1"><?php esc_html_e( "Add to cart button text", 'woo-quick-cart-for-multiple-variations' ); ?> </td>
						<td class="ur-2">
							<input value="<?php echo isset( $vpe_add_to_cart_button_text ) ? esc_attr( $vpe_add_to_cart_button_text ) : ""; ?>"
							       name="vpe_add_to_cart_button_text"
							       id="vpe_add_to_cart_button_text" type="text" style="" value=""
							       class="" placeholder="">
							<span class="enable_vpe_disctiption_tab"><i
										class="fa fa-question-circle "></i></span>
							<p class="description"
							   style="display:none;"><?php esc_html_e( 'This shows the add to cart button text. Default:Add to cart.', 'woo-quick-cart-for-multiple-variations' ); ?></p>
						</td>
					</tr>
					</tbody>
				</table>
				<p class="submit save-for-later" id="save-for-later">
					<input type="submit" value="<?php esc_html_e( 'Save Changes', 'woo-quick-cart-for-multiple-variations' ); ?>"
					       class="button button-primary" id="vpe_submit_plugin" name="vpe_submit_plugin">
				</p>
			</div>
		</form>
	</div>
	<?php
}

function wqcmv_get_started_dots_plugin_settings() {
	?>
	<div class="vpe-table res-cl">
		<h2><?php esc_html_e( 'Thanks For Installing', 'woo-quick-cart-for-multiple-variations' ); ?></h2>
		<table class="form-table table-outer">
			<tbody>
			<tr>
				<td class="fr-2">
					<p class="block gettingstarted">
						<strong><?php esc_html_e( 'Getting Started', 'variant-purchase-extended' ); ?> </strong>
					</p>

					<p class="block textgetting">
						<?php esc_html_e( 'This plugin brings an intuitive approach to the variable product\'s purchase. Like you see the general format where the customers are restricted to add only one variant at a time to the cart. This plugin breaks all such restrictions and provides a smooth platform where the customers can select multiple variants at a time.', 'woo-quick-cart-for-multiple-variations' ); ?>
					</p>
					<h3><?php esc_html_e( 'Admin Settings', 'woo-quick-cart-for-multiple-variations' ); ?> </h3>
					<p class="block textgetting">
                            <span class="gettingstarted">
                                <img style="border-bottom: 2px solid #E9E9E9;margin-top: 3%;"
                                     src="<?php echo esc_url( WQCMV_PLUGIN_URL . 'admin/images/variant_extended_settings.png' ); ?>"
                                     alt="Variant Extended Settings">
                            </span>
					</p>
					<h3><?php esc_html_e( 'Front View', 'woo-quick-cart-for-multiple-variations' ); ?> </h3>
					<p class="block textgetting">
                            <span class="frontview">
                                <img style="border-bottom: 2px solid #E9E9E9;margin-top: 3%;"
                                     src="<?php echo esc_url( WQCMV_PLUGIN_URL . 'admin/images/front_view.png' ); ?>"
                                     alt="Front View">
                            </span>
					</p>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<?php
}

function wqcmv_introduction_variant_extended() {

	$plugin_name    = WQCMV_PLUGIN_NAME;
	$plugin_version = WQCMV_PLUGIN_VERSION;
	?>
	<div class="vpe-table">
		<form id="cw_plugin_form_id_second">

			<div class="under-table third-tab">
				<div class="set">
					<h2><?php esc_html_e( "Quick info", 'woo-quick-cart-for-multiple-variations' ); ?></h2>
				</div>
				<table class="form-table table-outer">
					<tbody>
					<tr>
						<td class="fr-1"><?php esc_html_e( 'Product Type', 'woo-quick-cart-for-multiple-variations' ); ?></td>
						<td class="fr-2"><?php esc_html_e( 'WooCommerce Plugin', 'woo-quick-cart-for-multiple-variations' ) ?></td>
					</tr>
					<tr>
						<td class="fr-1"><?php esc_html_e( 'Product Name', 'woo-quick-cart-for-multiple-variations' ); ?></td>
						<td class="fr-2"><?php echo esc_attr( $plugin_name ); ?></td>
					</tr>
					<tr>
						<td class="fr-1"><?php esc_html_e( 'Installed Version', 'woo-quick-cart-for-multiple-variations' ); ?></td>
						<td class="fr-2"><?php echo esc_attr( $plugin_version ); ?></td>
					</tr>
					<tr>
						<td class="fr-1"><?php esc_html_e( 'License & Terms of use', 'woo-quick-cart-for-multiple-variations' ); ?></td>
						<td class="fr-2">
							<?php $click_here = '<a href="https://www.thedotstore.com/terms-and-conditions/" target="_blank">' . esc_html__( 'Click here', 'woo-quick-cart-for-multiple-variations' ) . '</a>';
							echo sprintf( esc_html__( '%1$s to view license and terms of use.', 'woo-quick-cart-for-multiple-variations' ), $click_here ); ?>
						</td>
					</tr>
					<tr>
						<td class="fr-1"><?php esc_html_e( 'Help & Support', 'woo-quick-cart-for-multiple-variations' ); ?></td>
						<td class="fr-2">
							<ul style="margin-left: 15px !important;list-style: inherit; ">
								<li>
									<a href="#"><?php esc_html_e( 'Quick Start Guide', 'woo-quick-cart-for-multiple-variations' ); ?></a>
								</li>
								<li>
									<a href="#"><?php esc_html_e( 'Documentation', 'woo-quick-cart-for-multiple-variations' ); ?></a>
								</li>
								<li>
									<a href="<?php echo esc_url( "https://www.thedotstore.com/support/" ); ?>"><?php esc_html_e( 'Support Fourm', 'woo-quick-cart-for-multiple-variations' ) ?></a>
								</li>
							</ul>
						</td>
					</tr>
					<tr>
						<td class="fr-1"><?php esc_html_e( 'Localization', 'woo-quick-cart-for-multiple-variations' ); ?></td>
						<td class="fr-2"><?php esc_html_e( 'English, Spanish', 'woo-quick-cart-for-multiple-variations' ); ?></td>
					</tr>
					</tbody>
				</table>
			</div>
		</form>
	</div>
	<?php
}