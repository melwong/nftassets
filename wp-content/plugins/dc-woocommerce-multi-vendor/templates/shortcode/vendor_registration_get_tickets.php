<?php wc_print_notices(); ?>

<?php

wp_register_style('vandor-dashboard-style2','http://localhost/boothstand/wp-content/plugins/dc-woocommerce-multi-vendor/assets/frontend/css/vendor_dashboard.min.css');

wp_enqueue_style('vandor-dashboard-style2');

?>

<!--Mel: Begin-->
<?php
/*
 * The template for displaying vendor add product
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor-dashboard/product-manager/add-product.php
 *
 * @author 	WC Marketplace
 * @package 	WCMp/Templates
 * @version   3.3.0
 */

 //global $WCMp, $wc_product_attributes;

//$current_vendor_id = apply_filters('wcmp_current_loggedin_vendor_id', get_current_user_id());

// If vendor does not have product submission cap then show message
/* if (is_user_logged_in() && is_user_wcmp_vendor($current_vendor_id) && !current_user_can('edit_products')) {
    ?>
    <div class="col-md-12">
        <div class="panel panel-default">
            <?php _e('You do not have enough permission to submit a new product. Please contact site administrator.', 'dc-woocommerce-multi-vendor'); ?>
        </div>
    </div>
    <?php
    return;
} */

		/* $WCMp->library->load_colorpicker_lib();
        $WCMp->library->load_datepicker_lib();
        $WCMp->library->load_frontend_upload_lib();
        $WCMp->library->load_accordian_lib();
        $WCMp->library->load_select2_lib();

        $suffix = defined( 'WCMP_SCRIPT_DEBUG' ) && WCMP_SCRIPT_DEBUG ? '' : '.min';

        if ( get_wcmp_vendor_settings( 'is_singleproductmultiseller', 'general' ) == 'Enable' ) {
            wp_enqueue_script( 'wcmp_admin_product_auto_search_js', $WCMp->plugin_url . 'assets/admin/js/admin-product-auto-search' . $suffix . '.js', array( 'jquery' ), $WCMp->version, true );
            wp_localize_script( 'wcmp_admin_product_auto_search_js', 'wcmp_admin_product_auto_search_js_params', array(
                'ajax_url'              => admin_url( 'admin-ajax.php' ),
                'search_products_nonce' => wp_create_nonce( 'search-products' ),
            ) );
        }

        if ( ! wp_script_is( 'tiny_mce', 'enqueued' ) ) {
            wp_enqueue_editor();
        }
        // Enqueue jQuery UI and autocomplete
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_enqueue_script( 'wp-a11y' );
        wp_enqueue_script( 'suggest' );
        
        wp_register_script( 'wcmp_product_classify', $WCMp->plugin_url . 'assets/frontend/js/product-classify.js', array( 'jquery', 'jquery-blockui' ), $WCMp->version, true );
        $script_param = array(
            'ajax_url' => $WCMp->ajax_url(),
            'initial_graphic_url' => $WCMp->plugin_url.'assets/images/select-category-graphic.png',
            'i18n' => array(
                'select_cat_list' => __( 'Select a category from the list', 'dc-woocommerce-multi-vendor' )
            )
        );
        wp_enqueue_script( 'wcmp_product_classify' );
        $WCMp->localize_script( 'wcmp_product_classify', apply_filters( 'wcmp_product_classify_script_data_params', $script_param ) );
 */
        //$WCMp->template->get_template( 'vendor-dashboard/product-manager/add-product.php' );
?>



