<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.thedotstore.com/
 * @since             1.0.0
 * @package           woo-quick-cart-for-multiple-variations
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Quick Cart for Multiple Variations
 * Plugin URI:        https://wordpress.org/plugins/woo-quick-cart-for-multiple-variations/
 * Description:       This plugin extends the variable purchase ability. Allows multiple variants to be purchased at a time.
 * Version:           1.0.2
 * Author:            Thedotstore
 * Author URI:        https://www.thedotstore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-quick-cart-for-multiple-variations
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
define( 'WQCMV_PLUGIN_VERSION', '1.0.2' );

//Plugin URL
if ( ! defined( 'WQCMV_PLUGIN_URL' ) ) {
    define( 'WQCMV_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
//Plugin Path
if ( ! defined( 'WQCMV_PLUGIN_PATH' ) ) {
    define( 'WQCMV_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WQCMV_PLUGIN_NAME' ) ) {
    define('WQCMV_PLUGIN_NAME','WooCommerce Quick Cart for Multiple Variations');
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-variant_purchase_extended-activator.php
 */
function wqcmv_activate_variant_purchase_extended() {
    require_once WQCMV_PLUGIN_PATH . 'includes/class-variant_purchase_extended-activator.php';
    Variant_Purchase_Extended_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-variant_purchase_extended-deactivator.php
 */
function wqcmv_deactivate_variant_purchase_extended() {
    require_once WQCMV_PLUGIN_PATH . 'includes/class-variant_purchase_extended-deactivator.php';
    Variant_Purchase_Extended_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'wqcmv_activate_variant_purchase_extended');
register_deactivation_hook(__FILE__, 'wqcmv_deactivate_variant_purchase_extended');



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wqcmv_run_variant_purchase_extended() {

    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require WQCMV_PLUGIN_PATH . 'includes/class-variant_purchase_extended.php';
    $plugin = new Variant_Purchase_Extended();
    $plugin->run();
}


/**
 * Check plugin requirement on plugins loaded, this plugin requires Gravity Forms to be installed and active.
 *
 * @since    1.0.0
 */
add_action( 'plugins_loaded', 'wqcmv_initialize_plugin' );
function wqcmv_initialize_plugin() {

    $wc_active = in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) );
    if ( current_user_can('activate_plugins') && $wc_active !== true ) {
        add_action('admin_notices', 'wqcmv_plugin_admin_notice');
    } else {
        wqcmv_run_variant_purchase_extended();
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wqcmv_plugin_links' );
    }

}

add_filter( 'plugin_row_meta', 'wqcmv_custom_plugin_row_meta', 10, 2 );

function wqcmv_custom_plugin_row_meta( $links, $file ) {

    if ( strpos( $file, 'woo-quick-cart-for-multiple-variations.php' ) !== false ) {
        $new_links = array(
            'doc' => '<a href="#" target="_blank">Docs</a>',
            'support' => '<a href="https://www.thedotstore.com/support/" target="_blank">Support</a>',
        );

        $links = array_merge( $links, $new_links );
    }

    return $links;
}

/**
 * Settings link on plugin listing page
 */
function wqcmv_plugin_links( $links ) {

    $vpe_links = array(
        '<a href="'.admin_url('admin.php?page=woo-quick-cart-for-multiple-variations&tab=wqcmv_variant_purchase_extended').'">'.__( 'Settings', 'woo-quick-cart-for-multiple-variations' ).'</a>'
    );
    return array_merge( $vpe_links, $links );
}

/**
 * Show admin notice in case of Gravity Forms plugin is missing.
 *
 * @since    1.0.0
 */
function wqcmv_plugin_admin_notice() {

    $vpe_plugin = 'WooCommerce Quick Cart for Multiple Variations';
    $wc_plugin = 'WooCommerce';
    ?>
    <div class="error">
        <p>
            <?php echo sprintf( __( '%1$s is ineffective as it requires %2$s to be installed and active.', 'woo-quick-cart-for-multiple-variations' ), '<strong>' . esc_html( $vpe_plugin ) . '</strong>', '<strong>' . esc_html( $wc_plugin ) . '</strong>' );?>
        </p>
    </div>
    <?php

}

if( !  function_exists( 'debug' ) ) {
    function debug( $params ) {
        echo '<pre>';
        print_r( $params );
        echo '</pre>';
    }
}