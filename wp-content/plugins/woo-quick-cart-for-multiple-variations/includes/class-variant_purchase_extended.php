<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.thedotstore.com/
 * @since      1.0.0
 *
 * @package    woo-quick-cart-for-multiple-variations
 * @subpackage woo-quick-cart-for-multiple-variations/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    woo-quick-cart-for-multiple-variations
 * @subpackage woo-quick-cart-for-multiple-variations/includes
 * @author     Thedotstore <wordpress@thedotstore.com>
 */
class Variant_Purchase_Extended
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Variant_Purchase_Extended_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {

        if (defined('WQCMV_PLUGIN_VERSION')) {
            $this->version = WQCMV_PLUGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'variant_purchase_extended';
        $this->wqcmv_load_dependencies();
        $this->wqcmv_set_locale();
        $this->wqcmv_define_admin_hooks();
        $this->wqcmv_define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Variant_Purchase_Extended_Loader. Orchestrates the hooks of the plugin.
     * - Variant_Purchase_Extended_i18n. Defines internationalization functionality.
     * - Variant_Purchase_Extended_Admin. Defines all hooks for the admin area.
     * - Variant_Purchase_Extended_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function wqcmv_load_dependencies()
    {

        /**
         * Define Static Function From here
         */

        require_once WQCMV_PLUGIN_PATH . 'includes/variant_purchase_extended_functions.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */

        require_once WQCMV_PLUGIN_PATH . 'includes/class-variant_purchase_extended-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once WQCMV_PLUGIN_PATH . 'includes/class-variant_purchase_extended-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once WQCMV_PLUGIN_PATH . 'admin/class-variant_purchase_extended-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once WQCMV_PLUGIN_PATH . 'public/class-variant_purchase_extended-public.php';

        $this->loader = new Variant_Purchase_Extended_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Variant_Purchase_Extended_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function wqcmv_set_locale()
    {

        $plugin_i18n = new Variant_Purchase_Extended_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function wqcmv_define_admin_hooks()
    {

        $plugin_admin = new Variant_Purchase_Extended_Admin($this->get_plugin_name(), $this->get_version());
        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'wqcmv_enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'wqcmv_enqueue_scripts');
        $this->loader->add_action('admin_init', $plugin_admin, 'wqcmv_welcome_variable_purchase_extended_screen_do_activation_redirect');
        if (empty($GLOBALS['admin_page_hooks']['dots_store'])) {
            $this->loader->add_action('admin_menu', $plugin_admin, 'wqcmv_dot_store_menu_traking_fbg');
        }
        $this->loader->add_action("admin_menu", $plugin_admin, "wqcmv_add_new_menu_items_traking_fbg");
        if (!empty($page) && (($page === 'variant-purchase-extended'))) {
            $this->loader->add_filter('admin_footer_text', $plugin_admin, 'wqcmv_admin_footer_review');
        }
        $this->loader->add_action('woocommerce_product_options_general_product_data', $plugin_admin, 'wqcmv_add_product_visiblity_field');
        $this->loader->add_action('woocommerce_process_product_meta', $plugin_admin, 'wqcmv_save_product_visiblity_field', 10, 2);
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function wqcmv_define_public_hooks()
    {

        $plugin_public = new Variant_Purchase_Extended_Public($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'wqcmv_enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'wqcmv_enqueue_scripts');
        $this->loader->add_action('woocommerce_before_variations_form', $plugin_public, 'wqcmv_woocommerce_after_single_product_summary', 10, 0);
        $this->loader->add_action('wp_ajax_nopriv_wqcmv_woocommerce_ajax_add_to_cart', $plugin_public,
            'wqcmv_woocommerce_ajax_add_to_cart'
        );
        $this->loader->add_action('wp_ajax_wqcmv_woocommerce_ajax_add_to_cart', $plugin_public, 'wqcmv_woocommerce_ajax_add_to_cart');
        $this->loader->add_action('wp_ajax_nopriv_wqcmv_products_pagination', $plugin_public,
            'wqcmv_products_pagination'
        );
        $this->loader->add_action('wp_ajax_wqcmv_products_pagination', $plugin_public, 'wqcmv_products_pagination');
        $this->loader->add_action('wp_head', $plugin_public, 'wqcmv_variation_products_css');
        $this->loader->add_action('wp_ajax_nopriv_wqcmv_update_mini_cart', $plugin_public,
            'wqcmv_update_mini_cart'
        );
        $this->loader->add_action('wp_ajax_wqcmv_update_mini_cart', $plugin_public, 'wqcmv_update_mini_cart');
        $this->loader->add_shortcode('vpe-woo-variable-product', $plugin_public, 'wqcmv_shortcode_template');
        $this->loader->add_filter('body_class', $plugin_public, 'wqcmv_body_classes');

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Variant_Purchase_Extended_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}