<div class="col-md-12 add-product-outer-wrapper">
    <div class="select-product-cat-wrapper">
        <?php $is_new_listing = isset($_REQUEST['new_listing']) ? true : false;
        $is_cats_hier = isset($_REQUEST['cats_hier']) ? true : false;
        if( ( $is_new_listing && $is_cats_hier ) || !get_wcmp_vendor_settings('is_singleproductmultiseller', 'general') == 'Enable' ) {
        ?>
        <!-- New product list categories hierarchically -->
        <div class="select-cat-step-wrapper">
            <div class="cat-step1" >
                <div class="panel panel-default pannel-outer-heading mt-0">
                    <div class="panel-heading">
                        <h1><span class="primary-color"><span><?php _e( 'Step 1 of', 'dc-woocommerce-multi-vendor' );?></span> <?php _e( '2:', 'dc-woocommerce-multi-vendor' );?></span> <?php _e('Select a product category', 'dc-woocommerce-multi-vendor'); ?></h1>
                        <h3><?php _e('Once a category is assigned to a product, it cannot be altered.', 'dc-woocommerce-multi-vendor'); ?></h3>
                    </div>
                    <div class="panel-body panel-content-padding form-horizontal breadcrumb-panel">
                        <div class="product-search-wrapper categories-search-wrapper">
                            <div class="form-text"><?php _e('Search category', 'dc-woocommerce-multi-vendor'); ?></div>
                            <div class="form-input">
                                <input id="search-categories-keyword" type="text" placeholder="<?php _e('Example: tshirt, music, album etc...', 'dc-woocommerce-multi-vendor'); ?>">
                                <ul id="searched-categories-results" class="list-group">
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default pannel-outer-heading wcmp-categories-level-panel has-scroller"> 
                        <div class="cat-column-scroller cat-left-scroller"><i class="wcmp-font ico-left-arrow-icon"></i></div>
                    <div class="form-horizontal cat-list-holder">
                        <div class="wcmp-product-categories-wrap cat-column-wrapper">
                            <div class="wcmp-product-cat-level 1-level-cat cat-column" data-level="1"  data-mcs-theme="dark">
                                <ul class="wcmp-product-categories 1-level" data-cat-level="1">
                                    <?php echo wcmp_list_categories( apply_filters( 'wcmp_vendor_product_classify_1_level_categories', array(
                                    'taxonomy' => 'product_cat', 
                                    'hide_empty' => false, 
                                    'html_list' => true,
                                    'cat_link'  => 'javascript:void(0)',
                                    ) ) ); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                        <div class="cat-column-scroller cat-right-scroller"><i class="wcmp-font ico-right-arrow-icon"></i></div>
                </div>
            </div>
        </div>
        <?php }else{ ?>
        <!-- List a product by name or gtin -->
        <div class="cat-intro">
            <div class="panel panel-default pannel-outer-heading mt-0"> 
                <div class="panel-body panel-content-padding form-horizontal text-center">
                    <img src="<?php echo $WCMp->plugin_url.'assets/images/add-product-graphic.png'; ?>" alt="">
                    <h1 class="heading-underline"><?php _e('List a New Product', 'dc-woocommerce-multi-vendor'); ?></h1>
                    <div class="serach-product-cat-wrapper">
                        <h2><?php _e('Search from our existing Product Catalog', 'dc-woocommerce-multi-vendor'); ?></h2>
                        <form class="search-pro-by-name-gtin">
                            <input type="text" name="keyword" placeholder="<?php _e('Product name, UPC, ISBN ...', 'dc-woocommerce-multi-vendor'); ?>" class="form-control inline-input search-product-name-gtin-keyword" required>
                            <button type="submit" class="btn btn-default search-product-name-gtin-btn"><?php echo strtoupper(__('Search', 'dc-woocommerce-multi-vendor')); ?></button> 
                        </form>
                        <?php $url = ( get_wcmp_vendor_settings('is_disable_marketplace_plisting', 'general') == 'Enable' ) ? esc_url(wcmp_get_vendor_dashboard_endpoint_url(get_wcmp_vendor_settings('wcmp_edit_product_endpoint', 'vendor', 'general', 'edit-product'))) : '?new_listing=1&cats_hier=1'; ?>
                        <p><?php _e('Not in the catalog?', 'dc-woocommerce-multi-vendor'); ?> <a href="<?php echo $url; ?>" class="cat-step-btn"><?php _e('Create a new product', 'dc-woocommerce-multi-vendor'); ?> <i class="wcmp-font ico-right-arrow-icon"></i></a></p>
                    </div>
                </div>
            </div>
            <div class="panel panel-custom mt-15 product-search-panel searched-products-name-gtin-panel" style="display: block;">
                <div class="panel-heading"><?php _e('Your search results:', 'dc-woocommerce-multi-vendor'); ?></div>
                <div class="panel-body search-result-holder p-0 searched-result-products-name-gtin"></div>
            </div>          
        </div>
        <!-- End List a product by name or gtin -->
        <?php } ?>
        <div class="clearfix"></div>
    </div>
</div>
<?php
do_action('wcmp-frontend-product-manager_template');

//Mel: End
?>

<?php

wcmp_list_a_product_by_name();

