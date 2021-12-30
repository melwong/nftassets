<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       awais300@gmail.com
 * @since      1.0.0
 *
 * @package    Woo_Multi_Variation_Cart
 * @subpackage Woo_Multi_Variation_Cart/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woo_Multi_Variation_Cart
 * @subpackage Woo_Multi_Variation_Cart/public
 * @author     Awais <awais@gmail.com>
 */
class Woo_Multi_Variation_Cart_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woo_Multi_Variation_Cart_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woo_Multi_Variation_Cart_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/woo-multi-variation-cart-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Woo_Multi_Variation_Cart_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Woo_Multi_Variation_Cart_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/woo-multi-variation-cart-public.js', array('jquery'), $this->version, false);

    }

    /**
     * Add CSS to head
     * @return void
     */
    public function head_enqueue_styles_js() {
        //CSS
        if(@$_GET['mode'] == 'multi-add'):
        ?>
            <style>
                .woocommerce-error {
                    display: none;
                }
            </style>
        <?php
        endif;

        //JS        
        if(is_admin() || !is_product() ) {
            return;
        }

        global $post;
        $product_id = $post->ID;
        $product = wc_get_product($product_id);

        $attr = self::get_attributes($product);
        if(!empty($attr) && $attr['count'] == 1) {
            $name = $attr['name'];
            echo "<script>
                    jQuery(document).ready(function($) {
                        $('.reset_variations').click(function(e) {
                            e.preventDefault();
                            $('#{$name} > option').attr('selected',false);     
                        });


                        $('#{$name} > option').mousedown(function(e) {
                                e.preventDefault();
                                $(this).prop('selected', !$(this).prop('selected'));
                                return false;
                        });
                    });
            </script>";
        }
    }

    /**
     * Woocommerce hook to change the select element
     * @param  $html String
     * @param  $args Array
     * @return String
     */
    public function woocommerce_dropdown_variation_attribute_options_html($html, $args)
    {
        global $product;
        $attr = self::get_attributes($product);
        if(!empty($attr) && $attr['count'] == 1) {
            $name = $attr['name'];
            $html = str_ireplace('name="attribute_' . $name . '"', 'name="attribute_' . $name . '[]" multiple', $html);
            return $html;
        }

        return $html;
    }


    /**
     * De-reigster default JS to make it work with our customizations
     * @return void
     */
    public function wc_deregister_javascript()
    {
        wp_deregister_script('wc-add-to-cart-variation');
    }


    /**
     * Add multi product to cart
     * @return void
     */
    public function wc_variable_products_to_cart()
    {
        if(is_admin() || !is_product() ) {
            return;
        }

        if(!isset($_POST['product_id']) || empty($_POST['product_id']) ) {
            return;
        }

        $product = wc_get_product($_POST['product_id'] );
        $attr = self::get_attributes($product);

        if(!empty($attr) && $attr['count'] == 1) {
            $attr_name = $attr['name'];
            $attr_slug = explode('_', $attr_name)[1];

            $product_id = $_POST['product_id'];
            $quantity   = $_POST['quantity'];

            $product_attrs = $_POST['attribute_' . $attr_name];
            $product_type = WC_Product_Factory::get_product_type($product_id);
            if ($product_type == 'variable' && is_array($product_attrs) && count($product_attrs) > 0 ) {
                foreach ($product_attrs as $key => $variation_option) {
                    if (trim($variation_option) == '') {
                        continue;
                    } else {
                        $variation_id = $this->get_product_variation_id($variation_option, $product_id, $attr_name);
                        file_put_contents('test.log', $variation_id . "\n");
                        $variation    = array(
                            $attr_slug => $variation_option,
                        );

                        WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
                    }

                }

                wp_redirect('/checkout/?mode=multi-add');
                exit;
            }
        }
    }

    /**
     * Get the variation ID
     * @param  String
     * @param  $product_id
     * @return int
     */
    public function get_product_variation_id($attr, $product_id, $attr_name)
    {
        global $wpdb;

        $attr_name = 'attribute_' . $attr_name;

        $query = "SELECT p.ID
        FROM {$wpdb->prefix}posts AS p
        JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
        JOIN {$wpdb->prefix}postmeta AS pm2 ON p.ID = pm2.post_id
        WHERE pm.meta_key = '{$attr_name}'
        AND pm.meta_value LIKE '$attr'
        AND p.post_parent = $product_id";

        return $wpdb->get_var($query);
    }


    /**
     * Get Product attribute info
     * @param  Object $product
     * @return Array
     */
    public static function get_attributes($product) {
        $attr = array();
        if(empty($product)) {
            wp_die('Application error. Object is empty.');
        } else {
            $attr['count'] = count($product->get_attributes());
            $attr_obj = array_shift($product->get_attributes());

            if(!empty($attr_obj)) {
                $reflection = new ReflectionClass($attr_obj);
                $property = $reflection->getProperty('data');
                $property->setAccessible(true);
                $attr_array = $property->getValue($attr_obj);
                foreach ($attr_array as $key => $value) {
                    if($key == 'name') {
                        $attr['name'] = $value;
                        break;
                    }
                }
            }
            return $attr;
        }
    }
}