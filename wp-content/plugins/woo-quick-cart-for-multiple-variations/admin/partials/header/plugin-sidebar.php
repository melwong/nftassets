<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$image_url = WQCMV_PLUGIN_URL . 'admin/images/right_click.png';
?>
<div class="dotstore_plugin_sidebar">

    <div class="dotstore-important-link">
        <h2><span class="dotstore-important-link-title"><?php _e('Important links', 'woo-quick-cart-for-multiple-variations'); ?></span></h2>
        <div class="video-detail important-link">
            <ul>
                <li>
                    <img src="<?php echo esc_url($image_url); ?>" alt="Right Click">
                    <a target="_blank" href="#">
                        <?php _e('Plugin documentation', 'woo-quick-cart-for-multiple-variations'); ?>
                    </a>
                </li> 
                <li>
                    <img src="<?php echo esc_url($image_url); ?>" alt="Right Click">
                    <a target="_blank" href="<?php echo esc_url("https://www.thedotstore.com/support/"); ?>"><?php _e('Support platform', 'woo-quick-cart-for-multiple-variations'); ?></a>
                </li>
                <li>
                    <img src="<?php echo esc_url($image_url); ?>" alt="Right Click">
                    <a target="_blank" href="<?php echo esc_url("https://www.thedotstore.com/suggest-a-feature/"); ?>"><?php _e('Suggest A Feature', 'woo-quick-cart-for-multiple-variations'); ?></a>
                </li>
                <li>
                    <img src="<?php echo esc_url($image_url); ?>" alt="Right Click">
                    <a  target="_blank" href="#"><?php _e('Changelog', 'woo-quick-cart-for-multiple-variations'); ?></a>
                </li>
            </ul>
        </div>
    </div>
    <!-- html for popular plugin !-->
    <div class="dotstore-important-link">
        <h2><span class="dotstore-important-link-title"><?php _e('OUR POPULAR PLUGIN', 'woo-quick-cart-for-multiple-variations'); ?></span></h2>
        <div class="video-detail important-link">
            <ul>
                <li>

                    <img class="sidebar_plugin_icone" src="<?php echo esc_url(WQCMV_PLUGIN_URL . 'admin/images/Advance_Extra_Cost_Plugin_for_WooCommerce_sidebar.png'); ?>" alt="Advance Extra Cost Plugin for WooCommerce sidebar">
                    <a target="_blank" href="<?php echo esc_url("https://www.thedotstore.com/advanced-flat-rate-shipping-method-for-woocommerce"); ?>"><?php _e('Woocommerce Conditional Extra Fees', 'woo-quick-cart-for-multiple-variations'); ?></a>
                </li> 
                <li>
                    <img class="sidebar_plugin_icone" src="<?php echo esc_url(WQCMV_PLUGIN_URL . 'admin/images/Woocommerce_Blocker_Lite_Prevent_fake_orders_and_Blacklist_fraud_customers.png'); ?>" alt="Woocommerce Blocker Lite Prevent fake orders and Blacklist fraud customers">
                    <a  target="_blank" href="<?php echo esc_url("https://www.thedotstore.com/product/woocommerce-blocker-lite-prevent-fake-orders-blacklist-fraud-customers/"); ?>"><?php _e('Woocommerce Blocker', 'woo-quick-cart-for-multiple-variations'); ?></a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php echo esc_url(WQCMV_PLUGIN_URL . 'admin/images/advanced_product_Size_Chart_WooCommerce_icone.png'); ?>" alt="Advanced Product Size Chart WooCommerce icon">
                    <a target="_blank" href="<?php echo esc_url("https://www.thedotstore.com/woocommerce-advanced-product-size-charts/"); ?>"><?php _e('Woocommerce Advanced Product Size Charts', 'woo-quick-cart-for-multiple-variations'); ?></a>
                </li>
                <li>
                    <img class="sidebar_plugin_icone" src="<?php echo esc_url(WQCMV_PLUGIN_URL . 'admin/images/WooCommerce_Enhanced_Ecommerce_Analytics_Integration_with_Conversion_Tracking.png'); ?>" alt="WooCommerce Enhanced Ecommerce Analytics Integration with Conversion Tracking">
                    <a  target="_blank" href="<?php echo esc_url("https://www.thedotstore.com/woocommerce-enhanced-ecommerce-analytics-integration-with-conversion-tracking"); 
                    ?>"><?php _e('Woo Enhanced Ecommerce Analytics Integration', 'woo-quick-cart-for-multiple-variations'); ?></a>
                </li>
                <li>
                    <img  class="sidebar_plugin_icone" src="<?php echo esc_url(WQCMV_PLUGIN_URL . 'admin/images/AMM.png'); ?>" alt="AMM">
                    <a target="_blank" href="<?php echo esc_url("https://www.thedotstore.com/advance-menu-manager-wordpress/"); ?>"><?php _e('Advanced Menu Manager For WordPress', 'woo-quick-cart-for-multiple-variations'); ?></a>
                </li>
            </ul>
        </div>
        <div class="view-button">
            <a class="view_button_dotstore" target="_blank" href="<?php echo esc_url("http://www.thedotstore.com/plugins/"); ?>"><?php _e('VIEW ALL', 'woo-quick-cart-for-multiple-variations'); ?></a>
        </div>
    </div>
</div>