function wcmp_list_a_product_by_name() {
        global $WCMp, $wpdb;
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $html = '';
        if(!empty($keyword)){
            $ids = array();
            $posts = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_wcmp_gtin_code' AND meta_value LIKE %s;", esc_sql( '%'.$keyword.'%' ) ) );
            if ( ! $posts ) {
                $data_store = WC_Data_Store::load('product');
                $ids = $data_store->search_products($keyword, '', false);
                $include = array();
                foreach ($ids as $id) {
                    $product = wc_get_product($id);
                    $product_map_id = get_post_meta($id, '_wcmp_spmv_map_id', true);
                    if( $product && $product_map_id ){
                        $results = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wcmp_products_map WHERE product_map_id=%d", $product_map_id) );
                        $product_ids = wp_list_pluck($results, 'product_id');
                        $first_inserted_map_pro_key = array_search(min(wp_list_pluck($results, 'ID')), wp_list_pluck($results, 'ID'));
                        if(isset($product_ids[$first_inserted_map_pro_key])){
                            $include[] = $product_ids[$first_inserted_map_pro_key];
                        }
                    }elseif($product) {
                        $include[] = $id;
                    }
                }

                if ($include) {
                    $ids = array_slice(array_intersect($ids, $include), 0, 10);
                } else {
                    $ids = array();
                }
            }else{
                $unique_gtin_arr = array();
                foreach ($posts as $post_id) {
                    $unique_gtin_arr[$post_id] = get_post_meta($post_id, '_wcmp_gtin_code', true);
                }
                $ids = array_keys(array_unique($unique_gtin_arr));
            }
            
            $product_objects = apply_filters( 'wcmp_list_a_products_objects',array_map('wc_get_product', $ids) );
            //$user_id = get_current_user_id();
			
			//echo "Prod_obj: " . print_r($product_objects);
			
			echo '<div class="cat-intro">
					<div class="panel panel-default pannel-outer-heading mt-0"> 
					<div class="panel panel-custom mt-15 product-search-panel searched-products-name-gtin-panel">
					<div class="panel-heading">'. _e('Your search resultssfsgfgd:', 'dc-woocommerce-multi-vendor') .'</div>
					<div class="panel-body search-result-holder p-0 searched-result-products-name-gtin">';
            
            if (count($product_objects) > 0) {
                foreach ($product_objects as $product_object) {
                    if ($product_object) {
                        $gtin_code = get_post_meta($product_object->get_id(), '_wcmp_gtin_code', true);
                        //if (is_user_wcmp_vendor($user_id) && $WCMp->vendor_caps->vendor_can($product_object->get_type())) {
                            // product cat
                            $product_cats = '';
                            $termlist = array();
                            //$terms = wp_get_post_terms( $product_object->get_id(), 'product_cat', array( 'fields' => 'ids' ) );
                            $terms = get_the_terms($product_object->get_id(), 'product_cat');
                            if (!$terms) {
                                $product_cats = '<span class="na">&ndash;</span>';
                            } else {
                                $terms_arr = array();
                                $terms = apply_filters( 'wcmp_vendor_product_list_row_product_categories', $terms, $product_object );
                                foreach ($terms as $term) {
                                    //$h_term = get_term_by('term_id', $term_id, 'product_cat');
                                    $terms_arr[] = $term->name;
                                }
                                $product_cats = implode(' | ', $terms_arr);
                            }

                            echo '<div class="search-result-clm">'
                            .  $product_object->get_image(apply_filters('wcmp_searched_name_gtin_product_list_image_size', array(98, 98)))
                            . '<div class="result-content">'
                            . '<p><strong>'.rawurldecode($product_object->get_formatted_name()).'</strong></p>'
                            . '<p>'.$product_object->get_price_html().'</p>'
                            . '<p>'.$product_cats.'</p>'
                            . '</div>'
                            . '<a href="'.$product_object->get_slug().'" data-product_id="'.$product_object->get_id().'" class="wcmp-create-pro-duplicate-btn btn btn-default item-sell">'.__('Sell yours', 'dc-woocommerce-multi-vendor').'</a>'
                            . '</div>';
                            
                        //} else {
                            
                        //}
                    }
                }
                
            } else {
                echo '<div class="search-result-clm"><div class="result-content">' . __('No Suggestions found', 'dc-woocommerce-multi-vendor') . "</div></div>";
            }
        }else{
            echo '<div class="search-result-clm"><div class="result-content">' . __('Empty search field! Enter a text to search.', 'dc-woocommerce-multi-vendor') . "</div></div>";
        }
		
		echo '</div>
            </div></div></div>';
	//echo "Keyword: " . $keyword;
	//echo "HTML: " . $html;
	//return $html;
    }
	
?>