<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$plugin_name    = WQCMV_PLUGIN_NAME;
$plugin_version = WQCMV_PLUGIN_VERSION;
?>
<div id="dotsstoremain">
	<div class="all-pad">
		<header class="dots-header">
			<div class="dots-logo-main">
				<img src="<?php echo esc_url( WQCMV_PLUGIN_URL . '/admin/images/WSFL.png' ); ?>" alt="WSFL">
			</div>
			<div class="dots-header-right">
				<div class="logo-detail">
					<strong><?php esc_html_e( 'WooCommerce Quick Cart for Multiple Variations' ); ?></strong>
					<span><?php _e( 'Free Version ', 'woo-quick-cart-for-multiple-variations' ); ?><?php echo $plugin_version; ?></span>

				</div>
				<div class="button-dots">
					<span class="support_dotstore_image"><a target="_blank" href="<?php echo esc_url( "https://www.thedotstore.com/support/" ); ?>">
                            <img src="<?php echo esc_url( WQCMV_PLUGIN_URL . '/admin/images/support_new.png' ); ?>" alt="Support New"></a>
                    </span>
				</div>
			</div>
			<?php
			$site_url = "admin.php?page=woo-quick-cart-for-multiple-variations&tab=";
			$tab      = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			if ( 'wqcmv_variant_purchase_extended' === $tab && ! empty( $tab ) ) {
				$wc_variant_purchase_extended = "active";
			} else {
				$wc_variant_purchase_extended = "";
			}

			if ( 'wqcmv_variant_purchase_extended_get_started_method' === $tab && ! empty( $tab ) ) {
				$wc_variant_purchase_get_started_method = "active";
			} else {
				$wc_variant_purchase_get_started_method = "";
			}
			if ( 'wqcmv_introduction_variant_extended' === $tab && ! empty( $tab ) ) {
				$introduction_variant_purchase = "active";
			} else {
				$introduction_variant_purchase = "";
			}
			?>
			<div class="dots-menu-main">
				<nav>
					<ul>
						<li>
							<a class="dotstore_plugin <?php echo esc_attr( $wc_variant_purchase_extended ); ?>"
							   href="<?php echo esc_url( $site_url . 'wqcmv_variant_purchase_extended' ); ?>"><?php _e( 'General Settings', 'woo-quick-cart-for-multiple-variations' ); ?></a>
						</li>
						<li>
							<a class="dotstore_plugin <?php echo esc_attr( $wc_variant_purchase_get_started_method ); ?> <?php echo esc_attr( $introduction_variant_purchase ); ?>"
							   href="<?php echo esc_url( $site_url . 'wqcmv_variant_purchase_extended_get_started_method' ); ?>"><?php _e( 'About Plugin', 'woo-quick-cart-for-multiple-variations' ); ?></a>
							<ul class="sub-menu">
								<li><a class="dotstore_plugin <?php echo esc_attr( $wc_variant_purchase_get_started_method ); ?>"
								       href="<?php echo esc_url( $site_url . 'wqcmv_variant_purchase_extended_get_started_method' ); ?>"><?php _e( 'Getting Started', 'woo-quick-cart-for-multiple-variations' ); ?></a>
								</li>
								<li><a class="dotstore_plugin <?php echo esc_attr( $introduction_variant_purchase ); ?>"
								       href="<?php echo esc_url( $site_url . 'wqcmv_introduction_variant_extended' ); ?>"><?php _e( 'Quick info', 'woo-quick-cart-for-multiple-variations' ); ?></a>
								</li>
							</ul>
						</li>
						<li>
							<a class="dotstore_plugin" href="#"><?php _e( 'Dotstore', 'woo-quick-cart-for-multiple-variations' ); ?></a>
							<ul class="sub-menu">
								<li><a target="_blank"
								       href="<?php echo esc_url( "http://www.thedotstore.com/woocommerce-plugins/" ); ?>"><?php _e( 'WooCommerce Plugins', 'woo-quick-cart-for-multiple-variations' ); ?></a>
								</li>
								<li><a target="_blank"
								       href="<?php echo esc_url( "http://www.thedotstore.com/wordpress-plugins/" ); ?>"><?php _e( 'Wordpress Plugins', 'woo-quick-cart-for-multiple-variations' ); ?></a>
								</li>
								<br>
								<li><a target="_blank"
								       href="<?php echo esc_url( "https://www.thedotstore.com/support" ); ?>"><?php _e( 'Contact Support', 'woo-quick-cart-for-multiple-variations' ); ?></a>
								</li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
		</header>